<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distribusi extends Model
{
    use HasFactory;

    protected $fillable = [
    'tanggal_distribusi', 'user_id', 'cabang_id_tujuan', 'total_harga_modal',
    'total_ppn_distribusi', 'total_harga_kirim', 'status'
    ];
    public function cabangTujuan() { return $this->belongsTo(Cabang::class, 'cabang_id_tujuan'); }
    public function user() { return $this->belongsTo(User::class); }
    public function details() { return $this->hasMany(DistribusiDetail::class); }
}
