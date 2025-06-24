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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            // User yang melakukan aksi (bisa null jika aksi dari sistem)
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            // Deskripsi aksi yang dilakukan
            $table->string('description');
            // Alamat IP pengguna
            $table->ipAddress('ip_address')->nullable();
            // Informasi browser/perangkat pengguna
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
