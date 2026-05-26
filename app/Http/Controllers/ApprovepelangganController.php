<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelanggan;
use App\Models\Layanan;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ApprovePelangganController extends Controller
{
    public function index(Request $request)
    {
        $query = Pelanggan::with(['layanan', 'site', 'approvedBy'])
            ->orderBy('created_at', 'desc')
            ->whereNotIn('status', ['pengajuan noc']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('layanan_id')) {
            $query->where('layanan_id', $request->layanan_id);
        }

        if ($request->filled('dari')) {
            $query->whereDate('created_at', '>=', $request->dari);
        }

        if ($request->filled('sampai')) {
            $query->whereDate('created_at', '<=', $request->sampai);
        }

        if ($request->filled('search')) {
            $q = $request->search;

            $query->where(function ($sub) use ($q) {
                $sub->where('nama', 'like', "%{$q}%")
                    ->orWhere('no_hp', 'like', "%{$q}%")
                    ->orWhere('nik', 'like', "%{$q}%")
                    ->orWhere('kode_pelanggan', 'like', "%{$q}%");
            });
        }

        $perPage = $request->input('per_page', 10);

        $pelanggan = $query->paginate($perPage)
            ->appends($request->query());

        $layanan = Layanan::all();

        return view('approve', compact('pelanggan', 'layanan'));
    }

public function approve($id)
{
    $pelanggan = Pelanggan::findOrFail($id);

    if ($pelanggan->status !== 'pending') {
        return back()->with('error', 'Pelanggan ini sudah pernah diproses.');
    }

    $url = url('/scan/' . $pelanggan->kode_pelanggan);
    $qr  = QrCode::size(300)->generate($url); 
    $pelanggan->update([
        'status'            => 'aktif',
        'approved_admin_at' => now(),
        'approved_admin_by' => auth()->id(),
        'qr_code'           => $qr, 
    ]);

    return back()->with('success', "Pelanggan {$pelanggan->nama} berhasil di-approve + QR dibuat.");
}

    public function reject(Request $request, $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        $request->validate([
            'alasan' => 'nullable|string|max:255',
        ]);

        $pelanggan->update([
            'status'         => 'nonaktif',
            'alasan_tolak'   => $request->alasan,
            'rejected_at'    => now(),
            'rejected_by'    => auth()->id(),
        ]);

        return back()->with(
            'success',
            "Pelanggan {$pelanggan->nama} berhasil dinonaktifkan."
        );
    }

    public function bulkApprove(Request $request)
{
    if (!$request->filled('ids')) {
        return back()->with('error', 'Tidak ada pelanggan yang dipilih.');
    }

    $ids       = explode(',', $request->ids);
    $pelanggan = Pelanggan::whereIn('id', $ids)->where('status', 'pending')->get();
    $count     = 0;

    foreach ($pelanggan as $p) {
    $url = url('/pelanggan/' . $p->kode_pelanggan);
    $qr  = QrCode::size(300)->generate($url);

    $p->update([
        'status'            => 'aktif',
        'approved_admin_at' => now(),
        'approved_admin_by' => auth()->id(),
        'qr_code'           => $qr,
    ]);
    $count++;
}
    
    return back()->with('success', "{$count} pelanggan berhasil di-approve + QR dibuat.");
}

    public function bulkReject(Request $request)
    {
        if (!$request->filled('ids')) {
            return back()->with(
                'error',
                'Tidak ada pelanggan yang dipilih.'
            );
        }

        $ids = explode(',', $request->ids);

        $updated = Pelanggan::whereIn('id', $ids)
            ->where('status', 'pending')
            ->update([
                'status'        => 'nonaktif',
                'rejected_at'   => now(),
                'rejected_by'   => auth()->id(),
            ]);

        return back()->with(
            'success',
            $updated . ' pelanggan berhasil dinonaktifkan.'
        );
    }
}