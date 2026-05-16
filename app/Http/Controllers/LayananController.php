<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Tagihan;
use Illuminate\Http\Request;

class LayananController extends Controller
{
    public function index(Request $request)
{
    $query = Pelanggan::with(['layanan', 'tagihan']);

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
    $tagihan   = \App\Models\Tagihan::with('pelanggan')->get();

    return view('layanan', compact('pelanggan', 'tagihan'));
}

    public function detail($id)
    {
        $pelanggan = Pelanggan::with(['layanan', 'tagihan'])->findOrFail($id);
        $tagihan   = $pelanggan->tagihan;

        return view('detail', compact('pelanggan', 'tagihan'));
    }

    public function edit($id)
    {
        return redirect()->route('layanan', $id);
    }
}