<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Membuat tabel Users dengan struktur yang sudah digabung (bersih)
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            
            // Kolom google_id untuk fitur login Google (boleh kosong)
            $table->string('google_id')->nullable(); 
            
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone')->nullable(); 
            
            // Tambahan: Kolom alamat dan foto (boleh kosong)
            $table->text('address')->nullable(); 
            $table->string('photo')->nullable();
            
            $table->enum('role', ['admin', 'customer'])->default('customer');
            $table->enum('status', ['active', 'inactive'])->default('active');
            
            // Password dibuat nullable karena user dari Google Login tidak mengisi password
            $table->string('password')->nullable(); 
            
            $table->rememberToken();       
            $table->timestamps();
        });

        // 2. Membuat tabel Password Reset Tokens (Bawaan Laravel)
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // 3. Membuat tabel Sessions (Bawaan Laravel)
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Menghapus tabel jika migrasi di-rollback
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};