<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    use HasFactory;

    protected $table = 'tagihan';

protected $fillable = [
    'pelanggan_id',
    'layanan_id',
    'jenis_tagihan',  // ← pastikan ada
    'tanggal',
    'bulan',
    'tahun',
    'jatuh_tempo',
    'total',
    'keterangan',
    'status',
    'tanggal_bayar', // ← tambah ini
    'metode_id',   
];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
         return $this->hasOne(Pembayaran::class);
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'tagihan_id');
    }


public function layanan()
{
    return $this->belongsTo(Layanan::class);
}
public function metode()
{
    return $this->belongsTo(\App\Models\MetodePembayaran::class, 'metode_id');
}

    }