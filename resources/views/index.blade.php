<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pelanggan - Jagonet</title>

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

        <a href="{{ url('/pelanggan') }}" class="menu-item active">
            <i class="bi bi-people"></i> Data Pelanggan
        </a>

        <!-- PROFILE -->
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
    <!-- END SIDEBAR -->

    <!-- MAIN CONTENT -->
    <div class="main-content" style="flex:1;">

        <!-- TOPBAR -->
        <div class="topbar">
            <div>
                <div class="page-title">Data Pelanggan</div>
                <div class="page-sub">Daftar seluruh pelanggan terdaftar</div>
            </div>
            <div class="breadcrumb-area">
                <i class="bi bi-house-door"></i>
                <span class="sep">/</span>
                <span>Pelanggan</span>
                <span class="sep">/</span>
                <span class="current">Data</span>
            </div>
        </div>

        <!-- CARD TABLE -->
        <div class="form-card">

            <!-- HEADER -->
            <div class="form-card-header">
                <div class="icon-wrap">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <div class="form-card-title">Data Pelanggan</div>
                    <div class="form-card-sub">List pelanggan yang sudah terdaftar</div>
                </div>
            </div>

            <!-- TOMBOL TAMBAH -->
            <div style="padding:20px;">
                <a href="/instalasi" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg"></i> Tambah Pelanggan
                </a>
            </div>

<!-- TABLE - VERSION 1: Menggunakan Bootstrap class -->
<div class="table-responsive" style="margin: 0 0 20px; padding: 0 20px;">
    <table class="table table-bordered align-middle custom-table" style="margin:0; min-width:1400px;">
        <thead class="table-light text-center fw-bold">
            <tr>
                <th>No</th>
                <th>Tgl Aktivasi</th>
                <th>Nama</th>
                <th>NIK</th>
                <th>Site</th>
                <th>Layanan</th>
                <th>No. Telepon</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody class="text-center">
            @foreach($pelanggan as $index => $p)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($p->created_at)->format('d/m/Y') }}</td>
                <td class="text-start">{{ $p->nama }}</td>
                <td>{{ $p->nik ?? '-' }}</td>
                <td>{{ $p->site->nama_site ?? '-' }}</td>
                <td>{{ $p->layanan->nama_paket ?? '-' }}</td>
                <td>{{ $p->no_hp ?? '-' }}</td>
                
                @php $aktif = strtolower($p->status) == 'aktif'; @endphp
                
                <td>
                    <span class="badge rounded-pill {{ $aktif ? 'bg-success' : 'bg-danger' }}">
                        <i class="bi bi-{{ $aktif ? 'check' : 'x' }}-circle-fill"></i>
                        {{ $aktif ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                
                <td class="text-center">
                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                        <button type="button"
                                class="btn btn-warning btn-sm px-3"
                                data-bs-toggle="modal"
                                data-bs-target="#editModal{{ $p->id }}"
                                title="Update">
                            <i class="bi bi-pencil-square"></i>
                        </button>

                        <form action="{{ url('/pelanggan/'.$p->id) }}"
                              method="POST"
                              style="display:inline-block;"
                              onsubmit="return confirm('Yakin hapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="btn btn-danger btn-sm px-3"
                                    title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
            <!-- END TABLE -->

        </div>
        <!-- END CARD -->

    </div>
    <!-- END MAIN CONTENT -->

</div>
<!-- END WRAPPER -->


{{-- =============================================
     MODAL UPDATE
     ============================================= --}}
@foreach($pelanggan as $p)
<div class="modal fade" id="editModal{{ $p->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <form action="{{ url('/pelanggan/'.$p->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-header"
                     style="background:linear-gradient(135deg,#28a745,#20c157); color:#fff;">
                    <h5 class="modal-title">Update Pelanggan</h5>
                    <button type="button" class="btn-close btn-close-white"
                            data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Nama</label>
                            <input type="text" name="nama" class="form-control"
                                   value="{{ $p->nama }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">NIK</label>
                            <input type="text" name="nik" class="form-control"
                                   value="{{ $p->nik }}" maxlength="16"
                                   placeholder="16 digit NIK">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">No Telepon</label>
                            <input type="text" name="no_hp" class="form-control"
                                   value="{{ $p->no_hp }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="aktif"    {{ $p->status == 'aktif'    ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ $p->status == 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Site</label>
                            <select name="site_id" class="form-select" required>
                                @foreach($site as $s)
                                <option value="{{ $s->id }}"
                                    {{ $p->site_id == $s->id ? 'selected' : '' }}>
                                    {{ $s->nama_site }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Layanan</label>
                            <select name="layanan_id" class="form-select" required>
                                @foreach($layanan as $l)
                                <option value="{{ $l->id }}"
                                    {{ $p->layanan_id == $l->id ? 'selected' : '' }}>
                                    {{ $l->nama_paket }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        Simpan Perubahan
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
@endforeach
{{-- END MODAL --}}

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>