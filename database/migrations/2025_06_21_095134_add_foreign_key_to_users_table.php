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
        Schema::table('users', function (Blueprint $table) {
            // Ini adalah perintah untuk membuat relasi/foreign key
            $table->foreign('cabang_id')
                  ->references('id')
                  ->on('cabangs')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Perintah untuk menghapus relasi jika migrasi di-rollback
            $table->dropForeign(['cabang_id']);
        });
    }
};