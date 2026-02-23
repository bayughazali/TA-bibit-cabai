@extends('layouts.app')

@section('title', 'Login - Shop Bibit Cabai Bondowoso')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-success text-white text-center py-4">
                    <h3 class="mb-0">
                        <i class="fas fa-seedling me-2"></i>Masuk ke Akun Anda
                    </h3>
                </div>
                
                <div class="card-body p-4">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" id="loginForm">
                        @csrf
                        
                        <div class="mb-3">
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
                                       autofocus
                                       placeholder="contoh@gmail.com">
                            </div>
                            <div class="form-text text-muted">
                                <small><i class="fas fa-info-circle"></i> Harus menggunakan email @gmail.com</small>
                            </div>
                            @error('email')
                            <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                            @enderror
                            <div id="emailError" class="text-danger mt-1" style="display: none;">
                                <small>Email harus menggunakan domain @gmail.com dan tidak boleh mengandung spasi</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       required 
                                       autocomplete="current-password"
                                       placeholder="Minimal 8 karakter">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fas fa-eye" id="eyeIcon"></i>
                                </button>
                            </div>
                            <div class="form-text text-muted">
                                <small><i class="fas fa-info-circle"></i> Password minimal 8 karakter, tanpa spasi</small>
                            </div>
                            @error('password')
                            <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                            @enderror
                            <div id="passwordError" class="text-danger mt-1" style="display: none;">
                                <small>Password minimal 8 karakter dan tidak boleh mengandung spasi</small>
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input @error('remember') is-invalid @enderror" id="remember" name="remember" required>
                            <label class="form-check-label" for="remember">
                                Ingat saya <span class="text-danger">*</span>
                            </label>
                            @error('remember')
                            <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                            @enderror
                            <div id="rememberError" class="text-danger mt-1" style="display: none;">
                                <small>Anda harus mencentang "Ingat saya" untuk melanjutkan</small>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg" id="submitBtn" disabled>
                                <i class="fas fa-sign-in-alt me-2"></i>Masuk
                            </button>
                        </div>
                    </form>

                    <hr class="my-4">
                    
                    <div class="text-center">
                        <p class="mb-0">Belum punya akun? 
                            <a href="{{ route('register') }}" class="text-success text-decoration-none fw-bold">
                                Daftar di sini
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
    const form = document.getElementById('loginForm');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const rememberCheckbox = document.getElementById('remember');
    const togglePassword = document.getElementById('togglePassword');
    const eyeIcon = document.getElementById('eyeIcon');
    const submitBtn = document.getElementById('submitBtn');

    // Toggle password visibility
    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        eyeIcon.classList.toggle('fa-eye');
        eyeIcon.classList.toggle('fa-eye-slash');
    });

    // Real-time validation for email
    emailInput.addEventListener('input', function() {
        const emailError = document.getElementById('emailError');
        const gmailPattern = /@gmail\.com$/;
        const hasSpaces = /\s/.test(this.value);
        
        if ((!gmailPattern.test(this.value) || hasSpaces) && this.value.length > 0) {
            emailError.style.display = 'block';
            this.classList.add('is-invalid');
        } else {
            emailError.style.display = 'none';
            this.classList.remove('is-invalid');
        }
        checkFormValidity();
    });

    // Real-time validation for password
    passwordInput.addEventListener('input', function() {
        const passwordError = document.getElementById('passwordError');
        const hasSpaces = /\s/.test(this.value);
        
        if (this.value.length < 8 || hasSpaces) {
            passwordError.style.display = 'block';
            this.classList.add('is-invalid');
        } else {
            passwordError.style.display = 'none';
            this.classList.remove('is-invalid');
        }
        checkFormValidity();
    });

    // Real-time validation for remember checkbox
    rememberCheckbox.addEventListener('change', function() {
        const rememberError = document.getElementById('rememberError');
        if (!this.checked) {
            rememberError.style.display = 'block';
            this.classList.add('is-invalid');
        } else {
            rememberError.style.display = 'none';
            this.classList.remove('is-invalid');
        }
        checkFormValidity();
    });

    // Check form validity and enable/disable submit button
    function checkFormValidity() {
        const emailValid = emailInput.value.length > 0 && 
                          /@gmail\.com$/.test(emailInput.value) && 
                          !/\s/.test(emailInput.value);
        const passwordValid = passwordInput.value.length >= 8 && 
                             !/\s/.test(passwordInput.value);
        const rememberValid = rememberCheckbox.checked;
        
        if (emailValid && passwordValid && rememberValid) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('btn-secondary');
            submitBtn.classList.add('btn-success');
        } else {
            submitBtn.disabled = true;
            submitBtn.classList.remove('btn-success');
            submitBtn.classList.add('btn-secondary');
        }
    }

    // Prevent spaces in email and password fields
    emailInput.addEventListener('keydown', function(e) {
        if (e.key === ' ') {
            e.preventDefault();
        }
    });

    passwordInput.addEventListener('keydown', function(e) {
        if (e.key === ' ') {
            e.preventDefault();
        }
    });

    // Form validation before submit
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Check email validation
        const gmailPattern = /@gmail\.com$/;
        const emailHasSpaces = /\s/.test(emailInput.value);
        if (!gmailPattern.test(emailInput.value) || emailHasSpaces) {
            document.getElementById('emailError').style.display = 'block';
            emailInput.classList.add('is-invalid');
            isValid = false;
        }
        
        // Check password validation
        const passwordHasSpaces = /\s/.test(passwordInput.value);
        if (passwordInput.value.length < 8 || passwordHasSpaces) {
            document.getElementById('passwordError').style.display = 'block';
            passwordInput.classList.add('is-invalid');
            isValid = false;
        }
        
        // Check remember checkbox
        if (!rememberCheckbox.checked) {
            document.getElementById('rememberError').style.display = 'block';
            rememberCheckbox.classList.add('is-invalid');
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

    // Initialize form state
    checkFormValidity();
});
</script>
@endsection