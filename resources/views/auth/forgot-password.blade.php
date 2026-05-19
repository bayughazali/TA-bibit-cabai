@extends('layouts.app')

@section('title', 'Lupa Password - Shop Bibit Cabai Bondowoso')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-success text-white text-center py-4">
                    <h3 class="mb-0">
                        <i class="fas fa-key me-2"></i>Lupa Password
                    </h3>
                    <p class="mb-0 mt-2 opacity-75 small">Masukkan email terdaftar Anda</p>
                </div>

                <div class="card-body p-4">

                    {{-- Step Indicator --}}
                    <div class="d-flex align-items-center justify-content-center mb-4">
                        <div class="step-item active">
                            <div class="step-circle bg-success text-white">1</div>
                            <div class="step-label text-success fw-bold">Email</div>
                        </div>
                        <div class="step-line bg-secondary mx-2"></div>
                        <div class="step-item">
                            <div class="step-circle bg-secondary text-white">2</div>
                            <div class="step-label text-secondary">Kode OTP</div>
                        </div>
                        <div class="step-line bg-secondary mx-2"></div>
                        <div class="step-item">
                            <div class="step-circle bg-secondary text-white">3</div>
                            <div class="step-label text-secondary">Password Baru</div>
                        </div>
                    </div>

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <p class="text-muted text-center mb-4">
                        <i class="fas fa-info-circle text-success me-1"></i>
                        Kami akan mengirim kode verifikasi ke email Anda untuk mereset password.
                    </p>

                    <form method="POST" action="{{ route('password.email') }}" id="forgotForm">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">
                                Email <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-envelope text-success"></i>
                                </span>
                                <input type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       id="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       required
                                       autofocus
                                       placeholder="contoh@gmail.com">
                            </div>
                            <div class="form-text text-muted">
                                <small><i class="fas fa-info-circle"></i> Gunakan email @gmail.com yang terdaftar</small>
                            </div>
                            @error('email')
                            <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                            @enderror
                            <div id="emailError" class="text-danger mt-1" style="display: none;">
                                <small>Email harus menggunakan domain @gmail.com</small>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg" id="submitBtn" disabled>
                                <i class="fas fa-paper-plane me-2"></i>Kirim Kode Verifikasi
                            </button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <a href="{{ route('login') }}" class="text-success text-decoration-none">
                            <i class="fas fa-arrow-left me-1"></i>Kembali ke Halaman Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.step-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
}
.step-circle {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 14px;
}
.step-label {
    font-size: 11px;
    white-space: nowrap;
}
.step-line {
    height: 2px;
    width: 40px;
    margin-bottom: 18px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const emailInput = document.getElementById('email');
    const submitBtn = document.getElementById('submitBtn');
    const emailError = document.getElementById('emailError');

    emailInput.addEventListener('input', function () {
        const val = this.value;
        const gmailPattern = /@gmail\.com$/;
        const hasSpaces = /\s/.test(val);

        if (val.length > 0 && (!gmailPattern.test(val) || hasSpaces)) {
            emailError.style.display = 'block';
            this.classList.add('is-invalid');
            submitBtn.disabled = true;
        } else {
            emailError.style.display = 'none';
            this.classList.remove('is-invalid');
            submitBtn.disabled = val.length === 0;
        }
    });

    emailInput.addEventListener('keydown', function (e) {
        if (e.key === ' ') e.preventDefault();
    });
});
</script>
@endsection