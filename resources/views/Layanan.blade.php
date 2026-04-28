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
body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: #f4f6f9;
}

.table td, .table th {
    vertical-align: middle;
}

/* ---- STATUS BADGE ---- */
.status-pill {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 7px 14px;
    border-radius: 50px;
    font-size: 12px;
    font-weight: 700;
    border: 1px solid transparent;
}

.status-pill i {
    font-size: 10px;
}

.status-active {
    background: linear-gradient(135deg, #e8fff1, #d8ffe8);
    color: #0f9d58;
    border-color: #b7f3cd;
}

.status-nonactive {
    background: linear-gradient(135deg, #fff1f1, #ffe1e1);
    color: #dc3545;
    border-color: #ffc4c4;
}

/* ---- ACTION BUTTONS ---- */
.action-group {
    display: flex;
    align-items: center;
    gap: 8px;
    justify-content: center;
}

.action-modern {
    width: 38px;
    height: 38px;
    border: none;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: .25s;
    box-shadow: 0 6px 14px rgba(0, 0, 0, .06);
    cursor: pointer;
    text-decoration: none;
}

.action-modern i {
    font-size: 15px;
}

.action-modern:hover {
    transform: translateY(-3px);
}

.btn-detail {
    background: linear-gradient(135deg, #eef4ff, #dfeaff);
    color: #0d6efd;
}

.btn-detail:hover {
    background: #0d6efd;
    color: #fff;
}

.btn-reminder {
    background: linear-gradient(135deg, #fff7e6, #ffecc2);
    color: #ff9800;
}

.btn-reminder:hover {
    background: #ff9800;
    color: #fff;
}

.btn-tambah {
    width: 38px;
    height: 38px;
    border: none;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    color: #059669;
    font-size: 18px;
    font-weight: 800;
    cursor: pointer;
    transition: .25s;
    box-shadow: 0 6px 14px rgba(0,0,0,.06);
    padding: 0;
}

.btn-tambah:hover {
    background: #22c55e;
    color: #fff;
    transform: translateY(-3px);
}

/* ---- MODAL NOTIFIKASI ---- */
.modal-notif .modal-content {
    border-radius: 18px;
    border: none;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0,0,0,0.15);
}

.notif-header {
    background: #fff;
    color: #111;
    padding: 22px 28px 16px;
    font-size: 22px;
    font-weight: 800;
    text-align: center;
    letter-spacing: 1px;
    border-bottom: 1px solid #eee;
}

.notif-body {
    padding: 22px 28px 28px;
    background: #fff;
}

.notif-box {
    border: 1.5px solid #d4d4d4;
    border-radius: 10px;
    padding: 14px 16px;
    margin-bottom: 14px;
    background: #fff;
}

.notif-label {
    font-size: 11px;
    font-weight: 800;
    color: #222;
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.notif-text {
    font-size: 13.5px;
    color: #444;
    line-height: 1.75;
}

.notif-preview-box {
    border: 1.5px solid #d4d4d4;
    border-radius: 10px;
    padding: 16px 18px;
    margin-bottom: 22px;
    background: #fff;
    font-size: 13.5px;
    color: #333;
    line-height: 1.8;
}

.notif-preview-box b {
    font-size: 13px;
    font-weight: 800;
    color: #111;
}

.btn-kirim {
    background: #22c55e;
    color: #fff;
    border: none;
    border-radius: 10px;
    padding: 13px 0;
    font-weight: 800;
    font-size: 14px;
    letter-spacing: 1px;
    cursor: pointer;
    width: 100%;
    display: block;
    transition: background 0.2s;
}

.btn-kirim:hover {
    background: #16a34a;
    color: #fff;
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
    <!-- END SIDEBAR -->

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

            <!-- CARD HEADER -->
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

                    <thead class="table-light text-center fw-bold">
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

                    <tbody class="text-center">
                        @foreach($pelanggan as $p)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>{{ $p->created_at->format('d/m/Y') }}</td>

                            <td class="text-start">{{ $p->nama }}</td>

                            <td class="text-start">Rp {{ number_format($p->layanan->harga ?? 0, 0, ',', '.') }}</td>

                            <td>{{ $p->layanan->nama_paket ?? '-' }}</td>

                            <td>
                                @if(strtolower($p->status) == 'aktif')
                                    <span class="status-pill status-active">
                                        <i class="bi bi-check-circle-fill"></i> Aktif
                                    </span>
                                @else
                                    <span class="status-pill status-nonactive">
                                        <i class="bi bi-x-circle-fill"></i> Nonaktif
                                    </span>
                                @endif
                            </td>

                            <td>
                                <div class="action-group">

                                    {{-- Tombol Detail --}}
                                    <a href="{{ route('layanan.detail', $p->id) }}"
                                       class="action-modern btn-detail"
                                       title="Detail">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>

                                    {{-- Tombol Reminder --}}
                                    <button class="action-modern btn-reminder"
                                        title="Reminder"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalNotif{{ $p->id }}">
                                        <i class="bi bi-bell-fill"></i>
                                    </button>

                                    {{-- Tombol Generate Tagihan --}}
                                    <form method="POST"
                                          action="{{ route('pelanggan.generateTagihan') }}"
                                          style="margin:0;">
                                        @csrf
                                        <input type="hidden" name="pelanggan_id" value="{{ $p->id }}">
                                        <button type="submit"
                                                class="btn-tambah"
                                                title="Generate Tagihan">
                                            <i class="bi bi-plus-lg"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
            <!-- END TABLE -->

        </div>
        <!-- END CARD -->

    </div>
    <!-- END MAIN CONTENT -->

</div>
<!-- END WRAPPER -->


{{-- =============================================
     MODAL NOTIFIKASI
     Diletakkan di luar wrapper utama agar tidak
     terganggu overflow:hidden pada parent
     ============================================= --}}
@foreach($pelanggan as $p)
<div class="modal fade modal-notif" id="modalNotif{{ $p->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">

            {{-- HEADER --}}
            <div class="notif-header">
                NOTIFIKASI
            </div>

            <div class="notif-body">

                {{-- BOX 1: TAGIHAN INTERNET BULANAN --}}
                <div class="notif-box">
                    <div class="notif-label">Tagihan Internet Bulanan</div>
                    <div class="notif-text">
                        Nama Pelanggan : {{ $p->nama }}<br>
                        Periode Tagihan : {{ date('F Y') }}
                    </div>
                </div>

                {{-- BOX 2: ISOLIR LAYANAN --}}
                <div class="notif-box">
                    <div class="notif-label">Isolir Layanan</div>
                    <div class="notif-text">
                        Yth. {{ $p->nama }},<br>
                        Kami informasikan bahwa layanan internet Anda saat ini
                        <b>diisolir</b> dikarenakan tagihan {{ date('F') }} telah
                        melebihi batas jatuh tempo pembayaran.
                    </div>
                </div>

                {{-- PREVIEW PESAN --}}
                <div class="notif-label" style="margin-bottom:8px;">Preview Pesan</div>
                <div class="notif-preview-box">
                    <b>TAGIHAN INTERNET BULANAN</b><br><br>
                    Nama Pelanggan : {{ $p->nama }}<br>
                    Periode Tagihan : {{ date('F Y') }}<br>
                    Total Tagihan : Rp {{ number_format($p->layanan->harga ?? 0, 0, ',', '.') }}<br><br>
                    Silakan lakukan pembayaran sebelum tanggal [Jatuh Tempo] ke rekening berikut:<br>
                    BCA : [No Rekening] a/n [Nama]<br>
                    BRI : [No Rekening] a/n [Nama]<br><br>
                    Kirim bukti pembayaran ke: [Nomor Admin]<br>
                    Terima Kasih 😊🙏
                </div>

                {{-- TOMBOL KIRIM --}}
                <button class="btn-kirim" data-bs-dismiss="modal">
                    KIRIM
                </button>

            </div>
            {{-- END notif-body --}}

        </div>
    </div>
</div>
@endforeach
{{-- END MODAL --}}
@foreach($tagihan as $t)
<div class="modal fade" id="editTagihan{{ $t->id }}" tabindex="-1">
<div class="modal-dialog">
<div class="modal-content">

<form action="{{ url('/tagihan/'.$t->id) }}" method="POST">
@csrf
@method('PUT')

<div class="modal-header bg-warning">
<h5 class="modal-title">Edit Tagihan</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

<div class="mb-3">
<label>Periode</label>
<input type="text" name="periode"
class="form-control"
value="{{ $t->periode }}">
</div>

<div class="mb-3">
<label>Jumlah</label>
<input type="number" name="jumlah"
class="form-control"
value="{{ $t->jumlah }}">
</div>

<div class="mb-3">
<label>Status</label>
<select name="status" class="form-control">
<option value="Belum Bayar"
{{ $t->status=='Belum Bayar' ? 'selected' : '' }}>
Belum Bayar
</option>

<option value="Lunas"
{{ $t->status=='Lunas' ? 'selected' : '' }}>
Lunas
</option>
</select>
</div>

</div>

<div class="modal-footer">
<button type="submit" class="btn btn-success">
Simpan
</button>
</div>

</form>

</div>
</div>
</div>
@endforeach
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>