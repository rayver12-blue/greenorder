<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('orders')) {
            return;
        }

        if (!Schema::hasColumn('orders', 'payment_reference')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('payment_reference')->nullable()->after('payment_status');
            });
        }

        if (Schema::hasColumn('orders', 'transaction_reference')) {
            $rows = DB::table('orders')
                ->whereNotNull('transaction_reference')
                ->select('id', 'transaction_reference')
                ->get();

            foreach ($rows as $row) {
                DB::table('orders')
                    ->where('id', $row->id)
                    ->whereNull('payment_reference')
                    ->update(['payment_reference' => $row->transaction_reference]);
            }
        }

        if (Schema::hasColumn('orders', 'payment_token')) {
            $rows = DB::table('orders')
                ->whereNotNull('payment_token')
                ->select('id', 'payment_token')
                ->get();

            foreach ($rows as $row) {
                DB::table('orders')
                    ->where('id', $row->id)
                    ->whereNull('payment_reference')
                    ->update(['payment_reference' => $row->payment_token]);
            }
        }

        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'payment_token')) {
                $table->dropColumn('payment_token');
            }

            if (Schema::hasColumn('orders', 'transaction_reference')) {
                $table->dropColumn('transaction_reference');
            }
        });

        if (!Schema::hasTable('payment_transactions')) {
            Schema::create('payment_transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained()->cascadeOnDelete();
                $table->string('payment_method');
                $table->string('payment_status');
                $table->decimal('amount', 10, 2);
                $table->string('payment_reference')->nullable();
                $table->json('payment_details')->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (!Schema::hasColumn('orders', 'payment_reference')) {
                    $table->string('payment_reference')->nullable();
                }
            });
        }

        if (Schema::hasTable('payment_transactions')) {
            Schema::dropIfExists('payment_transactions');
        }
    }
};
