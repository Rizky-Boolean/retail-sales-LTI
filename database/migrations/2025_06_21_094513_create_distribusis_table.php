<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_xxxxxx_create_distribusis_table.php
    public function up(): void
    {
        Schema::create('distribusis', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_distribusi');
            $table->foreignId('user_id')->comment('Admin Induk yang mengirim')->constrained('users');
            $table->foreignId('cabang_id_tujuan')->constrained('cabangs');
            $table->decimal('total_harga_modal', 15, 2);
            $table->decimal('total_ppn_distribusi', 15, 2);
            $table->decimal('total_harga_kirim', 15, 2);
            $table->enum('status', ['dikirim', 'diterima'])->default('dikirim');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distribusis');
    }
};
