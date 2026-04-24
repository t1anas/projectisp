<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Data Layanan - Jagonet</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<link rel="stylesheet" href="{{ asset('inputform.css') }}">

<style>
body{
    font-family:'Plus Jakarta Sans',sans-serif;
    background:#f4f6f9;
}

.table td,.table th{
    vertical-align:middle;
}

.badge-status{
    padding:6px 12px;
    border-radius:30px;
    font-size:12px;
    font-weight:700;
}

.aktif{
    background:#e8f8ee;
    color:#09973B;
}

.nonaktif{
    background:#ffeaea;
    color:#dc3545;
}

.action-btn{
    width:34px;
    height:34px;
    border:none;
    border-radius:10px;
}

.btn-view{
    background:#eef4ff;
    color:#0d6efd;
}

.btn-bell{
    background:#fff7e6;
    color:#ff9800;
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

    <a href="{{ url('admin') }}" class="menu-item">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    <a href="{{ url('/layanan') }}" class="menu-item active">
        <i class="bi bi-wifi"></i> Data Layanan
    </a>

    <a href="{{ url('/instalasi') }}" class="menu-item">
        <i class="bi bi-router"></i> Instalasi Baru
    </a>

    @if(Auth::user()->role == 'admin')
    <a href="{{ url('/pemasukan') }}" class="menu-item">
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

<!-- MAIN CONTENT -->
<div class="main-content" style="flex:1;">

    <!-- TOPBAR -->
    <div class="topbar">

        <div>
            <div class="page-title">Data Layanan</div>
            <div class="page-sub">Kelola data layanan pelanggan internet</div>
        </div>

        <div class="breadcrumb-area">
            <i class="bi bi-house-door"></i>
            <span class="sep">/</span>
            <span>Layanan</span>
            <span class="sep">/</span>
            <span class="current">Data</span>
        </div>

    </div>

    <!-- CARD -->
    <div class="form-card">

        <!-- HEADER -->
        <div class="form-card-header">

            <div class="icon-wrap">
                <i class="bi bi-wifi"></i>
            </div>

            <div>
                <div class="form-card-title">Data Layanan</div>
                <div class="form-card-sub">Daftar seluruh layanan pelanggan</div>
            </div>

        </div>

        <!-- FILTER -->
        <div style="padding:20px; border-bottom:1px solid #eee;">

            <div class="row g-3">

                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Cari pelanggan...">
                </div>

                <div class="col-md-3">
                    <select class="form-select">
                        <option>Semua Status</option>
                        <option>AKTIF</option>
                        <option>NONAKTIF</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <input type="date" class="form-control">
                </div>

                <div class="col-md-2">
                    <input type="date" class="form-control">
                </div>

                <div class="col-md-1">
                    <button class="btn btn-success w-100">
                        <i class="bi bi-search"></i>
                    </button>
                </div>

            </div>

        </div>

        <!-- TABLE -->
        <div class="table-responsive px-3 pb-4">

            <table class="table table-bordered table-hover align-middle">

                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Aktivasi</th>
                        <th>Nama</th>
                        <th>Tagihan</th>
                        <th>Paket</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($pelanggan as $p)
                    <tr>

                        <td>{{ $loop->iteration }}</td>

                        <td>{{ $p->created_at->format('d/m/Y') }}</td>

                        <td>{{ $p->nama }}</td>

                        <td>Rp {{ number_format($p->layanan->harga ?? 0,0,',','.') }}</td>

                        <td>{{ $p->layanan->nama_paket ?? '-' }}</td>

                        <td>
                            <span class="badge-status {{ $p->layanan->status == 'AKTIF' ? 'aktif':'nonaktif' }}">
                                {{ $p->layanan->status }}
                            </span>
                        </td>

                        <td>
                            <button class="action-btn btn-view">
                                <i class="bi bi-eye"></i>
                            </button>

                            <button class="action-btn btn-bell">
                                <i class="bi bi-bell"></i>
                            </button>
                        </td>

                    </tr>
                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>