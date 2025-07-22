<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah tipe kolom enum untuk menambahkan status baru
        DB::statement("ALTER TABLE distribusis CHANGE COLUMN status status ENUM('dikirim', 'diterima', 'ditolak') NOT NULL DEFAULT 'dikirim'");

        Schema::table('distribusis', function (Blueprint $table) {
            // Tambahkan kolom untuk alasan penolakan
            $table->text('alasan_penolakan')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE distribusis CHANGE COLUMN status status ENUM('dikirim', 'diterima') NOT NULL DEFAULT 'dikirim'");
        Schema::table('distribusis', function (Blueprint $table) {
            $table->dropColumn('alasan_penolakan');
        });
    }
};