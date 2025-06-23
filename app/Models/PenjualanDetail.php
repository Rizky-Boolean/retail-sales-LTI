<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanDetail extends Model
{
    use HasFactory;

    protected $fillable = [
    'penjualan_id', 'sparepart_id', 'qty', 'harga_jual_satuan', 'hpp_satuan',
    ];
    public function sparepart() { return $this->belongsTo(Sparepart::class); }
}
