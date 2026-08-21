<?php
// ── 2024_01_01_000001_create_users_table.php ─────────────────────────────────
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 30)->unique();
            $table->string('name', 100);
            $table->string('email', 255)->unique()->nullable();
            $table->date('birthdate');
            $table->string('mobile', 15)->unique();
            $table->enum('role', ['user', 'admin'])->default('user');
            $table->string('password');
            $table->rememberToken();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();

            $table->index('role');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
