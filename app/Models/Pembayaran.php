<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';

protected $fillable = [
    'pelanggan_id',
    'layanan_id',
    'tagihan_id',
    'metode_id',
    'tanggal_bayar',
    'jumlah_bayar',
    'status',
];

    // Relasi ke Tagihan
    public function tagihan()
    {
        return $this->belongsTo(Tagihan::class, 'tagihan_id');
    }

    // Relasi ke Metode Pembayaran
    public function metode()
    {
        return $this->belongsTo(MetodePembayaran::class, 'metode_id');
            return $this->belongsTo(Metode::class);

    }
    public function pelanggan()
{
    return $this->belongsTo(Pelanggan::class);
}

public function layanan()
{
    return $this->belongsTo(Layanan::class);
}

}