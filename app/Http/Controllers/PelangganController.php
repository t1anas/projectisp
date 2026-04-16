<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Tagihan;

class PelangganController extends Controller
{
    public function detail($kode)
    {
        $pelanggan = Pelanggan::where('kode_pelanggan', $kode)
            ->with(['tagihan.pembayaran'])
            ->firstOrFail();

        return view('pelanggan.detail', compact('pelanggan'));
    }
}