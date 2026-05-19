<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password Admin - TA Bibit Cabai</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
        .btn-primary-custom {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            border: none;
            border-radius: 25px;
            color: white;
            font-weight: 600;
        }
        .btn-primary-custom:hover {
            background: linear-gradient(135deg, #0d7377 0%, #2dd654 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(17, 153, 142, 0.3);
            color: white;
        }
        .btn-primary-custom:disabled {
            background: #adb5bd;
            transform: none;
            box-shadow: none;
            cursor: not-allowed;
        }
        .form-control:focus {
            border-color: #11998e;
            box-shadow: 0 0 0 0.2rem rgba(17, 153, 142, 0.25);
        }

        /* Step Indicator */
        .step-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 28px;
        }
        .step-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 13px;
        }
        .step-circle.active { background: #11998e; color: #fff; }
        .step-circle.done   { background: #11998e; color: #fff; }
        .step-circle.idle   { background: #dee2e6; color: #888; }
        .step-label { font-size: 11px; white-space: nowrap; margin-top: 4px; }
        .step-label.active { color: #11998e; font-weight: 700; }
        .step-label.idle   { color: #aaa; }
        .step-item { display: flex; flex-direction: column; align-items: center; }
        .step-line { height: 2px; width: 36px; margin: 0 6px 18px; }
        .step-line.done { background: #11998e; }
        .step-line.idle { background: #dee2e6; }

        .icon-box {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin: 0 auto 16px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4 col-lg-4">
            <div class="card login-card">
                <div class="card-body p-5">

                    {{-- Icon --}}
                    <div class="icon-box">🔑</div>

                    <div class="text-center mb-3">
                        <h4 class="fw-bold text-dark mb-1">Lupa Password Admin</h4>
                        <p class="text-muted small">Masukkan email admin yang terdaftar</p>
                    </div>

                    {{-- Step Indicator --}}
                    <div class="step-wrapper">
                        <div class="step-item">
                            <div class="step-circle active">1</div>
                            <div class="step-label active">Email</div>
                        </div>
                        <div class="step-line idle"></div>
                        <div class="step-item">
                            <div class="step-circle idle">2</div>
                            <div class="step-label idle">Kode OTP</div>
                        </div>
                        <div class="step-line idle"></div>
                        <div class="step-item">
                            <div class="step-circle idle">3</div>
                            <div class="step-label idle">Password Baru</div>
                        </div>
                    </div>

                    {{-- Error --}}
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
                            <small>
                                @foreach ($errors->all() as $error)
                                    <div>⚠️ {{ $error }}</div>
                                @endforeach
                            </small>
                            <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
                            <small>⚠️ {{ session('error') }}</small>
                            <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- Form --}}
                    <form method="POST" action="{{ route('admin.password.email') }}" id="forgotForm" novalidate>
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">Email Admin</label>
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   placeholder="admin@example.com"
                                   autofocus
                                   required>
                            <div class="form-text text-muted small">
                                ℹ️ Hanya akun admin yang terdaftar di sistem
                            </div>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="emailError" class="text-danger mt-1" style="display:none;">
                                <small>⚠️ Format email tidak valid</small>
                            </div>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary-custom py-2 fw-bold" id="submitBtn" disabled>
                                📨 Kirim Kode Verifikasi
                            </button>
                        </div>
                    </form>

                    <div class="text-center">
                        <a href="{{ route('admin.login') }}" class="text-decoration-none small" style="color:#11998e;">
                            ← Kembali ke Login Admin
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const emailInput = document.getElementById('email');
    const submitBtn  = document.getElementById('submitBtn');
    const emailError = document.getElementById('emailError');

    function validateEmail(val) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val) && !/\s/.test(val);
    }

    emailInput.addEventListener('input', function () {
        const val = this.value.trim();
        if (val.length > 0 && !validateEmail(val)) {
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
</body>
</html>