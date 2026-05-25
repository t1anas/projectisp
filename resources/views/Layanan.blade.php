<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Data Layanan - Jagonet</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('inputform.css') }}">
</head>
<body>

<div style="display:flex; min-height:100vh;">

    {{-- ══════════════════════════════════════
         SIDEBAR
    ══════════════════════════════════════ --}}
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="hamburger"><span></span><span></span><span></span></div>
            <span class="logo-text">JAGONET</span>
        </div>

        <div class="section-label">Main Board</div>

        <a href="{{ Auth::user()->dashboard_url }}" class="menu-item">
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
    {{-- END SIDEBAR --}}


    {{-- ══════════════════════════════════════
         MAIN CONTENT
    ══════════════════════════════════════ --}}
    <div class="main-content">

        {{-- Flash Messages --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show m-3">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show m-3">
            <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        {{-- TOPBAR --}}
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

        {{-- OUTER CARD --}}
        <div class="form-card">

            {{-- HEADER CARD --}}
            <div class="form-card-header">
                <div class="icon-wrap">
                    <i class="bi bi-wifi"></i>
                </div>
                <div>
                    <div class="form-card-title">Data Layanan</div>
                    <div class="form-card-sub">Daftar seluruh layanan pelanggan</div>
                </div>
            </div>

            {{-- TOOLBAR / FILTER --}}
            <div class="card-toolbar">
                <div class="d-flex align-items-center gap-2 flex-wrap">

                    {{-- Tombol Data Terpilih --}}
                    <div class="dropdown">
                        <button class="btn-data-terpilih dropdown-toggle"
                                type="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">
                            Data Terpilih
                        </button>
                        <ul class="dropdown-menu shadow">
                            <li>
                                <button type="button"
                                        class="dropdown-item text-danger"
                                        onclick="hapusDataTerpilih()">
                                    <i class="bi bi-trash-fill me-2"></i> Hapus Data Terpilih
                                </button>
                            </li>
                            <li>
                                <div class="item-switch" onclick="ubahStatusTerpilih()">
                                    <i class="bi bi-toggles2"></i>
                                    Ubah Status Terpilih
                                </div>
                            </li>
                        </ul>
                    </div>

                    {{-- Tombol Menu --}}
                    <div class="dropdown">
                        <button class="btn-menu dropdown-toggle"
                                type="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">
                            Menu
                        </button>
                        <ul class="dropdown-menu shadow">
                            <li>
                                <a class="dropdown-item" href="{{ route('layanan.export') }}">
                                    <i class="bi bi-file-earmark-excel me-2 text-success"></i> Export Data
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#"
                                   data-bs-toggle="modal" data-bs-target="#modalImport">
                                    <i class="bi bi-file-earmark-excel me-2 text-danger"></i> Import Data
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('layanan.cetak') }}" target="_blank">
                                    <i class="bi bi-printer me-2 text-secondary"></i> Cetak
                                </a>
                            </li>
                        </ul>
                    </div>

                    {{-- Generate Tagihan --}}
                    <form method="POST"
                          action="{{ route('pelanggan.generateTagihanTerpilih') }}"
                          id="formGenerateTerpilih"
                          style="margin:0;">
                        @csrf
                        <div id="hiddenIdsTerpilih"></div>
                        <button type="button"
                                onclick="generateTerpilih()"
                                class="btn btn-sm"
                                style="height:40px; display:inline-flex; align-items:center; gap:5px;
                                       white-space:nowrap; border:none; color:#fff;
                                       background:linear-gradient(135deg,#09973B,#0ab844);">
                            <i class="bi bi-plus-lg"></i> Generate Tagihan
                        </button>
                    </form>

                    {{-- Filter --}}
                    <form method="GET" action="{{ url('/layanan') }}"
                          class="d-flex align-items-center gap-2 flex-wrap">

                        <input type="text" name="search"
                               class="form-control form-control-sm"
                               style="width:180px; height:40px;"
                               placeholder="Cari pelanggan..."
                               value="{{ request('search') }}">

                        <select name="status"
                                class="form-select form-select-sm"
                                style="width:160px; height:40px;">
                            <option value="">Semua Status</option>
                            <option value="aktif"    {{ request('status') === 'aktif'    ? 'selected' : '' }}>Aktif</option>
                            <option value="isolir"   {{ request('status') === 'isolir'   ? 'selected' : '' }}>Isolir</option>
                            <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>

                        <input type="date" name="dari"
                               class="form-control form-control-sm"
                               style="width:145px; height:40px;"
                               value="{{ request('dari') }}">

                        <input type="date" name="sampai"
                               class="form-control form-control-sm"
                               style="width:145px; height:40px;"
                               value="{{ request('sampai') }}">

                        <button type="submit" class="btn btn-success btn-sm px-3" style="height:40px;">
                            <i class="bi bi-search"></i>
                        </button>

                        @if(request()->hasAny(['search','status','dari','sampai']))
                        <a href="{{ url('/layanan') }}"
                           class="btn btn-outline-secondary btn-sm px-3"
                           style="height:40px; display:inline-flex; align-items:center;">
                            <i class="bi bi-x-lg"></i>
                        </a>
                        @endif

                    </form>
                </div>
            </div>

{{-- TABLE --}}
<div style="padding: 20px;">
    <div class="detail-card">

        {{-- TABLE SCROLL --}}
        <div class="table-responsive">
            <table class="table table-bordered align-middle custom-table mb-0">
                <thead class="table-light text-center fw-bold">
                    <tr>
                        <th style="width:40px;">
                            <input type="checkbox" id="checkAll"
                                   style="width:16px;height:16px;cursor:pointer;"
                                   onchange="toggleAll(this)">
                        </th>
                        <th>No</th>
                        <th style="white-space: nowrap;">Kode Pelanggan</th>
                        <th>Aktivasi</th>
                        <th>Nama</th>
                        <th>Nama Layanan</th>
                        <th>Tagihan</th>
                        <th>Paket</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @forelse($pelanggan as $p)
                    <tr>
                        <td>
                            <input type="checkbox"
                                   name="pelanggan_ids[]"
                                   value="{{ $p->id }}"
                                   class="row-check"
                                   style="width:16px;height:16px;cursor:pointer;"
                                   onchange="updateSelected()">
                        </td>
                        <td>{{ $loop->iteration }}</td>
                        <td style="text-align: left">{{ $p->kode_pelanggan }}</td>
                        <td>
                            <div class="date-main">{{ $p->created_at->format('d/m/Y') }}</div>
                            <div class="date-rel">{{ $p->created_at->diffForHumans() }}</div>
                        </td>
                        <td class="text-start">
                            <div class="clamp-2">{{ $p->nama }}</div>
                        </td>
                        <td class="text-start">
                            <div class="clamp-2">{{ $p->nama_layanan ?? '-' }}</div>
                        </td>
                        <td style="white-space: nowrap;">
                            Rp {{ number_format($p->layanan->harga ?? 0, 0, ',', '.') }}
                        </td>
                        <td style="white-space: nowrap;">
                            {{ $p->layanan->nama_paket ?? '-' }}
                        </td>
                        <td>
                            @if(strtolower($p->status) === 'aktif')
                                <span class="status-pill status-active">
                                    <i class="bi bi-check-circle-fill"></i> Aktif
                                </span>
                            @elseif(strtolower($p->status) === 'isolir')
                                <span class="status-pill status-isolir">
                                    <i class="bi bi-slash-circle-fill"></i> Isolir
                                </span>
                            @else
                                <span class="status-pill status-nonactive">
                                    <i class="bi bi-x-circle-fill"></i> Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="action-group">
                                <a href="{{ route('layanan.detail', $p->id) }}"
                                   class="btn-action btn-action-detail"
                                   data-tip="Detail">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                                <button type="button"
                                        class="btn-action btn-action-edit"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEditPelanggan{{ $p->id }}"
                                        data-tip="Edit">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                <button type="button"
                                        class="btn-action btn-action-notif"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalNotif{{ $p->id }}"
                                        data-tip="Notifikasi">
                                    <i class="bi bi-bell-fill"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5" style="color:#9ca3af; font-size:14px;">
                            <i class="bi bi-inbox" style="font-size:30px; display:block; margin-bottom:8px;"></i>
                            Tidak ada data yang ditemukan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- FOOTER PAGINATION --}}
        <div class="card-identity-row" style="justify-content:space-between;">
            <span class="identity-nik">
                Menampilkan {{ $pelanggan->firstItem() }}–{{ $pelanggan->lastItem() }}
                dari {{ $pelanggan->total() }} data
            </span>
            {{ $pelanggan->appends(request()->query())->links() }}
        </div>

    </div>
</div>
{{-- END TABLE --}}

        </div>
        {{-- END OUTER CARD --}}

    </div>
    {{-- END MAIN CONTENT --}}

</div>


{{-- ══════════════════════════════════════
     MODAL EDIT LAYANAN
══════════════════════════════════════ --}}
@foreach($pelanggan as $p)
<div class="modal fade" id="modalEditPelanggan{{ $p->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ url('/layanan/' . $p->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-header"
                     style="background:linear-gradient(135deg,#28a745,#20c157); color:#fff;">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil-fill me-2"></i> Edit Data Layanan
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control"
                                   value="{{ $p->nama }}"
                                   placeholder="Nama pelanggan" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">No. HP / WhatsApp</label>
                            <input type="text" name="no_hp" class="form-control"
                                   value="{{ $p->no_hp }}"
                                   placeholder="08xxxxxxxxxx">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Layanan</label>
                            <input type="text" name="nama_layanan" class="form-control"
                                   value="{{ $p->nama_layanan }}"
                                   placeholder="Nama layanan internet">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status Pelanggan</label>
                            <select name="status" class="form-select">
                                <option value="aktif"    {{ strtolower($p->status) == 'aktif'    ? 'selected' : '' }}>Aktif</option>
                                <option value="isolir"   {{ strtolower($p->status) == 'isolir'   ? 'selected' : '' }}>Isolir</option>
                                <option value="nonaktif" {{ strtolower($p->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea name="alamat" class="form-control" rows="3"
                                      placeholder="Masukkan alamat lengkap pelanggan...">{{ $p->alamat }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success fw-bold">
                        <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach


{{-- ══════════════════════════════════════
     MODAL NOTIFIKASI
══════════════════════════════════════ --}}
@foreach($pelanggan as $p)
@php $periodeTagihan = \Carbon\Carbon::parse($p->created_at)->subDay(); @endphp

<div class="modal fade" id="modalNotif{{ $p->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content" style="border-radius:12px; overflow:hidden;">

            <div class="modal-header"
                 style="background:linear-gradient(135deg,#28a745,#20c157); color:#fff;">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-bell-fill me-2"></i> Notifikasi
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                {{-- Info Tagihan --}}
                <div class="border rounded p-3 mb-3">
                    <div class="fw-bold text-uppercase mb-2"
                         style="font-size:11px; letter-spacing:.5px; color:#555;">
                        Tagihan Internet Bulanan
                    </div>
                    <div style="font-size:13.5px; color:#444; line-height:1.75;">
                        Nama Pelanggan : {{ $p->nama }}<br>
                        Periode Tagihan : {{ $periodeTagihan->translatedFormat('F Y') }}
                    </div>
                </div>

                {{-- Info Isolir --}}
                <div class="border rounded p-3 mb-3">
                    <div class="fw-bold text-uppercase mb-2"
                         style="font-size:11px; letter-spacing:.5px; color:#555;">
                        Isolir Layanan
                    </div>
                    <div style="font-size:13.5px; color:#444; line-height:1.75;">
                        Yth. {{ $p->nama }},<br>
                        Kami informasikan bahwa layanan internet Anda saat ini
                        <strong>diisolir</strong> dikarenakan tagihan
                        {{ $periodeTagihan->translatedFormat('F Y') }}
                        telah melebihi batas jatuh tempo pembayaran.
                    </div>
                </div>

                {{-- Preview Pesan --}}
                <div class="fw-bold text-uppercase mb-2"
                     style="font-size:11px; letter-spacing:.5px; color:#555;">
                    Preview Pesan
                </div>
                <div class="border rounded p-3 mb-3"
                     style="font-size:13.5px; color:#333; line-height:1.8; background:#f9f9f9;">
                    <strong>TAGIHAN INTERNET BULANAN</strong><br><br>
                    Nama Pelanggan : {{ $p->nama }}<br>
                    Periode Tagihan : {{ $periodeTagihan->translatedFormat('F Y') }}<br>
                    Total Tagihan   : Rp {{ number_format($p->layanan->harga ?? 0, 0, ',', '.') }}<br><br>
                    Silakan lakukan pembayaran sebelum tanggal [Jatuh Tempo] ke rekening berikut:<br>
                    BCA : [No Rekening] a/n [Nama]<br>
                    BRI : [No Rekening] a/n [Nama]<br><br>
                    Kirim bukti pembayaran ke: [Nomor Admin]<br>
                    Terima Kasih 😊🙏
                </div>

                {{-- Tombol Kirim WhatsApp --}}
                @php
                    $noHp  = preg_replace('/[^0-9]/', '', $p->no_hp ?? '');
                    $pesan = urlencode(
                        "TAGIHAN INTERNET BULANAN\n\n" .
                        "Nama Pelanggan : {$p->nama}\n" .
                        "Periode Tagihan : " . $periodeTagihan->translatedFormat('F Y') . "\n" .
                        "Total Tagihan   : Rp " . number_format($p->layanan->harga ?? 0, 0, ',', '.') . "\n\n" .
                        "Silakan lakukan pembayaran sebelum tanggal [Jatuh Tempo].\n" .
                        "Terima Kasih 😊🙏"
                    );
                @endphp
                <a href="https://wa.me/{{ $noHp }}?text={{ $pesan }}"
                   target="_blank"
                   class="btn btn-success fw-bold w-100"
                   style="letter-spacing:1px;">
                    <i class="bi bi-whatsapp me-2"></i> KIRIM VIA WHATSAPP
                </a>

            </div>
        </div>
    </div>
</div>
@endforeach


{{-- ══════════════════════════════════════
     MODAL IMPORT DATA
══════════════════════════════════════ --}}
<div class="modal fade" id="modalImport" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('layanan.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header"
                     style="background:linear-gradient(135deg,#28a745,#20c157); color:#fff;">
                    <h5 class="modal-title">
                        <i class="bi bi-file-earmark-excel me-2"></i> Import Data
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label fw-bold">Pilih File Excel / CSV</label>
                    <input type="file" name="file" class="form-control"
                           accept=".xlsx,.xls,.csv" required>
                    <div class="form-text mt-2">Format yang didukung: .xlsx, .xls, .csv</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success fw-bold">
                        <i class="bi bi-upload me-1"></i> Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- ══════════════════════════════════════
     MODAL UBAH STATUS TERPILIH
══════════════════════════════════════ --}}
<div class="modal fade" id="modalUbahStatus" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content"
             style="border-radius:14px; overflow:hidden; box-shadow:0 20px 60px rgba(0,0,0,.15);">
            <form id="formUbahStatus" method="POST" action="{{ url('/layanan/bulk-status') }}">
                @csrf
                <input type="hidden" name="status" id="selectedStatusValue" value="aktif">
                <div id="hiddenIdsStatus"></div>

                <div class="modal-header"
                     style="background:linear-gradient(135deg,#09973B,#0ab844);
                            color:#fff; padding:16px 20px; border:none;">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:36px; height:36px; border-radius:50%;
                                    background:rgba(255,255,255,.2);
                                    display:flex; align-items:center;
                                    justify-content:center; font-size:17px;">
                            <i class="bi bi-toggles2"></i>
                        </div>
                        <div>
                            <div style="font-weight:700; font-size:15px; line-height:1.2;">
                                Ubah Status Terpilih
                            </div>
                            <div style="font-size:11.5px; opacity:.85;">
                                <i class="bi bi-people-fill me-1"></i>
                                <span id="jumlahTerpilih">0</span> data dipilih
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white ms-auto"
                            data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body" style="padding:18px 20px;">
                    <p style="font-size:12.5px; color:#6b7280; margin-bottom:14px;">
                        Pilih status baru yang akan diterapkan pada data terpilih:
                    </p>

                    <div class="status-option opt-aktif selected" onclick="pilihStatus(this,'aktif')">
                        <div class="so-icon"><i class="bi bi-check-circle-fill"></i></div>
                        <div>
                            <div class="so-label text-success">Aktif</div>
                            <div class="so-desc">Layanan berjalan normal</div>
                        </div>
                        <div class="so-dot"></div>
                    </div>

                    <div class="status-option opt-isolir" onclick="pilihStatus(this,'isolir')">
                        <div class="so-icon"><i class="bi bi-slash-circle-fill"></i></div>
                        <div>
                            <div class="so-label" style="color:#d97706;">Isolir</div>
                            <div class="so-desc">Layanan diisolir sementara</div>
                        </div>
                        <div class="so-dot"></div>
                    </div>

                    <div class="status-option opt-nonaktif" onclick="pilihStatus(this,'nonaktif')">
                        <div class="so-icon"><i class="bi bi-x-circle-fill"></i></div>
                        <div>
                            <div class="so-label text-danger">Nonaktif</div>
                            <div class="so-desc">Layanan dinonaktifkan</div>
                        </div>
                        <div class="so-dot"></div>
                    </div>
                </div>

                <div class="modal-footer" style="padding:12px 20px; border-top:1px solid #f3f4f6;">
                    <button type="button" class="btn btn-secondary btn-sm px-4"
                            data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm px-4 fw-bold"
                            style="background:linear-gradient(135deg,#09973B,#0ab844);
                                   color:#fff; border:none;">
                        <i class="bi bi-check-lg me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════
     MODAL GENERATE TAGIHAN
══════════════════════════════════════ --}}
<div class="modal fade" id="modalGenerateTagihan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 350px;">
        <div class="modal-content" style="border-radius:14px; overflow:hidden; box-shadow:0 20px 60px rgba(0,0,0,.15);">

            <div class="modal-header"
                 style="background:linear-gradient(135deg,#09973B,#0ab844);
                        color:#fff; padding:16px 20px; border:none;">
                <div class="d-flex align-items-center gap-2">
                    <div style="width:36px; height:36px; border-radius:50%;
                                background:rgba(255,255,255,.2);
                                display:flex; align-items:center;
                                justify-content:center; font-size:17px;">
                        <i class="bi bi-plus-circle-fill"></i>
                    </div>
                    <div>
                        <div style="font-weight:700; font-size:15px; line-height:1.2;">Generate Tagihan</div>
                        <div style="font-size:11.5px; opacity:.85;">
                            <i class="bi bi-people-fill me-1"></i>
                            <span id="jumlahGenerateTerpilih">0</span> pelanggan dipilih
                        </div>
                    </div>
                </div>
                
            </div>

            <div class="modal-body" style="padding:20px 20px 14px; min-height: 250px;">

                {{-- Info box --}}
                <div style="background:#f0fdf4; border:1px solid #bbf7d0;
                            border-radius:10px; padding:14px 30px; margin-bottom:4px;">
                    <div style="display:flex; align-items:flex-start; gap:10px;">
                        <div style="width:50px; height:32px; border-radius:50%;
                                    background:#dcfce7; display:flex; align-items:center;
                                    justify-content:center; font-size:16px;
                                    color:#16a34a; flex-shrink:0; margin-top:1px;">
                            <i class="bi bi-info-circle-fill"></i>
                        </div>
                        <div>
                            <div style="font-size:12px; font-weight:600; color:#15803d; margin-bottom:3px;">
                                Konfirmasi Generate
                            </div>
                            <div style="font-size:12.5px; color:#374151; line-height:1.65;">
                                Tagihan akan dibuat otomatis untuk
                                <strong id="jumlahGenerateText">0</strong> pelanggan terpilih.
                                Pastikan data sudah benar sebelum melanjutkan.
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="modal-footer" style="padding:12px 20px; border-top:1px solid #f3f4f6; gap:8px;">
                <button type="button" class="btn btn-secondary btn-sm px-4"
                        data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-sm px-4 fw-bold"
                        style="background:linear-gradient(135deg,#09973B,#0ab844);
                               color:#fff; border:none;"
                        onclick="submitGenerate()">
                    <i class="bi bi-check-lg me-1"></i> Ya, Generate
                </button>
            </div>

        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Taruh sebelum fungsi lainnya
function jagoAlert(pesan, callback) {
    const existing = document.getElementById('jagoAlertModal');
    if (existing) existing.remove();

    const html = `
    <div id="jagoAlertModal" style="position:fixed;inset:0;background:rgba(0,0,0,.52);
         z-index:9999;display:flex;align-items:center;justify-content:center;">
      <div style="background:#fff;border-radius:16px;overflow:hidden;width:340px;
                  box-shadow:0 20px 60px rgba(0,0,0,.2);">
        <div style="background:linear-gradient(135deg,#09973B,#0ab844);
                    padding:20px;display:flex;flex-direction:column;
                    align-items:center;gap:10px;">
          <div style="width:52px;height:52px;border-radius:50%;
                      background:rgba(255,255,255,.18);display:flex;
                      align-items:center;justify-content:center;font-size:26px;color:#fff;">
            <i class="bi bi-exclamation-triangle-fill"></i>
          </div>
          <div style="font-size:15px;font-weight:600;color:#fff;">Perhatian</div>
        </div>
        <div style="padding:20px 22px 8px;text-align:center;">
          <p style="font-size:13.5px;color:#555;line-height:1.7;margin:0;">${pesan}</p>
          
        </div>
        <div style="padding:14px 20px 18px;display:flex;justify-content:center;">
          <button id="jagoAlertOk"
                  style="padding:9px 40px;border-radius:8px;border:none;
                         background:linear-gradient(135deg,#09973B,#0ab844);
                         color:#fff;font-size:13.5px;font-weight:600;cursor:pointer;">
            <i class="bi bi-check-lg me-1"></i> Mengerti
          </button>
        </div>
      </div>
    </div>`;

    document.body.insertAdjacentHTML('beforeend', html);
    document.getElementById('jagoAlertOk').onclick = () => {
        document.getElementById('jagoAlertModal').remove();
        if (callback) callback();
    };
}
/* ══════════════════════════════════════
   CHECKBOX HELPERS
══════════════════════════════════════ */
function toggleAll(master) {
    document.querySelectorAll('.row-check').forEach(cb => cb.checked = master.checked);
    updateSelected();
}

function updateSelected() {
    const total   = document.querySelectorAll('.row-check').length;
    const checked = document.querySelectorAll('.row-check:checked').length;
    const master  = document.getElementById('checkAll');
    master.checked       = (checked === total && total > 0);
    master.indeterminate = (checked > 0 && checked < total);
}

function getSelectedIds() {
    return [...document.querySelectorAll('.row-check:checked')].map(cb => cb.value);
}

/* ══════════════════════════════════════
   HAPUS DATA TERPILIH
══════════════════════════════════════ */
function hapusDataTerpilih() {
    const ids = getSelectedIds();
    if (ids.length === 0) {
       jagoAlert('Pilih minimal satu data terlebih dahulu.');
        return;
    }
    if (!confirm(`Yakin ingin menghapus ${ids.length} data terpilih?\nTindakan ini tidak dapat dibatalkan.`)) return;

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ url("/layanan/bulk-delete") }}';
    form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}">`;

    ids.forEach(id => {
        const input = document.createElement('input');
        input.type  = 'hidden';
        input.name  = 'ids[]';
        input.value = id;
        form.appendChild(input);
    });

    document.body.appendChild(form);
    form.submit();
}

/* ══════════════════════════════════════
   UBAH STATUS TERPILIH
══════════════════════════════════════ */
function pilihStatus(el, value) {
    document.querySelectorAll('.status-option').forEach(o => o.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('selectedStatusValue').value = value;
}

function ubahStatusTerpilih() {
    const ids = getSelectedIds();
    if (ids.length === 0) {
       jagoAlert('Pilih minimal satu data terlebih dahulu.');
        return;
    }

    document.getElementById('jumlahTerpilih').textContent = ids.length;

    // Reset ke Aktif
    document.querySelectorAll('.status-option').forEach(o => o.classList.remove('selected'));
    document.querySelector('.opt-aktif').classList.add('selected');
    document.getElementById('selectedStatusValue').value = 'aktif';

    // Inject hidden inputs
    const container = document.getElementById('hiddenIdsStatus');
    container.innerHTML = '';
    ids.forEach(id => {
        const input = document.createElement('input');
        input.type  = 'hidden';
        input.name  = 'ids[]';
        input.value = id;
        container.appendChild(input);
    });

    new bootstrap.Modal(document.getElementById('modalUbahStatus')).show();
}

/* ══════════════════════════════════════
   GENERATE TAGIHAN TERPILIH
══════════════════════════════════════ */
function generateTerpilih() {
    const ids = getSelectedIds();
    if (ids.length === 0) {
       jagoAlert('Pilih minimal satu data terlebih dahulu.');
        return;
    }

    document.getElementById('jumlahGenerateTerpilih').textContent = ids.length;
    document.getElementById('jumlahGenerateText').textContent = ids.length;

    new bootstrap.Modal(document.getElementById('modalGenerateTagihan')).show();
}

function submitGenerate() {
    const ids = getSelectedIds();

    const container = document.getElementById('hiddenIdsTerpilih');
    container.innerHTML = '';
    ids.forEach(id => {
        const input = document.createElement('input');
        input.type  = 'hidden';
        input.name  = 'ids[]';
        input.value = id;
        container.appendChild(input);
    });

    document.getElementById('formGenerateTerpilih').submit();
}
</script>

</body>
</html>