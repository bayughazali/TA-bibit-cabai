<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kelola Pengguna - Bibit Cabai Admin</title>

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
        .topbar-title p  { font-size: 0.75rem; color: #6b7280; margin: 0; }

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
            grid-template-columns: repeat(4, 1fr);
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

        .stat-card.primary { border-left-color: #2563eb; }
        .stat-card.warning { border-left-color: #d97706; }
        .stat-card.success { border-left-color: var(--green-main); }
        .stat-card.info    { border-left-color: #0891b2; }

        .stat-label {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 4px;
        }

        .stat-card.primary .stat-label { color: #2563eb; }
        .stat-card.warning .stat-label { color: #d97706; }
        .stat-card.success .stat-label { color: var(--green-main); }
        .stat-card.info    .stat-label { color: #0891b2; }

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

        /* Search */
        .search-bar {
            padding: 14px 20px;
            border-bottom: 1px solid #f3f4f6;
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
        .badge-role {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.72rem;
            font-weight: 600;
            display: inline-block;
        }

        .badge-admin      { background: #dbeafe; color: #1e40af; }
        .badge-user       { background: #dcfce7; color: #166534; }
        .badge-verified   { background: #dcfce7; color: #166534; }
        .badge-unverified { background: #f3f4f6; color: #6b7280; }

        /* ===== ACTION BUTTONS ===== */
        .btn-action {
            padding: 6px 9px;
            border: none;
            border-radius: 6px;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-action:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
            text-decoration: none;
            color: inherit;
        }

        .btn-view   { background: #0891b2; color: white; }
        .btn-edit   { background: #d97706; color: white; }
        .btn-delete { background: #dc2626; color: white; }

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
        .mobile-card-list { display: none; padding: 14px; }

        .user-card {
            background: white;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 10px rgba(0,0,0,0.04);
        }

        .user-card-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
            gap: 10px;
        }

        .user-card-avatar {
            width: 42px; height: 42px;
            background: linear-gradient(135deg, var(--green-dark), var(--green-main));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .user-card-name  { font-weight: 700; font-size: 0.95rem; color: #111827; }
        .user-card-email { font-size: 0.78rem; color: #9ca3af; margin-top: 2px; }

        .user-card-badges { display: flex; gap: 6px; flex-wrap: wrap; }

        .user-card-meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin-bottom: 12px;
            font-size: 0.8rem;
        }

        .user-card-meta-item .label { color: #9ca3af; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; margin-bottom: 2px; }
        .user-card-meta-item .value { color: #374151; }

        .user-card-actions { display: flex; gap: 8px; }
        .user-card-actions .btn-action { flex: 1; justify-content: center; padding: 8px; }

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

        @media (max-width: 1100px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 767px) {
            .desktop-table-wrap { display: none; }
            .mobile-card-list { display: block; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
            .content-area { padding: 14px 14px 20px; }
            .topbar { padding: 0 14px; }
            .btn-back-website span { display: none; }
            .btn-back-website { padding: 9px 12px; }
            .table-card-header { flex-direction: column; align-items: stretch; }
            .table-card-header .btn { width: 100%; }
            .table-footer { flex-direction: column; align-items: flex-start; }
        }

        @media (max-width: 400px) {
            .stats-grid { grid-template-columns: 1fr; }
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
            <a href="{{ route('admin.cancellations') }}" class="menu-item">
                <i class="fas fa-times-circle"></i><span>Pengajuan Batal</span>
            </a>
            <a href="{{ route('admin.users') }}" class="menu-item active">
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
                        &nbsp;/&nbsp;Pengguna
                    </div>
                    <h2>Kelola Pengguna</h2>
                    <p>Kelola semua pengguna terdaftar di sini!</p>
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

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Stats -->
            <div class="stats-grid">
                <div class="stat-card primary">
                    <div>
                        <div class="stat-label">Total Pengguna</div>
                        <div class="stat-value">{{ $totalUsers ?? 0 }}</div>
                    </div>
                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                </div>
                <div class="stat-card warning">
                    <div>
                        <div class="stat-label">Admin</div>
                        <div class="stat-value">{{ $totalAdmins ?? 0 }}</div>
                    </div>
                    <div class="stat-icon"><i class="fas fa-user-shield"></i></div>
                </div>
                <div class="stat-card success">
                    <div>
                        <div class="stat-label">Email Terverifikasi</div>
                        <div class="stat-value">{{ $verifiedUsers ?? 0 }}</div>
                    </div>
                    <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                </div>
                <div class="stat-card info">
                    <div>
                        <div class="stat-label">Aktif (30 Hari)</div>
                        <div class="stat-value">{{ $activeUsers ?? 0 }}</div>
                    </div>
                    <div class="stat-icon"><i class="fas fa-user-clock"></i></div>
                </div>
            </div>

            <!-- Table Card -->
            <div class="table-card">
                <div class="table-card-header">
                    <div class="section-title">
                        <i class="fas fa-users text-success"></i>
                        Daftar Pengguna ({{ $totalUsers ?? 0 }})
                    </div>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i>Tambah Pengguna
                    </a>
                </div>

                <!-- Search -->
                <div class="search-bar">
                    <div class="input-group" style="max-width:400px;">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" id="searchInput"
                               placeholder="Cari nama, email, atau telepon...">
                    </div>
                </div>

                @if(isset($users) && $users->count() > 0)

                    <!-- DESKTOP TABLE -->
                    <div class="desktop-table-wrap table-responsive">
                        <table class="data-table" id="usersTable">
                            <thead>
                                <tr>
                                    <th style="padding-left:20px;">ID</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Telepon</th>
                                    <th>Alamat</th>
                                    <th>Role</th>
                                    <th>Verifikasi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td style="padding-left:20px; color:#6b7280;">
                                        {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                                    </td>
                                    <td><strong style="color:#111827;">{{ $user->name }}</strong></td>
                                    <td style="color:#374151;">{{ $user->email }}</td>
                                    <td style="color:#374151;">{{ $user->phone ?? '-' }}</td>
                                    <td><small style="color:#6b7280;">{{ \Illuminate\Support\Str::limit($user->address ?? '-', 30) }}</small></td>
                                    <td>
                                        @if($user->is_admin == 1)
                                            <span class="badge-role badge-admin">Admin</span>
                                        @else
                                            <span class="badge-role badge-user">User</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->email_verified_at)
                                            <span class="badge-role badge-verified">
                                                <i class="fas fa-check me-1"></i>Terverifikasi
                                            </span>
                                        @else
                                            <span class="badge-role badge-unverified">Belum Verifikasi</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('admin.users.show', $user) }}"
                                               class="btn-action btn-view" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <!-- <a href="{{ route('admin.users.edit', $user) }}"
                                               class="btn-action btn-edit" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a> -->
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                                  style="display:inline;"
                                                  onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-action btn-delete" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- MOBILE CARD LIST -->
                    <div class="mobile-card-list" id="mobileUserList">
                        @foreach($users as $user)
                        <div class="user-card" data-search="{{ strtolower($user->name . ' ' . $user->email . ' ' . ($user->phone ?? '')) }}">
                            <div class="user-card-top">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="user-card-avatar">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="user-card-name">{{ $user->name }}</div>
                                        <div class="user-card-email">{{ $user->email }}</div>
                                    </div>
                                </div>
                                <div class="user-card-badges">
                                    @if($user->is_admin == 1)
                                        <span class="badge-role badge-admin">Admin</span>
                                    @else
                                        <span class="badge-role badge-user">User</span>
                                    @endif
                                </div>
                            </div>

                            <div class="user-card-meta">
                                <div class="user-card-meta-item">
                                    <div class="label">Telepon</div>
                                    <div class="value">{{ $user->phone ?? '-' }}</div>
                                </div>
                                <div class="user-card-meta-item">
                                    <div class="label">Status Email</div>
                                    <div class="value">
                                        @if($user->email_verified_at)
                                            <span class="badge-role badge-verified" style="font-size:0.68rem;">
                                                <i class="fas fa-check me-1"></i>Terverifikasi
                                            </span>
                                        @else
                                            <span class="badge-role badge-unverified" style="font-size:0.68rem;">Belum Verifikasi</span>
                                        @endif
                                    </div>
                                </div>
                                @if($user->address)
                                <div class="user-card-meta-item" style="grid-column:span 2;">
                                    <div class="label">Alamat</div>
                                    <div class="value">{{ \Illuminate\Support\Str::limit($user->address, 60) }}</div>
                                </div>
                                @endif
                            </div>

                            <div class="user-card-actions">
                                <a href="{{ route('admin.users.show', $user) }}" class="btn-action btn-view" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <!-- <a href="{{ route('admin.users.edit', $user) }}" class="btn-action btn-edit" title="Edit">
                                    <i class="fas fa-edit"></i> Edit
                                </a> -->
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                      style="flex:0;"
                                      onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="table-footer">
                        <small style="color:#6b7280;">
                            Menampilkan {{ $users->firstItem() }}–{{ $users->lastItem() }}
                            dari {{ $users->total() }} pengguna
                        </small>
                        <div>{{ $users->links() }}</div>
                    </div>

                @else
                    <div style="text-align:center; padding:50px 20px; color:#9ca3af;">
                        <i class="fas fa-users" style="font-size:2.5rem; display:block; margin-bottom:12px; opacity:0.3;"></i>
                        <h5 style="color:#6b7280;">Belum ada pengguna terdaftar</h5>
                        <p style="font-size:0.875rem;">Mulai dengan menambahkan pengguna baru.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ===== SIDEBAR =====
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

        // ===== LIVE SEARCH (desktop table + mobile cards) =====
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();

            // Desktop table rows
            const rows = document.querySelectorAll('#usersTable tbody tr');
            rows.forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
            });

            // Mobile cards
            const cards = document.querySelectorAll('.user-card');
            cards.forEach(card => {
                const text = card.getAttribute('data-search') || card.textContent.toLowerCase();
                card.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    </script>
</body>
</html>