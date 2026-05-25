@extends('layouts.app')

@section('title', 'Verifikasi Kode - Shop Bibit Cabai Bondowoso')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-success text-white text-center py-4">
                    <h3 class="mb-0">
                        <i class="fas fa-shield-alt me-2"></i>Verifikasi Kode
                    </h3>
                    <p class="mb-0 mt-2 opacity-75 small">Masukkan kode yang dikirim ke email Anda</p>
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
                        <div class="step-item active">
                            <div class="step-circle bg-success text-white">2</div>
                            <div class="step-label text-success fw-bold">Kode OTP</div>
                        </div>
                        <div class="step-line bg-secondary mx-2"></div>
                        <div class="step-item">
                            <div class="step-circle bg-secondary text-white">3</div>
                            <div class="step-label text-secondary">Password Baru</div>
                        </div>
                    </div>

                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                  {{-- Info target pengiriman --}}
                    <div class="alert alert-light border-start border-success border-3 mb-4">
                        <div class="d-flex align-items-center">
                            @if(session('reset_method') === 'whatsapp')
                                <i class="fab fa-whatsapp text-success me-3 fs-5"></i>
                                <div>
                                    <div class="fw-semibold text-dark">Kode dikirim via WhatsApp ke:</div>
                                    <div class="text-muted small">{{ session('reset_phone_display', 'nomor WA Anda') }}</div>
                                </div>
                            @else
                                <i class="fas fa-envelope text-success me-3 fs-5"></i>
                                <div>
                                    <div class="fw-semibold text-dark">Kode dikirim ke:</div>
                                    <div class="text-muted small">{{ session('reset_email', 'email Anda') }}</div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <form method="POST" action="{{ route('password.verify-otp') }}" id="otpForm">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-semibold text-center d-block">
                                Kode Verifikasi (6 digit) <span class="text-danger">*</span>
                            </label>

                            {{-- OTP Input Boxes --}}
                            <div class="d-flex justify-content-center gap-2 mb-2" id="otpBoxes">
                                <input type="text" class="otp-box form-control text-center fw-bold fs-4"
                                       maxlength="1" inputmode="numeric" pattern="[0-9]" autocomplete="off">
                                <input type="text" class="otp-box form-control text-center fw-bold fs-4"
                                       maxlength="1" inputmode="numeric" pattern="[0-9]" autocomplete="off">
                                <input type="text" class="otp-box form-control text-center fw-bold fs-4"
                                       maxlength="1" inputmode="numeric" pattern="[0-9]" autocomplete="off">
                                <input type="text" class="otp-box form-control text-center fw-bold fs-4"
                                       maxlength="1" inputmode="numeric" pattern="[0-9]" autocomplete="off">
                                <input type="text" class="otp-box form-control text-center fw-bold fs-4"
                                       maxlength="1" inputmode="numeric" pattern="[0-9]" autocomplete="off">
                                <input type="text" class="otp-box form-control text-center fw-bold fs-4"
                                       maxlength="1" inputmode="numeric" pattern="[0-9]" autocomplete="off">
                            </div>

                            {{-- Hidden input to store full OTP --}}
                            <input type="hidden" name="otp" id="otpHidden">

                            @error('otp')
                            <div class="text-danger text-center mt-1"><small>{{ $message }}</small></div>
                            @enderror
                        </div>

                        {{-- Countdown Timer --}}
                        <div class="text-center mb-4">
                            <div id="timerWrapper">
                               <p class="text-muted small mb-1">Kode berlaku selama:</p>
                                <span id="countdown" class="badge bg-success fs-6 px-3 py-2">03:00</span>
                            </div>
                          <div id="expiredWrapper" style="display:none;">
                            <div class="alert alert-danger text-center py-2 px-3 mb-0">
                                <i class="fas fa-times-circle me-1"></i>
                                <strong>Kode sudah kadaluarsa.</strong><br>
                                <small>Silakan minta kirim kode lagi.</small>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg" id="submitBtn" disabled>
                                <i class="fas fa-check-circle me-2"></i>Verifikasi Kode
                            </button>
                        </div>
                    </form>

                    <hr class="my-4">

                    {{-- Resend OTP --}}
                    <div class="text-center">
                        <p class="text-muted small mb-2">Tidak menerima kode?</p>
                        <form method="POST" action="{{ route('password.resend-otp') }}" id="resendForm">
                            @csrf
                            <button type="submit" class="btn btn-outline-success btn-sm" id="resendBtn" disabled>
                                <i class="fas fa-redo me-1"></i>Kirim Ulang Kode
                            </button>
                        </form>
                        <div class="mt-2">
                            <a href="{{ route('password.request') }}" class="text-muted text-decoration-none small">
                                <i class="fas fa-arrow-left me-1"></i>Ganti email
                            </a>
                        </div>
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
.otp-box {
    width: 48px !important;
    height: 56px !important;
    padding: 0 !important;
    border-radius: 10px !important;
    border: 2px solid #dee2e6 !important;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.otp-box:focus {
    border-color: #198754 !important;
    box-shadow: 0 0 0 3px rgba(25,135,84,0.2) !important;
}
.otp-box.filled {
    border-color: #198754 !important;
    background-color: #f0fff4 !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const boxes      = document.querySelectorAll('.otp-box');
    const otpHidden  = document.getElementById('otpHidden');
    const submitBtn  = document.getElementById('submitBtn');
    const resendBtn  = document.getElementById('resendBtn');
    const countdownEl   = document.getElementById('countdown');
    const timerWrapper  = document.getElementById('timerWrapper');
    const expiredWrapper = document.getElementById('expiredWrapper');

    let isExpired = false; // ← flag global

    // ---- OTP box logic ----
    boxes.forEach((box, index) => {
        box.addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length === 1) {
                this.classList.add('filled');
                if (index < boxes.length - 1) boxes[index + 1].focus();
            } else {
                this.classList.remove('filled');
            }
            updateHidden();
        });

        box.addEventListener('keydown', function (e) {
            if (e.key === 'Backspace' && this.value === '' && index > 0) {
                boxes[index - 1].focus();
                boxes[index - 1].value = '';
                boxes[index - 1].classList.remove('filled');
                updateHidden();
            }
        });

        box.addEventListener('paste', function (e) {
            e.preventDefault();
            const pasted = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '');
            pasted.split('').slice(0, 6).forEach((char, i) => {
                if (boxes[i]) {
                    boxes[i].value = char;
                    boxes[i].classList.add('filled');
                }
            });
            updateHidden();
            const lastFilled = Math.min(pasted.length, 5);
            boxes[lastFilled].focus();
        });
    });

    function updateHidden() {
        const otp = Array.from(boxes).map(b => b.value).join('');
        otpHidden.value = otp;
        // Tombol submit aktif HANYA jika 6 digit DAN belum expired
        submitBtn.disabled = (otp.length !== 6 || isExpired);
    }

    // ---- Countdown Timer: 3 menit ----
    let seconds = 3 * 60;

    function formatTime(s) {
        const m   = Math.floor(s / 60).toString().padStart(2, '0');
        const sec = (s % 60).toString().padStart(2, '0');
        return `${m}:${sec}`;
    }

    const timer = setInterval(function () {
        seconds--;
        countdownEl.textContent = formatTime(seconds);

        // Berubah merah saat 60 detik terakhir
        if (seconds <= 60) {
            countdownEl.classList.remove('bg-success', 'bg-warning');
            countdownEl.classList.add('bg-danger');
        }

        if (seconds <= 0) {
            clearInterval(timer);

            // Set flag expired
            isExpired = true;

            // Sembunyikan timer, tampilkan pesan expired
            timerWrapper.style.display = 'none';
            expiredWrapper.style.display = 'block';

            // Nonaktifkan submit & semua kotak OTP
            submitBtn.disabled = true;
            boxes.forEach(box => {
                box.disabled = true;
                box.classList.add('bg-light');
            });

            // Aktifkan tombol kirim ulang
            resendBtn.disabled = false;
        }
    }, 1000);

    // Aktifkan resend setelah 30 detik
    setTimeout(() => {
        if (!isExpired) resendBtn.disabled = false;
    }, 30000);
});
</script>
@endsection