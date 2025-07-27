<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Sparepart extends Model
{
    use HasFactory, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
    'kode_part',
    'nama_part',
    'kategori',
    'satuan',
    'harga_jual',
    'markup_persen',
    'harga_modal_terakhir',
    'harga_beli_terakhir',
    'stok_induk',
    'is_active',
    ];
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function cabangs()
    {
        return $this->belongsToMany(Cabang::class, 'cabang_sparepart')->withPivot('stok');
    }
}