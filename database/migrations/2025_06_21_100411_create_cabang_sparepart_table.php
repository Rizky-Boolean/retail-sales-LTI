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
        Schema::create('cabang_sparepart', function (Blueprint $table) {
            $table->foreignId('cabang_id')->constrained('cabangs')->onDelete('cascade');
            $table->foreignId('sparepart_id')->constrained('spareparts')->onDelete('cascade');
            $table->integer('stok')->default(0);

            // Membuat kedua kolom menjadi primary key
            $table->primary(['cabang_id', 'sparepart_id']);

            // [START] TAMBAHKAN BARIS INI
            $table->timestamps();
            // [END] TAMBAHKAN BARIS INI
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cabang_sparepart');
    }
};
