<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Tagihan;
use App\Models\Layanan;


class DetailController extends Controller
{
    public function index($id)
    {
        $pelanggan = Pelanggan::with('layanan')->findOrFail($id);

        $tagihan = Tagihan::where('pelanggan_id', $id)
                    ->latest()
                    ->get();
        $layanan = Layanan::all();

        return view('detail', compact('pelanggan', 'tagihan', 'layanan'));
    }
}