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
                            <div class="header-label-text">Informasi Pelanggan</div>
                            <div class="header-label-sub">Detail Pelanggan</div>
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
                    <div class="status-badge">
                        <span class="status-dot"></span> Aktif
                    </div>
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
    const filename = "QR_{{ $pelanggan->kode_pelanggan ?? 'pelanggan' }}.png";

    if (!svg) return alert('QR belum tersedia.');

    const padding = 20; 
    const size    = 300;
    const total   = size + (padding * 2);

    const svgData   = new XMLSerializer().serializeToString(svg);
    const svgBase64 = 'data:image/svg+xml;base64,' + btoa(unescape(encodeURIComponent(svgData)));

    const canvas  = document.createElement('canvas');
    canvas.width  = total;
    canvas.height = total;

    const ctx = canvas.getContext('2d');
    const img = new Image();

    img.onload = function () {
        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, total, total);

        ctx.drawImage(img, padding, padding, size, size);

        const a    = document.createElement('a');
        a.href     = canvas.toDataURL('image/png');
        a.download = filename;
        a.click();
    };

    img.src = svgBase64;
}
</script>

</body>
</html>