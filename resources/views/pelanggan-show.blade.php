<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pelanggan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('inputform.css') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
</head>
<body>

<div style="display:flex; min-height:100vh;">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
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

    <!-- CONTENT -->
    <div class="container py-4 d-flex justify-content-center" style="margin-left:240px;">
        <div class="content-wrapper">

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

            <!-- MAIN CARD -->
            <div class="detail-card">

                <!-- IDENTITAS PELANGGAN -->
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

                <!-- QUICK STATS -->
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
                        <div class="stat-label">Tanggal Aktivasi</div>
                        <div class="stat-value">
                            {{ \Carbon\Carbon::parse($pelanggan->created_at)->format('d M Y') }}
                        </div>
                    </div>
                </div>

                <!-- BODY -->
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

                        <div class="qr-section">
                            <div class="qr-section-label">
                                <i class="bi bi-qr-code"></i> QR Pelanggan
                            </div>
                            <div class="qr-inner-wrap">
                                <div id="qrcode"></div>
                            </div>
                            <button class="btn-download-qr" onclick="downloadQR()">
                                <i class="bi bi-download"></i> Unduh QR Code
                            </button>
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<script>
    const pelangganData = [
        "Kode: {{ $pelanggan->kode_pelanggan ?? '' }}",
        "Nama: {{ $pelanggan->nama }}",
        "NIK: {{ $pelanggan->nik ?? '' }}",
        "Site: {{ $pelanggan->site->nama_site ?? '' }}",
        "Paket: {{ $pelanggan->layanan->nama_paket ?? '' }}",
        "Aktivasi: {{ \Carbon\Carbon::parse($pelanggan->created_at)->format('d M Y') }}"
    ].join(' | ');

    const qrInstance = new QRCode(document.getElementById("qrcode"), {
        text: pelangganData,
        width: 140,
        height: 140,
        colorDark: "#111827",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
    });

    function downloadQR() {
        setTimeout(function () {
            const img    = document.querySelector("#qrcode img");
            const canvas = document.querySelector("#qrcode canvas");
            const a      = document.createElement("a");

            const filename = "QR_{{ $pelanggan->kode_pelanggan ?? 'pelanggan' }}_{{ Str::slug($pelanggan->nama) }}.png";

            if (canvas) {
                a.href     = canvas.toDataURL("image/png");
                a.download = filename;
                a.click();
            } else if (img) {
                fetch(img.src)
                    .then(r => r.blob())
                    .then(blob => {
                        a.href     = URL.createObjectURL(blob);
                        a.download = filename;
                        a.click();
                        URL.revokeObjectURL(a.href);
                    });
            }
        }, 300);
    }
</script>

</body>
</html>