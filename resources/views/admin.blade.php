<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Jagonet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --jago-orange: #faf8f7;
            --jago-orange-dark: #ffffff;
            --jago-sidebar: linear-gradient(180deg, #09973B 0%, #FAE59E 100%);
            --jago-sidebar-hover: rgba(192,96,26,0.15);
            --jago-sidebar-active: rgba(192,96,26,0.25);
        }

        body { background: #f0f2f5; font-family: 'Segoe UI', sans-serif; margin: 0; min-height: 100vh; }

        .sidebar {
            width: 240px;
            min-height: 100vh;
            background: var(--jago-sidebar);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 20px 20px 16px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .logo-text { font-size: 20px; font-weight: 700; color: var(--jago-orange); letter-spacing: 2px; }
        .hamburger { cursor: pointer; display: flex; flex-direction: column; gap: 4px; }
        .hamburger span { width: 20px; height: 2px; background: white; border-radius: 2px; display: block; }

        .section-label {
            font-size: 10px; font-weight: 600; letter-spacing: 1.5px;
            color: rgba(255,255,255,0.35); text-transform: uppercase;
            padding: 16px 20px 6px;
        }

        .menu-item {
            display: flex; align-items: center; gap: 12px;
            padding: 11px 20px; color: rgba(255,255,255,0.7);
            font-size: 13.5px; cursor: pointer;
            transition: all 0.18s; margin: 2px 10px; border-radius: 8px;
            text-decoration: none;
        }
        .menu-item:hover { background: var(--jago-sidebar-hover); color: white; }
        .menu-item.active { background: var(--jago-sidebar-active); color: white; border-left: 3px solid var(--jago-orange); }
        .menu-item i { font-size: 16px; width: 20px; flex-shrink: 0; }

        .profile-section { margin-top: auto; padding: 16px 12px 12px; border-top: 1px solid rgba(255,255,255,0.08); }

        .admin-card {
            display: flex; align-items: center;
            gap: 10px; padding: 10px; background: rgb(216, 138, 49);
            border-radius: 10px; margin-bottom: 10px;
            border: 1px solid #333;
        }

        .admin-avatar {
            width: 36px; height: 36px; background: var(--jago-orange);
            border-radius: 50%; color: #09973B;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }

        .admin-role { font-size: 10px; color: var(--jago-orange); font-weight: 700; letter-spacing: 1px; }
        .admin-name { font-size: 13px; color: white; font-weight: 500; }

        .logout-btn {
            width: 100%; background: rgba(192,96,26,0.15);
            border: 1px solid rgba(192,96,26,0.4); color: #e8855a;
            font-size: 12px; font-weight: 600; letter-spacing: 1px;
            padding: 8px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            cursor: pointer; transition: all 0.18s;
        }
        .logout-btn:hover { background: rgba(192,96,26,0.3); color: white; }

        .main-content { margin-left: 240px; padding: 32px 28px; min-height: 100vh; }

        .topbar { display: flex; align-items: center; justify-content: space-between; margin-bottom: 28px; }
        .page-title { font-size: 22px; font-weight: 700; color: #1a1a2e; margin: 0; }
        .page-sub { font-size: 13px; color: #888; margin: 2px 0 0; }

        .scan-card {
            background: linear-gradient(135deg, #09973B 0%, #F2E39B 100%);
            border-radius: 16px;
            padding: 40px 36px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 48px;
        }

        .qr-box {
            width: 160px;
            height: 160px;
            background: rgba(255,255,255,0.12);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border: 2px dashed rgba(221, 216, 212, 0.5);
        }

        .scan-title { font-size: 19px; font-weight: 700; color: #ffffff; margin-bottom: 6px; }
        .scan-desc { font-size: 13.5px; color: #fdf8f8; margin-bottom: 20px; }

        .scan-btn {
            background: var(--jago-orange); border: none; color: #09973B;
            font-size: 13.5px; font-weight: 600; padding: 10px 28px; border-radius: 10px;
            cursor: pointer; display: inline-flex; align-items: center; gap: 8px;
            transition: all 0.18s; letter-spacing: 0.3px;
        }
        .scan-btn:hover { background: var(--jago-orange-dark); transform: translateY(-1px); }
    </style>
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

        <a href="{{ url('/dashboard') }}" class="menu-item active">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="{{ url('/layanan') }}" class="menu-item">
            <i class="bi bi-wifi"></i> Data Layanan
        </a>
        <a href="{{ url('/instalasi') }}" class="menu-item">
            <i class="bi bi-router"></i> Instalasi Baru
        </a>
        <a href="{{ url('/pemasukan') }}" class="menu-item">
            <i class="bi bi-wallet2"></i> Pemasukan
        </a>

        <div class="section-label">Pelanggan</div>

        <a href="{{ url('/pelanggan') }}" class="menu-item">
            <i class="bi bi-people"></i> Data Pelanggan
        </a>

        <!-- PROFILE -->
        <div class="profile-section">
            <div class="admin-card">
                <div class="admin-avatar">
                    <i class="bi bi-person-fill" style="color:white; font-size:18px;"></i>
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
        <div class="topbar">
            <div>
                <div class="page-title">Dashboard</div>
                <div class="page-sub">Selamat datang kembali, {{ Auth::user()->name }} 👋</div>
            </div>
            <div style="font-size:13px; color:#aaa;">
                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </div>
        </div>

        <div class="scan-card mb-4">
            <div class="qr-box">
                <svg width="110" height="110" viewBox="0 0 80 80" fill="none">
                    <rect x="4" y="4" width="28" height="28" rx="4" stroke="#c0601a" stroke-width="3.5"/>
                    <rect x="12" y="12" width="12" height="12" rx="1.5" fill="#c0601a"/>
                    <rect x="48" y="4" width="28" height="28" rx="4" stroke="#c0601a" stroke-width="3.5"/>
                    <rect x="56" y="12" width="12" height="12" rx="1.5" fill="#c0601a"/>
                    <rect x="4" y="48" width="28" height="28" rx="4" stroke="#c0601a" stroke-width="3.5"/>
                    <rect x="12" y="56" width="12" height="12" rx="1.5" fill="#c0601a"/>
                    <rect x="48" y="48" width="12" height="12" rx="1.5" fill="#c0601a"/>
                    <rect x="64" y="48" width="12" height="12" rx="1.5" fill="#c0601a"/>
                    <rect x="48" y="64" width="12" height="12" rx="1.5" fill="#c0601a"/>
                    <rect x="64" y="64" width="12" height="12" rx="1.5" fill="#c0601a"/>
                </svg>
            </div>
            <div>
                <div class="scan-title">Scan QR Pelanggan</div>
                <div class="scan-desc">Klik tombol berikut untuk memulai proses pembayaran pelanggan</div>
                <button class="scan-btn">
                    <i class="bi bi-qr-code-scan"></i> Scan Sekarang
                </button>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>