<?php
namespace App\Http\Controllers;

use App\Models\Tagihan;

class TagihanController extends Controller  
{
public function index()
{
    $query = Tagihan::with('pelanggan', 'layanan');

    if (request('cari')) {
        $query->whereHas('pelanggan', function ($q) {
            $q->where('nama', 'like', '%' . request('cari') . '%');
        });
    }

    if (request('status')) {
        $query->where('status', request('status'));
    }

    if (request('bulan')) {
        $bulan = request('bulan'); 

        $query->whereYear('tanggal', substr($bulan, 0, 4))
              ->whereMonth('tanggal', substr($bulan, 5, 2));
    }

    $tagihan = $query->get();

    // total setelah difilter
    $total = $tagihan->sum('total');

    return view('tagihan', compact('tagihan', 'total'));
}

}
