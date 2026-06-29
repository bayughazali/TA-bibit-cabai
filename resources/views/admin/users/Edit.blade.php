<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengguna - Bibit Cabai Admin</title>
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

        .btn-topbar-back {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            color: white;
        }

        .btn-topbar-back:hover { box-shadow: 0 4px 12px rgba(220,38,38,0.35); color: white; }

        .btn-topbar-save {
            background: linear-gradient(135deg, var(--green-main), var(--green-dark));
            color: white;
        }

        .btn-topbar-save:hover { box-shadow: 0 4px 12px rgba(22,163,74,0.35); color: white; }

        .btn-topbar-view {
            background: linear-gradient(135deg, #0891b2, #0e7490);
            color: white;
        }

        .btn-topbar-view:hover { box-shadow: 0 4px 12px rgba(8,145,178,0.35); color: white; }

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
        .icon-yellow { background: #fefce8; color: #ca8a04; }
        .icon-gray   { background: #f3f4f6; color: #6b7280; }
        .icon-red    { background: #fef2f2; color: #dc2626; }

        .info-card-header h3 { font-size: 0.9rem; font-weight: 700; color: #111827; margin: 0; }
        .info-card-header p  { font-size: 0.75rem; color: #6b7280; margin: 0; }

        .info-card-body { padding: 20px; }

        /* ===== SECTION DIVIDER ===== */
        .section-divider {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #9ca3af;
            padding-bottom: 10px;
            border-bottom: 1px solid #f3f4f6;
            margin-bottom: 16px;
        }

        /* ===== FORM STYLES ===== */
        .form-label-custom {
            font-size: 0.78rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 5px;
            display: block;
        }

        .form-control-custom,
        .form-select-custom {
            font-size: 0.84rem;
            padding: 8px 12px;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            width: 100%;
            color: #111827;
            background: white;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }

        .form-control-custom:focus,
        .form-select-custom:focus {
            border-color: var(--green-main);
            box-shadow: 0 0 0 3px rgba(22,163,74,0.12);
        }

        .form-control-custom.is-invalid,
        .form-select-custom.is-invalid {
            border-color: #dc2626;
        }

        textarea.form-control-custom { resize: vertical; min-height: 90px; }

        .invalid-feedback-custom {
            font-size: 0.75rem;
            color: #dc2626;
            margin-top: 4px;
        }

        /* Password toggle */
        .input-pw-wrap {
            position: relative;
        }

        .input-pw-wrap .form-control-custom {
            padding-right: 40px;
        }

        .btn-pw-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            font-size: 0.85rem;
            padding: 0;
            line-height: 1;
        }

        .btn-pw-toggle:hover { color: #374151; }

        /* User info badge */
        .user-meta-badge {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            background: #f9fafb;
            border: 1px solid #f3f4f6;
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 20px;
            font-size: 0.8rem;
            color: #6b7280;
        }

        .user-meta-badge span { display: flex; align-items: center; gap: 5px; }
        .user-meta-badge strong { color: #111827; }

        /* Checkbox custom */
        .check-card {
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            padding: 14px 16px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            cursor: pointer;
            transition: border-color 0.2s, background 0.2s;
        }

        .check-card:has(input:checked) {
            border-color: var(--green-main);
            background: #f0fdf4;
        }

        .check-card input[type="checkbox"] {
            width: 17px; height: 17px;
            accent-color: var(--green-main);
            margin-top: 2px;
            flex-shrink: 0;
            cursor: pointer;
        }

        .check-card-label { font-size: 0.84rem; font-weight: 600; color: #111827; }
        .check-card-desc  { font-size: 0.75rem; color: #6b7280; margin-top: 2px; }
        .check-card-desc.text-danger { color: #dc2626 !important; }

        /* ===== AVATAR MINI ===== */
        .avatar-mini {
            width: 44px; height: 44px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--green-main), var(--green-dark));
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; color: white; font-weight: 700;
            flex-shrink: 0;
        }

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
                        &nbsp;/&nbsp;<a href="{{ route('admin.users') }}">Pengguna</a>
                        &nbsp;/&nbsp;<a href="{{ route('admin.users.show', $user) }}">{{ $user->name }}</a>
                        &nbsp;/&nbsp;Edit
                    </div>
                    <h2>Edit Pengguna</h2>
                    <p>{{ $user->name }}</p>
                </div>
            </div>
            <div class="topbar-actions">
                <a href="{{ route('admin.users') }}" class="btn-topbar btn-topbar-back">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali</span>
                </a>
                <a href="{{ route('admin.users.show', $user) }}" class="btn-topbar btn-topbar-view">
                    <i class="fas fa-eye"></i>
                    <span>Lihat Detail</span>
                </a>
                <button type="submit" form="editUserForm" class="btn-topbar btn-topbar-save">
                    <i class="fas fa-save"></i>
                    <span>Simpan</span>
                </button>
            </div>
        </header>

        <!-- Content -->
        <div class="content-area fade-in">

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i><strong>Error!</strong>
                    <ul class="mb-0 mt-1">
                        @foreach ($errors->all() as $error)
                            <li style="font-size:0.85rem;">{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form id="editUserForm" method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    <!-- ===== KOLOM KIRI (8/12) ===== -->
                    <div class="col-lg-8">

                        <!-- Informasi Dasar -->
                        <div class="info-card">
                            <div class="info-card-header">
                                <div class="info-card-icon icon-blue">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <h3>Informasi Dasar</h3>
                                    <p>Nama, email, dan password pengguna</p>
                                </div>
                            </div>
                            <div class="info-card-body">

                                <!-- User meta badge -->
                                <div class="user-meta-badge">
                                    <span><i class="fas fa-hashtag" style="color:var(--green-main)"></i> ID: <strong>#{{ $user->id }}</strong></span>
                                    <span><i class="fas fa-calendar" style="color:var(--green-main)"></i> Terdaftar: <strong>{{ $user->created_at->format('d M Y') }}</strong></span>
                                    <span>
                                        @if($user->email_verified_at)
                                            <i class="fas fa-check-circle" style="color:#16a34a"></i> <strong style="color:#16a34a">Email Terverifikasi</strong>
                                        @else
                                            <i class="fas fa-clock" style="color:#9ca3af"></i> <span style="color:#9ca3af">Belum Verifikasi</span>
                                        @endif
                                    </span>
                                </div>

                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label-custom">Nama Lengkap <span style="color:#dc2626">*</span></label>
                                        <input type="text" name="name"
                                               class="form-control-custom @error('name') is-invalid @enderror"
                                               value="{{ old('name', $user->name) }}" required>
                                        @error('name')<div class="invalid-feedback-custom">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label-custom">Email <span style="color:#dc2626">*</span></label>
                                        <input type="email" name="email"
                                               class="form-control-custom @error('email') is-invalid @enderror"
                                               value="{{ old('email', $user->email) }}" required>
                                        @error('email')<div class="invalid-feedback-custom">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="section-divider mt-4">Ganti Password</div>
                                <p style="font-size:0.78rem;color:#9ca3af;margin-bottom:14px;">Kosongkan jika tidak ingin mengganti password.</p>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label-custom">Password Baru</label>
                                        <div class="input-pw-wrap">
                                            <input type="password" name="password" id="password"
                                                   class="form-control-custom @error('password') is-invalid @enderror"
                                                   placeholder="Minimal 8 karakter">
                                            <button type="button" class="btn-pw-toggle" onclick="togglePassword('password','eye1')">
                                                <i class="fas fa-eye" id="eye1"></i>
                                            </button>
                                        </div>
                                        @error('password')<div class="invalid-feedback-custom">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label-custom">Konfirmasi Password Baru</label>
                                        <div class="input-pw-wrap">
                                            <input type="password" name="password_confirmation" id="password_confirmation"
                                                   class="form-control-custom"
                                                   placeholder="Ulangi password baru">
                                            <button type="button" class="btn-pw-toggle" onclick="togglePassword('password_confirmation','eye2')">
                                                <i class="fas fa-eye" id="eye2"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Informasi Tambahan -->
                        <div class="info-card">
                            <div class="info-card-header">
                                <div class="info-card-icon icon-orange">
                                    <i class="fas fa-address-card"></i>
                                </div>
                                <div>
                                    <h3>Informasi Tambahan</h3>
                                    <p>Telepon dan alamat pengguna</p>
                                </div>
                            </div>
                            <div class="info-card-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label-custom">Nomor Telepon</label>
                                        <input type="text" name="phone"
                                        class="form-control-custom @error('phone') is-invalid @enderror"
                                        value="{{ old('phone', $user->phone ?? '') }}"
                                        placeholder="081234567890">
                                        @error('phone')<div class="invalid-feedback-custom">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label-custom">Alamat</label>
                                        <textarea name="address"
                                                  class="form-control-custom @error('address') is-invalid @enderror"
                                                  rows="4">{{ old('address', $user->address) }}</textarea>
                                        @error('address')<div class="invalid-feedback-custom">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Hak Akses -->
                        <div class="info-card">
                            <div class="info-card-header">
                                <div class="info-card-icon icon-purple">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <div>
                                    <h3>Hak Akses</h3>
                                    <p>Role dan izin pengguna</p>
                                </div>
                            </div>
                            <div class="info-card-body">
                                <label class="check-card" for="is_admin">
                                    <input class="form-check-input" type="checkbox"
                                           name="is_admin" id="is_admin"
                                           {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}
                                           {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                    <div>
                                        <div class="check-card-label"><i class="fas fa-shield-alt me-1" style="color:var(--green-main)"></i> Jadikan Admin</div>
                                        @if($user->id === auth()->id())
                                            <div class="check-card-desc text-danger">Anda tidak dapat mengubah role akun sendiri.</div>
                                        @else
                                            <div class="check-card-desc">Admin memiliki akses penuh ke seluruh dashboard.</div>
                                        @endif
                                    </div>
                                </label>
                            </div>
                        </div>

                    </div>
                    <!-- Akhir col-lg-8 -->

                    <!-- ===== KOLOM KANAN (4/12) ===== -->
                    <div class="col-lg-4">

                        <!-- User Profile Card -->
                        <div class="info-card">
                            <div class="info-card-header">
                                <div class="info-card-icon icon-green">
                                    <i class="fas fa-id-card"></i>
                                </div>
                                <div>
                                    <h3>Profil Pengguna</h3>
                                    <p>Ringkasan akun</p>
                                </div>
                            </div>
                            <div class="info-card-body" style="text-align:center;">
                                <div style="width:64px;height:64px;border-radius:50%;
                                            background:linear-gradient(135deg,var(--green-main),var(--green-dark));
                                            display:flex;align-items:center;justify-content:center;
                                            font-size:1.5rem;color:white;font-weight:700;
                                            margin:0 auto 12px;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div style="font-weight:700;color:#111827;font-size:0.95rem;margin-bottom:4px;">{{ $user->name }}</div>
                                <div style="font-size:0.78rem;color:#6b7280;margin-bottom:12px;">{{ $user->email }}</div>
                                <div style="display:inline-flex;align-items:center;gap:6px;
                                            padding:4px 12px;border-radius:20px;font-size:0.75rem;font-weight:700;
                                            {{ $user->is_admin ? 'background:#dbeafe;color:#1e40af;' : 'background:#dcfce7;color:#14532d;' }}">
                                    <i class="fas {{ $user->is_admin ? 'fa-shield-alt' : 'fa-user' }}"></i>
                                    {{ $user->is_admin ? 'Admin' : 'User' }}
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="info-card">
                            <div class="info-card-header">
                                <div class="info-card-icon icon-gray">
                                    <i class="fas fa-cog"></i>
                                </div>
                                <div>
                                    <h3>Aksi</h3>
                                    <p>Simpan atau batalkan perubahan</p>
                                </div>
                            </div>
                            <div class="info-card-body" style="display:flex;flex-direction:column;gap:10px;">
                                <button type="submit" form="editUserForm"
                                        style="width:100%;padding:10px 16px;border-radius:9px;border:none;
                                               background:linear-gradient(135deg,var(--green-main),var(--green-dark));
                                               color:white;font-weight:700;font-size:0.875rem;cursor:pointer;
                                               display:flex;align-items:center;justify-content:center;gap:8px;
                                               transition:all 0.2s;">
                                    <i class="fas fa-save"></i> Simpan Perubahan
                                </button>
                                <a href="{{ route('admin.users.show', $user) }}"
                                   style="width:100%;padding:10px 16px;border-radius:9px;
                                          border:1.5px solid #bfdbfe;background:#eff6ff;
                                          color:#2563eb;font-weight:600;font-size:0.875rem;
                                          display:flex;align-items:center;justify-content:center;gap:8px;
                                          text-decoration:none;transition:all 0.2s;">
                                    <i class="fas fa-eye"></i> Lihat Detail
                                </a>
                                <a href="{{ route('admin.users') }}"
                                   style="width:100%;padding:10px 16px;border-radius:9px;
                                          border:1.5px solid #e5e7eb;background:#f9fafb;
                                          color:#374151;font-weight:600;font-size:0.875rem;
                                          display:flex;align-items:center;justify-content:center;gap:8px;
                                          text-decoration:none;transition:all 0.2s;">
                                    <i class="fas fa-times"></i> Batal
                                </a>
                            </div>
                        </div>

                    </div>
                    <!-- Akhir col-lg-4 -->

                </div>
            </form>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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

        function togglePassword(fieldId, iconId) {
            const field = document.getElementById(fieldId);
            const icon  = document.getElementById(iconId);
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.alert').forEach(function(alert) {
                setTimeout(function() {
                    const a = bootstrap.Alert.getOrCreateInstance(alert);
                    if (a) a.close();
                }, 5000);
            });
        });
    </script>
</body>
</html>