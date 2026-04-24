<?php

namespace App\Http\Controllers;
use App\Models\Layanan;

class LayananController extends Controller
{
public function index()
{
    $layanan = Layanan::with('pelanggan')->get();
    return view('layanan', compact('layanan'));
}
}