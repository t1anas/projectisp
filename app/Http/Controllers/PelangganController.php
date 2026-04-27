<?php
namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Tagihan;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Site;
use App\Models\Layanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggan = Pelanggan::with('layanan', 'site')->get();
        $site      = Site::all();
        $layanan   = Layanan::all();

        return view('index', compact('pelanggan', 'site', 'layanan'));
    }

    // 🔥 DETAIL
    public function detail($kode)
{
    $pelanggan = Pelanggan::where('kode_pelanggan', $kode)
        ->with(['tagihan.pembayaran', 'layanan', 'site'])
        ->firstOrFail();

    return view('pelanggan.detail', compact('pelanggan'));
}

    // 🔥 FORM INPUT
    public function create()
    {
        $site    = Site::all();
        $layanan = Layanan::all();

        return view('create', compact('site', 'layanan'));
    }

    // 🔥 SIMPAN DATA
    public function store(Request $request)
    {
        $request->validate([
            'nama'          => 'required',
            'site_id'       => 'required',
            'layanan_id'    => 'required',
            'nik'  => 'required'
        ]);

        $site = Site::find($request->site_id);

        $count = Pelanggan::where('site_id', $request->site_id)
                    ->where('nik', $request->nik)
                    ->count() + 1;

        $nomor = str_pad($count, 4, '0', STR_PAD_LEFT);

        $kode = $site->kode_site . $request->nik . $nomor;

        Pelanggan::create([
    'kode_pelanggan' => $kode,
    'nama'           => $request->nama,
    'alamat'         => $request->alamat,
    'no_hp'          => $request->no_hp,
    'site_id'        => $request->site_id,
    'layanan_id'     => $request->layanan_id,
    'nik'            => $request->nik,
    'lokasi_link'    => $request->lokasi_link,

    'status'         => 'pending',
    'created_by'     => Auth::id()
]);


        return redirect('/pelanggan')->with('success', 'Berhasil tambah pelanggan');
    }

    // 🔥 UPDATE DATA
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'       => 'required',
            'site_id'    => 'required',
            'layanan_id' => 'required'
        ]);

        $pelanggan = Pelanggan::findOrFail($id);

        $pelanggan->update([
            'nama'       => $request->nama,
            'alamat'     => $request->alamat,
            'no_hp'      => $request->no_hp,
            'site_id'    => $request->site_id,
            'layanan_id' => $request->layanan_id,
            'lokasi_link'=> $request->lokasi_link
        ]);

        return redirect('/pelanggan')->with('success', 'Data berhasil diupdate');
    }

    // 🔥 HAPUS DATA
    public function destroy($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->delete();

        return redirect('/pelanggan')->with('success', 'Data berhasil dihapus');
    }
    public function approve($id)
{
    $p = Pelanggan::with('layanan')->findOrFail($id);

    // update status
    $p->status = 'aktif';
    $p->approved_by = Auth::id();
    $p->approved_at = now();

    // 🔥 QR (pakai URL biar dinamis)
    $url = url('/pelanggan/'.$p->kode_pelanggan);

    $qr = QrCode::size(300)->generate($url);

    $filename = 'qr_'.$p->kode_pelanggan.'.svg';
    file_put_contents(public_path('qrcodes/'.$filename), $qr);

    $p->qr_code = 'qrcodes/'.$filename;

    $p->save();

    return back()->with('success', 'Pelanggan berhasil di-approve + QR dibuat');
}
public function approvePage()
{
    $pelanggan = Pelanggan::with('layanan')
        ->where('status', 'pending')
        ->get();

    return view('approve', compact('pelanggan'));
}
public function generateTagihan()
{
    $pelanggan = Pelanggan::with('layanan')->get();

    foreach ($pelanggan as $p) {

        if (!$p->layanan) continue;

        $cek = Tagihan::where('pelanggan_id', $p->id)
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->exists();

        if (!$cek) {
            Tagihan::create([
    'pelanggan_id' => $p->id,
    'layanan_id' => $p->layanan->id,
    'tanggal' => now(),
    'bulan' => now()->month,
    'tahun' => now()->year,
    'total' => $p->layanan->harga,
    'status' => 'belum bayar'
]);
        }
    }

    return back()->with('success', 'Tagihan berhasil digenerate');
}
}
