<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    protected $table = 'layanan';

    protected $fillable = [
        'nama_paket',
        'harga'
    ];

    public function pelanggan()
{
    return $this->hasMany(Pelanggan::class, 'layanan_id');
}
}
