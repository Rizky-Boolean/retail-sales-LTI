<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('spareparts', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('satuan');
        });
        Schema::table('suppliers', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('kontak');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('role');
        });
        Schema::table('cabangs', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('alamat');
        });
    }
    public function down(): void
    {
        Schema::table('spareparts', function (Blueprint $table) { $table->dropColumn('is_active'); });
        Schema::table('suppliers', function (Blueprint $table) { $table->dropColumn('is_active'); });
        Schema::table('users', function (Blueprint $table) { $table->dropColumn('is_active'); });
        Schema::table('cabangs', function (Blueprint $table) { $table->dropColumn('is_active'); });
    }
};