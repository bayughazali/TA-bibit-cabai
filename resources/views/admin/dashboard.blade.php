<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Bibit Cabai</title>
    
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
        
        .menu-item:hover, .menu-item.active {
            background: rgba(255,255,255,0.1);
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
        
        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            border-left: 5px solid;
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-card.products { border-left-color: #28a745; }
        .stat-card.orders { border-left-color: #007bff; }
        .stat-card.revenue { border-left-color: #ffc107; }
        .stat-card.users { border-left-color: #6f42c1; }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 8px;
        }
        
        .stat-label {
            color: #6c757d;
            font-size: 1rem;
            font-weight: 500;
        }
        
        .stat-icon {
            float: right;
            font-size: 2.5rem;
            opacity: 0.3;
            margin-top: -10px;
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
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }
        
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.3;
        }
        
        .empty-state h4 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        
        .empty-state p {
            font-size: 1rem;
            margin-bottom: 20px;
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
            
            .stats-grid {
                grid-template-columns: 1fr;
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
                <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>Dashboard
                </a>
                <a href="{{ route('admin.products.index') }}" class="menu-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <i class="fas fa-seedling"></i>Kelola Produk
                </a>
                <a href="{{ route('admin.orders') }}" class="menu-item {{ request()->routeIs('admin.orders') ? 'active' : '' }}">
                    <i class="fas fa-shopping-cart"></i>Pesanan
                </a>
                <a href="{{ route('admin.users') }}" class="menu-item {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>Pengguna
                </a>
                <a href="{{ route('admin.reports') }}" class="menu-item {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i>Laporan
                </a>
                <a href="{{ route('admin.settings') }}" class="menu-item {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i>Pengaturan
                </a>
                <div class="mt-4 px-3">
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm w-100" onclick="return confirm('Apakah Anda yakin ingin logout?')">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="main-content">
            <div class="admin-header">
                <h2>Dashboard</h2>
                <p class="mb-0">Selamat datang di dashboard admin!</p>
            </div>

            <div class="content-area">
                <div class="stats-grid">
                    <div class="stat-card products">
                        <div class="stat-number text-success">{{ $totalProducts ?? 0 }}</div>
                        <div class="stat-label">Total Produk</div>
                        <i class="fas fa-seedling stat-icon text-success"></i>
                    </div>
                    <div class="stat-card orders">
                        <div class="stat-number text-primary">{{ $activeProducts ?? 0 }}</div>
                        <div class="stat-label">Produk Aktif</div>
                        <i class="fas fa-check-circle stat-icon text-primary"></i>
                    </div>
                    <div class="stat-card revenue">
                        <div class="stat-number text-warning">{{ $totalSold ?? 0 }}</div>
                        <div class="stat-label">Total Terjual</div>
                        <i class="fas fa-chart-line stat-icon text-warning"></i>
                    </div>
                    <div class="stat-card users">
                        <div class="stat-number text-info">{{ $lowStock ?? 0 }}</div>
                        <div class="stat-label">Stok Menipis</div>
                        <i class="fas fa-exclamation-triangle stat-icon text-info"></i>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0">📈 Grafik Penjualan</h5>
                            </div>
                            <div class="card-body">
                                <div class="empty-state">
                                    <i class="fas fa-chart-line"></i>
                                    <h4>Belum Ada Data</h4>
                                    <p>Grafik penjualan akan ditampilkan setelah ada transaksi</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0">⚡ Aktivitas Terbaru</h5>
                            </div>
                            <div class="card-body">
                                <div class="empty-state">
                                    <i class="fas fa-clock"></i>
                                    <h4>Belum Ada Aktivitas</h4>
                                    <p>Aktivitas terbaru akan ditampilkan di sini</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>