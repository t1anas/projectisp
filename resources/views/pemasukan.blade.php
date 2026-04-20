```php
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pemasukan - Jagonet</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('inputform.css') }}">

<style>
body{
    font-family:'Plus Jakarta Sans',sans-serif;
    background:#f4f6f9;
}

.filter-box{
    padding:20px;
    border-bottom:1px solid #eee;
}

.table td,.table th{
    vertical-align:middle;
}

.action-btn{
    width:32px;
    height:32px;
    border:none;
    border-radius:8px;
}
</style>
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

    <a href="{{ url('/pemasukan') }}" class="menu-item active">
        <i class="bi bi-wallet2"></i> Pemasukan
    </a>

    <div class="section-label">Pelanggan</div>

    <a href="{{ url('/pelanggan') }}" class="menu-item">
        <i class="bi bi-people"></i> Data Pelanggan
    </a>

    <!-- PROFILE -->
    <div class="profile-section">
        <div class="admin-card">
            <div class="admin-avatar">
                <i class="bi bi-person-fill" style="color:white;"></i>
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
            <div class="page-title">Pemasukan</div>
            <div class="page-sub">Kelola transaksi pembayaran pelanggan</div>
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

        <!-- HEADER -->
        <div class="form-card-header">
            <div class="icon-wrap">
                <i class="bi bi-cash-stack"></i>
            </div>

            <div>
                <div class="form-card-title">Data Pemasukan</div>
                <div class="form-card-sub">Riwayat pembayaran pelanggan internet</div>
            </div>
        </div>

        <!-- FILTER -->
        <div class="filter-box">
            <div class="row g-3">

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Tanggal Awal</label>
                    <input type="date" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Tanggal Akhir</label>
                    <input type="date" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Metode</label>
                    <select class="form-select">
                        <option>Semua Metode</option>
                        <option>Cash</option>
                        <option>Transfer</option>
                        <option>QRIS</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Cari</label>
                    <input type="text" class="form-control" placeholder="Cari pelanggan...">
                </div>

            </div>
        </div>

        <!-- BUTTON -->
        <div style="padding:20px 20px 15px;">
            <a href="#" class="btn btn-success btn-sm">
                <i class="bi bi-plus-lg"></i> Tambah Pembayaran
            </a>

            <a href="#" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-download"></i> Export
            </a>
        </div>

        <!-- TABLE -->
        <div class="table-responsive px-3 pb-4">
            <table class="table table-bordered table-hover align-middle">

                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Nama</th>
                        <th>Paket</th>
                        <th>Tagihan</th>
                        <th>Metode</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    <tr>
                        <td>1</td>
                        <td>20/04/2026</td>
                        <td>Ines Farah</td>
                        <td>20 Mbps</td>
                        <td>Rp 150.000</td>
                        <td>Cash</td>
                        <td><span class="badge bg-success">Lunas</span></td>
                        <td>
                            <button class="action-btn btn-primary"><i class="bi bi-eye"></i></button>
                            <button class="action-btn btn-warning"><i class="bi bi-pencil"></i></button>
                        </td>
                    </tr>

                    <tr>
                        <td>2</td>
                        <td>20/04/2026</td>
                        <td>Budi Santoso</td>
                        <td>50 Mbps</td>
                        <td>Rp 250.000</td>
                        <td>Transfer</td>
                        <td><span class="badge bg-success">Lunas</span></td>
                        <td>
                            <button class="action-btn btn-primary"><i class="bi bi-eye"></i></button>
                            <button class="action-btn btn-warning"><i class="bi bi-pencil"></i></button>
                        </td>
                    </tr>

                    <tr>
                        <td>3</td>
                        <td>20/04/2026</td>
                        <td>Siti Aminah</td>
                        <td>100 Mbps</td>
                        <td>Rp 300.000</td>
                        <td>QRIS</td>
                        <td><span class="badge bg-danger">Belum</span></td>
                        <td>
                            <button class="action-btn btn-primary"><i class="bi bi-eye"></i></button>
                            <button class="action-btn btn-warning"><i class="bi bi-pencil"></i></button>
                        </td>
                    </tr>

                </tbody>

            </table>
        </div>

    </div>

</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
```
