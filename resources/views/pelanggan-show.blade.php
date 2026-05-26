<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pelanggan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('inputform.css') }}">
    <style>
        #qrcode svg { width: 140px !important; height: 140px !important; }
    </style>
</head>
<body>

<div style="display:flex; min-height:100vh;">

    {{-- SIDEBAR --}}
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="hamburger">
                <span></span><span></span><span></span>
            </div>
            <span class="logo-text">JAGONET</span>
        </div>

        <div class="section-label">Main Board</div>

        <a href="{{ Auth::user()->dashboard_url }}" class="menu-item">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="{{ url('/layanan') }}" class="menu-item">
            <i class="bi bi-wifi"></i> Data Layanan
        </a>

        @php
            $instalasiUrl = match(Auth::user()->role) {
                'cs'    => '/instalasi',
                'admin' => '/approve',
                'noc'   => '/instalasi-noc',
                default => '/instalasi',
            };
        @endphp

        <a href="{{ url($instalasiUrl) }}" class="menu-item">
            <i class="bi bi-router"></i> Instalasi Baru
        </a>

        @if(Auth::user()->role === 'admin')
            <a href="{{ url('/pemasukan') }}" class="menu-item">
                <i class="bi bi-wallet2"></i> Pemasukan
            </a>
        @endif

        <div class="section-label">Pelanggan</div>

        <a href="{{ url('/pelanggan') }}" class="menu-item active">
            <i class="bi bi-people"></i> Data Pelanggan
        </a>

        <div class="profile-section">
            <div class="admin-card">
                <div class="admin-avatar">
                    <i class="bi bi-person-fill text-white"></i>
                </div>
                <div>
                    <div class="admin-role">{{ strtoupper(Auth::user()->role) }}</div>
                    <div class="admin-name">{{ Auth::user()->name }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="bi bi-box-arrow-right"></i> LOG OUT
                </button>
            </form>
        </div>
    </div>
    {{-- END SIDEBAR --}}

    {{-- CONTENT --}}
    <div class="container py-4 d-flex justify-content-center" style="margin-left:240px;">
        <div class="content-wrapper">

            {{-- HEADER --}}
            <div class="header-card">
                <div class="top-row">
                    <div class="header-card">
                        <a href="{{ url('/pelanggan') }}" class="btn-kembali" title="Kembali">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                        <div class="header-icon-box">
                            <i class="bi bi-person-lines-fill"></i>
                        </div>
                        <div>
                            <div class="header-label-text">Detail Pelanggan</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="detail-card">

                <div class="card-identity-row">
                    <div class="card-identity-left">
                        <div class="avatar">
                            {{ strtoupper(substr($pelanggan->nama, 0, 1)) }}
                        </div>
                        <div>
                            <div class="identity-name">{{ $pelanggan->nama }}</div>
                            <div class="identity-nik">Kode Pelanggan : {{ $pelanggan->kode_pelanggan ?? '-' }}</div>
                        </div>
                    </div>
                    @if($pelanggan->status === 'isolir')
                    <span class="status-pill status-pending">
                        <i class="bi bi-hourglass-split"></i> Isolir
                    </span>
                    @elseif(strtolower($pelanggan->status) === 'aktif')
                    <span class="status-pill status-active">
                        <i class="bi bi-check-circle-fill"></i> Aktif
                    </span>
                    @else
                    <span class="status-pill status-nonactive">
                        <i class="bi bi-x-circle-fill"></i> Nonaktif
                    </span>
                    @endif
                </div>

                <div class="quick-stats">
                    <div class="stat-item">
                        <div class="stat-label">Site</div>
                        <div class="stat-value">{{ $pelanggan->site->nama_site ?? '-' }}</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">Paket Layanan</div>
                        <div class="stat-value">{{ $pelanggan->layanan->nama_paket ?? '-' }}</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">Nama Layanan</div>
                        <div class="stat-value">
                            {{ $pelanggan->nama_layanan ?? '-' }}
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">Catatan NOC</div>
                        <div class="stat-value">
                            {{ $pelanggan->catatan_noc ?? '-' }}
                        </div>
                    </div>
                </div>

                {{-- BODY --}}
                <div class="detail-body">

                    <div class="section-title">
                        <i class="bi bi-person"></i> Data Pribadi
                    </div>

                    <div class="row-pribadi">

                        <div>
                            <div class="detail-item">
                                <div class="detail-label">NIK</div>
                                <div class="detail-value">{{ $pelanggan->nik ?? '-' }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Alamat</div>
                                <div class="detail-value">{{ $pelanggan->alamat ?? '-' }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Lokasi</div>
                                <div class="detail-value">
                                    @if($pelanggan->lokasi_link)
                                        <a href="{{ $pelanggan->lokasi_link }}" target="_blank"
                                           style="color:#0f9d58; text-decoration:none; font-weight:600; display:inline-flex; align-items:center; gap:4px;">
                                            <i class="bi bi-geo-alt-fill"></i> Buka Google Maps
                                            <i class="bi bi-box-arrow-up-right" style="font-size:11px;"></i>
                                        </a>
                                    @else
                                        <span style="color:#9ca3af;">—</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="detail-item">
                                <div class="detail-label">Site</div>
                                <div class="detail-value">{{ $pelanggan->site->nama_site ?? '-' }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Tanggal Aktivasi</div>
                                <div class="detail-value">
                                    {{ \Carbon\Carbon::parse($pelanggan->created_at)->format('d M Y') }}
                                </div>
                            </div>
                        </div>

                        <div style="display:flex; flex-direction:column; align-items:center; gap:10px;">
                            <div style="font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:.5px;">
                                <i class="bi bi-qr-code"></i> QR Pelanggan
                            </div>

                            @if($pelanggan->qr_code)
                                <div id="qrcode" style="width:140px; height:140px; overflow:hidden; display:flex; align-items:center; justify-content:center;">
                                    {!! $pelanggan->qr_code !!}
                                </div>
                            @else
                                <div style="width:140px; height:140px; display:flex; flex-direction:column; align-items:center; justify-content:center; background:#f9fafb; border-radius:10px; border:1px dashed #d1d5db;">
                                    <i class="bi bi-qr-code" style="font-size:32px; color:#d1d5db;"></i>
                                    <span style="font-size:11px; color:#9ca3af; margin-top:6px;">Belum ada QR</span>
                                </div>
                            @endif

                            <button class="btn-download-qr" onclick="downloadQR()" style="width:140px;">
                                <i class="bi bi-download"></i> Unduh QR
                            </button>
                        </div>

                    </div>
                </div>
            </div>
            {{-- END MAIN CARD --}}

        </div>
    </div>
    {{-- END CONTENT --}}

</div>

<script>
function downloadQR() {
    const svg      = document.querySelector("#qrcode svg");
    const filename = "Kartu_{{ $pelanggan->kode_pelanggan ?? 'pelanggan' }}.png";
    if (!svg) return alert('QR belum tersedia.');

    const W = 680, H = 420;
    const canvas  = document.createElement('canvas');
    canvas.width  = W;
    canvas.height = H;
    const ctx     = canvas.getContext('2d');

    function rr(x, y, w, h, r) {
        ctx.beginPath();
        ctx.moveTo(x + r, y); ctx.lineTo(x + w - r, y);
        ctx.quadraticCurveTo(x + w, y, x + w, y + r);
        ctx.lineTo(x + w, y + h - r);
        ctx.quadraticCurveTo(x + w, y + h, x + w - r, y + h);
        ctx.lineTo(x + r, y + h);
        ctx.quadraticCurveTo(x, y + h, x, y + h - r);
        ctx.lineTo(x, y + r);
        ctx.quadraticCurveTo(x, y, x + r, y);
        ctx.closePath();
    }

    function wrapText(text, maxW, font) {
        ctx.font = font;
        const words = text.split(' ');
        const lines = [];
        let cur = '';
        words.forEach(w => {
            const test = cur ? cur + ' ' + w : w;
            if (ctx.measureText(test).width <= maxW) { cur = test; }
            else { if (cur) lines.push(cur); cur = w; }
        });
        if (cur) lines.push(cur);
        return lines;
    }

    const logoImg = new Image();
    const qrImg   = new Image();
    const svgData   = new XMLSerializer().serializeToString(svg);
    const svgBase64 = 'data:image/svg+xml;base64,' + btoa(unescape(encodeURIComponent(svgData)));

    const loadLogo = new Promise(res => { logoImg.onload = res; logoImg.src = '{{ asset("LUOGOOOO.png") }}'; });
    const loadQR   = new Promise(res => { qrImg.onload  = res; qrImg.src   = svgBase64; });

    Promise.all([loadLogo, loadQR]).then(() => {

        rr(0, 0, W, H, 22);
        ctx.fillStyle = '#ffffff'; ctx.fill();

        const HEADER_H = 72;

        const hGrad = ctx.createLinearGradient(0, 0, W, 0);
        hGrad.addColorStop(0,   '#0db84a');
        hGrad.addColorStop(0.5, '#09973B');
        hGrad.addColorStop(1,   '#076b2b');
        ctx.fillStyle = hGrad;
        ctx.fillRect(0, 0, W, HEADER_H);

        ctx.save();
        ctx.beginPath(); ctx.arc(W - 20, -20, 90, 0, Math.PI * 2);
        ctx.strokeStyle = 'rgba(255,255,255,0.06)'; ctx.lineWidth = 22; ctx.stroke();
        ctx.beginPath(); ctx.arc(W + 10, HEADER_H + 10, 60, 0, Math.PI * 2);
        ctx.strokeStyle = 'rgba(255,255,255,0.04)'; ctx.lineWidth = 14; ctx.stroke();
        ctx.restore();

        const aGrad = ctx.createLinearGradient(0, 0, W, 0);
        aGrad.addColorStop(0, '#FF6B2B'); aGrad.addColorStop(1, '#FF9A5C');
        ctx.fillStyle = aGrad;
        ctx.fillRect(0, HEADER_H - 3, W, 3);

        const LOGO_X = 20, LOGO_Y = 16, LOGO_S = 40;
        ctx.fillStyle = 'rgba(255,255,255,0.18)';
        rr(LOGO_X, LOGO_Y, LOGO_S, LOGO_S, 9); ctx.fill();
        ctx.strokeStyle = 'rgba(255,255,255,0.3)'; ctx.lineWidth = 1;
        rr(LOGO_X, LOGO_Y, LOGO_S, LOGO_S, 9); ctx.stroke();
        ctx.drawImage(logoImg, LOGO_X, LOGO_Y, LOGO_S, LOGO_S);

        const BX = LOGO_X + LOGO_S + 12;
        ctx.fillStyle = '#ffffff'; ctx.font = 'bold 16px Arial'; ctx.textAlign = 'left';
        ctx.fillText('JAGONET', BX, LOGO_Y + 16);
        ctx.fillStyle = 'rgba(255,255,255,0.5)'; ctx.font = '7px Arial';
        ctx.fillText('INTERNET SERVICE PROVIDER', BX, LOGO_Y + 28);

        const DIV_X = BX + 150;
        ctx.strokeStyle = 'rgba(255,255,255,0.25)'; ctx.lineWidth = 1;
        ctx.beginPath(); ctx.moveTo(DIV_X, 18); ctx.lineTo(DIV_X, HEADER_H - 10); ctx.stroke();

        const NX       = DIV_X + 16;
        const nameMaxW = W - NX - 20;

        ctx.fillStyle = 'rgba(255,255,255,0.55)'; ctx.font = '7px Arial';
        ctx.fillText('PELANGGAN', NX, LOGO_Y + 8);

        const namaText  = '{{ addslashes($pelanggan->nama) }}';
        const namaLines = wrapText(namaText, nameMaxW, 'bold 15px Arial');
        ctx.fillStyle = '#ffffff';
        namaLines.slice(0, 2).forEach((line, i) => {
            ctx.font = 'bold 15px Arial';
            ctx.fillText(line, NX, LOGO_Y + 22 + i * 17);
        });

        const afterNamaY = LOGO_Y + 22 + Math.min(namaLines.length, 2) * 17 + 3;
        ctx.fillStyle = 'rgba(255,255,255,0.65)'; ctx.font = '9px "Courier New"';
        ctx.fillText('{{ $pelanggan->kode_pelanggan ?? "JGN-2025-00001" }}', NX, afterNamaY);

        const LEFT_X = 0;
        const LEFT_W = 228;

        function cardPath() {
            ctx.beginPath();
            ctx.moveTo(LEFT_X, HEADER_H);
            ctx.lineTo(LEFT_X + LEFT_W, HEADER_H);
            ctx.lineTo(LEFT_X + LEFT_W, H);
            ctx.lineTo(LEFT_X, H);
            ctx.closePath();
        }

        ctx.save();
        cardPath();
        const cardGrad = ctx.createLinearGradient(LEFT_X, HEADER_H, LEFT_X, H);
        cardGrad.addColorStop(0,   '#0db84a');
        cardGrad.addColorStop(0.5, '#09973B');
        cardGrad.addColorStop(1,   '#065f26');
        ctx.fillStyle = cardGrad; ctx.fill();
        ctx.restore();

        ctx.save();
        cardPath(); ctx.clip();
        ctx.beginPath(); ctx.arc(LEFT_X + LEFT_W - 10, H - 10, 80, 0, Math.PI * 2);
        ctx.strokeStyle = 'rgba(255,255,255,0.06)'; ctx.lineWidth = 28; ctx.stroke();
        ctx.restore();

        let cy = HEADER_H + 16;
        const TX = LEFT_X + 14;
        const TW = LEFT_W - 28;

        function infoBlock(label, lines, font) {
            ctx.fillStyle = 'rgba(255,255,255,0.6)'; ctx.font = '7px Arial'; ctx.textAlign = 'left';
            ctx.fillText(label, TX, cy); cy += 13;
            ctx.fillStyle = '#ffffff'; ctx.font = font || 'bold 12px Arial';
            lines.forEach(l => { ctx.fillText(l, TX, cy); cy += 14; });
            cy += 8;
        }

        function divider() {
            ctx.strokeStyle = 'rgba(255,255,255,0.15)'; ctx.lineWidth = 0.5;
            ctx.beginPath(); ctx.moveTo(TX, cy); ctx.lineTo(TX + TW, cy); ctx.stroke();
            cy += 10;
        }

        const paketLines = wrapText('{{ addslashes($pelanggan->layanan->nama_paket ?? "-") }}', TW, 'bold 12px Arial');
        infoBlock('PAKET', paketLines, 'bold 12px Arial');
        divider();

        const layananLines = wrapText('{{ addslashes($pelanggan->nama_layanan ?? "-") }}', TW, '11px Arial');
        infoBlock('NAMA LAYANAN', layananLines.slice(0, 2), '11px Arial');
        divider();

        const alamatLines = wrapText('{{ addslashes($pelanggan->alamat ?? "-") }}', TW, '10px Arial');
        infoBlock('ALAMAT', alamatLines.slice(0, 3), '10px Arial');
        divider();

        infoBlock('NO. HP', ['{{ addslashes($pelanggan->no_hp ?? "-") }}'], '11px Arial');

        const PILL_Y = H - 36;
        @if($pelanggan->status === 'aktif')
            const statusColor = '#065f26', statusBg = 'rgba(255,255,255,0.9)', statusBdr = 'rgba(255,255,255,0.5)';
        @elseif($pelanggan->status === 'isolir')
            const statusColor = '#92400e', statusBg = 'rgba(255,237,180,0.95)', statusBdr = 'rgba(251,191,36,0.6)';
        @else
            const statusColor = '#7f1d1d', statusBg = 'rgba(254,202,202,0.95)', statusBdr = 'rgba(248,113,113,0.6)';
        @endif

        rr(TX, PILL_Y, 86, 20, 10);
        ctx.fillStyle = statusBg; ctx.fill();
        ctx.strokeStyle = statusBdr; ctx.lineWidth = 1;
        rr(TX, PILL_Y, 86, 20, 10); ctx.stroke();
        ctx.fillStyle = statusColor;
        ctx.beginPath(); ctx.arc(TX + 12, PILL_Y + 10, 3.5, 0, Math.PI * 2); ctx.fill();
        ctx.font = 'bold 8px Arial'; ctx.textAlign = 'center';
        ctx.fillText('{{ strtoupper($pelanggan->status) }}', TX + 50, PILL_Y + 13);
        ctx.textAlign = 'left';

        const VD_X = LEFT_W + 14;
        ctx.strokeStyle = '#e5e7eb'; ctx.lineWidth = 0.5;
        ctx.beginPath(); ctx.moveTo(VD_X, HEADER_H + 10); ctx.lineTo(VD_X, H - 16); ctx.stroke();

        const QR_X      = VD_X + 14;
        const QR_W      = W - QR_X - 14;
        const QR_AREA_Y = HEADER_H + 10;
        const QR_AREA_H = H - QR_AREA_Y - 16;

        rr(QR_X, QR_AREA_Y, QR_W, QR_AREA_H, 12);
        ctx.fillStyle = '#fafafa'; ctx.fill();
        ctx.strokeStyle = '#e8e8e8'; ctx.lineWidth = 1;
        rr(QR_X, QR_AREA_Y, QR_W, QR_AREA_H, 12); ctx.stroke();

        const QR_PAD = 28;
        const QR_S   = Math.min(QR_W, QR_AREA_H) - QR_PAD * 2;
        const QR_LX  = QR_X + (QR_W - QR_S) / 2;
        const QR_LY  = QR_AREA_Y + (QR_AREA_H - QR_S) / 2 - 8;
        ctx.drawImage(qrImg, QR_LX, QR_LY, QR_S, QR_S);

        ctx.fillStyle = '#9ca3af'; ctx.font = '8px Arial'; ctx.textAlign = 'center';
        ctx.fillText('SCAN QR CODE', QR_X + QR_W / 2, QR_AREA_Y + QR_AREA_H - 7);
        ctx.textAlign = 'left';

        const link = document.createElement('a');
        link.href     = canvas.toDataURL('image/png');
        link.download = filename;
        link.click();
    });
}
</script>

</body>
</html>