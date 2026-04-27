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

        // ❌ skip kalau tidak punya layanan
        if (!$p->layanan) continue;

        // 🔥 hitung H-1 dari tanggal daftar
        $tanggalDaftar = Carbon::parse($p->created_at)->day;
        $tanggalTagihan = $tanggalDaftar - 1;

        if ($tanggalTagihan == 0) {
            $tanggalTagihan = now()->subDay()->day;
        }

        // ❌ kalau bukan harinya → skip
        if ($hariIni != $tanggalTagihan) continue;

        // ✅ cek biar tidak double
        $cek = Tagihan::where('pelanggan_id', $p->id)
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->exists();

        if (!$cek) {
            Tagihan::create([
                'pelanggan_id' => $p->id,
                'layanan_id' => $p->layanan->id,
                'tanggal' => now(),
                'total' => $p->layanan->harga,
                'status' => 'belum bayar'
            ]);
        }
    }

    $this->info('Generate tagihan otomatis selesai');
}
}
