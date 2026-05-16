<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Tagihan;
use App\Models\Site;
use App\Models\Layanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $query = Pelanggan::with(['layanan', 'site'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('dari')) {
            $query->whereDate('created_at', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('created_at', '<=', $request->sampai);
        }

        $pelanggan = $query->get();
        $site      = Site::all();
        $layanan   = Layanan::all();

        return view('index', compact('pelanggan', 'site', 'layanan'));
    }

    public function create()
    {
        $site    = Site::all();
        $layanan = Layanan::all();

        return view('create', compact('site', 'layanan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'       => 'required',
            'site_id'    => 'required',
            'layanan_id' => 'required',
            'nik'        => 'required',
        ]);

        $site  = Site::findOrFail($request->site_id);
        $count = Pelanggan::where('site_id', $request->site_id)
                    ->where('nik', $request->nik)
                    ->count() + 1;

        $kode = $site->kode_site . $request->nik . str_pad($count, 4, '0', STR_PAD_LEFT);

        Pelanggan::create([
            'kode_pelanggan' => $kode,
            'nama'           => $request->nama,
            'alamat'         => $request->alamat,
            'no_hp'          => $request->no_hp,
            'site_id'        => $request->site_id,
            'layanan_id'     => $request->layanan_id,
            'nik'            => $request->nik,
            'lokasi_link'    => $request->lokasi_link,
            'status'         => 'pengajuan noc',
            'created_by'     => Auth::id(),
        ]);

        return redirect('/pelanggan')->with('success', 'Berhasil tambah pelanggan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'       => 'required',
            'site_id'    => 'required',
            'layanan_id' => 'required',
        ]);

        Pelanggan::findOrFail($id)->update([
            'nama'        => $request->nama,
            'alamat'      => $request->alamat,
            'no_hp'       => $request->no_hp,
            'site_id'     => $request->site_id,
            'layanan_id'  => $request->layanan_id,
            'lokasi_link' => $request->lokasi_link,
            'status'      => strtolower($request->status),
        ]);

        return redirect('/pelanggan')->with('success', 'Data berhasil diupdate');
    }

    public function destroy($id)
    {
        Pelanggan::findOrFail($id)->delete();

        return redirect('/pelanggan')->with('success', 'Data berhasil dihapus');
    }

    public function generateTagihan()
    {
        $pelanggan = Pelanggan::with('layanan')->get();

        foreach ($pelanggan as $p) {
            if (!$p->layanan) continue;

            $sudahAda = Tagihan::where('pelanggan_id', $p->id)
                ->whereMonth('tanggal', now()->month)
                ->whereYear('tanggal', now()->year)
                ->exists();

            if (!$sudahAda) {
                Tagihan::create([
                    'pelanggan_id' => $p->id,
                    'layanan_id'   => $p->layanan->id,
                    'tanggal'      => now(),
                    'jatuh_tempo'   => now()->addDays(3), 
                    'bulan'        => now()->month,
                    'tahun'        => now()->year,
                    'total'        => $p->layanan->harga,
                    'status'       => 'belum bayar',
                ]);
            }
        }

        return back()->with('success', 'Tagihan berhasil digenerate');
    }
}