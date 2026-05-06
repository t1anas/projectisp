<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Detail Layanan - Jagonet</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<link rel="stylesheet" href="{{ asset('inputform.css') }}">

<style>
/* ===== BASE ===== */
.modal-bayar .modal-content {
    border-radius: 18px;
    border: none;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0,0,0,0.15);
}

.bayar-header {
    background: #fff;
    padding: 20px;
    text-align: center;
    font-weight: 800;
    font-size: 18px;
    border-bottom: 1px solid #eee;
}

.bayar-body {
    padding: 22px;
    background: #fff;
}

.bayar-box {
    border: 1.5px solid #e5e7eb;
    border-radius: 12px;
    padding: 14px;
    margin-bottom: 14px;
}

.bayar-label {
    font-size: 11px;
    font-weight: 800;
    color: #555;
    margin-bottom: 6px;
    text-transform: uppercase;
}

.bayar-input {
    border-radius: 10px;
    font-size: 13px;
}

.btn-konfirmasi {
    width: 100%;
    background: #22c55e;
    border: none;
    padding: 12px;
    border-radius: 10px;
    font-weight: 800;
    color: #fff;
    transition: .2s;
}

.btn-konfirmasi:hover {
    background: #16a34a;
}
body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: #f4f6f9;
}

.profil-card {
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 14px rgba(0,0,0,0.07);
}

.profil-card-top {
    background: linear-gradient(160deg, #1e9e42, #22c55e);
    padding: 20px 20px 48px;
    position: relative;
}

.profil-avatar-row {
    display: flex;
    align-items: center;
    gap: 12px;
}

.profil-avatar {
    width: 46px;
    height: 46px;
    border-radius: 50%;
    background: rgba(255,255,255,0.25);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 21px;
    color: #fff;
    flex-shrink: 0;
}

.profil-nama {
    font-size: 15px;
    font-weight: 800;
    color: #fff;
    line-height: 1.3;
}

.profil-kode {
    font-size: 10.5px;
    color: rgba(255,255,255,0.72);
    letter-spacing: 0.3px;
    margin-top: 2px;
}

.profil-badges {
    position: absolute;
    bottom: 14px;
    left: 20px;
    display: flex;
    gap: 7px;
    flex-wrap: wrap;
}

.badge-aktif {
    background: #16a34a;
    color: #fff;
    font-size: 10px;
    font-weight: 800;
    padding: 3px 11px;
    border-radius: 20px;
    letter-spacing: 0.3px;
}

.badge-nonaktif {
    background: #ef4444;
    color: #fff;
    font-size: 10px;
    font-weight: 800;
    padding: 3px 11px;
    border-radius: 20px;
}

.badge-paket {
    background: rgba(0,0,0,0.18);
    color: #fff;
    font-size: 10px;
    font-weight: 700;
    padding: 3px 11px;
    border-radius: 20px;
}

.profil-info {
    padding: 16px 20px 8px;
}

.profil-info-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 12px;
    padding: 9px 0;
    border-bottom: 1px solid #f1f3f5;
    font-size: 12.5px;
}

.profil-info-row:last-child {
    border-bottom: none;
}

.profil-info-row .key {
    color: #a0aab4;
    font-weight: 500;
    flex-shrink: 0;
}

.profil-info-row .val {
    color: #111;
    font-weight: 700;
    text-align: right;
}

.profil-actions {
    padding: 6px 20px 20px;
    display: flex;
    flex-direction: column;
    gap: 9px;
}

.btn-edit-tagihan {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    background: #fff;
    border: 1.5px solid #d1d5db;
    color: #374151;
    font-size: 12.5px;
    font-weight: 700;
    padding: 10px;
    border-radius: 10px;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.18s;
    width: 100%;
}

.btn-edit-tagihan:hover {
    background: #f9fafb;
    border-color: #9ca3af;
    color: #111;
}

.btn-isolir {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    background: #fffbeb;
    border: 1.5px solid #fde68a;
    color: #b45309;
    font-size: 12.5px;
    font-weight: 700;
    padding: 10px;
    border-radius: 10px;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.18s;
    width: 100%;
}

.btn-isolir:hover {
    background: #fef3c7;
    border-color: #f59e0b;
    color: #92400e;
}

.tagihan-section-title {
    font-size: 17px;
    font-weight: 800;
    color: #111;
    margin-bottom: 14px;
}

.tagihan-item {
    background: #fff;
    border-radius: 12px;
    padding: 14px 18px;
    margin-bottom: 9px;
    box-shadow: 0 1px 6px rgba(0,0,0,0.05);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    border: 1.5px solid transparent;
    transition: box-shadow 0.15s;
}

.tagihan-item.berjalan {
    border-color: #bbf7d0;
    background: #f0fdf4;
}

.tagihan-item-left {
    flex: 1;
    min-width: 0;
}

.tagihan-bulan {
    font-size: 14px;
    font-weight: 800;
    color: #111;
    margin-bottom: 3px;
}

.tagihan-item.berjalan .tagihan-bulan {
    color: #15803d;
}

.tagihan-status-text {
    font-size: 11.5px;
    color: #9ca3af;
}

.tagihan-item-right {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
}

.btn-cetak {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #f3f4f6;
    border: 1px solid #e5e7eb;
    color: #555;
    font-size: 11.5px;
    font-weight: 700;
    padding: 7px 13px;
    border-radius: 8px;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.15s;
    white-space: nowrap;
}

.btn-cetak:hover {
    background: #e5e7eb;
    color: #111;
}

.btn-bayar {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #22c55e;
    border: none;
    color: #fff;
    font-size: 11.5px;
    font-weight: 800;
    padding: 7px 18px;
    border-radius: 8px;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.15s;
    white-space: nowrap;
    letter-spacing: 0.3px;
}

.btn-bayar:hover {
    background: #16a34a;
    color: #fff;
    transform: translateY(-1px);
}

.btn-hapus {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #ef4444;
    border: none;
    color: #fff;
    font-size: 11.5px;
    font-weight: 800;
    padding: 7px 18px;
    border-radius: 8px;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.15s;
    white-space: nowrap;
    letter-spacing: 0.3px;
}

.btn-hapus:hover {
    background: #dc2626;
    color: #fff;
    transform: translateY(-1px);
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #d1d5db;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 1px 6px rgba(0,0,0,0.05);
}

.empty-state i {
    font-size: 42px;
    margin-bottom: 12px;
    display: block;
}

.empty-state p {
    font-size: 13px;
    margin: 0;
    color: #9ca3af;
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
            <div>
                <div class="page-title">Detail Layanan</div>
                <div class="page-sub">Kelola tagihan dan layanan pelanggan</div>
            </div>
            <div class="breadcrumb-area">
                <i class="bi bi-house-door"></i>
                <span class="sep">/</span>
                <a href="{{ url('/layanan') }}" style="color:inherit; text-decoration:none;">Layanan</a>
                <span class="sep">/</span>
                <span class="current">Detail</span>
            </div>
        </div>

        <div style="padding: 28px 24px;">

            <div class="row g-4 align-items-start">

                <!-- KIRI: PROFIL CARD -->
                <div class="col-md-4 col-lg-3">
                    <div class="profil-card">

                        <div class="profil-card-top">
                            <div class="profil-avatar-row">
                                <div class="profil-avatar">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                                <div>
                                    <div class="profil-nama">{{ $pelanggan->nama }}</div>
                                    <div class="profil-kode">
                                        {{ $pelanggan->kode ?? 'CSR'.str_pad($pelanggan->id, 12, '0', STR_PAD_LEFT) }}
                                    </div>
                                </div>
                            </div>

                            <div class="profil-badges">
                                @if(strtolower($pelanggan->status ?? '') == 'aktif')
                                    <span class="badge-aktif">Aktif</span>
                                @else
                                    <span class="badge-nonaktif">Nonaktif</span>
                                @endif

                                <span class="badge-paket">
                                    {{ $pelanggan->layanan->kecepatan ?? '' }}/{{ $pelanggan->layanan->nama_paket ?? '-' }}
                                </span>
                            </div>
                        </div>

                        <div class="profil-info">
                            <div class="profil-info-row">
                                <span class="key">Tagihan</span>
                                <span class="val">Rp {{ number_format($pelanggan->layanan->harga ?? 0, 0, ',', '.') }}</span>
                            </div>
                            <div class="profil-info-row">
                                <span class="key">No. HP</span>
                                <span class="val">{{ $pelanggan->no_hp ?? '-' }}</span>
                            </div>
                            <div class="profil-info-row">
                                <span class="key">Alamat</span>
                                <span class="val">{{ $pelanggan->alamat ?? '-' }}</span>
                            </div>
                            <div class="profil-info-row">
                                <span class="key">Aktivasi</span>
                                <span class="val">{{ \Carbon\Carbon::parse($pelanggan->layanan->created_at)->format('d M Y') }}</span>
                            </div>
                        </div>

                        <div class="profil-actions">
                            <button type="button"
                                    class="btn-edit-tagihan"
                                    data-bs-toggle="modal"
                                    data-bs-target="#tambahTagihan">
                                <i class="bi bi-plus-lg"></i> Buat Tagihan
                            </button>
                            <a href="{{ url('/layanan/'.$pelanggan->id.'/isolir') }}" class="btn-isolir">
                                <i class="bi bi-bell-fill"></i> Isolir Pelanggan
                            </a>
                        </div>

                    </div>
                </div>

                <!-- KANAN: DAFTAR TAGIHAN -->
                <div class="col-md-8 col-lg-9">

                    <div class="tagihan-section-title">Daftar Tagihan</div>

                    @if(isset($tagihan) && $tagihan->count() > 0)

                        @foreach($tagihan as $t)

                            @if(strtolower($t->status) != 'lunas')
                            {{-- BELUM LUNAS --}}
                            <div class="tagihan-item berjalan">
                                <div class="tagihan-item-left">
                                    <div class="tagihan-bulan">
                                        {{ strtoupper(\Carbon\Carbon::parse($t->tanggal)->format('M Y')) }}
                                        — {{ \Illuminate\Support\Str::title($t->jenis_tagihan ?? 'tagihan internet bulanan') }}
                                    </div>
                                    <div class="tagihan-status-text">
                                        Tagihan Berjalan &middot; Jatuh Tempo:
                                        {{ $t->jatuh_tempo ? \Carbon\Carbon::parse($t->jatuh_tempo)->format('d M Y') : '-' }}
                                        @if($t->jatuh_tempo && \Carbon\Carbon::parse($t->jatuh_tempo)->isPast())
                                            <span class="badge bg-danger ms-1" style="font-size:10px;">Lewat Jatuh Tempo</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="tagihan-item-right">
                                    <a href="{{ url('/kwitansi/'.$t->id) }}" target="_blank" class="btn-cetak">
                                        <i class="bi bi-printer-fill"></i> Cetak Kwitansi
                                    </a>
                                <button class="btn-bayar"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalBayar{{ $t->id }}">Bayar
                                </button>
                                </div>
                            </div>

                            @else
                            {{-- SUDAH LUNAS --}}
                            <div class="tagihan-item">
                                <div class="tagihan-item-left">
                                    <div class="tagihan-bulan">
                                        {{ strtoupper(\Carbon\Carbon::parse($t->tanggal)->format('M Y')) }}
                                        — {{ \Illuminate\Support\Str::title($t->jenis_tagihan ?? 'tagihan internet bulanan') }}
                                    </div>
                                    <div class="tagihan-status-text">
                                        Lunas:
{{ $t->pembayaran ? \Carbon\Carbon::parse($t->pembayaran->tanggal_bayar)->format('d/m/Y') : '-' }}

via
{{ $t->pembayaran && $t->pembayaran->metode ? strtoupper($t->pembayaran->metode->nama_metode) : 'KAS' }}
                                    </div>
                                </div>
                                <div class="tagihan-item-right">
                                    <a href="{{ url('/tagihan/'.$t->id.'/kwitansi') }}" class="btn-cetak">
                                        <i class="bi bi-printer-fill"></i> Cetak Kwitansi
                                    </a>
                                    <form method="POST"
                                          action="{{ url('/tagihan/'.$t->id) }}"
                                          onsubmit="return confirm('Hapus data tagihan ini?')"
                                          style="margin:0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-hapus">Hapus</button>
                                    </form>
                                </div>
                            </div>
                            @endif

                        @endforeach

                    @else
                    <div class="empty-state">
                        <i class="bi bi-inbox"></i>
                        <p>Belum ada data tagihan</p>
                    </div>
                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

<!-- MODAL: DETAIL PELANGGAN -->
<div class="modal fade" id="detailModal{{ $pelanggan->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4">

            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold">Detail Layanan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">
                <div class="mb-3"><strong>Nama :</strong> {{ $pelanggan->nama }}</div>
                <div class="mb-3"><strong>No HP :</strong> {{ $pelanggan->no_hp ?? '-' }}</div>
                <div class="mb-3"><strong>Alamat :</strong> {{ $pelanggan->alamat ?? '-' }}</div>
                <div class="mb-3"><strong>Paket :</strong> {{ $pelanggan->layanan->nama_paket ?? '-' }}</div>
                <div class="mb-3"><strong>Harga :</strong> Rp {{ number_format($pelanggan->layanan->harga ?? 0,0,',','.') }}</div>
                <div class="mb-3"><strong>Status :</strong> {{ ucfirst($pelanggan->status) }}</div>
                <div class="mb-3"><strong>Tanggal Aktivasi :</strong> {{ $pelanggan->created_at->format('d M Y') }}</div>
            </div>

        </div>
    </div>
</div>

<!-- MODAL: BUAT TAGIHAN -->
<div class="modal fade" id="tambahTagihan" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form action="{{ route('tagihan.store') }}" method="POST">
            @csrf

            <input type="hidden" name="pelanggan_id" value="{{ $pelanggan->id }}">
            <input type="hidden" name="layanan_id"   value="{{ $pelanggan->layanan->id ?? '' }}">

            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Buat Tagihan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="mb-3">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" id="inputTanggal"
                           class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Jatuh Tempo</label>
                    <input type="date" name="jatuh_tempo" id="inputJatuhTempo"
                           class="form-control" readonly>
                </div>

                <div class="mb-3">
                    <label>Jumlah Tagihan</label>
                    <input type="number" name="total" class="form-control"
                           value="{{ $pelanggan->layanan->harga ?? 0 }}" required>
                </div>

                <div class="mb-3">
                    <label>Jenis Tagihan</label>
                    <select name="jenis_tagihan" class="form-control" required>
                        <option value="">-- Pilih Jenis --</option>
                        <option value="tagihan internet bulanan" selected>Tagihan Internet Bulanan</option>
                        <option value="tagihan instalasi">Tagihan Instalasi</option>
                        <option value="tagihan penjualan alat">Tagihan Penjualan Alat</option>
                        <option value="pendapatan jasa">Pendapatan Jasa</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Keterangan</label>
                    <textarea name="keterangan" class="form-control"></textarea>
                </div>

            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-success">
                    Simpan Tagihan
                </button>
            </div>

            </form>
        </div>
    </div>
</div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('inputTanggal').addEventListener('change', function () {
            const tgl = new Date(this.value);
            tgl.setDate(tgl.getDate() + 3);
            document.getElementById('inputJatuhTempo').value = tgl.toISOString().split('T')[0];
        });
    });
</script>
  
@foreach($tagihan as $t)
@if(strtolower($t->status) != 'lunas')

<div class="modal fade modal-bayar" id="modalBayar{{ $t->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="bayar-header" style="display:flex; align-items:center; justify-content:space-between; padding: 20px;">
    <span></span>
    <span>Pembayaran Tagihan</span>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

            <form method="POST" action="{{ route('tagihan.bayar', $t->id) }}">
                @csrf

                <div class="bayar-body">

                    <!-- INFO TAGIHAN -->
                   <!-- PERIODE -->
<div class="bayar-box" style="background: linear-gradient(135deg,#f0fdf4,#dcfce7); border-color:#bbf7d0;">
    
    <div class="bayar-label" style="color:#15803d;">
        Periode Tagihan
    </div>

    <div style="display:flex; align-items:center; gap:10px;">
        
        <div style="
            width:36px;
            height:36px;
            border-radius:10px;
            background:#22c55e;
            display:flex;
            align-items:center;
            justify-content:center;
            color:#fff;
            font-size:16px;
        ">
            <i class="bi bi-calendar-event"></i>
        </div>

        <div>
            <div style="font-size:14px; font-weight:800; color:#166534;">
                {{ strtoupper(\Carbon\Carbon::parse($t->tanggal)->translatedFormat('F Y')) }}
            </div>
            <div style="font-size:11px; color:#4b5563;">
                Periode penagihan layanan
            </div>
        </div>

    </div>
</div>

<!-- TOTAL -->
<div class="bayar-box">
    <div class="bayar-label">Total Tagihan</div>
    <div class="input-group">
        <span class="input-group-text">Rp</span>
        <input type="number"
               name="total"
               class="form-control bayar-input"
               value="{{ $t->total }}"
               required>
    </div>
</div>

                    <!-- TANGGAL -->
                    <div class="bayar-box">
                        <div class="bayar-label">Tanggal Pembayaran</div>
                        <input type="date" name="tanggal_bayar"
                               class="form-control bayar-input"
                               value="{{ date('Y-m-d') }}" required>
                    </div>

                    <!-- METODE -->
                    <div class="bayar-box">
                        <div class="bayar-label">Metode Pembayaran</div>
                        <select name="metode_id" class="form-select bayar-input" required>
                            <option value="">Pilih Metode</option>
                            @foreach($metode as $m)
                                <option value="{{ $m->id }}">
                                    {{ $m->nama_metode }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                   <!-- JENIS TAGIHAN -->
<div class="bayar-box">
    <div class="bayar-label">Jenis Tagihan</div>
    <input type="text" 
           name="keterangan"
           class="form-control bayar-input"
           value="{{ \Illuminate\Support\Str::title($t->jenis_tagihan ?? 'Tagihan Internet Bulanan') }}"
           readonly
           style="background:#f8fafc; color:#374151; font-weight:700;">
</div>

                    <!-- BUTTON -->
                    <button type="submit" class="btn-konfirmasi">
                        KONFIRMASI PEMBAYARAN
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>

@endif
@if(session('success'))
<div id="overlaySuccess" style="
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.45);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: fadeIn .25s ease;
">
    <div style="
        background: #fff;
        border-radius: 24px;
        box-shadow: 0 30px 80px rgba(0,0,0,0.2);
        padding: 48px 40px;
        text-align: center;
        max-width: 360px;
        width: 90%;
        animation: popIn .3s ease;
    ">
        <!-- ICON -->
        <div style="
            width: 80px; height: 80px;
            background: linear-gradient(135deg,#dcfce7,#bbf7d0);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        ">
            <i class="bi bi-check-lg" style="font-size:40px; color:#16a34a;"></i>
        </div>

        <!-- TEKS -->
        <div style="font-size:22px; font-weight:900; color:#111; margin-bottom:8px;">
            Pembayaran Berhasil!
        </div>
        <div style="font-size:14px; color:#6b7280; margin-bottom:32px;">
            {{ session('success') }}
        </div>

        <!-- TOMBOL -->
        <button onclick="document.getElementById('overlaySuccess').remove()" style="
            background: linear-gradient(135deg,#16a34a,#22c55e);
            color: #fff;
            border: none;
            padding: 13px 48px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 800;
            cursor: pointer;
            transition: .2s;
        ">
            OK
        </button>
    </div>
</div>

<style>
@keyframes fadeIn {
    from { opacity:0; }
    to   { opacity:1; }
}
@keyframes popIn {
    from { opacity:0; transform: scale(.85); }
    to   { opacity:1; transform: scale(1); }
}
</style>

<script>
    setTimeout(() => {
        const el = document.getElementById('overlaySuccess');
        if (el) el.remove();
    }, 4000);
</script>
@endif
@endforeach
@if(session('success'))
<div id="overlaySuccess" ...>
    ...
</div>
@endif
</body>
</html>