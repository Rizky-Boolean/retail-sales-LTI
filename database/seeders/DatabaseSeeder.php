<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cabang;
use App\Models\User;
use App\Models\Supplier;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat Cabang
        $cabangA = Cabang::create([
            'nama_cabang' => 'Yamaha Central Lampung',
            'alamat' => 'Jl. Proklamator No. 1, Bandar Jaya'
        ]);
        $cabangB = Cabang::create([
            'nama_cabang' => 'Yamaha Metro Square',
            'alamat' => 'Jl. Jenderal Sudirman No. 10, Metro'
        ]);

        // 2. Buat Supplier
        Supplier::create([
            'nama_supplier' => 'PT. Suku Cadang Jaya',
            'alamat' => 'Jl. Gatot Subroto No. 12, Jakarta',
            'kontak' => '+62215550123'
        ]);
        Supplier::create([
            'nama_supplier' => 'CV. Mitra Otomotif',
            'alamat' => 'Jl. Pahlawan No. 45, Surabaya',
            'kontak' => '+62315550456'
        ]);

        // 3. Buat User Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@yamaha.com',
            'password' => Hash::make('123'),
            'role' => 'super_admin',
            'cabang_id' => null,
        ]);
        
        // 4. Buat User Admin Gudang Induk
        User::create([
            'name' => 'Admin Gudang Induk',
            'email' => 'admin.induk@yamaha.com',
            'password' => Hash::make('123'),
            'role' => 'admin_gudang_induk',
            'cabang_id' => null,
        ]);
        
        // 5. Buat User Admin Gudang Cabang A
        User::create([
            'name' => 'Budi (Admin Cabang A)',
            'email' => 'budi.cabang.a@yamaha.com',
            'password' => Hash::make('123'),
            'role' => 'admin_gudang_cabang',
            'cabang_id' => $cabangA->id,
        ]);

        // 6. Buat User Admin Gudang Cabang B
        User::create([
            'name' => 'Citra (Admin Cabang B)',
            'email' => 'citra.cabang.b@yamaha.com',
            'password' => Hash::make('123'),
            'role' => 'admin_gudang_cabang',
            'cabang_id' => $cabangB->id,
        ]);
    }
}
