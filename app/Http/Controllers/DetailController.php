<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Tagihan;

class DetailController extends Controller
{
    public function index($id)
    {
        $pelanggan = Pelanggan::with('layanan')->findOrFail($id);

        $tagihan = Tagihan::where('pelanggan_id', $id)
                    ->latest()
                    ->get();

        return view('layanan.detail', compact('pelanggan', 'tagihan'));
    }
}