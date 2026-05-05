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
<style>
    .date-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 50px;
    font-size: 12px;
    font-weight: 600;
    background: #eef2ff;
    color: #0c0b28;
    border: 1px solid #dbeafe;
}

.date-pill i {
    font-size: 11px;
}
.modal-content input,
.modal-content select,
.modal-content textarea {
    border-radius: 10px;
    transition: 0.2s;
}

.modal-content input:focus,
.modal-content select:focus,
.modal-content textarea:focus {
    box-shadow: 0 0 0 3px rgba(34,197,94,.15);
    border-color: #22c55e;
}
body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f4f6f9; }
.table td, .table th { vertical-align: middle; }
.status-pill { display: inline-flex; align-items: center; gap: 7px; padding: 7px 14px; border-radius: 50px; font-size: 12px; font-weight: 700; border: 1px solid transparent; }
.status-pill i { font-size: 10px; }
.status-lunas { background: linear-gradient(135deg, #e8fff1, #d8ffe8); color: #0f9d58; border-color: #b7f3cd; }
.status-belum { background: linear-gradient(135deg, #fff1f1, #ffe1e1); color: #dc3545; border-color: #ffc4c4; }
.action-group { display: flex; align-items: center; gap: 8px; justify-content: center; }
.action-modern { width: 38px; height: 38px; border: none; border-radius: 12px; display: flex; align-items: center; justify-content: center; transition: .25s; box-shadow: 0 6px 14px rgba(0,0,0,.06); cursor: pointer; text-decoration: none; }
.action-modern i { font-size: 15px; }
.action-modern:hover { transform: translateY(-3px); }
.btn-detail { background: linear-gradient(135deg, #eef4ff, #dfeaff); color: #0d6efd; }
.btn-detail:hover { background: #0d6efd; color: #fff; }
.btn-edit { background: linear-gradient(135deg, #fff7e6, #ffecc2); color: #ff9800; }
.btn-edit:hover { background: #ff9800; color: #fff; }
.btn-hapus { background: linear-gradient(135deg, #fff1f1, #ffe1e1); color: #dc3545; }
.btn-hapus:hover { background: #dc3545; color: #fff; }
.filter-box { padding: 20px; border-bottom: 1px solid #eee; }
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
        <a href="{{ Auth::user()->dashboard_url }}" class="menu-item"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="{{ url('/layanan') }}" class="menu-item"><i class="bi bi-wifi"></i> Data Layanan</a>
        <a href="{{ url('/instalasi') }}" class="menu-item"><i class="bi bi-router"></i> Instalasi Baru</a>
        @if(Auth::user()->role == 'admin')
        <a href="{{ url('/pemasukan') }}" class="menu-item"><i class="bi bi-wallet2"></i> Pemasukan</a>
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

<!-- Ganti bagian filter-box -->
<div class="filter-box">
    <div class="row g-3 align-items-end">

        {{-- TOMBOL TAMBAH (paling kiri) --}}
        <div class="col-md-auto">
            <button type="button" class="btn btn-primary h-100 px-3"
                    data-bs-toggle="modal" data-bs-target="#modalTambahTagihan">
                <i class="bi bi-plus-lg me-1"></i> Tambah
            </button>
        </div>

        {{-- FILTER FORM --}}
        <form method="GET" action="{{ url('/tagihan') }}" class="col">
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
</div>

            <div class="table-responsive px-3 pb-4">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light text-center fw-bold">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Jenis Tagihan</th>
                            <th>Layanan</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @forelse($tagihan as $t)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
    <span class="date-pill">
        <i class="bi bi-calendar3"></i>
        {{ \Carbon\Carbon::parse($t->tanggal)->translatedFormat('d M Y') }}
    </span>
</td>
                            <td class="text-start">{{ $t->pelanggan->nama ?? '-' }}</td>
                            <td>{{ $t->jenis_tagihan ?? '-' }}</td>
                            <td>{{ $t->pelanggan->layanan->nama_paket ?? '-' }}</td>
                            <td class="text-start fw-semibold">Rp {{ number_format($t->total, 0, ',', '.') }}</td>
                            <td>
                                @if($t->status == 'lunas')
                                    <span class="status-pill status-lunas"><i class="bi bi-check-circle-fill"></i> Lunas</span>
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
                                    <form method="POST" action="{{ url('/tagihan/'.$t->id) }}"
                                          style="margin:0;" onsubmit="return confirm('Yakin mau hapus tagihan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-modern btn-hapus" title="Hapus">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-muted py-4">
                                <i class="bi bi-inbox fs-4 d-block mb-1"></i>
                                Tidak ada data tagihan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="fw-light text-opacity-75 px-1">
                    Total: <span class="fw-semibold">Rp {{ number_format($total ?? 0, 0, ',', '.') }}</span>
                </div>
            </div>

        </div>

    </div>
</div>

<!-- MODAL TAMBAH TAGIHAN -->
<div class="modal fade" id="modalTambahTagihan" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:20px; overflow:hidden;">

            <form action="{{ url('/tagihan') }}" method="POST">
                @csrf

                <!-- HEADER -->
                <div style="
                    background: linear-gradient(135deg,#16a34a,#22c55e,#4ade80);
                    padding:22px;
                    color:white;
                ">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="fw-bold mb-0">
                            <i class="bi bi-plus-circle me-2"></i>Tambah Tagihan
                        </h5>

                    </div>
                    <small style="opacity:.85;">Isi data tagihan pelanggan</small>
                </div>

                <!-- BODY -->
                <div class="p-4">

                    <!-- Pelanggan -->
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

                    <!-- Tanggal -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="bi bi-calendar3"></i>
                            </span>
                            <input type="date" name="tanggal"
                                   class="form-control"
                                   value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <!-- Jumlah -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jumlah Tagihan</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white">Rp</span>
                            <input type="number" name="total" id="inputTotal"
                                   class="form-control" required>
                        </div>
                    </div>

                    <!-- Jenis -->
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

                    <!-- Keterangan -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keterangan</label>
                        <textarea name="keterangan" class="form-control rounded-3"
                                  rows="2" placeholder="Opsional..."></textarea>
                    </div>

                </div>

                <!-- FOOTER -->
                <div class="px-4 pb-4 d-flex justify-content-end gap-2">
                    <button type="button"
                            class="btn btn-light border rounded-3 px-3"
                            data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit"
                            class="btn text-white px-4 rounded-3 fw-semibold"
                            style="background:linear-gradient(135deg,#16a34a,#22c55e);">
                        <i class="bi bi-save me-1"></i> Simpan
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<script>
function updateLayanan(select) {
    const opt = select.options[select.selectedIndex];
    document.getElementById('inputLayananId').value = opt.dataset.layanan ?? '';
    document.getElementById('inputTotal').value     = opt.dataset.harga  ?? '';
}
</script>

@foreach($tagihan as $t)
<div class="modal fade" id="detailTagihan{{ $t->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 overflow-hidden"
             style="border-radius:24px; box-shadow:0 25px 70px rgba(0,0,0,.18);">

            {{-- HEADER --}}
            <div style="
                background:linear-gradient(135deg,#16a34a,#22c55e,#4ade80);
                padding:28px;
                position:relative;
            ">

                <div class="d-flex align-items-center gap-3">

                    <div style="
                        width:58px;
                        height:58px;
                        border-radius:50%;
                        background:rgba(255,255,255,.18);
                        border:2px solid rgba(255,255,255,.35);
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        backdrop-filter:blur(6px);
                    ">
                        <i class="bi bi-person-fill text-white fs-4"></i>
                    </div>

                    <div>
                        <div style="font-size:20px;font-weight:800;color:white;line-height:1.2;">
                            {{ $t->pelanggan->nama ?? '-' }}
                        </div>

                        <div style="font-size:12px;color:rgba(255,255,255,.8);">
                            ID : {{ $t->pelanggan->id ?? '-' }}
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2 flex-wrap">

                    <span style="
                        background:rgba(255,255,255,.18);
                        color:white;
                        padding:6px 12px;
                        border-radius:50px;
                        font-size:11px;
                        font-weight:700;
                    ">
                        {{ $t->pelanggan->alamat }}
                    </span>

                </div>
            </div>

            {{-- TOTAL --}}
            <div style="
                background:#f0fdf4;
                padding:18px 28px;
                border-bottom:1px solid #dcfce7;
            ">
                <div class="d-flex justify-content-between align-items-center">
                    <span style="font-size:12px;color:#666;font-weight:700;">
                        TOTAL TAGIHAN
                    </span>

                    <span style="
                        font-size:26px;
                        font-weight:900;
                        color:#15803d;
                    ">
                        Rp {{ number_format($t->total,0,',','.') }}
                    </span>
                </div>
            </div>

            {{-- BODY --}}
            <div class="p-4">

                <div class="row g-3">

                    <div class="col-12">
                        <div class="p-3 rounded-4 border bg-light">
                            <small class="text-muted d-block mb-1">Tanggal Tagihan</small>
                            <div class="fw-bold">
                                {{ \Carbon\Carbon::parse($t->tanggal)->format('d F Y') }}
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="p-3 rounded-4 border bg-light">
                            <small class="text-muted d-block mb-1">Nama Paket</small>
                            <div class="fw-bold">
                                {{ $t->pelanggan->layanan->nama_paket ?? '-' }}
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="p-3 rounded-4 border bg-light">
                            <small class="text-muted d-block mb-1">Status Pembayaran</small>

                            @if($t->status == 'lunas')
                                <span class="badge bg-success px-3 py-2 rounded-pill">
                                    Sudah Dibayar
                                </span>
                            @else
                                <span class="badge bg-danger px-3 py-2 rounded-pill">
                                    Belum Dibayar
                                </span>
                            @endif
                        </div>
                    </div>

                </div>

                <button type="button"
                        class="btn btn-success w-100 mt-4 py-2 rounded-4 fw-bold"
                        data-bs-dismiss="modal">
                    Tutup
                </button>

            </div>

        </div> 

    </div>
</div>
@endforeach
@foreach($tagihan as $t)
<div class="modal fade" id="editTagihan{{ $t->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 overflow-hidden"
             style="border-radius:24px; box-shadow:0 25px 70px rgba(0,0,0,.18);">

            <form action="{{ url('/tagihan/'.$t->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- HEADER -->
                <div style="
                    background:linear-gradient(135deg,#16a34a,#22c55e,#4ade80);
                    padding:24px;
                    color:white;
                ">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-pencil-square me-2"></i> Edit Tagihan
                    </h5>
                    <small style="opacity:.8;">Perbarui data tagihan pelanggan</small>
                </div>

                <!-- BODY -->
                <div class="p-4">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal</label>
                        <input type="date" name="tanggal"
                               class="form-control rounded-3"
                               value="{{ $t->tanggal }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jumlah Tagihan</label>
                        <input type="number" name="total"
                               class="form-control rounded-3"
                               value="{{ $t->total }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jenis Tagihan</label>
                        <select name="jenis_tagihan" class="form-control rounded-3">
                            <option value="tagihan internet bulanan" {{ $t->jenis_tagihan=='tagihan internet bulanan' ? 'selected' : '' }}>Tagihan Internet Bulanan</option>
                            <option value="tagihan instalasi" {{ $t->jenis_tagihan=='tagihan instalasi' ? 'selected' : '' }}>Tagihan Instalasi</option>
                            <option value="tagihan penjualan alat" {{ $t->jenis_tagihan=='tagihan penjualan alat' ? 'selected' : '' }}>Tagihan Penjualan Alat</option>
                            <option value="pendapatan jasa" {{ $t->jenis_tagihan=='pendapatan jasa' ? 'selected' : '' }}>Pendapatan Jasa</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keterangan</label>
                        <textarea name="keterangan"
                                  class="form-control rounded-3"
                                  rows="3">{{ $t->keterangan }}</textarea>
                    </div>

                    <input type="hidden" name="layanan_id" value="{{ $t->layanan_id }}">

                </div>

                <!-- FOOTER -->
                <div class="px-4 pb-4 d-flex justify-content-end gap-2">
                    <button type="button"
                            class="btn btn-light border rounded-3"
                            data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit"
                            class="btn text-white rounded-3"
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
</body>
</html>