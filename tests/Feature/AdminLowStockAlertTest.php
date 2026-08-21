<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLowStockAlertTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_products_page_highlights_products_below_low_stock_threshold(): void
    {
        Product::create([
            'name' => 'Spring Roll',
            'category' => 'Appetizer',
            'description' => 'Classic starter',
            'price' => 49.00,
            'stock' => 5,
            'day_availability' => 'common',
            'is_available' => true,
        ]);

        $html = view('admin.products', [
            'products' => Product::with('images')->withCount('reviews')->withAvg('reviews', 'rating')->latest()->paginate(20),
            'categories' => collect(['Appetizer']),
            'search' => null,
            'day' => 'all',
            'category' => 'all',
            'lowStockThreshold' => 10,
        ])->render();

        $this->assertStringContainsString('Low stock', $html);
        $this->assertStringContainsString('Threshold', $html);
    }
}
