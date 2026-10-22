<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', ['pending', 'processing', 'delivered', 'done', 'cancelled'])
                ->default('pending')
                ->change();
        });
    }

    public function down(): void
    {
        DB::table('orders')->where('status', 'done')->update(['status' => 'delivered']);

        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', ['pending', 'processing', 'delivered', 'cancelled'])
                ->default('pending')
                ->change();
        });
    }
};