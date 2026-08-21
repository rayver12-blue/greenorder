<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->enum('day_availability', ['common', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'])->default('common');
            $table->string('image')->nullable();
            $table->unsignedBigInteger('sold_count')->default(0);
            $table->timestamps();

            $table->index('day_availability');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
