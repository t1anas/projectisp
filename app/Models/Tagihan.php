<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    protected $table = 'tagihan';
        protected $fillable = [
        'pelanggan_id',
        'jumlah',
        'status',
        'bulan',
        'tahun',
    ];
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
