<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokMasukDetail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'stok_masuk_id',
        'sparepart_id',
        'qty',
        'harga_beli_satuan',
        'harga_modal_satuan',
    ];

    /**
     * Mendefinisikan relasi ke Sparepart.
     */
    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class);
    }
}
