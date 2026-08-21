<?php

namespace Tests\Feature;

use App\Http\Controllers\AdminController;
use App\Jobs\SendOrderStatusEmailJob;
use App\Models\Order;
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
}
