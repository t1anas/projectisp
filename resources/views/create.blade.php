<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pelanggan - Jagonet</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Ganti path sesuai lokasi file CSS kamu --}}
    <link rel="stylesheet" href="{{ asset('inputform.css') }}">
</head>
<body>

<div style="display:flex; min-height:100vh;">

    <!-- ─── SIDEBAR ─── -->
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="hamburger"><span></span><span></span><span></span></div>
            <span class="logo-text">JAGONET</span>
        </div>

        <div class="section-label">Main Board</div>

        <a href="{{ url('/cs/cs') }}" class="menu-item">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="{{ url('/layanan') }}" class="menu-item">
            <i class="bi bi-wifi"></i> Data Layanan
        </a>
        <a href="{{ url('/instalasi') }}" class="menu-item active">
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

        <!-- Profile -->
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

    <!-- ─── MAIN CONTENT ─── -->
    <div class="main-content" style="flex:1;">

        <!-- Topbar -->
        <div class="topbar">
            <div>
                <div class="page-title">Tambah Pelanggan</div>
                <div class="page-sub">Isi data pelanggan baru di bawah ini</div>
            </div>
            <div class="breadcrumb-area">
                <i class="bi bi-house-door"></i>
                <span class="sep">/</span>
                <span>Data Pelanggan</span>
                <span class="sep">/</span>
                <span class="current">Form Input</span>
            </div>
        </div>

        <!-- Form Card -->
        <div class="form-card">

            <!-- Header -->
            <div class="form-card-header">
                <div class="icon-wrap">
                    <i class="bi bi-person-plus-fill"></i>
                </div>
                <div>
                    <div class="form-card-title">Form Input Pelanggan</div>
                    <div class="form-card-sub">Lengkapi semua field yang diperlukan</div>
                </div>
            </div>

            <!-- Body -->
            <form action="{{ url('/pelanggan/store') }}" method="POST">
            @csrf

            <div class="form-body">

                <!-- Informasi Pribadi -->
                <div class="form-section-label">
                    <i class="bi bi-person-vcard"></i> Informasi Pribadi
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Nama Lengkap <span class="required-star">*</span></label>
                        <div class="input-icon-wrap">
                            <i class="bi bi-person icon"></i>
                            <input type="text" name="nama" class="form-control" placeholder="Masukkan nama lengkap" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">No HP</label>
                        <div class="input-icon-wrap">
                            <i class="bi bi-telephone icon"></i>
                            <input type="text" name="no_hp" class="form-control" placeholder="08xx-xxxx-xxxx">
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control" placeholder="Masukkan alamat lengkap pelanggan"></textarea>
                    </div>
                </div>

                <!-- Informasi Layanan -->
                <div class="form-section-label">
                    <i class="bi bi-wifi"></i> Informasi Layanan
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Site <span class="required-star">*</span></label>
                        <select name="site_id" class="form-select">
                            <option value="" disabled selected>— Pilih Site —</option>
                            @foreach($site as $s)
                                <option value="{{ $s->id }}">{{ $s->nama_site }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">NIK</label>
                        <div class="input-icon-wrap">
                            <i class="bi bi-hash icon"></i>
                            <input type="char" name="nik" class="form-control" placeholder="contoh: 3500xxxx">
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Layanan / Paket <span class="required-star">*</span></label>
                        <select name="layanan_id" class="form-select">
                            <option value="" disabled selected>— Pilih Paket Layanan —</option>
                            @foreach($layanan as $l)
                                <option value="{{ $l->id }}">{{ $l->nama_paket }} — {{ $l->harga }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Lokasi -->
                <div class="form-section-label">
                    <i class="bi bi-geo-alt"></i> Lokasi
                </div>

                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Link Lokasi (Google Maps)</label>
                        <div class="input-icon-wrap">
                            <i class="bi bi-pin-map icon"></i>
                            <input type="text" name="lokasi_link" class="form-control" placeholder="https://maps.google.com/...">
                        </div>
                    </div>
                </div>

            </div>

            <!-- Footer -->
            <div class="form-footer">
                <a href="{{ url('/pelanggan') }}" class="btn-cancel">
                    <i class="bi bi-x-lg" style="font-size:11px;"></i> Batal
                </a>
                <button type="submit" class="btn-save">
                    <i class="bi bi-check-lg"></i> Simpan Pelanggan
                </button>
            </div>

            </form>
        </div>
        <!-- /form-card -->

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>