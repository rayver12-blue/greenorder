<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_available')->default(true)->after('stock');
            $table->boolean('is_featured')->default(false)->after('is_available');
            $table->decimal('discount_price', 10, 2)->nullable()->after('is_featured');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['is_available', 'is_featured', 'discount_price']);
        });
    }
};
