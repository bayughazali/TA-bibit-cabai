<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Kode Admin - TA Bibit Cabai</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 28px;
        }
        .step-circle {
            width: 36px; height: 36px;
            border-radius: 50%;
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
        .otp-box {
            width: 46px !important;
            height: 54px !important;
            padding: 0 !important;
            border-radius: 10px !important;
            border: 2px solid #dee2e6 !important;
            font-size: 22px !important;
            font-weight: 700 !important;
            text-align: center;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .otp-box:focus {
            border-color: #11998e !important;
            box-shadow: 0 0 0 3px rgba(17,153,142,0.2) !important;
            outline: none !important;
        }
        .otp-box.filled {
            border-color: #11998e !important;
            background-color: #f0fff4 !important;
        }
        /* Kotak OTP saat expired */
        .otp-box:disabled {
            background-color: #f8f9fa !important;
            border-color: #dee2e6 !important;
            color: #adb5bd !important;
            cursor: not-allowed !important;
        }
        .email-badge {
            background: #f0fff4;
            border: 1px solid #11998e33;
            border-left: 3px solid #11998e;
            border-radius: 8px;
            padding: 10px 14px;
        }
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

                    <div class="icon-box">🛡️</div>

                    <div class="text-center mb-3">
                        <h4 class="fw-bold text-dark mb-1">Verifikasi Kode OTP</h4>
                        <p class="text-muted small">Masukkan kode 6 digit yang dikirim ke email</p>
                    </div>

                    {{-- Step Indicator --}}
                    <div class="step-wrapper">
                        <div class="step-item">
                            <div class="step-circle done">✓</div>
                            <div class="step-label done">Email</div>
                        </div>
                        <div class="step-line done"></div>
                        <div class="step-item">
                            <div class="step-circle active">2</div>
                            <div class="step-label active">Kode OTP</div>
                        </div>
                        <div class="step-line idle"></div>
                        <div class="step-item">
                            <div class="step-circle idle">3</div>
                            <div class="step-label idle">Password Baru</div>
                        </div>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show py-2" role="alert">
                            <small>✅ {{ session('success') }}</small>
                            <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
                            <small>⚠️ {{ session('error') }}</small>
                            <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- Email Target Badge --}}
                    <div class="email-badge mb-4 d-flex align-items-center gap-2">
                        <span style="font-size:20px;">📧</span>
                        <div>
                            <div class="fw-semibold text-dark small">Kode dikirim ke:</div>
                            <div class="text-muted" style="font-size:13px;">{{ session('admin_reset_email', 'email admin') }}</div>
                        </div>
                    </div>

                    {{-- =============================================
                         FORM OTP — disabled seluruhnya saat expired
                         ============================================= --}}
                    <form method="POST" action="{{ route('admin.password.verify-otp') }}" id="otpForm" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-center d-block">
                                Kode Verifikasi (6 digit)
                            </label>

                            {{-- OTP Boxes --}}
                            <div class="d-flex justify-content-center gap-2 mb-2" id="otpBoxes">
                                {{-- disabled langsung dari PHP jika backend bilang expired --}}
                                @php $isExpiredFromServer = session('otp_expired', false); @endphp

                                @for ($i = 0; $i < 6; $i++)
                                <input type="text"
                                       class="otp-box form-control"
                                       maxlength="1"
                                       inputmode="numeric"
                                       pattern="[0-9]"
                                       autocomplete="off"
                                       {{ $isExpiredFromServer ? 'disabled' : '' }}>
                                @endfor
                            </div>
                            <input type="hidden" name="otp" id="otpHidden">

                            @error('otp')
                                <div class="text-danger text-center mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Countdown Timer --}}
                        <div class="text-center mb-4">
                            <div id="timerWrapper" {{ $isExpiredFromServer ? 'style=display:none;' : '' }}>
                                <p class="text-muted small mb-1">Kode berlaku selama:</p>
                                <span id="countdown" class="badge fs-6 px-3 py-2" style="background:#11998e;">03:00</span>
                            </div>
                            <div id="expiredWrapper" {{ $isExpiredFromServer ? '' : 'style=display:none;' }}>
                                <span class="badge bg-danger fs-6 px-3 py-2">
                                    ❌ Kode sudah kadaluarsa
                                </span>
                            </div>
                        </div>

                        {{-- Tombol Verifikasi --}}
                        {{-- disabled dari server jika expired, dari JS jika OTP belum 6 digit --}}
                        <div class="d-grid mb-3">
                            <button type="submit"
                                    class="btn btn-primary-custom py-2 fw-bold"
                                    id="submitBtn"
                                    {{ $isExpiredFromServer ? 'disabled' : 'disabled' }}>
                                @if($isExpiredFromServer)
                                    ⛔ Waktu Habis
                                @else
                                    ✅ Verifikasi Kode
                                @endif
                            </button>
                        </div>
                    </form>

                    <hr class="my-3">

                    {{-- Resend — hanya aktif saat expired --}}
                    <div class="text-center">
                        <p class="text-muted small mb-2">Tidak menerima kode?</p>
                        <form method="POST" action="{{ route('admin.password.resend-otp') }}" id="resendForm">
                            @csrf
                            <button type="submit"
                                    class="btn btn-outline-secondary btn-sm"
                                    id="resendBtn"
                                    {{ $isExpiredFromServer ? '' : 'disabled' }}>
                                🔄 Kirim Ulang Kode
                            </button>
                        </form>
                        <div class="mt-2">
                            <a href="{{ route('admin.password.request') }}" class="text-decoration-none small text-muted">
                                ← Ganti email
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Variabel elemen ──────────────────────────────────────
    const boxes       = document.querySelectorAll('.otp-box');
    const otpHidden   = document.getElementById('otpHidden');
    const submitBtn   = document.getElementById('submitBtn');
    const resendBtn   = document.getElementById('resendBtn');
    const resendForm  = document.getElementById('resendForm');
    const countdown   = document.getElementById('countdown');
    const timerWrap   = document.getElementById('timerWrapper');
    const expiredWrap = document.getElementById('expiredWrapper');

    // ── Flag dari backend (PHP → JS) ─────────────────────────
    // true  = backend sudah konfirmasi OTP expired (record dihapus)
    // false = backend belum, timer frontend yang jalan
    const isExpiredFromServer = {{ $isExpiredFromServer ? 'true' : 'false' }};

    // ── Jika sudah expired dari server → langsung lock ───────
    if (isExpiredFromServer) {
        lockExpired();
        return; // stop, tidak perlu jalankan timer
    }

    // ── OTP input logic ──────────────────────────────────────
    boxes.forEach((box, i) => {
        box.addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value) {
                this.classList.add('filled');
                if (i < boxes.length - 1) boxes[i + 1].focus();
            } else {
                this.classList.remove('filled');
            }
            updateHidden();
        });

        box.addEventListener('keydown', function (e) {
            if (e.key === 'Backspace' && !this.value && i > 0) {
                boxes[i - 1].focus();
                boxes[i - 1].value = '';
                boxes[i - 1].classList.remove('filled');
                updateHidden();
            }
        });

        box.addEventListener('paste', function (e) {
            e.preventDefault();
            const pasted = (e.clipboardData || window.clipboardData)
                            .getData('text').replace(/\D/g, '');
            pasted.split('').slice(0, 6).forEach((ch, idx) => {
                if (boxes[idx]) {
                    boxes[idx].value = ch;
                    boxes[idx].classList.add('filled');
                }
            });
            updateHidden();
            boxes[Math.min(pasted.length, 5)].focus();
        });
    });

    function updateHidden() {
        const otp = Array.from(boxes).map(b => b.value).join('');
        otpHidden.value = otp;
        // Tombol verify aktif hanya jika 6 digit DAN belum expired
        submitBtn.disabled = (otp.length !== 6);
    }

    // ── Countdown — pakai sessionStorage agar refresh tidak reset ──
    // Key unik per email agar tidak bentrok jika beda sesi
    const STORAGE_KEY  = 'otp_timer_{{ md5(session("admin_reset_email", "x")) }}';
    const PENALTY_KEY  = 'otp_penalty_{{ md5(session("admin_reset_email", "x")) }}';
    const DURATION     = 3 * 60; // 180 detik (3 menit)
    const WRONG_PENALTY = 30;    // pengurangan waktu saat salah OTP (detik)

    let startTime = parseInt(sessionStorage.getItem(STORAGE_KEY) || '0');
    if (!startTime || isNaN(startTime)) {
        startTime = Date.now();
        sessionStorage.setItem(STORAGE_KEY, startTime);
    }

    // ── Terapkan penalty jika backend bilang OTP salah ────────
    // Backend set session 'otp_wrong' = true saat Hash::check gagal
    const wrongFromServer = {{ session('otp_wrong') ? 'true' : 'false' }};
    if (wrongFromServer) {
        // Tambah penalty ke startTime (maju ke masa lalu = sisa waktu berkurang)
        startTime -= (WRONG_PENALTY * 1000);
        sessionStorage.setItem(STORAGE_KEY, startTime);
        sessionStorage.removeItem(PENALTY_KEY);
    }

    const timerInterval = setInterval(() => {
        const elapsed   = Math.floor((Date.now() - startTime) / 1000);
        const remaining = DURATION - elapsed;

        if (remaining <= 0) {
            clearInterval(timerInterval);
            sessionStorage.removeItem(STORAGE_KEY);
            lockExpired();
            return;
        }

        // Warna kuning saat sisa ≤ 30 detik
        if (remaining <= 30) {
            countdown.style.background = '#dc3545';
            countdown.style.color = '#fff';
        } else if (remaining <= 60) {
            countdown.style.background = '#ffc107';
            countdown.style.color = '#000';
        }

        const m = String(Math.floor(remaining / 60)).padStart(2, '0');
        const s = String(remaining % 60).padStart(2, '0');
        countdown.textContent = `${m}:${s}`;
    }, 500);

    // ── Fungsi lock saat expired ─────────────────────────────
    function lockExpired() {
        // Sembunyikan timer, tampilkan badge expired
        if (timerWrap)   timerWrap.style.display   = 'none';
        if (expiredWrap) expiredWrap.style.display  = 'block';

        // Disable & ubah teks tombol verifikasi
        submitBtn.disabled    = true;
        submitBtn.textContent = '⛔ Waktu Habis';
        submitBtn.style.background = '#adb5bd';

        // Blokir submit form meski tombol diklik paksa (inject JS / inspect)
        document.getElementById('otpForm').addEventListener('submit', function (e) {
            e.preventDefault();
            return false;
        });

        // Disable semua kotak OTP
        boxes.forEach(b => {
            b.disabled = true;
            b.value    = '';
            b.classList.remove('filled');
        });

        // Aktifkan tombol kirim ulang
        resendBtn.disabled = false;
    }

    // ── Bersihkan sessionStorage saat resend dikirim ─────────
    resendForm.addEventListener('submit', function () {
        sessionStorage.removeItem(STORAGE_KEY);
    });

});
</script>
</body>
</html>