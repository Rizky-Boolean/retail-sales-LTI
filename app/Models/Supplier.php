<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Supplier extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'nama_supplier',
        'alamat',
        'kontak',
    ];
    public function stokMasuks()
    {
        return $this->hasMany(StokMasuk::class);
    }
}