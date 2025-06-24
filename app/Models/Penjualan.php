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
    public function user() { return $this->belongsTo(User::class); }
    public function cabang() { return $this->belongsTo(Cabang::class); }
    public function details() { return $this->hasMany(PenjualanDetail::class); }
}
