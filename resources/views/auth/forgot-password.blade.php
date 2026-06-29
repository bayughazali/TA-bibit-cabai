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
            <div class="step-label text-success fw-bold">Identitas</div>
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
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Toggle Metode --}}
    <div class="d-flex gap-2 mb-4" id="methodToggle">
        <button type="button" class="btn btn-success flex-fill" id="btnEmail" onclick="switchMethod('email')">
            <i class="fas fa-envelope me-1"></i> Gunakan Email
        </button>
        <button type="button" class="btn btn-outline-success flex-fill" id="btnWa" onclick="switchMethod('whatsapp')">
            <i class="fab fa-whatsapp me-1"></i> Gunakan WhatsApp
        </button>
    </div>

    <form method="POST" action="{{ route('password.email') }}" id="forgotForm">
        @csrf
        <input type="hidden" name="method" id="methodInput" value="email">

        {{-- Email Section --}}
        <div id="emailSection">
            <p class="text-muted text-center mb-3 small">
                <i class="fas fa-info-circle text-success me-1"></i>
                Kode verifikasi akan dikirim ke email Anda.
            </p>
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
                           id="email" name="email"
                           value="{{ old('email') }}"
                           placeholder="contoh@gmail.com">
                </div>
                <div class="form-text text-muted">
                    <small><i class="fas fa-info-circle"></i> Gunakan email @gmail.com yang terdaftar</small>
                </div>
                @error('email')
                <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                @enderror
                <div id="emailError" class="text-danger mt-1" style="display:none;">
                    <small>Email harus menggunakan domain @gmail.com</small>
                </div>
            </div>
        </div>

        {{-- WhatsApp Section --}}
        <div id="waSection" style="display:none;">
            <p class="text-muted text-center mb-3 small">
                <i class="fab fa-whatsapp text-success me-1"></i>
                Kode verifikasi akan dikirim via WhatsApp.
            </p>
            <div class="mb-4">
                <label for="phone" class="form-label fw-semibold">
                    Nomor WhatsApp <span class="text-danger">*</span>
                </label>
             <div class="input-group">
    <input type="tel"
           class="form-control @error('phone') is-invalid @enderror"
           id="phone" name="phone"
           value="{{ old('phone') }}"
           placeholder="081234567890"
           inputmode="numeric">
</div>
<div class="form-text text-muted">
    <small><i class="fas fa-info-circle"></i> Masukkan nomor WhatsApp minimal 10 digit</small>
</div>
                <!-- <div class="form-text text-muted">
                    <small><i class="fas fa-info-circle"></i> Masukkan nomor tanpa angka 0 di depan</small>
                </div> -->
                @error('phone')
                <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                @enderror
                <div id="phoneError" class="text-danger mt-1" style="display:none;">
                    <small>Nomor WhatsApp tidak valid</small>
                </div>
            </div>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-success btn-lg" id="submitBtn" disabled>
                <i class="fas fa-paper-plane me-2"></i>
                <span id="submitText">Kirim Kode Verifikasi</span>
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
let currentMethod = 'email';

function switchMethod(method) {
    currentMethod = method;
    document.getElementById('methodInput').value = method;

    const btnEmail = document.getElementById('btnEmail');
    const btnWa = document.getElementById('btnWa');
    const emailSection = document.getElementById('emailSection');
    const waSection = document.getElementById('waSection');
    const submitBtn = document.getElementById('submitBtn');

    if (method === 'email') {
        btnEmail.className = 'btn btn-success flex-fill';
        btnWa.className = 'btn btn-outline-success flex-fill';
        emailSection.style.display = 'block';
        waSection.style.display = 'none';
    } else {
        btnEmail.className = 'btn btn-outline-success flex-fill';
        btnWa.className = 'btn btn-success flex-fill';
        emailSection.style.display = 'none';
        waSection.style.display = 'block';
    }

    submitBtn.disabled = true;
    validateInput();
}

function validateInput() {
    const submitBtn = document.getElementById('submitBtn');

    if (currentMethod === 'email') {
        const val = document.getElementById('email').value;
        const valid = /^[^\s]+@gmail\.com$/.test(val);
        document.getElementById('emailError').style.display = (val.length > 0 && !valid) ? 'block' : 'none';
        submitBtn.disabled = !valid;
    } else {
    const val = document.getElementById('phone').value.replace(/\D/g, '');
    const valid = val.length >= 10 && val.length <= 15;
    document.getElementById('phoneError').style.display = (val.length > 0 && !valid) ? 'block' : 'none';
    submitBtn.disabled = !valid;
}
}

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('email').addEventListener('input', validateInput);
    document.getElementById('phone').addEventListener('input', function () {
        this.value = this.value.replace(/\D/g, '');
        validateInput();
    });
    document.getElementById('phone').addEventListener('keydown', function (e) {
        if (e.key === ' ') e.preventDefault();
    });
});
</script>
@endsection