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
    //index=menampilkan data
public function index(Request $request)
{
    $query = Pembayaran::with('pelanggan', 'layanan', 'metode');

    if ($request->tgl_awal && $request->tgl_akhir) {
        $query->whereBetween('tanggal_bayar', [
            $request->tgl_awal,
            $request->tgl_akhir
        ]);
    }

    if ($request->metode) {
        $query->where('metode_id', $request->metode);
    }

    if ($request->cari) {
        $query->whereHas('pelanggan', function ($q) use ($request) {
            $q->where('nama', 'like', '%' . $request->cari . '%');
        });
    }

    $pembayaran = $query->get();

    $total = $pembayaran->sum('jumlah_bayar');

    $metode     = MetodePembayaran::all();
    $layanan    = Layanan::all();
    $tagihan    = Tagihan::all();
    $pelanggan  = Pelanggan::with(['layanan', 'tagihan'])->get();

    return view('pembayaran', compact(
        'pembayaran', 'metode', 'pelanggan', 'layanan', 'tagihan', 'total'
    ));
}

    //untuk form tambah data
    public function create()
    {
        $tagihan = Tagihan::all();
        $metode = MetodePembayaran::all();

        return view('pemasukan.create', compact(
            'tagihan',
            'metode'
        ));
    }

    //store=menyimpan data
public function store(Request $request)
{
    $request->validate([
        'pelanggan_id' => 'required',
        'layanan_id'   => 'required',
        'tagihan_id'   => 'required',
        'metode_id'    => 'required',
        'tanggal_bayar'=> 'required|date',
        'jumlah_bayar' => 'required|numeric',
    ]);

    // ambil tagihan
    $tagihan = Tagihan::findOrFail($request->tagihan_id);

    if ($tagihan->status == 'lunas') {
        return back()->with('error', 'Tagihan sudah lunas!');
    }

    
    Pembayaran::create([
        'pelanggan_id' => $request->pelanggan_id,
        'layanan_id'   => $request->layanan_id,
        'tagihan_id'   => $request->tagihan_id,
        'metode_id'    => $request->metode_id,
        'tanggal_bayar'=> $request->tanggal_bayar,
        'jumlah_bayar' => $request->jumlah_bayar,
        'status'       => 'lunas'
    ]);

    $totalBayar = Pembayaran::where('tagihan_id', $tagihan->id)
        ->sum('jumlah_bayar');

    if ($totalBayar >= $tagihan->jumlah) {
        $tagihan->status = 'lunas';
    } else {
        $tagihan->status = 'belum lunas';
    }

    $tagihan->save();

    return redirect()->route('pembayaran')
        ->with('success', 'Data pembayaran berhasil ditambahkan');
}

    //untuk edit data
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
            'pelanggan_id' => 'required',
            'layanan_id'   => 'required',
            'status'       => 'required|in:lunas,belum lunas',
            'tagihan_id'   => 'required',
            'metode_id'    => 'required',
            'tanggal_bayar'=> 'required|date',
            'jumlah_bayar' => 'required|numeric'
        ]);

        $pembayaran = Pembayaran::findOrFail($id);

        $pembayaran->update([
            'pelanggan_id' => $request->pelanggan_id,
            'layanan_id'   => $request->layanan_id,
            'status'       => $request->status,
            'tagihan_id'    => $request->tagihan_id,
            'metode_id'     => $request->metode_id,
            'tanggal_bayar' => $request->tanggal_bayar,
            'jumlah_bayar'  => $request->jumlah_bayar
        ]);

        return redirect()->route('pembayaran')
            ->with('success', 'Data pembayaran berhasil diupdate');
    }

    //fungsi untuk hapus data
    public function destroy($id)
{
    $pembayaran = Pembayaran::findOrFail($id);

    // ambil tagihan terkait
    $tagihan = Tagihan::findOrFail($pembayaran->tagihan_id);

    // hapus pembayaran
    $pembayaran->delete();

    $totalBayar = Pembayaran::where('tagihan_id', $tagihan->id)
        ->sum('jumlah_bayar');

    // update status
    if ($totalBayar >= $tagihan->jumlah) {
        $tagihan->status = 'lunas';
    } else {
        $tagihan->status = 'belum lunas';
    }

    $tagihan->save();

    return redirect()->route('pembayaran')
        ->with('success', 'Pembayaran dihapus & status tagihan diperbarui');
}
public function menu()
{
    return view('pemasukan');
}
}