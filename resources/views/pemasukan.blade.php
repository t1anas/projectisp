<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pemasukan</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('inputform.css') }}">


</head>

<body>
<div style="display:flex; min-height:100vh;">

    <!-- SIDEBAR -->
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
        default => '/instalasi'
    };
@endphp

<a href="{{ url($instalasiUrl) }}" class="menu-item">
    <i class="bi bi-router"></i> Instalasi Baru
</a>

        @if(Auth::user()->role == 'admin')
        <a href="{{ url('/pemasukan') }}" class="menu-item active">
            <i class="bi bi-wallet2"></i> Pemasukan
        </a>
        @endif

        <div class="section-label">Pelanggan</div>

        <a href="{{ url('/pelanggan') }}" class="menu-item">
            <i class="bi bi-people"></i> Data Pelanggan
        </a>

        <!-- PROFILE -->
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
    <!-- END SIDEBAR -->

    <!-- MAIN CONTENT -->
    <div class="main-content" style="flex:1;">

        <!-- TOPBAR -->
        <div class="topbar">
            <div>
                <div class="page-title">Pemasukan</div>
                <div class="page-sub">Kelola transaksi keuangan pelanggan</div>
            </div>
            <div class="breadcrumb-area">
                <i class="bi bi-house-door"></i>
                <span class="sep">/</span>
                <span>Keuangan</span>
                <span class="sep">/</span>
                <span class="current">Pemasukan</span>
            </div>
        </div>

        <!-- CARD -->
        <div class="form-card">

            <div class="form-card-header">
                <div class="icon-wrap">
                    <i class="bi bi-wallet2"></i>
                </div>
                <div>
                    <div class="form-card-title">Menu Pemasukan</div>
                    <div class="form-card-sub">Pilih kategori yang ingin dikelola</div>
                </div>
            </div>

            <div class="nav-box-wrapper">

                <!--Pembayaran -->
                <a href="{{ url('/pembayaran') }}" class="nav-box nav-box-green">
                    <div class="nav-box-icon">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div>
                        <div class="nav-box-label">Transaksi</div>
                        <div class="nav-box-title">Data Pembayaran</div>
                    </div>
                    <div class="nav-box-desc">
                        Kelola riwayat pembayaran pelanggan, tambah transaksi baru, dan lacak status pembayaran.
                    </div>
                    <div class="nav-box-arrow">
                        <i class="bi bi-arrow-right"></i>
                    </div>
                </a>

                <!-- Tagihan -->
                <a href="{{ url('/tagihan') }}" class="nav-box nav-box-blue">
                    <div class="nav-box-icon">
                        <i class="bi bi-receipt"></i>
                    </div>
                    <div>
                        <div class="nav-box-label">Penagihan</div>
                        <div class="nav-box-title">Data Tagihan</div>
                    </div>
                    <div class="nav-box-desc">
                        Lihat dan kelola tagihan bulanan pelanggan, pantau status lunas atau belum bayar.
                    </div>
                    <div class="nav-box-arrow">
                        <i class="bi bi-arrow-right"></i>
                    </div>
                </a>

            </div>

        </div><!-- END CARD -->

    </div><!-- END MAIN CONTENT -->

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>