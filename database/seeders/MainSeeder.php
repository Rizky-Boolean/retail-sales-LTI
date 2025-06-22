<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cabang;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class MainSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat beberapa cabang untuk pengujian
        $cabangA = Cabang::create([
            'nama_cabang' => 'Yamaha Central Lampung',
            'alamat' => 'Jl. Proklamator No. 1, Bandar Jaya'
        ]);

        $cabangB = Cabang::create([
            'nama_cabang' => 'Yamaha Metro Square',
            'alamat' => 'Jl. Jenderal Sudirman No. 10, Metro'
        ]);

        // 2. Buat User Admin Gudang Induk (tidak terikat cabang)
        User::create([
            'name' => 'Admin Gudang Induk',
            'email' => 'admin.induk@yamaha.com',
            'password' => Hash::make('password'),
            'role' => 'admin_induk',
            'cabang_id' => null,
        ]);

        // 3. Buat beberapa User Admin untuk Cabang A
        User::create([
            'name' => 'Budi (Admin Cabang A)',
            'email' => 'budi.cabang.a@yamaha.com',
            'password' => Hash::make('password'),
            'role' => 'admin_cabang',
            'cabang_id' => $cabangA->id,
        ]);

        User::create([
            'name' => 'Ani (Admin Cabang A)',
            'email' => 'ani.cabang.a@yamaha.com',
            'password' => Hash::make('password'),
            'role' => 'admin_cabang',
            'cabang_id' => $cabangA->id,
        ]);

        // 4. Buat satu User Admin untuk Cabang B
        User::create([
            'name' => 'Citra (Admin Cabang B)',
            'email' => 'citra.cabang.b@yamaha.com',
            'password' => Hash::make('password'),
            'role' => 'admin_cabang',
            'cabang_id' => $cabangB->id,
        ]);
    }
}