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
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show m-3">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show m-3">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

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

    <div class="form-card">

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
                    <label class="form-label invisible">Metode</label>
                    <select class="form-select">
                        <option value="">Semua Metode</option>
                        @foreach ($metode as $m)
                            <option value="{{ $m->id }}">{{ $m->nama_metode }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label invisible">Cari</label>
                    <input type="text" class="form-control" placeholder="Cari pelanggan...">
                </div>

            </div>

        </div>

        <!-- BUTTON -->
        <div style="padding:20px 20px 15px;">

            <button type="button" class="btn btn-success btn-sm"
                data-bs-toggle="modal"
                data-bs-target="#modalPembayaran">
                <i class="bi bi-plus-lg"></i> Tambah Pembayaran
            </button>

            <a href="#" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-download"></i> Export
            </a>

        </div>

        <!-- TABLE -->
        <div class="table-responsive px-3 pb-4">

            <table class="table table-bordered table-hover align-middle">

                <thead class="table-light text-center fw-bold">
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
                    @foreach ($pembayaran as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->tanggal_bayar }}</td>
                        <td>{{ $item->pelanggan->nama ?? '-' }}</td>
                        <td>{{ $item->layanan->nama_paket ?? '-' }}</td>
                        <td>Rp {{ number_format($item->jumlah_bayar,0,',','.') }}</td>
                        <td>{{ $item->metode->nama_metode ?? '-' }}</td>
                        <td>
                            @if ($item->status == 'lunas')
                                <span class="badge bg-success">Lunas</span>
                            @else
                                <span class="badge bg-danger">Belum</span>
                            @endif
                        </td>
                        <td>
                            <button class="action-btn btn-primary">
                                <i class="bi bi-eye"></i>
                            </button>

                            <button class="action-btn btn-warning">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="{{ route('pemasukan.destroy', $item->id) }}"
                                method="POST"
                                style="display:inline;"
                                onsubmit="return confirm('Yakin mau hapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn btn-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>

        </div>

    </div>

</div>
</div>

<!-- MODAL -->
<div class="modal fade" id="modalPembayaran" tabindex="-1">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="bi bi-cash-stack"></i> Tambah Pembayaran
                </h5>
                <button type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal">
                </button>
            </div>

            <form action="{{ route('pemasukan.store') }}" method="POST">
                @csrf

                <div class="modal-body">

                    <div class="row g-3">

                        <!-- Nama Pelanggan -->
                        <div class="col-md-6">
                            <label class="form-label">Nama Pelanggan</label>
                            <select name="pelanggan_id" id="pelanggan" class="form-select" required>
                                <option value="">-- Pilih Pelanggan --</option>
                                @foreach($pelanggan as $p)
                                    <option
                                        value="{{ $p->id }}"
                                        data-layanan="{{ $p->layanan_id }}"
                                        data-paket="{{ $p->layanan->nama_paket ?? '' }}"
                                        data-harga="{{ $p->layanan->harga ?? 0 }}"
                                        data-tagihan="{{ $p->tagihan->first()->id ?? '' }}">
                                        {{ $p->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Tanggal -->
                        <div class="col-md-6">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="tanggal_bayar" class="form-control" required>
                        </div>

                        <!-- Paket (otomatis dari pelanggan) -->
                        <div class="col-md-6">
                            <label class="form-label">Paket</label>
                            <input type="hidden" name="layanan_id" id="layanan_id">
                            <input type="text" id="paket_view" class="form-control" readonly
                                   placeholder="Otomatis terisi saat pilih pelanggan">
                        </div>

                        <!-- Tagihan (otomatis dari pelanggan) -->
                        <div class="col-md-6">
                            <label class="form-label">Tagihan</label>
                            <input type="hidden" name="tagihan_id" id="tagihan_id">
                            <input type="text" id="tagihan_view" class="form-control" readonly
                                   placeholder="Otomatis terisi saat pilih pelanggan">
                        </div>

                        <!-- Jumlah Bayar (dari harga layanan) -->
                        <div class="col-md-6">
                            <label class="form-label">Jumlah Bayar</label>
                            <input type="number" name="jumlah_bayar" id="jumlah_bayar"
                                   class="form-control" readonly>
                        </div>

                        <!-- Metode -->
                        <div class="col-md-6">
                            <label class="form-label">Metode</label>
                            <select name="metode_id" class="form-select" required>
                                <option value="">-- Pilih Metode --</option>
                                @foreach($metode as $m)
                                    <option value="{{ $m->id }}">{{ $m->nama_metode }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Status -->
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="lunas">Lunas</option>
                                <option value="belum">Belum</option>
                            </select>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-success">
                        Simpan
                    </button>
                </div>

            </form>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('pelanggan').addEventListener('change', function () {
        let opt = this.options[this.selectedIndex];

        let layananId  = opt.getAttribute('data-layanan') || '';
        let paket      = opt.getAttribute('data-paket')   || '';
        let harga      = opt.getAttribute('data-harga')   || 0;
        let tagihanId  = opt.getAttribute('data-tagihan') || '';

        document.getElementById('layanan_id').value  = layananId;
        document.getElementById('paket_view').value  = paket;
        document.getElementById('tagihan_id').value  = tagihanId;
        document.getElementById('tagihan_view').value = tagihanId ? 'Tagihan #' + tagihanId : '';
        document.getElementById('jumlah_bayar').value = harga;
    });
});
</script>

</body>
</html>