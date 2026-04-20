<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';
    use HasFactory;
    public function tagihan()
{
    return $this->belongsTo(Tagihan::class);
}

public function metode()
{
    return $this->belongsTo(MetodePembayaran::class, 'metode_id');
}
public function pelanggan()
{
    return $this->belongsTo(Pelanggan::class, 'id_pelanggan');
}

public function layanan()
{
    return $this->belongsTo(Layanan::class, 'id_layanan');
}
}
