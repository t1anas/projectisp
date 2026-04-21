<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Tagihan;
use App\Models\MetodePembayaran;
use App\Models\Pelanggan;
use App\Models\Layanan;

class PemasukanController extends Controller
{
    // TAMPIL DATA
public function index()
{
    $pembayaran = Pembayaran::with('pelanggan','layanan','metode')->get();
    $metode     = MetodePembayaran::all();
    $pelanggan  = Pelanggan::all();
    $layanan    = Layanan::all();
    $tagihan = Tagihan::all();

    return view('pemasukan', compact(
        'pembayaran',
        'metode',
        'pelanggan',
        'layanan',
        'tagihan'
    ));
}

    // FORM TAMBAH
    public function create()
    {
        $tagihan = Tagihan::all();
        $metode = MetodePembayaran::all();

        return view('pemasukan.create', compact(
            'tagihan',
            'metode'
        ));
    }

    // SIMPAN DATA
    public function store(Request $request)
    {
        $request->validate([
            'tagihan_id'   => 'required',
            'metode_id'    => 'required',
            'tanggal_bayar'=> 'required|date',
            'jumlah_bayar' => 'required|numeric'
        ]);

        Pembayaran::create([
            'tagihan_id'    => $request->tagihan_id,
            'metode_id'     => $request->metode_id,
            'tanggal_bayar' => $request->tanggal_bayar,
            'jumlah_bayar'  => $request->jumlah_bayar
        ]);

        return redirect()->route('pemasukan.index')
            ->with('success', 'Data pembayaran berhasil ditambahkan');
    }

    // EDIT
    public function edit($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        $tagihan = Tagihan::all();
        $metode = MetodePembayaran::all();

        return view('pemasukan.edit', compact(
            'pembayaran',
            'tagihan',
            'metode'
        ));
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $request->validate([
            'tagihan_id'   => 'required',
            'metode_id'    => 'required',
            'tanggal_bayar'=> 'required|date',
            'jumlah_bayar' => 'required|numeric'
        ]);

        $pembayaran = Pembayaran::findOrFail($id);

        $pembayaran->update([
            'tagihan_id'    => $request->tagihan_id,
            'metode_id'     => $request->metode_id,
            'tanggal_bayar' => $request->tanggal_bayar,
            'jumlah_bayar'  => $request->jumlah_bayar
        ]);

        return redirect()->route('pemasukan.index')
            ->with('success', 'Data pembayaran berhasil diupdate');
    }

    // HAPUS
    public function destroy($id)
    {
        Pembayaran::findOrFail($id)->delete();

        return redirect()->route('pemasukan.index')
            ->with('success', 'Data pembayaran berhasil dihapus');
    }
}