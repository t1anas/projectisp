<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;

class PelangganDetailController extends Controller
{
    public function show($id)
    {
        $pelanggan = Pelanggan::with(['site', 'layanan'])
            ->findOrFail($id);

        return view('pelanggan-show', compact('pelanggan'));
    }
}