<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Tagihan - Jagonet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('inputform.css') }}">
    <link rel="stylesheet" href="{{ asset('pembayaran.css') }}">
    
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
        <a href="{{ Auth::user()->dashboard_url }}" class="menu-item"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="{{ url('/layanan') }}" class="menu-item"><i class="bi bi-wifi"></i> Data Layanan</a>
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
            <a href="{{ url('/pemasukan') }}" class="menu-item active">
                <i class="bi bi-wallet2"></i> Pemasukan
            </a>
        @endif
        <div class="section-label">Pelanggan</div>
        <a href="{{ url('/pelanggan') }}" class="menu-item"><i class="bi bi-people"></i> Data Pelanggan</a>
        <div class="profile-section">
            <div class="admin-card">
                <div class="admin-avatar"><i class="bi bi-person-fill text-white"></i></div>
                <div>
                    <div class="admin-role">{{ strtoupper(Auth::user()->role) }}</div>
                    <div class="admin-name">{{ Auth::user()->name }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn"><i class="bi bi-box-arrow-right"></i> LOG OUT</button>
            </form>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="main-content" style="flex:1;">

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show m-3">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show m-3">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="topbar">
            <div>
                <div class="page-title">Data Tagihan</div>
                <div class="page-sub">Kelola semua tagihan pelanggan internet</div>
            </div>
            <div class="breadcrumb-area">
                <i class="bi bi-house-door"></i>
                <span class="sep">/</span>
                <span>Keuangan</span>
                <span class="sep">/</span>
                <span class="current">Tagihan</span>
            </div>
        </div>

        <div class="form-card">

            <div class="form-card-header">
                <div class="icon-wrap"><i class="bi bi-receipt"></i></div>
                <div>
                    <div class="form-card-title">Data Tagihan</div>
                    <div class="form-card-sub">Daftar seluruh tagihan pelanggan</div>
                </div>
            </div>

            <div class="filter-box">
                <div class="d-flex gap-2 mb-3">
                    <button type="button" class="btn btn-primary px-3"
                            data-bs-toggle="modal" data-bs-target="#modalTambahTagihan">
                        <i class="bi bi-plus-lg me-1"></i> Tambah
                    </button>
                    <div class="dropdown">
                        <button class="btn-data-terpilih dropdown-toggle"
                                type="button" data-bs-toggle="dropdown">
                            Data Terpilih
                        </button>
                        <ul class="dropdown-menu shadow">
                            <li>
                                <button type="button" class="dropdown-item text-danger"
                                        onclick="hapusTagihanTerpilih()">
                                    <i class="bi bi-trash-fill me-2"></i> Hapus Data Terpilih
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>

                <form method="GET" action="{{ url('/tagihan') }}">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <input type="text" name="cari" class="form-control"
                                   placeholder="Cari pelanggan..."
                                   value="{{ request('cari') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="lunas"       {{ request('status') == 'lunas'       ? 'selected' : '' }}>Lunas</option>
                                <option value="belum bayar" {{ request('status') == 'belum bayar' ? 'selected' : '' }}>Belum Bayar</option>
                                <option value="belum lunas" {{ request('status') == 'belum lunas' ? 'selected' : '' }}>Belum Lunas</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="month" name="bulan" class="form-control"
                                   value="{{ request('bulan') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- TABEL --}}
            <div class="table-responsive px-3 pb-4">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light text-center fw-bold">
                        <tr>
                            <th>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="checkAll" onchange="toggleAll(this)">
                                    <label class="form-check-label" for="checkAll"></label>
                                </div>
                            </th>
                            <th>No</th>
                            <th style="white-space: nowrap">Tanggal</th>
                            <th style="min-width:180px;">Nama</th>
                            <th>Layanan</th>
                            <th style="white-space: nowrap">Total</th>
                            <th style="white-space: nowrap;">Jumlah Terbayar</th>
                            <th>Jenis Tagihan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @forelse($tagihan as $t)
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input row-check" type="checkbox" value="{{ $t->id }}"
                                            id="check{{ $t->id }}" onchange="updateSelected()">
                                        <label class="form-check-label" for="check{{ $t->id }}"></label>
                                    </div>
                                </td>
                                <td>{{ $loop->iteration }}</td>
                                <td style="white-space: nowrap">
                                    <span class="date-pill">
                                        <i class="bi bi-calendar3"></i>
                                        {{ \Carbon\Carbon::parse($t->tanggal)->translatedFormat('d M Y') }}
                                    </span>
                                </td>
                                <td class="text-start" style="min-width:180px;">
                                    <div class="clamp-2">{{ $t->pelanggan->nama ?? '-' }}</div>
                                </td>
                                <td>{{ optional($t->layanan)->nama_paket ?? '-' }}</td>
                                <td class="text-start fw-semibold" style="white-space: nowrap">Rp {{ number_format($t->total, 0, ',', '.') }}</td>
                                <td class="text-start fw-semibold" style="white-space: nowrap">Rp {{ number_format($t->pembayaran->sum('jumlah_bayar'), 0, ',', '.') }}</td>
                                <td>{{ $t->jenis_tagihan }}</td>
                                <td style="white-space: nowrap">
                                    @if($t->status == 'lunas')
                                        <span class="status-pill status-lunas"><i class="bi bi-check-circle-fill"></i> Lunas</span>
                                    @elseif($t->status == 'belum lunas')
                                        <span class="status-pill status-belum"><i class="bi bi-exclamation-triangle-fill"></i> Belum Lunas</span>
                                    @else
                                        <span class="status-pill status-belum"><i class="bi bi-x-circle-fill"></i> Belum Bayar</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-group">
                                        <button class="action-modern btn-detail" title="Detail"
                                                data-bs-toggle="modal"
                                                data-bs-target="#detailTagihan{{ $t->id }}">
                                            <i class="bi bi-eye-fill"></i>
                                        </button>
                                        <button class="action-modern btn-edit" title="Edit"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editTagihan{{ $t->id }}">
                                            <i class="bi bi-pencil-fill"></i>
                                        </button>
                                        <button type="button" class="action-modern btn-hapus"
                                                title="Hapus"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalHapusTagihan{{ $t->id }}">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-muted py-4">
                                    <i class="bi bi-inbox fs-4 d-block mb-1"></i>
                                    Tidak ada data tagihan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex gap-4 fw-light text-opacity-75">
                    <div>
                        Total:
                        <span class="fw-semibold">
                            Rp {{ number_format($total ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                    <div>
                        Total Terbayar:
                        <span class="fw-semibold">
                            Rp {{ number_format($totalBayar ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- MODAL TAMBAH --}}
<div class="modal fade" id="modalTambahTagihan" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:20px; overflow:hidden;">
            <form action="{{ url('/tagihan') }}" method="POST">
                @csrf
                <div style="background:linear-gradient(135deg,#16a34a,#22c55e,#4ade80); padding:22px; color:white;">
                    <h5 class="fw-bold mb-0"><i class="bi bi-plus-circle me-2"></i>Tambah Tagihan</h5>
                    <small style="opacity:.85;">Isi data tagihan pelanggan</small>
                </div>
                <div class="p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pelanggan</label>
                        <select name="pelanggan_id" class="form-select rounded-3"
                                id="selectPelanggan" onchange="updateLayanan(this)" required>
                            <option value="">-- Pilih Pelanggan --</option>
                            @foreach($pelanggan as $p)
                                <option value="{{ $p->id }}"
                                        data-layanan="{{ $p->layanan->id ?? '' }}"
                                        data-harga="{{ $p->layanan->harga ?? 0 }}">
                                    {{ $p->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <input type="hidden" name="layanan_id" id="inputLayananId">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-calendar3"></i></span>
                            <input type="date" name="tanggal" class="form-control"
                                   value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jumlah Tagihan</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white">Rp</span>
                            <input type="number" name="total" id="inputTotal" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jenis Tagihan</label>
                        <select name="jenis_tagihan" class="form-select rounded-3" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="tagihan internet bulanan">Tagihan Internet Bulanan</option>
                            <option value="tagihan instalasi">Tagihan Instalasi</option>
                            <option value="tagihan penjualan alat">Tagihan Penjualan Alat</option>
                            <option value="pendapatan jasa">Pendapatan Jasa</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keterangan</label>
                        <textarea name="keterangan" class="form-control rounded-3"
                                  rows="2" placeholder="Opsional..."></textarea>
                    </div>
                </div>
                <div class="px-4 pb-4 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-light border rounded-3 px-3"
                            data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn text-white px-4 rounded-3 fw-semibold"
                            style="background:linear-gradient(135deg,#16a34a,#22c55e);">
                        <i class="bi bi-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL HAPUS TAGIHAN --}}
@foreach($tagihan as $t)
    <div class="modal fade" id="modalHapusTagihan{{ $t->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
            <div class="modal-content" style="border:none; border-radius:20px; overflow:hidden;">

                <div style="display:flex; align-items:center; gap:12px; padding:18px 20px;
                            background:linear-gradient(135deg,#fee2e2,#fecaca);
                            border-bottom:1px solid #fca5a5;">
                    <div style="width:40px; height:40px; border-radius:50%; background:#fca5a5;
                                display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <i class="bi bi-trash-fill" style="color:#dc2626; font-size:16px;"></i>
                    </div>
                    <div style="flex:1;">
                        <div style="font-weight:700; font-size:15px; color:#b91c1c;">Hapus Tagihan</div>
                        <div style="font-size:12px; color:#ef4444;">Tindakan ini tidak dapat dibatalkan</div>
                    </div>
                </div>

                <div style="padding:20px;">
                    <div style="display:flex; align-items:center; gap:12px;
                                background:#f9fafb; border:1px solid #e5e7eb;
                                border-radius:12px; padding:14px; margin-bottom:14px;">
                        <div style="width:42px; height:42px; border-radius:50%;
                                    background:#fee2e2; color:#dc2626;
                                    display:flex; align-items:center; justify-content:center;
                                    font-size:18px; flex-shrink:0;">
                            <i class="bi bi-receipt"></i>
                        </div>
                        <div>
                            <div style="font-size:14px; font-weight:700; color:#111827;">
                                {{ $t->pelanggan->nama ?? '-' }}
                                <span style="font-size:11px; font-weight:500; color:#dc2626;
                                             background:#fee2e2; border:1px solid #fecaca;
                                             border-radius:6px; padding:1px 8px; margin-left:4px;">
                                    {{ $t->status == 'lunas' ? 'Lunas' : ($t->status == 'belum lunas' ? 'Belum Lunas' : 'Belum Bayar') }}
                                </span>
                            </div>
                            <div style="font-size:12px; color:#6b7280; margin-top:2px;">
                                {{ \Carbon\Carbon::parse($t->tanggal)->translatedFormat('d F Y') }}
                                &middot; Rp {{ number_format($t->total, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>

                    <div style="display:flex; align-items:flex-start; gap:10px;
                                background:#fef2f2; border:1px solid #fecaca;
                                border-left:4px solid #dc2626;
                                border-radius:10px; padding:12px 14px; margin-bottom:20px;">
                        <i class="bi bi-exclamation-triangle-fill"
                           style="color:#dc2626; margin-top:1px; flex-shrink:0;"></i>
                        <span style="font-size:12.5px; color:#b91c1c; line-height:1.6;">
                            Data tagihan ini beserta riwayat pembayarannya
                            akan <strong>dihapus permanen</strong>.
                        </span>
                    </div>

                    <div style="display:flex; gap:8px;">
                        <button type="button"
                                class="btn btn-secondary btn-sm"
                                data-bs-dismiss="modal"
                                style="flex:1; border-radius:10px; height:40px;">
                            Batal
                        </button>
                        <form method="POST"
                              action="{{ url('/tagihan/'.$t->id) }}"
                              style="flex:1; margin:0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    style="width:100%; height:40px; border-radius:10px;
                                           border:1px solid #fecaca; background:#fee2e2;
                                           color:#dc2626; font-weight:600; font-size:13px;
                                           cursor:pointer; display:flex; align-items:center;
                                           justify-content:center; gap:6px;">
                                <i class="bi bi-trash-fill"></i> Ya, hapus sekarang
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endforeach

<script>
function updateLayanan(select) {
    const opt = select.options[select.selectedIndex];
    document.getElementById('inputLayananId').value = opt.dataset.layanan ?? '';
    document.getElementById('inputTotal').value     = opt.dataset.harga  ?? '';
}
</script>

{{-- MODAL DETAIL --}}
@foreach($tagihan as $t)
    <div class="modal fade" id="detailTagihan{{ $t->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 overflow-hidden"
                 style="border-radius:24px; box-shadow:0 25px 70px rgba(0,0,0,.18);">
                <div style="background:linear-gradient(135deg,#16a34a,#22c55e,#4ade80); padding:28px; position:relative;">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:58px; height:58px; border-radius:50%; background:rgba(255,255,255,.18);
                                    border:2px solid rgba(255,255,255,.35); display:flex; align-items:center;
                                    justify-content:center; backdrop-filter:blur(6px);">
                            <i class="bi bi-person-fill text-white fs-4"></i>
                        </div>
                        <div>
                            <div style="font-size:20px; font-weight:800; color:white; line-height:1.2;">
                                {{ $t->pelanggan->nama ?? '-' }}
                            </div>
                            <div style="font-size:12px; color:rgba(255,255,255,.8);">
                                ID : {{ $t->pelanggan->id ?? '-' }}
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 d-flex gap-2 flex-wrap">
                        <span style="background:rgba(255,255,255,.18); color:white; padding:6px 12px;
                                     border-radius:50px; font-size:11px; font-weight:700;">
                            {{ $t->pelanggan->alamat }}
                        </span>
                    </div>
                </div>
                <div style="background:#f0fdf4; padding:18px 28px; border-bottom:1px solid #dcfce7;">
                    <div class="d-flex justify-content-between align-items-center">
                        <span style="font-size:12px; color:#666; font-weight:700;">TOTAL TAGIHAN</span>
                        <span style="font-size:26px; font-weight:900; color:#15803d;">
                            Rp {{ number_format($t->total, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
                <div class="p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="p-3 rounded-4 border bg-light">
                                <small class="text-muted d-block mb-1">Tanggal Tagihan</small>
                                <div class="fw-bold">{{ \Carbon\Carbon::parse($t->tanggal)->format('d F Y') }}</div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="p-3 rounded-4 border bg-light">
                                <small class="text-muted d-block mb-1">Nama Paket</small>
                                <div class="fw-bold">{{ $t->layanan->nama_paket ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="p-3 rounded-4 border bg-light">
                                <small class="text-muted d-block mb-1">Status Pembayaran</small>
                                @if($t->status == 'lunas')
                                    <span class="badge bg-success px-3 py-2 rounded-pill">Sudah Dibayar</span>
                                @elseif($t->status == 'belum lunas')
                                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Belum Lunas</span>
                                @else
                                    <span class="badge bg-danger px-3 py-2 rounded-pill">Belum Dibayar</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-success w-100 mt-4 py-2 rounded-4 fw-bold"
                            data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

{{-- MODAL EDIT --}}
@foreach($tagihan as $t)
    <div class="modal fade" id="editTagihan{{ $t->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 overflow-hidden"
                 style="border-radius:24px; box-shadow:0 25px 70px rgba(0,0,0,.18);">
                <form action="{{ url('/tagihan/'.$t->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div style="background:linear-gradient(135deg,#16a34a,#22c55e,#4ade80);
                                padding:24px; color:white;">
                        <h5 class="fw-bold mb-0"><i class="bi bi-pencil-square me-2"></i> Edit Tagihan</h5>
                        <small style="opacity:.8;">Perbarui data tagihan pelanggan</small>
                    </div>
                    <div class="p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control rounded-3"
                                   value="{{ \Carbon\Carbon::parse($t->tanggal)->format('Y-m-d') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jumlah Tagihan</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">Rp</span>
                                <input type="number" name="total" class="form-control rounded-3"
                                       value="{{ $t->total }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Layanan</label>
                            <select name="layanan_id" class="form-select rounded-3">
                                <option value="">-- Tanpa Layanan --</option>
                                @foreach($layanan as $l)
                                    <option value="{{ $l->id }}"
                                        {{ $t->layanan_id == $l->id ? 'selected' : '' }}>
                                        {{ $l->nama_paket }} ({{ $l->kecepatan }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jenis Tagihan</label>
                            <input type="text" class="form-control rounded-3"
                                   value="{{ $t->jenis_tagihan }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Status Pembayaran</label>
                            <select name="status" class="form-select rounded-3">
                                <option value="lunas"       {{ $t->status == 'lunas'       ? 'selected' : '' }}>Lunas</option>
                                <option value="belum lunas" {{ $t->status == 'belum lunas' ? 'selected' : '' }}>Belum Lunas</option>
                                <option value="belum bayar" {{ $t->status == 'belum bayar' ? 'selected' : '' }}>Belum Bayar</option>
                            </select>
                        </div>
                    </div>
                    <div class="px-4 pb-4 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light border rounded-3"
                                data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn text-white rounded-3"
                                style="background:linear-gradient(135deg,#16a34a,#22c55e);">
                            <i class="bi bi-check-circle me-1"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
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

function hapusTagihanTerpilih() {
    const ids = getSelectedIds();
    if (ids.length === 0) {
        alert('Pilih minimal satu data terlebih dahulu.');
        return;
    }
    if (!confirm(`Yakin ingin menghapus ${ids.length} tagihan terpilih? Tindakan ini tidak dapat dibatalkan.`)) return;

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ url("/tagihan/bulk-delete") }}';
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
</script>
</body>
</html>