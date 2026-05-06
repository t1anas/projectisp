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
    $request->validate([
        'tanggal_bayar' => 'required|date',
        'metode_id'     => 'required',
        'total'         => 'required|numeric',
    ]);

    $tagihan = Tagihan::where('pelanggan_id', $id)
                      ->where('status', 'belum bayar')
                      ->latest()
                      ->first();

    if (!$tagihan) {
        return back()->with('error', 'Tidak ada tagihan yang perlu dibayar');
    }

    // Buat record pembayaran
    \App\Models\Pembayaran::create([
        'tagihan_id'    => $tagihan->id,
        'pelanggan_id'  => $tagihan->pelanggan_id,
        'layanan_id'    => $tagihan->layanan_id,
        'jumlah_bayar'  => $request->total,
        'tanggal_bayar' => $request->tanggal_bayar,
        'metode_id'     => $request->metode_id,
        'status'        => 'lunas',
    ]);

    // Update status tagihan
    $tagihan->update(['status' => 'lunas']);

    return back()->with('success', 'Pembayaran berhasil dikonfirmasi');
}
}