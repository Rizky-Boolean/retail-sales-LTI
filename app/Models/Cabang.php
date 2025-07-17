<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cabang extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_cabang',
        'alamat',
        'kontak',
    ];

    public function spareparts()
    {
        return $this->belongsToMany(Sparepart::class, 'cabang_sparepart')->withPivot('stok');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Mendefinisikan relasi ke Distribusi (sebagai tujuan).
     */
    public function distribusis()
    {
        return $this->hasMany(Distribusi::class, 'cabang_id_tujuan');
    }

    /**
     * Mendefinisikan relasi ke Penjualan.
     */
    public function penjualans()
    {
        return $this->hasMany(Penjualan::class);
    }
}