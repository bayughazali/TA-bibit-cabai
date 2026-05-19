@extends('layouts.app')

@section('title', 'Reset Password - Shop Bibit Cabai Bondowoso')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-success text-white text-center py-4">
                    <h3 class="mb-0">
                        <i class="fas fa-lock-open me-2"></i>Buat Password Baru
                    </h3>
                    <p class="mb-0 mt-2 opacity-75 small">Buat password yang kuat untuk akun Anda</p>
                </div>

                <div class="card-body p-4">

                    {{-- Step Indicator --}}
                    <div class="d-flex align-items-center justify-content-center mb-4">
                        <div class="step-item">
                            <div class="step-circle bg-success text-white">
                                <i class="fas fa-check" style="font-size:12px;"></i>
                            </div>
                            <div class="step-label text-success fw-bold">Email</div>
                        </div>
                        <div class="step-line bg-success mx-2"></div>
                        <div class="step-item">
                            <div class="step-circle bg-success text-white">
                                <i class="fas fa-check" style="font-size:12px;"></i>
                            </div>
                            <div class="step-label text-success fw-bold">Kode OTP</div>
                        </div>
                        <div class="step-line bg-success mx-2"></div>
                        <div class="step-item active">
                            <div class="step-circle bg-success text-white">3</div>
                            <div class="step-label text-success fw-bold">Password Baru</div>
                        </div>
                    </div>

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <p class="text-muted text-center mb-4">
                        <i class="fas fa-check-circle text-success me-1"></i>
                        Email terverifikasi. Silakan buat password baru Anda.
                    </p>

                    <form method="POST" action="{{ route('password.update') }}" id="resetForm">
                        @csrf

                        {{-- Hidden token from session --}}
                        <input type="hidden" name="token" value="{{ session('reset_token') }}">
                        <input type="hidden" name="email" value="{{ session('reset_email') }}">

                        {{-- New Password --}}
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">
                                Password Baru <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-lock text-success"></i>
                                </span>
                                <input type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       id="password"
                                       name="password"
                                       required
                                       placeholder="Minimal 8 karakter">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fas fa-eye" id="eyeIcon1"></i>
                                </button>
                            </div>
                            <div class="form-text text-muted">
                                <small><i class="fas fa-info-circle"></i> Minimal 8 karakter, tanpa spasi</small>
                            </div>
                            @error('password')
                            <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                            @enderror
                            <div id="passwordError" class="text-danger mt-1" style="display:none;">
                                <small>Password minimal 8 karakter dan tidak boleh mengandung spasi</small>
                            </div>
                        </div>

                        {{-- Password Strength Bar --}}
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <small class="text-muted">Kekuatan password:</small>
                                <small id="strengthLabel" class="fw-semibold text-secondary">—</small>
                            </div>
                            <div class="progress" style="height: 6px; border-radius: 3px;">
                                <div id="strengthBar" class="progress-bar bg-secondary"
                                     role="progressbar" style="width: 0%"></div>
                            </div>
                        </div>

                        {{-- Confirm Password --}}
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-semibold">
                                Konfirmasi Password <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-lock text-success"></i>
                                </span>
                                <input type="password"
                                       class="form-control @error('password_confirmation') is-invalid @enderror"
                                       id="password_confirmation"
                                       name="password_confirmation"
                                       required
                                       placeholder="Ulangi password baru">
                                <button class="btn btn-outline-secondary" type="button" id="toggleConfirm">
                                    <i class="fas fa-eye" id="eyeIcon2"></i>
                                </button>
                            </div>
                            @error('password_confirmation')
                            <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                            @enderror
                            <div id="confirmError" class="text-danger mt-1" style="display:none;">
                                <small>Password tidak cocok</small>
                            </div>
                            <div id="confirmSuccess" class="text-success mt-1" style="display:none;">
                                <small><i class="fas fa-check-circle me-1"></i>Password cocok</small>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg" id="submitBtn" disabled>
                                <i class="fas fa-save me-2"></i>Simpan Password Baru
                            </button>
                        </div>
                    </form>

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
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    const submitBtn = document.getElementById('submitBtn');
    const strengthBar = document.getElementById('strengthBar');
    const strengthLabel = document.getElementById('strengthLabel');

    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function () {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        document.getElementById('eyeIcon1').classList.toggle('fa-eye');
        document.getElementById('eyeIcon1').classList.toggle('fa-eye-slash');
    });

    document.getElementById('toggleConfirm').addEventListener('click', function () {
        const type = confirmInput.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmInput.setAttribute('type', type);
        document.getElementById('eyeIcon2').classList.toggle('fa-eye');
        document.getElementById('eyeIcon2').classList.toggle('fa-eye-slash');
    });

    // Password strength checker
    function checkStrength(pwd) {
        let score = 0;
        if (pwd.length >= 8) score++;
        if (pwd.length >= 12) score++;
        if (/[A-Z]/.test(pwd)) score++;
        if (/[0-9]/.test(pwd)) score++;
        if (/[^A-Za-z0-9]/.test(pwd)) score++;
        return score;
    }

    passwordInput.addEventListener('input', function () {
        const val = this.value;
        const hasSpaces = /\s/.test(val);
        const passwordError = document.getElementById('passwordError');

        if (val.length > 0 && (val.length < 8 || hasSpaces)) {
            passwordError.style.display = 'block';
            this.classList.add('is-invalid');
        } else {
            passwordError.style.display = 'none';
            this.classList.remove('is-invalid');
        }

        // Strength bar
        if (val.length === 0) {
            strengthBar.style.width = '0%';
            strengthBar.className = 'progress-bar bg-secondary';
            strengthLabel.textContent = '—';
            strengthLabel.className = 'fw-semibold text-secondary';
        } else {
            const score = checkStrength(val);
            const levels = [
                { pct: '20%', cls: 'bg-danger', label: 'Sangat Lemah', labelCls: 'text-danger' },
                { pct: '40%', cls: 'bg-warning', label: 'Lemah', labelCls: 'text-warning' },
                { pct: '60%', cls: 'bg-info', label: 'Sedang', labelCls: 'text-info' },
                { pct: '80%', cls: 'bg-primary', label: 'Kuat', labelCls: 'text-primary' },
                { pct: '100%', cls: 'bg-success', label: 'Sangat Kuat', labelCls: 'text-success' },
            ];
            const lvl = levels[Math.min(score - 1, 4)] || levels[0];
            strengthBar.style.width = lvl.pct;
            strengthBar.className = `progress-bar ${lvl.cls}`;
            strengthLabel.textContent = lvl.label;
            strengthLabel.className = `fw-semibold ${lvl.labelCls}`;
        }

        validateConfirm();
        checkFormValidity();
    });

    confirmInput.addEventListener('input', function () {
        validateConfirm();
        checkFormValidity();
    });

    function validateConfirm() {
        const confirmError = document.getElementById('confirmError');
        const confirmSuccess = document.getElementById('confirmSuccess');
        if (confirmInput.value.length === 0) {
            confirmError.style.display = 'none';
            confirmSuccess.style.display = 'none';
            confirmInput.classList.remove('is-invalid', 'is-valid');
        } else if (passwordInput.value !== confirmInput.value) {
            confirmError.style.display = 'block';
            confirmSuccess.style.display = 'none';
            confirmInput.classList.add('is-invalid');
            confirmInput.classList.remove('is-valid');
        } else {
            confirmError.style.display = 'none';
            confirmSuccess.style.display = 'block';
            confirmInput.classList.remove('is-invalid');
            confirmInput.classList.add('is-valid');
        }
    }

    function checkFormValidity() {
        const pwValid = passwordInput.value.length >= 8 && !/\s/.test(passwordInput.value);
        const confirmValid = confirmInput.value === passwordInput.value && confirmInput.value.length > 0;
        submitBtn.disabled = !(pwValid && confirmValid);
    }

    // Prevent spaces
    [passwordInput, confirmInput].forEach(input => {
        input.addEventListener('keydown', function (e) {
            if (e.key === ' ') e.preventDefault();
        });
    });
});
</script>
@endsection