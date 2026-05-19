<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Produk - Bibit Cabai Admin</title>
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

        /* ===== PAGE HEADER CARD ===== */
        .page-header {
            background: white;
            padding: 24px;
            border-radius: 14px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 10px rgba(0,0,0,0.04);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .section-title {
            font-size: 1rem;
            font-weight: 700;
            color: #111827;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* ===== FILTER ROW ===== */
        .filter-row { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 20px; }
        .filter-row .search-wrap { flex: 1 1 200px; }
        .filter-row .select-wrap { flex: 1 1 140px; }

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

        /* ===== PRODUCT INFO ===== */
        .product-image { width: 54px; height: 54px; object-fit: cover; border-radius: 8px; flex-shrink: 0; }
        .product-info { display: flex; align-items: center; gap: 10px; }
        .product-details h6 { margin: 0; font-weight: 600; color: #111827; font-size: 0.88rem; }
        .product-details small { color: #9ca3af; font-size: 0.78rem; }

        /* ===== BADGES ===== */
        .status-badge { padding: 4px 10px; border-radius: 12px; font-size: 0.72rem; font-weight: 600; display: inline-block; }
        .status-badge.success { background: #dcfce7; color: #166534; }
        .status-badge.warning { background: #fef3c7; color: #92400e; }
        .status-badge.danger  { background: #fee2e2; color: #991b1b; }

        .category-badge {
            background: #2563eb; color: white;
            padding: 4px 9px; border-radius: 12px; font-size: 0.72rem; font-weight: 600;
            white-space: nowrap;
        }

        .label-badge { color: white; padding: 4px 9px; border-radius: 12px; font-size: 0.72rem; font-weight: 600; }
        .label-badge.bg-success   { background: #16a34a !important; }
        .label-badge.bg-danger    { background: #dc2626 !important; }
        .label-badge.bg-warning   { background: #d97706 !important; color: white !important; }
        .label-badge.bg-secondary { background: #6b7280 !important; }

        .stock-badge {
            background: #0891b2; color: white;
            padding: 4px 9px; border-radius: 12px;
            font-size: 0.72rem; font-weight: 700;
        }

        /* ===== ACTION BUTTONS ===== */
        .action-buttons { display: flex; gap: 5px; flex-wrap: nowrap; }

        .btn-action {
            padding: 6px 9px;
            border: none; border-radius: 6px;
            font-size: 0.8rem; cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex; align-items: center; justify-content: center;
        }

        .btn-edit   { background: #d97706; color: white; }
        .btn-delete { background: #dc2626; color: white; }

        .btn-action:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
            text-decoration: none; color: inherit;
        }

        /* ===== MODAL ===== */
        .modal-backdrop-custom {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,0.5); z-index: 2000;
            align-items: center; justify-content: center;
            opacity: 0; transition: opacity 0.3s;
        }

        .modal-backdrop-custom.show { display: flex; opacity: 1; }

        .modal-content-custom {
            background: white; padding: 28px 24px;
            border-radius: 14px; max-width: 380px; width: 90%;
            text-align: center;
            transform: scale(0.85); transition: transform 0.3s;
        }

        .modal-backdrop-custom.show .modal-content-custom { transform: scale(1); }

        /* ===== EMPTY STATE ===== */
        .empty-state { text-align: center; padding: 50px 20px; color: #9ca3af; }
        .empty-state i { font-size: 3.5rem; margin-bottom: 16px; opacity: 0.4; display: block; }

        /* ===== MOBILE CARDS ===== */
        .mobile-product-list { display: none; }

        .product-card {
            background: white; border-radius: 12px;
            padding: 14px; margin-bottom: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 10px rgba(0,0,0,0.04);
            display: flex; gap: 12px; align-items: flex-start;
        }

        .product-card-img { width: 64px; height: 64px; object-fit: cover; border-radius: 8px; flex-shrink: 0; }
        .product-card-body { flex: 1; min-width: 0; }
        .product-card-name { font-weight: 700; font-size: 0.95rem; color: #111827; margin-bottom: 4px; }
        .product-card-desc { font-size: 0.78rem; color: #9ca3af; margin-bottom: 8px; }
        .product-card-meta { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 10px; }
        .product-card-price { font-weight: 700; color: var(--green-main); font-size: 0.9rem; }
        .product-card-actions { display: flex; gap: 6px; }

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
            .mobile-product-list { display: block; }
            .section-header { flex-direction: column; align-items: stretch; }
            .section-header .btn { width: 100%; }
            .filter-row .search-wrap,
            .filter-row .select-wrap { flex: 1 1 100%; }
            .content-area { padding: 14px 14px 20px; }
            .topbar { padding: 0 14px; }
            .btn-back-website span { display: none; }
            .btn-back-website { padding: 9px 12px; }
            .page-header { padding: 16px; }
        }

        @media (min-width: 768px) and (max-width: 900px) {
            .data-table th:nth-child(6),
            .data-table td:nth-child(6) { display: none; }
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

    <!-- Main Content -->
    <div class="main-content">

        <!-- Topbar -->
        <header class="topbar">
            <div class="topbar-left">
                <button class="hamburger-btn" id="hamburgerBtn" onclick="toggleSidebar()" aria-label="Toggle menu">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="topbar-title">
                    <div class="topbar-breadcrumb">
                        <a href="{{ route('admin.dashboard') }}"><i class="fas fa-home"></i> Dashboard</a>
                        &nbsp;/&nbsp;Kelola Produk
                    </div>
                    <h2>Kelola Produk</h2>
                    <p>Kelola semua produk bibit cabai Anda di sini!</p>
                </div>
            </div>
            <a href="{{ route('home') }}" class="btn-back-website">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali ke Website</span>
            </a>
        </header>

        <!-- Content -->
        <div class="content-area fade-in">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="page-header">
                <div class="section-header">
                    <div class="section-title">
                        <i class="fas fa-seedling text-success"></i>
                        Daftar Produk ({{ $products->total() ?? 0 }})
                    </div>
                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Tambah Produk
                    </a>
                </div>

                <!-- Filter Form -->
                <form method="GET" action="{{ route('admin.products.index') }}" id="filterForm">
                    <div class="filter-row">
                        <div class="search-wrap">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" name="search"
                                       value="{{ request('search') }}" placeholder="Cari produk..."
                                       id="searchProduct">
                            </div>
                        </div>
                        <div class="select-wrap">
                            <select class="form-select" name="category" id="filterCategory">
                                <option value="">Semua Kategori</option>
                                <option value="Sayuran" {{ request('category') === 'Sayuran' ? 'selected' : '' }}>Sayuran</option>
                                <option value="Buah"    {{ request('category') === 'Buah'    ? 'selected' : '' }}>Buah</option>
                                <option value="Herbal"  {{ request('category') === 'Herbal'  ? 'selected' : '' }}>Herbal</option>
                                <option value="Hias"    {{ request('category') === 'Hias'    ? 'selected' : '' }}>Hias</option>
                            </select>
                        </div>
                        <div class="select-wrap">
                            <select class="form-select" name="status" id="filterStatus">
                                <option value="">Semua Status</option>
                                <option value="aktif"    {{ request('status') === 'aktif'    ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Non-aktif</option>
                            </select>
                        </div>
                    </div>
                </form>

                @if($products && $products->count() > 0)

                    <!-- DESKTOP TABLE -->
                    <div class="desktop-table-wrap table-responsive">
                        <table class="data-table" id="productsTable">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Kategori</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th>Status</th>
                                    <th>Label</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="productsTableBody">
                                @foreach($products as $product)
                                    <tr>
                                        <td>
                                            <div class="product-info">
                                                <img src="{{ $product->image_url }}"
                                                     alt="{{ $product->name }}"
                                                     class="product-image"
                                                     onerror="this.src='https://via.placeholder.com/60x60?text=No+Image'">
                                                <div class="product-details">
                                                    <h6>{{ $product->name }}</h6>
                                                    <small>{{ Str::limit($product->description, 30) }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="category-badge">{{ $product->category }}</span></td>
                                        <td><strong style="color:var(--green-main);">{{ $product->formatted_price }}</strong></td>
                                        <td><span class="stock-badge">{{ number_format($product->stock) }}</span></td>
                                        <td>
                                            <span class="{{ $product->status_badge_class }}">
                                                {{ ucfirst($product->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="label-badge {{ $product->label_badge_class }}">
                                                {{ ucfirst($product->label) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('admin.products.edit', $product) }}"
                                                   class="btn-action btn-edit" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn-action btn-delete"
                                                        onclick="deleteProduct({{ $product->id }}, '{{ $product->name }}')"
                                                        title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- MOBILE CARD LIST -->
                    <div class="mobile-product-list">
                        @foreach($products as $product)
                            <div class="product-card">
                                <img src="{{ $product->image_url }}"
                                     alt="{{ $product->name }}"
                                     class="product-card-img"
                                     onerror="this.src='https://via.placeholder.com/64x64?text=No+Image'">
                                <div class="product-card-body">
                                    <div class="product-card-name">{{ $product->name }}</div>
                                    <div class="product-card-desc">{{ Str::limit($product->description, 50) }}</div>
                                    <div class="product-card-meta">
                                        <span class="category-badge">{{ $product->category }}</span>
                                        <span class="{{ $product->status_badge_class }}">{{ ucfirst($product->status) }}</span>
                                        <span class="label-badge {{ $product->label_badge_class }}">{{ ucfirst($product->label) }}</span>
                                        <span class="stock-badge">Stok: {{ number_format($product->stock) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="product-card-price">{{ $product->formatted_price }}</span>
                                        <div class="product-card-actions">
                                            <a href="{{ route('admin.products.edit', $product) }}"
                                               class="btn-action btn-edit" title="Edit">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <button type="button" class="btn-action btn-delete"
                                                    onclick="deleteProduct({{ $product->id }}, '{{ $product->name }}')"
                                                    title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">
                        <small style="color:#6b7280;">
                            Menampilkan {{ $products->firstItem() }}–{{ $products->lastItem() }}
                            dari {{ $products->total() }} produk
                        </small>
                        <div>{{ $products->appends(request()->query())->links() }}</div>
                    </div>

                @else
                    <div class="empty-state">
                        <i class="fas fa-seedling"></i>
                        <h4>Belum Ada Produk</h4>
                        <p>Mulai dengan menambahkan produk bibit cabai pertama Anda!</p>
                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary mt-3">
                            <i class="fas fa-plus me-2"></i>Tambah Produk Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal-backdrop-custom">
        <div class="modal-content-custom">
            <h4 class="text-danger mb-3">
                <i class="fas fa-exclamation-triangle"></i> Konfirmasi Hapus
            </h4>
            <p class="mb-2">Apakah Anda yakin ingin menghapus produk:</p>
            <p class="mb-4"><strong id="productNameToDelete"></strong></p>
            <p class="text-muted small mb-4">Aksi ini tidak dapat dibatalkan.</p>
            <div class="d-flex gap-2 justify-content-center">
                <button class="btn btn-secondary" onclick="closeDeleteModal()">Batal</button>
                <button class="btn btn-danger" onclick="confirmDelete()">Hapus</button>
            </div>
        </div>
    </div>

    <!-- Delete Form (Hidden) -->
    <form id="deleteForm" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>

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

        // ===== FILTERS =====
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput    = document.getElementById('searchProduct');
            const categoryFilter = document.getElementById('filterCategory');
            const statusFilter   = document.getElementById('filterStatus');
            let searchTimeout;

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(function() {
                        document.getElementById('filterForm').submit();
                    }, 500);
                });
            }

            if (categoryFilter) categoryFilter.addEventListener('change', () => document.getElementById('filterForm').submit());
            if (statusFilter)   statusFilter.addEventListener('change',   () => document.getElementById('filterForm').submit());

            document.querySelectorAll('.alert').forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(alert);
                    if (bsAlert) bsAlert.close();
                }, 5000);
            });
        });

        // ===== DELETE MODAL =====
        let productToDelete = null;

        function deleteProduct(id, name) {
            productToDelete = id;
            document.getElementById('productNameToDelete').textContent = name;
            document.getElementById('deleteModal').classList.add('show');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('show');
            productToDelete = null;
        }

        function confirmDelete() {
            if (productToDelete) {
                const form = document.getElementById('deleteForm');
                form.action = `/admin/products/${productToDelete}`;
                form.submit();
            }
        }

        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) closeDeleteModal();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeDeleteModal();
        });

        setTimeout(function() {
            const successAlert = document.querySelector('.alert-success');
            if (successAlert) {
                successAlert.style.transition = 'opacity 0.5s';
                successAlert.style.opacity = '0';
                setTimeout(() => successAlert.remove(), 500);
            }
        }, 3000);
    </script>
</body>
</html>