<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - Bibit Cabai</title>
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

        .btn-back-header {
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

        .btn-back-header:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(220,38,38,0.35);
            color: white;
            text-decoration: none;
        }

        /* ===== CONTENT AREA ===== */
        .content-area { padding: 20px 24px 32px; flex: 1; }

        /* ===== FORM CARD ===== */
        .form-card {
            background: white;
            border-radius: 14px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 10px rgba(0,0,0,0.04);
            overflow: hidden;
        }

        .form-card-header {
            padding: 20px 24px 16px;
            border-bottom: 1px solid #f3f4f6;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-card-header-icon {
            width: 38px; height: 38px;
            background: #fef9c3;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: #ca8a04;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .form-card-header h3 { font-size: 1rem; font-weight: 700; color: #111827; margin: 0; }
        .form-card-header p { font-size: 0.78rem; color: #6b7280; margin: 0; }

        .form-card-body { padding: 24px; }

        /* ===== FORM ELEMENTS ===== */
        .form-label {
            font-size: 0.82rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }

        .req { color: #dc2626; }

        .form-control, .form-select {
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            font-size: 0.875rem;
            padding: 9px 12px;
            color: #111827;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--green-main);
            box-shadow: 0 0 0 3px rgba(22,163,74,0.12);
            outline: none;
        }

        .form-control.is-invalid, .form-select.is-invalid { border-color: #dc2626; }

        .input-group-text {
            background: #f9fafb;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px 0 0 8px;
            color: #6b7280;
            font-size: 0.875rem;
        }

        .input-group .form-control,
        .input-group .form-select { border-radius: 0 8px 8px 0; }

        .form-text { font-size: 0.75rem; color: #9ca3af; margin-top: 4px; }
        textarea.form-control { resize: vertical; min-height: 100px; }

        /* ===== CURRENT IMAGE ===== */
        .current-image-box {
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            padding: 14px;
            background: #f9fafb;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .current-image-box img {
            width: 80px; height: 80px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            flex-shrink: 0;
        }

        .current-image-info { font-size: 0.8rem; color: #6b7280; }
        .current-image-info strong { display: block; color: #374151; margin-bottom: 3px; font-size: 0.82rem; }

        /* ===== IMAGE UPLOAD ===== */
        .image-upload-area {
            border: 2px dashed #d1d5db;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            background: #fafafa;
            position: relative;
        }

        .image-upload-area:hover { border-color: var(--green-main); background: #f0fdf4; }
        .image-upload-area input[type=file] {
            position: absolute; inset: 0; width: 100%; height: 100%;
            opacity: 0; cursor: pointer;
        }

        .upload-icon { font-size: 2rem; color: #d1d5db; margin-bottom: 8px; }
        .upload-text { font-size: 0.82rem; color: #6b7280; }
        .upload-hint { font-size: 0.72rem; color: #9ca3af; margin-top: 4px; }

        .image-preview-box { display: none; margin-top: 14px; }
        .image-preview-box img {
            max-width: 150px; max-height: 150px;
            border-radius: 10px; object-fit: cover;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        /* ===== INFO BANNER ===== */
        .info-banner {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 10px;
            padding: 12px 16px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: 0.82rem;
            color: #92400e;
            margin-bottom: 20px;
        }

        .info-banner i { margin-top: 1px; flex-shrink: 0; }

        /* ===== SECTION DIVIDER ===== */
        .section-divider {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #9ca3af;
            padding-bottom: 10px;
            border-bottom: 1px solid #f3f4f6;
            margin-bottom: 18px;
        }

        /* ===== ACTION BUTTONS ===== */
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            flex-wrap: wrap;
            padding-top: 20px;
            border-top: 1px solid #f3f4f6;
            margin-top: 24px;
        }

        .btn-cancel {
            background: #f9fafb;
            color: #374151;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            padding: 10px 20px;
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex; align-items: center; gap: 7px;
            transition: all 0.2s;
        }

        .btn-cancel:hover { background: #f3f4f6; color: #111827; text-decoration: none; }

        .btn-reset-form {
            background: #fff7ed;
            color: #c2410c;
            border: 1.5px solid #fed7aa;
            border-radius: 8px;
            padding: 10px 20px;
            font-size: 0.875rem;
            font-weight: 600;
            display: inline-flex; align-items: center; gap: 7px;
            transition: all 0.2s;
            cursor: pointer;
        }

        .btn-reset-form:hover { background: #ffedd5; }

        .btn-save {
            background: linear-gradient(135deg, #ca8a04, #a16207);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 10px 24px;
            font-size: 0.875rem;
            font-weight: 700;
            display: inline-flex; align-items: center; gap: 7px;
            transition: all 0.2s;
            cursor: pointer;
        }

        .btn-save:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(202,138,4,0.35);
            color: white;
        }

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
            .topbar { padding: 0 14px; height: 58px; }
            .btn-back-header span { display: none; }
            .btn-back-header { padding: 9px 12px; }
            .form-card-body { padding: 16px; }
            .form-actions { justify-content: stretch; }
            .form-actions > * { flex: 1; justify-content: center; }
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
            <a href="{{ route('admin.products.index') }}" class="menu-item active">
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
                        &nbsp;/&nbsp;<a href="{{ route('admin.products.index') }}">Kelola Produk</a>
                        &nbsp;/&nbsp;Edit Produk
                    </div>
                    <h2>Edit Produk</h2>
                    <p>Edit informasi produk {{ $product->name }}</p>
                </div>
            </div>
            <a href="{{ route('admin.products.index') }}" class="btn-back-header">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali ke Produk</span>
            </a>
        </header>

        <!-- Content -->
        <div class="content-area fade-in">

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>Terdapat kesalahan:</strong>
                    <ul class="mb-0 mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="form-card">
                <div class="form-card-header">
                    <div class="form-card-header-icon">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div>
                        <h3>Form Edit Produk</h3>
                        <p>Ubah data produk sesuai kebutuhan</p>
                    </div>
                </div>

                <div class="form-card-body">
                    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" id="productForm">
                        @csrf
                        @method('PUT')

                        <!-- INFORMASI DASAR -->
                        <div class="section-divider">Informasi Dasar</div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Produk <span class="req">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-seedling"></i></span>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           name="name" id="name"
                                           value="{{ old('name', $product->name) }}"
                                           placeholder="Contoh: Bibit Cabai Rawit Genni"
                                           required>
                                </div>
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Kategori <span class="req">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-list"></i></span>
                                    <select class="form-select @error('category') is-invalid @enderror"
                                            name="category" id="category" required>
                                        <option value="">Pilih Kategori</option>
                                        <option value="Sayuran" {{ old('category', $product->category) === 'Sayuran' ? 'selected' : '' }}>Sayuran</option>
                                        <option value="Buah"    {{ old('category', $product->category) === 'Buah'    ? 'selected' : '' }}>Buah</option>
                                        <option value="Herbal"  {{ old('category', $product->category) === 'Herbal'  ? 'selected' : '' }}>Herbal</option>
                                        <option value="Hias"    {{ old('category', $product->category) === 'Hias'    ? 'selected' : '' }}>Hias</option>
                                    </select>
                                </div>
                                @error('category')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- HARGA, STOK, STATUS, LABEL -->
                        <div class="row g-3 mb-3">
                            <div class="col-sm-6 col-md-3">
                                <label class="form-label">Harga <span class="req">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number"
                                           class="form-control @error('price') is-invalid @enderror"
                                           name="price" id="price"
                                           value="{{ old('price', $product->price) }}"
                                           min="0" step="1" placeholder="0" required>
                                </div>
                                @error('price')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-sm-6 col-md-3">
                                <label class="form-label">Stok <span class="req">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-boxes"></i></span>
                                    <input type="number"
                                           class="form-control @error('stock') is-invalid @enderror"
                                           name="stock" id="stock"
                                           value="{{ old('stock', $product->stock) }}"
                                           min="0" placeholder="0" required>
                                </div>
                                @error('stock')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-sm-6 col-md-3">
                                <label class="form-label">Status <span class="req">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-toggle-on"></i></span>
                                    <select class="form-select @error('status') is-invalid @enderror"
                                            name="status" id="status" required>
                                        <option value="aktif"    {{ old('status', $product->status) === 'aktif'    ? 'selected' : '' }}>Aktif</option>
                                        <option value="nonaktif" {{ old('status', $product->status) === 'nonaktif' ? 'selected' : '' }}>Non-aktif</option>
                                    </select>
                                </div>
                                @error('status')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-sm-6 col-md-3">
                                <label class="form-label">Label</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                    <select class="form-select @error('label') is-invalid @enderror"
                                            name="label" id="label">
                                        <option value="">Tidak Ada</option>
                                        <option value="baru"     {{ old('label', $product->label) === 'baru'     ? 'selected' : '' }}>Baru</option>
                                        <option value="populer"  {{ old('label', $product->label) === 'populer'  ? 'selected' : '' }}>Populer</option>
                                        <option value="diskon"   {{ old('label', $product->label) === 'diskon'   ? 'selected' : '' }}>Diskon</option>
                                        <option value="terbatas" {{ old('label', $product->label) === 'terbatas' ? 'selected' : '' }}>Terbatas</option>
                                    </select>
                                </div>
                                @error('label')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- DESKRIPSI -->
                        <div class="mb-3">
                            <label class="form-label">Deskripsi Produk <span class="req">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      name="description" id="description" rows="4"
                                      placeholder="Jelaskan keunggulan dan cara perawatan bibit..." required>{{ old('description', $product->description) }}</textarea>
                            <div class="form-text">Deskripsi yang detail akan membantu pelanggan memahami produk Anda.</div>
                            @error('description')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- GAMBAR -->
                        <div class="section-divider" style="margin-top:24px">Gambar Produk</div>

                        @if($product->image_url)
                        <div class="mb-3">
                            <label class="form-label">Gambar Saat Ini</label>
                            <div class="current-image-box">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                                <div class="current-image-info">
                                    <strong>{{ $product->name }}</strong>
                                    Gambar produk yang sedang aktif. Upload gambar baru di bawah untuk menggantinya.
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label">
                                Upload Gambar Baru
                                <span style="font-weight:400;color:#9ca3af">(Opsional — kosongkan jika tidak ingin mengubah)</span>
                            </label>
                            <div class="image-upload-area" id="uploadArea">
                                <input type="file" name="image" id="image" accept="image/*"
                                       onchange="previewImage(this)">
                                <div id="uploadPlaceholder">
                                    <div class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                                    <div class="upload-text">Klik atau seret gambar ke sini</div>
                                    <div class="upload-hint">JPG, PNG, GIF — Maks. 2MB — Disarankan 500×500px</div>
                                </div>
                            </div>
                            @error('image')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div class="image-preview-box" id="imagePreview">
                                <img id="previewImg" src="" alt="Preview">
                                <div class="form-text mt-1">
                                    <span id="previewName"></span>
                                    <a href="#" onclick="clearImage(); return false;" style="color:#dc2626;margin-left:8px">
                                        <i class="fas fa-times"></i> Hapus
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- INFO BANNER -->
                        <div class="info-banner">
                            <i class="fas fa-exclamation-triangle"></i>
                            <div>Pastikan semua perubahan sudah benar sebelum menyimpan. Perubahan akan langsung tampil di website.</div>
                        </div>

                        <!-- TOMBOL AKSI -->
                        <div class="form-actions">
                            <a href="{{ route('admin.products.index') }}" class="btn-cancel">
                                <i class="fas fa-times"></i> Batal
                            </a>
                            <button type="reset" class="btn-reset-form" onclick="clearImage()">
                                <i class="fas fa-undo"></i> Reset
                            </button>
                            <button type="submit" class="btn-save">
                                <i class="fas fa-save"></i> Update Produk
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ===== SIDEBAR =====
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

        // ===== IMAGE PREVIEW =====
        function previewImage(input) {
            if (input.files && input.files[0]) {
                if (input.files[0].size > 2 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar! Maksimal 2MB.');
                    input.value = '';
                    return;
                }
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImg').src = e.target.result;
                    document.getElementById('previewName').textContent = input.files[0].name;
                    document.getElementById('imagePreview').style.display = 'block';
                    document.getElementById('uploadPlaceholder').style.opacity = '0.4';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function clearImage() {
            document.getElementById('image').value = '';
            document.getElementById('imagePreview').style.display = 'none';
            document.getElementById('uploadPlaceholder').style.opacity = '1';
        }

        // ===== PRICE: digits only =====
        document.getElementById('price').addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // ===== DRAG & DROP =====
        const uploadArea = document.getElementById('uploadArea');
        ['dragover','dragenter'].forEach(evt => {
            uploadArea.addEventListener(evt, function(e) {
                e.preventDefault();
                this.style.borderColor = 'var(--green-main)';
                this.style.background = '#f0fdf4';
            });
        });
        ['dragleave','drop'].forEach(evt => {
            uploadArea.addEventListener(evt, function(e) {
                e.preventDefault();
                this.style.borderColor = '#d1d5db';
                this.style.background = '#fafafa';
            });
        });

        // Auto-dismiss alerts
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.alert').forEach(function(alert) {
                setTimeout(function() {
                    const a = bootstrap.Alert.getOrCreateInstance(alert);
                    if (a) a.close();
                }, 5000);
            });
            document.getElementById('name').focus();
        });
    </script>
</body>
</html>