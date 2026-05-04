<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Tagihan;
use App\Models\Layanan;
use App\Models\MetodePembayaran;
use Illuminate\Http\Request;

class DetailController extends Controller
{
    public function index($id)
    {
        $pelanggan = Pelanggan::with('layanan')->findOrFail($id);

        $tagihan = Tagihan::where('pelanggan_id', $id)
                    ->latest()
                    ->get();

        $layanan = Layanan::all();
        $metode    = MetodePembayaran::all();

        return view('detail', compact('pelanggan', 'tagihan', 'layanan', 'metode'));
    }

    public function bayar(Request $request, $id)
    {
        $tagihan = Tagihan::where('pelanggan_id', $id)
                          ->where('status', 'belum bayar')
                          ->latest()
                          ->first();

        if (!$tagihan) {
            return back()->with('error', 'Tidak ada tagihan yang perlu dibayar');
        }

        $tagihan->update([
            'status'       => 'lunas',
            'tanggal_bayar'=> $request->tanggal_bayar,
            'metode_id' => $request->metode_id,
            'keterangan'   => $request->keterangan,
        ]);

        return back()->with('success', 'Pembayaran berhasil dikonfirmasi');
    }
}