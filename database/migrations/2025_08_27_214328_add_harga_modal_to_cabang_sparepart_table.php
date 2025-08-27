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
        Schema::table('cabang_sparepart', function (Blueprint $table) {
            // Tambahkan kolom untuk harga modal setelah kolom 'stok'
            $table->decimal('harga_modal', 15, 2)->default(0)->after('stok');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cabang_sparepart', function (Blueprint $table) {
            // Hapus kolom jika migrasi di-rollback
            $table->dropColumn('harga_modal');
        });
    }
};