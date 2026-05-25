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
<link rel="stylesheet" href="{{ asset('detail.css') }}">
</head>
<body>

<div style="display:flex; min-height:100vh;">

    {{-- ===== SIDEBAR ===== --}}
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="hamburger"><span></span><span></span><span></span></div>
            <span class="logo-text">JAGONET</span>
        </div>

        <div class="section-label">Main Board</div>

        <a href="{{ url('admin') }}" class="menu-item">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="{{ url('/layanan') }}" class="menu-item active">
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
    {{-- ===== END SIDEBAR ===== --}}


    {{-- ===== MAIN CONTENT ===== --}}
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

                {{-- ===== PROFIL CARD ===== --}}
                <div class="col-md-4 col-lg-3">
                    <div class="profil-card">

                        <div class="profil-card-top">
                            <div class="profil-avatar-row">
                                <div class="profil-avatar">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                                <div>
                                    <div class="profil-nama">{{ $pelanggan->nama }}</div>
                                    <div class="profil-kode">{{ $pelanggan->kode_pelanggan ?? '-' }}</div>
                                </div>
                            </div>

                            <div class="profil-badges">
                                @if(strtolower($pelanggan->status ?? '') == 'aktif')
                                    <span class="badge-aktif">Aktif</span>
                                @elseif(strtolower($pelanggan->status ?? '') == 'isolir')
                                    <span class="badge-isolir">Isolir</span>
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

                            @if(strtolower($pelanggan->status) == 'isolir')
                            <button type="button"
                                    class="btn-edit-tagihan"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalAktivasi">
                                <i class="bi bi-wifi"></i> Aktifkan Kembali
                            </button>
                            @else
                            <button type="button"
                                    class="btn-isolir"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalIsolir">
                                <i class="bi bi-lock-fill"></i> Isolir Pelanggan
                            </button>
                            @endif
                        </div>

                    </div>
                </div>

                {{-- ===== DAFTAR TAGIHAN ===== --}}
                <div class="col-md-8 col-lg-9">

                    <div class="tagihan-section-title">Daftar Tagihan</div>

                    @if(isset($tagihan) && $tagihan->count() > 0)
                        @foreach($tagihan as $t)
                        @php
                            $terbayar = $t->pembayaran->sum('jumlah_bayar') ?? 0;
                            $sisa     = $t->total - $terbayar;
                        @endphp

                        @if(strtolower($t->status) != 'lunas')
                        {{-- Tagihan belum lunas --}}
                        <div class="tagihan-item berjalan">
                            <div class="tagihan-item-left">
                                <div class="tagihan-bulan">
                                    {{ strtoupper(\Carbon\Carbon::parse($t->tanggal)->format('M Y')) }}
                                    — {{ \Illuminate\Support\Str::title($t->jenis_tagihan ?? 'tagihan internet bulanan') }}
                                </div>
                                <div class="tagihan-status-text">
                                    @if($terbayar > 0)
                                        Belum Lunas &middot; Terbayar:
                                        <strong style="color:#f59e0b;">Rp {{ number_format($terbayar, 0, ',', '.') }}</strong>
                                        &middot; Sisa:
                                        <strong style="color:#ef4444;">Rp {{ number_format($sisa, 0, ',', '.') }}</strong>
                                    @else
                                        Tagihan Berjalan &middot; Jatuh Tempo:
                                        {{ $t->jatuh_tempo ? \Carbon\Carbon::parse($t->jatuh_tempo)->format('d M Y') : '-' }}
                                    @endif
                                    @if($t->jatuh_tempo && \Carbon\Carbon::parse($t->jatuh_tempo)->isPast())
                                        <span class="badge bg-danger ms-1" style="font-size:10px;">Lewat Jatuh Tempo</span>
                                    @endif
                                </div>
                            </div>
                            <div class="tagihan-item-right">
                                <a href="{{ url('/tagihan/'.$t->id.'/kwitansi') }}" class="btn-cetak">
                                    <i class="bi bi-printer-fill"></i> Cetak Kwitansi
                                </a>
                                <button type="button"
                                        class="btn-bayar"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalBayar{{ $t->id }}">
                                    Bayar
                                </button>
                            </div>
                        </div>

                        @else
                        {{-- Tagihan lunas --}}
                        <div class="tagihan-item">
                            <div class="tagihan-item-left">
                                <div class="tagihan-bulan">
                                    {{ strtoupper(\Carbon\Carbon::parse($t->tanggal)->format('M Y')) }}
                                    — {{ \Illuminate\Support\Str::title($t->jenis_tagihan ?? 'tagihan internet bulanan') }}
                                </div>
                                <div class="tagihan-status-text">
                                    Lunas:
                                    {{ $t->pembayaran->last() ? \Carbon\Carbon::parse($t->pembayaran->last()->tanggal_bayar)->format('d/m/Y') : '-' }}
                                    via
                                    {{ $t->pembayaran->last() && $t->pembayaran->last()->metode ? strtoupper($t->pembayaran->last()->metode->nama_metode) : 'KAS' }}
                                </div>
                            </div>
                            <div class="tagihan-item-right">
                                <a href="{{ url('/tagihan/'.$t->id.'/kwitansi') }}" class="btn-cetak">
                                    <i class="bi bi-printer-fill"></i> Cetak Kwitansi
                                </a>
                                <button type="button"
                                        class="btn-hapus"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalHapus{{ $t->id }}">
                                    Hapus
                                </button>
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
    {{-- ===== END MAIN CONTENT ===== --}}

</div>


{{-- ===== MODAL DETAIL LAYANAN ===== --}}
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
                <div class="mb-3"><strong>Harga :</strong> Rp {{ number_format($pelanggan->layanan->harga ?? 0, 0, ',', '.') }}</div>
                <div class="mb-3"><strong>Status :</strong> {{ ucfirst($pelanggan->status) }}</div>
                <div class="mb-3"><strong>Tanggal Aktivasi :</strong> {{ $pelanggan->created_at->format('d M Y') }}</div>
            </div>
        </div>
    </div>
</div>


{{-- ===== MODAL BUAT TAGIHAN ===== --}}
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
                    <input type="date" name="tanggal" id="inputTanggal" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Jatuh Tempo</label>
                    <input type="date" name="jatuh_tempo" id="inputJatuhTempo" class="form-control" readonly>
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
                <button type="submit" class="btn btn-success">Simpan Tagihan</button>
            </div>
            </form>
        </div>
    </div>
</div>


{{-- ===== MODAL KONFIRMASI ISOLIR ===== --}}
<div class="modal fade modal-isolir" id="modalIsolir" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content">
            <div class="isolir-head">
                <div class="isolir-head-icon">
                    <i class="bi bi-lock-fill"></i>
                </div>
                <div class="isolir-head-text">
                    <div class="title">Isolir pelanggan</div>
                    <div class="sub">Layanan akan ditangguhkan sementara</div>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="isolir-body">
                <div class="isolir-pelanggan">
                    <div class="isolir-avatar">{{ strtoupper(substr($pelanggan->nama, 0, 2)) }}</div>
                    <div>
                        <div class="isolir-nama">
                            {{ $pelanggan->nama }}
                            <span class="isolir-badge-paket">
                                {{ $pelanggan->layanan->kecepatan ?? '' }}/{{ $pelanggan->layanan->nama_paket ?? '-' }}
                            </span>
                        </div>
                        <div class="isolir-meta">
                            {{ $pelanggan->kode_pelanggan ?? '-' }} &middot;
                            Aktif sejak {{ \Carbon\Carbon::parse($pelanggan->layanan->created_at ?? now())->format('d M Y') }}
                        </div>
                    </div>
                </div>
                <div class="isolir-alert">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span>Pastikan proses isolir telah dikonfirmasi kepada pelanggan.</span>
                </div>
            </div>
            <div class="isolir-footer">
                <a href="{{ route('layanan.isolir', $pelanggan->id) }}" class="btn-isolir-konfirm">
                    <i class="bi bi-lock-fill"></i> Ya, isolir sekarang
                </a>
            </div>
        </div>
    </div>
</div>


{{-- ===== MODAL KONFIRMASI AKTIVASI ===== --}}
<div class="modal fade modal-isolir" id="modalAktivasi" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content" style="border:none; border-radius:20px; overflow:hidden;">
            <div class="isolir-head" style="border-bottom:1px solid #e5e7eb;">
                <div class="isolir-head-icon" style="background:#dbeafe;">
                    <i class="bi bi-wifi" style="color:#1d4ed8;"></i>
                </div>
                <div class="isolir-head-text">
                    <div class="title">Aktifkan kembali</div>
                    <div class="sub">Layanan akan dipulihkan untuk pelanggan</div>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="isolir-body">
                <div class="isolir-pelanggan">
                    <div class="isolir-avatar" style="background:#dbeafe; color:#2563eb;">
                        {{ strtoupper(substr($pelanggan->nama, 0, 2)) }}
                    </div>
                    <div>
                        <div class="isolir-nama">
                            {{ $pelanggan->nama }}
                            <span class="isolir-badge-paket">
                                {{ $pelanggan->layanan->kecepatan ?? '' }}/{{ $pelanggan->layanan->nama_paket ?? '-' }}
                            </span>
                        </div>
                        <div class="isolir-meta">
                            {{ $pelanggan->kode_pelanggan ?? '-' }} &middot;
                            Isolir sejak {{ \Carbon\Carbon::parse($pelanggan->updated_at ?? now())->format('d M Y') }}
                        </div>
                    </div>
                </div>
                <div class="isolir-alert"
                     style="background:#eff6ff; border-color:#bfdbfe; border-left-color:#3b82f6; color:#1d4ed8;">
                    <i class="bi bi-info-circle-fill" style="color:#3b82f6;"></i>
                    <span>Layanan pelanggan akan kembali aktif setelah proses aktivasi.</span>
                </div>
            </div>
            <div class="isolir-footer">
                <a href="{{ route('layanan.aktifkan', $pelanggan->id) }}"
                   class="btn-isolir-konfirm"
                   style="background:#dbeafe; border-color:#bfdbfe; color:#1d4ed8;">
                    <i class="bi bi-wifi"></i> Ya, aktifkan sekarang
                </a>
            </div>
        </div>
    </div>
</div>


{{-- ===== MODAL BAYAR (per tagihan) ===== --}}
@foreach($tagihan as $t)
@if(strtolower($t->status) != 'lunas')
@php
    $sudahBayar  = $t->pembayaran->sum('jumlah_bayar') ?? 0;
    $sisaTagihan = $t->total - $sudahBayar;
@endphp
<div class="modal fade modal-bayar" id="modalBayar{{ $t->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="bayar-header"
                 style="display:flex; align-items:center; justify-content:space-between; padding:20px;">
                <span></span>
                <span>Pembayaran Tagihan</span>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('tagihan.bayar', $t->id) }}">
                @csrf
                <div class="bayar-body">
                    <div class="bayar-box"
                         style="background:linear-gradient(135deg,#f0fdf4,#dcfce7); border-color:#bbf7d0;">
                        <div class="bayar-label" style="color:#15803d;">Periode Tagihan</div>
                        <div style="display:flex; align-items:center; gap:10px;">
                            <div style="width:36px; height:36px; border-radius:10px; background:#22c55e;
                                        display:flex; align-items:center; justify-content:center;
                                        color:#fff; font-size:16px;">
                                <i class="bi bi-calendar-event"></i>
                            </div>
                            <div>
                                <div style="font-size:14px; font-weight:800; color:#166534;">
                                    {{ strtoupper(\Carbon\Carbon::parse($t->tanggal)->translatedFormat('F Y')) }}
                                </div>
                                <div style="font-size:11px; color:#4b5563;">Periode penagihan layanan</div>
                            </div>
                        </div>
                    </div>
                    <div class="bayar-box">
                        <div class="bayar-label">Total Tagihan</div>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" class="form-control bayar-input"
                                   value="{{ number_format($t->total, 0, ',', '.') }}"
                                   readonly style="background:#f8fafc; font-weight:700;">
                        </div>
                    </div>
                    @if($sudahBayar > 0)
                    <div class="bayar-box" style="background:#fffbeb; border-color:#fde68a;">
                        <div class="bayar-label" style="color:#b45309;">Sudah Dibayar</div>
                        <div style="font-size:14px; font-weight:800; color:#92400e;">
                            Rp {{ number_format($sudahBayar, 0, ',', '.') }}
                            <span style="font-size:11px; font-weight:500; color:#b45309;">
                                (sisa: Rp {{ number_format($sisaTagihan, 0, ',', '.') }})
                            </span>
                        </div>
                    </div>
                    @endif
                    <div class="bayar-box">
                        <div class="bayar-label">Total Bayar</div>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="total" class="form-control bayar-input"
                                   value="{{ $sisaTagihan ?? $t->total }}"
                                   max="{{ $sisaTagihan ?? $t->total }}" min="1" required>
                        </div>
                        <small class="text-muted mt-1 d-block" style="font-size:10.5px; padding:4px 2px;">
                            Boleh diisi sebagian. Maks: Rp {{ number_format($sisaTagihan ?? $t->total, 0, ',', '.') }}
                        </small>
                    </div>
                    <div class="bayar-box">
                        <div class="bayar-label">Tanggal Pembayaran</div>
                        <input type="date" name="tanggal_bayar" class="form-control bayar-input"
                               value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="bayar-box">
                        <div class="bayar-label">Metode Pembayaran</div>
                        <select name="metode_id" class="form-select bayar-input" required>
                            <option value="">Pilih Metode</option>
                            @foreach($metode as $m)
                                <option value="{{ $m->id }}">{{ $m->nama_metode }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="bayar-box">
                        <div class="bayar-label">Jenis Tagihan</div>
                        <input type="text" name="keterangan" class="form-control bayar-input"
                               value="{{ \Illuminate\Support\Str::title($t->jenis_tagihan ?? 'Tagihan Internet Bulanan') }}"
                               readonly style="background:#f8fafc; color:#374151; font-weight:700;">
                    </div>
                    <button type="submit" class="btn-konfirmasi">KONFIRMASI PEMBAYARAN</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endforeach


{{-- ===== MODAL KONFIRMASI HAPUS TAGIHAN ===== --}}
@foreach($tagihan as $t)
@if(strtolower($t->status) == 'lunas')
<div class="modal fade" id="modalHapus{{ $t->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:400px;">
        <div class="modal-content" style="border:none; border-radius:20px; overflow:hidden;">

            <div style="background:linear-gradient(135deg,#dc2626,#ef4444);
                        padding:18px 20px; display:flex; align-items:center; gap:12px;">
                <div style="width:40px; height:40px; border-radius:50%;
                            background:rgba(255,255,255,.18); display:flex;
                            align-items:center; justify-content:center;
                            font-size:19px; color:#fff; flex-shrink:0;">
                    <i class="bi bi-trash-fill"></i>
                </div>
                <div style="flex:1;">
                    <div style="font-size:15px; font-weight:600; color:#fff; line-height:1.2;">Hapus tagihan</div>
                    <div style="font-size:11.5px; color:rgba(255,255,255,.82); margin-top:2px;">Tindakan ini tidak dapat dibatalkan</div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div style="padding:18px 20px 14px;">
                <div style="display:flex; align-items:center; gap:12px;
                            padding:13px 14px; border-radius:10px;
                            border:1px solid #f3f4f6; background:#f9fafb; margin-bottom:12px;">
                    <div style="width:38px; height:38px; border-radius:10px;
                                background:#fee2e2; display:flex; align-items:center;
                                justify-content:center; font-size:18px; color:#dc2626; flex-shrink:0;">
                        <i class="bi bi-receipt"></i>
                    </div>
                    <div>
                        <div style="font-size:14px; font-weight:600; color:#111827;">
                            {{ strtoupper(\Carbon\Carbon::parse($t->tanggal)->format('M Y')) }}
                            &nbsp;<span style="display:inline-block; font-size:11px; font-weight:500;
                                        background:#fee2e2; color:#dc2626; border:1px solid #fecaca;
                                        border-radius:20px; padding:2px 9px;">Lunas</span>
                        </div>
                        <div style="font-size:12px; color:#6b7280; margin-top:2px;">
                            {{ \Illuminate\Support\Str::title($t->jenis_tagihan ?? 'Tagihan Internet Bulanan') }}
                            &middot; Rp {{ number_format($t->total, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <div style="display:flex; align-items:flex-start; gap:9px;
                            background:#fef2f2; border:1px solid #fecaca;
                            border-left:3px solid #dc2626; border-radius:8px;
                            padding:11px 13px; font-size:12.5px; color:#b91c1c; line-height:1.6;">
                    <i class="bi bi-exclamation-triangle-fill"
                       style="font-size:15px; color:#dc2626; flex-shrink:0; margin-top:1px;"></i>
                    <span>Data tagihan dan seluruh riwayat pembayaran terkait akan dihapus secara permanen.</span>
                </div>
            </div>

            <div style="padding:12px 20px 18px; border-top:1px solid #f3f4f6; display:flex; gap:8px;">
                
                <form method="POST" action="{{ url('/tagihan/'.$t->id) }}" style="flex:1; margin:0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            style="width:100%; padding:10px; border-radius:8px; border:none;
                                   background:linear-gradient(135deg,#dc2626,#ef4444);
                                   color:#fff; font-size:13px; font-weight:600; cursor:pointer;
                                   display:flex; align-items:center; justify-content:center; gap:6px;">
                        <i class="bi bi-trash-fill"></i> Ya, hapus sekarang
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
@endif
@endforeach


{{-- ===== OVERLAY SUCCESS ===== --}}
@if(session('success'))
<div id="overlaySuccess" class="notif-overlay">
    <div class="notif-card">
        <div class="notif-stripe" style="background:#16a34a;"></div>
        <div class="notif-inner">
            <div class="notif-icon-wrap" style="border-color:#bbf7d0; color:#16a34a;">
                <i class="bi bi-check-lg"></i>
            </div>
            <div class="notif-label" style="color:#16a34a;">Berhasil</div>
            <div class="notif-title">Pembayaran Dikonfirmasi</div>
            <div class="notif-text">{{ session('success') }}</div>
        </div>
        <div class="notif-divider"></div>
        <button onclick="document.getElementById('overlaySuccess').remove()"
                class="notif-btn" style="color:#16a34a;">Tutup</button>
    </div>
</div>
<script>
setTimeout(() => {
    const el = document.getElementById('overlaySuccess');
    if (el) { el.style.opacity = '0'; setTimeout(() => el.remove(), 300); }
}, 4000);
</script>
@endif


{{-- ===== OVERLAY TAGIHAN ===== --}}
@if(session('tagihan_berhasil'))
<div id="overlayTagihan" class="notif-overlay">
    <div class="notif-card">
        <div class="notif-stripe" style="background:#0f766e;"></div>
        <div class="notif-inner">
            <div class="notif-icon-wrap" style="border-color:#99f6e4; color:#0f766e;">
                <i class="bi bi-receipt"></i>
            </div>
            <div class="notif-label" style="color:#0f766e;">Tagihan</div>
            <div class="notif-title">Tagihan Dibuat</div>
            <div class="notif-text">{{ session('tagihan_berhasil') }}</div>
        </div>
        <div class="notif-divider"></div>
        <button onclick="document.getElementById('overlayTagihan').remove()"
                class="notif-btn" style="color:#0f766e;">Tutup</button>
    </div>
</div>
<script>
setTimeout(() => {
    const el = document.getElementById('overlayTagihan');
    if (el) { el.style.opacity = '0'; setTimeout(() => el.remove(), 300); }
}, 4000);
</script>
@endif


{{-- ===== OVERLAY ISOLIR ===== --}}
@if(session('isolir_berhasil'))
<div id="overlayIsolir" class="notif-overlay">
    <div class="notif-card">
        <div class="notif-stripe" style="background:#92400e;"></div>
        <div class="notif-inner">
            <div class="notif-icon-wrap" style="border-color:#fde68a; color:#92400e;">
                <i class="bi bi-lock-fill"></i>
            </div>
            <div class="notif-label" style="color:#92400e;">Isolir</div>
            <div class="notif-title">Isolir Berhasil</div>
            <div class="notif-text">{{ session('isolir_berhasil') }}</div>
        </div>
        <div class="notif-divider"></div>
        <button onclick="document.getElementById('overlayIsolir').remove()"
                class="notif-btn" style="color:#92400e;">Tutup</button>
    </div>
</div>
<script>
setTimeout(() => {
    const el = document.getElementById('overlayIsolir');
    if (el) { el.style.opacity = '0'; setTimeout(() => el.remove(), 300); }
}, 4000);
</script>
@endif


{{-- ===== OVERLAY AKTIVASI ===== --}}
@if(session('aktivasi_berhasil'))
<div id="overlayAktivasi" class="notif-overlay">
    <div class="notif-card">
        <div class="notif-stripe" style="background:#1d4ed8;"></div>
        <div class="notif-inner">
            <div class="notif-icon-wrap" style="border-color:#bfdbfe; color:#1d4ed8;">
                <i class="bi bi-wifi"></i>
            </div>
            <div class="notif-label" style="color:#1d4ed8;">Aktif</div>
            <div class="notif-title">Aktivasi Berhasil</div>
            <div class="notif-text">{{ session('aktivasi_berhasil') }}</div>
        </div>
        <div class="notif-divider"></div>
        <button onclick="document.getElementById('overlayAktivasi').remove()"
                class="notif-btn" style="color:#1d4ed8;">Tutup</button>
    </div>
</div>
<script>
setTimeout(() => {
    const el = document.getElementById('overlayAktivasi');
    if (el) { el.style.opacity = '0'; setTimeout(() => el.remove(), 300); }
}, 4000);
</script>
@endif


{{-- ===== SCRIPTS ===== --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputTanggal = document.getElementById('inputTanggal');
    if (inputTanggal) {
        inputTanggal.addEventListener('change', function () {
            const tgl = new Date(this.value);
            tgl.setDate(tgl.getDate() + 3);
            document.getElementById('inputJatuhTempo').value = tgl.toISOString().split('T')[0];
        });
    }
});
</script>

</body>
</html>