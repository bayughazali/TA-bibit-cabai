@extends('layouts.app')

@section('title', 'Register - Shop Bibit Cabai Bondowoso')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-success text-white text-center py-4">
                    <h3 class="mb-0">
                        <i class="fas fa-user-plus me-2"></i>Daftar Akun Baru
                    </h3>
                </div>
                
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('register') }}" id="registerForm">
                        @csrf
                        
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           required 
                                           autocomplete="name"
                                           placeholder="Minimal 8 karakter">
                                </div>
                                <div class="form-text text-muted">
                                    <small><i class="fas fa-info-circle"></i> Nama minimal harus 8 karakter</small>
                                </div>
                                @error('name')
                                <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                                @enderror
                                <div id="nameError" class="text-danger mt-1" style="display: none;">
                                    <small>Nama minimal harus 8 karakter</small>
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           required 
                                           autocomplete="email"
                                           placeholder="contoh@gmail.com">
                                </div>
                                <div class="form-text text-muted">
                                    <small><i class="fas fa-info-circle"></i> Harus menggunakan email @gmail.com</small>
                                </div>
                                @error('email')
                                <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                                @enderror
                                <div id="emailError" class="text-danger mt-1" style="display: none;">
                                    <small>Email harus menggunakan domain @gmail.com</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="phone" class="form-label">No. Telepon <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    <input type="tel" 
                                           class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" 
                                           name="phone" 
                                           value="{{ old('phone') }}"
                                           required
                                placeholder="081234567890">
                                </div>
                                <div class="form-text text-muted">
                                    <small><i class="fas fa-info-circle"></i> Nomor telepon minimal 10 karakter</small>
                                </div>
                                @error('phone')
                                <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                                @enderror
                                <div id="phoneError" class="text-danger mt-1" style="display: none;">
                                <small>Nomor telepon minimal 10 karakter</small>
                            </div>
                            </div>

                            <div class="col-md-6">
                                <label for="address" class="form-label">Alamat <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                    <input type="text" 
                                           class="form-control @error('address') is-invalid @enderror" 
                                           id="address" 
                                           name="address" 
                                           value="{{ old('address') }}"
                                           required
                                          placeholder="Jl. Contoh No. 12, RT 01/RW 02, Desa Kademangan">
                                </div>
                                <div class="form-text text-muted">
                                    <small><i class="fas fa-info-circle"></i> Wajib lengkap: Nama Jalan · No. Rumah · RT/RW · Desa/Kelurahan</small>
                                </div>
                                @error('address')
                                <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                                @enderror
                                <div id="addressError" class="text-danger mt-1" style="display: none;">
                                <small>Alamat terlalu pendek, harap isi lengkap (minimal 20 karakter)</small>
                            </div>
                            </div>

                            <div class="col-md-6">
                                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           required 
                                           autocomplete="new-password"
                                           placeholder="Minimal 8 karakter">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fas fa-eye" id="eyeIcon"></i>
                                    </button>
                                </div>
                                <div class="form-text text-muted">
                                    <small><i class="fas fa-info-circle"></i> Password minimal 8 karakter</small>
                                </div>
                                @error('password')
                                <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                                @enderror
                                <div id="passwordError" class="text-danger mt-1" style="display: none;">
                                    <small>Password minimal harus 8 karakter</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" 
                                           class="form-control" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           required 
                                           autocomplete="new-password"
                                           placeholder="Ulangi password">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirmation">
                                        <i class="fas fa-eye" id="eyeIconConfirm"></i>
                                    </button>
                                </div>
                                <div id="passwordMatchError" class="text-danger mt-1" style="display: none;">
                                    <small>Konfirmasi password tidak cocok</small>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input @error('agree') is-invalid @enderror" id="agree" name="agree" required>
                                    <label class="form-check-label" for="agree">
                                        Saya setuju dengan <a href="#" class="text-success">syarat dan ketentuan</a> <span class="text-danger">*</span>
                                    </label>
                                </div>
                                @error('agree')
                                <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                                @enderror
                                <div id="agreeError" class="text-danger mt-1" style="display: none;">
                                    <small>Anda harus menyetujui syarat dan ketentuan</small>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-success btn-lg" id="submitBtn">
                                        <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <hr class="my-4">
                    
                    <div class="text-center">
                        <p class="mb-0">Sudah punya akun? 
                            <a href="{{ route('login') }}" class="text-success text-decoration-none fw-bold">
                                Masuk di sini
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registerForm');
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const phoneInput = document.getElementById('phone');
    const addressInput = document.getElementById('address');
    const passwordInput = document.getElementById('password');
    const passwordConfirmInput = document.getElementById('password_confirmation');
    const togglePassword = document.getElementById('togglePassword');
    const togglePasswordConfirmation = document.getElementById('togglePasswordConfirmation');
    const eyeIcon = document.getElementById('eyeIcon');
    const eyeIconConfirm = document.getElementById('eyeIconConfirm');
    const agreeCheckbox = document.getElementById('agree');
    const submitBtn = document.getElementById('submitBtn');

    // Toggle password visibility
    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        eyeIcon.classList.toggle('fa-eye');
        eyeIcon.classList.toggle('fa-eye-slash');
    });

    togglePasswordConfirmation.addEventListener('click', function() {
        const type = passwordConfirmInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordConfirmInput.setAttribute('type', type);
        eyeIconConfirm.classList.toggle('fa-eye');
        eyeIconConfirm.classList.toggle('fa-eye-slash');
    });

    // Real-time validation
    nameInput.addEventListener('input', function() {
        const nameError = document.getElementById('nameError');
        if (this.value.length < 8) {
            nameError.style.display = 'block';
            this.classList.add('is-invalid');
        } else {
            nameError.style.display = 'none';
            this.classList.remove('is-invalid');
        }
    });

    emailInput.addEventListener('input', function() {
        const emailError = document.getElementById('emailError');
        const gmailPattern = /@gmail\.com$/;
        if (!gmailPattern.test(this.value) && this.value.length > 0) {
            emailError.style.display = 'block';
            this.classList.add('is-invalid');
        } else {
            emailError.style.display = 'none';
            this.classList.remove('is-invalid');
        }
    });

    phoneInput.addEventListener('input', function() {
    const phoneError = document.getElementById('phoneError');
    if (this.value.length < 10 && this.value.length > 0) {
        phoneError.style.display = 'block';
        this.classList.add('is-invalid');
    } else {
        phoneError.style.display = 'none';
        this.classList.remove('is-invalid');
    }
});

    addressInput.addEventListener('input', function() {
        const addressError = document.getElementById('addressError');
        if (this.value.length < 20) {
    addressError.style.display = 'block';
            this.classList.add('is-invalid');
        } else {
            addressError.style.display = 'none';
            this.classList.remove('is-invalid');
        }
    });

    passwordInput.addEventListener('input', function() {
        const passwordError = document.getElementById('passwordError');
        if (this.value.length < 8) {
            passwordError.style.display = 'block';
            this.classList.add('is-invalid');
        } else {
            passwordError.style.display = 'none';
            this.classList.remove('is-invalid');
        }
        checkPasswordMatch();
    });

    passwordConfirmInput.addEventListener('input', function() {
        checkPasswordMatch();
    });

    agreeCheckbox.addEventListener('change', function() {
        const agreeError = document.getElementById('agreeError');
        if (!this.checked) {
            agreeError.style.display = 'block';
            this.classList.add('is-invalid');
            submitBtn.disabled = true;
        } else {
            agreeError.style.display = 'none';
            this.classList.remove('is-invalid');
            submitBtn.disabled = false;
        }
    });

    function checkPasswordMatch() {
        const passwordMatchError = document.getElementById('passwordMatchError');
        if (passwordInput.value !== passwordConfirmInput.value && passwordConfirmInput.value.length > 0) {
            passwordMatchError.style.display = 'block';
            passwordConfirmInput.classList.add('is-invalid');
            return false;
        } else {
            passwordMatchError.style.display = 'none';
            passwordConfirmInput.classList.remove('is-invalid');
            return true;
        }
    }

    // Form validation before submit
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Check all validations
        if (nameInput.value.length < 8) {
    document.getElementById('nameError').style.display = 'block';
            nameInput.classList.add('is-invalid');
            isValid = false;
        }
        
        const gmailPattern = /@gmail\.com$/;
        if (!gmailPattern.test(emailInput.value)) {
            document.getElementById('emailError').style.display = 'block';
            emailInput.classList.add('is-invalid');
            isValid = false;
        }
        
       if (phoneInput.value.length < 10) {
    document.getElementById('phoneError').style.display = 'block';
    phoneInput.classList.add('is-invalid');
    isValid = false;
}
        
        if (addressInput.value.length < 20) {
    document.getElementById('addressError').style.display = 'block';
            addressInput.classList.add('is-invalid');
            isValid = false;
        }
        
        if (passwordInput.value.length < 8) {
            document.getElementById('passwordError').style.display = 'block';
            passwordInput.classList.add('is-invalid');
            isValid = false;
        }
        
        if (passwordInput.value !== passwordConfirmInput.value) {
            document.getElementById('passwordMatchError').style.display = 'block';
            passwordConfirmInput.classList.add('is-invalid');
            isValid = false;
        }
        
        if (!agreeCheckbox.checked) {
            document.getElementById('agreeError').style.display = 'block';
            agreeCheckbox.classList.add('is-invalid');
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
            // Scroll to first error
            const firstError = document.querySelector('.is-invalid');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });

    // Initialize submit button state
    submitBtn.disabled = !agreeCheckbox.checked;
});
</script>
@endsection