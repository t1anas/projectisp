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

<style>
body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: #f4f6f9;
}

/* ---- NAV BOX ---- */
.nav-box-wrapper {
    display: flex;
    gap: 24px;
    padding: 32px;
    flex-wrap: wrap;
}

.nav-box {
    flex: 1;
    min-width: 260px;
    border-radius: 20px;
    padding: 36px 32px;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 16px;
    text-decoration: none;
    transition: transform .25s, box-shadow .25s;
    position: relative;
    overflow: hidden;
    border: none;
}

.nav-box:hover {
    transform: translateY(-6px);
    box-shadow: 0 24px 48px rgba(0,0,0,0.13);
    text-decoration: none;
}

/* Box 1 — Pembayaran (hijau) */
.nav-box-green {
    background: linear-gradient(135deg, #22c55e, #16a34a);
    box-shadow: 0 12px 32px rgba(34,197,94,0.25);
    color: #fff;
}

.nav-box-green::before {
    content: '';
    position: absolute;
    width: 180px;
    height: 180px;
    border-radius: 50%;
    background: rgba(255,255,255,0.08);
    top: -40px;
    right: -40px;
}

.nav-box-green::after {
    content: '';
    position: absolute;
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: rgba(255,255,255,0.06);
    bottom: -20px;
    right: 60px;
}

/* Box 2 — Tagihan (biru) */
.nav-box-blue {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    box-shadow: 0 12px 32px rgba(59,130,246,0.25);
    color: #fff;
}

.nav-box-blue::before {
    content: '';
    position: absolute;
    width: 180px;
    height: 180px;
    border-radius: 50%;
    background: rgba(255,255,255,0.08);
    top: -40px;
    right: -40px;
}

.nav-box-blue::after {
    content: '';
    position: absolute;
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: rgba(255,255,255,0.06);
    bottom: -20px;
    right: 60px;
}

.nav-box-icon {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    background: rgba(255,255,255,0.18);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
    color: #fff;
    position: relative;
    z-index: 1;
}

.nav-box-label {
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: rgba(255,255,255,0.75);
    position: relative;
    z-index: 1;
}

.nav-box-title {
    font-size: 22px;
    font-weight: 800;
    color: #fff;
    line-height: 1.2;
    position: relative;
    z-index: 1;
}

.nav-box-desc {
    font-size: 13px;
    color: rgba(255,255,255,0.75);
    line-height: 1.6;
    position: relative;
    z-index: 1;
}

.nav-box-arrow {
    margin-top: auto;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(255,255,255,0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    color: #fff;
    position: relative;
    z-index: 1;
    transition: background .2s;
}

.nav-box:hover .nav-box-arrow {
    background: rgba(255,255,255,0.35);
}
</style>
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

        <a href="{{ url('/instalasi') }}" class="menu-item">
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