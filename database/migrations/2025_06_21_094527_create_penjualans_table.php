<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_xxxxxx_create_penjualans_table.php
    public function up(): void
    {
        Schema::create('penjualans', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_nota')->unique();
            $table->date('tanggal_penjualan');
            $table->foreignId('user_id')->comment('Admin Cabang yang menjual')->constrained('users');
            $table->foreignId('cabang_id')->constrained('cabangs');
            $table->string('nama_pembeli')->default('Customer Retail');
            $table->boolean('ppn_dikenakan')->default(false);
            $table->decimal('total_penjualan', 15, 2);
            $table->decimal('total_ppn_penjualan', 15, 2)->default(0);
            $table->decimal('total_final', 15, 2);
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualans');
    }
};
