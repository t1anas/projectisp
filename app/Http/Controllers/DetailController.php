<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Tagihan;
use App\Models\Layanan;
use App\Models\MetodePembayaran;
use Illuminate\Http\Request;

class DetailController extends Controller
{
    public function index($id)
    {
        $pelanggan = Pelanggan::with('layanan')->findOrFail($id);

        $tagihan = Tagihan::where('pelanggan_id', $id)
    ->with('pembayaran.metode')
    ->latest()
    ->get();

        $layanan = Layanan::all();
        $metode    = MetodePembayaran::all();

        return view('detail', compact('pelanggan', 'tagihan', 'layanan', 'metode'));
    }
}