<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Tagihan;

class LayananController extends Controller
{
    public function index()
    {
        $pelanggan = Pelanggan::with(['layanan', 'tagihan'])->get(); // ← tambah 'tagihan'
        $tagihan   = Tagihan::with('pelanggan')->get();              // ← langsung dari model

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