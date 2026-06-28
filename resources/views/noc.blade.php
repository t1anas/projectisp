<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard NOC - Jagonet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('inputform.css') }}">

    <style>
        :root {
            --jago-orange: #faf8f7;
            --jago-orange-dark: #ffffff;
            --jago-sidebar: linear-gradient(180deg, #09973B 0%, #FAE59E 100%);
            --jago-sidebar-hover: rgba(192,96,26,0.15);
            --jago-sidebar-active: rgba(192,96,26,0.25);
        }

        @media (min-width: 992px) {
            .main-content { margin-left: 240px; padding: 32px 28px; min-height: 100vh; }
        }
        @media (max-width: 991px) {
            .main-content { padding: 20px 16px; min-height: 100vh; }
        }

        /* STAT CARDS */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 28px;
        }
        @media (max-width: 768px) {
            .stat-grid { grid-template-columns: 1fr; }
        }
        @media (min-width: 769px) and (max-width: 991px) {
            .stat-grid { grid-template-columns: repeat(2, 1fr); }
        }

        .stat-card {
            background: white; border-radius: 16px; padding: 24px 22px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            display: flex; align-items: center; gap: 18px;
            transition: transform 0.18s, box-shadow 0.18s;
            position: relative; overflow: hidden;
            min-width: 0; /* cegah konten flex memaksa lebar card melebar */
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,0.10); }
        .stat-icon {
            width: 52px; height: 52px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px; flex-shrink: 0;
        }
        .stat-icon.green { background: #e6f9ee; color: #09973B; }
        .stat-icon.red   { background: #fff0f0; color: #e53e3e; }
        .stat-icon.blue  { background: #ebf4ff; color: #3182ce; }
        .stat-label { font-size: 12.5px; color: #888; margin-bottom: 4px; font-weight: 500; }
        .stat-value { font-size: 28px; font-weight: 800; color: #1a1a2e; line-height: 1; }
        .stat-badge {
            position: absolute; top: 16px; right: 16px;
            font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 20px;
        }
        .stat-badge.up { background: #e6f9ee; color: #09973B; }
        .stat-badge.down { background: #fff0f0; color: #e53e3e; }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
            flex-wrap: wrap;
            gap: 12px;
        }
    </style>
</head>
<body>

<div style="display:flex; min-height:100vh;">

    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <div class="sidebar" id="appSidebar">
        <div class="sidebar-header">
            <div class="hamburger" onclick="toggleSidebar()"><span></span><span></span><span></span></div>
            <span class="logo-text">JAGONET</span>
        </div>

        <div class="section-label">Main Board</div>

        <a href="{{ Auth::user()->dashboard_url }}" class="menu-item active">
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

    <!-- MAIN CONTENT -->
    <div class="main-content" style="flex:1;">

        <div class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button type="button" class="btn-sidebar-toggle d-lg-none" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i>
                </button>
                <div>
                    <div class="page-title">Dashboard</div>
                    <div class="page-sub">Selamat datang kembali, {{ Auth::user()->name }}</div>
                </div>
            </div>
            <div style="font-size:13px; color:#aaa;">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</div>
        </div>

        <!-- STAT CARDS -->
<!-- STAT CARDS -->
        @php
            $totalUpgrade   = $agenda->whereIn('jenis', ['upgrade_layanan', 'downgrade_layanan'])->count();
            $pendingUpgrade = $agenda->whereIn('jenis', ['upgrade_layanan', 'downgrade_layanan'])->where('status', 'pending')->count();
            $ditolakUpgrade = $agenda->whereIn('jenis', ['upgrade_layanan', 'downgrade_layanan'])->where('status', 'ditolak')->count();

            $totalIsolir   = $agenda->where('jenis', 'isolir')->count();
            $pendingIsolir = $agenda->where('jenis', 'isolir')->where('status', 'pending')->count();
            $ditolakIsolir = $agenda->where('jenis', 'isolir')->where('status', 'ditolak')->count();

            $totalAktivasi   = $agenda->where('jenis', 'aktivasi')->count();
            $pendingAktivasi = $agenda->where('jenis', 'aktivasi')->where('status', 'pending')->count();
            $ditolakAktivasi = $agenda->where('jenis', 'aktivasi')->where('status', 'ditolak')->count();
        @endphp

        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-icon blue"><i class="bi bi-arrow-up-circle-fill"></i></div>
                <div>
                    <div class="stat-label">Upgrade / Downgrade</div>
                    <div class="stat-value">{{ $totalUpgrade }}</div>
                </div>
                <span class="stat-badge" style="background:#fff7e6; color:#b8860b;">
                    {{ $pendingUpgrade }} pending · {{ $ditolakUpgrade }} ditolak
                </span>
            </div>
            <div class="stat-card">
                <div class="stat-icon red"><i class="bi bi-person-x-fill"></i></div>
                <div>
                    <div class="stat-label">Isolir</div>
                    <div class="stat-value">{{ $totalIsolir }}</div>
                </div>
                <span class="stat-badge" style="background:#fff7e6; color:#b8860b;">
                    {{ $pendingIsolir }} pending · {{ $ditolakIsolir }} ditolak
                </span>
            </div>
            <div class="stat-card">
                <div class="stat-icon green"><i class="bi bi-person-check-fill"></i></div>
                <div>
                    <div class="stat-label">Aktivasi</div>
                    <div class="stat-value">{{ $totalAktivasi }}</div>
                </div>
                <span class="stat-badge" style="background:#fff7e6; color:#b8860b;">
                    {{ $pendingAktivasi }} pending · {{ $ditolakAktivasi }} ditolak
                </span>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleSidebar() {
        document.getElementById('appSidebar').classList.toggle('show');
        document.getElementById('sidebarOverlay').classList.toggle('show');
    }
</script>
</body>
</html>