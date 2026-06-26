<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan QR - Jagonet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('inputform.css') }}">
    <link rel="stylesheet" href="{{ asset('scan.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>


</head>
<body>

<div class="page-layout">

    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <div class="sidebar" id="appSidebar">
        <div class="sidebar-header">
            <div class="hamburger" onclick="toggleSidebar()"><span></span><span></span><span></span></div>
            <span class="logo-text">JAGONET</span>
        </div>

        <div class="section-label">Main Board</div>

        <a href="{{ Auth::user()->dashboard_url }}" class="menu-item">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="{{ url('/layanan') }}" class="menu-item active">
            <i class="bi bi-wifi"></i> Data Layanan
        </a>

        @php
            $instalasiUrl = match(Auth::user()->role) {
                'cs'    => '/instalasi',
                'admin' => '/approve',
                'noc'   => '/instalasi-noc',
                default => '/instalasi'
            };
        @endphp

        <a href="{{ url($instalasiUrl) }}" class="menu-item">
            <i class="bi bi-router"></i> Instalasi Baru
        </a>

        @if(Auth::user()->role == 'cs')
        <a href="{{ route('agenda.cs') }}" class="menu-item">
            <i class="bi bi-arrow-down-up"></i>Agenda CS
        </a>
        @endif

        @if(Auth::user()->role == 'noc')
            <a href="{{ url('/agenda-noc') }}" class="menu-item">
                <i class="bi bi-journal-check"></i> Agenda NOC
            </a>
        @endif

        @if(Auth::user()->role == 'admin')
            <a href="{{ url('/pemasukan') }}" class="menu-item">
                <i class="bi bi-wallet2"></i> Pemasukan
            </a>
        @endif

        <div class="section-label">Pelanggan</div>

        <a href="{{ url('/pelanggan') }}" class="menu-item">
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

        <div class="d-flex align-items-center gap-3" style="margin-bottom: 16px;">
        <button type="button" class="btn-sidebar-toggle d-lg-none" onclick="toggleSidebar()">
            <i class="bi bi-list"></i>
        </button>
    </div>
    <div class="main-content scan-page-content">

        <div class="scanner-card">

            <div class="scanner-header">
                <div class="scanner-header-top">
                    <div class="scanner-icon"><i class="bi bi-qr-code-scan"></i></div>
                    <div>
                        <div class="scanner-title">Scan QR Pelanggan</div>
                        <div class="scanner-sub">Arahkan kamera ke QR code pelanggan</div>
                    </div>
                </div>
            </div>

            <div class="scanner-body">

                <div class="viewfinder-wrap">
                    <div class="corner corner-tl"></div>
                    <div class="corner corner-tr"></div>
                    <div class="corner corner-bl"></div>
                    <div class="corner corner-br"></div>
                    <div class="scan-line" id="scanLine"></div>
                    <div id="reader"></div>
                </div>

                <div class="status-box" id="statusBox">
                    <i class="bi bi-camera"></i> Menunggu kamera...
                </div>

                <div id="result-card">
                    <div class="result-label"><i class="bi bi-check-circle-fill"></i> QR Terdeteksi</div>
                    <div class="result-nama" id="resultNama">—</div>
                    <div class="result-kode" id="resultKode">—</div>
                    <a id="btnBuka" href="#" class="btn-buka">
                        <i class="bi bi-person-lines-fill"></i> Lihat Detail Pelanggan
                    </a>
                    <button class="btn-reset" onclick="resetScanner()">
                        <i class="bi bi-arrow-counterclockwise"></i> Scan Lagi
                    </button>
                </div>

                <div class="input-group-custom">
                    <input type="text" id="manualInput"
                           placeholder="Masukkan kode / nama pelanggan"
                           onkeydown="if(event.key==='Enter') cariManual()">
                    <button class="btn-lanjut" onclick="cariManual()">Lanjutkan</button>
                </div>

            </div>
        </div>

    </div>
</div>

<script>
    function toggleSidebar() {
    document.getElementById('appSidebar').classList.toggle('show');
    document.getElementById('sidebarOverlay').classList.toggle('show');
}
    let html5QrCode;

    function onScanSuccess(url) {
        html5QrCode.stop();
        document.getElementById('scanLine').style.display = 'none';

        const statusBox = document.getElementById('statusBox');
        statusBox.className = 'status-box success';
        statusBox.innerHTML = '<i class="bi bi-check-circle-fill"></i> QR berhasil dibaca! Mengalihkan...';

        setTimeout(() => { window.location.href = url; }, 1000);
    }

    function onScanError() {}

    function startScanner() {
        html5QrCode = new Html5Qrcode("reader");
        html5QrCode.start(
            { facingMode: "environment" },
            { fps: 10, qrbox: { width: 220, height: 220 } },
            onScanSuccess,
            onScanError
        ).then(() => {
            const s = document.getElementById('statusBox');
            s.className = 'status-box success';
            s.innerHTML = '<i class="bi bi-camera-video-fill"></i> Kamera aktif';
        }).catch(() => {
            const s = document.getElementById('statusBox');
            s.className = 'status-box error';
            s.innerHTML = '<i class="bi bi-camera-video-off-fill"></i> Kamera tidak bisa diakses. Cek izin browser.';
        });
    }

    function resetScanner() {
        document.getElementById('result-card').style.display = 'none';
        document.getElementById('scanLine').style.display = 'block';
        const s = document.getElementById('statusBox');
        s.className = 'status-box';
        s.innerHTML = '<i class="bi bi-camera"></i> Menunggu kamera...';
        startScanner();
    }

    function cariManual() {
        const val = document.getElementById('manualInput').value.trim();
        if (!val) return;
        window.location.href = '/scan/' + encodeURIComponent(val);
    }

    startScanner();
</script>

</body>
</html>