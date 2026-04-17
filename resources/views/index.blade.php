<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pelanggan - Jagonet</title>

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
            <div class="hamburger"><span></span><span></span><span></span></div>
            <span class="logo-text">JAGONET</span>
        </div>

        <div class="section-label">Main Board</div>

        <a href="{{ url('admin') }}" class="menu-item">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="{{ url('/layanan') }}" class="menu-item">
            <i class="bi bi-wifi"></i> Data Layanan
        </a>
        <a href="{{ url('/instalasi') }}" class="menu-item">
            <i class="bi bi-router"></i> Instalasi Baru
        </a>
        <a href="{{ url('/pemasukan') }}" class="menu-item">
            <i class="bi bi-wallet2"></i> Pemasukan
        </a>

        <div class="section-label">Pelanggan</div>

        <a href="{{ url('/pelanggan') }}" class="menu-item active">
            <i class="bi bi-people"></i> Data Pelanggan
        </a>

        <!-- Profile -->
        <div class="profile-section">
            <div class="admin-card">
                <div class="admin-avatar">
                    <i class="bi bi-person-fill" style="color:white; font-size:17px;"></i>
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

        <!-- TOPBAR -->
        <div class="topbar">
            <div>
                <div class="page-title">Data Pelanggan</div>
                <div class="page-sub">Daftar seluruh pelanggan terdaftar</div>
            </div>
            <div class="breadcrumb-area">
                <i class="bi bi-house-door"></i>
                <span class="sep">/</span>
                <span>Pelanggan</span>
                <span class="sep">/</span>
                <span class="current">Data</span>
            </div>
        </div>

        <!-- CARD TABLE -->
        <div class="form-card">

            <!-- HEADER -->
            <div class="form-card-header">
                <div class="icon-wrap">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <div class="form-card-title">Data Pelanggan</div>
                    <div class="form-card-sub">List pelanggan yang sudah terdaftar</div>
                </div>
            </div>

            <!-- ACTION BUTTON -->
            <div style="padding:20px;">
                <a href="/admin" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Home
                </a>
                <a href="/instalasi" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg"></i> Tambah Pelanggan
                </a>
            </div>

            <!-- TABLE -->
            <div class="table-responsive px-3 pb-4">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama</th>
                            <th>Site</th>
                            <th>Layanan</th>
                            <th>No. Telepon</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pelanggan as $p)
                        <tr>
                            <td>{{ $p->nama }}</td>
                            <td>{{ $p->site->nama_site ?? '-' }}</td>
                            <td>{{ $p->layanan->nama_paket ?? '-' }}</td>
                            <td>{{ $p->no_hp ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
        <!-- /card -->

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>