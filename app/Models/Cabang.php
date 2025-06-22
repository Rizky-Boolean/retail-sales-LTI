<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    use HasFactory;

    public function spareparts()
    {
    return $this->belongsToMany(Sparepart::class, 'cabang_sparepart')->withPivot('stok');
    }
}
