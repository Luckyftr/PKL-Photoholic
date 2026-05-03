<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Kita tambahkan kolom address dan photo
            $table->text('address')->nullable()->after('phone'); 
            $table->string('photo')->nullable()->after('address');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Ini untuk jaga-jaga kalau kita ingin membatalkan migrasi
            $table->dropColumn(['address', 'photo']);
        });
    }
};
