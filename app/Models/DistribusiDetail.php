<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistribusiDetail extends Model
{
    use HasFactory;

    protected $fillable = [
    'distribusi_id', 'sparepart_id', 'qty', 'harga_modal_satuan', 'harga_kirim_satuan'
    ];
    public function sparepart() { return $this->belongsTo(Sparepart::class); }
}
