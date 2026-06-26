<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pelanggan;
use App\Models\User;

class AgendaNoc extends Model
{
    protected $table = 'agenda_noc';

    protected $fillable = [
        'pelanggan_id',
        'jenis',
        'status',
        'catatan',
        'created_by',
        'approved_by',
        'layanan_baru_id',
        'approved_at',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function layananBaru()
    {
        return $this->belongsTo(Layanan::class, 'layanan_baru_id');
    }
    
}