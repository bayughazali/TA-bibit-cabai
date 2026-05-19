@extends('layouts.app')

@section('title', 'Profil Saya - Bibit Cabai')

@section('styles')
<style>
    body { background: #f8f9fa; font-family: 'Segoe UI', sans-serif; }

    .profile-hero {
        background: linear-gradient(135deg, #28a745, #20c997);
        padding: 40px 0 80px;
        color: white;
    }

    .avatar-circle {
        width: 90px; height: 90px; border-radius: 50%;
        background: rgba(255,255,255,0.3);
        display: flex; align-items: center; justify-content: center;
        font-size: 2.5rem; font-weight: bold; color: white;
        border: 4px solid rgba(255,255,255,0.5);
        margin: 0 auto 15px;
    }

    .profile-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        margin-top: -50px;
        padding: 30px;
    }

    .nav-profile .nav-link {
        color: #6c757d;
        border-radius: 8px;
        padding: 10px 16px;
        font-weight: 500;
    }

    .nav-profile .nav-link.active,
    .nav-profile .nav-link:hover {
        background: #e8f5e9;
        color: #28a745;
    }

    .nav-profile .nav-link i { width: 20px; }

    .form-control:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40,167,69,0.2);
    }

    .btn-green {
        background: linear-gradient(45deg, #28a745, #20c997);
        border: none; color: white; font-weight: 600;
        padding: 10px 25px; border-radius: 8px;
    }

    .btn-green:hover { opacity: 0.9; color: white; }

    .section-title {
        font-size: 1.1rem; font-weight: 700;
        color: #333; margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #f0f0f0;
    }

    .info-badge {
        background: #f0fff4; border: 1px solid #b7ebc8;
        border-radius: 8px; padding: 10px 15px;
        font-size: 0.85rem; color: #2d6a4f;
        margin-bottom: 20px;
    }
</style>
@endsection

@section('content')

{{-- Hero --}}
<div class="profile-hero text-center">
    <div class="avatar-circle">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
    <h4 class="mb-1">{{ auth()->user()->name }}</h4>
    <small>{{ auth()->user()->email }}</small>
</div>

<div class="container py-4">
    <div class="row g-4">

        {{-- Sidebar Menu --}}
        <div class="col-md-3">
            <div class="profile-card p-0 overflow-hidden">
                <div class="p-3 border-bottom">
                    <small class="text-muted text-uppercase fw-bold" style="font-size:0.7rem;">Menu</small>
                </div>
                <nav class="nav flex-column nav-profile p-2">
                    <a href="{{ route('profile') }}" class="nav-link active">
                        <i class="fas fa-user me-2"></i>Profil Saya
                    </a>
                    <a href="{{ route('orders.my') }}" class="nav-link">
                        <i class="fas fa-shopping-bag me-2"></i>Pesanan Saya
                    </a>
                    <hr class="my-2">
                    <a href="{{ route('home') }}" class="nav-link text-muted">
                        <i class="fas fa-home me-2"></i>Kembali ke Beranda
                    </a>
                </nav>
            </div>
        </div>

        {{-- Konten --}}
        <div class="col-md-9">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Form Edit Profil --}}
            <div class="profile-card mb-4">
                <div class="section-title"><i class="fas fa-user-edit me-2 text-success"></i>Edit Profil</div>

                <div class="info-badge">
    <i class="fas fa-info-circle me-2"></i>
    Email tidak dapat diubah melalui halaman ini. Email harus menggunakan <strong>

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', auth()->user()->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" class="form-control bg-light" value="{{ auth()->user()->email }}" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nomor Telepon</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                               value="{{ old('phone', auth()->user()->phone ?? '+62') }}"
                               placeholder="+62812xxxxxxxx">
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Alamat</label>
                        <textarea name="address" rows="4"
                                  class="form-control @error('address') is-invalid @enderror"
                                  style="min-height:100px; resize:vertical;"
                                  placeholder="Contoh: Jl. Mawar No. 10, RT 02/RW 03, Kelurahan Sumbersari, Jember">{{ old('address', auth()->user()->address) }}</textarea>
                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <button type="submit" class="btn btn-green">
                        <i class="fas fa-save me-2"></i>Simpan Perubahan
                    </button>
                </form>
            </div>

            {{-- Form Ganti Password --}}
            <div class="profile-card">
                <div class="section-title"><i class="fas fa-lock me-2 text-success"></i>Ganti Password</div>

                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf

                                    <div class="mb-3">
                        <label class="form-label fw-bold">Password Lama</label>
                        <div class="input-group">
                            <input type="password" name="current_password" id="cur_pass"
                                class="form-control @error('current_password') is-invalid @enderror"
                                placeholder="Masukkan password lama" required>
                            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('cur_pass','eye0')">
                                <i class="fas fa-eye" id="eye0"></i>
                            </button>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Password Baru</label>
                            <div class="input-group">
                                <input type="password" name="password" id="new_pass"
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="Minimal 8 karakter" required>
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('new_pass','eye1')">
                                    <i class="fas fa-eye" id="eye1"></i>
                                </button>
                            </div>
                            @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Konfirmasi Password</label>
                            <div class="input-group">
                                <input type="password" name="password_confirmation" id="conf_pass"
                                       class="form-control" placeholder="Ulangi password baru" required>
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('conf_pass','eye2')">
                                    <i class="fas fa-eye" id="eye2"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-green">
                        <i class="fas fa-key me-2"></i>Ganti Password
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

@endsection

@section('scripts')
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
@endsection