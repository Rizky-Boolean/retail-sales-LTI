<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stok_masuks', function (Blueprint $table) {
            // Tambahkan kolom baru setelah supplier_id
            $table->boolean('ppn_dikenakan')->default(false)->after('supplier_id');
        });
    }

    public function down(): void
    {
        Schema::table('stok_masuks', function (Blueprint $table) {
            $table->dropColumn('ppn_dikenakan');
        });
    }
};