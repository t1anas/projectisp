<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pelanggan;
use App\Models\Tagihan;
use Carbon\Carbon;

class GenerateTagihan extends Command
{
    protected $signature = 'app:generate-tagihan';

    protected $description = 'Generate monthly bills for customers';

    public function handle()
{
    $hariIni = now()->day;

    $pelanggan = Pelanggan::with('layanan')->get();

    foreach ($pelanggan as $p) {

        if (!$p->layanan) continue;

        // ambil tanggal daftar
        $tanggalDaftar = Carbon::parse($p->created_at);

        // H-1 dari tanggal daftar
        $tanggalTagihan = $tanggalDaftar->copy()->subDay();

        // kalau bukan hari ini → skip
        if ($hariIni != $tanggalTagihan->day) continue;

        $cek = Tagihan::where('pelanggan_id', $p->id)
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->exists();

if (!$cek) {
    Tagihan::create([
        'pelanggan_id' => $p->id,
        'layanan_id'   => $p->layanan->id,
        'tanggal'      => $tanggalTagihan, 
        'total'        => $p->layanan->harga,
        'status'       => 'belum bayar',
    ]);
}
    }

    $this->info('Generate tagihan otomatis selesai');
}
}
