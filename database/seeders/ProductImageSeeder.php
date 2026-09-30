<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        Product::where(function ($query) {
            $query->whereNull('image')->orWhere('image', '');
        })->update(['image' => 'food-placeholder.svg']);
    }
}
