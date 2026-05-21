<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pemasukan - Jagonet</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('inputform.css') }}">

<style>
body{
    font-family:'Plus Jakarta Sans',sans-serif;
    background:#f4f6f9;
}
.filter-box{
    padding:20px;
    border-bottom:1px solid #eee;
}
.table td,.table th{
    vertical-align:middle;
}
.action-btn{
    width:32px;
    height:32px;
    border:none;
    border-radius:8px;
}

/* === Tagihan card pilihan === */
.tagihan-card {
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 12px 16px;
    cursor: pointer;
    transition: all .2s;
    background: #fff;
    display: flex;
    align-items: center;
    gap: 12px;
}
.tagihan-card:hover {
    border-color: #22c55e;
    background: #f0fdf4;
}
.tagihan-card.selected {
    border-color: #16a34a;
    background: linear-gradient(135deg,#f0fdf4,#dcfce7);
    box-shadow: 0 0 0 3px rgba(34,197,94,.15);
}
.tagihan-card .tag-icon {
    width: 38px; height: 38px;
    border-radius: 10px;
    background: #e8fff1;
    color: #16a34a;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}
.tagihan-card.selected .tag-icon {
    background: #16a34a;
    color: #fff;
}
.tagihan-card .tag-jenis {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: #64748b;
}
.tagihan-card.selected .tag-jenis { color: #15803d; }
.tagihan-card .tag-tanggal {
    font-size: 13px;
    font-weight: 600;
    color: #1e293b;
}
.tagihan-card .tag-nominal {
    margin-left: auto;
    font-size: 14px;
    font-weight: 700;
    color: #16a34a;
}
.tagihan-card .tag-check {
    font-size: 18px;
    color: #16a34a;
    display: none;
}
.tagihan-card.selected .tag-check { display: inline; }
.badge-status {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 12px;
    border-radius: 50px;
    font-size: 12px;
    font-weight: 700;
}
.badge-lunas {
    background: linear-gradient(135deg,#e8fff1,#d8ffe8);
    color: #0f9d58;
    border: 1px solid #b7f3cd;
}
.badge-belum-lunas {
    background: linear-gradient(135deg,#fffbeb,#fef3c7);
    color: #d97706;
    border: 1px solid #fde68a;
}
.badge-belum-bayar {
    background: linear-gradient(135deg,#fff1f1,#ffe1e1);
    color: #dc3545;
    border: 1px solid #ffc4c4;
}
</style>
</head>

<body>
<div style="display:flex; min-height:100vh;">

<!-- SIDEBAR -->
<div class="sidebar">

    <div class="sidebar-header">
        <div class="hamburger">
            <span></span><span></span><span></span>
        </div>
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
    <i class="bi bi-router"></i> Instalasi Baru</a>

        @if(Auth::user()->role == 'admin')
            <a href="{{ url('/pemasukan') }}" class="menu-item active">
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

<!-- MAIN CONTENT -->
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
            <div class="page-title">Pemasukan</div>
            <div class="page-sub">Kelola transaksi pembayaran pelanggan</div>
        </div>

        <div class="breadcrumb-area">
            <i class="bi bi-house-door"></i>
            <span class="sep">/</span>
            <span>Keuangan</span>
            <span class="sep">/</span>
            <span class="current">Pemasukan</span>
        </div>

    </div>

    <div class="form-card">

        <div class="form-card-header">

            <div class="icon-wrap">
                <i class="bi bi-cash-stack"></i>
            </div>

            <div>
                <div class="form-card-title">Data Pemasukan</div>
                <div class="form-card-sub">Riwayat pembayaran pelanggan internet</div>
            </div>

        </div>

        <form method="GET" action="{{ url('/pembayaran') }}">
            <div class="filter-box">

                <div class="row g-3">

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Tanggal Awal</label>
                        <input type="date" name="tgl_awal" class="form-control"
                               value="{{ request('tgl_awal') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Tanggal Akhir</label>
                        <input type="date" name="tgl_akhir" class="form-control"
                               value="{{ request('tgl_akhir') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label invisible">Metode</label>
                        <select name="metode" class="form-select">
                            <option value="">Semua Metode</option>
                            @foreach ($metode as $m)
                                <option value="{{ $m->id }}"
                                    {{ request('metode') == $m->id ? 'selected' : '' }}>
                                    {{ $m->nama_metode }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label invisible">Cari</label>
                        <input type="text" name="cari" class="form-control"
                               placeholder="Cari pelanggan..."
                               value="{{ request('cari') }}">
                    </div>

                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>

                </div>

            </div>
        </form>

        <div style="padding:20px 20px 15px;">

            <button type="button" class="btn btn-success btn-sm"
                data-bs-toggle="modal"
                data-bs-target="#modalPembayaran">
                <i class="bi bi-plus-lg"></i> Tambah Pembayaran
            </button>

            <a href="#" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-download"></i> Export
            </a>

        </div>

        <div class="table-responsive px-3 pb-4">

            <table class="table table-bordered table-hover align-middle">

                <thead class="text-center fw-bold" style="background:#1e293b; color:#cbd5e1;">
                    <tr>
                        <th style="padding:13px 10px;">No</th>
                        <th style="padding:13px 10px;">Tgl Bayar</th>
                        <th style="padding:13px 10px;">Tgl Tagihan</th>
                        <th style="padding:13px 10px;">Nama</th>
                        <th style="padding:13px 10px;">Paket</th>
                        <th style="padding:13px 10px;">Tagihan</th>
                        <th style="padding:13px 10px;">Metode</th>
                        <th style="padding:13px 10px;">Status</th>
                        <th style="padding:13px 10px;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($pembayaran as $item)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">
                            <span style="display:inline-flex; align-items:center; gap:6px; padding:5px 12px;
                                         border-radius:50px; font-size:12px; font-weight:600;
                                         background:#eef2ff; color:#0c0b28; border:1px solid #dbeafe;">
                                <i class="bi bi-calendar3" style="font-size:11px;"></i>
                                {{ \Carbon\Carbon::parse($item->tanggal_bayar)->translatedFormat('d M Y') }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if($item->tagihan && $item->tagihan->tanggal)
                                <span style="display:inline-flex; align-items:center; gap:6px; padding:5px 12px;
                                             border-radius:50px; font-size:12px; font-weight:600;
                                             background:#fefce8; color:#854d0e; border:1px solid #fef08a;">
                                    <i class="bi bi-calendar-event" style="font-size:11px;"></i>
                                    {{ \Carbon\Carbon::parse($item->tagihan->tanggal)->translatedFormat('d M Y') }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="fw-semibold">{{ $item->pelanggan->nama ?? '-' }}</td>
                        <td class="text-center">{{ $item->layanan->nama_paket ?? '-' }}</td>
                        <td class="fw-bold text-center" style="color:#15803d;">
                            Rp {{ number_format($item->jumlah_bayar, 0, ',', '.') }}
                        </td>
                        <td class="text-center">{{ $item->metode->nama_metode ?? '-' }}</td>
                        <td class="text-center">
    @if ($item->status == 'lunas')
        <span class="badge-status badge-lunas">
            <i class="bi bi-check-circle-fill" style="font-size:10px;"></i> Lunas
        </span>
    @elseif ($item->status == 'belum lunas')
        <span class="badge-status badge-belum-lunas">
            <i class="bi bi-clock-fill" style="font-size:10px;"></i> Belum Lunas
        </span>
    @else
        <span class="badge-status badge-belum-bayar">
            <i class="bi bi-x-circle-fill" style="font-size:10px;"></i> Belum Bayar
        </span>
    @endif
</td>
                        <td class="text-center">
                            <div style="display:flex; align-items:center; gap:8px; justify-content:center;">
                                <button class="action-btn btn-warning"
                                    style="border-radius:10px; width:34px; height:34px;
                                           background:linear-gradient(135deg,#fff7e6,#ffecc2);
                                           color:#ff9800; border:none;"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditPembayaran"
                                    data-id="{{ $item->id }}"
                                    data-tanggal="{{ $item->tanggal_bayar }}"
                                    data-nama="{{ $item->pelanggan->nama ?? '-' }}"
                                    data-paket="{{ $item->layanan->nama_paket ?? '-' }}"
                                    data-jumlah="{{ $item->jumlah_bayar }}"
                                    data-metode="{{ $item->metode_id }}"
                                    data-status="{{ $item->status }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('pembayaran.destroy', $item->id) }}"
                                      method="POST" style="display:inline; margin:0;"
                                      onsubmit="return confirm('Yakin mau hapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        style="border-radius:10px; width:34px; height:34px;
                                               background:linear-gradient(135deg,#fff1f1,#ffe1e1);
                                               color:#dc3545; border:none;">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>

            <div class="px-3 pb-4">
                <div class="fw-light text-opacity-75">
                    Total:
                    Rp {{ number_format($total ?? 0,0,',','.') }}
                </div>
            </div>

        </div>

    </div>

</div>
</div>

<div class="modal fade" id="modalPembayaran" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="bi bi-cash-stack"></i> Tambah Pembayaran
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('pembayaran.store') }}" method="POST" id="formTambahPembayaran">
                @csrf

                <div class="modal-body">
                    <div class="row g-3">

                        {{-- Pelanggan --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Pelanggan</label>
                            <select name="pelanggan_id" id="pelanggan" class="form-select" required>
                                <option value="">-- Pilih Pelanggan --</option>
                                @foreach($pelanggan as $p)
                                    <option
                                        value="{{ $p->id }}"
                                        data-layanan="{{ $p->layanan_id }}"
                                        data-paket="{{ $p->layanan->nama_paket ?? '' }}"
                                        data-harga="{{ $p->layanan->harga ?? 0 }}"
                                        data-tagihan="{{ htmlspecialchars($p->tagihan->map(function($t) use ($p) { return ['id' => $t->id, 'jenis' => $t->jenis ?? 'Bulanan', 'tanggal' => $t->tanggal, 'nominal' => $t->nominal ?? ($p->layanan->harga ?? 0)]; })->toJson(), ENT_QUOTES) }}">
                                        {{ $p->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tanggal Bayar</label>
                            <input type="date" name="tanggal_bayar" id="tanggal_bayar"
                                   class="form-control" required
                                   value="{{ date('Y-m-d') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Paket</label>
                            <input type="hidden" name="layanan_id" id="layanan_id">
                            <input type="text" id="paket_view" class="form-control bg-light" readonly
                                   placeholder="Otomatis terisi saat pilih pelanggan">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jumlah Bayar</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light fw-semibold text-success">Rp</span>
                                <input type="number" name="jumlah_bayar" id="jumlah_bayar"
                                       class="form-control bg-light" readonly
                                       placeholder="Terisi otomatis">
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                Pilih Tagihan
                                <span class="text-danger">*</span>
                            </label>
                            <input type="hidden" name="tagihan_id" id="tagihan_id">

                            <div id="tagihan_placeholder"
                                 style="border:2px dashed #cbd5e1; border-radius:12px;
                                        padding:20px; text-align:center; color:#94a3b8;">
                                <i class="bi bi-receipt" style="font-size:28px; display:block; margin-bottom:6px;"></i>
                                Pilih pelanggan terlebih dahulu untuk melihat daftar tagihan
                            </div>

                            <div id="tagihan_list" class="row g-2" style="display:none;"></div>

                            <div id="tagihan_empty"
                                 style="display:none; border:2px dashed #fca5a5; border-radius:12px;
                                        padding:20px; text-align:center; color:#ef4444;">
                                <i class="bi bi-exclamation-circle" style="font-size:28px; display:block; margin-bottom:6px;"></i>
                                Tidak ada tagihan yang tersedia untuk pelanggan ini
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Metode Pembayaran</label>
                            <select name="metode_id" class="form-select" required>
                                <option value="">-- Pilih Metode --</option>
                                @foreach($metode as $m)
                                    <option value="{{ $m->id }}">{{ $m->nama_metode }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select">
                                <option value="lunas">Lunas</option>
                                <option value="belum">Belum</option>
                            </select>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditPembayaran" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="bi bi-pencil-square"></i> Edit Pembayaran
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form id="formEditPembayaran" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-body">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Nama Pelanggan</label>
                            <input type="text" id="edit_nama_pelanggan" class="form-control bg-light" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tanggal Bayar</label>
                            <input type="date" name="tanggal_bayar" id="edit_tanggal_bayar" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Paket</label>
                            <input type="text" id="edit_paket" class="form-control bg-light" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Jumlah Bayar</label>
                            <input type="number" name="jumlah_bayar" id="edit_jumlah_bayar" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Metode</label>
                            <select name="metode_id" id="edit_metode_id" class="form-select" required>
                                <option value="">-- Pilih Metode --</option>
                                @foreach($metode as $m)
                                    <option value="{{ $m->id }}">{{ $m->nama_metode }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" id="edit_status" class="form-select">
                                <option value="lunas">Lunas</option>
                                <option value="belum">Belum</option>
                            </select>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const selPelanggan   = document.getElementById('pelanggan');
    const tagihanList    = document.getElementById('tagihan_list');
    const tagihanPH      = document.getElementById('tagihan_placeholder');
    const tagihanEmpty   = document.getElementById('tagihan_empty');

    if (selPelanggan) {
        selPelanggan.addEventListener('change', function () {
            const opt      = this.options[this.selectedIndex];
            const layanan  = opt.getAttribute('data-layanan') || '';
            const paket    = opt.getAttribute('data-paket')   || '';
            const harga    = opt.getAttribute('data-harga')   || 0;
            let   tagihans = [];

            try { tagihans = JSON.parse(opt.getAttribute('data-tagihan') || '[]'); }
            catch(e) { tagihans = []; }

            document.getElementById('layanan_id').value  = layanan;
            document.getElementById('paket_view').value  = paket;

            document.getElementById('tagihan_id').value    = '';
            document.getElementById('jumlah_bayar').value  = '';
            tagihanList.innerHTML = '';

            if (!this.value) {
                tagihanPH.style.display    = '';
                tagihanList.style.display  = 'none';
                tagihanEmpty.style.display = 'none';
                return;
            }

            tagihanPH.style.display = 'none';

            if (tagihans.length === 0) {
                tagihanList.style.display  = 'none';
                tagihanEmpty.style.display = '';
                return;
            }

            tagihanList.style.display  = '';
            tagihanEmpty.style.display = 'none';

            tagihans.forEach(function(t) {
                const col = document.createElement('div');
                col.className = 'col-12 col-md-6';

                const tgl = t.tanggal
                    ? new Date(t.tanggal).toLocaleDateString('id-ID', {day:'2-digit',month:'long',year:'numeric'})
                    : '-';

                const nominal = parseInt(t.nominal || 0).toLocaleString('id-ID');

                col.innerHTML = `
                    <div class="tagihan-card" data-id="${t.id}" data-nominal="${t.nominal || 0}">
                        <div class="tag-icon"><i class="bi bi-receipt-cutoff"></i></div>
                        <div>
                            <div class="tag-jenis">${t.jenis || 'Tagihan'}</div>
                            <div class="tag-tanggal"><i class="bi bi-calendar3 me-1" style="font-size:11px;"></i>${tgl}</div>
                        </div>
                        <div class="tag-nominal">Rp ${nominal}</div>
                        <i class="bi bi-check-circle-fill tag-check"></i>
                    </div>`;

                col.querySelector('.tagihan-card').addEventListener('click', function () {
                    document.querySelectorAll('.tagihan-card').forEach(c => c.classList.remove('selected'));
                    this.classList.add('selected');

                    document.getElementById('tagihan_id').value   = this.getAttribute('data-id');
                    document.getElementById('jumlah_bayar').value = this.getAttribute('data-nominal');
                });

                tagihanList.appendChild(col);
            });
        });
    }

    const modalTambah = document.getElementById('modalPembayaran');
    if (modalTambah) {
        modalTambah.addEventListener('hidden.bs.modal', function () {
            document.getElementById('formTambahPembayaran').reset();
            document.getElementById('layanan_id').value   = '';
            document.getElementById('tagihan_id').value   = '';
            document.getElementById('paket_view').value   = '';
            document.getElementById('jumlah_bayar').value = '';
            tagihanList.innerHTML = '';
            tagihanList.style.display  = 'none';
            tagihanEmpty.style.display = 'none';
            tagihanPH.style.display    = '';
            document.getElementById('tanggal_bayar').value = new Date().toISOString().split('T')[0];
        });
    }

    const modalEdit = document.getElementById('modalEditPembayaran');
    if (modalEdit) {
        modalEdit.addEventListener('show.bs.modal', function (e) {
            const btn = e.relatedTarget;

            document.getElementById('formEditPembayaran').action =
                '/pembayaran/' + btn.getAttribute('data-id');

            document.getElementById('edit_nama_pelanggan').value =
                btn.getAttribute('data-nama');

            document.getElementById('edit_tanggal_bayar').value =
                btn.getAttribute('data-tanggal');

            document.getElementById('edit_paket').value =
                btn.getAttribute('data-paket');

            document.getElementById('edit_jumlah_bayar').value =
                btn.getAttribute('data-jumlah');

            document.getElementById('edit_metode_id').value =
                btn.getAttribute('data-metode');

            document.getElementById('edit_status').value =
                btn.getAttribute('data-status');
        });
    }

});
</script>
</body>
</html>