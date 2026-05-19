<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kelola Pengguna - Bibit Cabai Admin</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
        }

        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, #28a745, #20c997);
            color: white;
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
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

        .sidebar-header p { opacity: 0.8; font-size: 0.9rem; }

        .sidebar-menu { padding: 20px 0; }

        .menu-item {
            display: block;
            padding: 15px 25px;
            color: white;
            text-decoration: none;
            transition: all 0.3s;
            border-left: 4px solid transparent;
        }

        .menu-item:hover, .menu-item.active {
            background: rgba(255,255,255,0.2);
            color: white;
            text-decoration: none;
            border-left-color: #fff;
            transform: translateX(5px);
        }

        .menu-item i { width: 20px; margin-right: 10px; }

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

        .content-area { padding: 30px; }

        .page-header {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }

        .breadcrumb { background: transparent; padding: 0; margin-bottom: 10px; }
        .breadcrumb-item a { color: #28a745; text-decoration: none; }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-left: 4px solid;
        }

        .stat-card.primary { border-left-color: #007bff; }
        .stat-card.warning { border-left-color: #ffc107; }
        .stat-card.info    { border-left-color: #17a2b8; }
        .stat-card.success { border-left-color: #28a745; }

        .stat-label {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .stat-card.primary .stat-label { color: #007bff; }
        .stat-card.warning .stat-label { color: #ffc107; }
        .stat-card.info    .stat-label { color: #17a2b8; }
        .stat-card.success .stat-label { color: #28a745; }

        .stat-value { font-size: 1.5rem; font-weight: 700; color: #333; }
        .stat-icon  { font-size: 2rem; color: #dee2e6; }

        /* Section Header */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f8f9fa;
        }

        .section-title { font-size: 1.3rem; font-weight: 600; color: #333; }

        /* Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        .data-table th, .data-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }

        .data-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #495057;
            font-size: 0.85rem;
        }

        .data-table tbody tr:hover { background-color: #f8f9fc; }

        /* Badges */
        .badge-role {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-admin    { background: #cce5ff; color: #004085; }
        .badge-user     { background: #d4edda; color: #155724; }
        .badge-verified { background: #d4edda; color: #155724; }
        .badge-unverified { background: #e2e3e5; color: #383d41; }

        /* Action Buttons */
        .btn-action {
            padding: 6px 10px;
            border: none;
            border-radius: 6px;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .btn-action:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            text-decoration: none;
            color: inherit;
        }

        .btn-view   { background: #17a2b8; color: white; }
        .btn-edit   { background: #ffc107; color: #212529; }
        .btn-delete { background: #dc3545; color: white; }

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
            transition: all 0.3s;
            text-decoration: none;
        }

        .back-button:hover {
            background: linear-gradient(45deg, #c82333, #a71e2a);
            transform: translateY(-2px);
            color: white;
            text-decoration: none;
        }

        .fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            .sidebar { width: 100%; position: relative; }
            .main-content { margin-left: 0; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
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
                <a href="{{ route('admin.products.index') }}" class="menu-item">
                    <i class="fas fa-seedling"></i>Kelola Produk
                </a>
                <a href="{{ route('admin.orders') }}" class="menu-item">
                    <i class="fas fa-shopping-cart"></i>Pesanan
                </a>
                 {{-- ↓ TAMBAHKAN DI SINI ↓ --}}
                <a href="{{ route('admin.cancellations') }}" class="menu-item">
                    <i class="fas fa-times-circle"></i>Pengajuan Batal
                </a>
                <a href="{{ route('admin.users') }}" class="menu-item active">
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
                        <li class="breadcrumb-item active" aria-current="page">Pengguna</li>
                    </ol>
                </nav>
                <h2>Kelola Pengguna</h2>
                <p class="mb-0">Kelola semua pengguna terdaftar di sini!</p>
            </div>

            <div class="content-area fade-in">

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Stats Cards -->
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

                <!-- Users Table -->
                <div class="page-header">
                    <div class="section-header">
                        <h3 class="section-title">
                            <i class="fas fa-users text-success me-2"></i>
                            Daftar Pengguna ({{ $totalUsers ?? 0 }})
                        </h3>
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i>Tambah Pengguna
                        </a>
                    </div>

                    <!-- Search -->
                    <div class="mb-3">
                        <div class="input-group" style="max-width: 400px;">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" id="searchInput"
                                   placeholder="Cari nama, email, atau telepon...">
                        </div>
                    </div>

                    @if(isset($users) && $users->count() > 0)
                    <div class="table-responsive">
                        <table class="data-table" id="usersTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
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
                                   <td>{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                                    <td><strong>{{ $user->name }}</strong></td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone ?? '-' }}</td>
                                    <td><small>{{ \Illuminate\Support\Str::limit($user->address ?? '-', 30) }}</small></td>
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
                                            <a href="{{ route('admin.users.edit', $user) }}"
                                               class="btn-action btn-edit" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                                  style="display: inline;"
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

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <small class="text-muted">
                            Menampilkan {{ $users->firstItem() }} sampai {{ $users->lastItem() }}
                            dari {{ $users->total() }} pengguna
                        </small>
                        <div>{{ $users->links() }}</div>
                    </div>

                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-users fa-3x text-muted mb-3 d-block"></i>
                        <h5 class="text-muted">Belum ada pengguna terdaftar</h5>
                        <p class="text-muted">Mulai dengan menambahkan pengguna baru.</p>
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Live search
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('#usersTable tbody tr');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    </script>
</body>
</html>