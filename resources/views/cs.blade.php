<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard CS - Jagonet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('inputform.css') }}">

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
                <div class="page-sub">Selamat datang kembali, {{ Auth::user()->name }} </div>
            </div>
            </div>
            <div style="font-size:13px; color:#aaa;">Selasa, 22 April 2025</div>
        </div>

        <!-- STAT CARDS -->
        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-icon green"><i class="bi bi-person-check-fill"></i></div>
                <div>
                    <div class="stat-label">Pelanggan Aktif</div>
                    <div class="stat-value">248</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon red"><i class="bi bi-person-x-fill"></i></div>
                <div>
                    <div class="stat-label">Pelanggan Nonaktif</div>
                    <div class="stat-value">34</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon blue"><i class="bi bi-people-fill"></i></div>
                <div>
                    <div class="stat-label">Total Pelanggan</div>
                    <div class="stat-value">282</div>
                </div>
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