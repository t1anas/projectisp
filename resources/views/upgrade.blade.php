<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upgrade / Downgrade Layanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('inputform.css') }}">
</head>
<body>

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
        <a href="{{ route('agenda.cs') }}" class="menu-item">
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

    {{-- ── MAIN CONTENT ── --}}
    <div class="main-content">
        <div class="content-wrapper" style="max-width: 100%;">
    <div class="d-flex align-items-center gap-3" style="margin-bottom: 16px;">
        <button type="button" class="btn-sidebar-toggle d-lg-none" onclick="toggleSidebar()">
            <i class="bi bi-list"></i>
        </button>
    </div>

            <div class="form-card">

                {{-- ── HEADER ── --}}
                <div class="form-card-header">
                    <a href="{{ url('/pelanggan') }}" class="btn-kembali">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <div class="icon-wrap">
                        <i class="bi bi-arrow-left-right"></i>
                    </div>
                    <div>
                        <p class="form-card-title">Upgrade / Downgrade Layanan</p>
                        <p class="form-card-sub">Perubahan paket layanan pelanggan</p>
                    </div>
                </div>

                {{-- ── FORM ── --}}
                <form action="{{ route('upgrade.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="pelanggan_id" value="{{ $pelanggan->id }}">

                    <div class="form-body">

                        <div class="form-section-label">
                            <i class="bi bi-person"></i> Data Pelanggan
                        </div>

                        <div class="row-2 mb-4">
                            <div>
                                <label class="form-label">Nama Pelanggan</label>
                                <input type="text"
                                       class="form-control"
                                       value="{{ $pelanggan->nama }}"
                                       readonly>
                            </div>
                            <div>
                                <label class="form-label">ID Pelanggan</label>
                                <input type="text"
                                       class="form-control"
                                       value="{{ $pelanggan->id }}"
                                       readonly>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Paket Saat Ini</label>
                            <div class="paket-box">
                                <i class="bi bi-wifi" style="color: #0f9d58; font-size: 18px; flex-shrink: 0;"></i>
                                <span class="paket-name">{{ $pelanggan->layanan->nama_paket }}</span>
                                <span class="paket-badge">Aktif</span>
                            </div>
                        </div>

                        <div class="form-section-label" style="margin-top: 24px;">
                            <i class="bi bi-box-seam"></i> Paket Baru
                        </div>

                        <div class="row-2 mb-4">
                            <div>
                                <label class="form-label">
                                    Pilih Paket <span class="required-star">*</span>
                                </label>
                                <div class="select-wrap">
                                    <select name="layanan_baru_id" class="form-select" required>
                                        <option value="">-- Pilih Paket --</option>
                                        @foreach ($layanan as $item)
                                            @if ($item->id != $pelanggan->layanan_id)
                                                <option value="{{ $item->id }}">
                                                    {{ $item->nama_paket }}
                                                    — Rp {{ number_format($item->harga, 0, ',', '.') }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <span class="chev"><i class="bi bi-chevron-down"></i></span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4" style="grid-column: 1 / -1;">
                            <label class="form-label">Catatan</label>
                            <textarea name="catatan"
                                      class="form-control"
                                      style="width: 100%;"
                                      rows="4"
                                      placeholder="Alasan upgrade / downgrade..."></textarea>
                        </div>

                        @if ($errors->any())
                            <div class="mb-4">
                                @foreach ($errors->all() as $error)
                                    <p style="color: red; font-size: 13px;">{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif

                    </div>

                    {{-- ── FOOTER ── --}}
                    <div class="form-footer">
                        <a href="{{ url('/pelanggan') }}" class="btn-cancel">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn-save">
                            <i class="bi bi-check-lg"></i> Ajukan
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleSidebar() {
        document.getElementById('appSidebar').classList.toggle('show');
        document.getElementById('sidebarOverlay').classList.toggle('show');
    }
</script>
</body>
</html>