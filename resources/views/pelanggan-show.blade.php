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

    const W = 760, H = 440;
    const canvas  = document.createElement('canvas');
    canvas.width  = W;
    canvas.height = H;
    const ctx     = canvas.getContext('2d');

    function roundRect(x, y, w, h, r) {
        ctx.beginPath();
        ctx.moveTo(x + r, y);
        ctx.lineTo(x + w - r, y);
        ctx.quadraticCurveTo(x + w, y, x + w, y + r);
        ctx.lineTo(x + w, y + h - r);
        ctx.quadraticCurveTo(x + w, y + h, x + w - r, y + h);
        ctx.lineTo(x + r, y + h);
        ctx.quadraticCurveTo(x, y + h, x, y + h - r);
        ctx.lineTo(x, y + r);
        ctx.quadraticCurveTo(x, y, x + r, y);
        ctx.closePath();
    }

    const logoImg = new Image();
    const qrImg   = new Image();

    const svgData   = new XMLSerializer().serializeToString(svg);
    const svgBase64 = 'data:image/svg+xml;base64,' + btoa(unescape(encodeURIComponent(svgData)));

    const loadLogo = new Promise(res => { logoImg.onload = res; logoImg.src = '{{ asset("LUOGOOOO.png") }}'; });
    const loadQR   = new Promise(res => { qrImg.onload  = res; qrImg.src   = svgBase64; });

    Promise.all([loadLogo, loadQR]).then(() => {

        const grad = ctx.createLinearGradient(0, 0, 0, H);
        grad.addColorStop(0, '#FAE59E');
        grad.addColorStop(1, '#09973B');
        roundRect(0, 0, W, H, 22);
        ctx.fillStyle = grad;
        ctx.fill();

        ctx.strokeStyle = 'rgba(255,255,255,0.12)';
        ctx.lineWidth   = 1;
        ctx.beginPath();
        ctx.moveTo(0, 80);
        ctx.lineTo(W, 80);
        ctx.stroke();

        ctx.drawImage(logoImg, 14, 18, 44, 44);

        ctx.fillStyle = '#ffffff';
        ctx.font      = 'bold 28px Arial';
        ctx.textAlign = 'left';
        ctx.fillText('JAGONET', 72, 47);

        ctx.fillStyle = 'rgba(255,255,255,0.5)';
        ctx.font      = '11px Arial';
        ctx.fillText('Internet Service Provider', 73, 64);

        const bodyTop = 100, bodyLeft = 30;

        ctx.fillStyle = 'rgba(255,255,255,0.5)';
        ctx.font      = '11px Arial';
        ctx.fillText('NAMA PELANGGAN', bodyLeft, bodyTop + 18);

        ctx.fillStyle = '#ffffff';
        ctx.font      = 'bold 22px Arial';
        ctx.fillText('{{ addslashes($pelanggan->nama) }}', bodyLeft, bodyTop + 44);

        ctx.fillStyle = 'rgba(255,255,255,0.5)';
        ctx.font      = '11px Arial';
        ctx.fillText('PAKET LAYANAN', bodyLeft, bodyTop + 76);

        ctx.fillStyle = '#ffffff';
        ctx.font      = 'bold 17px Arial';
        ctx.fillText('{{ addslashes($pelanggan->layanan->nama_paket ?? "-") }}', bodyLeft, bodyTop + 98);

        ctx.fillStyle = 'rgba(255,255,255,0.5)';
        ctx.font      = '11px Arial';
        ctx.fillText('NAMA LAYANAN', bodyLeft, bodyTop + 126);

        ctx.fillStyle = '#e0eaff';
        ctx.font      = '14px Arial';
        ctx.fillText('{{ addslashes($pelanggan->nama_layanan ?? "-") }}', bodyLeft, bodyTop + 146);

        ctx.fillStyle = 'rgba(255,255,255,0.5)';
        ctx.font      = '11px Arial';
        ctx.fillText('ALAMAT', bodyLeft, bodyTop + 174);

        ctx.fillStyle = 'rgba(255,255,255,0.75)';
        ctx.font      = '13px Arial';
        const alamatFull = '{{ addslashes($pelanggan->alamat ?? "-") }}';
        const words = alamatFull.split(' ');
        let line1 = '', line2 = '';
        words.forEach(w => {
            if ((line1 + ' ' + w).trim().length <= 42) line1 = (line1 + ' ' + w).trim();
            else line2 = (line2 + ' ' + w).trim();
        });
        ctx.fillText(line1, bodyLeft, bodyTop + 194);
        if (line2) ctx.fillText(line2, bodyLeft, bodyTop + 210);

        ctx.fillStyle = 'rgba(255,255,255,0.4)';
        ctx.font      = '13px "Courier New"';
        ctx.fillText('{{ $pelanggan->kode_pelanggan ?? "-" }}', bodyLeft, H - 52);

        @if($pelanggan->status === 'aktif' || strtolower($pelanggan->status) === 'aktif')
        ctx.fillStyle = '#22c55e';
        @elseif($pelanggan->status === 'isolir')
        ctx.fillStyle = '#f59e0b';
        @else
        ctx.fillStyle = '#ef4444';
        @endif
        roundRect(bodyLeft, H - 42, 56, 22, 11);
        ctx.fill();
        ctx.fillStyle = '#ffffff';
        ctx.font      = 'bold 11px Arial';
        ctx.textAlign = 'center';
        ctx.fillText('{{ strtoupper($pelanggan->status) }}', bodyLeft + 28, H - 26);
        ctx.textAlign = 'left';

        ctx.strokeStyle = 'rgba(255,255,255,0.15)';
        ctx.lineWidth   = 1;
        ctx.beginPath();
        ctx.moveTo(W - 220, 90);
        ctx.lineTo(W - 220, H - 20);
        ctx.stroke();

        const qrSize = 180, qrX = W - 210, qrY = 96;

        ctx.fillStyle = '#ffffff';
        roundRect(qrX - 10, qrY - 10, qrSize + 20, qrSize + 32, 10);
        ctx.fill();

        ctx.drawImage(qrImg, qrX, qrY, qrSize, qrSize);

        ctx.fillStyle = '#888888';
        ctx.font      = '10px Arial';
        ctx.textAlign = 'center';
        ctx.fillText('SCAN QR CODE', qrX + qrSize / 2, qrY + qrSize + 16);
        ctx.textAlign = 'left';

        ctx.strokeStyle = 'rgba(255,255,255,0.08)';
        ctx.lineWidth   = 1;
        ctx.beginPath();
        ctx.moveTo(0, H - 30);
        ctx.lineTo(W, H - 30);
        ctx.stroke();

        ctx.fillStyle = 'rgba(255,255,255,0.3)';
        ctx.font      = '10px Arial';
        ctx.fillText('jagonet.id  ·  Layanan 24/7', bodyLeft, H - 12);

        [5, 8, 11, 15].forEach((h, i) => {
            ctx.fillStyle = 'rgba(255,255,255,0.5)';
            ctx.fillRect(W - 60 + i * 9, H - 14 - h, 5, h);
        });

        const a    = document.createElement('a');
        a.href     = canvas.toDataURL('image/png');
        a.download = filename;
        a.click();
    });
}
</script>

</body>
</html>