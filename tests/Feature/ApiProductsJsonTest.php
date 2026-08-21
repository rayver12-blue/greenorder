<?php

namespace Tests\Feature;

use App\Http\Controllers\CustomerController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class ApiProductsJsonTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_products_endpoint_returns_json_product_data(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $request = Request::create('/api/products', 'GET', ['day' => 'common', 'page' => 1]);
        $request->setUserResolver(fn () => $user);

        $response = app(CustomerController::class)->productsJson($request);

        $this->assertSame(200, $response->getStatusCode());
        $payload = $response->getData(true);

        $this->assertArrayHasKey('items', $payload);
        $this->assertArrayHasKey('total', $payload);
        $this->assertArrayHasKey('page', $payload);
        $this->assertArrayHasKey('per_page', $payload);
        $this->assertArrayHasKey('last_page', $payload);
    }
}
