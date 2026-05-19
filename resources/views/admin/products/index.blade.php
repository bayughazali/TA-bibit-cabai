<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Produk - Bibit Cabai Admin</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom Styles -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
        }
        
        .admin-container {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, #28a745, #20c997);
            color: white;
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }
        
        .sidebar-header {
            padding: 25px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-header h3 {
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .sidebar-header p {
            opacity: 0.8;
            font-size: 0.9rem;
        }
        
        .sidebar-menu {
            padding: 20px 0;
        }
        
        .menu-item {
            display: block;
            padding: 15px 25px;
            color: white;
            text-decoration: none;
            transition: all 0.3s;
            border-left: 4px solid transparent;
            cursor: pointer;
        }
        
        .menu-item:hover {
            background: rgba(255,255,255,0.1);
            color: white;
            text-decoration: none;
            border-left-color: #fff;
            transform: translateX(5px);
        }
        
        .menu-item.active {
            background: rgba(255,255,255,0.2);
            color: white;
            text-decoration: none;
            border-left-color: #fff;
            transform: translateX(5px);
        }
        
        .menu-item i {
            width: 20px;
            margin-right: 10px;
        }
        
        /* Main Content */
        .main-content {
            margin-left: 280px;
            flex: 1;
            min-height: 100vh;
        }
        
        .admin-header {
            background: white;
            padding: 20px 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-bottom: 1px solid #dee2e6;
        }
        
        .content-area {
            padding: 30px;
        }
        
        .page-header {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f8f9fa;
        }
        
        .section-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
        }
        
        /* Tables */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .data-table th,
        .data-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        
        .data-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #495057;
        }
        
        .data-table tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        /* Product Image */
        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }
        
        .product-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .product-details h6 {
            margin: 0;
            font-weight: 600;
            color: #333;
        }
        
        .product-details small {
            color: #6c757d;
        }
        
        /* Badges */
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .status-badge.success {
            background: #d4edda;
            color: #155724;
        }
        
        .status-badge.warning {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-badge.danger {
            background: #f8d7da;
            color: #721c24;
        }
        
        .category-badge {
            background: #007bff;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.8rem;
        }
        
        .label-badge {
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.8rem;
        }
        
        .label-badge.bg-success { background: #28a745 !important; }
        .label-badge.bg-danger { background: #dc3545 !important; }
        .label-badge.bg-warning { background: #ffc107 !important; color: #212529 !important; }
        .label-badge.bg-secondary { background: #6c757d !important; }
        
        .stock-badge {
            background: #20c997;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        
        .btn-action {
            padding: 6px 10px;
            border: none;
            border-radius: 6px;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-view { background: #17a2b8; color: white; }
        .btn-edit { background: #ffc107; color: #212529; }
        .btn-delete { background: #dc3545; color: white; }
        
        .btn-action:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            text-decoration: none;
            color: inherit;
        }
        
        /* Back Button */
        .back-button {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1100;
            background: linear-gradient(45deg, #dc3545, #c82333);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        
        .back-button:hover {
            background: linear-gradient(45deg, #c82333, #a71e2a);
            transform: translateY(-2px);
            color: white;
            text-decoration: none;
        }
        
        /* Breadcrumb */
        .breadcrumb {
            background: transparent;
            padding: 0;
            margin-bottom: 20px;
        }
        
        .breadcrumb-item a {
            color: #28a745;
            text-decoration: none;
        }
        
        .breadcrumb-item a:hover {
            color: #20c997;
        }
        
        /* Animation for transitions */
        .fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Modal Styles */
        .modal-backdrop {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 2000;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .modal-backdrop.show {
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 1;
        }

        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 12px;
            max-width: 400px;
            width: 90%;
            text-align: center;
            transform: scale(0.8);
            transition: transform 0.3s ease;
        }

        .modal-backdrop.show .modal-content {
            transform: scale(1);
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: relative;
                min-height: auto;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .section-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
        }
    </style>
</head>

<body>
    <!-- Back Button -->
    <a href="{{ route('home') }}" class="back-button">
        <i class="fas fa-arrow-left me-2"></i>Kembali ke Website
    </a>

    <div class="admin-container">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-header">
                <h3>🌱 Bibit Cabai</h3>
                <p>Admin Dashboard</p>
                <small class="text-light">{{ Auth::user()->name ?? 'Admin User' }}</small>
            </div>
            <div class="sidebar-menu">
                <a href="{{ route('admin.dashboard') }}" class="menu-item">
                    <i class="fas fa-tachometer-alt"></i>Dashboard
                </a>
                <a href="{{ route('admin.products.index') }}" class="menu-item active">
                    <i class="fas fa-seedling"></i>Kelola Produk
                </a>
                <a href="{{ route('admin.orders') }}" class="menu-item">
                    <i class="fas fa-shopping-cart"></i>Pesanan
                </a>
                 {{-- ↓ TAMBAHKAN DI SINI ↓ --}}
                <a href="{{ route('admin.cancellations') }}" class="menu-item">
                    <i class="fas fa-times-circle"></i>Pengajuan Batal
                </a>
                <a href="{{ route('admin.users') }}" class="menu-item">
                    <i class="fas fa-users"></i>Pengguna
                </a>
                <a href="{{ route('admin.laporan') }}" class="menu-item">
                    <i class="fas fa-chart-line"></i>Laporan
                </a>
                <!-- <a href="{{ route('admin.settings') }}" class="menu-item">
                    <i class="fas fa-cog"></i>Pengaturan
                </a> -->
                <div class="mt-4 px-3">
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm w-100">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="main-content">
            <div class="admin-header">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-home"></i> Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Kelola Produk</li>
                    </ol>
                </nav>
                <h2>Kelola Produk</h2>
                <p class="mb-0">Kelola semua produk bibit cabai Anda di sini!</p>
            </div>

            <div class="content-area fade-in">
                <!-- Display Success/Error Messages -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="page-header">
                    <div class="section-header">
                        <h3 class="section-title">
                            <i class="fas fa-seedling text-success me-2"></i>
                            Daftar Produk ({{ $products->total() ?? 0 }})
                        </h3>
                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Tambah Produk
                        </a>
                    </div>
                    
                    <!-- Search and Filter Form -->
                    <form method="GET" action="{{ route('admin.products.index') }}" id="filterForm">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    <input type="text" class="form-control" name="search" 
                                           value="{{ request('search') }}" placeholder="Cari produk..." 
                                           id="searchProduct">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" name="category" id="filterCategory">
                                    <option value="">Semua Kategori</option>
                                    <option value="Sayuran" {{ request('category') === 'Sayuran' ? 'selected' : '' }}>Sayuran</option>
                                    <option value="Buah" {{ request('category') === 'Buah' ? 'selected' : '' }}>Buah</option>
                                    <option value="Herbal" {{ request('category') === 'Herbal' ? 'selected' : '' }}>Herbal</option>
                                    <option value="Hias" {{ request('category') === 'Hias' ? 'selected' : '' }}>Hias</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" name="status" id="filterStatus">
                                    <option value="">Semua Status</option>
                                    <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Non-aktif</option>
                                </select>
                            </div>
                        </div>
                    </form>
                    
                    <!-- Products Table -->
                    @if($products && $products->count() > 0)
                        <div class="table-responsive">
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
                                                        <small class="text-muted">{{ Str::limit($product->description, 30) }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="category-badge">{{ $product->category }}</span>
                                            </td>
                                            <td>
                                                <strong class="text-success">{{ $product->formatted_price }}</strong>
                                            </td>
                                            <td>
                                                <span class="stock-badge">{{ number_format($product->stock) }}</span>
                                            </td>
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
                                                <!-- <div class="action-buttons">
                                                    <a href="{{ route('admin.products.show', $product) }}" 
                                                       class="btn-action btn-view" title="Lihat">
                                                        <i class="fas fa-eye"></i>
                                                    </a> -->
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
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div>
                                <small class="text-muted">
                                    Menampilkan {{ $products->firstItem() }} sampai {{ $products->lastItem() }} 
                                    dari {{ $products->total() }} produk
                                </small>
                            </div>
                            <div>
                                {{ $products->appends(request()->query())->links() }}
                            </div>
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="empty-state">
                            <i class="fas fa-seedling"></i>
                            <h4>Belum Ada Produk</h4>
                            <p>Mulai dengan menambahkan produk bibit cabai pertama Anda!</p>
                            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Tambah Produk Pertama
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal-backdrop">
        <div class="modal-content">
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
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Global variables for delete functionality
        let productToDelete = null;
        let productNameToDelete = '';

        // Auto-submit form when filters change
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchProduct');
            const categoryFilter = document.getElementById('filterCategory');
            const statusFilter = document.getElementById('filterStatus');
            let searchTimeout;

            // Debounced search
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(function() {
                        document.getElementById('filterForm').submit();
                    }, 500);
                });
            }

            // Immediate filter change
            if (categoryFilter) {
                categoryFilter.addEventListener('change', function() {
                    document.getElementById('filterForm').submit();
                });
            }

            if (statusFilter) {
                statusFilter.addEventListener('change', function() {
                    document.getElementById('filterForm').submit();
                });
            }

            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(alert);
                    if (bsAlert) {
                        bsAlert.close();
                    }
                }, 5000);
            });
        });

        // Delete product function with modal
        function deleteProduct(id, name) {
            productToDelete = id;
            productNameToDelete = name;
            
            document.getElementById('productNameToDelete').textContent = name;
            
            const modal = document.getElementById('deleteModal');
            modal.classList.add('show');
        }

        // Close delete modal
        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.remove('show');
            productToDelete = null;
            productNameToDelete = '';
        }

        // Confirm delete
        function confirmDelete() {
            if (productToDelete) {
                const form = document.getElementById('deleteForm');
                form.action = `/admin/products/${productToDelete}`;
                form.submit();
            }
        }

        // Close modal when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });

        // Escape key to close modal
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDeleteModal();
            }
        });

        // Image error handling
        document.addEventListener('DOMContentLoaded', function() {
            const images = document.querySelectorAll('.product-image');
            images.forEach(function(img) {
                img.addEventListener('error', function() {
                    this.src = 'https://via.placeholder.com/60x60?text=No+Image&color=6c757d&background=f8f9fa';
                });
            });
        });

        // Success message auto-hide
        setTimeout(function() {
            const successAlert = document.querySelector('.alert-success');
            if (successAlert) {
                successAlert.style.transition = 'opacity 0.5s';
                successAlert.style.opacity = '0';
                setTimeout(function() {
                    successAlert.remove();
                }, 500);
            }
        }, 3000);
    </script>
</body>
</html>