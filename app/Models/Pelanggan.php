<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    public function tagihan()
{
    return $this->hasMany(Tagihan::class);
}

public function layanan()
{
    return $this->belongsTo(Layanan::class);
}

public function site()
{
    return $this->belongsTo(Site::class);
}
}
