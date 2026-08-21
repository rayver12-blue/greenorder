<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderAudit;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Revenue stats (only delivered = completed orders)
        $totalRevenue    = Order::where('status', 'delivered')->sum('total_amount');
        $todayRevenue    = Order::where('status', 'delivered')->whereDate('updated_at', today())->sum('total_amount');
        $todayOrders     = Order::whereDate('created_at', today())->count();
        $monthRevenue    = Order::where('status', 'delivered')->whereMonth('updated_at', now()->month)->whereYear('updated_at', now()->year)->sum('total_amount');
        $monthDelivered  = Order::where('status', 'delivered')->whereMonth('updated_at', now()->month)->whereYear('updated_at', now()->year)->count();

        // Counts
        $totalProducts   = Product::count();
        $totalOrders     = Order::count();
        $pendingOrders   = Order::where('status', 'pending')->count();
        $processingOrders = Order::where('status', 'processing')->count();
        $completedOrders = Order::where('status', 'delivered')->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();

        // Recent orders (latest 8)
        $recentOrders = Order::with(['user', 'items.product'])->latest()->limit(8)->get();

        // Chart: last 14 days daily revenue
        $chartData = collect(range(13, 0))->map(function ($daysAgo) {
            $date = now()->subDays($daysAgo);
            $dateString = $date->toDateString();
            return [
                'date'    => $date->format('M j'),
                'revenue' => (float) Order::where('status', 'delivered')
                    ->whereDate('updated_at', $dateString)
                    ->sum('total_amount'),
                'orders'  => Order::whereDate('created_at', $dateString)->count(),
                'sold'    => (int) OrderItem::whereHas('order', function ($q) use ($dateString) {
                    $q->where('status', 'delivered')->whereDate('updated_at', $dateString);
                })->sum('quantity'),
            ];
        });

        // Order status breakdown for donut chart
        $statusBreakdown = [
            ['label' => 'Pending',    'value' => $pendingOrders,    'color' => '#f59e0b'],
            ['label' => 'Processing', 'value' => $processingOrders, 'color' => '#3b82f6'],
            ['label' => 'Delivered',  'value' => $completedOrders,  'color' => '#16a34a'],
            ['label' => 'Cancelled',  'value' => $cancelledOrders,  'color' => '#ef4444'],
        ];

        return view('admin.dashboard', compact(
            'totalRevenue', 'todayRevenue', 'todayOrders',
            'monthRevenue', 'monthDelivered',
            'totalProducts', 'totalOrders',
            'pendingOrders', 'processingOrders', 'completedOrders', 'cancelledOrders',
            'recentOrders', 'chartData', 'statusBreakdown'
        ));
    }

    // ── Products ──────────────────────────────────────────────────────────────

    public function products(Request $request)
    {
        $search   = $request->query('q');
        $day      = $request->query('day', 'all');
        $category = $request->query('category', 'all');
        $lowStockThreshold = (int) config('greenorder.low_stock_threshold', 10);

        $query = Product::with('images')->withCount('reviews')->withAvg('reviews', 'rating');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('category', 'like', '%' . $search . '%');
            });
        }

        if ($day !== 'all') {
            $query->where('day_availability', $day);
        }

        if ($category !== 'all') {
            $query->where('category', $category);
        }

        $products = $query->latest()->paginate(20)->appends($request->query());
        $categories = Product::whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('admin.products', compact('products', 'categories', 'search', 'day', 'category', 'lowStockThreshold'));
    }

    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:100',
            'category'         => 'nullable|string|max:50',
            'description'      => 'nullable|string|max:500',
            'price'            => 'required|numeric|min:0',
            'stock'            => 'required|integer|min:0',
            'day_availability' => 'required|in:common,monday,tuesday,wednesday,thursday,friday,saturday',
            'image'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gallery.*'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $filename = $request->file('image')->hashName();
            $request->file('image')->move(public_path('images/products'), $filename);
            $validated['image'] = 'products/' . $filename;
        }

        $product = Product::create($validated);

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $i => $file) {
                $filename = $file->hashName();
                $file->move(public_path('images/products'), $filename);
                $product->images()->create(['path' => 'products/' . $filename, 'sort_order' => $i]);
            }
        }

        return back()->with('success', 'Product added successfully.');
    }

    public function updateProduct(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:100',
            'category'         => 'nullable|string|max:50',
            'description'      => 'nullable|string|max:500',
            'price'            => 'required|numeric|min:0',
            'stock'            => 'required|integer|min:0',
            'day_availability' => 'required|in:common,monday,tuesday,wednesday,thursday,friday,saturday',
            'image'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gallery.*'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'remove_images'    => 'nullable|array',
            'remove_images.*'  => 'integer',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) @unlink(public_path('images/' . $product->image));
            $filename = $request->file('image')->hashName();
            $request->file('image')->move(public_path('images/products'), $filename);
            $validated['image'] = 'products/' . $filename;
        }

        $product->update($validated);

        // Remove selected gallery images
        if ($request->filled('remove_images')) {
            $toRemove = ProductImage::whereIn('id', $request->remove_images)
                ->where('product_id', $product->id)->get();
            foreach ($toRemove as $img) {
                @unlink(public_path('images/' . $img->path));
                $img->delete();
            }
        }

        // Add new gallery images
        if ($request->hasFile('gallery')) {
            $nextOrder = $product->images()->max('sort_order') + 1;
            foreach ($request->file('gallery') as $i => $file) {
                $filename = $file->hashName();
                $file->move(public_path('images/products'), $filename);
                $product->images()->create(['path' => 'products/' . $filename, 'sort_order' => $nextOrder + $i]);
            }
        }

        return back()->with('success', 'Product updated.');
    }

    public function destroyProduct(Product $product)
    {
        if ($product->image) @unlink(public_path('images/' . $product->image));
        foreach ($product->images as $img) {
            @unlink(public_path('images/' . $img->path));
        }
        $product->delete();
        return back()->with('success', 'Product deleted.');
    }

    public function bulkToggle(Request $request)
    {
        $request->validate([
            'ids'    => 'required|array',
            'ids.*'  => 'integer',
            'action' => 'required|in:enable,disable',
        ]);
        Product::whereIn('id', $request->ids)
            ->update(['is_available' => $request->action === 'enable']);
        $count = count($request->ids);
        return back()->with('success', $count . ' product(s) ' . ($request->action === 'enable' ? 'enabled' : 'disabled') . '.');
    }

    // ── Orders ────────────────────────────────────────────────────────────────

    public function orders(Request $request)
    {
        $status = $request->query('status', 'all');
        $query  = Order::with(['user', 'items.product'])->latest();
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        $orders = $query->paginate(20);

        // Status counts for tab badges
        $counts = [
            'all'        => Order::count(),
            'pending'    => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'delivered'  => Order::where('status', 'delivered')->count(),
            'cancelled'  => Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.orders', compact('orders', 'status', 'counts'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate(['status' => 'required|in:pending,processing,delivered,cancelled']);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        if ($oldStatus === $newStatus) {
            return back()->with('success', 'Order #' . str_pad($order->id, 4, '0', STR_PAD_LEFT) . ' status is already ' . ucfirst($newStatus) . '.');
        }

        DB::transaction(function () use ($order, $oldStatus, $newStatus) {
            $order->update(['status' => $newStatus]);
            $order->load('items.product');

            // if it was not cancelled, and now it is
            if ($oldStatus !== 'cancelled' && $newStatus === 'cancelled') {
                foreach ($order->items as $item) {
                    if ($item->product) {
                        $item->product->increment('stock', $item->quantity);
                        $item->product->decrement('sold_count', $item->quantity);
                    }
                }
            } elseif ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
                foreach ($order->items as $item) {
                    if ($item->product) {
                        $item->product->decrement('stock', $item->quantity);
                        $item->product->increment('sold_count', $item->quantity);
                    }
                }
            }
        });

        OrderAudit::create([
            'order_id'   => $order->id,
            'user_id'    => $order->user_id,
            'actor_id'   => $request->user()->id ?? null,
            'actor_role' => 'admin',
            'action'     => 'status_changed',
            'message'    => 'Status updated to ' . ucfirst($newStatus) . '.',
        ]);

        return back()->with('success', 'Order #' . str_pad($order->id, 4, '0', STR_PAD_LEFT) . ' status updated to ' . ucfirst($request->status) . '.');
    }

    // ── Reports ───────────────────────────────────────────────────────────────

    public function reports()
    {
        $topProducts = Product::withSum(
            ['orderItems as total_sold' => fn($q) => $q->whereHas('order', fn($q) => $q->where('status', 'delivered'))],
            'quantity'
        )->orderByDesc('total_sold')->limit(10)->get();

        $monthlyRevenue = collect(range(11, 0))->map(function ($monthsAgo) {
            $date = now()->subMonths($monthsAgo);
            return [
                'month'   => $date->format('M Y'),
                'revenue' => (float) Order::where('status', 'delivered')
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->sum('total_amount'),
            ];
        });

        return view('admin.reports', compact('topProducts', 'monthlyRevenue'));
    }

    public function customers()
    {
        $users = User::where('role', 'user')
            ->with(['orders.items.product'])
            ->latest()
            ->paginate(20);

        $orderMap = $users->getCollection()
            ->mapWithKeys(function ($user) {
                $orders = $user->orders->sortByDesc('created_at')->values()->map(function ($order) {
                    return [
                        'id'           => $order->id,
                        'status'       => $order->status,
                        'status_label' => $order->status_label,
                        'status_color' => $order->status_color,
                        'total_amount' => (string) $order->total_amount,
                        'order_type'   => $order->order_type,
                        'notes'        => $order->notes,
                        'created_at'   => $order->created_at->format('M j, Y g:i A'),
                        'items'        => $order->items->map(function ($item) {
                            return [
                                'name'       => $item->product?->name ?? 'Unknown',
                                'quantity'   => $item->quantity,
                                'unit_price' => (string) $item->unit_price,
                                'subtotal'   => (string) $item->subtotal,
                            ];
                        })->values(),
                    ];
                });

                return [$user->id => $orders];
            });

        return view('admin.customers', compact('users', 'orderMap'));
    }

    public function profile()
    {
        return view('admin.profile');
    }

    public function exportOrders(Request $request)
    {
        $status = $request->query('status', 'all');
        $from   = $request->query('from');
        $to     = $request->query('to');

        $query = Order::with(['user', 'items.product'])->latest();

        if ($status !== 'all') {
            $query->where('status', $status);
        }
        if ($from) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to) {
            $query->whereDate('created_at', '<=', $to);
        }

        $orders = $query->get();
        $filename = 'orders-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($orders) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Order #', 'Customer', 'Email', 'Mobile', 'Type', 'Items', 'Total (PHP)', 'Payment Method', 'Payment Status', 'Order Status', 'Notes', 'Date']);
            foreach ($orders as $order) {
                $items = $order->items->map(fn($i) => ($i->quantity . 'x ' . ($i->product?->name ?? 'Deleted')))->join('; ');
                fputcsv($handle, [
                    str_pad($order->id, 4, '0', STR_PAD_LEFT),
                    $order->user?->name ?? 'N/A',
                    $order->user?->email ?? 'N/A',
                    $order->user?->mobile ?? 'N/A',
                    $order->order_type === 'dine_in' ? 'Dine In' : 'Takeout',
                    $items,
                    number_format($order->total_amount, 2),
                    $order->payment_method ?? 'N/A',
                    $order->payment_status ?? 'N/A',
                    ucfirst($order->status),
                    $order->notes ?? '',
                    $order->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
