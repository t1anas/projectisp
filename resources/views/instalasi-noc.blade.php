<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalasi NOC - Jagonet</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('inputform.css') }}">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f4f6f9; }

        /* Table */
        .table, .table * { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 13px; }
        .table th { font-weight: 600; letter-spacing: 0.3px; }
        .table td { font-weight: 500; vertical-align: middle; padding: 10px 12px; line-height: 1.5; }
        .table th:last-child, .table td:last-child { min-width: 120px; white-space: nowrap; }

        /* Clamp */
        .clamp-2 {
            display: -webkit-box; -webkit-line-clamp: 2;
            -webkit-box-orient: vertical; overflow: hidden; max-width: 160px;
        }
        .clamp-3 {
            display: -webkit-box; -webkit-line-clamp: 3;
            -webkit-box-orient: vertical; overflow: hidden;
            max-width: 160px; font-size: 12px; color: #6b7280; line-height: 1.4;
        }

        .date-main { font-size: 12px; font-weight: 600; color: #111827; }
        .date-rel   { font-size: 11px; color: #9ca3af; margin-top: 2px; }

        .status-pill {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 4px 10px; border-radius: 50px;
            font-size: 11px; font-weight: 700; border: 1px solid transparent;
        }
        .status-pill i { font-size: 8px; }
        .status-active    { background: #e8fff1; color: #0f9d58; border-color: #b7f3cd; }
        .status-nonactive { background: #fff1f1; color: #dc3545; border-color: #ffc4c4; }
        .status-pending   { background: #fffbeb; color: #b45309; border-color: #fcd34d; }
        .status-pengajuan-noc   { background: #eff6ff; color: #2563eb; border-color: #bfdbfe; }

        .layanan-badge {
            background: #e8fff1; color: #0f9d58; border-radius: 50px;
            padding: 4px 12px; font-size: 11px; font-weight: 700;
            border: 1px solid #b7f3cd; white-space: nowrap;
        }

        .action-group { display: flex; align-items: center; gap: 6px; justify-content: center; flex-wrap: nowrap; }
        .action-modern {
            width: 34px; height: 34px; border: none; border-radius: 10px;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 14px; transition: .2s; cursor: pointer; text-decoration: none;
            flex-shrink: 0;
        }
        .action-modern:hover { transform: translateY(-2px); }
        .btn-detail   { background: #eef4ff; color: #0d6efd; }
        .btn-detail:hover  { background: #0d6efd; color: #fff; }
        .btn-approve  { background: #e8fff1; color: #0f9d58; }
        .btn-approve:hover { background: #16a34a; color: #fff; }
        .btn-reject   { background: #fff1f1; color: #dc3545; }
        .btn-reject:hover  { background: #dc2626; color: #fff; }

        /* Modal */
        .modal-content {
            border-radius: 18px; border: none; overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,.15);
        }
        .modal-header-custom {
            background: #fff; color: #111; padding: 20px 28px 16px;
            font-size: 17px; font-weight: 800; border-bottom: 1px solid #eee;
            display: flex; align-items: center; justify-content: space-between;
        }
        .modal-header-custom .btn-close { opacity: .5; }

        /* Detail rows */
        .sec-div {
            font-size: 11px; font-weight: 800; letter-spacing: 1px;
            text-transform: uppercase; color: #0f9d58;
            padding: 14px 0 6px; border-bottom: 1px solid #e8fff1; margin-bottom: 8px;
        }
        .d-row {
            display: flex; align-items: flex-start; gap: 12px;
            padding: 6px 0; font-size: 13px; border-bottom: 1px solid #f9fafb;
        }
        .d-lbl { width: 150px; flex-shrink: 0; color: #6b7280; font-weight: 600; }
        .d-val  { color: #111827; word-break: break-word; }

        /* Info card (readonly area di modal approve) */
        .info-readonly {
            background: #f8fafb;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 16px 18px;
            margin-bottom: 20px;
        }
        .info-readonly-title {
            font-size: 11px; font-weight: 800; letter-spacing: 1px;
            text-transform: uppercase; color: #6b7280;
            margin-bottom: 10px;
        }
        .info-readonly .d-row {
            border-bottom: 1px solid #eef0f3;
        }
        .info-readonly .d-row:last-child { border-bottom: none; }

        /* Form label & input */
        .modal-body .form-label  { font-size: 13px; font-weight: 700; color: #374151; }
        .modal-body .form-control, .modal-body .form-select {
            font-size: 13px; border-radius: 10px;
            border: 1px solid #e5e7eb;
        }
        .modal-body textarea.form-control { resize: none; }
        .modal-body .form-text { font-size: 11px; color: #9ca3af; }

        .btn-approve-submit {
            background: #0f9d58; color: #fff; border: none;
            border-radius: 10px; font-weight: 700; font-size: 13px;
            padding: 8px 20px; cursor: pointer; transition: .2s;
            display: inline-flex; align-items: center; gap: 6px;
        }
        .btn-approve-submit:hover { background: #0b7a45; }

        .btn-nonaktif {
            background: #fff1f1; color: #dc3545; border: 1px solid #ffc4c4;
            border-radius: 10px; font-weight: 700; font-size: 13px;
            padding: 8px 20px; cursor: pointer; transition: .2s;
        }
        .btn-nonaktif:hover { background: #dc2626; color: #fff; }

        /* Required star */
        .req { color: #dc3545; }

        /* Divider in approve modal */
        .approve-divider {
            border: none; border-top: 1px dashed #d1d5db; margin: 16px 0;
        }
    </style>
</head>
<body>

<div style="display:flex; min-height:100vh;">

    {{-- SIDEBAR --}}
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="hamburger"><span></span><span></span><span></span></div>
            <span class="logo-text">JAGONET</span>
        </div>

        <div class="section-label">Main Board</div>

        <a href="{{ Auth::user()->dashboard_url }}" class="menu-item">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="{{ url('/layanan') }}" class="menu-item">
            <i class="bi bi-wifi"></i> Data Layanan
        </a>

        @php
            $instalasiUrl = match(Auth::user()->role) {
                'cs'    => '/instalasi',
                'admin' => '/approve',
                'noc'   => '/instalasi-noc',
                default => '/instalasi',
            };
        @endphp

        <a href="{{ url($instalasiUrl) }}" class="menu-item active">
            <i class="bi bi-router"></i> Instalasi Baru
        </a>

        @if(Auth::user()->role === 'admin')
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

    {{-- MAIN CONTENT --}}
    <div class="main-content" style="flex:1;">

        {{-- TOPBAR --}}
        <div class="topbar">
            <div>
                <div class="page-title">Instalasi NOC</div>
                <div class="page-sub">Kelola proses instalasi jaringan pelanggan baru</div>
            </div>
            <div class="breadcrumb-area">
                <i class="bi bi-house-door"></i>
                <span class="sep">/</span>
                <span>Instalasi</span>
                <span class="sep">/</span>
                <span class="current">NOC</span>
            </div>
        </div>

        {{-- ALERTS --}}
        @if(session('success'))
            <div class="alert d-flex align-items-center gap-2 mx-4 mt-3 mb-0 border-0 rounded-3"
                 style="font-size:13px; background:#e8fff1; color:#0f9d58; border:1px solid #b7f3cd !important;">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert d-flex align-items-center gap-2 mx-4 mt-3 mb-0 border-0 rounded-3"
                 style="font-size:13px; background:#fff1f1; color:#dc3545; border:1px solid #ffc4c4 !important;">
                <i class="bi bi-x-circle-fill"></i> {{ session('error') }}
            </div>
        @endif

        {{-- CARD --}}
        <div class="form-card">

            {{-- CARD HEADER --}}
            <div class="form-card-header">
                <div class="icon-wrap">
                    <i class="bi bi-router"></i>
                </div>
                <div>
                    <div class="form-card-title">Instalasi NOC</div>
                    <div class="form-card-sub">Daftar pelanggan yang perlu dikonfigurasi oleh NOC</div>
                </div>
            </div>

            {{-- FILTER --}}
            <div style="padding:20px; border-bottom:1px solid #eee;">
                <form method="GET" action="{{ url('/instalasi-noc') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control"
                                   placeholder="Cari nama / no hp / kode / NIK..."
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="pengajuan noc"   {{ request('status') === 'pengajuan noc'   ? 'selected' : '' }}>Pengajuan NOC</option>
                                <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>Pending</option>
                                <option value="aktif"    {{ request('status') === 'aktif'    ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="layanan_id" class="form-select">
                                <option value="">Semua Paket</option>
                                @foreach($layanan as $l)
                                    <option value="{{ $l->id }}" {{ request('layanan_id') == $l->id ? 'selected' : '' }}>
                                        {{ $l->nama_paket }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="dari" class="form-control" value="{{ request('dari') }}">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="sampai" class="form-control" value="{{ request('sampai') }}">
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- TABLE --}}
            <div class="table-responsive px-3 pb-4">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light text-center fw-bold">
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Registrasi</th>
                            <th style="min-width:180px;">Nama</th>
                            <th>Telepon</th>
                            <th>NIK</th>
                            <th>Alamat</th>
                            <th>Site</th>
                            <th>Layanan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @forelse($pelanggan as $i => $p)
                        <tr>
                            <td>{{ $pelanggan->firstItem() + $i }}</td>

                            <td>
                                <a href="{{ url('/pelanggan/' . $p->id) }}"
                                   style="color:#111827; font-weight:600; text-decoration:none;">
                                    {{ $p->kode_pelanggan ?? '—' }}
                                </a>
                            </td>

                            <td>
                                <div class="date-main">{{ \Carbon\Carbon::parse($p->created_at)->format('Y/m/d') }}</div>
                                <div class="date-rel">{{ \Carbon\Carbon::parse($p->created_at)->diffForHumans() }}</div>
                            </td>

                            <td class="text-start" style="min-width:180px;">
                                <div class="clamp-2">{{ $p->nama }}</div>
                            </td>

                            <td>
                                @if($p->no_hp)
                                    <a href="tel:{{ $p->no_hp }}"
                                       style="color:#111827; text-decoration:none; white-space:nowrap;">
                                        {{ $p->no_hp }}
                                    </a>
                                @else
                                    <span style="color:#d1d5db;">—</span>
                                @endif
                            </td>

                            <td style="font-family:monospace; color:#6b7280; font-size:12px;">
                                {{ $p->nik ?? '—' }}
                            </td>

                            <td class="text-start">
                                <div class="clamp-3">{{ $p->alamat ?? '—' }}</div>
                            </td>

                            <td>
                                <span style="font-size:12px; color:#374151; font-weight:600;">
                                    {{ $p->site->nama_site ?? '—' }}
                                </span>
                            </td>

                            <td>
                                <span class="layanan-badge">{{ $p->layanan->nama_paket ?? '—' }}</span>
                            </td>

                            <td>
                                @php $st = strtolower($p->status); @endphp
                                @if($st === 'pending')
                                    <span class="status-pill status-pending">
                                        <i class="bi bi-hourglass-split"></i> Pending
                                    </span>
                                @elseif($st === 'aktif')
                                    <span class="status-pill status-active">
                                        <i class="bi bi-check-circle-fill"></i> Aktif
                                    </span>
                                @elseif($st === 'nonaktif')
                                    <span class="status-pill status-nonactive">
                                        <i class="bi bi-x-circle-fill"></i> Nonaktif
                                    </span>
                                @else
                                    <span class="status-pill status-pengajuan-noc">
                                        <i class="bi bi-arrow-repeat"></i> Pengajuan NOC
                                    </span>
                                @endif
                            </td>

                            <td>
                                <div class="action-group">

                                    {{-- Detail --}}
                                    <button type="button" class="action-modern btn-detail" title="Detail"
                                        onclick='openDetail(@json($p))'>
                                        <i class="bi bi-eye-fill"></i>
                                    </button>

                                    @if($st === 'pengajuan noc')
                                        <button type="button" class="action-modern btn-approve" title="Approve & Konfigurasi"
                                            onclick='openApprove(@json($p))'>
                                            <i class="bi bi-check-lg"></i>
                                        </button>

                                        <button type="button" class="action-modern btn-reject" title="Tolak"
                                            onclick="openReject({{ $p->id }}, '{{ addslashes($p->nama) }}')">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    @endif

                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center py-5" style="color:#9ca3af; font-size:14px;">
                                <i class="bi bi-inbox" style="font-size:30px; display:block; margin-bottom:8px;"></i>
                                Tidak ada data yang ditemukan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- END TABLE --}}

            {{-- PAGINATION --}}
            @if($pelanggan->hasPages())
            <div style="padding:14px 20px; border-top:1px solid #eee;">
                {{ $pelanggan->links() }}
            </div>
            @endif

        </div>
        {{-- END CARD --}}

    </div>
    {{-- END MAIN CONTENT --}}

</div>


{{-- ============================================================ --}}
{{-- MODAL: Detail Pelanggan --}}
{{-- ============================================================ --}}
<div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header-custom">
                <span><i class="bi bi-person-lines-fill me-2" style="color:#0f9d58;"></i>Detail Pelanggan</span>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailBody" style="padding:20px 28px;"></div>
            <div class="modal-footer" style="border-top:1px solid #eee; padding:14px 28px;">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>


{{-- ============================================================ --}}
{{-- MODAL: Approve NOC (dengan input nama_layanan + catatan_noc) --}}
{{-- ============================================================ --}}
<div class="modal fade" id="modalApprove" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width:520px;">
        <div class="modal-content">
            <div class="modal-header-custom">
                <span>
                    <i class="bi bi-router me-2" style="color:#0f9d58;"></i>
                    Konfigurasi Instalasi NOC
                </span>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form method="POST" id="approveForm">
                @csrf
                <div class="modal-body" style="padding:20px 28px;">

                    {{-- Info readonly --}}
                    <div class="info-readonly">
                        <div class="info-readonly-title">
                            <i class="bi bi-info-circle me-1"></i> Data Pelanggan
                        </div>
                        <div class="d-row">
                            <span class="d-lbl">Nama</span>
                            <span class="d-val" id="apv-nama">—</span>
                        </div>
                        <div class="d-row">
                            <span class="d-lbl">Paket Layanan</span>
                            <span class="d-val" id="apv-paket">—</span>
                        </div>
                        <div class="d-row">
                            <span class="d-lbl">Alamat</span>
                            <span class="d-val" id="apv-alamat">—</span>
                        </div>
                        <div class="d-row">
                            <span class="d-lbl">Site</span>
                            <span class="d-val" id="apv-site">—</span>
                        </div>
                    </div>

                    <hr class="approve-divider">

                    <div class="mb-3">
                        <label class="form-label">
                            Nama Layanan <span class="req">*</span>
                        </label>
                        <input type="text" name="nama_layanan" id="apv-input-nama-layanan"
                               class="form-control" required
                               placeholder="Contoh: PLG-001 / pppoe-username">
                        <div class="form-text">Nama koneksi / username PPPoE yang akan dikonfigurasi di router.</div>
                    </div>

                    <div class="mb-1">
                        <label class="form-label">
                            Catatan NOC <span style="color:#9ca3af; font-weight:400;">(opsional)</span>
                        </label>
                        <textarea name="catatan_noc" class="form-control" rows="3"
                            placeholder="Contoh: ODP-JGN-001 port 3, kabel 50m, VLAN 10, dsb."></textarea>
                        <div class="form-text">Catatan teknis instalasi untuk kebutuhan dokumentasi NOC.</div>
                    </div>

                </div>

                <div class="modal-footer" style="border-top:1px solid #eee; padding:14px 28px; gap:8px;">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-approve-submit">
                        <i class="bi bi-check-lg"></i> Approve & Selesai
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- ============================================================ --}}
{{-- MODAL: Reject / Tolak --}}
{{-- ============================================================ --}}
<div class="modal fade" id="modalReject" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header-custom">
                <span><i class="bi bi-x-circle-fill me-2" style="color:#dc3545;"></i>Tolak Instalasi</span>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="rejectForm">
                @csrf
                <div class="modal-body" style="padding:20px 28px;">
                    <p style="font-size:13px; color:#374151; margin-bottom:14px;">
                        Anda akan <strong>menolak instalasi</strong> untuk pelanggan
                        <strong id="rejectName" style="color:#dc3545;"></strong>.
                        Status akan dikembalikan menjadi <code>nonaktif</code>.
                    </p>
                    <label class="form-label" style="font-size:13px; font-weight:700;">
                        Alasan <span style="color:#9ca3af; font-weight:400;">(opsional)</span>
                    </label>
                    <textarea name="alasan" class="form-control" rows="3"
                        placeholder="Contoh: Lokasi tidak terjangkau, perangkat tidak tersedia, dsb."></textarea>
                </div>
                <div class="modal-footer" style="border-top:1px solid #eee; padding:14px 28px; gap:8px;">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-nonaktif">
                        <i class="bi bi-x-lg"></i> Tolak Instalasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    /* ── Helpers ── */
    const rowHtml = (lbl, val) =>
        `<div class="d-row">
            <span class="d-lbl">${lbl}</span>
            <span class="d-val">${val ?? '—'}</span>
        </div>`;

    const fmt = v => v ? new Date(v).toLocaleString('id-ID') : '—';

    const statusPill = {
        proses:   '<span class="status-pill status-pengajuan-noc"><i class="bi bi-arrow-repeat"></i> Pengajuan NOC</span>',
        pending:  '<span class="status-pill status-pending"><i class="bi bi-hourglass-split"></i> Pending</span>',
        aktif:    '<span class="status-pill status-active"><i class="bi bi-check-circle-fill"></i> Aktif</span>',
        nonaktif: '<span class="status-pill status-nonactive"><i class="bi bi-x-circle-fill"></i> Nonaktif</span>',
    };

    /* ── Modal: Detail ── */
    function openDetail(p) {
        const lokasi = p.lokasi_link
            ? `<a href="${p.lokasi_link}" target="_blank" style="color:#0f9d58;">
                   Buka Google Maps <i class="bi bi-box-arrow-up-right"></i>
               </a>`
            : '—';

        document.getElementById('detailBody').innerHTML = `
            <div class="sec-div">Informasi Pribadi</div>
            ${rowHtml('Kode Pelanggan', `<span style="font-weight:700;">${p.kode_pelanggan ?? '—'}</span>`)}
            ${rowHtml('Nama Lengkap',   p.nama)}
            ${rowHtml('NIK',            `<span style="font-family:monospace;">${p.nik ?? '—'}</span>`)}
            ${rowHtml('No HP',          p.no_hp)}
            ${rowHtml('Alamat',         p.alamat)}

            <div class="sec-div">Informasi Jaringan & Layanan</div>
            ${rowHtml('Site',    p.site?.nama_site)}
            ${rowHtml('Layanan', `<span class="layanan-badge">${p.layanan?.nama_paket ?? '—'}</span>`)}
            ${rowHtml('Harga',   p.layanan?.harga ? 'Rp ' + Number(p.layanan.harga).toLocaleString('id-ID') : '—')}
            ${rowHtml('Nama Layanan (NOC)', p.nama_layanan ?? '<span style="color:#9ca3af;font-style:italic;">Belum dikonfigurasi</span>')}
            ${rowHtml('Catatan NOC', p.catatan_noc ?? '<span style="color:#9ca3af;font-style:italic;">—</span>')}
            ${rowHtml('Lokasi', lokasi)}

            <div class="sec-div">Status & Waktu</div>
            ${rowHtml('Status',           statusPill[p.status] ?? '—')}
            ${rowHtml('Approved Admin',   fmt(p.approved_at))}
            ${rowHtml('Approved NOC',     fmt(p.approved_noc_at))}
            ${rowHtml('Terdaftar',        fmt(p.created_at))}
        `;

        new bootstrap.Modal(document.getElementById('modalDetail')).show();
    }

    /* ── Modal: Approve NOC ── */
    function openApprove(p) {
        document.getElementById('apv-nama').textContent   = p.nama ?? '—';
        document.getElementById('apv-paket').innerHTML    =
            p.layanan?.nama_paket
                ? `<span class="layanan-badge">${p.layanan.nama_paket}</span>`
                : '—';
        document.getElementById('apv-alamat').textContent = p.alamat ?? '—';
        document.getElementById('apv-site').textContent   = p.site?.nama_site ?? '—';

        // Set action form → route approve NOC
        document.getElementById('approveForm').action = `/instalasi-noc/${p.id}/approve`;

        // Reset input
        document.getElementById('apv-input-nama-layanan').value = '';
        document.querySelector('#approveForm textarea[name="catatan_noc"]').value = '';

        new bootstrap.Modal(document.getElementById('modalApprove')).show();
    }

    /* ── Modal: Reject ── */
    function openReject(id, nama) {
        document.getElementById('rejectForm').action      = `/instalasi-noc/${id}/reject`;
        document.getElementById('rejectName').textContent = nama;
        new bootstrap.Modal(document.getElementById('modalReject')).show();
    }
</script>
</body>
</html>