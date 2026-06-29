<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pengguna - Bibit Cabai Admin</title>
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
        .form-card { background:white; border-radius:12px; padding:35px; box-shadow:0 4px 6px rgba(0,0,0,0.05); max-width:700px; }
        .form-label { font-weight:600; color:#495057; }
        .form-control:focus { border-color:#28a745; box-shadow:0 0 0 0.2rem rgba(40,167,69,0.25); }
        .btn-save { background:linear-gradient(45deg,#28a745,#20c997); border:none; color:white; padding:12px 30px; border-radius:8px; font-weight:600; }
        .btn-save:hover { opacity:0.9; color:white; }
        .breadcrumb { background:transparent; padding:0; margin-bottom:10px; }
        .breadcrumb-item a { color:#28a745; text-decoration:none; }
        .back-button {
            position:fixed; top:20px; right:20px; z-index:1100;
            background:linear-gradient(45deg,#dc3545,#c82333);
            color:white; border:none; padding:12px 20px;
            border-radius:25px; font-weight:600; text-decoration:none; transition:all 0.3s;
        }
        .back-button:hover { transform:translateY(-2px); color:white; }
        .section-divider { border-top:2px solid #f8f9fa; margin:25px 0; }
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
                    <li class="breadcrumb-item active">Tambah Pengguna</li>
                </ol>
            </nav>
            <h2>Tambah Pengguna Baru</h2>
            <p class="mb-0">Isi form berikut untuk menambah pengguna baru.</p>
        </div>

        <div class="content-area">
            <div class="form-card">

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf

                    {{-- Informasi Dasar --}}
                    <h5 class="mb-3 text-success"><i class="fas fa-user me-2"></i>Informasi Dasar</h5>

                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" placeholder="contoh@email.com" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Password <span class="text-danger">*</span></label>
        <div class="input-group">
            <input type="password" name="password" id="password"
                   class="form-control @error('password') is-invalid @enderror"
                   placeholder="Minimal 8 karakter" required>
            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password', 'eye1')">
                <i class="fas fa-eye" id="eye1"></i>
            </button>
        </div>
        @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
        <div class="input-group">
            <input type="password" name="password_confirmation" id="password_confirmation"
                   class="form-control"
                   placeholder="Ulangi password" required>
            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password_confirmation', 'eye2')">
                <i class="fas fa-eye" id="eye2"></i>
            </button>
        </div>
    </div>
</div>

                    <div class="section-divider"></div>
                    <h5 class="mb-3 text-success"><i class="fas fa-address-card me-2"></i>Informasi Tambahan</h5>

                    <div class="mb-3">
                        <label class="form-label">Nomor Telepon</label>
                       <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                        value="{{ old('phone') }}"
                        placeholder="081234567890">
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                      <textarea name="address" class="form-control @error('address') is-invalid @enderror"
          rows="4" style="min-height: 100px; resize: vertical;"
          placeholder="Contoh: Jl. Mawar No. 10, RT 02/RW 03, Kelurahan Sumbersari, Jember">{{ old('address') }}</textarea>
                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="section-divider"></div>
                    <h5 class="mb-3 text-success"><i class="fas fa-shield-alt me-2"></i>Hak Akses</h5>

                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_admin" id="is_admin"
                                   {{ old('is_admin') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_admin">
                                <strong>Jadikan Admin</strong>
                                <small class="text-muted d-block">Admin memiliki akses penuh ke dashboard.</small>
                            </label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-save">
                            <i class="fas fa-save me-2"></i>Simpan Pengguna
                        </button>
                        <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
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
</script>
</body>

</html>