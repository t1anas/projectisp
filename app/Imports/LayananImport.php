<?php

namespace App\Imports;

use App\Models\Pelanggan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LayananImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Pelanggan([
            'nama'         => $row['nama'],
            'no_hp'        => $row['no_hp'],
            'nama_layanan' => $row['nama_layanan'],
            'alamat'       => $row['alamat'],
            'status'       => $row['status'] ?? 'aktif',
        ]);
    }
}