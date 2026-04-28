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
body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: #f4f6f9;
}

/* ==========================
   PROFIL CARD (kiri)
   ========================== */
.profil-card {
    background: #fff;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    width: 100%;
}

.profil-card-top {
    background: linear-gradient(160deg, #1e9e42, #22c55e);
    padding: 18px 18px 42px;
    position: relative;
}

.profil-avatar-row {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 10px;
}

.profil-avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: rgba(255,255,255,0.25);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
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
    font-size: 10px;
    color: rgba(255,255,255,0.7);
    letter-spacing: 0.3px;
}

.profil-badges {
    position: absolute;
    bottom: 12px;
    left: 18px;
    display: flex;
    gap: 7px;
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

/* Info rows */
.profil-info {
    padding: 14px 18px 10px;
}

.profil-info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #f3f4f6;
    font-size: 12.5px;
}

.profil-info-row:last-child {
    border-bottom: none;
}

.profil-info-row .key {
    color: #aaa;
    font-weight: 500;
}

.profil-info-row .val {
    color: #111;
    font-weight: 700;
    text-align: right;
}

/* Action buttons */
.profil-actions {
    padding: 4px 18px 18px;
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
    font-size: 12px;
    font-weight: 700;
    padding: 9px;
    border-radius: 9px;
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
    font-size: 12px;
    font-weight: 700;
    padding: 9px;
    border-radius: 9px;
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

/* ==========================
   DAFTAR TAGIHAN (kanan)
   ========================== */
.tagihan-section-title {
    font-size: 17px;
    font-weight: 800;
    color: #111;
    margin-bottom: 14px;
}

.tagihan-item {
    background: #fff;
    border-radius: 11px;
    padding: 13px 16px;
    margin-bottom: 8px;
    box-shadow: 0 1px 5px rgba(0,0,0,0.05);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
}

.tagihan-item.berjalan {
    border: 1.5px solid #bbf7d0;
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
    margin-bottom: 2px;
}

.tagihan-item.berjalan .tagihan-bulan {
    color: #15803d;
}

.tagihan-status-text {
    font-size: 11px;
    color: #999;
}

.tagihan-item-right {
    display: flex;
    align-items: center;
    gap: 7px;
    flex-shrink: 0;
}

/* Tombol Cetak Kwitansi */
.btn-cetak {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #f3f4f6;
    border: 1px solid #e5e7eb;
    color: #555;
    font-size: 11px;
    font-weight: 700;
    padding: 6px 12px;
    border-radius: 7px;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.15s;
    white-space: nowrap;
}

.btn-cetak:hover {
    background: #e5e7eb;
    color: #111;
}

/* Tombol Bayar */
.btn-bayar {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #22c55e;
    border: none;
    color: #fff;
    font-size: 11px;
    font-weight: 800;
    padding: 6px 16px;
    border-radius: 7px;
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

/* Tombol Hapus */
.btn-hapus {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #ff4d4d;
    border: none;
    color: #fff;
    font-size: 11px;
    font-weight: 800;
    padding: 6px 16px;
    border-radius: 7px;
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

/* Empty state */
.empty-state {
    text-align: center;
    padding: 50px 20px;
    color: #ccc;
}

.empty-state i {
    font-size: 40px;
    margin-bottom: 12px;
    display: block;
}

.empty-state p {
    font-size: 13px;
    margin: 0;
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
    <!-- END SIDEBAR -->

    <!-- MAIN CONTENT -->
    <div class="main-content" style="flex:1;">

        <!-- TOPBAR -->
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

        <!-- CONTENT AREA -->
        <div style="padding:24px;">

            <div class="row g-4 align-items-start">

                <!-- ====== KIRI: PROFIL CARD ====== -->
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

                        {{-- INFO ROWS --}}
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
                                <span class="key">Tanggal Aktivasi</span>
                                <span class="val">{{ \Carbon\Carbon::parse($pelanggan->layanan->created_at)->format('d M Y') }}</span>
                            </div>
                        </div>
                        
                        {{-- ACTION BUTTONS --}}
                        <div class="profil-actions">
                            <a href="{{ url('/layanan/'.$pelanggan->id.'/edit') }}" class="btn-edit-tagihan">
                                <i class="bi bi-pencil-square"></i> Edit Tagihan
                            </a>
                            <a href="{{ url('/layanan/'.$pelanggan->id.'/isolir') }}" class="btn-isolir">
                                <i class="bi bi-bell-fill"></i> Isolir Pelanggan
                            </a>
                        </div>

                    </div>
                </div>
                {{-- END KIRI --}}

                <!-- ====== KANAN: DAFTAR TAGIHAN ====== -->
                <div class="col-md-8 col-lg-9">

                    <div class="tagihan-section-title">Daftar Tagihan</div>

                    @if(isset($tagihan) && $tagihan->count() > 0)

                        @foreach($tagihan as $t)

                            @if(strtolower($t->status) != 'lunas')
                            {{-- TAGIHAN BELUM LUNAS (berjalan) --}}
                            <div class="tagihan-item berjalan">
                                <div class="tagihan-item-left">
                                    <div class="tagihan-bulan">
                                        {{ \Carbon\Carbon::parse($t->periode)->translatedFormat('F Y') }}
                                    </div>
                                    <div class="tagihan-status-text">Tagihan Berjalan</div>
                                </div>
                                <div class="tagihan-item-right">
                                    <a href="{{ url('/tagihan/'.$t->id.'/kwitansi') }}" class="btn-cetak">
                                        <i class="bi bi-printer-fill"></i> Cetak Kwitansi
                                    </a>
                                    <a href="{{ url('/tagihan/'.$t->id.'/bayar') }}" class="btn-bayar">
                                        Bayar
                                    </a>
                                </div>
                            </div>

                            @else
                            {{-- TAGIHAN SUDAH LUNAS --}}
                            <div class="tagihan-item">
                                <div class="tagihan-item-left">
                                    <div class="tagihan-bulan">
                                        {{ \Carbon\Carbon::parse($t->periode)->translatedFormat('F Y') }}
                                    </div>
                                    <div class="tagihan-status-text">
                                        Lunas : {{ \Carbon\Carbon::parse($t->tanggal_bayar)->format('Y/m/d') }}
                                        via {{ strtoupper($t->metode ?? 'KAS') }}
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
                {{-- END KANAN --}}

            </div>

        </div>
        {{-- END CONTENT AREA --}}

    </div>
    <!-- END MAIN CONTENT -->

</div>
<!-- END WRAPPER -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>