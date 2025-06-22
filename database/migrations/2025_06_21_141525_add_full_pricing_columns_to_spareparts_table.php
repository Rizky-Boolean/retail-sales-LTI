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
        Schema::table('spareparts', function (Blueprint $table) {
            // Kolom untuk menyimpan persentase markup
            $table->decimal('markup_persen', 5, 2)->default(0)->after('harga_jual');
            
            // Kolom untuk harga modal dan harga beli terakhir
            $table->decimal('harga_modal_terakhir', 15, 2)->default(0)->after('stok_induk');
            $table->decimal('harga_beli_terakhir', 15, 2)->default(0)->after('harga_modal_terakhir');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spareparts', function (Blueprint $table) {
            $table->dropColumn(['markup_persen', 'harga_modal_terakhir', 'harga_beli_terakhir']);
        });
    }
};
