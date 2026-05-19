<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Pembatalan - Bibit Cabai Admin</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
        .topbar-title h2 { font-size: 1.1rem; font-weight: 700; color: #111827; margin: 0; }
        .topbar-title p { font-size: 0.75rem; color: #6b7280; margin: 0; }

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
        .content-area { padding: 20px 24px 24px; flex: 1; }

        /* ===== STATS GRID ===== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 18px 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 10px rgba(0,0,0,0.04);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-left: 4px solid;
        }

        .stat-card.warning { border-left-color: #d97706; }
        .stat-card.success { border-left-color: var(--green-main); }
        .stat-card.danger  { border-left-color: #dc2626; }

        .stat-label {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 4px;
        }

        .stat-card.warning .stat-label { color: #d97706; }
        .stat-card.success .stat-label { color: var(--green-main); }
        .stat-card.danger  .stat-label { color: #dc2626; }

        .stat-value { font-size: 1.5rem; font-weight: 800; color: #111827; }
        .stat-icon  { font-size: 1.75rem; color: #e5e7eb; }

        /* ===== TABLE CARD ===== */
        .table-card {
            background: white;
            border-radius: 14px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 10px rgba(0,0,0,0.04);
            overflow: hidden;
        }

        .table-card-header {
            padding: 18px 20px;
            border-bottom: 1px solid #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .section-title {
            font-size: 1rem;
            font-weight: 700;
            color: #111827;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* ===== TABLE ===== */
        .data-table { width: 100%; border-collapse: collapse; }

        .data-table th, .data-table td {
            padding: 11px 14px;
            text-align: left;
            border-bottom: 1px solid #f3f4f6;
            font-size: 0.875rem;
            vertical-align: middle;
        }

        .data-table th {
            background: #f9fafb;
            font-weight: 600;
            color: #6b7280;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            white-space: nowrap;
        }

        .data-table tbody tr:hover { background: #f9fafb; }

        /* ===== BADGES ===== */
        .badge-status {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.72rem;
            font-weight: 600;
            display: inline-block;
        }

        .badge-pending  { background: #fef3c7; color: #92400e; }
        .badge-approved { background: #dcfce7; color: #166534; }
        .badge-rejected { background: #fee2e2; color: #991b1b; }

        /* ===== ACTION BUTTONS ===== */
        .btn-action {
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-approve { background: #16a34a; color: white; }
        .btn-reject  { background: #dc2626; color: white; }
        .btn-label-approved { background: #dcfce7; color: #166534; cursor: default; }
        .btn-label-rejected { background: #fee2e2; color: #991b1b; cursor: default; }

        .btn-action:hover:not([style*="cursor:default"]) {
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
            color: inherit;
        }

        /* ===== TABLE FOOTER ===== */
        .table-footer {
            padding: 14px 20px;
            border-top: 1px solid #f3f4f6;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        /* ===== MOBILE CARDS ===== */
        .mobile-card-list { display: none; }

        .cancel-card {
            background: white;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 10px rgba(0,0,0,0.04);
        }

        .cancel-card-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
        }

        .cancel-card-invoice { font-weight: 700; font-size: 0.9rem; color: #111827; }
        .cancel-card-amount  { font-size: 0.78rem; color: #9ca3af; margin-top: 2px; }

        .cancel-card-meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin-bottom: 10px;
            font-size: 0.8rem;
        }

        .cancel-card-meta-item .label { color: #9ca3af; font-size: 0.72rem; text-transform: uppercase; font-weight: 600; margin-bottom: 2px; }
        .cancel-card-meta-item .value { color: #374151; }

        .cancel-card-reason {
            background: #f9fafb;
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 0.8rem;
            color: #374151;
            margin-bottom: 12px;
        }

        .cancel-card-reason .reason-label { font-weight: 700; color: #6b7280; font-size: 0.72rem; text-transform: uppercase; margin-bottom: 4px; }

        .cancel-card-actions { display: flex; gap: 8px; }
        .cancel-card-actions .btn-action { flex: 1; justify-content: center; }

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
            .desktop-table-wrap { display: none; }
            .mobile-card-list { display: block; }
            .stats-grid { grid-template-columns: 1fr 1fr; }
            .stats-grid .stat-card:last-child { grid-column: span 2; }
            .content-area { padding: 14px 14px 20px; }
            .topbar { padding: 0 14px; }
            .btn-back-website span { display: none; }
            .btn-back-website { padding: 9px 12px; }
            .table-footer { flex-direction: column; align-items: flex-start; }
        }

        @media (max-width: 400px) {
            .stats-grid { grid-template-columns: 1fr; }
            .stats-grid .stat-card:last-child { grid-column: span 1; }
        }
    </style>
</head>
<body>

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

            <a href="{{ route('admin.dashboard') }}" class="menu-item">
                <i class="fas fa-tachometer-alt"></i><span>Dashboard</span>
            </a>
            <a href="{{ route('admin.products.index') }}" class="menu-item">
                <i class="fas fa-seedling"></i><span>Kelola Produk</span>
            </a>
            <a href="{{ route('admin.orders') }}" class="menu-item">
                <i class="fas fa-shopping-cart"></i><span>Pesanan</span>
            </a>
            <a href="{{ route('admin.cancellations') }}" class="menu-item active">
                <i class="fas fa-times-circle"></i><span>Pengajuan Batal</span>
            </a>
            <a href="{{ route('admin.users') }}" class="menu-item">
                <i class="fas fa-users"></i><span>Pengguna</span>
            </a>
            <a href="{{ route('admin.laporan') }}" class="menu-item">
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

    <!-- Main Content -->
    <div class="main-content">

        <!-- Topbar -->
        <header class="topbar">
            <div class="topbar-left">
                <button class="hamburger-btn" onclick="toggleSidebar()" aria-label="Toggle menu">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="topbar-title">
                    <div class="topbar-breadcrumb">
                        <a href="{{ route('admin.dashboard') }}"><i class="fas fa-home"></i> Dashboard</a>
                        &nbsp;/&nbsp;Pengajuan Pembatalan
                    </div>
                    <h2>Pengajuan Pembatalan Pesanan</h2>
                    <p>Kelola semua permintaan pembatalan dari pelanggan.</p>
                </div>
            </div>
            <a href="{{ route('home') }}" class="btn-back-website">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali ke Website</span>
            </a>
        </header>

        <!-- Content -->
        <div class="content-area fade-in">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Stats -->
            <div class="stats-grid">
                <div class="stat-card warning">
                    <div>
                        <div class="stat-label">Menunggu Review</div>
                        <div class="stat-value">{{ $cancellations->where('status','pending')->count() }}</div>
                    </div>
                    <div class="stat-icon"><i class="fas fa-clock"></i></div>
                </div>
                <div class="stat-card success">
                    <div>
                        <div class="stat-label">Disetujui</div>
                        <div class="stat-value">{{ $cancellations->where('status','approved')->count() }}</div>
                    </div>
                    <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                </div>
                <div class="stat-card danger">
                    <div>
                        <div class="stat-label">Ditolak</div>
                        <div class="stat-value">{{ $cancellations->where('status','rejected')->count() }}</div>
                    </div>
                    <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
                </div>
            </div>

            <!-- Table Card -->
            <div class="table-card">
                <div class="table-card-header">
                    <div class="section-title">
                        <i class="fas fa-times-circle text-success"></i>
                        Daftar Pengajuan Pembatalan ({{ $cancellations->total() }})
                    </div>
                </div>

                <!-- DESKTOP TABLE -->
                <div class="desktop-table-wrap table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th class="ps-3">Invoice</th>
                                <th>Pelanggan</th>
                                <th>Alasan</th>
                                <th>Keterangan</th>
                                <th>Tgl Pengajuan</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cancellations as $item)
                            <tr>
                                <td class="ps-3">
                                    <strong class="d-block" style="color:#111827;">{{ $item->transaksi->invoice_number ?? '-' }}</strong>
                                    <small style="color:#9ca3af;">
                                        Rp{{ number_format($item->transaksi->total_amount ?? 0, 0, ',', '.') }}
                                    </small>
                                </td>
                                <td>
                                    <strong style="color:#111827;">{{ $item->user->name ?? '-' }}</strong><br>
                                    <small style="color:#9ca3af;">{{ $item->user->email ?? '-' }}</small>
                                </td>
                                <td style="max-width:160px;">
                                    <small style="color:#374151;">{{ $item->reason }}</small>
                                </td>
                                <td style="max-width:180px;">
                                    <small style="color:#6b7280;">{{ $item->description ?? '-' }}</small>
                                </td>
                                <td>
                                    <small style="color:#374151;">{{ $item->created_at->format('d M Y') }}</small><br>
                                    <small style="color:#9ca3af;">{{ $item->created_at->format('H:i') }}</small>
                                </td>
                                <td>
                                    <span class="badge-status badge-{{ $item->status }}">
                                        {{ $item->status === 'pending' ? 'Menunggu' : ($item->status === 'approved' ? 'Disetujui' : 'Ditolak') }}
                                    </span>
                                    @if($item->reviewed_at)
                                        <br><small style="color:#9ca3af;">{{ $item->reviewed_at->format('d M Y') }}</small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($item->status === 'pending')
                                        <form action="{{ route('admin.cancellations.approve', $item->id) }}" method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Setujui pembatalan pesanan {{ $item->transaksi->invoice_number ?? '' }}?')">
                                            @csrf
                                            <textarea name="admin_note" style="display:none"> disetujui</textarea>
                                            <button type="submit" class="btn-action btn-approve me-1">
                                                <i class="fas fa-check"></i>Setujui
                                            </button>
                                        </form>
                                        <button type="button" class="btn-action btn-reject"
                                                onclick="showRejectModal({{ $item->id }}, '{{ $item->transaksi->invoice_number ?? '' }}')">
                                            <i class="fas fa-times"></i>Tolak
                                        </button>
                                    @elseif($item->status === 'approved')
                                        <span class="btn-action btn-label-approved">Disetujui</span>
                                    @elseif($item->status === 'rejected')
                                        <span class="btn-action btn-label-rejected">Ditolak</span>
                                    @else
                                        <span style="color:#9ca3af;">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" style="text-align:center; padding:50px 20px; color:#9ca3af;">
                                    <i class="fas fa-inbox" style="font-size:2.5rem; display:block; margin-bottom:12px; opacity:0.3;"></i>
                                    Belum ada pengajuan pembatalan
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- MOBILE CARD LIST -->
                <div class="mobile-card-list" style="padding:16px;">
                    @forelse($cancellations as $item)
                    <div class="cancel-card">
                        <div class="cancel-card-top">
                            <div>
                                <div class="cancel-card-invoice">{{ $item->transaksi->invoice_number ?? '-' }}</div>
                                <div class="cancel-card-amount">Rp{{ number_format($item->transaksi->total_amount ?? 0, 0, ',', '.') }}</div>
                            </div>
                            <span class="badge-status badge-{{ $item->status }}">
                                {{ $item->status === 'pending' ? 'Menunggu' : ($item->status === 'approved' ? 'Disetujui' : 'Ditolak') }}
                            </span>
                        </div>

                        <div class="cancel-card-meta">
                            <div class="cancel-card-meta-item">
                                <div class="label">Pelanggan</div>
                                <div class="value">{{ $item->user->name ?? '-' }}</div>
                            </div>
                            <div class="cancel-card-meta-item">
                                <div class="label">Tanggal</div>
                                <div class="value">{{ $item->created_at->format('d M Y') }}</div>
                            </div>
                            <div class="cancel-card-meta-item" style="grid-column:span 2;">
                                <div class="label">Email</div>
                                <div class="value">{{ $item->user->email ?? '-' }}</div>
                            </div>
                        </div>

                        <div class="cancel-card-reason">
                            <div class="reason-label">Alasan</div>
                            {{ $item->reason }}
                            @if($item->description)
                                <div style="margin-top:6px; color:#6b7280; font-size:0.78rem;">{{ $item->description }}</div>
                            @endif
                        </div>

                        @if($item->status === 'pending')
                        <div class="cancel-card-actions">
                            <form action="{{ route('admin.cancellations.approve', $item->id) }}" method="POST"
                                  style="flex:1;"
                                  onsubmit="return confirm('Setujui pembatalan pesanan {{ $item->transaksi->invoice_number ?? '' }}?')">
                                @csrf
                                <textarea name="admin_note" style="display:none"> disetujui</textarea>
                                <button type="submit" class="btn-action btn-approve" style="width:100%; justify-content:center;">
                                    <i class="fas fa-check"></i>Setujui
                                </button>
                            </form>
                            <button type="button" class="btn-action btn-reject"
                                    style="flex:1; justify-content:center;"
                                    onclick="showRejectModal({{ $item->id }}, '{{ $item->transaksi->invoice_number ?? '' }}')">
                                <i class="fas fa-times"></i>Tolak
                            </button>
                        </div>
                        @elseif($item->status === 'approved')
                            <span class="btn-action btn-label-approved" style="width:100%; justify-content:center; display:flex;">Disetujui</span>
                        @elseif($item->status === 'rejected')
                            <span class="btn-action btn-label-rejected" style="width:100%; justify-content:center; display:flex;">Ditolak</span>
                        @endif
                    </div>
                    @empty
                    <div style="text-align:center; padding:50px 20px; color:#9ca3af;">
                        <i class="fas fa-inbox" style="font-size:2.5rem; display:block; margin-bottom:12px; opacity:0.3;"></i>
                        Belum ada pengajuan pembatalan
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($cancellations->hasPages())
                <div class="table-footer">
                    <small style="color:#6b7280;">
                        Menampilkan {{ $cancellations->firstItem() }}–{{ $cancellations->lastItem() }}
                        dari {{ $cancellations->total() }} pengajuan
                    </small>
                    <div>{{ $cancellations->links() }}</div>
                </div>
                @endif
            </div>

        </div>
    </div>

    <!-- Modal Tolak -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
            <div class="modal-content border-0" style="border-radius:14px; overflow:hidden;">
                <div class="modal-header bg-danger text-white border-0">
                    <h6 class="modal-title fw-bold mb-0">
                        <i class="fas fa-times-circle me-2"></i>Tolak Pengajuan
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="rejectForm" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <p class="mb-3" style="font-size:0.9rem;">Pesanan: <strong id="rejectInvoice"></strong></p>
                        <label class="form-label fw-semibold" style="font-size:0.875rem;">
                            Alasan Penolakan <span class="text-danger">*</span>
                        </label>
                        <textarea name="admin_note" class="form-control" rows="3"
                                  placeholder="Jelaskan alasan penolakan kepada pelanggan..."
                                  required maxlength="500"></textarea>
                        <small class="text-muted">Wajib diisi. Maks. 500 karakter.</small>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger fw-bold">
                            <i class="fas fa-times me-1"></i>Tolak Pengajuan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
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

        window.addEventListener('resize', function() {
            if (window.innerWidth > 900) closeSidebar();
        });

        function showRejectModal(id, invoice) {
            document.getElementById('rejectInvoice').textContent = invoice;
            document.getElementById('rejectForm').action = `/admin/cancellations/${id}/reject`;
            new bootstrap.Modal(document.getElementById('rejectModal')).show();
        }
    </script>
</body>
</html>