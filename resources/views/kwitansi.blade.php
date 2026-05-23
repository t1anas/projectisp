<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kwitansi #{{ $tagihan->id }}</title>
</head>
<link rel="stylesheet" href="{{ asset('KWITANSI.css') }}">
<body>
<div class="doc">

    {{-- HEADER --}}
    <div class="hdr">
        <div class="hdr-left">
            <div class="logo-box">
                <img src="{{ asset('LUOGOOOO.png') }}" alt="Logo JAGONET">
            </div>
            <div>
                <div class="hdr-brand-name">JAGO NET</div>
                <div class="hdr-brand-sub">PT Sarana Media Cemerlang</div>
                <div class="hdr-brand-tagline">Jagonya Internet!</div>
            </div>
        </div>
        <div class="hdr-right">
            <div class="hdr-info">
                <div class="lbl-inv-caption">Invoice</div>
                <div class="lbl-inv-number">#INV/{{ $tagihan->id }}</div>
                <div class="lbl-inv-caption" style="margin-top:6px;">Jatuh Tempo</div>
                <div class="lbl-inv-date">
                    {{ $tagihan->jatuh_tempo ? \Carbon\Carbon::parse($tagihan->jatuh_tempo)->translatedFormat('d F Y') : '-' }}
                </div>
            </div>
        </div>
    </div>

    {{-- STATUS BAR --}}
    <div class="status-bar">
        <div class="status-bar-left">
            <div class="status-dot {{ $tagihan->status == 'lunas' ? 'dot-green' : 'dot-red' }}"></div>
            <span class="status-label">Status Pembayaran</span>
        </div>
        @if($tagihan->status == 'lunas')
            <div class="status-badge badge-lunas">&#10003;&nbsp;&nbsp;LUNAS</div>
        @else
            <div class="status-badge badge-belum">&#9888;&nbsp;&nbsp;BELUM BAYAR</div>
        @endif
    </div>

    {{-- BODY --}}
    <div class="body">

        {{-- INVOICE TO & DETAIL --}}
        <div class="two-col">
            <div class="col">
                <div class="section-head">Invoice To</div>
                <div class="field-row">
                    <span class="fk">Nama</span>
                    <span class="fs">:</span>
                    <span class="fv">{{ $tagihan->pelanggan->nama ?? '-' }}</span>
                </div>
                <div class="field-row">
                    <span class="fk">Telepon</span>
                    <span class="fs">:</span>
                    <span class="fv">{{ $tagihan->pelanggan->no_hp ?? '-' }}</span>
                </div>
                <div class="field-row">
                    <span class="fk">Alamat</span>
                    <span class="fs">:</span>
                    <span class="fv">{{ $tagihan->pelanggan->alamat ?? '-' }}</span>
                </div>
            </div>
            <div class="col" style="text-align:right;">
                <div class="section-head" style="text-align:right;">Detail</div>
                <div style="font-size:11px;color:#555;margin-bottom:2px;">
                    Invoice &nbsp;&nbsp;&nbsp;: <strong style="color:#111;">#INV/{{ $tagihan->id }}</strong>
                </div>
                <div style="font-size:11px;color:#555;">
                    Payment Date : <strong style="color:#111;">{{ $tagihan->jatuh_tempo ? \Carbon\Carbon::parse($tagihan->jatuh_tempo)->translatedFormat('d F Y') : '-' }}</strong>
                </div>
            </div>
        </div>

        {{-- TABEL DESKRIPSI --}}
        <table class="t1">
            <thead>
                <tr>
                    <th style="width:46%;">Deskripsi</th>
                    <th class="c" style="width:12%;">Qty</th>
                    <th class="c" style="width:22%;">Harga</th>
                    <th class="r" style="width:20%;">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ \Illuminate\Support\Str::title($tagihan->jenis_tagihan ?? 'Tagihan Bulanan') }} {{ $tagihan->pelanggan->layanan->nama_paket ?? '' }}</td>
                    <td class="c">1</td>
                    <td class="c">{{ number_format($tagihan->total, 0, ',', '.') }}</td>
                    <td class="r">Rp. {{ number_format($tagihan->total, 0, ',', '.') }}</td>
                </tr>
                @if($tagihan->keterangan)
                <tr>
                    <td colspan="3" style="color:#888;font-size:11px;font-style:italic;">{{ $tagihan->keterangan }}</td>
                    <td></td>
                </tr>
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="r">Jumlah</td>
                    <td class="r">Rp. {{ number_format($tagihan->total, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        {{-- TABEL RINCIAN PEMBAYARAN --}}
        <div class="t2-wrap">
            <div class="t2-title">Rincian Pembayaran</div>
            <table class="t2">
                <thead>
                    <tr>
                        <th style="width:20%;">Tanggal</th>
                        <th style="width:25%;">Pembayaran</th>
                        <th>Keterangan</th>
                        <th class="r" style="width:20%;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php
    $semuaBayar = $tagihan->pembayaran ?? collect();
    $totalBayar = $semuaBayar->sum('jumlah_bayar');
    $sisaBayar  = $tagihan->total - $totalBayar;
@endphp
@if($semuaBayar->count() > 0)
    @foreach($semuaBayar as $bayar)
    <tr>
        <td>{{ $bayar->tanggal_bayar ? \Carbon\Carbon::parse($bayar->tanggal_bayar)->format('d/m/Y') : '-' }}</td>
        <td>{{ $bayar->metode->nama_metode ?? 'KAS' }}</td>
        <td>{{ $tagihan->keterangan ?? '-' }}</td>
        <td class="r">Rp. {{ number_format($bayar->jumlah_bayar, 0, ',', '.') }}</td>
    </tr>
    @endforeach
@else
    <tr>
        <td colspan="4" style="text-align:center;color:#999;font-style:italic;padding:10px;">Belum Ada Pembayaran</td>
    </tr>
@endif
                </tbody>
                <tfoot>
                    <tr>
    <td colspan="3" style="text-align:right;font-weight:700;color:#222;">Jumlah Dibayar</td>
    <td class="r">Rp. {{ number_format($totalBayar, 0, ',', '.') }}</td>
</tr>
<tr>
    <td colspan="3" style="text-align:right;font-weight:700;color:#222;">Sisa</td>
    <td class="r-red">Rp. {{ number_format($sisaBayar, 0, ',', '.') }}</td>
</tr>
                </tfoot>
            </table>
        </div>

        <div style="height:28px;"></div>

        {{-- PAYMENT --}}
        <div class="section-head">Payment</div>
        <div class="payment-wrap">
            <div class="bank-info">
                <div class="bank-row">
                    <span class="bk">BCA</span>
                    <span>:</span>
                    <span class="bv">1777710303</span>
                </div>
                <div class="bank-row">
                    <span class="bk">BRI</span>
                    <span>:</span>
                    <span class="bv">004501003731397</span>
                </div>
                <div class="bank-row">
                    <span class="bk">Atas Nama</span>
                    <span>:</span>
                    <span class="bv">PT Sarana Media Cemerlang</span>
                </div>
            </div>
            <div class="konfirm-box">
                Konfirmasi pembayaran anda kepada<br>
                <strong>PT SARANA MEDIA CEMERLANG</strong><br>
                melalui email : finance@jagonet.co.id<br>
                dengan menyertakan nama pelanggan sesuai invoice pada<br>
                Bukti Transfer<br>
                Untuk konfirmasi pembayaran melalui whatsapp 0822-4944-3030
            </div>
        </div>

        <div class="auto-note">
            Invoice Ini Dibuat Secara Otomatis Oleh Sistem dan Dapat Dicetak Ulang<br>
            Dengan Melakukan Scan QRCode
        </div>

    </div>

    {{-- FOOTER --}}
    <div class="footer">
        <div class="footer-left">
            <div>
                <div class="footer-brand">
                    <img src="{{ asset('LUOGOOOO.png') }}" alt="Logo">
                    <span class="footer-brand-name">JAGO NET</span>
                </div>
                <div class="footer-links">
                    <a href="https://www.jago.net.id" class="footer-link">www.jago.net.id</a>
                    <span class="footer-sep">|</span>
                    <span class="footer-link">@jagonetmgt</span>
                    <span class="footer-sep">|</span>
                    <span class="footer-link">/jagonetmgt</span>
                </div>
            </div>
            <div class="footer-office">
                <strong style="color:#fff;">Head Office :</strong><br>
                Ds.Madigondo RT.16/RW.V<br>
                Kec.Takeran Kab.Magetan
            </div>
        </div>
        <div class="footer-tagline">Jagonya Internet!</div>
    </div>

    {{-- TOMBOL PRINT --}}
    <div class="print-bar">
        <a href="javascript:history.back()" class="btn-back">&#8592; Kembali</a>
        <button onclick="window.print()" class="btn-print">&#128424; Cetak Invoice</button>
    </div>

</div>
</body>
</html>