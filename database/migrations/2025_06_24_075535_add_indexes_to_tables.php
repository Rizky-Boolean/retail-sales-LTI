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
        // Menambahkan index ke tabel yang sering di-filter
        
        Schema::table('users', function (Blueprint $table) {
            $table->index('role');
            $table->index('cabang_id');
        });

        Schema::table('stok_masuks', function (Blueprint $table) {
            $table->index('supplier_id');
            $table->index('tanggal_masuk');
        });

        Schema::table('distribusis', function (Blueprint $table) {
            $table->index('cabang_id_tujuan');
            $table->index('status');
        });

        Schema::table('penjualans', function (Blueprint $table) {
            $table->index('cabang_id');
            $table->index('tanggal_penjualan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Logika untuk menghapus index jika migrasi di-rollback
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropIndex(['cabang_id']);
        });

        Schema::table('stok_masuks', function (Blueprint $table) {
            $table->dropIndex(['supplier_id']);
            $table->dropIndex(['tanggal_masuk']);
        });

        Schema::table('distribusis', function (Blueprint $table) {
            $table->dropIndex(['cabang_id_tujuan']);
            $table->dropIndex(['status']);
        });

        Schema::table('penjualans', function (Blueprint $table) {
            $table->dropIndex(['cabang_id']);
            $table->dropIndex(['tanggal_penjualan']);
        });
    }
};