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
            <i class="bi bi-router"></i> Instalasi Baru
        </a>

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
    <div class="main-content" style="flex:1; min-width:0; overflow-x:hidden;">

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

            <div class="table-responsive px-3 pb-4" style="overflow-x: auto;">
                <table class="table table-bordered table-hover align-middle" style="min-width: 900px;">

                    <thead class="text-center fw-bold" style="background:#1e293b; color:#9fa6af;">
                        <tr>
                            <th>No</th>
                            <th>Tgl Bayar</th>
                            <th>Tgl Tagihan</th>
                            <th>Nama</th>
                            <th>Paket</th>
                            <th style="white-space: nowrap;">Jumlah Bayar</th>
                            <th>Metode</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($pembayaran as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">
                                    {{ \Carbon\Carbon::parse($item->tanggal_bayar)->translatedFormat('d M Y') }}
                                </td>
                                <td class="text-center">
                                    @if($item->tagihan && $item->tagihan->tanggal)
                                        {{ \Carbon\Carbon::parse($item->tagihan->tanggal)->translatedFormat('d M Y') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="fw-semibold">{{ $item->pelanggan->nama ?? '-' }}</td>
                                <td class="text-center" style="white-space: nowrap;">
                                    {{ $item->layanan->nama_paket ?? '-' }}
                                </td>
                                <td class="fw-bold text-center" style="white-space: nowrap;">
                                    Rp {{ number_format($item->jumlah_bayar, 0, ',', '.') }}
                                </td>
                                <td class="text-center" style="white-space: nowrap;">
                                    {{ $item->metode->nama_metode ?? '-' }}
                                </td>
                                <td class="text-center">{{ $item->tagihan->jenis_tagihan ?? '-' }}</td>
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
                                        <button type="button"
        data-bs-toggle="modal"
        data-bs-target="#modalHapusPembayaran{{ $item->id }}"
        style="border-radius:10px; width:34px; height:34px;
               background:linear-gradient(135deg,#fff1f1,#ffe1e1);
               color:#dc3545; border:none; cursor:pointer;">
    <i class="bi bi-trash"></i>
</button>
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

<!-- MODAL TAMBAH PEMBAYARAN -->
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
                                    @php
                                        $tagihanBelumBayar = $p->tagihan
                                            ->where('status', '!=', 'lunas')
                                            ->map(fn($t) => [
                                                'id'      => $t->id,
                                                'jenis'   => $t->jenis_tagihan,
                                                'tanggal' => $t->tanggal,
                                                'nominal' => $t->total - $t->pembayaran->sum('jumlah_bayar'),
                                            ])->values();
                                    @endphp

                                    <option
                                        value="{{ $p->id }}"
                                        data-layanan="{{ $p->layanan_id }}"
                                        data-paket="{{ $p->layanan->nama_paket ?? '' }}"
                                        data-harga="{{ $p->layanan->harga ?? 0 }}"
                                        data-tagihan='{{ $tagihanBelumBayar->toJson() }}'>
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
                                       class="form-control"
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

<!-- MODAL EDIT PEMBAYARAN -->
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

{{-- MODAL HAPUS PEMBAYARAN --}}
@foreach($pembayaran as $item)
<div class="modal fade" id="modalHapusPembayaran{{ $item->id }}" tabindex="-1" aria-hidden="true">
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
                    <div style="font-weight:700; font-size:15px; color:#b91c1c;">Hapus Pembayaran</div>
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
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div>
                        <div style="font-size:14px; font-weight:700; color:#111827;">
                            {{ $item->pelanggan->nama ?? '-' }}
                        </div>
                        <div style="font-size:12px; color:#6b7280; margin-top:2px;">
                            {{ \Carbon\Carbon::parse($item->tanggal_bayar)->translatedFormat('d F Y') }}
                            &middot;
                            <strong style="color:#dc2626;">
                                Rp {{ number_format($item->jumlah_bayar, 0, ',', '.') }}
                            </strong>
                            &middot; {{ $item->metode->nama_metode ?? '-' }}
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
                        Data pembayaran ini akan <strong>dihapus permanen</strong>
                        dan status tagihan terkait akan ikut berubah.
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
                          action="{{ route('pembayaran.destroy', $item->id) }}"
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const selPelanggan = document.getElementById('pelanggan');
    const tagihanList  = document.getElementById('tagihan_list');
    const tagihanPH    = document.getElementById('tagihan_placeholder');
    const tagihanEmpty = document.getElementById('tagihan_empty');

    if (selPelanggan) {
        selPelanggan.addEventListener('change', function () {
            const opt     = this.options[this.selectedIndex];
            const layanan = opt.getAttribute('data-layanan') || '';
            const paket   = opt.getAttribute('data-paket')   || '';
            const harga   = opt.getAttribute('data-harga')   || 0;
            let tagihans  = [];

            try { tagihans = JSON.parse(opt.getAttribute('data-tagihan') || '[]'); }
            catch(e) { tagihans = []; }

            document.getElementById('layanan_id').value = layanan;
            document.getElementById('paket_view').value = paket;

            document.getElementById('tagihan_id').value   = '';
            document.getElementById('jumlah_bayar').value = '';
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