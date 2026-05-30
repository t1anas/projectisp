<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Agenda NOC</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('inputform.css') }}">
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

        @if(Auth::user()->role == 'noc')
        <a href="{{ url('/agenda-noc') }}" class="menu-item active">
            <i class="bi bi-journal-check"></i> Agenda NOC
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
            <div>
                <div class="page-title">Agenda NOC</div>
                <div class="page-sub">Kelola agenda dan jadwal teknisi NOC</div>
            </div>
            <div class="breadcrumb-area">
                <i class="bi bi-house-door"></i>
                <span class="sep">/</span>
                <span>NOC</span>
                <span class="sep">/</span>
                <span class="current">Agenda NOC</span>
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
                <div><div class="stat-val">{{ $total }}</div><div class="stat-lbl">Total Agenda</div></div>
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
                    <div class="icon-wrap"><i class="bi bi-journal-check"></i></div>
                    <div>
                        <div class="form-card-title">Daftar Agenda NOC</div>
                        <div class="form-card-sub">Setujui atau tolak agenda yang masuk</div>
                    </div>
                </div>
                <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                    <div class="search-icon-wrap">
                        <i class="bi bi-search"></i>
                        <input type="text" id="searchInput" class="search-input-white" placeholder="Cari pelanggan..." oninput="filterTable()">
                    </div>
                    <select class="filter-select-white" id="filterJenis" onchange="filterTable()">
                        <option value="">Semua Jenis</option>
                        <option value="instalasi">Instalasi</option>
                        <option value="gangguan">Gangguan</option>
                        <option value="maintenance">Maintenance</option>
                        <option value="survey">Survey</option>
                        <option value="relokasi">Relokasi</option>
                        <option value="isolir">Isolir</option>
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
                            <th>Nama Layanan</th>
                            <th>Jenis</th>
                            <th>Status</th>
                            <th>Dibuat Oleh</th>
                            <th>Disetujui Oleh</th>
                            <th>Approved At</th>
                            <th>Created At</th>
                            <th>Aksi</th>
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
                                <span style="font-weight:700;">{{ $item->pelanggan->nama ?? '#'.$item->pelanggan_id }} </span>
                            </td>

                            <td>{{ $item->pelanggan->nama_layanan ?? '—' }}</td>

                            <td>
                                @php
                                    $jenisClass = match(strtolower($item->jenis)) {
                                        'instalasi'   => 'jenis-instalasi',
                                        'gangguan'    => 'jenis-gangguan',
                                        'maintenance' => 'jenis-maintenance',
                                        'survey'      => 'jenis-survey',
                                        'relokasi'    => 'jenis-relokasi',
                                        'isolir'      => 'jenis-isolir',
                                        default       => 'jenis-default',
                                    };
                                @endphp
                                <span class="jenis-badge {{ $jenisClass }}">{{ ucfirst($item->jenis) }}</span>
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

                            <td>{{ $item->createdBy->name ?? '—' }}</td>

                            <td>{{ $item->approvedBy->name ?? '—' }}</td>

                            <td>
                                @if($item->approved_at)
                                <div class="datetime-text">
                                    <span class="date-part">{{ \Carbon\Carbon::parse($item->approved_at)->format('d M Y') }}</span><br>
                                    {{ \Carbon\Carbon::parse($item->approved_at)->format('H:i') }}
                                </div>
                                @else
                                <span style="color:var(--muted); font-size:12px;">—</span>
                                @endif
                            </td>

                            <td>
                                <div class="datetime-text">
                                    <span class="date-part">{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</span><br>
                                    {{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }}
                                </div>
                            </td>

                            <td>
                                @if($item->status == 'pending')
                                <div class="action-group">
                                    <button
                                        class="action-modern btn-approve"
                                        title="Setujui"
                                        onclick="openModal('approve', {{ $item->id }}, '{{ ucfirst($item->jenis) }}', '{{ $item->pelanggan->nama ?? '#'.$item->pelanggan_id }}')"
                                    ><i class="bi bi-check-lg"></i></button>
                                    <button
                                        class="action-modern btn-reject"
                                        title="Tolak"
                                        onclick="openModal('reject', {{ $item->id }}, '{{ ucfirst($item->jenis) }}', '{{ $item->pelanggan->nama ?? '#'.$item->pelanggan_id }}')"
                                    ><i class="bi bi-x-lg"></i></button>
                                </div>
                                @else
                                <span style="color:var(--muted); font-size:12px; font-style:italic;">Diproses</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9">
                                <div class="empty-state">
                                    <i class="bi bi-journal-x"></i>
                                    <p>Belum ada agenda yang tersedia.</p>
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
                    dari <strong style="color:var(--text);">{{ $agenda->count() }}</strong> agenda
                </div>
            </div>

        </div>

        <div style="height:28px;"></div>
    </div>
</div>

<div class="modal-backdrop-custom" id="confirmModal">
    <div class="modal-box">
        <div class="modal-icon" id="modalIcon"><i id="modalIconInner"></i></div>
        <div class="modal-title" id="modalTitle"></div>
        <div class="modal-desc"  id="modalDesc"></div>
        <div class="modal-actions">
            <button class="modal-btn-cancel" onclick="closeModal()">Batal</button>
            <form id="modalForm" method="POST" style="display:inline;">
                @csrf
                @method('PATCH')
                <button type="submit" class="modal-btn-confirm" id="modalConfirmBtn"></button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
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

function openModal(type, id, jenis, nama) {
    const modal = document.getElementById('confirmModal');
    const icon  = document.getElementById('modalIcon');
    const iconI = document.getElementById('modalIconInner');
    const title = document.getElementById('modalTitle');
    const desc  = document.getElementById('modalDesc');
    const btn   = document.getElementById('modalConfirmBtn');
    const form  = document.getElementById('modalForm');

    if (type === 'approve') {
        icon.className  = 'modal-icon approve';
        iconI.className = 'bi bi-check-circle-fill';
        title.textContent = 'Setujui Agenda?';
        desc.textContent  = `Kamu akan menyetujui agenda ${jenis} untuk pelanggan ${nama}.`;
        btn.className   = 'modal-btn-confirm approve';
        btn.innerHTML   = '<i class="bi bi-check-lg"></i> Ya, Setujui';
        form.action     = `/agenda-noc/${id}/approve`;
    } else {
        icon.className  = 'modal-icon reject';
        iconI.className = 'bi bi-x-circle-fill';
        title.textContent = 'Tolak Agenda?';
        desc.textContent  = `Kamu akan menolak agenda ${jenis} untuk pelanggan ${nama}.`;
        btn.className   = 'modal-btn-confirm reject';
        btn.innerHTML   = '<i class="bi bi-x-lg"></i> Ya, Tolak';
        form.action     = `/agenda-noc/${id}/reject`;
    }

    modal.classList.add('show');
}

function closeModal() {
    document.getElementById('confirmModal').classList.remove('show');
}

document.getElementById('confirmModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>
</body>
</html>