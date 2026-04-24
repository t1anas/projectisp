<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;

class CSController extends Controller
{
    public function dashboard()
    {
        $aktif = Pelanggan::where('status', 'aktif')->count();
        $nonaktif = Pelanggan::where('status', 'nonaktif')->count();
        $total = Pelanggan::count();

        return view('cs', compact('aktif','nonaktif','total'));
    }
}