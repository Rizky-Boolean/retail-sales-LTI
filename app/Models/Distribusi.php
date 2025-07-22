<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Distribusi extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
    'tanggal_distribusi', 'user_id', 'cabang_id_tujuan', 'total_harga_modal',
    'total_ppn_distribusi', 'total_harga_kirim', 'status','alasan_penolakan',
    ];
    public function cabangTujuan() { return $this->belongsTo(Cabang::class, 'cabang_id_tujuan'); }
    public function user()
    {
        // Juga sertakan user yang sudah di-soft delete saat mencari relasi
        return $this->belongsTo(User::class)->withTrashed();
    }
    public function details() { return $this->hasMany(DistribusiDetail::class); }
}
