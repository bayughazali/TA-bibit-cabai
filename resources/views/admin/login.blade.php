<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - TA Bibit Cabal</title>
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
        .btn-login {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            border: none;
            border-radius: 25px;
            color: white;
            font-weight: 600;
        }
        .btn-login:hover {
            background: linear-gradient(135deg, #0d7377 0%, #2dd654 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(17, 153, 142, 0.3);
            color: white;
        }
        .form-control:focus {
            border-color: #11998e;
            box-shadow: 0 0 0 0.2rem rgba(17, 153, 142, 0.25);
        }
        .text-primary {
            color: #11998e !important;
        }
        .btn-link {
            color: #11998e;
        }
        .btn-link:hover {
            color: #0d7377;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card login-card">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h3 class="fw-bold text-dark">Admin Login</h3>
                            <p class="text-muted">TA Bibit Cabal</p>
                        </div>

                        {{-- Error Messages --}}
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        {{-- Success Messages --}}
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        {{-- Login Form --}}
                        <form method="POST" action="{{ route('admin.login') }}" novalidate>
                            @csrf
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Admin</label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}"
                                       placeholder="contoh: admin@example.com"
                                       autocomplete="email"
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <div class="position-relative">
                                    <input type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password"
                                           placeholder="Masukkan password"
                                           autocomplete="current-password"
                                           required>
                                    <button type="button" 
                                            class="btn btn-link position-absolute end-0 top-50 translate-middle-y pe-3" 
                                            onclick="togglePassword()">
                                        👁️
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-login py-3 fw-bold">
                                    🌿 Masuk ke Dashboard
                                </button>
                            </div>
                        </form>

                        <div class="text-center mt-4">
                            <a href="{{ url('/') }}" class="text-decoration-none" style="color: #11998e;">
                                ← Kembali ke Beranda
                            </a>
                        </div>

                        {{-- Debug Info (Hapus di production) --}}
                        @if (config('app.debug'))
                            <div class="mt-4 p-2 bg-light rounded small">
                                <strong>Debug Info:</strong><br>
                                Route: {{ Route::currentRouteName() }}<br>
                                Method: {{ request()->method() }}<br>
                                @if(request()->method() === 'POST')
                                    Data: {{ json_encode(request()->only(['email'])) }}
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const button = event.target;
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                button.textContent = '🙈';
            } else {
                passwordField.type = 'password';
                button.textContent = '👁️';
            }
        }

        // Debug: Log form data saat submit
        document.querySelector('form').addEventListener('submit', function(e) {
            const formData = new FormData(this);
            console.log('Form Data:');
            for (let [key, value] of formData.entries()) {
                console.log(key + ': "' + value + '"');
            }
        });

        // Clear validation errors saat user mulai mengetik
        document.getElementById('email').addEventListener('input', function() {
            this.classList.remove('is-invalid');
            const feedback = this.nextElementSibling;
            if (feedback && feedback.classList.contains('invalid-feedback')) {
                feedback.style.display = 'none';
            }
        });

        document.getElementById('password').addEventListener('input', function() {
            this.classList.remove('is-invalid');
            const feedback = this.nextElementSibling;
            if (feedback && feedback.classList.contains('invalid-feedback')) {
                feedback.style.display = 'none';
            }
        });
    </script>
</body>
</html>