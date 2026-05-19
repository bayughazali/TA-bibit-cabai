<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TA-Bibit')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom Styles -->
    <style>
        .admin-link {
            background: linear-gradient(45deg, #28a745, #20c997) !important;
            border: none !important;
            font-weight: 600 !important;
            color: white !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 2px 10px rgba(40, 167, 69, 0.2);
        }
        
        .admin-link:hover {
            background: linear-gradient(45deg, #20c997, #17a2b8) !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.4) !important;
            color: white !important;
        }
        
        .admin-notification {
            position: relative;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(40, 167, 69, 0); }
            100% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0); }
        }
        
        .admin-badge {
            background: linear-gradient(45deg, #fd7e14, #e83e8c);
            font-size: 0.65rem;
            padding: 2px 6px;
            margin-left: 5px;
            animation: blink 1.5s infinite;
        }
        
        @keyframes blink {
            0%, 50% { opacity: 1; }
            51%, 100% { opacity: 0.5; }
        }
        
        .dropdown-item.admin-access {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white !important;
            font-weight: 600;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .dropdown-item.admin-access:hover {
            background: linear-gradient(135deg, #20c997 0%, #17a2b8 100%);
            color: white !important;
            transform: translateX(5px);
        }
    </style>
    
    @yield('styles')
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">
                <i class="fas fa-seedling me-2"></i>Bibit Cabai
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('home') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('products.best-selling') }}">Produk Terlaris</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('contact') }}">Hubungi Kami</a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    @auth
                        {{-- Tampilkan Admin Panel HANYA jika is_admin = 1 --}}
                            @if(Auth::user()->is_admin)
                                <li class="nav-item me-2">
                                    <a class="nav-link admin-link admin-notification px-3 py-2 rounded" 
                                    href="{{ route('admin.dashboard') }}" 
                                    title="Dashboard Admin">
                                        <i class="fas fa-crown me-1"></i>Admin Panel
                                        <span class="badge admin-badge">NEW</span>
                                    </a>
                                </li>
                            @endif
                        
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user me-1"></i>{{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            @if(Auth::user()->is_admin)
                                <li>
                                    <a class="dropdown-item admin-access" href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard Admin
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                            @endif
                                <li>
                                <a class="dropdown-item" href="{{ route('profile') }}">
                                        <i class="fas fa-user-circle me-2"></i>Profil Saya
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('orders.my') }}">
                                        <i class="fas fa-box me-2"></i>Pesanan Saya
                                    </a>
                                </li>
                                <li>
                                    <!-- <a class="dropdown-item" href="#">
                                        <i class="fas fa-cog me-2"></i>Pengaturan
                                    </a> -->
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-2"></i>Keluar
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <!-- Admin Access for Non-Authenticated Users -->
                        <li class="nav-item me-2">
                            <a class="nav-link admin-link px-3 py-2 rounded" 
                               href="{{ route('admin.login.form') }}"
                               title="Login Admin">
                                <i class="fas fa-user-shield me-1"></i>Admin Login
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i>Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('register') }}">
                                <i class="fas fa-user-plus me-1"></i>Register
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Alert Messages -->
   <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Notifikasi Pesanan Customer -->  
@auth
    @php 
        $notifTerkirim = auth()->user()->unreadNotifications
           ->filter(fn($n) => isset($n->data['status']) && in_array($n->data['status'], ['processing', 'shipped', 'delivered', 'cancelled']))
            ->count();
    @endphp
    @if($notifTerkirim > 0)
    <div id="notif-pesanan" data-count="{{ $notifTerkirim }}" class="alert alert-warning alert-dismissible fade show mb-0" role="alert">
        <div class="container">
            <i class="fas fa-bell me-2"></i>
          Anda memiliki <strong>{{ $notifTerkirim }}</strong> update status pesanan baru.
            <a href="{{ route('orders.my') }}" class="alert-link">Lihat Pesanan Saya</a>
            <button type="button" class="btn-close" onclick="tutupNotif('notif_pesanan', 'notif-pesanan')"></button>
        </div>
    </div>
    @endif
@endauth

    <!-- Admin Welcome Banner (for authenticated users) -->
   @auth
    @if(Auth::user()->is_admin)
    <div id="notif-welcome" class="alert alert-info alert-dismissible fade show mb-0" role="alert" style="background: linear-gradient(135deg, #17a2b8, #20c997); border: none;">
            <div class="container">
                <div class="d-flex align-items-center justify-content-between flex-wrap">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-info-circle me-2"></i>
                        <span>Selamat datang, <strong>{{ Auth::user()->name }}</strong>! Akses dashboard admin untuk mengelola produk dan laporan.</span>
                    </div>
                    <div class="mt-2 mt-sm-0">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-light btn-sm me-2">
                            <i class="fas fa-external-link-alt me-1"></i>Buka Dashboard
                        </a>
                       <button type="button" class="btn-close btn-close-white" onclick="tutupNotif('notif_welcome', 'notif-welcome')"></button>
                    </div>
                </div>
            </div>
        </div>
        </div>
    @endif
    @endauth

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-seedling me-2"></i>Bibit Cabai</h5>
                    <p>Platform terbaik untuk kebutuhan bibit dan tanaman Anda.</p>
                    <div class="mt-3">
                       @auth
                                @if(Auth::user()->is_admin)
                                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-success btn-sm">
                                        <i class="fas fa-tools me-1"></i>Admin Panel
                                    </a>
                                @endif
                            @else
                            <a href="{{ route('admin.login.form') }}" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-tools me-1"></i>Admin Panel
                            </a>
                        @endauth
                    </div>
                </div>
               <div class="col-md-6">
    <h5>Hubungi Kami</h5>
    <p>
        <a href="https://wa.me/62123456789" target="_blank" class="text-white text-decoration-none">
            <i class="fab fa-whatsapp me-2 text-success"></i>+62 123 456 789
        </a>
    </p>
    <p>
        <a href="mailto:info@tabibit.com" class="text-white text-decoration-none">
            <i class="fas fa-envelope me-2 text-success"></i>info@tabibit.com
        </a>
    </p>
</div>
            </div>
            <hr>
            <div class="text-center">
                <p>&copy; {{ date('Y') }} Bibit Cabai. All rights reserved. | 
                    @auth
                        @if(Auth::user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}" class="text-success text-decoration-none">Admin Panel</a>
                        @endif
                    @else
                        <a href="{{ route('admin.login.form') }}" class="text-success text-decoration-none">Admin Panel</a>
                    @endauth
                </p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Scripts -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Notif pesanan - cek berdasarkan jumlah notif
        var pesananEl = document.getElementById('notif-pesanan');
        if (pesananEl) {
            var count = pesananEl.getAttribute('data-count');
            var savedCount = localStorage.getItem('notif_pesanan_count');
            if (savedCount === count) {
                pesananEl.style.display = 'none';
            }
        }

        // Notif welcome - pakai sessionStorage (reset setiap login baru)
        if (sessionStorage.getItem('notif_welcome') === 'ditutup') {
            var el = document.getElementById('notif-welcome');
            if (el) el.style.display = 'none';
        }

        // Auto-hide semua notif setelah 5 detik
        setTimeout(function() {
            ['notif-pesanan', 'notif-welcome'].forEach(function(elId) {
                var el = document.getElementById(elId);
                if (el && el.style.display !== 'none') {
                    el.style.transition = 'opacity 0.8s ease';
                    el.style.opacity = '0';
                    setTimeout(function() { el.style.display = 'none'; }, 800);
                }
            });
        }, 5000);
    });

    function tutupNotif(key, elId) {
        if (key === 'notif_pesanan') {
            var el = document.getElementById(elId);
            var count = el ? el.getAttribute('data-count') : '0';
            localStorage.setItem('notif_pesanan_count', count);
        } else {
            sessionStorage.setItem(key, 'ditutup'); // ganti localStorage -> sessionStorage
        }
        var el = document.getElementById(elId);
        if (el) {
            el.style.transition = 'opacity 0.3s';
            el.style.opacity = '0';
            setTimeout(function() { el.style.display = 'none'; }, 300);
        }
    }
</script>

    @yield('scripts')
</body>
</html>