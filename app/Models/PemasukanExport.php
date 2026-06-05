<?php

namespace App\Models;

use App\Models\Pembayaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PemasukanExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Pembayaran::with([
            'pelanggan',
            'layanan',
            'metode',
            'tagihan'
        ])->get()->map(function ($item) {

            return [
                $item->tagihan->id ??'-',
                $item->pelanggan->kode_pelanggan ??'-',
                $item->tanggal_bayar,
                $item->tagihan->tanggal ?? '-',
                $item->pelanggan->nama ?? '-',
                $item->pelanggan->site->nama_site?? '-',
                $item->layanan->nama_paket ?? '-',
                $item->layanan->harga ?? '-',
                $item->jumlah_bayar,
                $item->metode->nama_metode ?? '-',
                $item->tagihan->jenis_tagihan ?? '-',
                $item->status,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'T. ID',
            'P. ID',
            'Tanggal Bayar',
            'Tanggal Tagihan',
            'Nama Pelanggan',
            'Site',
            'Paket',
            'Tagihan',
            'Jumlah Bayar',
            'Metode',
            'Keterangan',
            'Status'
        ];
    }
}