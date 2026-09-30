<?php

namespace Tests\Feature;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Jobs\SendOrderStatusEmailJob;
use App\Models\Order;
use App\Models\OrderAudit;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class OrderStatusNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_order_status_update_dispatches_a_notification_job(): void
    {
        Bus::fake();

        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create(['role' => 'user']);

        $order = Order::create([
            'user_id' => $customer->id,
            'total_amount' => 120.00,
            'status' => 'pending',
            'order_type' => 'takeout',
            'payment_method' => 'cash',
            'payment_status' => 'Unpaid',
        ]);

        $request = Request::create('/admin/orders/' . $order->id . '/status', 'PATCH', ['status' => 'processing']);
        $request->setUserResolver(fn () => $admin);

        $controller = app(AdminController::class);
        $response = $controller->updateOrderStatus($request, $order);

        $this->assertNotNull($response);
        Bus::assertDispatched(SendOrderStatusEmailJob::class, function ($job) use ($order) {
            return $job->orderId === $order->id && $job->status === 'processing';
        });
    }

    public function test_delivered_order_status_label_reads_ready_for_pick_up(): void
    {
        $order = Order::create([
            'user_id' => User::factory()->create(['role' => 'user'])->id,
            'total_amount' => 120.00,
            'status' => 'delivered',
            'order_type' => 'takeout',
            'payment_method' => 'cash',
            'payment_status' => 'Paid',
        ]);

        $this->assertSame('Ready for Pick Up', $order->status_label);
    }

    public function test_customer_order_cards_include_receipt_navigation(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $order = Order::create([
            'user_id' => $user->id,
            'total_amount' => 120.00,
            'status' => 'processing',
            'order_type' => 'takeout',
            'payment_method' => 'cash',
            'payment_status' => 'Paid',
        ]);

        $request = Request::create('/customer/orders', 'GET');
        $request->setUserResolver(fn () => $user);

        $response = app(CustomerController::class)->myOrders($request);

        $this->assertStringContainsString('data-receipt-url="' . route('customer.orders.receipt', $order) . '"', $response->render());
    }

    public function test_admin_review_orders_show_pickup_status_and_receipt_navigation(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $order = Order::create([
            'user_id' => User::factory()->create(['role' => 'user'])->id,
            'total_amount' => 120.00,
            'status' => 'delivered',
            'order_type' => 'takeout',
            'payment_method' => 'cash',
            'payment_status' => 'Paid',
        ]);

        $request = Request::create('/admin/orders', 'GET', ['status' => 'delivered']);
        $request->setUserResolver(fn () => $admin);

        $response = app(AdminController::class)->orders($request);
        $html = $response->render();

        $this->assertStringContainsString('Ready for Pick Up', $html);
        $this->assertStringContainsString('data-receipt-url="' . route('admin.orders.receipt', $order) . '"', $html);
    }

    public function test_admin_review_orders_page_uses_new_order_notification_dot_for_recent_orders(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Order::create([
            'user_id' => User::factory()->create(['role' => 'user'])->id,
            'total_amount' => 120.00,
            'status' => 'pending',
            'order_type' => 'takeout',
            'payment_method' => 'cash',
            'payment_status' => 'Unpaid',
            'created_at' => now()->subMinute(),
            'updated_at' => now()->subMinute(),
        ]);

        $request = Request::create('/admin/orders', 'GET');
        $request->setUserResolver(fn () => $admin);

        $response = app(AdminController::class)->orders($request);
        $html = $response->render();

        $this->assertStringContainsString('new-order-dot', $html);
        $this->assertStringContainsString(route('admin.orders.new-count'), $html);
    }

    public function test_customer_ajax_order_update_count_clears_after_visiting_my_orders(): void
    {
        $customer = User::factory()->create(['role' => 'user']);
        $this->actingAs($customer);

        $initialResponse = $this->getJson('http://localhost/customer/order-updates/count');
        $this->assertSame(200, $initialResponse->status(), $initialResponse->getContent());
        $initialResponse->assertJson(['count' => 0]);

        $order = Order::create([
            'user_id' => $customer->id,
            'total_amount' => 120.00,
            'status' => 'processing',
            'order_type' => 'takeout',
            'payment_method' => 'cash',
            'payment_status' => 'Paid',
        ]);
        OrderAudit::create([
            'order_id' => $order->id,
            'user_id' => $customer->id,
            'actor_id' => User::factory()->create(['role' => 'admin'])->id,
            'actor_role' => 'admin',
            'action' => 'status_changed',
            'message' => 'Status updated to Processing.',
        ]);

        $this->getJson('http://localhost/customer/order-updates/count')->assertOk()->assertJson(['count' => 1]);
        $this->get('http://localhost/customer/orders')->assertOk();
        $this->getJson('http://localhost/customer/order-updates/count')->assertOk()->assertJson(['count' => 0]);
    }

    public function test_customer_product_catalog_includes_description_and_reviews_for_detail_modal(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $product = Product::create([
            'name' => 'Chicken Adobo',
            'category' => 'Meals',
            'description' => 'Savory chicken simmered in soy and vinegar.',
            'price' => 120.00,
            'stock' => 20,
            'day_availability' => 'common',
            'is_available' => true,
        ]);

        $order = Order::create([
            'user_id' => $user->id,
            'total_amount' => 120.00,
            'status' => 'delivered',
            'order_type' => 'takeout',
            'payment_method' => 'cash',
            'payment_status' => 'Paid',
        ]);

        ProductReview::create([
            'product_id' => $product->id,
            'user_id' => $user->id,
            'order_id' => $order->id,
            'rating' => 5,
            'comment' => 'Very tasty and satisfying.',
        ]);

        $request = Request::create('/customer/products', 'GET', ['day' => 'common']);
        $request->setUserResolver(fn () => $user);

        $response = app(CustomerController::class)->productsJson($request);
        $payload = json_decode($response->getContent(), true);

        $this->assertSame('Savory chicken simmered in soy and vinegar.', $payload['items'][0]['description']);
        $this->assertSame('Very tasty and satisfying.', $payload['items'][0]['reviews'][0]['comment']);
    }

    public function test_daily_menu_cards_use_safe_product_id_popup_handlers(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $response = app(CustomerController::class)->home();
        $html = $response->render();

        $this->assertStringContainsString('onclick="openProductModalById(${p.id})"', $html);
        $this->assertStringContainsString('openProductLightboxById(${p.id},${i+1})', $html);
        $this->assertStringNotContainsString('openProductModal(${JSON.stringify(p)})', $html);
        $this->assertStringNotContainsString('id="product-modal-next"', $html);
        $this->assertStringContainsString('setInterval(nextProductModalImage, 5000)', $html);
    }
}
