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
        'is_active',
    ];
    public function stokMasuks()
    {
        return $this->hasMany(StokMasuk::class);
    }
        public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}