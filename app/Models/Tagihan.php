<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    protected $table = 'tagihan';
    use HasFactory;
    public function pelanggan()
{
    return $this->belongsTo(Pelanggan::class);
}

public function pembayaran()
{
    return $this->hasOne(Pembayaran::class);
}
}
