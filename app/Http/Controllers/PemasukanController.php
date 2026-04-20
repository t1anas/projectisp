<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelanggan;
use App\Models\Layanan;
use App\Models\Pembayaran;
use App\Models\MetodePembayaran;

class PemasukanController extends Controller
{
    
    // 🔹 TAMPIL DATA
    public function index()
    {
            $pembayaran = Pembayaran::with('pelanggan','layanan','metode')->get();
            $metode = MetodePembayaran::all();

    return view('pemasukan', compact('pembayaran','metode'));
    }

    // 🔹 FORM TAMBAH
    public function create()
    {
        $pelanggan = Pelanggan::all();
        $layanan = Layanan::all();

        return view('pemasukan.create', compact('pelanggan','layanan'));
    }

    // 🔹 SIMPAN DATA
    public function store(Request $request)
    {
        $request->validate([
            'id_pelanggan' => 'required',
            'id_layanan' => 'required',
            'tanggal' => 'required|date',
            'tagihan' => 'required|numeric',
            'metode' => 'required',
            'status' => 'required'
        ]);

        Pembayaran::create($request->all());

        return redirect()->route('pemasukan.index')
                         ->with('success','Data pembayaran berhasil ditambahkan');
    }

    // 🔹 DETAIL
    public function show($id)
    {
        $pembayaran = Pembayaran::with('pelanggan','layanan')->findOrFail($id);

        return view('pemasukan.show', compact('pembayaran'));
    }

    // 🔹 FORM EDIT
    public function edit($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        $pelanggan = Pelanggan::all();
        $layanan = Layanan::all();

        return view('pemasukan.edit', compact('pembayaran','pelanggan','layanan'));
    }

    // 🔹 UPDATE
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_pelanggan' => 'required',
            'id_layanan' => 'required',
            'tanggal' => 'required|date',
            'tagihan' => 'required|numeric',
            'metode' => 'required',
            'status' => 'required'
        ]);

        $pembayaran = Pembayaran::findOrFail($id);
        $pembayaran->update($request->all());

        return redirect()->route('pemasukan.index')
                         ->with('success','Data pembayaran berhasil diupdate');
    }

    // 🔹 HAPUS
    public function destroy($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        $pembayaran->delete();

        return redirect()->route('pemasukan')
                         ->with('success','Data pembayaran berhasil dihapus');
    }

}