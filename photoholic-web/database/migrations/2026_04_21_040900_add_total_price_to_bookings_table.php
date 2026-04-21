<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Menambahkan kolom total_price dengan tipe integer (atau ganti decimal jika pakai desimal)
            // after('payment_method') menaruh kolom ini setelah kolom payment_method
            $table->integer('total_price')->after('payment_method')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Menghapus kolom jika migration di-rollback
            $table->dropColumn('total_price'); 
        });
    }
};