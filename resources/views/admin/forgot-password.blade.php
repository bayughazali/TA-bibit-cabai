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
                        <p class="text-muted small">Gunakan email atau WhatsApp admin terdaftar</p>
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
                  {{-- Toggle Metode --}}
<div class="d-flex gap-2 mb-4">
    <button type="button"
            class="btn btn-sm flex-fill fw-semibold"
            id="btnEmail"
            style="background:#11998e;color:white;border-radius:20px;"
            onclick="switchMethod('email')">
        📧 Email
    </button>
    <button type="button"
            class="btn btn-sm flex-fill fw-semibold"
            id="btnWa"
            style="background:white;color:#11998e;border:2px solid #11998e;border-radius:20px;"
            onclick="switchMethod('whatsapp')">
        💬 WhatsApp
    </button>
</div>

<form method="POST" action="{{ route('admin.password.email') }}" id="forgotForm" novalidate>
    @csrf
    <input type="hidden" name="method" id="methodInput" value="email">

    {{-- EMAIL SECTION --}}
    <div id="emailSection">
        <div class="mb-4">
            <label for="email" class="form-label fw-semibold">Email Admin</label>
            <input type="email"
                   class="form-control @error('email') is-invalid @enderror"
                   id="email"
                   name="email"
                   value="{{ old('email') }}"
                   placeholder="admin@example.com"
                   autofocus>
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
    </div>

    {{-- WHATSAPP SECTION --}}
    <div id="waSection" style="display:none;">
        <div class="mb-4">
            <label for="phone" class="form-label fw-semibold">Nomor WhatsApp Admin</label>
            <div class="input-group">
                <span class="input-group-text fw-semibold" style="color:#11998e;">+62</span>
                <input type="tel"
                       class="form-control @error('phone') is-invalid @enderror"
                       id="phone"
                       name="phone"
                       value="{{ old('phone') }}"
                       placeholder="8123456789"
                       inputmode="numeric">
                        </div>
                        <div class="form-text text-muted small">
                            ℹ️ Masukkan nomor tanpa angka 0 di depan
                        </div>
                        @error('phone')
                            <div class="text-danger mt-1 small">{{ $message }}</div>
                        @enderror
                        <div id="phoneError" class="text-danger mt-1" style="display:none;">
                            <small>⚠️ Nomor WhatsApp tidak valid (min 9 digit)</small>
                        </div>
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
let currentMethod = 'email';

function switchMethod(method) {
    currentMethod = method;
    document.getElementById('methodInput').value = method;

    const btnEmail  = document.getElementById('btnEmail');
    const btnWa     = document.getElementById('btnWa');
    const emailSec  = document.getElementById('emailSection');
    const waSec     = document.getElementById('waSection');

    if (method === 'email') {
        btnEmail.style.background = '#11998e';
        btnEmail.style.color      = 'white';
        btnEmail.style.border     = 'none';
        btnWa.style.background    = 'white';
        btnWa.style.color         = '#11998e';
        btnWa.style.border        = '2px solid #11998e';
        emailSec.style.display    = 'block';
        waSec.style.display       = 'none';
    } else {
        btnWa.style.background    = '#11998e';
        btnWa.style.color         = 'white';
        btnWa.style.border        = 'none';
        btnEmail.style.background = 'white';
        btnEmail.style.color      = '#11998e';
        btnEmail.style.border     = '2px solid #11998e';
        emailSec.style.display    = 'none';
        waSec.style.display       = 'block';
    }

    document.getElementById('submitBtn').disabled = true;
    validateInput();
}

function validateInput() {
    const submitBtn = document.getElementById('submitBtn');

    if (currentMethod === 'email') {
        const val   = document.getElementById('email').value.trim();
        const valid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val);
        document.getElementById('emailError').style.display =
            (val.length > 0 && !valid) ? 'block' : 'none';
        submitBtn.disabled = !valid;
    } else {
        const val   = document.getElementById('phone').value.replace(/\D/g, '');
        const valid = val.length >= 9 && val.length <= 13;
        document.getElementById('phoneError').style.display =
            (val.length > 0 && !valid) ? 'block' : 'none';
        submitBtn.disabled = !valid;
    }
}

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('email').addEventListener('input', function () {
        this.value = this.value.replace(/\s/g, '');
        validateInput();
    });

    document.getElementById('phone').addEventListener('input', function () {
        this.value = this.value.replace(/\D/g, '');
        validateInput();
    });

    ['email', 'phone'].forEach(id => {
        document.getElementById(id).addEventListener('keydown', function (e) {
            if (e.key === ' ') e.preventDefault();
        });
    });
});
</script>
</body>
</html>