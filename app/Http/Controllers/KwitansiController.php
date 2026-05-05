<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;

class KwitansiController extends Controller
{
    public function cetak($id)
    {
        $tagihan = Tagihan::with([
            'pelanggan.layanan',
            'pembayaran.metode'
        ])->findOrFail($id);

        // Pastikan status valid
        if (!in_array($tagihan->status, ['lunas', 'belum'])) {
            $tagihan->status = 'belum';
        }

        return view('kwitansi', compact('tagihan'));
    }
}