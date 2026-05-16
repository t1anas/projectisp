<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Layanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InstalasiNocController extends Controller
{    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('userakses:noc');
    }
    public function index(Request $request)
    {
        $query = Pelanggan::with(['layanan', 'site'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama',            'like', "%{$search}%")
                  ->orWhere('no_hp',         'like', "%{$search}%")
                  ->orWhere('kode_pelanggan', 'like', "%{$search}%")
                  ->orWhere('nik',           'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'pengajuan noc');
        }

        if ($request->filled('layanan_id')) {
            $query->where('layanan_id', $request->layanan_id);
        }

        if ($request->filled('dari')) {
            $query->whereDate('created_at', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('created_at', '<=', $request->sampai);
        }

        $pelanggan = $query->paginate(15)->withQueryString();
        $layanan   = Layanan::orderBy('nama_paket')->get();

        return view('instalasi-noc', compact('pelanggan', 'layanan'));
    }

public function approve(Request $request, $id)
{
    $request->validate([
        'nama_layanan' => 'required|string|max:255',
        'catatan_noc'  => 'nullable|string|max:1000',
    ]);

    $pelanggan = Pelanggan::findOrFail($id);

    if ($pelanggan->status !== 'pengajuan noc') {
        return back()->with('error', 'Pelanggan ini tidak dalam status pengajuan NOC.');
    }

    $pelanggan->update([
        'nama_layanan'    => $request->nama_layanan,
        'catatan_noc'     => $request->catatan_noc,
        'approved_noc_at' => now(),
        'approved_noc_by' => Auth::id(),
        'status'          => 'pending',
    ]);

    return redirect()->route('instalasi-noc')
        ->with('success', "Konfigurasi \"{$pelanggan->nama}\" selesai. Menunggu approval admin.");
}

    public function reject(Request $request, $id)
    {
        $request->validate([
            'alasan' => 'nullable|string|max:1000',
        ]);

        $pelanggan = Pelanggan::findOrFail($id);

        if ($pelanggan->status !== 'pengajuan noc') {
            return redirect()->back()
                ->with('error', 'Pelanggan ini tidak dalam status pengajuan NOC.');
        }

        $pelanggan->update([
            'status'        => 'nonaktif',
            'catatan_noc'   => $request->alasan,
            'approved_noc_by' => Auth::id(),
        ]);

        return redirect()->route('instalasi-noc')
            ->with('success', "Instalasi pelanggan \"{$pelanggan->nama}\" telah ditolak.");
    }

    public function show($id)
    {
        $pelanggan = Pelanggan::with(['layanan', 'site'])->findOrFail($id);

        return view('pelanggan.show', compact('pelanggan'));
    }
}