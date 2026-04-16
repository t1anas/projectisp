<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;
    public function tagihan()
{
    return $this->belongsTo(Tagihan::class);
}

public function metode()
{
    return $this->belongsTo(MetodePembayaran::class, 'metode_id');
}
}
