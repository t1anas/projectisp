<?php

namespace App\Imports;

use App\Models\Pembayaran;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PembayaranImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Pembayaran([
            'pelanggan_id'  => $row['pelanggan_id'],
            'layanan_id'    => $row['layanan_id'],
            'tagihan_id'    => $row['tagihan_id'],
            'metode_id'     => $row['metode_id'],
            'tanggal_bayar' => $row['tanggal_bayar'],
            'jumlah_bayar'  => $row['jumlah_bayar'],
            'status'        => $row['status'] ?? 'lunas',
        ]);
    }
}