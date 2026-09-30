<?php

namespace Tests\Feature;

use App\Models\Product;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\ProductImageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductImageSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_fills_missing_product_images_without_overwriting_existing_images(): void
    {
        $missingImage = Product::create([
            'name' => 'Image Needed',
            'description' => 'A product without an image.',
            'price' => 50,
        ]);
        $existingImage = Product::create([
            'name' => 'Has Image',
            'description' => 'A product with its own image.',
            'price' => 60,
            'image' => 'products/dish-photo.jpg',
        ]);

        app(ProductImageSeeder::class)->run();

        $this->assertSame('food-placeholder.svg', $missingImage->fresh()->image);
        $this->assertSame('products/dish-photo.jpg', $existingImage->fresh()->image);
        $this->assertFileExists(public_path('images/food-placeholder.svg'));
    }

    public function test_database_seeder_assigns_an_image_to_every_sample_product(): void
    {
        app(DatabaseSeeder::class)->run();

        $this->assertSame(0, Product::whereNull('image')->count());
        $this->assertSame(0, Product::where('image', '')->count());
    }
}
