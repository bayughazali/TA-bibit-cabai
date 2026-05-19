<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pengguna - Bibit Cabai Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Segoe UI',sans-serif; background:#f8f9fa; }
        .admin-container { display:flex; min-height:100vh; }
        .sidebar {
            width:280px; background:linear-gradient(180deg,#28a745,#20c997);
            color:white; min-height:100vh; position:fixed; top:0; left:0; z-index:1000;
        }
        .sidebar-header { padding:25px 20px; text-align:center; border-bottom:1px solid rgba(255,255,255,0.1); }
        .sidebar-header h3 { font-size:1.8rem; font-weight:bold; margin-bottom:5px; }
        .sidebar-menu { padding:20px 0; }
        .menu-item {
            display:block; padding:15px 25px; color:white; text-decoration:none;
            transition:all 0.3s; border-left:4px solid transparent;
        }
        .menu-item:hover, .menu-item.active {
            background:rgba(255,255,255,0.2); color:white;
            border-left-color:#fff; transform:translateX(5px);
        }
        .menu-item i { width:20px; margin-right:10px; }
        .main-content { margin-left:280px; flex:1; }
        .admin-header { background:white; padding:20px 30px; box-shadow:0 2px 4px rgba(0,0,0,0.1); }
        .content-area { padding:30px; }
        .detail-card { background:white; border-radius:12px; padding:35px; box-shadow:0 4px 6px rgba(0,0,0,0.05); max-width:700px; }
        .avatar-circle {
            width:80px; height:80px; border-radius:50%;
            background:linear-gradient(135deg,#28a745,#20c997);
            display:flex; align-items:center; justify-content:center;
            font-size:2rem; color:white; font-weight:bold; margin-bottom:15px;
        }
        .info-row { display:flex; padding:14px 0; border-bottom:1px solid #f8f9fa; }
        .info-row:last-child { border-bottom:none; }
        .info-label { width:160px; font-weight:600; color:#6c757d; font-size:0.9rem; flex-shrink:0; }
        .info-value { color:#333; flex:1; }
        .badge-role { padding:5px 12px; border-radius:12px; font-size:0.8rem; font-weight:600; }
        .badge-admin      { background:#cce5ff; color:#004085; }
        .badge-user       { background:#d4edda; color:#155724; }
        .badge-verified   { background:#d4edda; color:#155724; }
        .badge-unverified { background:#e2e3e5; color:#383d41; }
        .breadcrumb { background:transparent; padding:0; margin-bottom:10px; }
        .breadcrumb-item a { color:#28a745; text-decoration:none; }
        .back-button {
            position:fixed; top:20px; right:20px; z-index:1100;
            background:linear-gradient(45deg,#dc3545,#c82333);
            color:white; border:none; padding:12px 20px;
            border-radius:25px; font-weight:600; text-decoration:none; transition:all 0.3s;
        }
        .back-button:hover { transform:translateY(-2px); color:white; }
    </style>
</head>
<body>

<a href="{{ route('home') }}" class="back-button">
    <i class="fas fa-arrow-left me-2"></i>Kembali ke Website
</a>

<div class="admin-container">
    <nav class="sidebar">
        <div class="sidebar-header">
            <h3>🌱 Bibit Cabai</h3>
            <p>Admin Dashboard</p>
            <small class="text-light">{{ Auth::user()->name ?? 'Admin' }}</small>
        </div>
        <div class="sidebar-menu">
            <a href="{{ route('admin.dashboard') }}" class="menu-item"><i class="fas fa-tachometer-alt"></i>Dashboard</a>
            <a href="{{ route('admin.products.index') }}" class="menu-item"><i class="fas fa-seedling"></i>Kelola Produk</a>
            <a href="{{ route('admin.orders') }}" class="menu-item"><i class="fas fa-shopping-cart"></i>Pesanan</a>
            <a href="{{ route('admin.users') }}" class="menu-item active"><i class="fas fa-users"></i>Pengguna</a>
            <a href="{{ route('admin.reports') }}" class="menu-item"><i class="fas fa-chart-line"></i>Laporan</a>
            <!-- <a href="{{ route('admin.settings') }}" class="menu-item"><i class="fas fa-cog"></i>Pengaturan</a> -->
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

    <div class="main-content">
        <div class="admin-header">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fas fa-home"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.users') }}">Pengguna</a></li>
                    <li class="breadcrumb-item active">Detail Pengguna</li>
                </ol>
            </nav>
            <h2>Detail Pengguna</h2>
            <p class="mb-0">Informasi lengkap pengguna.</p>
        </div>

        <div class="content-area">
            <div class="detail-card">

                {{-- Avatar & Nama --}}
                <div class="text-center mb-4">
                    <div class="avatar-circle mx-auto">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <h4 class="mb-1">{{ $user->name }}</h4>
                    @if($user->is_admin)
                        <span class="badge-role badge-admin"><i class="fas fa-shield-alt me-1"></i>Admin</span>
                    @else
                        <span class="badge-role badge-user"><i class="fas fa-user me-1"></i>User</span>
                    @endif
                </div>

                {{-- Info Detail --}}
                <div class="info-row">
                    <div class="info-label"><i class="fas fa-hashtag me-2 text-muted"></i>ID</div>
                    <div class="info-value">#{{ $user->id }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label"><i class="fas fa-envelope me-2 text-muted"></i>Email</div>
                    <div class="info-value">{{ $user->email }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label"><i class="fas fa-check-circle me-2 text-muted"></i>Verifikasi</div>
                    <div class="info-value">
                        @if($user->email_verified_at)
                            <span class="badge-role badge-verified">
                                <i class="fas fa-check me-1"></i>Terverifikasi
                            </span>
                            <small class="text-muted ms-2">{{ $user->email_verified_at->format('d M Y') }}</small>
                        @else
                            <span class="badge-role badge-unverified">Belum Verifikasi</span>
                        @endif
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label"><i class="fas fa-phone me-2 text-muted"></i>Telepon</div>
                    <div class="info-value">{{ $user->phone ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label"><i class="fas fa-map-marker-alt me-2 text-muted"></i>Alamat</div>
                    <div class="info-value">{{ $user->address ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label"><i class="fas fa-calendar me-2 text-muted"></i>Bergabung</div>
                    <div class="info-value">{{ $user->created_at->format('d M Y, H:i') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label"><i class="fas fa-sync me-2 text-muted"></i>Diperbarui</div>
                    <div class="info-value">{{ $user->updated_at->format('d M Y, H:i') }}</div>
                </div>

                {{-- Action Buttons --}}
                <div class="d-flex gap-2 mt-4">
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Edit Pengguna
                    </a>
                    <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                    @if($user->id !== auth()->id())
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                          class="ms-auto" onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-2"></i>Hapus
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>