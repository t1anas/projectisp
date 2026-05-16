<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class ScanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('scan');
    }

public function result($kode)
{
    $pelanggan = Pelanggan::with('layanan')
        ->where('kode_pelanggan', $kode)
        ->firstOrFail();

    if (!$pelanggan->layanan) {
        return redirect('/scan')->with('error', 'Layanan pelanggan tidak ditemukan.');
    }

    return redirect('/layanan/' . $pelanggan->id);
}
}