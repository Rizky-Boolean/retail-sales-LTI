<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class StokMasuk extends Model
{
    use HasFactory, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tanggal_masuk',
        'supplier_id',
        'ppn_dikenakan', // <-- Tambahkan ini
        'user_id',
        'total_pembelian',
        'total_ppn_supplier',
        'total_final',
        'catatan',
    ];

    // Relasi ke Supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Relasi ke User
    public function user()
    {
        // Juga sertakan user yang sudah di-soft delete saat mencari relasi
        return $this->belongsTo(User::class);
    }

    // Relasi ke Detail
    public function details()
    {
        return $this->hasMany(StokMasukDetail::class);
    }
}
