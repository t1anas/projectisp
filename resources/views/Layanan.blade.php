<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Layanan - Jagonet</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('inputform.css') }}">
    <link rel="stylesheet" href="{{ asset('detail.css') }}">
</head>
<body>

<div class="page-wrap">

    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <div class="sidebar" id="appSidebar">
        <div class="sidebar-header">
            <div class="hamburger" onclick="toggleSidebar()"><span></span><span></span><span></span></div>
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
    {{-- END SIDEBAR --}}


    {{-- MAIN CONTENT--}}
    <div class="main-content">

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

        <div class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button type="button" class="btn-sidebar-toggle d-lg-none" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i>
                </button>
                <div>
                    <div class="page-title">Data Layanan</div>
                    <div class="page-sub">Kelola data layanan pelanggan internet</div>
                </div>
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
                </div>
            </div>

            {{-- FILTER --}}
            <div class="card-toolbar">
                <div class="d-flex align-items-center gap-2 flex-wrap">

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
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <button type="button"
                                        class="dropdown-item text-warning"
                                        onclick="ajukanIsolirTerpilih()">
                                    <i class="bi bi-slash-circle-fill me-2"></i> Ajukan Isolir
                                </button>
                            </li>
                            <li>
                                <button type="button"
                                        class="dropdown-item text-success"
                                        onclick="ajukanAktivasiTerpilih()">
                                    <i class="bi bi-check-circle-fill me-2"></i> Ajukan Aktivasi
                                </button>
                            </li>
                        </ul>
                    </div>

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

                    <form method="POST"
                          action="{{ route('pelanggan.generateTagihanTerpilih') }}"
                          id="formGenerateTerpilih"
                          style="margin:0;">
                        @csrf
                        <div id="hiddenIdsTerpilih"></div>
                        <button type="button"
                                onclick="generateTerpilih()"
                                class="btn btn-sm btn-generate-tagihan">
                            <i class="bi bi-plus-lg"></i> Generate Tagihan
                        </button>
                    </form>

                    <form method="GET" action="{{ url('/layanan') }}"
                          class="d-flex align-items-center gap-2 flex-wrap">

                        <input type="text" name="search"
                               class="form-control form-control-sm filter-search"
                               placeholder="Cari pelanggan..."
                               value="{{ request('search') }}">

                        <select name="status"
                                class="form-select form-select-sm filter-status">
                            <option value="">Semua Status</option>
                            <option value="aktif"              {{ request('status') === 'aktif'              ? 'selected' : '' }}>Aktif</option>
                            <option value="isolir"             {{ request('status') === 'isolir'             ? 'selected' : '' }}>Isolir</option>
                            <option value="nonaktif"           {{ request('status') === 'nonaktif'           ? 'selected' : '' }}>Nonaktif</option>
                            <option value="pengajuan isolir"   {{ request('status') === 'pengajuan isolir'   ? 'selected' : '' }}>Pengajuan Isolir</option>
                            <option value="pengajuan aktivasi" {{ request('status') === 'pengajuan aktivasi' ? 'selected' : '' }}>Pengajuan Aktivasi</option>
                        </select>

                        <input type="date" name="dari"
                               class="form-control form-control-sm filter-date"
                               value="{{ request('dari') }}">

                        <input type="date" name="sampai"
                               class="form-control form-control-sm filter-date"
                               value="{{ request('sampai') }}">

                        <button type="submit" class="btn btn-sm btn-search-jago">
                            <i class="bi bi-search"></i>
                        </button>

                        @if(request()->hasAny(['search', 'status', 'dari', 'sampai']))
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
                                        <td class="tagihan-col">
                                            Rp {{ number_format($p->layanan->harga ?? 0, 0, ',', '.') }}
                                        </td>
                                        <td class="tagihan-col">
                                            {{ $p->layanan->nama_paket ?? '-' }}
                                        </td>
                                        <td style="white-space: nowrap;">
                                            @php
                                                $statusMap = [
                                                    'aktif'              => ['class' => 'status-active',   'icon' => 'check-circle-fill', 'label' => 'Aktif'],
                                                    'isolir'             => ['class' => 'status-isolir',   'icon' => 'slash-circle-fill', 'label' => 'Isolir'],
                                                    'pending'            => ['class' => 'status-pending',  'icon' => 'hourglass-split',   'label' => 'Pending'],
                                                    'pengajuan isolir'   => ['class' => 'status-pending',  'icon' => 'hourglass-split',   'label' => 'Pengajuan Isolir'],
                                                    'pengajuan aktivasi' => ['class' => 'status-pending',  'icon' => 'hourglass-split',   'label' => 'Pengajuan Aktivasi'],
                                                ];
                                                $s = $statusMap[strtolower($p->status)] ?? ['class' => 'status-nonactive', 'icon' => 'x-circle-fill', 'label' => 'Nonaktif'];
                                            @endphp
                                            <span class="status-pill {{ $s['class'] }}">
                                                <i class="bi bi-{{ $s['icon'] }}"></i> {{ $s['label'] }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="action-group">
                                                @if(Auth::user()->role == 'admin')
                                                    <a href="{{ route('layanan.detail', $p->id) }}"
                                                       class="btn-action btn-action-detail"
                                                       data-tip="Detail">
                                                        <i class="bi bi-eye-fill"></i>
                                                    </a>
                                                @elseif(in_array(Auth::user()->role, ['cs', 'noc']))
                                                    <button type="button"
                                                            class="btn-action btn-action-detail"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalDetail{{ $p->id }}"
                                                            data-tip="Detail">
                                                        <i class="bi bi-eye-fill"></i>
                                                    </button>
                                                @endif
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
                                        <td colspan="10" class="text-center py-5" style="color:#9ca3af; font-size:14px;">
                                            <i class="bi bi-inbox" style="font-size:30px; display:block; margin-bottom:8px;"></i>
                                            Tidak ada data yang ditemukan
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

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

@foreach($pelanggan as $p)
    @if(in_array(Auth::user()->role, ['cs', 'noc']))
        <div class="modal fade modal-detail" id="modalDetail{{ $p->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" style="max-width:560px;">
                <div class="modal-content">

                    <div class="modal-detail-header">
                        <div class="modal-detail-header-inner">
                            <div class="modal-detail-header-icon">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <div>
                                <div class="modal-detail-header-title">Detail Layanan</div>
                            </div>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-detail-avatar-row">
                        <div class="modal-detail-avatar">
                            {{ strtoupper(substr($p->nama, 0, 2)) }}
                        </div>
                        <div>
                            <div class="modal-detail-nama">{{ $p->nama }}</div>
                            <div class="modal-detail-sub">
                                <i class="bi bi-upc-scan" style="font-size:12px;"></i>
                                {{ $p->kode_pelanggan }}
                                &nbsp;·&nbsp;
                                @php
                                    $detailStatusMap = [
                                        'aktif'              => ['class' => 'modal-detail-status-aktif',        'icon' => 'check-circle-fill',  'label' => 'Aktif'],
                                        'isolir'             => ['class' => 'modal-detail-status-isolir',       'icon' => 'slash-circle-fill',  'label' => 'Isolir'],
                                        'pengajuan isolir'   => ['class' => 'modal-detail-status-peng-isolir',  'icon' => 'hourglass-split',    'label' => 'Pengajuan Isolir'],
                                        'pengajuan aktivasi' => ['class' => 'modal-detail-status-peng-aktivasi','icon' => 'hourglass-split',    'label' => 'Pengajuan Aktivasi'],
                                    ];
                                    $ds = $detailStatusMap[strtolower($p->status)] ?? ['class' => 'modal-detail-status-nonaktif', 'icon' => 'x-circle-fill', 'label' => 'Nonaktif'];
                                @endphp
                                <span class="modal-detail-status-pill {{ $ds['class'] }}">
                                    <i class="bi bi-{{ $ds['icon'] }}" style="font-size:9px;"></i> {{ $ds['label'] }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <hr class="modal-detail-divider">

                    <div class="modal-detail-section-label">Informasi kontak</div>
                    <div class="modal-detail-grid">
                        <div class="modal-detail-cell">
                            <div class="modal-detail-field-label">
                                <i class="bi bi-telephone"></i> No. HP
                            </div>
                            <div class="modal-detail-field-value">{{ $p->no_hp ?? '-' }}</div>
                        </div>
                        <div class="modal-detail-cell-right">
                            <div class="modal-detail-field-label">
                                <i class="bi bi-calendar3"></i> Aktivasi
                            </div>
                            <div class="modal-detail-field-value">{{ $p->created_at->format('d/m/Y') }}</div>
                        </div>
                        <div class="modal-detail-cell-full">
                            <div class="modal-detail-field-label">
                                <i class="bi bi-geo-alt"></i> Alamat
                            </div>
                            <div class="modal-detail-field-value">{{ $p->alamat ?? '-' }}</div>
                        </div>
                    </div>

                    <hr class="modal-detail-divider">

                    <div class="modal-detail-section-label">Informasi layanan</div>
                    <div class="modal-detail-grid">
                        <div class="modal-detail-cell">
                            <div class="modal-detail-field-label">
                                <i class="bi bi-wifi"></i> Nama Layanan
                            </div>
                            <div class="modal-detail-field-value">{{ $p->nama_layanan ?? '-' }}</div>
                        </div>
                        <div class="modal-detail-cell-right">
                            <div class="modal-detail-field-label">
                                <i class="bi bi-box"></i> Paket
                            </div>
                            <div class="modal-detail-field-value">{{ $p->layanan->nama_paket ?? '-' }}</div>
                        </div>
                        <div class="modal-detail-cell-last-left">
                            <div class="modal-detail-field-label">
                                <i class="bi bi-receipt"></i> Tagihan / Bulan
                            </div>
                            <div class="modal-detail-field-value-green">
                                Rp {{ number_format($p->layanan->harga ?? 0, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="modal-detail-cell-last-right">
                            <div class="modal-detail-field-label">
                                <i class="bi bi-building"></i> Site
                            </div>
                            <div class="modal-detail-field-value">{{ $p->site->nama_site ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="modal-detail-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>

                </div>
            </div>
        </div>
    @endif
@endforeach

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
                                    <option value="aktif"              {{ strtolower($p->status) == 'aktif'              ? 'selected' : '' }}>Aktif</option>
                                    <option value="isolir"             {{ strtolower($p->status) == 'isolir'             ? 'selected' : '' }}>Isolir</option>
                                    <option value="nonaktif"           {{ strtolower($p->status) == 'nonaktif'           ? 'selected' : '' }}>Nonaktif</option>
                                    <option value="pengajuan isolir"   {{ strtolower($p->status) == 'pengajuan isolir'   ? 'selected' : '' }}>Pengajuan Isolir</option>
                                    <option value="pengajuan aktivasi" {{ strtolower($p->status) == 'pengajuan aktivasi' ? 'selected' : '' }}>Pengajuan Aktivasi</option>
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

@foreach($pelanggan as $p)
    @php $periodeTagihan = \Carbon\Carbon::parse($p->created_at)->subDay(); @endphp

    <div class="modal fade modal-notif" id="modalNotif{{ $p->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header"
                     style="background:linear-gradient(135deg,#28a745,#20c157); color:#fff;">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-bell-fill me-2"></i> Notifikasi
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="border rounded p-3 mb-3">
                        <div class="notif-section-label">
                            Tagihan Internet Bulanan
                        </div>
                        <div class="notif-section-text">
                            Nama Pelanggan : {{ $p->nama }}<br>
                            Periode Tagihan : {{ $periodeTagihan->translatedFormat('F Y') }}
                        </div>
                    </div>

                    <div class="border rounded p-3 mb-3">
                        <div class="notif-section-label">
                            Isolir Layanan
                        </div>
                        <div class="notif-section-text">
                            Yth. {{ $p->nama }},<br>
                            Kami informasikan bahwa layanan internet Anda saat ini
                            <strong>diisolir</strong> dikarenakan tagihan
                            {{ $periodeTagihan->translatedFormat('F Y') }}
                            telah melebihi batas jatuh tempo pembayaran.
                        </div>
                    </div>

                    <div class="notif-section-label">
                        Preview Pesan
                    </div>
                    <div class="border rounded p-3 mb-3 notif-preview-box">
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

<div class="modal fade" id="modalUbahStatus" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <form id="formUbahStatus" method="POST" action="{{ url('/layanan/bulk-status') }}">
                @csrf
                <input type="hidden" name="status" id="selectedStatusValue" value="aktif">
                <div id="hiddenIdsStatus"></div>

                <div class="form-card-header" style="border-radius:0;">
                    <div>
                        <div class="form-card-title">Ubah Status Terpilih</div>
                        <div class="form-card-sub">
                            <i class="bi bi-people-fill me-1"></i>
                            <span id="jumlahTerpilih">0</span> data dipilih
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <p class="isolir-confirm-text">
                        Pilih status baru yang akan diterapkan pada data terpilih:
                    </p>

                    @foreach([
                        ['value' => 'aktif',    'class' => 'opt-aktif',    'icon' => 'check-circle-fill', 'label' => 'Aktif',    'label-class' => 'text-success', 'desc' => 'Layanan berjalan normal',    'selected' => true],
                        ['value' => 'isolir',   'class' => 'opt-isolir',   'icon' => 'slash-circle-fill', 'label' => 'Isolir',   'label-class' => 'text-warning', 'desc' => 'Layanan diisolir sementara', 'selected' => false],
                        ['value' => 'nonaktif', 'class' => 'opt-nonaktif', 'icon' => 'x-circle-fill',     'label' => 'Nonaktif', 'label-class' => 'text-danger',  'desc' => 'Layanan dinonaktifkan',      'selected' => false],
                    ] as $opt)
                    <div class="status-option {{ $opt['class'] }} {{ $opt['selected'] ? 'selected' : '' }}"
                         onclick="pilihStatus(this,'{{ $opt['value'] }}')">
                        <div class="so-icon"><i class="bi bi-{{ $opt['icon'] }}"></i></div>
                        <div>
                            <div class="so-label {{ $opt['label-class'] }}">{{ $opt['label'] }}</div>
                            <div class="so-desc">{{ $opt['desc'] }}</div>
                        </div>
                        <div class="so-dot"></div>
                    </div>
                    @endforeach
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-save">
                        <i class="bi bi-check-lg"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade modal-generate" id="modalGenerateTagihan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="form-card-header" style="border-radius:0;">
                <div class="icon-wrap">
                    <i class="bi bi-plus-circle-fill"></i>
                </div>
                <div>
                    <div class="form-card-title">Generate Tagihan</div>
                    <div class="form-card-sub">
                        <i class="bi bi-people-fill me-1"></i>
                        <span id="jumlahGenerateTerpilih">0</span> pelanggan dipilih
                    </div>
                </div>
            </div>

            <div class="modal-body p-4 text-center">
                <div class="modal-generate-title">
                    Buat tagihan untuk <span id="jumlahGenerateText">0</span> pelanggan?
                </div>
                <div class="modal-generate-desc">
                    Tagihan akan dibuat otomatis untuk semua pelanggan terpilih.
                    Pastikan data sudah benar sebelum melanjutkan.
                </div>
            </div>

            <div class="modal-footer border-top px-4 py-3 gap-2">
                <button type="button" class="btn-cancel" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-1"></i> Batal
                </button>
                <button type="button" class="btn-save" onclick="submitGenerate()">
                    <i class="bi bi-check-lg"></i> Ya, Generate
                </button>
            </div>

        </div>
    </div>
</div>

<div class="modal fade modal-isolir" id="modalIsolirTerpilih" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content">
            <form id="formIsolirTerpilih" method="POST" action="{{ url('/layanan/bulk-isolir') }}">
                @csrf
                <div id="hiddenIdsIsolir"></div>

                <div class="isolir-head">
                    <div class="isolir-head-icon">
                        <i class="bi bi-slash-circle-fill"></i>
                    </div>
                    <div class="isolir-head-text">
                        <div class="title">Ajukan Isolir</div>
                        <div class="sub">
                            <i class="bi bi-people-fill me-1"></i>
                            <span id="jumlahIsolirTerpilih">0</span> pelanggan dipilih
                        </div>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
                </div>

                <div class="isolir-body">
                    <p class="isolir-confirm-text">
                        Anda yakin ingin mengajukan isolir untuk pelanggan berikut?
                    </p>
                    <ul id="listNamaIsolir" class="isolir-list-nama"></ul>
                </div>

                <div class="isolir-footer">
                    <button type="button" class="btn-isolir-batal" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i> Batal
                    </button>
                    <button type="submit" class="btn-isolir-konfirm">
                        <i class="bi bi-slash-circle-fill"></i> Ya, Ajukan Isolir
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade modal-isolir" id="modalAktivasiTerpilih" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content">
            <form id="formAktivasiTerpilih" method="POST" action="{{ url('/layanan/bulk-aktivasi') }}">
                @csrf
                <div id="hiddenIdsAktivasi"></div>

                <div class="isolir-head aktivasi-head">
                    <div class="isolir-head-icon aktivasi-head-icon">
                        <i class="bi bi-check-circle-fill aktivasi-head-icon"></i>
                    </div>
                    <div class="isolir-head-text">
                        <div class="title">Ajukan Aktivasi</div>
                        <div class="sub">
                            <i class="bi bi-people-fill me-1"></i>
                            <span id="jumlahAktivasiTerpilih">0</span> pelanggan dipilih
                        </div>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
                </div>

                <div class="isolir-body">
                    <p class="isolir-confirm-text">
                        Anda yakin ingin mengajukan aktivasi untuk pelanggan berikut?
                    </p>
                    <ul id="listNamaAktivasi" class="isolir-list-nama"></ul>
                </div>

                <div class="isolir-footer">
                    <button type="button" class="btn-isolir-batal" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i> Batal
                    </button>
                    <button type="submit" class="btn-isolir-konfirm btn-aktivasi-konfirm">
                        <i class="bi bi-check-circle-fill"></i> Ya, Ajukan Aktivasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function jagoAlert(pesan, callback) {
    document.getElementById('jagoAlertModal')?.remove();
    document.body.insertAdjacentHTML('beforeend', `
        <div id="jagoAlertModal">
            <div class="jago-alert-box">
                <div class="jago-alert-head">
                    <div class="jago-alert-head-icon"><i class="bi bi-exclamation-triangle-fill"></i></div>
                    <div class="jago-alert-head-title">Perhatian</div>
                </div>
                <div class="jago-alert-body"><p>${pesan}</p></div>
                <div class="jago-alert-footer">
                    <button id="jagoAlertOk"><i class="bi bi-check-lg me-1"></i> Mengerti</button>
                </div>
            </div>
        </div>
    `);
    document.getElementById('jagoAlertOk').onclick = () => {
        document.getElementById('jagoAlertModal').remove();
        callback?.();
    };
}

function toggleSidebar() {
    document.getElementById('appSidebar').classList.toggle('show');
    document.getElementById('sidebarOverlay').classList.toggle('show');
}

const SK  = 'layanan_selected_ids';
const SKN = 'layanan_selected_names';

const getIds   = () => JSON.parse(sessionStorage.getItem(SK)  || '[]');
const getNames = () => JSON.parse(sessionStorage.getItem(SKN) || '{}');

const saveStorage = (ids, names) => {
    sessionStorage.setItem(SK,  JSON.stringify(ids));
    sessionStorage.setItem(SKN, JSON.stringify(names));
};

const getNama   = row => row?.querySelector('td:nth-child(5) .clamp-2')?.textContent.trim() ?? '-';
const makeInput = (name, value) => Object.assign(document.createElement('input'), { type: 'hidden', name, value });

const injectIds = (containerId, ids) => {
    const container = document.getElementById(containerId);
    container.innerHTML = '';
    ids.forEach(id => container.appendChild(makeInput('ids[]', id)));
};

const fillList = (ulId, ids, names) => {
    const ul = document.getElementById(ulId);
    ul.innerHTML = '';
    ids.forEach(id => Object.assign(ul.appendChild(document.createElement('li')), {
        textContent: names[id] ?? `ID #${id}`
    }));
};

document.addEventListener('DOMContentLoaded', () => {
    const storedIds = getIds();

    document.querySelectorAll('.row-check').forEach(cb => {
        if (storedIds.includes(cb.value)) cb.checked = true;

        cb.addEventListener('change', function () {
            let ids = getIds(), names = getNames();
            if (this.checked) {
                if (!ids.includes(this.value)) {
                    ids.push(this.value);
                    names[this.value] = getNama(this.closest('tr'));
                }
            } else {
                ids = ids.filter(id => id !== this.value);
                delete names[this.value];
            }
            saveStorage(ids, names);
            updateSelected();
        });
    });

    updateSelected();

    ['formIsolirTerpilih', 'formAktivasiTerpilih', 'formUbahStatus', 'formGenerateTerpilih'].forEach(formId => {
        document.getElementById(formId)?.addEventListener('submit', () => {
            sessionStorage.removeItem(SK);
            sessionStorage.removeItem(SKN);
        });
    });
});

function toggleAll(master) {
    let ids = getIds(), names = getNames();
    document.querySelectorAll('.row-check').forEach(cb => {
        cb.checked = master.checked;
        if (master.checked) {
            if (!ids.includes(cb.value)) {
                ids.push(cb.value);
                names[cb.value] = getNama(cb.closest('tr'));
            }
        } else {
            ids = ids.filter(id => id !== cb.value);
            delete names[cb.value];
        }
    });
    saveStorage(ids, names);
    updateSelected();
}

function updateSelected() {
    const ids     = getIds();
    const pageIds = [...document.querySelectorAll('.row-check')].map(cb => cb.value);
    const count   = pageIds.filter(id => ids.includes(id)).length;
    const master  = document.getElementById('checkAll');

    master.checked       = count === pageIds.length && pageIds.length > 0;
    master.indeterminate = count > 0 && count < pageIds.length;
}

function getSelectedIds() { return getIds(); }

function ajukanIsolirTerpilih() {
    const ids = getIds(), names = getNames();
    if (!ids.length) { jagoAlert('Pilih minimal satu data terlebih dahulu.'); return; }
    document.getElementById('jumlahIsolirTerpilih').textContent = ids.length;
    fillList('listNamaIsolir', ids, names);
    injectIds('hiddenIdsIsolir', ids);
    new bootstrap.Modal(document.getElementById('modalIsolirTerpilih')).show();
}

function ajukanAktivasiTerpilih() {
    const ids = getIds(), names = getNames();
    if (!ids.length) { jagoAlert('Pilih minimal satu data terlebih dahulu.'); return; }
    document.getElementById('jumlahAktivasiTerpilih').textContent = ids.length;
    fillList('listNamaAktivasi', ids, names);
    injectIds('hiddenIdsAktivasi', ids);
    new bootstrap.Modal(document.getElementById('modalAktivasiTerpilih')).show();
}

function hapusDataTerpilih() {
    const ids = getIds();
    if (!ids.length) { jagoAlert('Pilih minimal satu data terlebih dahulu.'); return; }
    if (!confirm(`Yakin ingin menghapus ${ids.length} data terpilih?\nTindakan ini tidak dapat dibatalkan.`)) return;

    const form = Object.assign(document.createElement('form'), {
        method: 'POST',
        action: '{{ url("/layanan/bulk-delete") }}'
    });
    form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}">`;
    ids.forEach(id => form.appendChild(makeInput('ids[]', id)));
    document.body.appendChild(form);
    form.submit();
}

function pilihStatus(el, value) {
    document.querySelectorAll('.status-option').forEach(o => o.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('selectedStatusValue').value = value;
}

function ubahStatusTerpilih() {
    const ids = getIds();
    if (!ids.length) { jagoAlert('Pilih minimal satu data terlebih dahulu.'); return; }
    document.getElementById('jumlahTerpilih').textContent = ids.length;
    document.querySelectorAll('.status-option').forEach(o => o.classList.remove('selected'));
    document.querySelector('.opt-aktif').classList.add('selected');
    document.getElementById('selectedStatusValue').value = 'aktif';
    injectIds('hiddenIdsStatus', ids);
    new bootstrap.Modal(document.getElementById('modalUbahStatus')).show();
}

function generateTerpilih() {
    const ids = getIds();
    if (!ids.length) { jagoAlert('Pilih minimal satu data terlebih dahulu.'); return; }
    document.getElementById('jumlahGenerateTerpilih').textContent = ids.length;
    document.getElementById('jumlahGenerateText').textContent = ids.length;
    new bootstrap.Modal(document.getElementById('modalGenerateTagihan')).show();
}

function submitGenerate() {
    injectIds('hiddenIdsTerpilih', getIds());
    document.getElementById('formGenerateTerpilih').submit();
}
</script>
</body>
</html>