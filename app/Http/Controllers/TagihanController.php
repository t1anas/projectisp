<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use App\Models\Pembayaran;
use App\Models\Layanan;
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    public function index()
    {
        $query = Tagihan::with('pelanggan', 'layanan', 'pembayaran.metode');

        if (request('cari')) {
            $query->whereHas('pelanggan', function ($q) {
                $q->where('nama', 'like', '%' . request('cari') . '%');
            });
        }

        if (request('status')) {
            $query->where('status', request('status'));
        }

        if (request('bulan')) {
            $bulan = request('bulan');
            $query->whereYear('tanggal', substr($bulan, 0, 4))
                  ->whereMonth('tanggal', substr($bulan, 5, 2));
        }

        $tagihan   = $query->get();
        $total     = $tagihan->sum('total');
        $pelanggan = \App\Models\Pelanggan::with('layanan')->get();
        $layanan   = \App\Models\Layanan::all();
        $totalBayar = $tagihan->sum(fn($t) => $t->pembayaran->sum('jumlah_bayar'));

        return view('tagihan', compact('tagihan', 'total', 'pelanggan', 'layanan', 'totalBayar'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pelanggan_id'  => 'required',
            'tanggal'       => 'required|date',
            'total'         => 'required|numeric',
            'jenis_tagihan' => 'required',
            'keterangan'    => 'nullable',
        ]);

        $jatuhTempo = \Carbon\Carbon::parse($request->tanggal)->addDays(3);

        Tagihan::create([
            'pelanggan_id'  => $request->pelanggan_id,
            'layanan_id'    => $request->layanan_id,
            'jenis_tagihan' => $request->jenis_tagihan,
            'tanggal'       => $request->tanggal,
            'bulan'         => date('m', strtotime($request->tanggal)),
            'tahun'         => date('Y', strtotime($request->tanggal)),
            'jatuh_tempo'   => $jatuhTempo,
            'total'         => $request->total,
            'jumlah_bayar'  => $request->jumlah_bayar,
            'keterangan'    => $request->keterangan,
            'status'        => 'belum bayar',
        ]);

return redirect()->back()->with('tagihan_berhasil', 'Tagihan bulan ini berhasil dibuat.');    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal'      => 'required|date',
            'total'        => 'required|numeric',
            'layanan_id'   => 'nullable|exists:layanan,id',
            'jumlah_bayar' => 'nullable|numeric',
        ]);

        $tagihan    = Tagihan::findOrFail($id);
        $statusLama = $tagihan->status;
        $statusBaru = $request->status ?? $tagihan->status;
        $jatuhTempo = \Carbon\Carbon::parse($request->tanggal)->addDays(3);

        $tagihan->update([
            'tanggal'      => $request->tanggal,
            'bulan'        => date('m', strtotime($request->tanggal)),
            'tahun'        => date('Y', strtotime($request->tanggal)),
            'keterangan'   => $request->keterangan,
            'jatuh_tempo'  => $jatuhTempo,
            'jumlah_bayar' => $request->jumlah_bayar ?? $tagihan->jumlah_bayar,
            'layanan_id'   => $request->layanan_id ?? $tagihan->layanan_id,
            'status'       => $statusBaru,
            'total'        => $request->total,
        ]);

        if ($statusLama !== 'lunas' && $statusBaru === 'lunas') {
            $sudahAdaPembayaran = Pembayaran::where('tagihan_id', $tagihan->id)->exists();

            if (!$sudahAdaPembayaran) {
                Pembayaran::create([
                    'pelanggan_id'  => $tagihan->pelanggan_id,
                    'layanan_id'    => $tagihan->layanan_id,
                    'tagihan_id'    => $tagihan->id,
                    'metode_id'     => null,
                    'tanggal_bayar' => now(),
                    'jumlah_bayar'  => $tagihan->total,
                    'status'        => 'lunas',
                ]);
            }
        }

        if ($statusLama === 'lunas' && $statusBaru !== 'lunas') {
            Pembayaran::where('tagihan_id', $tagihan->id)->delete();
        }

        return back()->with('success', 'Tagihan berhasil diupdate');
    }

    public function destroy($id)
    {
        $tagihan = Tagihan::findOrFail($id);
        Pembayaran::where('tagihan_id', $tagihan->id)->delete();

        $tagihan->delete();

        return redirect()->back()->with('success', 'Tagihan berhasil dihapus');
    }

    public function bayar(Request $request, $id)
    {
        $request->validate([
            'tanggal_bayar' => 'required|date',
            'metode_id'     => 'required',
            'total'         => 'required|numeric',
        ]);

        $tagihan = Tagihan::findOrFail($id);

        Pembayaran::create([
            'tagihan_id'    => $tagihan->id,
            'pelanggan_id'  => $tagihan->pelanggan_id,
            'layanan_id'    => $tagihan->layanan_id,
            'jumlah_bayar'  => $request->total,
            'tanggal_bayar' => $request->tanggal_bayar,
            'metode_id'     => $request->metode_id,
            'status'        => 'lunas',
        ]);

        $totalBayar = Pembayaran::where('tagihan_id', $tagihan->id)
            ->sum('jumlah_bayar');

        if ($totalBayar <= 0) {
            $tagihan->status = 'belum bayar';
        } elseif ($totalBayar < $tagihan->total) {
            $tagihan->status = 'belum lunas';
        } else {
            $tagihan->status = 'lunas';
        }

        $tagihan->save();

        return back()->with('success', 'Pembayaran berhasil dikonfirmasi');
    }

    public function kwitansi($id)
    {
        $tagihan = Tagihan::with('pelanggan.layanan')->findOrFail($id);
        return view('kwitansi', compact('tagihan'));
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return back()->with('error', 'Tidak ada data yang dipilih.');
        }

        Pembayaran::whereIn('tagihan_id', $ids)->delete();

        Tagihan::whereIn('id', $ids)->delete();

        return back()->with('success', count($ids) . ' tagihan berhasil dihapus.');
    }
}