<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class CheckoutAndPaymentFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_checkout_stores_cart_session_for_payment(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $product = Product::create([
            'name' => 'Chicken Rice',
            'category' => 'Meals',
            'description' => 'Fresh meal',
            'price' => 120.00,
            'stock' => 12,
            'day_availability' => 'common',
            'is_available' => true,
        ]);

        $request = Request::create('/customer/checkout', 'POST', [
            'cart_data' => json_encode([
                ['id' => $product->id, 'qty' => 2, 'price' => 120.00],
            ]),
            'order_type' => 'takeout',
            'notes' => 'Extra sauce',
        ]);
        $request->setUserResolver(fn () => $user);
        $request->setLaravelSession(Session::driver());

        $response = app(\App\Http\Controllers\CustomerController::class)->checkout($request);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('customer.payment', $response->headers->get('Location') ? 'customer.payment' : '');
        $this->assertNotNull(Session::get('checkout.order'));
        $this->assertSame('takeout', Session::get('checkout.order.order_type'));
    }

    public function test_customer_payment_creates_order_and_payment_transaction(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $product = Product::create([
            'name' => 'Soup Bowl',
            'category' => 'Meals',
            'description' => 'Hot soup',
            'price' => 80.00,
            'stock' => 10,
            'day_availability' => 'common',
            'is_available' => true,
        ]);

        Session::put('checkout.order', [
            'items' => [
                ['id' => $product->id, 'qty' => 1, 'price' => 80.00],
            ],
            'total' => 80.00,
            'order_type' => 'dine_in',
            'notes' => 'No onion',
        ]);

        $request = Request::create('/customer/pay', 'POST', [
            'payment_method' => 'cod',
            'order_type' => 'dine_in',
            'notes' => 'No onion',
        ]);
        $request->setLaravelSession(Session::driver());

        $response = app(\App\Http\Controllers\CustomerController::class)->pay($request);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertDatabaseHas('orders', ['user_id' => $user->id, 'payment_method' => 'Cash on Delivery']);
        $this->assertDatabaseHas('payment_transactions', ['payment_method' => 'Cash on Delivery']);
        $this->assertNull(Session::get('checkout.order'));
        $this->assertGreaterThan(0, Order::count());
    }
}
