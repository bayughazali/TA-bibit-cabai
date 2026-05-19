<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password Admin - TA Bibit Cabai</title>
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
        }
        .form-control:focus {
            border-color: #11998e;
            box-shadow: 0 0 0 0.2rem rgba(17, 153, 142, 0.25);
        }

        .step-wrapper {
            display: flex; align-items: center; justify-content: center; margin-bottom: 28px;
        }
        .step-circle {
            width: 36px; height: 36px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 13px;
        }
        .step-circle.active { background: #11998e; color: #fff; }
        .step-circle.done   { background: #11998e; color: #fff; }
        .step-circle.idle   { background: #dee2e6; color: #888; }
        .step-label { font-size: 11px; white-space: nowrap; margin-top: 4px; }
        .step-label.active { color: #11998e; font-weight: 700; }
        .step-label.done   { color: #11998e; }
        .step-label.idle   { color: #aaa; }
        .step-item { display: flex; flex-direction: column; align-items: center; }
        .step-line { height: 2px; width: 36px; margin: 0 6px 18px; }
        .step-line.done { background: #11998e; }
        .step-line.idle { background: #dee2e6; }

        .icon-box {
            width: 64px; height: 64px;
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
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

                    <div class="icon-box">🔓</div>

                    <div class="text-center mb-3">
                        <h4 class="fw-bold text-dark mb-1">Password Baru Admin</h4>
                        <p class="text-muted small">Buat password baru yang kuat untuk akun admin</p>
                    </div>

                    {{-- Step Indicator --}}
                    <div class="step-wrapper">
                        <div class="step-item">
                            <div class="step-circle done">✓</div>
                            <div class="step-label done">Email</div>
                        </div>
                        <div class="step-line done"></div>
                        <div class="step-item">
                            <div class="step-circle done">✓</div>
                            <div class="step-label done">Kode OTP</div>
                        </div>
                        <div class="step-line done"></div>
                        <div class="step-item">
                            <div class="step-circle active">3</div>
                            <div class="step-label active">Password Baru</div>
                        </div>
                    </div>

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

                    <form method="POST" action="{{ route('admin.password.update') }}" id="resetForm" novalidate>
                        @csrf
                        <input type="hidden" name="token" value="{{ session('admin_reset_token') }}">
                        <input type="hidden" name="email" value="{{ session('admin_reset_email') }}">

                        {{-- New Password --}}
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Password Baru</label>
                            <div class="input-group">
                                <input type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       id="password"
                                       name="password"
                                       placeholder="Minimal 8 karakter"
                                       required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePw1">👁️</button>
                            </div>
                            <div class="form-text text-muted small">ℹ️ Minimal 8 karakter, tanpa spasi</div>
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            <div id="pwError" class="text-danger small mt-1" style="display:none;">
                                ⚠️ Password minimal 8 karakter dan tidak boleh mengandung spasi
                            </div>
                        </div>

                        {{-- Strength Bar --}}
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <small class="text-muted">Kekuatan password:</small>
                                <small id="strengthLabel" class="fw-semibold text-secondary">—</small>
                            </div>
                            <div class="progress" style="height:6px;border-radius:3px;">
                                <div id="strengthBar" class="progress-bar bg-secondary" style="width:0%;transition:width .3s;"></div>
                            </div>
                        </div>

                        {{-- Confirm Password --}}
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password</label>
                            <div class="input-group">
                                <input type="password"
                                       class="form-control @error('password_confirmation') is-invalid @enderror"
                                       id="password_confirmation"
                                       name="password_confirmation"
                                       placeholder="Ulangi password baru"
                                       required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePw2">👁️</button>
                            </div>
                            @error('password_confirmation')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            <div id="confirmError" class="text-danger small mt-1" style="display:none;">
                                ⚠️ Password tidak cocok
                            </div>
                            <div id="confirmOk" class="text-success small mt-1" style="display:none;">
                                ✅ Password cocok
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary-custom py-2 fw-bold" id="submitBtn" disabled>
                                💾 Simpan Password Baru
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const pw    = document.getElementById('password');
    const conf  = document.getElementById('password_confirmation');
    const btn   = document.getElementById('submitBtn');
    const bar   = document.getElementById('strengthBar');
    const label = document.getElementById('strengthLabel');
    const pwErr = document.getElementById('pwError');
    const cErr  = document.getElementById('confirmError');
    const cOk   = document.getElementById('confirmOk');

    // Toggle visibility
    document.getElementById('togglePw1').addEventListener('click', () => {
        pw.type = pw.type === 'password' ? 'text' : 'password';
        document.getElementById('togglePw1').textContent = pw.type === 'password' ? '👁️' : '🙈';
    });
    document.getElementById('togglePw2').addEventListener('click', () => {
        conf.type = conf.type === 'password' ? 'text' : 'password';
        document.getElementById('togglePw2').textContent = conf.type === 'password' ? '👁️' : '🙈';
    });

    // Strength checker
    function strength(v) {
        let s = 0;
        if (v.length >= 8)  s++;
        if (v.length >= 12) s++;
        if (/[A-Z]/.test(v)) s++;
        if (/[0-9]/.test(v)) s++;
        if (/[^A-Za-z0-9]/.test(v)) s++;
        return s;
    }

    pw.addEventListener('input', function () {
        const v = this.value;
        const invalid = v.length > 0 && (v.length < 8 || /\s/.test(v));
        pwErr.style.display = invalid ? 'block' : 'none';
        this.classList.toggle('is-invalid', invalid);

        // Strength bar
        if (v.length === 0) {
            bar.style.width = '0%'; bar.className = 'progress-bar bg-secondary';
            label.textContent = '—'; label.className = 'fw-semibold text-secondary';
        } else {
            const lvls = [
                { w:'20%', c:'bg-danger',  l:'Sangat Lemah', lc:'text-danger'  },
                { w:'40%', c:'bg-warning', l:'Lemah',        lc:'text-warning' },
                { w:'60%', c:'bg-info',    l:'Sedang',       lc:'text-info'    },
                { w:'80%', c:'bg-primary', l:'Kuat',         lc:'text-primary' },
                { w:'100%',c:'bg-success', l:'Sangat Kuat',  lc:'text-success' },
            ];
            const lvl = lvls[Math.min(strength(v) - 1, 4)] || lvls[0];
            bar.style.width = lvl.w; bar.className = `progress-bar ${lvl.c}`;
            label.textContent = lvl.l; label.className = `fw-semibold ${lvl.lc}`;
        }
        checkConf(); checkBtn();
    });

    conf.addEventListener('input', function () { checkConf(); checkBtn(); });

    function checkConf() {
        if (conf.value.length === 0) {
            cErr.style.display = 'none'; cOk.style.display = 'none';
            conf.classList.remove('is-valid', 'is-invalid');
        } else if (conf.value !== pw.value) {
            cErr.style.display = 'block'; cOk.style.display = 'none';
            conf.classList.add('is-invalid'); conf.classList.remove('is-valid');
        } else {
            cErr.style.display = 'none'; cOk.style.display = 'block';
            conf.classList.remove('is-invalid'); conf.classList.add('is-valid');
        }
    }

    function checkBtn() {
        const pwOk   = pw.value.length >= 8 && !/\s/.test(pw.value);
        const confOk = conf.value === pw.value && conf.value.length > 0;
        btn.disabled = !(pwOk && confOk);
    }

    [pw, conf].forEach(i => i.addEventListener('keydown', e => { if (e.key === ' ') e.preventDefault(); }));
});
</script>
</body>
</html>