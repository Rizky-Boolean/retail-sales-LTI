<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Penjualan extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
    'nomor_nota', 'tanggal_penjualan', 'user_id', 'cabang_id', 'nama_pembeli',
    'ppn_dikenakan', 'total_penjualan', 'total_ppn_penjualan', 'total_final',
    ];
    public function user()
    {
        // Juga sertakan user yang sudah di-soft delete saat mencari relasi
        return $this->belongsTo(User::class)->withTrashed();
    }
    public function cabang() { return $this->belongsTo(Cabang::class); }
    public function details() { return $this->hasMany(PenjualanDetail::class); }
}
