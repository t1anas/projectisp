<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Tagihan;
use App\Models\Layanan;
use App\Models\MetodePembayaran;
use App\Models\AgendaNoc;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\LayananImport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class LayananController extends Controller
{
    public function index(Request $request)
    {
        $query = Pelanggan::with(['layanan', 'tagihan'])
            ->whereNotIn('status', ['pengajuan noc']);

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

        $pelanggan = $query->paginate();
        $tagihan   = Tagihan::with('pelanggan')->get();

        return view('layanan', compact('pelanggan', 'tagihan'));
    }

    public function detail($id)
{
    $pelanggan = Pelanggan::with('layanan')->findOrFail($id);

    $tagihan = Tagihan::where('pelanggan_id', $id)
        ->with('pembayaran.metode')
        ->latest()
        ->get();

    $layanan = Layanan::all();
    $metode  = MetodePembayaran::all();

    return view('detail', compact('pelanggan', 'tagihan', 'layanan', 'metode'));
}

    public function edit($id)
    {
        return redirect()->route('layanan', $id);
    }

    public function update(Request $request, $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        $pelanggan->update([
            'nama'         => $request->nama,
            'no_hp'        => $request->no_hp,
            'status'       => $request->status,
            'nama_layanan' => $request->nama_layanan,
            'alamat'       => $request->alamat,
        ]);

        return redirect('/layanan')->with('success', 'Data berhasil diperbarui.');
    }

    public function bulkDelete(Request $request)
    {
        Pelanggan::whereIn('id', $request->ids ?? [])->delete();
        return back()->with('success', 'Data berhasil dihapus.');
    }

    public function bulkStatus(Request $request)
    {
        Pelanggan::whereIn('id', $request->ids ?? [])
                 ->update(['status' => $request->status]);
        return back()->with('success', 'Status berhasil diubah.');
    }

    public function export()
    {
        $pelanggan = Pelanggan::with('layanan')->get();

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="data-layanan-' . date('Ymd') . '.csv"',
        ];

        $callback = function () use ($pelanggan) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'No', 'Nama', 'Nama Layanan', 'Paket',
                'Tagihan', 'Status', 'No HP', 'Alamat', 'Aktivasi'
            ]);

            foreach ($pelanggan as $i => $p) {
                fputcsv($file, [
                    $i + 1,
                    $p->nama,
                    $p->nama_layanan ?? '-',
                    $p->layanan->nama_paket ?? '-',
                    $p->layanan->harga ?? 0,
                    $p->status,
                    $p->no_hp,
                    $p->alamat,
                    $p->created_at->format('d/m/Y'),
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function cetak()
    {
        $pelanggan = Pelanggan::with('layanan')->get();
        return view('layanan-cetak', compact('pelanggan'));
    }
    public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls,csv|max:2048',
    ]);

    Excel::import(new LayananImport, $request->file('file'));

    return back()->with('success', 'Data berhasil diimport.');
}
public function isolir($id)
{
    $pelanggan = Pelanggan::findOrFail($id);

    $cek = AgendaNoc::where('pelanggan_id', $id)
        ->where('status', 'pending')
        ->exists();

    if ($cek) {
        return back()->with(
            'error',
            'Masih ada pengajuan yang belum diproses NOC.'
        );
    }

    $pelanggan->update([
        'status' => 'pending'
    ]);

    AgendaNoc::create([
        'pelanggan_id' => $pelanggan->id,
        'jenis' => 'isolir',
        'status' => 'pending',
        'created_by' => Auth::id(),
    ]);

    return redirect()
        ->route('detail', $id)
        ->with(
            'isolir_berhasil',
            'Pengajuan isolir berhasil dikirim ke NOC.'
        );
}

public function aktifkan($id)
{
    $pelanggan = Pelanggan::findOrFail($id);

    $cek = AgendaNoc::where('pelanggan_id', $id)
        ->where('status', 'pending')
        ->exists();

    if ($cek) {
        return back()->with(
            'error',
            'Masih ada pengajuan yang belum diproses NOC.'
        );
    }

    $pelanggan->update([
        'status' => 'pending'
    ]);

    AgendaNoc::create([
        'pelanggan_id' => $pelanggan->id,
        'jenis' => 'aktivasi',
        'status' => 'pending',
        'created_by' => Auth::id(),
    ]);

    return redirect()
        ->route('detail', $id)
        ->with(
            'aktivasi_berhasil',
            'Pengajuan aktivasi berhasil dikirim ke NOC.'
        );
}

public function bulkIsolir(Request $request)
{
    $ids = $request->ids ?? [];

    foreach ($ids as $id) {
        $pelanggan = Pelanggan::find($id);
        if (!$pelanggan) continue;

        // Skip jika sudah ada pengajuan pending
        $cekAda = AgendaNoc::where('pelanggan_id', $id)
            ->where('status', 'pending')
            ->exists();

        if ($cekAda) continue;

        $pelanggan->update(['status' => 'pengajuan isolir']);

        AgendaNoc::create([
            'pelanggan_id' => $pelanggan->id,
            'jenis'        => 'isolir',
            'status'       => 'pending',
            'created_by'   => Auth::id(),
        ]);
    }

    return back()->with('success', 'Pengajuan isolir berhasil dikirim ke NOC.');
}

public function bulkAktivasi(Request $request)
{
    foreach ($request->ids ?? [] as $id) {
        $pelanggan = Pelanggan::find($id);
        if (!$pelanggan) continue;

        $cekAda = AgendaNoc::where('pelanggan_id', $id)
            ->where('status', 'pending')
            ->exists();

        if ($cekAda) continue;

        $pelanggan->update(['status' => 'pengajuan aktivasi']);

        AgendaNoc::create([
            'pelanggan_id' => $pelanggan->id,
            'jenis'        => 'aktivasi',
            'status'       => 'pending',
            'created_by'   => Auth::id(),
        ]);
    }

    return back()->with('success', 'Pengajuan aktivasi berhasil dikirim ke NOC.');
}
}