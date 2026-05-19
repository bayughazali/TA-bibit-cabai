<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Bibit Cabai</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --sidebar-width: 260px;
            --header-height: 64px;
            --green-dark: #15803d;
            --green-main: #16a34a;
            --green-light: #22c55e;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f1f5f2;
            overflow-x: hidden;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--green-dark) 0%, #14532d 100%);
            color: white;
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1050;
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 24px 20px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.12);
            flex-shrink: 0;
        }

        .sidebar-header .brand-icon {
            font-size: 2rem;
            margin-bottom: 6px;
            display: block;
        }

        .sidebar-header h3 {
            font-size: 1.35rem;
            font-weight: 700;
            margin-bottom: 2px;
            letter-spacing: -0.3px;
        }

        .sidebar-header .subtitle {
            opacity: 0.65;
            font-size: 0.78rem;
            margin-bottom: 6px;
        }

        .sidebar-header .admin-name {
            display: inline-block;
            background: rgba(255,255,255,0.15);
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 500;
        }

        .sidebar-menu {
            padding: 12px 0;
            flex: 1;
        }

        .menu-section-label {
            font-size: 0.68rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            opacity: 0.45;
            padding: 12px 20px 6px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: rgba(255,255,255,0.82);
            text-decoration: none;
            transition: all 0.2s;
            border-left: 3px solid transparent;
            font-size: 0.9rem;
            cursor: pointer;
        }

        .menu-item i {
            width: 18px;
            font-size: 0.95rem;
            flex-shrink: 0;
            text-align: center;
        }

        .menu-item:hover {
            background: rgba(255,255,255,0.1);
            color: white;
            text-decoration: none;
            border-left-color: rgba(255,255,255,0.5);
        }

        .menu-item.active {
            background: rgba(255,255,255,0.15);
            color: white;
            border-left-color: #fff;
            font-weight: 600;
        }

        .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid rgba(255,255,255,0.12);
            flex-shrink: 0;
        }

        .btn-logout {
            width: 100%;
            background: rgba(255,255,255,0.12);
            color: white;
            border: 1.5px solid rgba(255,255,255,0.3);
            padding: 9px 16px;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-logout:hover {
            background: rgba(255,255,255,0.22);
            border-color: rgba(255,255,255,0.5);
            color: white;
        }

        /* Sidebar Overlay (mobile) */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.45);
            z-index: 1040;
            backdrop-filter: blur(2px);
        }

        .sidebar-overlay.show { display: block; }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ===== TOPBAR ===== */
        .topbar {
            background: white;
            height: var(--header-height);
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 1px 0 #e5e7eb;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .hamburger-btn {
            display: none;
            background: none;
            border: none;
            color: #374151;
            font-size: 1.25rem;
            padding: 6px;
            cursor: pointer;
            border-radius: 6px;
            transition: background 0.15s;
        }

        .hamburger-btn:hover { background: #f3f4f6; }

        .topbar-title h2 {
            font-size: 1.15rem;
            font-weight: 700;
            color: #111827;
            margin: 0;
            line-height: 1.2;
        }

        .topbar-title p {
            font-size: 0.78rem;
            color: #6b7280;
            margin: 0;
        }

        .btn-back-website {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            color: white;
            border: none;
            padding: 9px 18px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.825rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .btn-back-website:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(220,38,38,0.35);
            color: white;
            text-decoration: none;
        }

        /* ===== CONTENT AREA ===== */
        .content-area {
            padding: 24px;
            flex: 1;
        }

        /* ===== STATS GRID ===== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: white;
            padding: 22px 20px;
            border-radius: 14px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 12px rgba(0,0,0,0.04);
            border-left: 4px solid;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        }

        .stat-card.products { border-left-color: #16a34a; }
        .stat-card.orders   { border-left-color: #2563eb; }
        .stat-card.revenue  { border-left-color: #d97706; }
        .stat-card.users    { border-left-color: #7c3aed; }

        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 4px;
            line-height: 1;
            letter-spacing: -0.5px;
        }

        .stat-label {
            color: #6b7280;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .stat-icon {
            position: absolute;
            top: 18px;
            right: 18px;
            font-size: 2rem;
            opacity: 0.12;
        }

        /* ===== CHART CARD ===== */
        .chart-card {
            background: white;
            border-radius: 14px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 12px rgba(0,0,0,0.04);
            overflow: hidden;
            margin-bottom: 28px;
        }

        .chart-card-header {
            background: linear-gradient(135deg, var(--green-main), var(--green-light));
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .chart-card-header h5 {
            color: white;
            font-size: 0.975rem;
            font-weight: 700;
            margin: 0;
        }

        .btn-lihat-detail {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 1.5px solid rgba(255,255,255,0.55);
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
        }

        .btn-lihat-detail:hover {
            background: white;
            color: var(--green-main);
            border-color: white;
            text-decoration: none;
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.12);
        }

        .chart-card-body {
            padding: 20px;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 48px 20px;
            color: #9ca3af;
        }

        .empty-state i { font-size: 3rem; margin-bottom: 16px; opacity: 0.35; display: block; }
        .empty-state h4 { font-size: 1.1rem; color: #6b7280; margin-bottom: 8px; }
        .empty-state p  { font-size: 0.875rem; margin-bottom: 16px; }

        .btn-buka-laporan {
            background: var(--green-main);
            color: white;
            border: none;
            padding: 9px 20px;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            transition: all 0.2s;
        }

        .btn-buka-laporan:hover {
            background: var(--green-dark);
            color: white;
            text-decoration: none;
            transform: translateY(-1px);
        }

        /* ===== RESPONSIVE ===== */

        /* Large tablets & small desktops */
        @media (max-width: 1100px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* Tablets (sidebar collapses) */
        @media (max-width: 900px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
                box-shadow: 4px 0 24px rgba(0,0,0,0.2);
            }

            .main-content {
                margin-left: 0;
            }

            .hamburger-btn {
                display: flex;
                align-items: center;
                justify-content: center;
            }
        }

        /* Mobile phones */
        @media (max-width: 600px) {
            .stats-grid {
                grid-template-columns: 1fr 1fr;
                gap: 12px;
            }

            .stat-card {
                padding: 16px 14px;
            }

            .stat-number {
                font-size: 1.6rem;
            }

            .stat-label {
                font-size: 0.78rem;
            }

            .stat-icon {
                font-size: 1.5rem;
            }

            .content-area {
                padding: 16px;
            }

            .topbar {
                padding: 0 16px;
            }

            .btn-back-website span {
                display: none;
            }

            .btn-back-website {
                padding: 9px 12px;
            }

            .chart-card-header h5 {
                font-size: 0.875rem;
            }
        }

        @media (max-width: 380px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <!-- Sidebar Overlay (mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <span class="brand-icon">🌱</span>
            <h3>Bibit Cabai</h3>
            <p class="subtitle">Admin Dashboard</p>
            <span class="admin-name">{{ Auth::user()->name ?? 'Admin User' }}</span>
        </div>

        <div class="sidebar-menu">
            <div class="menu-section-label">Menu Utama</div>

            <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.products.index') }}" class="menu-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="fas fa-seedling"></i>
                <span>Kelola Produk</span>
            </a>

            <a href="{{ route('admin.orders') }}" class="menu-item {{ request()->routeIs('admin.orders') ? 'active' : '' }}">
                <i class="fas fa-shopping-cart"></i>
                <span>Pesanan</span>
            </a>

            <a href="{{ route('admin.cancellations') }}" class="menu-item {{ request()->routeIs('admin.cancellations') ? 'active' : '' }}">
                <i class="fas fa-times-circle"></i>
                <span>Pengajuan Batal</span>
            </a>

            <a href="{{ route('admin.users') }}" class="menu-item {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span>Pengguna</span>
            </a>

            <a href="{{ route('admin.laporan') }}" class="menu-item {{ request()->routeIs('admin.laporan') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i>
                <span>Laporan</span>
            </a>
        </div>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="btn-logout" onclick="return confirm('Apakah Anda yakin ingin logout?')">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">

        <!-- Topbar -->
        <header class="topbar">
            <div class="topbar-left">
                <button class="hamburger-btn" id="hamburgerBtn" onclick="toggleSidebar()" aria-label="Toggle menu">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="topbar-title">
                    <h2>Dashboard</h2>
                    <p>Selamat datang di dashboard admin!</p>
                </div>
            </div>
            <a href="{{ route('home') }}" class="btn-back-website">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali ke Website</span>
            </a>
        </header>

        <!-- Content -->
        <div class="content-area">

            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card products">
                    <div class="stat-number text-success">{{ $stats['total_products'] }}</div>
                    <div class="stat-label">Total Produk</div>
                    <i class="fas fa-seedling stat-icon text-success"></i>
                </div>
                <div class="stat-card orders">
                    <div class="stat-number text-primary">{{ $stats['active_products'] }}</div>
                    <div class="stat-label">Produk Aktif</div>
                    <i class="fas fa-check-circle stat-icon text-primary"></i>
                </div>
                <div class="stat-card revenue">
                    <div class="stat-number text-warning">{{ $stats['total_sold'] }}</div>
                    <div class="stat-label">Total Terjual</div>
                    <i class="fas fa-chart-line stat-icon text-warning"></i>
                </div>
                <div class="stat-card users">
                    <div class="stat-number text-info">{{ $stats['low_stock'] }}</div>
                    <div class="stat-label">Stok Menipis</div>
                    <i class="fas fa-exclamation-triangle stat-icon text-info"></i>
                </div>
            </div>

            <!-- Chart Card -->
            <div class="chart-card">
                <div class="chart-card-header">
                    <h5>📈 Grafik Pendapatan Harian (30 Hari Terakhir)</h5>
                    <a href="{{ route('admin.laporan') }}" class="btn-lihat-detail">
                        <i class="fas fa-external-link-alt"></i> Lihat Detail
                    </a>
                </div>
                <div class="chart-card-body">
                    @if(count($chartLabels) > 0)
                        <canvas id="salesChart" height="80"></canvas>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-chart-line"></i>
                            <h4>Belum Ada Data</h4>
                            <p>Grafik pendapatan akan ditampilkan setelah ada transaksi</p>
                            <a href="{{ route('admin.laporan') }}" class="btn-buka-laporan">
                                <i class="fas fa-chart-bar"></i>Buka Laporan
                            </a>
                        </div>
                    @endif
                </div>
            </div>

        </div><!-- /content-area -->
    </div><!-- /main-content -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <script>
        // Sidebar toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('show');
            document.body.style.overflow = sidebar.classList.contains('open') ? 'hidden' : '';
        }

        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
            document.body.style.overflow = '';
        }

        // Close sidebar on resize if desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth > 900) {
                closeSidebar();
            }
        });

        // Chart
        @if(count($chartLabels) > 0)
        const ctx = document.getElementById('salesChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Penjualan (Rp)',
                    data: {!! json_encode($chartData) !!},
                    borderColor: '#16a34a',
                    backgroundColor: 'rgba(22, 163, 74, 0.08)',
                    borderWidth: 2.5,
                    pointBackgroundColor: '#16a34a',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + Number(context.parsed.y).toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + Number(value).toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
        @endif
    </script>
</body>
</html>