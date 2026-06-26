<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Agenda CS</title>

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

        <a href="{{ url($instalasiUrl) }}" class="menu-item">
            <i class="bi bi-router"></i> Instalasi Baru
        </a>

        @if(Auth::user()->role == 'cs')
        <a href="{{ route('agenda.cs') }}" class="menu-item active">
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

    <div class="main-content">

        <div class="topbar">
            <div class="d-flex align-items-center gap-3">
            <button type="button" class="btn-sidebar-toggle d-lg-none" onclick="toggleSidebar()">
                <i class="bi bi-list"></i>
            </button>
            <div>
                <div class="page-title">Agenda CS</div>
                <div class="page-sub">Monitoring pengajuan upgrade dan downgrade layanan</div>
            </div>
        </div>
            <div class="breadcrumb-area">
                <i class="bi bi-house-door"></i>
                <span class="sep">/</span>
                <span>CS</span>
                <span class="sep">/</span>
                <span class="current">Agenda CS</span>
            </div>
        </div>

        @if(session('success'))
        <div class="alert d-flex align-items-center gap-2 mb-4"
             style="background:#e8fff1; border:1.5px solid #b7f3cd; color:var(--green-dark); border-radius:12px; font-weight:600; font-size:13px;">
            <i class="bi bi-check-circle-fill" style="font-size:17px;"></i>
            {{ session('success') }}
        </div>
        @endif

        @php
            $total    = $agenda->count();
            $menunggu = $agenda->where('status', 'pending')->count();
            $selesai  = $agenda->where('status', 'selesai')->count();
            $ditolak  = $agenda->where('status', 'ditolak')->count();
        @endphp

        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-icon blue"><i class="bi bi-journal-text"></i></div>
                <div><div class="stat-val">{{ $total }}</div><div class="stat-lbl">Total Pengajuan</div></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon yellow"><i class="bi bi-hourglass-split"></i></div>
                <div><div class="stat-val">{{ $menunggu }}</div><div class="stat-lbl">Menunggu</div></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green"><i class="bi bi-check-circle"></i></div>
                <div><div class="stat-val">{{ $selesai }}</div><div class="stat-lbl">Selesai</div></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon red"><i class="bi bi-x-circle"></i></div>
                <div><div class="stat-val">{{ $ditolak }}</div><div class="stat-lbl">Ditolak</div></div>
            </div>
        </div>

        <div class="form-card">

            <div class="form-card-header" style="border-radius:18px 18px 0 0; justify-content:space-between; flex-wrap:wrap; gap:12px;">
                <div style="display:flex; align-items:center; gap:12px;">
                    <div class="icon-wrap"><i class="bi bi-arrow-down-up"></i></div>
                    <div>
                        <div class="form-card-title">Daftar Pengajuan Upgrade / Downgrade</div>
                        <div class="form-card-sub">Pantau status pengajuan perubahan paket layanan</div>
                    </div>
                </div>
                <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                    <div class="search-icon-wrap">
                        <i class="bi bi-search"></i>
                        <input type="text" id="searchInput" class="search-input-white" placeholder="Cari pelanggan..." oninput="filterTable()">
                    </div>
                    <select class="filter-select-white" id="filterJenis" onchange="filterTable()">
                        <option value="">Semua Jenis</option>
                        <option value="upgrade_layanan">Upgrade</option>
                        <option value="downgrade_layanan">Downgrade</option>
                    </select>
                    <select class="filter-select-white" id="filterStatus" onchange="filterTable()">
                        <option value="">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="selesai">Selesai</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                </div>
            </div>

            <div class="agenda-table-wrap">
                <table class="agenda-table" id="agendaTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Pelanggan</th>
                            <th>Paket Saat Ini</th>
                            <th>Paket Baru</th>
                            <th>Jenis</th>
                            <th>Status</th>
                            <th>Catatan</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody id="agendaBody">
                        @forelse($agenda as $item)
                        <tr
                            data-jenis="{{ strtolower($item->jenis) }}"
                            data-status="{{ strtolower($item->status) }}"
                            data-search="{{ strtolower($item->pelanggan_id . ' ' . ($item->pelanggan->nama ?? '')) }}"
                        >
                            <td><span class="id-badge">{{ $item->id }}</span></td>

                            <td>
                                <span style="font-weight:700;">{{ $item->pelanggan->nama ?? '#'.$item->pelanggan_id }}</span>
                            </td>

                            <td>{{ $item->pelanggan->layanan->nama_paket ?? '—' }}</td>

                            <td>{{ $item->layananBaru->nama_paket ?? '—' }}</td>

                            <td>
                                @if($item->jenis == 'upgrade_layanan')
                                    <span class="jenis-badge jenis-instalasi">Upgrade</span>
                                @else
                                    <span class="jenis-badge jenis-relokasi">Downgrade</span>
                                @endif
                            </td>

                            <td>
                                @php
                                    $statusClass = match(strtolower($item->status)) {
                                        'pending' => 'status-menunggu',
                                        'selesai' => 'status-selesai',
                                        'ditolak' => 'status-ditolak',
                                        default   => 'status-menunggu',
                                    };
                                @endphp
                                <span class="status-badge {{ $statusClass }}">
                                    <span class="dot"></span>
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>

                            <td>{{ $item->catatan ?? '—' }}</td>

                            <td>
                                <div class="datetime-text">
                                    <span class="date-part">{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</span><br>
                                    {{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }}
                                </div>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="bi bi-journal-x"></i>
                                    <p>Belum ada pengajuan yang tersedia.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination-bar">
                <div>
                    Menampilkan <strong style="color:var(--text);" id="visibleCount">{{ $agenda->count() }}</strong>
                    dari <strong style="color:var(--text);">{{ $agenda->count() }}</strong> pengajuan
                </div>
            </div>

        </div>

        <div style="height:28px;"></div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleSidebar() {
    document.getElementById('appSidebar').classList.toggle('show');
    document.getElementById('sidebarOverlay').classList.toggle('show');
}
function filterTable() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    const jenis  = document.getElementById('filterJenis').value.toLowerCase();
    const status = document.getElementById('filterStatus').value.toLowerCase();
    const rows   = document.querySelectorAll('#agendaBody tr[data-jenis]');
    let visible  = 0;
    rows.forEach(row => {
        const ok = (!search || row.dataset.search.includes(search))
                && (!jenis  || row.dataset.jenis  === jenis)
                && (!status || row.dataset.status === status);
        row.style.display = ok ? '' : 'none';
        if (ok) visible++;
    });
    const vc = document.getElementById('visibleCount');
    if (vc) vc.textContent = visible;
}
</script>
</body>
</html>