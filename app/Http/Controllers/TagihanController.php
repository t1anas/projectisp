<?php
namespace App\Http\Controllers;

use App\Models\Tagihan;
use Illuminate\Http\Request;

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
        $total = $tagihan->sum('total');
        $pelanggan = \App\Models\Pelanggan::with('layanan')->get(); // tambah ini

    return view('tagihan', compact('tagihan', 'total', 'pelanggan')); // tambah pelanggan
       
    }

public function update(Request $request, $id)
{
    $request->validate([
        'tanggal'       => 'required|date',
        'total'         => 'required|numeric',
        'jenis_tagihan' => 'required'
    ]);

    $tagihan = Tagihan::findOrFail($id);

    $jatuhTempo = \Carbon\Carbon::parse($request->tanggal)->addDays(3);

    $tagihan->update([
        'tanggal'       => $request->tanggal,
        'bulan'         => date('m', strtotime($request->tanggal)),
        'tahun'         => date('Y', strtotime($request->tanggal)),
        'total'         => $request->total,
        'jenis_tagihan' => $request->jenis_tagihan,
        'keterangan'    => $request->keterangan,
        'jatuh_tempo'   => $jatuhTempo,
        'layanan_id'    => $request->layanan_id
    ]);

    return back()->with('success', 'Tagihan berhasil diupdate');
}
public function store(Request $request)
{
    $request->validate([
        'pelanggan_id'  => 'required',
        'tanggal'       => 'required|date',
        'total'         => 'required|numeric',
        'jenis_tagihan' => 'required',
        'keterangan'    => 'nullable'
    ]);

    $jatuhTempo = \Carbon\Carbon::parse($request->tanggal)->addDays(3);

    Tagihan::create([
        'pelanggan_id' => $request->pelanggan_id,
        'layanan_id'   => $request->layanan_id,
        'jenis_tagihan'=> $request->jenis_tagihan,
        'tanggal'      => $request->tanggal,
        'bulan'        => date('m', strtotime($request->tanggal)),
        'tahun'        => date('Y', strtotime($request->tanggal)),
        'jatuh_tempo'  => $jatuhTempo,
        'total'        => $request->total,
        'keterangan'   => $request->keterangan,
        'status'       => 'belum bayar'
    ]);

    return back()->with('success', 'Tagihan berhasil ditambahkan');
}
    public function destroy($id)
{
    $tagihan = Tagihan::findOrFail($id);

    $tagihan->delete();

    return redirect()->back()->with('success', 'Tagihan berhasil dihapus');
}

}