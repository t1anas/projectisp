<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pelanggan - Jagonet</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('inputform.css') }}">

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
    <div class="main-content">

        {{-- TOPBAR --}}
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

        {{-- OUTER CARD --}}
        <div class="form-card">

            {{-- HEADER CARD --}}
            <div class="form-card-header">
                <div class="icon-wrap">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <div class="form-card-title">Data Pelanggan</div>
                    <div class="form-card-sub">List pelanggan yang sudah terdaftar</div>
                </div>
            </div>

            <div class="card-toolbar">
    <form method="GET" action="{{ url('/pelanggan') }}">
        <div class="d-flex align-items-center gap-2 flex-wrap">

            <a href="/instalasi" class="btn btn-sm" style="height:34px; display:inline-flex; align-items:center; gap:5px; white-space:nowrap; background:linear-gradient(135deg,#09973B,#0ab844); color:#fff; border:none;">
                <i class="bi bi-plus-lg"></i> Tambah Pelanggan
            </a>

            <input type="text" name="search"
                   class="form-control form-control-sm"
                   style="width:180px; height:40px;"
                   placeholder="Cari nama..."
                   value="{{ request('search') }}">

            <select name="status" class="form-select form-select-sm" style="width:140px; height:40px;">
                <option value="">Semua Status</option>
                <option value="aktif"    {{ request('status') === 'aktif'    ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>Pending</option>
            </select>

            <input type="date" name="dari"
                   class="form-control form-control-sm"
                   style="width:145px; height:40px;"
                   value="{{ request('dari') }}">

            <input type="date" name="sampai"
                   class="form-control form-control-sm"
                   style="width:145px; height:40px;"
                   value="{{ request('sampai') }}">

            <button type="submit" class="btn btn-success btn-sm px-3" style="height:40px;">
                <i class="bi bi-search"></i>
            </button>

            @if(request()->hasAny(['search','status','dari','sampai']))
                <a href="{{ url('/pelanggan') }}" class="btn btn-outline-secondary btn-sm px-3" style="height:40px; display:inline-flex; align-items:center;">
                    <i class="bi bi-x-lg"></i>
                </a>
            @endif

        </div>
    </form>
</div>

            {{-- INNER CARD --}}
            <div class="table-card">
                <div class="table-card-scroll">
                    <table class="table table-bordered align-middle custom-table mb-0">
                        <thead class="table-light text-center fw-bold">
                            <tr>
                                <th>No</th>
                                <th>Tgl Aktivasi</th>
                                <th>Nama</th>
                                <th>Site</th>
                                <th>Layanan</th>
                                <th>No. Telepon</th>
                                <th>Alamat</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @forelse($pelanggan as $index => $p)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($p->created_at)->format('d/m/Y') }}</td>
                                <td class="text-start"><div class="clamp-2">{{ $p->nama }}</div></td>
                                <td>{{ $p->site->nama_site ?? '-' }}</td>
                                <td>{{ $p->layanan->nama_paket ?? '-' }}</td>
                                <td>{{ $p->no_hp ?? '-' }}</td>
                                <td class="text-start">
                                    <div class="clamp-2">{{ $p->alamat ?? '-' }}</div>
                                </td>

                                <td>
                                    @if(strtolower($p->status) === 'aktif')
                                    <span class="badge rounded-pill bg-success">
                                        <i class="bi bi-check-circle-fill"></i> Aktif
                                    </span>
                                    @elseif(strtolower($p->status) === 'pending')
                                    <span class="badge rounded-pill bg-warning text-dark">
                                        <i class="bi bi-hourglass-split"></i> Pending
                                    </span>
                                    @else
                                    <span class="badge rounded-pill bg-danger">
                                        <i class="bi bi-x-circle-fill"></i> Nonaktif
                                    </span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1 flex-nowrap">

                                        <a href="{{ url('/pelanggan/detail/'.$p->id) }}" class="btn btn-info btn-sm px-2">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <button type="button"
                                                class="btn btn-warning btn-sm px-2"
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
                                            <button type="submit" class="btn btn-danger btn-sm px-2" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-5" style="color:#9ca3af; font-size:14px;">
                                    <i class="bi bi-inbox" style="font-size:30px; display:block; margin-bottom:8px;"></i>
                                    Tidak ada data yang ditemukan
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- END INNER CARD --}}

        </div>
        {{-- END OUTER CARD --}}

    </div>
    {{-- END MAIN CONTENT --}}

</div>


{{-- MODAL UPDATE --}}
{{-- MODAL UPDATE --}}
@foreach($pelanggan as $pel)
<div class="modal fade" id="editModal{{ $pel->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <form action="{{ url('/pelanggan/'.$pel->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-header"
                     style="background:linear-gradient(135deg,#28a745,#20c157); color:#fff;">
                    <h5 class="modal-title">Update Pelanggan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Nama</label>
                            <input type="text" name="nama" class="form-control" value="{{ $pel->nama }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">No Telepon</label>
                            <input type="text" name="no_hp" class="form-control" value="{{ $pel->no_hp }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="aktif"    @selected(strtolower($pel->status) == 'aktif')>Aktif</option>
                                <option value="nonaktif" @selected(strtolower($pel->status) == 'nonaktif')>Non-Aktif</option>
                                <option value="pending"  @selected(strtolower($pel->status) == 'pending')>Pending</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Site</label>
                            <select name="site_id" class="form-select" required>
                                @foreach($site as $s)
                                <option value="{{ $s->id }}" {{ $pel->site_id == $s->id ? 'selected' : '' }}>
                                    {{ $s->nama_site }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Layanan</label>
                            <select name="layanan_id" class="form-select" required>
                                @foreach($layanan as $l)
                                <option value="{{ $l->id }}" {{ $pel->layanan_id == $l->id ? 'selected' : '' }}>
                                    {{ $l->nama_paket }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" class="form-control" rows="3"
                                      placeholder="Masukkan alamat lengkap">{{ $pel->alamat }}</textarea>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
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