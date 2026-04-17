<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Tagihan;
use App\Models\Site;
use App\Models\Layanan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index()
{
    $pelanggan = Pelanggan::with('layanan','site')->get();

    return view('index', compact('pelanggan'));

}
    // 🔥 DETAIL (QR masuk sini)
    public function detail($kode)
    {
        $pelanggan = Pelanggan::where('kode_pelanggan', $kode)
            ->with(['tagihan.pembayaran', 'layanan', 'site'])
            ->firstOrFail();

        return view('pelanggan.detail', compact('pelanggan'));
    }

    // 🔥 FORM INPUT
    public function create()
    {
        $site = Site::all();
        $layanan = Layanan::all();

        return view('create', compact('site','layanan'));
    }

    // 🔥 SIMPAN DATA
    public function store(Request $request)
    {
        // validasi dulu (biar aman)
        $request->validate([
            'nama' => 'required',
            'site_id' => 'required',
            'layanan_id' => 'required',
            'kode_wilayah' => 'required'
        ]);

        $site = Site::find($request->site_id);

        // generate nomor urut
        $count = Pelanggan::where('site_id', $request->site_id)
                    ->where('kode_wilayah', $request->kode_wilayah)
                    ->count() + 1;

        $nomor = str_pad($count, 4, '0', STR_PAD_LEFT);

        // contoh: PON60001
        $kode = $site->kode_site . $request->kode_wilayah . $nomor;

        Pelanggan::create([
            'kode_pelanggan' => $kode,
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'site_id' => $request->site_id,
            'layanan_id' => $request->layanan_id,
            'kode_wilayah' => $request->kode_wilayah,
            'lokasi_link' => $request->lokasi_link
        ]);

        return redirect('/pelanggan')->with('success', 'Berhasil tambah pelanggan');
    }
}