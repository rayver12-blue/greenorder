<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderAudit;
use App\Models\PaymentTransaction;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    public function home()
    {
        $bestSellers = Product::with('images')->orderByDesc('sold_count')->limit(3)->get();
        $wishlistIds = Wishlist::where('user_id', Auth::id())->pluck('product_id')->toArray();
        $categories  = Product::whereNotNull('category')->where('category', '!=', '')->distinct()->orderBy('category')->pluck('category');
        return view('customer.home', compact('bestSellers', 'wishlistIds', 'categories'));
    }

    public function productsJson(Request $request)
    {
        $day      = $request->query('day', 'common');
        $page     = max(1, (int) $request->query('page', 1));
        $q        = $request->query('q', '');
        $cat      = $request->query('category', '');
        $maxPrice = (float) $request->query('max_price', 99999);
        $perPage  = 8;

        $query = Product::with('images')->where('day_availability', $day);

        if ($q) {
            $query->where('name', 'like', '%' . $q . '%');
        }
        if ($cat && $cat !== 'all') {
            $query->where('category', $cat);
        }
        if ($maxPrice < 99999) {
            $query->where('price', '<=', $maxPrice);
        }

        $total       = $query->count();
        $items       = $query->skip(($page - 1) * $perPage)->take($perPage)->get();
        $wishlistIds = Wishlist::where('user_id', Auth::id())->pluck('product_id')->toArray();

        return response()->json([
            'items'     => $items->map(fn($p) => [
                'id'         => $p->id,
                'name'       => $p->name,
                'category'   => $p->category,
                'price'      => (float) $p->price,
                'stock'      => $p->stock,
                'sold_count' => $p->sold_count,
                'avg_rating' => $p->avg_rating,
                'image'      => $p->image,
                'images'     => $p->images->pluck('path')->toArray(),
                'wishlisted' => in_array($p->id, $wishlistIds),
            ]),
            'total'     => $total,
            'page'      => $page,
            'per_page'  => $perPage,
            'last_page' => (int) ceil($total / $perPage),
        ]);
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'cart_data'  => ['required', 'json'],
            'order_type' => ['required', 'in:dine_in,takeout'],
            'notes'      => ['nullable', 'string', 'max:300'],
        ]);

        $cartItems = json_decode($request->cart_data, true);
        if (empty($cartItems)) {
            return back()->with('error', 'Your cart is empty.');
        }

        $total = 0.0;
        foreach ($cartItems as $item) {
            $price = (float) ($item['price'] ?? 0);
            $qty   = max(1, (int) ($item['qty'] ?? 1));
            $total += $price * $qty;
        }

        $request->session()->put('checkout.order', [
            'items'      => $cartItems,
            'total'      => round($total, 2),
            'order_type' => $request->order_type,
            'notes'      => $request->filled('notes') ? trim($request->notes) : null,
        ]);

        return redirect()->route('customer.payment');
    }

    public function showPayment(Request $request)
    {
        $checkout = $request->session()->get('checkout.order', []);
        if (empty($checkout['items'] ?? [])) {
            return redirect()->route('customer.home')->with('error', 'Your cart is empty.');
        }

        return view('customer.payment', [
            'checkout' => $checkout,
            'total'    => (float) ($checkout['total'] ?? 0),
        ]);
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'cart_data'      => ['required', 'json'],
            'order_type'     => ['required', 'in:dine_in,takeout'],
            'notes'          => ['nullable', 'string', 'max:300'],
            'payment_method' => ['nullable', 'in:gcash,card,cod'],
        ]);

        $cartItems = json_decode($request->cart_data, true);
        if (empty($cartItems)) {
            return back()->with('error', 'Your cart is empty.');
        }

        $requested = [];
        foreach ($cartItems as $item) {
            $id  = (int) ($item['id'] ?? 0);
            $qty = max(1, (int) ($item['qty'] ?? 1));
            if ($id > 0) {
                $requested[$id] = ($requested[$id] ?? 0) + $qty;
            }
        }

        $products = Product::whereIn('id', array_keys($requested))->get()->keyBy('id');
        $noStock  = [];

        foreach ($requested as $id => $qty) {
            $product = $products->get($id);
            if (!$product || $product->stock <= 0 || $product->stock < $qty) {
                $noStock[] = $product?->name ?? ('Item #' . $id);
            }
        }

        if (!empty($noStock)) {
            return redirect()->route('customer.orders')
                ->with('error', 'Order auto-cancelled. Out of stock: ' . implode(', ', array_unique($noStock)) . '.');
        }

        $order = DB::transaction(function () use ($cartItems, $request, $products) {
            $total          = 0;
            $orderItemsData = [];

            foreach ($cartItems as $item) {
                $product = $products->get((int) ($item['id'] ?? 0));
                if (!$product) continue;

                $qty      = max(1, (int) ($item['qty'] ?? 1));
                $price    = (float) $product->price;
                $subtotal = round($price * $qty, 2);
                $total   += $subtotal;

                $orderItemsData[] = [
                    'product_id' => $product->id,
                    'quantity'   => $qty,
                    'unit_price' => $price,
                    'subtotal'   => $subtotal,
                ];

                $product->increment('sold_count', $qty);
                $product->decrement('stock', $qty);
            }

            $paymentMethod = match ($request->input('payment_method', 'cod')) {
                'gcash' => 'GCash',
                'card'  => 'Credit/Debit Card',
                default => 'Cash on Delivery',
            };

            $paymentStatus = $paymentMethod === 'Cash on Delivery' ? 'COD - Payment Pending' : 'Pending';

            $order = Order::create([
                'user_id'           => Auth::id(),
                'total_amount'      => round($total, 2),
                'status'            => 'pending',
                'order_type'        => $request->order_type,
                'notes'             => $request->filled('notes') ? trim($request->notes) : null,
                'payment_method'    => $paymentMethod,
                'payment_status'    => $paymentStatus,
                'payment_reference' => null,
            ]);

            foreach ($orderItemsData as $data) {
                $order->items()->create($data);
            }

            if ($request->filled('payment_method')) {
                $order->paymentTransactions()->create([
                    'payment_method'    => $paymentMethod,
                    'payment_status'    => $paymentStatus,
                    'amount'            => round($total, 2),
                    'payment_reference' => $paymentMethod === 'Cash on Delivery' ? 'COD-' . $order->id . '-' . Str::upper(Str::random(6)) : null,
                    'paid_at'           => $paymentMethod === 'Cash on Delivery' ? null : now(),
                ]);
            }

            return $order;
        });

        OrderAudit::create([
            'order_id'   => $order->id,
            'user_id'    => $order->user_id,
            'actor_id'   => $order->user_id,
            'actor_role' => 'user',
            'action'     => 'created',
            'message'    => 'Order placed.' . ($order->payment_method ? ' Payment method: ' . $order->payment_method . '.' : ''),
        ]);

        return redirect()->route('customer.orders')
            ->with('success', 'Order #' . str_pad($order->id, 4, '0', STR_PAD_LEFT) . ' placed successfully! 🎉');
    }

    public function pay(Request $request)
    {
        $request->validate([
            'payment_method'  => ['required', 'in:gcash,card,cod'],
            'amount'          => ['nullable', 'numeric', 'min:0.01'],
            'gcash_mobile'    => ['nullable', 'required_if:payment_method,gcash', 'regex:/^09[0-9]{9}$/'],
            'gcash_name'      => ['nullable', 'required_if:payment_method,gcash', 'string', 'min:2', 'max:100'],
            'cardholder_name' => ['nullable', 'required_if:payment_method,card', 'string', 'min:2', 'max:100'],
            'card_number'     => ['nullable', 'required_if:payment_method,card', 'string', 'max:19'],
            'expiration_date' => ['nullable', 'required_if:payment_method,card', 'string', 'max:10'],
            'cvv'             => ['nullable', 'required_if:payment_method,card', 'digits:3'],
        ]);

        $checkout = $request->session()->get('checkout.order', []);
        $items    = $checkout['items'] ?? json_decode((string) $request->input('cart_data', '[]'), true);
        $total    = (float) ($checkout['total'] ?? 0);

        if (empty($items)) {
            return back()->with('error', 'Your cart is empty.');
        }

        if ($total <= 0 && !empty($items)) {
            $total = 0.0;
            foreach ($items as $item) {
                $total += (float) ($item['price'] ?? 0) * max(1, (int) ($item['qty'] ?? 1));
            }
        }

        $paymentMethod = match ($request->payment_method) {
            'gcash' => 'GCash',
            'card'  => 'Credit/Debit Card',
            default => 'Cash on Delivery',
        };

        $orderType = $checkout['order_type'] ?? $request->input('order_type', 'dine_in');
        $notes     = $checkout['notes'] ?? $request->input('notes');

        if ($request->payment_method === 'cod') {
            $paymentStatus = 'COD - Payment Pending';
            $status        = 'pending';
            $reference     = 'COD-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(6));
        } else {
            $amount = (float) ($request->input('amount', $total));
            if (abs($amount - $total) > 0.01) {
                return back()->withInput()->with('error', 'The payment amount must match the order total.');
            }

            $paymentStatus = 'Paid';
            $status        = 'pending';
            $reference     = strtoupper($request->payment_method) . '-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(6));

            if ($request->payment_method === 'card') {
                $cardNumber = preg_replace('/\D+/', '', (string) $request->input('card_number', ''));
                if (strlen($cardNumber) < 13 || strlen($cardNumber) > 19) {
                    return back()->withInput()->with('error', 'Please enter a valid demo card number.');
                }
            }
        }

        $order = DB::transaction(function () use ($request, $items, $total, $orderType, $notes, $paymentMethod, $paymentStatus, $reference, $status) {
            $order = Order::create([
                'user_id'           => Auth::id(),
                'total_amount'      => round($total, 2),
                'status'            => $status,
                'order_type'        => $orderType,
                'notes'             => $notes,
                'payment_method'    => $paymentMethod,
                'payment_status'    => $paymentStatus,
                'payment_reference' => $reference,
                'paid_at'           => $paymentStatus === 'Paid' ? now() : null,
            ]);

            foreach ($items as $item) {
                $product = Product::find((int) ($item['id'] ?? 0));
                if (!$product) continue;

                $qty       = max(1, (int) ($item['qty'] ?? 1));
                $unitPrice = (float) ($item['price'] ?? $product->price);
                $subtotal  = round($unitPrice * $qty, 2);

                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity'   => $qty,
                    'unit_price' => $unitPrice,
                    'subtotal'   => $subtotal,
                ]);

                $product->increment('sold_count', $qty);
                $product->decrement('stock', $qty);
            }

            $paymentDetails = ['reference' => $reference, 'amount' => round($total, 2)];

            if ($request->payment_method === 'gcash') {
                $paymentDetails['gcash_mobile'] = $request->input('gcash_mobile');
                $paymentDetails['account_name'] = $request->input('gcash_name');
            }
            if ($request->payment_method === 'card') {
                $paymentDetails['cardholder_name'] = $request->input('cardholder_name');
                $paymentDetails['card_last4']      = substr(preg_replace('/\D+/', '', (string) $request->input('card_number', '')), -4);
            }

            $order->paymentTransactions()->create([
                'payment_method'    => $paymentMethod,
                'payment_status'    => $paymentStatus,
                'amount'            => round($total, 2),
                'payment_reference' => $reference,
                'payment_details'   => json_encode($paymentDetails),
                'paid_at'           => $paymentStatus === 'Paid' ? now() : null,
            ]);

            return $order;
        });

        $message = match ($request->payment_method) {
            'gcash' => 'Payment Successful (Simulation) for Order #' . str_pad($order->id, 4, '0', STR_PAD_LEFT) . '. This is a simulated payment. No real money was transferred.',
            'card'  => 'Card Payment Successful (Simulation) for Order #' . str_pad($order->id, 4, '0', STR_PAD_LEFT) . '. This is a simulated payment. No real money was charged.',
            default => 'Cash on Delivery selected. Order #' . str_pad($order->id, 4, '0', STR_PAD_LEFT) . ' is awaiting payment on delivery.',
        };

        OrderAudit::create([
            'order_id'   => $order->id,
            'user_id'    => $order->user_id,
            'actor_id'   => $order->user_id,
            'actor_role' => 'user',
            'action'     => 'payment_processed',
            'message'    => $message,
        ]);

        $request->session()->forget('checkout.order');

        return redirect()->route('customer.orders')->with('success', $message);
    }

    public function myOrders(Request $request)
    {
        $status = $request->query('status', 'all');
        $search = $request->query('q');

        $query = Order::with(['items.product', 'audits'])
            ->where('user_id', Auth::id())
            ->latest();

        if ($status !== 'all') {
            $query->where('status', $status);
        }
        if ($search) {
            $query->where('id', 'like', '%' . ltrim($search, '#0') . '%');
        }

        $orders = $query->paginate(10)->appends($request->query());

        $reviewMap = ProductReview::where('user_id', Auth::id())
            ->whereIn('order_id', $orders->pluck('id'))
            ->get()
            ->groupBy('order_id')
            ->map(fn($g) => $g->keyBy('product_id'));

        $counts = [
            'all'        => Order::where('user_id', Auth::id())->count(),
            'pending'    => Order::where('user_id', Auth::id())->where('status', 'pending')->count(),
            'processing' => Order::where('user_id', Auth::id())->where('status', 'processing')->count(),
            'delivered'  => Order::where('user_id', Auth::id())->where('status', 'delivered')->count(),
            'cancelled'  => Order::where('user_id', Auth::id())->where('status', 'cancelled')->count(),
        ];

        return view('customer.orders', compact('orders', 'status', 'search', 'counts', 'reviewMap'));
    }

    public function orderReceipt(Order $order)
    {
        abort_if($order->user_id !== Auth::id(), 403);
        $order->load('items.product', 'user');
        return view('customer.receipt', compact('order'));
    }

    public function orderDetail(Order $order)
    {
        abort_if($order->user_id !== Auth::id(), 403);
        $order->load('items.product');
        return view('customer.order-detail', compact('order'));
    }

    public function storeReview(Request $request)
    {
        $request->validate([
            'order_id'   => ['required', 'integer', 'exists:orders,id'],
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'rating'     => ['required', 'integer', 'min:1', 'max:5'],
            'comment'    => ['nullable', 'string', 'max:500'],
        ]);

        $order = Order::where('id', $request->order_id)
            ->where('user_id', Auth::id())
            ->where('status', 'delivered')
            ->firstOrFail();

        abort_if(!$order->items()->where('product_id', $request->product_id)->exists(), 403);

        ProductReview::updateOrCreate(
            ['order_id' => $order->id, 'user_id' => Auth::id(), 'product_id' => $request->product_id],
            ['rating' => $request->rating, 'comment' => $request->filled('comment') ? trim($request->comment) : null]
        );

        return back()->with('success', 'Review submitted!');
    }

    public function toggleWishlist(Request $request, Product $product)
    {
        $existing = Wishlist::where('user_id', Auth::id())->where('product_id', $product->id)->first();
        if ($existing) {
            $existing->delete();
            $wishlisted = false;
        } else {
            Wishlist::create(['user_id' => Auth::id(), 'product_id' => $product->id]);
            $wishlisted = true;
        }
        return response()->json(['wishlisted' => $wishlisted]);
    }

    public function activeOrderStatuses()
    {
        $orders = Order::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'processing'])
            ->get(['id', 'status']);
        return response()->json($orders->mapWithKeys(fn($o) => [$o->id => $o->status]));
    }

    public function cancelOrder(Request $request, Order $order)
    {
        abort_if($order->user_id !== Auth::id(), 403);

        if ($order->status === 'cancelled') {
            return back()->with('success', 'Order #' . str_pad($order->id, 4, '0', STR_PAD_LEFT) . ' is already cancelled.');
        }

        if (!in_array($order->status, ['pending', 'processing'], true)) {
            return back()->with('error', 'Only pending or processing orders can be cancelled.');
        }

        DB::transaction(function () use ($order) {
            $order->update(['status' => 'cancelled']);
            $order->load('items.product');
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                    $item->product->decrement('sold_count', $item->quantity);
                }
            }
        });

        OrderAudit::create([
            'order_id'   => $order->id,
            'user_id'    => $order->user_id,
            'actor_id'   => $order->user_id,
            'actor_role' => 'user',
            'action'     => 'cancelled',
            'message'    => 'Order cancelled by customer.',
        ]);

        return back()->with('success', 'Order #' . str_pad($order->id, 4, '0', STR_PAD_LEFT) . ' has been cancelled.');
    }
}
