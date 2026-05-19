<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan - Bibit Cabai</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --sidebar-width: 260px;
            --green-dark: #15803d;
            --green-main: #16a34a;
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
            top: 0; left: 0;
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

        .sidebar-header .brand-icon { font-size: 2rem; margin-bottom: 6px; display: block; }
        .sidebar-header h3 { font-size: 1.35rem; font-weight: 700; margin-bottom: 2px; }
        .sidebar-header .subtitle { opacity: 0.65; font-size: 0.78rem; margin-bottom: 6px; }
        .sidebar-header .admin-name {
            display: inline-block;
            background: rgba(255,255,255,0.15);
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 500;
        }

        .sidebar-menu { padding: 12px 0; flex: 1; }

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
        }

        .menu-item i { width: 18px; font-size: 0.95rem; flex-shrink: 0; text-align: center; }

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
            height: 64px;
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 1px 0 #e5e7eb;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar-left { display: flex; align-items: center; gap: 14px; }

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

        .topbar-breadcrumb { font-size: 0.8rem; color: #6b7280; margin-bottom: 2px; }
        .topbar-breadcrumb a { color: var(--green-main); text-decoration: none; }
        .topbar-breadcrumb a:hover { text-decoration: underline; }
        .topbar-title h2 { font-size: 1.1rem; font-weight: 700; color: #111827; margin: 0; }
        .topbar-title p { font-size: 0.75rem; color: #6b7280; margin: 0; }

        .topbar-actions { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }

        .btn-topbar {
            border: none;
            padding: 8px 14px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.8rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
            white-space: nowrap;
            cursor: pointer;
        }

        .btn-topbar:hover { transform: translateY(-1px); text-decoration: none; }

        .btn-topbar-export {
            background: #f0fdf4;
            color: var(--green-main);
            border: 1.5px solid #bbf7d0;
        }

        .btn-topbar-export:hover { background: #dcfce7; color: var(--green-dark); }

        /* ===== CONTENT AREA ===== */
        .content-area { padding: 20px 24px 32px; flex: 1; }

        /* ===== INFO CARD ===== */
        .info-card {
            background: white;
            border-radius: 14px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 10px rgba(0,0,0,0.04);
            overflow: hidden;
            margin-bottom: 16px;
        }

        .info-card-header {
            padding: 16px 20px;
            border-bottom: 1px solid #f3f4f6;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-card-icon {
            width: 34px; height: 34px;
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.9rem;
            flex-shrink: 0;
        }

        .icon-blue   { background: #eff6ff; color: #2563eb; }
        .icon-green  { background: #f0fdf4; color: var(--green-main); }
        .icon-orange { background: #fff7ed; color: #ea580c; }
        .icon-purple { background: #faf5ff; color: #7c3aed; }

        .info-card-header h3 { font-size: 0.9rem; font-weight: 700; color: #111827; margin: 0; }
        .info-card-header p  { font-size: 0.75rem; color: #6b7280; margin: 0; }

        .info-card-body { padding: 20px; }

        /* ===== FILTER ===== */
        .filter-label {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--green-main);
            margin-bottom: 14px;
            display: flex; align-items: center; gap: 6px;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 14px;
        }

        @media (min-width: 768px) { .filter-grid { grid-template-columns: repeat(4, 1fr); } }

        .filter-group label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #6b7280;
            margin-bottom: 5px;
            display: block;
        }

        .filter-input {
            width: 100%;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            padding: 8px 11px;
            font-size: 0.84rem;
            color: #111827;
            background: #fafafa;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }

        .filter-input:focus {
            border-color: var(--green-main);
            background: white;
            box-shadow: 0 0 0 3px rgba(22,163,74,0.1);
        }

        .filter-actions { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }

        .btn-tampilkan {
            background: linear-gradient(135deg, var(--green-main), var(--green-dark));
            color: white;
            border: none;
            border-radius: 8px;
            padding: 9px 18px;
            font-size: 0.84rem;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex; align-items: center; gap: 6px;
            transition: all 0.2s;
        }

        .btn-tampilkan:hover { box-shadow: 0 4px 12px rgba(22,163,74,0.35); transform: translateY(-1px); }

        .btn-reset {
            background: #f9fafb;
            color: #374151;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            padding: 9px 16px;
            font-size: 0.84rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex; align-items: center; gap: 6px;
            transition: all 0.2s;
        }

        .btn-reset:hover { background: #f3f4f6; color: #111827; text-decoration: none; }

        .btn-export {
            background: #f0fdf4;
            color: var(--green-main);
            border: 1.5px solid #bbf7d0;
            border-radius: 8px;
            padding: 9px 16px;
            font-size: 0.84rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex; align-items: center; gap: 6px;
            transition: all 0.2s;
        }

        .btn-export:hover { background: #dcfce7; color: var(--green-dark); text-decoration: none; }

        /* ===== SUMMARY CARDS ===== */
        .summary-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 16px;
        }

        @media (min-width: 768px) { .summary-grid { grid-template-columns: repeat(4, 1fr); } }

        .summary-card {
            background: white;
            border-radius: 14px;
            padding: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 10px rgba(0,0,0,0.04);
            border-left: 4px solid;
        }

        .summary-card.sg { border-left-color: var(--green-main); }
        .summary-card.sb { border-left-color: #2563eb; }
        .summary-card.so { border-left-color: #ea580c; }
        .summary-card.sp { border-left-color: #7c3aed; }

        .s-icon {
            width: 34px; height: 34px;
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }

        .sg .s-icon { background: #f0fdf4; color: var(--green-main); }
        .sb .s-icon { background: #eff6ff; color: #2563eb; }
        .so .s-icon { background: #fff7ed; color: #ea580c; }
        .sp .s-icon { background: #faf5ff; color: #7c3aed; }

        .s-val {
            font-size: 1.1rem;
            font-weight: 800;
            color: #111827;
            line-height: 1.2;
            margin-bottom: 3px;
            word-break: break-all;
        }

        @media (min-width: 768px) { .s-val { font-size: 1.25rem; } }
        .s-lbl { font-size: 0.72rem; color: #9ca3af; font-weight: 500; }

        /* ===== SECTION CARD TITLE ===== */
        .card-section-title {
            padding: 14px 20px;
            font-weight: 700;
            font-size: 0.88rem;
            color: #111827;
            border-bottom: 1px solid #f3f4f6;
            display: flex; align-items: center; gap: 8px;
        }

        .sdot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
        .dg { background: var(--green-main); }
        .db { background: #2563eb; }
        .do { background: #ea580c; }

        /* ===== TWO COLUMN ===== */
        .two-col {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
            margin-bottom: 16px;
        }

        @media (min-width: 992px) { .two-col { grid-template-columns: 5fr 7fr; } }

        /* ===== TABLE ===== */
        .tbl { width: 100%; font-size: 0.83rem; border-collapse: collapse; }

        .tbl thead th {
            background: #f9fafb;
            color: #6b7280;
            font-weight: 700;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 11px 14px;
            border-bottom: 1px solid #f3f4f6;
            white-space: nowrap;
        }

        .tbl tbody td {
            padding: 11px 14px;
            vertical-align: middle;
            border-bottom: 1px solid #f9fafb;
            color: #374151;
        }

        .tbl tbody tr:last-child td { border-bottom: none; }
        .tbl tbody tr:hover td { background: #f9fdfb; }

        /* Badges */
        .badge-s {
            padding: 4px 10px; border-radius: 20px;
            font-size: 0.72rem; font-weight: 700;
            display: inline-block; white-space: nowrap;
        }

        .bs-selesai { background: #dcfce7; color: #14532d; }
        .bs-proses  { background: #dbeafe; color: #1e40af; }
        .bs-batal   { background: #fee2e2; color: #991b1b; }
        .bs-kirim   { background: #fef9c3; color: #854d0e; }
        .bs-pending { background: #f3f4f6; color: #6b7280; }

        /* Progress */
        .prog { height: 5px; border-radius: 10px; background: #f3f4f6; overflow: hidden; }
        .prog-fill { height: 100%; border-radius: 10px; background: linear-gradient(90deg, var(--green-main), #4ade80); }

        /* Inv code */
        .inv-code {
            background: #f0fdf4; color: var(--green-main);
            padding: 2px 8px; border-radius: 6px;
            font-size: 0.78rem; font-family: monospace; font-weight: 600;
        }

        /* Action btn */
        .btn-eye {
            background: #f0fdf4; color: var(--green-main);
            border: 1px solid #bbf7d0; border-radius: 8px;
            padding: 5px 10px; font-size: 0.78rem;
            text-decoration: none; transition: all 0.2s;
            display: inline-flex; align-items: center;
        }

        .btn-eye:hover { background: #dcfce7; color: var(--green-dark); text-decoration: none; }

        /* Qty badge */
        .qty-badge {
            background: #f3f4f6; padding: 2px 9px;
            border-radius: 8px; font-size: 0.8rem;
            font-weight: 700; color: #374151;
        }

        /* Empty state */
        .empty { text-align: center; padding: 40px 20px; color: #d1d5db; }
        .empty i { font-size: 2.8rem; margin-bottom: 10px; display: block; }
        .empty p { font-size: 0.85rem; }

        /* Pagination */
        .page-link { color: var(--green-main); border-color: #e5e7eb; }
        .page-item.active .page-link { background: var(--green-main); border-color: var(--green-main); color: white; }
        .page-link:hover { color: var(--green-dark); }

        .pag-wrap {
            padding: 12px 16px;
            border-top: 1px solid #f3f4f6;
            display: flex; justify-content: space-between; align-items: center;
            flex-wrap: wrap; gap: 8px;
        }

        .pag-info { font-size: 0.78rem; color: #9ca3af; }

        /* ===== FADE IN ===== */
        .fade-in { animation: fadeIn 0.3s ease; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 900px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); box-shadow: 4px 0 24px rgba(0,0,0,0.2); }
            .main-content { margin-left: 0; }
            .hamburger-btn { display: flex; align-items: center; justify-content: center; }
        }

        @media (max-width: 767px) {
            .content-area { padding: 14px 14px 24px; }
            .topbar { padding: 0 14px; height: auto; min-height: 58px; flex-wrap: wrap; gap: 8px; padding-top: 10px; padding-bottom: 10px; }
            .topbar-actions .btn-topbar span { display: none; }
            .topbar-actions .btn-topbar { padding: 8px 10px; }
        }
    </style>
</head>
<body>

    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    <!-- ===== SIDEBAR ===== -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <span class="brand-icon">🌱</span>
            <h3>Bibit Cabai</h3>
            <p class="subtitle">Admin Dashboard</p>
            <span class="admin-name">{{ Auth::user()->name ?? 'Admin User' }}</span>
        </div>
        <div class="sidebar-menu">
            <div class="menu-section-label">Menu Utama</div>
            <a href="{{ route('admin.dashboard') }}" class="menu-item">
                <i class="fas fa-tachometer-alt"></i><span>Dashboard</span>
            </a>
            <a href="{{ route('admin.products.index') }}" class="menu-item">
                <i class="fas fa-seedling"></i><span>Kelola Produk</span>
            </a>
            <a href="{{ route('admin.orders') }}" class="menu-item">
                <i class="fas fa-shopping-cart"></i><span>Pesanan</span>
            </a>
            <a href="{{ route('admin.cancellations') }}" class="menu-item">
                <i class="fas fa-times-circle"></i><span>Pengajuan Batal</span>
            </a>
            <a href="{{ route('admin.users') }}" class="menu-item">
                <i class="fas fa-users"></i><span>Pengguna</span>
            </a>
            <a href="{{ route('admin.laporan') }}" class="menu-item active">
                <i class="fas fa-chart-line"></i><span>Laporan</span>
            </a>
        </div>
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i><span>Logout</span>
                </button>
            </form>
        </div>
    </nav>

    <!-- ===== MAIN ===== -->
    <div class="main-content">

        <!-- Topbar -->
        <header class="topbar">
            <div class="topbar-left">
                <button class="hamburger-btn" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="topbar-title">
                    <div class="topbar-breadcrumb">
                        <a href="{{ route('admin.dashboard') }}"><i class="fas fa-home"></i> Dashboard</a>
                        &nbsp;/&nbsp;Laporan
                    </div>
                    <h2>Laporan Penjualan</h2>
                    <p>Detail pendapatan, transaksi & produk keluar</p>
                </div>
            </div>
            <div class="topbar-actions">
                <a href="{{ route('admin.laporan.export') }}" class="btn-topbar btn-topbar-export">
                    <i class="fas fa-file-excel"></i>
                    <span>Export Excel</span>
                </a>
            </div>
        </header>

        <!-- Content -->
        <div class="content-area fade-in">

            <!-- FILTER -->
            <div class="info-card">
                <div class="info-card-header">
                    <div class="info-card-icon icon-green">
                        <i class="fas fa-sliders-h"></i>
                    </div>
                    <div>
                        <h3>Filter Laporan</h3>
                        <p>Tentukan periode dan kriteria laporan</p>
                    </div>
                </div>
                <div class="info-card-body">
                    <form method="GET" action="{{ route('admin.laporan') }}">
                        <div class="filter-grid">
                            <div class="filter-group">
                                <label>Dari Tanggal</label>
                                <input type="date" name="start_date" class="filter-input"
                                       value="{{ request('start_date', now()->startOfMonth()->toDateString()) }}">
                            </div>
                            <div class="filter-group">
                                <label>Sampai Tanggal</label>
                                <input type="date" name="end_date" class="filter-input"
                                       value="{{ request('end_date', now()->toDateString()) }}">
                            </div>
                            <div class="filter-group">
                                <label>Status Pesanan</label>
                                <select name="status" class="filter-input">
                                    <option value="">Semua Status</option>
                                    <option value="delivered"  {{ request('status')=='delivered'  ? 'selected':'' }}>Selesai</option>
                                    <option value="processing" {{ request('status')=='processing' ? 'selected':'' }}>Diproses</option>
                                    <option value="shipped"    {{ request('status')=='shipped'    ? 'selected':'' }}>Dikirim</option>
                                    <option value="cancelled"  {{ request('status')=='cancelled'  ? 'selected':'' }}>Dibatal</option>
                                    <option value="pending"    {{ request('status')=='pending'    ? 'selected':'' }}>Pending</option>
                                </select>
                            </div>
                            <div class="filter-group">
                                <label>Produk</label>
                                <select name="product_id" class="filter-input">
                                    <option value="">Semua Produk</option>
                                    @foreach($products ?? [] as $product)
                                        <option value="{{ $product->id }}" {{ request('product_id')==$product->id ? 'selected':'' }}>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="filter-actions">
                            <button type="submit" class="btn-tampilkan">
                                <i class="fas fa-search"></i> Tampilkan
                            </button>
                            <a href="{{ route('admin.laporan') }}" class="btn-reset">
                                <i class="fas fa-redo"></i> Reset
                            </a>
                            <a href="{{ route('admin.laporan.export') }}" class="btn-export">
                                <i class="fas fa-file-excel"></i> Export Excel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- SUMMARY CARDS -->
            <div class="summary-grid">
                <div class="summary-card sg">
                    <div class="s-icon"><i class="fas fa-wallet"></i></div>
                    <div class="s-val">Rp {{ number_format($summary['total_pendapatan'] ?? 0, 0, ',', '.') }}</div>
                    <div class="s-lbl">Total Pendapatan</div>
                </div>
                <div class="summary-card sb">
                    <div class="s-icon"><i class="fas fa-receipt"></i></div>
                    <div class="s-val">{{ number_format($summary['total_transaksi'] ?? 0) }}</div>
                    <div class="s-lbl">Total Transaksi</div>
                </div>
                <div class="summary-card so">
                    <div class="s-icon"><i class="fas fa-box-open"></i></div>
                    <div class="s-val">{{ number_format($summary['total_produk_keluar'] ?? 0) }}</div>
                    <div class="s-lbl">Produk Terjual (unit)</div>
                </div>
                <div class="summary-card sp">
                    <div class="s-icon"><i class="fas fa-calculator"></i></div>
                    <div class="s-val">Rp {{ number_format($summary['rata_per_transaksi'] ?? 0, 0, ',', '.') }}</div>
                    <div class="s-lbl">Rata-rata / Transaksi</div>
                </div>
            </div>

            <!-- GRAFIK PENDAPATAN -->
            <div class="info-card">
                <div class="card-section-title">
                    <span class="sdot dg"></span> Grafik Pendapatan Harian
                </div>
                <div class="info-card-body">
                    @if(isset($chartLabels) && count($chartLabels) > 0)
                        <canvas id="revenueChart" height="90"></canvas>
                    @else
                        <div class="empty">
                            <i class="fas fa-chart-area"></i>
                            <p>Belum ada data grafik untuk periode ini.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- PRODUK TERLARIS + DETAIL -->
            <div class="two-col">
                <div class="info-card" style="margin-bottom:0">
                    <div class="card-section-title">
                        <span class="sdot do"></span> Produk Terlaris
                    </div>
                    <div class="info-card-body">
                        @if(isset($topProducts) && count($topProducts) > 0)
                            <canvas id="topProductChart" height="200"></canvas>
                        @else
                            <div class="empty">
                                <i class="fas fa-seedling"></i>
                                <p>Belum ada data produk terlaris.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="info-card" style="margin-bottom:0">
                    <div class="card-section-title">
                        <span class="sdot db"></span> Detail Produk Keluar
                    </div>
                    @if(isset($productSales) && count($productSales) > 0)
                        <div class="table-responsive">
                            <table class="tbl">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Produk</th>
                                        <th class="text-center">Terjual</th>
                                        <th class="text-end">Pendapatan</th>
                                        <th>%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($productSales as $i => $item)
                                    <tr>
                                        <td style="color:#d1d5db;font-size:0.75rem">{{ $i + 1 }}</td>
                                        <td>
                                            <div style="font-weight:600;color:#111827">{{ $item->product_name }}</div>
                                            <div style="font-size:0.72rem;color:#9ca3af">{{ $item->category ?? '-' }}</div>
                                        </td>
                                        <td class="text-center">
                                            <span class="qty-badge">{{ number_format($item->total_qty) }}</span>
                                        </td>
                                        <td class="text-end" style="color:var(--green-main);font-weight:700;white-space:nowrap;font-size:0.82rem">
                                            Rp {{ number_format($item->total_revenue, 0, ',', '.') }}
                                        </td>
                                        <td style="min-width:80px">
                                            @php
                                                $pct = $summary['total_produk_keluar'] > 0
                                                    ? round($item->total_qty / $summary['total_produk_keluar'] * 100) : 0;
                                            @endphp
                                            <div class="d-flex align-items-center gap-1">
                                                <div class="prog flex-grow-1">
                                                    <div class="prog-fill" style="width:{{ $pct }}%"></div>
                                                </div>
                                                <span style="font-size:0.7rem;color:#9ca3af;white-space:nowrap">{{ $pct }}%</span>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty">
                            <i class="fas fa-box-open"></i>
                            <p>Belum ada data produk untuk periode ini.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- RIWAYAT TRANSAKSI -->
            <div class="info-card" style="margin-top:0">
                <div class="card-section-title">
                    <span class="sdot dg"></span> Riwayat Transaksi Detail
                </div>
                @if(isset($orders) && $orders->count() > 0)
                    <div class="table-responsive">
                        <table class="tbl">
                            <thead>
                                <tr>
                                    <th>No. Order</th>
                                    <th>Tanggal</th>
                                    <th>Pelanggan</th>
                                    <th>Produk</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr>
                                    <td><span class="inv-code">#{{ $order->invoice_number ?? $order->id }}</span></td>
                                    <td style="white-space:nowrap;color:#6b7280">
                                        {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}<br>
                                        <small style="color:#9ca3af">{{ \Carbon\Carbon::parse($order->created_at)->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <div style="font-weight:600;color:#111827">{{ $order->customer_name ?? $order->user->name ?? 'Guest' }}</div>
                                        <div style="font-size:0.72rem;color:#9ca3af">{{ $order->customer_email ?? $order->user->email ?? '-' }}</div>
                                    </td>
                                    <td>
                                        @forelse($order->details ?? [] as $detail)
                                            <div style="font-size:0.82rem">{{ $detail->product->name ?? '-' }}</div>
                                        @empty
                                            <span style="color:#d1d5db">-</span>
                                        @endforelse
                                    </td>
                                    <td class="text-center">
                                        @forelse($order->details ?? [] as $detail)
                                            <div>{{ $detail->quantity }}</div>
                                        @empty
                                            <span style="color:#d1d5db">-</span>
                                        @endforelse
                                    </td>
                                    <td class="text-end" style="color:var(--green-main);font-weight:700;white-space:nowrap">
                                        Rp {{ number_format($order->total_amount ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        @php $orderStatus = $order->order_status ?? 'pending'; @endphp
                                        @if($orderStatus == 'delivered')
                                            <span class="badge-s bs-selesai">Selesai</span>
                                        @elseif($orderStatus == 'shipped')
                                            <span class="badge-s bs-kirim">Dikirim</span>
                                        @elseif($orderStatus == 'cancelled')
                                            <span class="badge-s bs-batal">Batal</span>
                                        @elseif($orderStatus == 'processing')
                                            <span class="badge-s bs-proses">Diproses</span>
                                        @else
                                            <span class="badge-s bs-pending">Pending</span>
                                        @endif
                                        <br>
                                        @php $payStatus = $order->payment_status ?? 'pending'; @endphp
                                        @if($payStatus == 'paid')
                                            <small style="color:var(--green-main);font-weight:700;font-size:0.7rem">Paid</small>
                                        @elseif($payStatus == 'failed')
                                            <small style="color:#dc2626;font-weight:700;font-size:0.7rem">Failed</small>
                                        @else
                                            <small style="color:#ca8a04;font-weight:700;font-size:0.7rem">Pending</small>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.transaksis.show', $order->id) }}" class="btn-eye">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($orders->hasPages())
                    <div class="pag-wrap">
                        <span class="pag-info">
                            Menampilkan {{ $orders->firstItem() }}–{{ $orders->lastItem() }} dari {{ $orders->total() }} transaksi
                        </span>
                        {{ $orders->appends(request()->query())->links() }}
                    </div>
                    @endif
                @else
                    <div class="empty">
                        <i class="fas fa-receipt"></i>
                        <p>Belum ada transaksi untuk periode yang dipilih.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('sidebarOverlay').classList.toggle('show');
        }
        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('open');
            document.getElementById('sidebarOverlay').classList.remove('show');
        }
        window.addEventListener('resize', function() {
            if (window.innerWidth > 900) closeSidebar();
        });

        @if(isset($chartLabels) && count($chartLabels) > 0)
        const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctxRevenue, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [
                    {
                        type: 'line',
                        label: 'Tren',
                        data: {!! json_encode($chartData) !!},
                        borderColor: '#4ade80',
                        backgroundColor: 'transparent',
                        borderWidth: 2,
                        pointRadius: 3,
                        tension: 0.4,
                        order: 1
                    },
                    {
                        type: 'bar',
                        label: 'Pendapatan (Rp)',
                        data: {!! json_encode($chartData) !!},
                        backgroundColor: 'rgba(22,163,74,0.1)',
                        borderColor: '#16a34a',
                        borderWidth: 1.5,
                        borderRadius: 6,
                        order: 2
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: true, position: 'top' },
                    tooltip: {
                        callbacks: {
                            label: ctx => ctx.dataset.label + ': Rp ' + Number(ctx.parsed.y).toLocaleString('id-ID')
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f3f4f6' },
                        ticks: { callback: v => 'Rp ' + Number(v).toLocaleString('id-ID') }
                    },
                    x: { grid: { display: false } }
                }
            }
        });
        @endif

        @if(isset($topProducts) && count($topProducts) > 0)
        const ctxProd = document.getElementById('topProductChart').getContext('2d');
        new Chart(ctxProd, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($topProducts->pluck('product_name')) !!},
                datasets: [{
                    data: {!! json_encode($topProducts->pluck('total_qty')) !!},
                    backgroundColor: ['#16a34a','#4ade80','#2563eb','#ea580c','#7c3aed','#dc2626','#ca8a04'],
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                cutout: '60%',
                plugins: {
                    legend: { position: 'bottom', labels: { padding: 12, font: { size: 11 } } },
                    tooltip: {
                        callbacks: {
                            label: ctx => ctx.label + ': ' + Number(ctx.parsed).toLocaleString('id-ID') + ' unit'
                        }
                    }
                }
            }
        });
        @endif
    </script>
</body>
</html>