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

    <style>
        .topbar {
            padding: 16px 24px;
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .page-title { font-size: 17px; font-weight: 700; color: #111827; }
        .breadcrumb-area { font-size: 12px; color: #9ca3af; display: flex; align-items: center; gap: 5px; }
        .breadcrumb-area .current { color: #3b5bdb; font-weight: 600; }

        .filter-bar {
            padding: 16px 24px 0;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        .filter-bar .form-select,
        .filter-bar .form-control {
            font-size: 13px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            height: 36px;
            box-shadow: none;
            color: #374151;
        }
        .filter-bar .form-select:focus,
        .filter-bar .form-control:focus {
            border-color: #3b5bdb;
            box-shadow: 0 0 0 2px rgba(59,91,219,.12);
        }
        .btn-search {
            height: 36px;
            padding: 0 16px;
            background: #3b5bdb;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .btn-search:hover { background: #2f4ac7; }
        .btn-reset {
            height: 36px;
            padding: 0 14px;
            background: #6b7280;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .table-card {
            margin: 16px 24px 24px;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
        }
        .table-card-header {
            padding: 14px 18px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .tc-title {
            font-size: 14px;
            font-weight: 700;
            color: #111827;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .tc-title i { color: #3b5bdb; }
        .count-badge {
            background: #eff2ff;
            color: #3b5bdb;
            font-size: 11px;
            font-weight: 700;
            padding: 2px 10px;
            border-radius: 20px;
        }

        .selected-bar {
            display: none;
            align-items: center;
            gap: 8px;
            padding: 8px 18px;
            background: #eff2ff;
            border-bottom: 1px solid #c5cff7;
            font-size: 13px;
            color: #3b5bdb;
            font-weight: 600;
        }
        .selected-bar.show { display: flex; }
        .btn-bulk {
            border: none;
            border-radius: 6px;
            padding: 4px 12px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .btn-ba { background: #3b5bdb; color: #fff; }
        .btn-br { background: #fee2e2; color: #b91c1c; }

        .table-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 18px;
            border-bottom: 1px solid #f3f4f6;
            flex-wrap: wrap;
            gap: 8px;
        }
        .search-wrap {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: #6b7280;
        }
        .search-wrap input {
            width: 190px;
            font-size: 13px;
            border: 1px solid #d1d5db;
            border-radius: 5px;
            padding: 4px 9px;
        }
        .search-wrap input:focus {
            outline: none;
            border-color: #3b5bdb;
        }

        .ap-table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .ap-table thead th {
            background: #f9fafb;
            padding: 9px 12px;
            color: #6b7280;
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .4px;
            border-bottom: 1px solid #e5e7eb;
            white-space: nowrap;
        }
        .si { display: inline-flex; flex-direction: column; margin-left: 3px; vertical-align: middle; line-height: 1; }
        .si i { font-size: 8px; color: #d1d5db; }
        .ap-table tbody tr { border-bottom: 1px solid #f3f4f6; }
        .ap-table tbody tr:hover { background: #fafafa; }
        .ap-table tbody td { padding: 10px 12px; color: #374151; vertical-align: middle; }

        .paket-badge {
            display: inline-block;
            background: #eff2ff;
            color: #3b5bdb;
            font-size: 11px;
            font-weight: 700;
            padding: 2px 9px;
            border-radius: 20px;
        }
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 11px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
            white-space: nowrap;
        }
        .s-pending  { background: #fef9c3; color: #854d0e; }
        .s-aktif    { background: #dcfce7; color: #166534; }
        .s-nonaktif { background: #fee2e2; color: #991b1b; }

        .date-main { font-weight: 600; color: #111827; }
        .date-rel  { font-size: 11px; color: #9ca3af; margin-top: 1px; }

        .btn-act {
            width: 30px; height: 30px;
            border-radius: 6px; border: none;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 13px; cursor: pointer; transition: opacity .15s;
        }
        .btn-act:hover { opacity: .75; }
        .btn-view    { background: #e0f2fe; color: #0369a1; }
        .btn-approve { background: #dcfce7; color: #166534; }
        .btn-reject  { background: #fee2e2; color: #991b1b; }

        .table-footer {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 12px 18px;
            flex-wrap: wrap;
            gap: 8px;
        }
        .pag { display: flex; gap: 3px; }
        .page-btn {
            min-width: 30px; height: 30px; padding: 0 7px;
            border-radius: 6px; border: 1px solid #d1d5db;
            background: #fff; font-size: 12px; font-weight: 600;
            color: #374151; display: flex; align-items: center;
            justify-content: center; cursor: pointer; text-decoration: none;
            transition: background .12s, border-color .12s;
        }
        .page-btn:hover { background: #eff2ff; border-color: #3b5bdb; color: #3b5bdb; }
        .page-btn.active { background: #3b5bdb; border-color: #3b5bdb; color: #fff; }
        .page-btn.disabled { opacity: .4; cursor: default; pointer-events: none; }

        .modal-content  { border-radius: 10px; border: 1px solid #e5e7eb; }
        .modal-header   { border-bottom: 1px solid #e5e7eb; padding: 14px 18px; }
        .modal-title    { font-size: 15px; font-weight: 700; }
        .modal-body     { padding: 18px; }
        .modal-footer   { border-top: 1px solid #e5e7eb; padding: 12px 18px; }
        .d-row          { display: flex; gap: 8px; margin-bottom: 9px; font-size: 13px; }
        .d-lbl          { width: 140px; color: #6b7280; font-weight: 600; flex-shrink: 0; }
        .d-val          { color: #111827; font-weight: 500; }
        .sec-div {
            font-size: 10px; font-weight: 700; text-transform: uppercase;
            letter-spacing: .6px; color: #3b5bdb;
            margin: 14px 0 8px;
            display: flex; align-items: center; gap: 8px;
        }
        .sec-div::after { content: ''; flex: 1; height: 1px; background: #e5e7eb; }
        .form-check-input { width: 15px; height: 15px; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>

<div style="display:flex; min-height:100vh;">

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
                    <i class="bi bi-person-fill" style="color:white; font-size:17px;"></i>
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

    <!-- ─── MAIN ─── -->
    <div class="main-content" style="flex:1; background:#f3f4f6;">

        <!-- TOPBAR -->
        <div class="topbar">
            <div>
                <div class="page-title">Approve Pelanggan</div>
            </div>
            <div class="breadcrumb-area">
                <i class="bi bi-house-door"></i>
                <span>/</span><span>Pelanggan</span>
                <span>/</span><span class="current">Approve Pelanggan</span>
            </div>
        </div>

        <!-- ALERTS -->
        @if(session('success'))
        <div class="alert d-flex align-items-center gap-2 mx-4 mt-3 mb-0 border-0 rounded-2"
             style="font-size:13px; background:#dcfce7; color:#166534;">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="alert d-flex align-items-center gap-2 mx-4 mt-3 mb-0 border-0 rounded-2"
             style="font-size:13px; background:#fee2e2; color:#991b1b;">
            <i class="bi bi-x-circle-fill"></i> {{ session('error') }}
        </div>
        @endif

        <!-- FILTER BAR -->
        <div class="filter-bar">
            <form method="GET" action="{{ url('/approve-pelanggan') }}"
                  class="d-flex align-items-center gap-2 flex-wrap w-100">
                <select name="status" class="form-select" style="width:160px;">
                    <option value="">Semua Status</option>
                    <option value="pending"  {{ request('status')=='pending'  ?'selected':'' }}>Pending</option>
                    <option value="aktif"    {{ request('status')=='aktif'    ?'selected':'' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status')=='nonaktif' ?'selected':'' }}>Nonaktif</option>
                </select>
                <select name="layanan_id" class="form-select" style="width:200px;">
                    <option value="">Semua Paket</option>
                    @foreach($layanan as $l)
                        <option value="{{ $l->id }}" {{ request('layanan_id')==$l->id?'selected':'' }}>
                            {{ $l->nama_paket }}
                        </option>
                    @endforeach
                </select>
                <input type="date" name="dari"   class="form-control" style="width:140px;" value="{{ request('dari') }}">
                <input type="date" name="sampai" class="form-control" style="width:140px;" value="{{ request('sampai') }}">
                <button type="submit" class="btn-search"><i class="bi bi-search"></i> Cari</button>
                <a href="{{ url('/approve-pelanggan') }}" class="btn-reset">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </a>
            </form>
        </div>

        <!-- TABLE CARD -->
        <div class="table-card">
            <div class="table-card-header">
                <div class="tc-title">
                    <i class="bi bi-shield-check"></i> Data Approval Pelanggan
                </div>
                <span class="count-badge">{{ $pelanggan->total() }} data</span>
            </div>

            <!-- BULK BAR -->
            <div class="selected-bar" id="selectedBar">
                <i class="bi bi-check2-square"></i>
                <span id="selCount">0</span> data terpilih
                <form method="POST" action="{{ url('/approve/bulk-approve') }}">
                    @csrf
                    <input type="hidden" name="ids" id="bulkIds">
                    <button type="submit" class="btn-bulk btn-ba">
                        <i class="bi bi-check-lg"></i> Approve Terpilih
                    </button>
                </form>
                <form method="POST" action="{{ url('/approve/bulk-reject') }}">
                    @csrf
                    <input type="hidden" name="ids" id="bulkIdsR">
                    <button type="submit" class="btn-bulk btn-br">
                        <i class="bi bi-x-lg"></i> Nonaktifkan Terpilih
                    </button>
                </form>
            </div>

            <!-- TABLE META -->
            <div class="table-meta">
                <div class="search-wrap">
                    Search:
                    <form method="GET" action="{{ url('/approve-pelanggan') }}" style="display:contents;">
                        @foreach(request()->except('search','page') as $k=>$v)
                            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                        @endforeach
                        <input type="text" name="search"
                               value="{{ request('search') }}"
                               placeholder="nama / no hp / kode / NIK…">
                    </form>
                </div>
            </div>

            <!-- TABLE -->
            <div style="overflow-x:auto;">
                <table class="ap-table">
                    <thead>
                        <tr>
                            <th style="width:34px;">
                                <input type="checkbox" class="form-check-input" id="checkAll">
                            </th>
                            <th>No</th>
                            <th>Kode <span class="si"><i class="bi bi-caret-up-fill"></i><i class="bi bi-caret-down-fill"></i></span></th>
                            <th>Registrasi <span class="si"><i class="bi bi-caret-up-fill"></i><i class="bi bi-caret-down-fill"></i></span></th>
                            <th>Nama <span class="si"><i class="bi bi-caret-up-fill"></i><i class="bi bi-caret-down-fill"></i></span></th>
                            <th>Telepon</th>
                            <th>NIK</th>
                            <th>Alamat</th>
                            <th>Layanan</th>
                            <th>Status</th>
                            <th>Diapprove Oleh</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pelanggan as $i => $p)
                        <tr>
                            <td>
                                @if($p->status === 'pending')
                                    <input type="checkbox" class="form-check-input row-check" value="{{ $p->id }}">
                                @endif
                            </td>
                            <td>{{ $pelanggan->firstItem() + $i }}</td>
                            <td>
                                <a href="{{ url('/pelanggan/'.$p->id) }}"
                                   style="color:#111827; font-weight:700; text-decoration:none;">
                                    {{ $p->kode_pelanggan ?? '—' }}
                                </a>
                            </td>
                            <td>
                                <div class="date-main">{{ \Carbon\Carbon::parse($p->created_at)->format('Y/m/d') }}</div>
                                <div class="date-rel">{{ \Carbon\Carbon::parse($p->created_at)->diffForHumans() }}</div>
                            </td>
                            <td style="font-weight:600; color:#111827; white-space:nowrap;">{{ $p->nama }}</td>
                            <td>
                                @if($p->no_hp)
                                    <a href="tel:{{ $p->no_hp }}"
                                       style="color:#111827; text-decoration:none; white-space:nowrap;">{{ $p->no_hp }}</a>
                                @else
                                    <span style="color:#d1d5db;">—</span>
                                @endif
                            </td>
                            <td style="font-family:monospace; font-size:12px; color:#6b7280;">
                                {{ $p->nik ?? '—' }}
                            </td>
                            <td style="max-width:170px; white-space:normal; line-height:1.4; font-size:12px; color:#6b7280;">
                                {{ $p->alamat ?? '—' }}
                            </td>
                            <td>
                                <span class="paket-badge">{{ $p->layanan->nama_paket ?? '—' }}</span>
                            </td>
                            <td>
                                @if($p->status === 'pending')
                                    <span class="status-badge s-pending">
                                        <i class="bi bi-hourglass-split"></i> Pending
                                    </span>
                                @elseif($p->status === 'aktif')
                                    <span class="status-badge s-aktif">
                                        <i class="bi bi-check-circle-fill"></i> Aktif
                                    </span>
                                @elseif($p->status === 'nonaktif')
                                    <span class="status-badge s-nonaktif">
                                        <i class="bi bi-x-circle-fill"></i> Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($p->approvedBy)
                                    <div style="font-size:12px; font-weight:600; color:#111827;">
                                        {{ $p->approvedBy->name }}
                                    </div>
                                    @if($p->approved_admin_at)
                                        <div style="font-size:11px; color:#9ca3af;">
                                            {{ \Carbon\Carbon::parse($p->approved_admin_at)->format('d/m/Y H:i') }}
                                        </div>
                                    @endif
                                @else
                                    <span style="color:#d1d5db; font-size:12px;">—</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button type="button" class="btn-act btn-view" title="Detail"
                                        onclick='openDetail(@json($p->load(["layanan","site","approvedBy"])))'>
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    @if($p->status === 'pending')
                                        <form method="POST"
                                              action="{{ url('/approve/'.$p->id.'/approve') }}"
                                              style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn-act btn-approve" title="Approve → set Aktif">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>
                                        <button type="button" class="btn-act btn-reject" title="Nonaktifkan"
                                            onclick="openReject({{ $p->id }}, '{{ addslashes($p->nama) }}')">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="12" class="text-center py-5"
                                style="color:#9ca3af; font-size:14px;">
                                <i class="bi bi-inbox" style="font-size:30px; display:block; margin-bottom:8px;"></i>
                                Tidak ada data yang ditemukan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            <div class="table-footer">
                <div class="pag">
                    <a href="{{ $pelanggan->previousPageUrl() ?? '#' }}"
                       class="page-btn {{ $pelanggan->onFirstPage() ? 'disabled':'' }}">Previous</a>

                    @php $cur = $pelanggan->currentPage(); $last = $pelanggan->lastPage(); @endphp
                    @for($pg = 1; $pg <= $last; $pg++)
                        @if($pg == $cur)
                            <span class="page-btn active">{{ $pg }}</span>
                        @elseif($pg==1 || $pg==$last || abs($pg-$cur)<=2)
                            <a href="{{ $pelanggan->url($pg) }}" class="page-btn">{{ $pg }}</a>
                        @elseif(abs($pg-$cur)==3)
                            <span class="page-btn" style="border:none;cursor:default;">…</span>
                        @endif
                    @endfor

                    <a href="{{ $pelanggan->nextPageUrl() ?? '#' }}"
                       class="page-btn {{ !$pelanggan->hasMorePages() ? 'disabled':'' }}">Next</a>
                </div>
            </div>
        </div><!-- /table-card -->
    </div><!-- /main -->
</div>


<!-- ── MODAL DETAIL ── -->
<div class="modal fade" id="modalDetail" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title d-flex align-items-center gap-2">
                    <i class="bi bi-person-lines-fill" style="color:#3b5bdb;"></i>
                    Detail Pelanggan
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailBody"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- ── MODAL REJECT ── -->
<div class="modal fade" id="modalReject" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title d-flex align-items-center gap-2">
                    <i class="bi bi-x-circle-fill" style="color:#991b1b;"></i>
                    Nonaktifkan Pelanggan
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="rejectForm">
                @csrf
                <div class="modal-body">
                    <p style="font-size:13px; color:#374151; margin-bottom:12px;">
                        Anda akan <strong>menonaktifkan</strong> pelanggan
                        <strong id="rejectName" style="color:#991b1b;"></strong>.
                        Status akan diubah menjadi <code>nonaktif</code>.
                    </p>
                    <label class="form-label" style="font-size:13px; font-weight:600;">
                        Alasan <span style="color:#9ca3af; font-weight:400;">(opsional)</span>
                    </label>
                    {{-- name="alasan" sesuai controller: $request->alasan & alasan_tolak --}}
                    <textarea name="alasan" class="form-control" rows="3"
                        placeholder="Contoh: Data tidak valid, KTP tidak jelas, dsb."
                        style="font-size:13px; border-radius:6px; border:1px solid #d1d5db; resize:none;">
                    </textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm"
                        style="background:#991b1b; color:#fff; border-radius:6px; font-weight:600;">
                        <i class="bi bi-x-lg"></i> Nonaktifkan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// ── Bulk Checkbox ──
const checkAll  = document.getElementById('checkAll');
const rowChecks = [...document.querySelectorAll('.row-check')];
const selBar    = document.getElementById('selectedBar');

function syncBar() {
    const ids = rowChecks.filter(c => c.checked).map(c => c.value).join(',');
    selBar.classList.toggle('show', !!ids);
    document.getElementById('selCount').textContent = ids.split(',').filter(Boolean).length;
    document.getElementById('bulkIds').value        = ids;
    document.getElementById('bulkIdsR').value       = ids;
}

checkAll?.addEventListener('change', () => {
    rowChecks.forEach(c => c.checked = checkAll.checked);
    syncBar();
});
rowChecks.forEach(c => c.addEventListener('change', syncBar));

// ── Helpers ──
const row = (lbl, val) =>
    `<div class="d-row"><span class="d-lbl">${lbl}</span><span class="d-val">${val ?? '—'}</span></div>`;

const fmt = v => v ? new Date(v).toLocaleString('id-ID') : '—';

const statusBadge = {
    pending:  '<span class="status-badge s-pending"><i class="bi bi-hourglass-split"></i> Pending</span>',
    aktif:    '<span class="status-badge s-aktif"><i class="bi bi-check-circle-fill"></i> Aktif</span>',
    nonaktif: '<span class="status-badge s-nonaktif"><i class="bi bi-x-circle-fill"></i> Nonaktif</span>',
};

// ── Modal Detail ──
function openDetail(p) {
    const lokasi   = p.lokasi_link
        ? `<a href="${p.lokasi_link}" target="_blank" style="color:#3b5bdb;">Buka Google Maps <i class="bi bi-box-arrow-up-right"></i></a>`
        : '—';
    const approver = p.approvedBy?.name ?? (p.approved_admin_by ? `User #${p.approved_admin_by}` : '—');

    document.getElementById('detailBody').innerHTML = `
        <div class="sec-div">Informasi Pribadi</div>
        ${row('Kode Pelanggan', `<span style="font-weight:700;">${p.kode_pelanggan ?? '—'}</span>`)}
        ${row('Nama Lengkap',   p.nama)}
        ${row('NIK',            `<span style="font-family:monospace;">${p.nik ?? '—'}</span>`)}
        ${row('No HP',          p.no_hp)}
        ${row('Alamat',         p.alamat)}
        <div class="sec-div">Informasi Layanan</div>
        ${row('Site',   p.site?.nama_site)}
        ${row('Paket',  `<span class="paket-badge">${p.layanan?.nama_paket ?? '—'}</span>`)}
        ${row('Harga',  p.layanan?.harga)}
        ${row('Lokasi', lokasi)}
        <div class="sec-div">Status & Approval</div>
        ${row('Status',          statusBadge[p.status] ?? '—')}
        ${row('Diapprove Oleh',  approver)}
        ${row('Tanggal Approve', fmt(p.approved_admin_at))}
        ${row('Terdaftar',       fmt(p.created_at))}
    `;
    new bootstrap.Modal(document.getElementById('modalDetail')).show();
}

// ── Modal Reject ──
function openReject(id, nama) {
    document.getElementById('rejectForm').action      = `/approve/${id}/reject`;
    document.getElementById('rejectName').textContent = nama;
    new bootstrap.Modal(document.getElementById('modalReject')).show();
}
</script>
</body>
</html>