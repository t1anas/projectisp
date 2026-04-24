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
        $pelanggan = Pelanggan::with('layanan', 'site')->get();
        $site      = Site::all();
        $layanan   = Layanan::all();

        return view('index', compact('pelanggan', 'site', 'layanan'));
    }

    // 🔥 DETAIL
    public function detail($kode)
    {
        $pelanggan = Pelanggan::where('nik', $kode)
            ->with(['tagihan.pembayaran', 'layanan', 'site'])
            ->firstOrFail();

        return view('pelanggan.detail', compact('pelanggan'));
    }

    // 🔥 FORM INPUT
    public function create()
    {
        $site    = Site::all();
        $layanan = Layanan::all();

        return view('create', compact('site', 'layanan'));
    }

    // 🔥 SIMPAN DATA
    public function store(Request $request)
    {
        $request->validate([
            'nama'          => 'required',
            'site_id'       => 'required',
            'layanan_id'    => 'required',
            'nik'  => 'required'
        ]);

        $site = Site::find($request->site_id);

        $count = Pelanggan::where('site_id', $request->site_id)
                    ->where('nik', $request->nik)
                    ->count() + 1;

        $nomor = str_pad($count, 4, '0', STR_PAD_LEFT);

        $kode = $site->kode_site . $request->nik . $nomor;

        Pelanggan::create([
            'kode_pelanggan' => $kode,
            'nama'           => $request->nama,
            'alamat'         => $request->alamat,
            'no_hp'          => $request->no_hp,
            'site_id'        => $request->site_id,
            'layanan_id'     => $request->layanan_id,
            'nik'   => $request->nik,
            'lokasi_link'    => $request->lokasi_link
        ]);

        return redirect('/pelanggan')->with('success', 'Berhasil tambah pelanggan');
    }

    // 🔥 UPDATE DATA
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'       => 'required',
            'site_id'    => 'required',
            'layanan_id' => 'required'
        ]);

        $pelanggan = Pelanggan::findOrFail($id);

        $pelanggan->update([
            'nama'       => $request->nama,
            'alamat'     => $request->alamat,
            'no_hp'      => $request->no_hp,
            'site_id'    => $request->site_id,
            'layanan_id' => $request->layanan_id,
            'lokasi_link'=> $request->lokasi_link
        ]);

        return redirect('/pelanggan')->with('success', 'Data berhasil diupdate');
    }

    // 🔥 HAPUS DATA
    public function destroy($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->delete();

        return redirect('/pelanggan')->with('success', 'Data berhasil dihapus');
    }
}
