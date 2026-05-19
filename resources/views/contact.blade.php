@extends('layouts.app')

@section('title', 'Contact Us - Shop Bibit Cabai Bondowoso')

@section('content')
<div class="container py-5">
    <!-- Header Section -->
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold text-success">Contact Us</h1>
        <p class="lead text-muted">Hubungi kami untuk informasi lebih lanjut tentang bibit cabai berkualitas</p>
    </div>

    <div class="row g-5">
        <!-- Contact Information -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h3 class="text-success mb-4">
                        <i class="fas fa-store me-2"></i>Shop Bibit Cabai Bondowoso
                    </h3>
                    
                    <div class="contact-info">
                        <div class="mb-4">
                            <h5><i class="fas fa-map-marker-alt text-success me-2"></i>Alamat</h5>
                            <p class="text-muted">Jurang sapi, tapen, Bondowoso<br>Masuk paping sebelah toko</p>
                        </div>
                        
                        <div class="mb-4">
                            <h5><i class="fas fa-phone text-success me-2"></i>Telepon</h5>
                            <p class="text-muted">
                                <a href="tel:081331830561" class="text-decoration-none">081331830561</a>
                            </p>
                        </div>
                        
                        <div class="mb-4">
                            <h5><i class="fas fa-envelope text-success me-2"></i>Email</h5>
                            <p class="text-muted">
                                <a href="mailto:bibitcabai@gmail.com" class="text-decoration-none">
                                    bibitcabai@gmail.com
                                </a>
                            </p>
                        </div>
                        
                        <div class="mb-4">
                            <h5><i class="fab fa-whatsapp text-success me-2"></i>WhatsApp</h5>
                            <p class="text-muted">
                                <a href="https://wa.me/081331830561" class="text-decoration-none" target="_blank">
                                    081331830561
                                </a>
                            </p>
                        </div>
                        
                        <div class="mb-4">
                            <h5><i class="fas fa-clock text-success me-2"></i>Jam Operasional</h5>
                            <p class="text-muted mb-1">Senin - Jumat: 08:00 - 17:00</p>
                            <p class="text-muted mb-1">Sabtu: 08:00 - 15:00</p>
                            <p class="text-muted">Minggu: Tutup</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h3 class="text-success mb-4">
                        <i class="fas fa-paper-plane me-2"></i>Kirim Pesan
                    </h3>

                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <form action="{{ route('contact.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       required>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="phone" class="form-label">No. Telepon</label>
                                <input type="tel" 
                                       class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone') }}">
                                @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="subject" class="form-label">Subjek <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('subject') is-invalid @enderror" 
                                       id="subject" 
                                       name="subject" 
                                       value="{{ old('subject') }}" 
                                       required>
                                @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12">
                                <label for="message" class="form-label">Pesan <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('message') is-invalid @enderror" 
                                          id="message" 
                                          name="message" 
                                          rows="5" 
                                          required>{{ old('message') }}</textarea>
                                @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12">
                                <button type="submit" class="btn btn-success btn-lg w-100">
                                    <i class="fas fa-paper-plane me-2"></i>Kirim Pesan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Map Section (Optional) -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-map me-2"></i>Lokasi Kami</h5>
                </div>
                <div class="card-body p-0">
                    <div class="ratio ratio-21x9">
                    <iframe src="https://maps.google.com/maps?q=-7.894878,113.909934&z=17&output=embed&hl=id" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection