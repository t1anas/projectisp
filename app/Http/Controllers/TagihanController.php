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

        return view('tagihan', compact('tagihan', 'total'));
    }

    // ✅ TAMBAH INI
    public function update(Request $request, $id)
    {
        $request->validate([
            'total' => 'required|numeric',
            'status' => 'required'
        ]);

        $tagihan = Tagihan::findOrFail($id);

        $tagihan->total = $request->total;
        $tagihan->status = $request->status;

        // kalau kamu pakai keterangan
        if ($request->has('keterangan')) {
            $tagihan->keterangan = $request->keterangan;
        }

        $tagihan->save();

        return redirect()->back()->with('success', 'Tagihan berhasil diupdate');
    }
    public function destroy($id)
{
    $tagihan = Tagihan::findOrFail($id);

    $tagihan->delete();

    return redirect()->back()->with('success', 'Tagihan berhasil dihapus');
}

}