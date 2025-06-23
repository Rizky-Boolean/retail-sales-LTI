<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_xxxxxx_create_distribusi_details_table.php
    public function up(): void
    {
        Schema::create('distribusi_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('distribusi_id')->constrained('distribusis')->onDelete('cascade');
            $table->foreignId('sparepart_id')->constrained('spareparts')->onDelete('cascade');
            $table->integer('qty');
            $table->decimal('harga_modal_satuan', 15, 2)->comment('Harga modal dari gudang induk');
            $table->decimal('harga_kirim_satuan', 15, 2)->comment('Harga modal + PPN 11%');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distribusi_details');
    }
};
