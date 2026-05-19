<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Data Layanan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h2   { text-align: center; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 6px 10px; text-align: left; }
        th { background: #09973B; color: #fff; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <h2>Data Layanan - Jagonet</h2>
    <p style="text-align:center; color:#666;">Dicetak: {{ now()->format('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th><th>Nama</th><th>Nama Layanan</th>
                <th>Paket</th><th>Tagihan</th><th>Status</th><th>Aktivasi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pelanggan as $i => $p)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $p->nama }}</td>
                <td>{{ $p->nama_layanan ?? '-' }}</td>
                <td>{{ $p->layanan->nama_paket ?? '-' }}</td>
                <td>Rp {{ number_format($p->layanan->harga ?? 0, 0, ',', '.') }}</td>
                <td>{{ ucfirst($p->status) }}</td>
                <td>{{ $p->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <script>window.onload = () => window.print();</script>
</body>
</html>