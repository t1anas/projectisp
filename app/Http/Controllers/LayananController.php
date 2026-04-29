<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;

class LayananController extends Controller
{
    public function index()
    {
        $pelanggan = Pelanggan::with('layanan')->get();
        $tagihan = $pelanggan->pluck('tagihan')->flatten();

        return view('layanan', compact('pelanggan', 'tagihan'));
    }

    public function detail($id)
    {
        $pelanggan = Pelanggan::with(['layanan', 'tagihan'])
            ->findOrFail($id);

        $tagihan = $pelanggan->tagihan;

        return view('detail', compact('pelanggan', 'tagihan'));
    }

    public function edit($id)
    {
        return redirect()->route('layanan.detail', $id);
    }
}