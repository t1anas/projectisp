<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approve Pelanggan - Jagonet</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('inputform.css') }}">
</head>
<body>

<div style="display:flex; min-height:100vh;">

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
        <a href="{{ url('/layanan') }}" class="menu-item">
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

        <a href="{{ url($instalasiUrl) }}" class="menu-item active">
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

    {{-- MAIN CONTENT --}}
    <div class="main-content" style="flex:1;">

        {{-- TOPBAR --}}
        <div class="topbar">
            <div class="d-flex align-items-center gap-3">
            <button type="button" class="btn-sidebar-toggle d-lg-none" onclick="toggleSidebar()">
                <i class="bi bi-list"></i>
            </button>
            <div>
                <div class="page-title">Approve Pelanggan</div>
                <div class="page-sub">Kelola persetujuan pelanggan baru</div>
            </div>
            </div>
            <div class="breadcrumb-area">
                <i class="bi bi-house-door"></i>
                <span class="sep">/</span>
                <span>Pelanggan</span>
                <span class="sep">/</span>
                <span class="current">Approve</span>
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
                    <i class="bi bi-shield-check"></i>
                </div>
                <div>
                    <div class="form-card-title">Approve Pelanggan</div>
                    <div class="form-card-sub">Daftar pelanggan menunggu persetujuan</div>
                </div>
            </div>

            {{-- FILTER --}}
            <div style="padding:20px; border-bottom:1px solid #eee;">
                <form method="GET" action="{{ url('/approve') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control"
                                   placeholder="Cari nama / no hp / kode / NIK..."
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="">Semua Status</option>
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
                            <button type="submit" class="btn btn-sm btn-search-jago" style="width:100%;">
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

                            <td style="white-space: nowrap">
                                <span class="layanan-badge">{{ $p->layanan->nama_paket ?? '—' }}</span>
                            </td>

                            <td style="white-space:nowrap;">
                                @if($p->status === 'pending')
                                <span class="status-pill status-pending">
                                    <i class="bi bi-hourglass-split"></i> Pending</span>
                                @else
                                <span class="status-pill status-active">
                                    <i class="bi bi-check-circle-fill"></i> Sudah Aktif</span>
                                    @endif
                            </td>

                            <td>
                                <div class="action-group">

                                    <button type="button" class="action-modern btn-detail" title="Detail"
                                        onclick='openDetail(@json($p))'>
                                        <i class="bi bi-eye-fill"></i>
                                    </button>

                                    @if($p->status === 'pending')
                                        <form method="POST" action="{{ url('/approve/' . $p->id . '/approve') }}" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="action-modern btn-approve" title="Approve">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>

                                        <button type="button" class="action-modern btn-reject" title="Nonaktifkan"
                                            onclick="openReject({{ $p->id }}, '{{ addslashes($p->nama) }}')">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    @endif
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


{{-- MODAL: Detail --}}
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

<div class="modal fade" id="modalReject" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header-custom">
                <span><i class="bi bi-x-circle-fill me-2" style="color:#dc3545;"></i>Nonaktifkan Pelanggan</span>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="rejectForm">
                @csrf
                <div class="modal-body" style="padding:20px 28px;">
                    <p style="font-size:13px; color:#374151; margin-bottom:14px;">
                        Anda akan <strong>menonaktifkan</strong> pelanggan
                        <strong id="rejectName" style="color:#dc3545;"></strong>.
                        Status akan diubah menjadi <code>nonaktif</code>.
                    </p>
                    <label class="form-label" style="font-size:13px; font-weight:700;">
                        Alasan <span style="color:#9ca3af; font-weight:400;">(opsional)</span>
                    </label>
                    <textarea name="alasan" class="form-control" rows="3"
                        placeholder="Contoh: Data tidak valid, KTP tidak jelas, dsb."></textarea>
                </div>
                <div class="modal-footer" style="border-top:1px solid #eee; padding:14px 28px; gap:8px;">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-nonaktif">
                        <i class="bi bi-x-lg"></i> Nonaktifkan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleSidebar() {
    document.getElementById('appSidebar').classList.toggle('show');
    document.getElementById('sidebarOverlay').classList.toggle('show');
}

    const rowHtml = (lbl, val) =>
        `<div class="d-row">
            <span class="d-lbl">${lbl}</span>
            <span class="d-val">${val ?? '—'}</span>
        </div>`;

    const fmt = v => v ? new Date(v).toLocaleString('id-ID') : '—';

    const statusPill = {
        pending:  '<span class="status-pill status-pending"><i class="bi bi-hourglass-split"></i> Pending</span>',
        aktif:    '<span class="status-pill status-active"><i class="bi bi-check-circle-fill"></i> Aktif</span>',
        nonaktif: '<span class="status-pill status-nonactive"><i class="bi bi-x-circle-fill"></i> Nonaktif</span>',
    };

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

            <div class="sec-div">Informasi Layanan</div>
            ${rowHtml('Site',   p.site?.nama_site)}
            ${rowHtml('Layanan', `<span class="layanan-badge">${p.layanan?.nama_paket ?? '—'}</span>`)}
            ${rowHtml('Harga',  p.layanan?.harga ? 'Rp ' + Number(p.layanan.harga).toLocaleString('id-ID') : '—')}
            ${rowHtml('Lokasi', lokasi)}

            <div class="sec-div">Status & Approval</div>
            ${rowHtml('Status',          statusPill[p.status] ?? '—')}
            ${rowHtml('Tanggal Approve', fmt(p.approved_admin_at))}
            ${rowHtml('Terdaftar',       fmt(p.created_at))}
        `;

        new bootstrap.Modal(document.getElementById('modalDetail')).show();
    }

    function openReject(id, nama) {
        document.getElementById('rejectForm').action      = `/approve/${id}/reject`;
        document.getElementById('rejectName').textContent = nama;
        new bootstrap.Modal(document.getElementById('modalReject')).show();
    }
</script>
</body>
</html>