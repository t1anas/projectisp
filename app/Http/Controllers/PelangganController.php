<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Tagihan;
use App\Models\Site;
use App\Models\Layanan;
use App\Models\AgendaNoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $query = Pelanggan::with(['layanan', 'site'])
            ->orderBy('created_at', 'desc')
            ->whereNotIn('status', ['pengajuan noc', 'pending']);

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

public function generateTagihanTerpilih(Request $request)
{
    $ids = $request->ids ?? [];

    if (empty($ids)) {
        return back()->with('error', 'Tidak ada pelanggan yang dipilih.');
    }

    $pelanggan = Pelanggan::with('layanan')
        ->whereIn('id', $ids)
        ->where('status', 'aktif')
        ->get();

    foreach ($pelanggan as $p) {
        if (!$p->layanan) continue;

        $hariAktivasi = \Carbon\Carbon::parse($p->created_at)->day;

        $tagihanTerakhir = Tagihan::where('pelanggan_id', $p->id)
            ->orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->first();

        if ($tagihanTerakhir) {
            $bulanBaru = \Carbon\Carbon::create(
                $tagihanTerakhir->tahun,
                $tagihanTerakhir->bulan,
                $hariAktivasi
            )->addMonth();
        } else {
            $bulanBaru = \Carbon\Carbon::create(now()->year, now()->month, $hariAktivasi);
        }

        $sudahAda = Tagihan::where('pelanggan_id', $p->id)
            ->whereMonth('tanggal', $bulanBaru->month)
            ->whereYear('tanggal', $bulanBaru->year)
            ->exists();

        if (!$sudahAda) {
            Tagihan::create([
                'pelanggan_id'  => $p->id,
                'layanan_id'    => $p->layanan->id,
                'jenis_tagihan' => 'tagihan internet bulanan',
                'tanggal'       => $bulanBaru->startOfDay(),
                'bulan'         => $bulanBaru->month,
                'tahun'         => $bulanBaru->year,
                'jatuh_tempo'   => $bulanBaru->copy()->addDays(3),
                'total'         => $p->layanan->harga,
                'status'        => 'belum bayar',
            ]);
        }
    }

    return back()->with('success', 'Tagihan berhasil digenerate.');
}

public function formUpgrade($id)
    {
        $pelanggan = Pelanggan::with('layanan')
            ->findOrFail($id);

        $layanan = Layanan::all();

        return view('upgrade', compact('pelanggan', 'layanan'));
    }

    public function simpanUpgrade(Request $request)
    {
        $request->validate([
            'pelanggan_id'    => 'required|exists:pelanggan,id',
            'layanan_baru_id' => 'required|exists:layanan,id',
            'jenis_perubahan' => 'nullable|in:upgrade,downgrade',
            'catatan'         => 'nullable|string|max:1000',
        ]);

        $pelanggan   = Pelanggan::with('layanan')->findOrFail($request->pelanggan_id);
        $layananBaru = Layanan::findOrFail($request->layanan_baru_id);

        if ($request->layanan_baru_id == $pelanggan->layanan_id) {
            return back()->withErrors(['layanan_baru_id' => 'Paket baru tidak boleh sama dengan paket aktif.']);
        }

        if ($request->jenis_perubahan) {
            $jenis = $request->jenis_perubahan === 'upgrade'
                ? 'upgrade_layanan' : 'downgrade_layanan';
        } else {
            $jenis = $layananBaru->harga > $pelanggan->layanan->harga
                ? 'upgrade_layanan' : 'downgrade_layanan';
        }

        AgendaNoc::create([
            'pelanggan_id'    => $pelanggan->id,
            'layanan_baru_id' => $layananBaru->id,
            'jenis'           => $jenis,
            'status'          => 'pending',
            'catatan'         => $request->catatan,
            'created_by'      => auth()->id(),
        ]);

        return redirect('/pelanggan')->with('success', 'Permintaan upgrade/downgrade berhasil diajukan.');
    }
}