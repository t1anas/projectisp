<?php

namespace App\Models;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;
    protected $table = 'pelanggan';
    protected $fillable = [
        'kode_pelanggan',
        'nama',
        'status',
        'alamat',
        'no_hp',
        'site_id',
        'layanan_id',
        'nik',
        'lokasi_link',
        'qr_code',
        'nama_layanan',
        'catatan_noc',
        'approved_by',
        'approved_at',
        'approved_noc_by',
        'approved_noc_at',
        'created_by',
    ];

    public function tagihan()
    {
        return $this->hasMany(Tagihan::class, 'pelanggan_id');
    }

    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'layanan_id');
    }

    public function site()
    {
        return $this->belongsTo(Site::class, 'site_id');
    }

    public function approvedBy()
{
    return $this->belongsTo(User::class, 'approved_by');
}

public function approvedAdmin()
{
    return $this->belongsTo(User::class, 'approved_admin_by');
}

public function rejectedBy()
{
    return $this->belongsTo(User::class, 'rejected_by');
}
    
}