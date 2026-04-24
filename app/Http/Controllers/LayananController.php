<?php

namespace App\Http\Controllers;
use App\Models\Pelanggan;

class LayananController extends Controller
{
public function index()
{
    $pelanggan = Pelanggan::with('layanan')->get();
    return view('layanan', compact('pelanggan'));
}
}