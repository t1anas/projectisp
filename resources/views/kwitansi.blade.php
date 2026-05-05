<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kwitansi #{{ $tagihan->id }}</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: 'Segoe UI', Arial, sans-serif;
        background: #f0f4f8;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        min-height: 100vh;
        padding: 40px 20px;
    }

    .kwitansi-wrap {
        width: 720px;
        background: #fff;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0,0,0,0.12);
    }

    /* ===== HEADER ===== */
    .kw-header {
        background: linear-gradient(135deg, #15803d, #22c55e, #4ade80);
        padding: 36px 40px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .kw-logo-area {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .kw-logo-icon {
        width: 56px;
        height: 56px;
        background: rgba(255,255,255,0.2);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: #fff;
        border: 2px solid rgba(255,255,255,0.3);
    }

    .kw-logo-text {
        font-size: 28px;
        font-weight: 900;
        color: #fff;
        letter-spacing: 1px;
    }

    .kw-logo-sub {
        font-size: 12px;
        color: rgba(255,255,255,0.75);
        margin-top: 2px;
    }

    .kw-header-right {
        text-align: right;
    }

    .kw-title {
        font-size: 22px;
        font-weight: 900;
        color: #fff;
        letter-spacing: 1px;
    }

    .kw-nomor {
        font-size: 12px;
        color: rgba(255,255,255,0.8);
        margin-top: 4px;
    }

    .kw-status-badge {
        display: inline-block;
        margin-top: 8px;
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.5px;
    }

    .badge-lunas {
        background: rgba(255,255,255,0.25);
        color: #fff;
        border: 1px solid rgba(255,255,255,0.4);
    }

    .badge-belum {
        background: #fee2e2;
        color: #dc2626;
    }

    /* ===== BODY ===== */
    .kw-body {
        padding: 36px 40px;
    }

    /* INFO PELANGGAN */
    .kw-section-title {
        font-size: 11px;
        font-weight: 800;
        color: #9ca3af;
        letter-spacing: 1px;
        text-transform: uppercase;
        margin-bottom: 14px;
    }

    .kw-pelanggan-box {
        background: #f8fafc;
        border: 1.5px solid #e5e7eb;
        border-radius: 14px;
        padding: 20px 24px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 28px;
    }

    .kw-pelanggan-nama {
        font-size: 18px;
        font-weight: 800;
        color: #111;
        margin-bottom: 6px;
    }

    .kw-pelanggan-info {
        font-size: 13px;
        color: #6b7280;
        line-height: 1.8;
    }

    .kw-pelanggan-info span {
        color: #374151;
        font-weight: 600;
    }

    .kw-paket-box {
        background: linear-gradient(135deg, #f0fdf4, #dcfce7);
        border: 1.5px solid #bbf7d0;
        border-radius: 12px;
        padding: 14px 18px;
        text-align: center;
        min-width: 140px;
    }

    .kw-paket-label {
        font-size: 10px;
        font-weight: 800;
        color: #16a34a;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .kw-paket-nama {
        font-size: 15px;
        font-weight: 800;
        color: #15803d;
    }

    .kw-paket-speed {
        font-size: 11px;
        color: #4ade80;
        margin-top: 2px;
    }

    /* DETAIL TAGIHAN */
    .kw-detail-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 24px;
    }

    .kw-detail-table thead tr {
        background: #f1f5f9;
    }

    .kw-detail-table thead th {
        padding: 12px 16px;
        font-size: 11px;
        font-weight: 800;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        text-align: left;
        border: none;
    }

    .kw-detail-table thead th:last-child {
        text-align: right;
    }

    .kw-detail-table tbody td {
        padding: 14px 16px;
        font-size: 13px;
        color: #374151;
        border-bottom: 1px solid #f1f5f9;
    }

    .kw-detail-table tbody td:last-child {
        text-align: right;
        font-weight: 700;
    }

    /* TOTAL BOX */
    .kw-total-box {
        background: linear-gradient(135deg, #15803d, #22c55e);
        border-radius: 14px;
        padding: 20px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 28px;
    }

    .kw-total-label {
        font-size: 14px;
        font-weight: 700;
        color: rgba(255,255,255,0.85);
    }

    .kw-total-amount {
        font-size: 28px;
        font-weight: 900;
        color: #fff;
    }

    /* PEMBAYARAN INFO */
    .kw-bayar-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 32px;
    }

    .kw-bayar-item {
        background: #f8fafc;
        border: 1.5px solid #e5e7eb;
        border-radius: 12px;
        padding: 14px 16px;
    }

    .kw-bayar-item-label {
        font-size: 10px;
        font-weight: 800;
        color: #9ca3af;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .kw-bayar-item-val {
        font-size: 14px;
        font-weight: 700;
        color: #111;
    }

    /* FOOTER */
    .kw-footer {
        border-top: 1.5px dashed #e5e7eb;
        padding-top: 24px;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
    }

    .kw-footer-note {
        font-size: 11.5px;
        color: #9ca3af;
        line-height: 1.7;
        max-width: 320px;
    }

    .kw-ttd {
        text-align: center;
    }

    .kw-ttd-label {
        font-size: 11px;
        color: #6b7280;
        margin-bottom: 50px;
    }

    .kw-ttd-name {
        font-size: 13px;
        font-weight: 800;
        color: #111;
        border-top: 1.5px solid #374151;
        padding-top: 6px;
    }

    /* TOMBOL PRINT */
    .kw-print-bar {
        background: #f8fafc;
        border-top: 1.5px solid #e5e7eb;
        padding: 18px 40px;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .btn-print {
        background: linear-gradient(135deg, #15803d, #22c55e);
        color: #fff;
        border: none;
        padding: 11px 28px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 800;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: .2s;
    }

    .btn-print:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }

    .btn-back {
        background: #fff;
        color: #374151;
        border: 1.5px solid #d1d5db;
        padding: 11px 24px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: .2s;
    }

    .btn-back:hover {
        background: #f9fafb;
        color: #111;
    }

    @media print {
        body { background: #fff; padding: 0; }
        .kwitansi-wrap { box-shadow: none; border-radius: 0; }
        .kw-print-bar { display: none; }
    }
</style>
</head>
<body>

<div class="kwitansi-wrap">

    {{-- HEADER --}}
    <div class="kw-header">
        <div class="kw-logo-area">
            <div class="kw-logo-icon">🌐</div>
            <div>
                <div class="kw-logo-text">JAGONET</div>
                <div class="kw-logo-sub">Internet Service Provider</div>
            </div>
        </div>
        <div class="kw-header-right">
            <div class="kw-title">KWITANSI</div>
            <div class="kw-nomor">No. KWT-{{ str_pad($tagihan->id, 6, '0', STR_PAD_LEFT) }}</div>
            <div>
                @if($tagihan->status == 'lunas')
                    <span class="kw-status-badge badge-lunas">✓ LUNAS</span>
                @else
                    <span class="kw-status-badge badge-belum">⚠ BELUM BAYAR</span>
                @endif
            </div>
        </div>
    </div>

    {{-- BODY --}}
    <div class="kw-body">

        {{-- INFO PELANGGAN --}}
        <div class="kw-section-title">Informasi Pelanggan</div>
        <div class="kw-pelanggan-box">
            <div>
                <div class="kw-pelanggan-nama">{{ $tagihan->pelanggan->nama ?? '-' }}</div>
                <div class="kw-pelanggan-info">
                    📍 <span>{{ $tagihan->pelanggan->alamat ?? '-' }}</span><br>
                    📱 <span>{{ $tagihan->pelanggan->no_hp ?? '-' }}</span>
                </div>
            </div>
            <div class="kw-paket-box">
                <div class="kw-paket-label">Paket</div>
                <div class="kw-paket-nama">{{ $tagihan->pelanggan->layanan->nama_paket ?? '-' }}</div>
                <div class="kw-paket-speed">{{ $tagihan->pelanggan->layanan->kecepatan ?? '' }}</div>
            </div>
        </div>

        {{-- DETAIL TAGIHAN --}}
        <div class="kw-section-title">Detail Tagihan</div>
        <table class="kw-detail-table">
            <thead>
                <tr>
                    <th>Keterangan</th>
                    <th>Periode</th>
                    <th>Jatuh Tempo</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ \Illuminate\Support\Str::title($tagihan->jenis_tagihan ?? 'Tagihan Internet Bulanan') }}</td>
                    <td>{{ \Carbon\Carbon::parse($tagihan->tanggal)->translatedFormat('F Y') }}</td>
                    <td>{{ $tagihan->jatuh_tempo ? \Carbon\Carbon::parse($tagihan->jatuh_tempo)->format('d M Y') : '-' }}</td>
                    <td>Rp {{ number_format($tagihan->total, 0, ',', '.') }}</td>
                </tr>
                @if($tagihan->keterangan)
                <tr>
                    <td colspan="3" style="color:#9ca3af; font-size:12px;">
                        📝 {{ $tagihan->keterangan }}
                    </td>
                    <td></td>
                </tr>
                @endif
            </tbody>
        </table>

        {{-- TOTAL --}}
        <div class="kw-total-box">
            <div class="kw-total-label">TOTAL PEMBAYARAN</div>
            <div class="kw-total-amount">Rp {{ number_format($tagihan->total, 0, ',', '.') }}</div>
        </div>

        {{-- INFO PEMBAYARAN (jika lunas) --}}
        @if($tagihan->status == 'lunas')
        <div class="kw-section-title">Informasi Pembayaran</div>
        <div class="kw-bayar-grid">
            <div class="kw-bayar-item">
                <div class="kw-bayar-item-label">Tanggal Bayar</div>
                <div class="kw-bayar-item-val">
                   {{ $tagihan->pembayaran && $tagihan->pembayaran->tanggal_bayar 
    ? \Carbon\Carbon::parse($tagihan->pembayaran->tanggal_bayar)->format('d M Y') 
    : '-' }}
                </div>
            </div>
            <div class="kw-bayar-item">
                <div class="kw-bayar-item-label">Metode Pembayaran</div>
                <div class="kw-bayar-item-val">
                    {{ $tagihan->pembayaran->metode->nama_metode ?? 'KAS' }}
                </div>
            </div>
        </div>
        @endif

        {{-- FOOTER --}}
        <div class="kw-footer">
            <div class="kw-footer-note">
                Terima kasih telah mempercayai layanan JAGONET.<br>
                Simpan kwitansi ini sebagai bukti pembayaran yang sah.<br>
                <strong>Hubungi kami jika ada pertanyaan.</strong>
            </div>
        </div>

    </div>

    {{-- TOMBOL PRINT --}}
    <div class="kw-print-bar">
        <a href="javascript:history.back()" class="btn-back">
            ← Kembali
        </a>
        <button onclick="window.print()" class="btn-print">
            🖨️ Cetak Kwitansi
        </button>
    </div>

</div>

</body>
</html>