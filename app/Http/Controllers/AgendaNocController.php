<?php

namespace App\Http\Controllers;

use App\Models\AgendaNoc;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgendaNocController extends Controller
{
    public function index()
    {
        $agenda = AgendaNoc::with('pelanggan')
            ->latest()
            ->get();

        return view('agendanoc', compact('agenda'));
    }

public function approve($id)
{
    $agenda = AgendaNoc::with('pelanggan')->findOrFail($id);

    if ($agenda->jenis == 'isolir') {

        $agenda->pelanggan->update([
            'status' => 'isolir'
        ]);

    } elseif ($agenda->jenis == 'aktivasi') {

        $agenda->pelanggan->update([
            'status' => 'aktif'
        ]);

    }

    $agenda->update([
        'status' => 'selesai',
        'approved_by' => Auth::id(),
        'approved_at' => now(),
    ]);

    return back()->with('success', 'Agenda berhasil diproses');
}

    public function reject($id)
{
    $agenda = AgendaNoc::with('pelanggan')->findOrFail($id);

    if ($agenda->jenis == 'isolir') {

        $agenda->pelanggan->update([
            'status' => 'aktif'
        ]);

    } elseif ($agenda->jenis == 'aktivasi') {

        $agenda->pelanggan->update([
            'status' => 'isolir'
        ]);

    }

    $agenda->update([
        'status' => 'ditolak',
        'approved_by' => Auth::id(),
        'approved_at' => now(),
    ]);

    return back()->with('success', 'Pengajuan ditolak');
}}