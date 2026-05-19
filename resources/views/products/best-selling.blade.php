@extends('layouts.app')

@section('title', 'Produk Terlaris - Shop Bibit Cabai Bondowoso')

@section('content')
<div class="container py-5">
    <!-- Header Section -->
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold text-success">
            <i class="fas fa-fire text-warning me-3"></i>BIBIT TERLARIS
        </h1>
        <p class="lead text-muted">Bibit cabai paling diminati petani di Bondowoso</p>
    </div>

    <!-- Statistics Cards -->
    @if(isset($statistics) && $statistics['total_products_sold'] > 0)
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="stats-card text-center">
                <i class="fas fa-seedling fa-2x mb-3"></i>
                <h3>{{ number_format($statistics['total_products_sold']) }}</h3>
                <p class="mb-0">Total Bibit Terjual</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card text-center">
                <i class="fas fa-shopping-bag fa-2x mb-3"></i>
                <h3>{{ number_format($statistics['total_completed_orders']) }}</h3>
                <p class="mb-0">Pesanan Selesai</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card text-center">
                <i class="fas fa-crown fa-2x mb-3"></i>
                <h3>{{ $statistics['most_popular_product']->name ?? 'Belum Ada' }}</h3>
                <p class="mb-0">Produk Terpopuler</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Products Grid -->
    @if($bestSellingProducts->count() > 0)
    <div class="row g-4">
        @foreach($bestSellingProducts as $index => $product)
        <div class="col-lg-4 col-md-6">
            <div class="card product-card h-100 shadow-sm">
                <!-- Ranking Badge untuk Top 3 -->
                @if($index < 3)
                <div class="position-absolute top-0 start-0 m-2" style="z-index: 10;">
                    <span class="badge badge-ranking">
                        <i class="fas fa-trophy me-1"></i>#{{ $index + 1 }}
                    </span>
                </div>
                @endif
                
                <!-- Badge Terjual -->
                <div class="position-absolute top-0 end-0 m-2" style="z-index: 10;">
                    <span class="badge bg-danger">
                        <i class="fas fa-fire"></i> {{ number_format($product->total_sold) }} terjual
                    </span>
                </div>
                
                <!-- Product Image -->
                <img src="{{ asset('storage/' . $product->image) }}" 
                     class="card-img-top" 
                     alt="{{ $product->name }}"
                     style="height: 250px; object-fit: cover;">
                
                <div class="card-body d-flex flex-column">
                    <!-- Product Name -->
                    <h5 class="card-title mb-2">{{ $product->name }}</h5>
                    
                    <!-- Price -->
                    <div class="price-tag mb-3">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </div>
                    
                    <!-- Stock Info -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <small class="text-muted">
                            <i class="fas fa-boxes me-1"></i>Stok: {{ number_format($product->stock) }}
                        </small>
                        <span class="badge {{ $product->stock > 0 ? 'bg-success' : 'bg-danger' }}">
                            {{ $product->stock > 0 ? 'Tersedia' : 'Habis' }}
                        </span>
                    </div>
                    
                    <!-- Sales Statistics -->
                    <div class="sales-info mb-3 p-2 bg-light rounded">
                        <small class="d-flex justify-content-between">
                            <span><i class="fas fa-chart-line me-1 text-success"></i>{{ number_format($product->total_sold) }} terjual</span>
                            <span><i class="fas fa-users me-1 text-primary"></i>{{ number_format($product->total_orders) }} pembeli</span>
                        </small>
                    </div>
                    
                    <!-- Quantity Form -->
                    <div class="mt-auto">
                    @if($product->stock > 0)
                        <div class="input-group mb-3">
                            <button class="btn btn-outline-success" 
                                    type="button" 
                                    onclick="decreaseQty({{ $product->id }})">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" 
                                class="form-control quantity-input text-center" 
                                id="qty-{{ $product->id }}" 
                                value="1" 
                                min="1" 
                                max="{{ $product->stock }}">
                            <button class="btn btn-outline-success" 
                                    type="button" 
                                    onclick="increaseQty({{ $product->id }}, {{ $product->stock }})">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        
                        <button class="btn btn-success w-100" 
                                onclick="checkout({{ $product->id }})">
                            <i class="fas fa-shopping-cart me-1"></i>
                            Beli Sekarang
                        </button>
                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-outline-success w-100 mt-2">
                        <i class="fas fa-eye me-1"></i>
                        Lihat Detail Produk
                    </a>
                    @else
                        <button class="btn btn-secondary w-100" disabled>
                            <i class="fas fa-ban me-1"></i>
                            Stok Habis
                        </button>
                    @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <!-- No Data State -->
    <div class="text-center py-5">
        <div class="mb-4">
            <i class="fas fa-chart-line fa-5x text-muted"></i>
        </div>
        <h3 class="text-muted mb-3">Belum Ada Produk Terlaris</h3>
        <p class="text-muted mb-4">
            Produk terlaris akan muncul setelah ada transaksi yang selesai.<br>
            Saat ini belum ada produk yang terjual.
        </p>
        <a href="{{ route('home') }}" class="btn btn-success">
            <i class="fas fa-home me-2"></i>Kembali ke Beranda
        </a>
    </div>
    @endif
</div>

{{-- Script dipindah ke dalam @section('content') agar terbaca oleh browser --}}
<script>
const isLoggedIn = @json(auth()->check());
const loginUrl = "{{ route('login') }}";

function increaseQty(productId, maxStock) {
    const input = document.getElementById('qty-' + productId);
    let value = parseInt(input.value) || 1;
    if (value < maxStock) {
        input.value = value + 1;
    } else {
        alert('Stok maksimal: ' + maxStock);
    }
}

function decreaseQty(productId) {
    const input = document.getElementById('qty-' + productId);
    let value = parseInt(input.value) || 1;
    if (value > 1) {
        input.value = value - 1;
    }
}

function checkout(productId) {
    if (!isLoggedIn) {
        window.location.href = loginUrl;
        return;
    }

    const input = document.getElementById('qty-' + productId);
    if (!input) {
        alert('Terjadi kesalahan, coba refresh halaman!');
        return;
    }

    const quantity = parseInt(input.value) || 1;
    const max = parseInt(input.getAttribute('max'));

    if (isNaN(quantity) || quantity < 1) {
        alert('Jumlah minimal 1!');
        return;
    }

    if (quantity > max) {
        alert('Jumlah melebihi stok tersedia (' + max + ')!');
        return;
    }

    window.location.href = '/checkout?product_id=' + productId + '&quantity=' + quantity;
}
</script>

@endsection

@push('styles')
<style>
.product-card {
    border: none;
    border-radius: 15px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.product-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
}

.badge-ranking {
    background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
    color: #000;
    font-weight: bold;
    padding: 8px 12px;
    border-radius: 10px;
    font-size: 0.85rem;
}

.price-tag {
    font-size: 1.5rem;
    font-weight: bold;
    color: #28a745;
}

.stats-card {
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 15px rgba(0,0,0,0.15);
}

.stats-card i {
    color: #28a745;
}

.stats-card h3 {
    color: #28a745;
    font-weight: bold;
    margin: 10px 0;
    font-size: 2rem;
}

.stats-card p {
    color: #6c757d;
    font-size: 0.95rem;
}

.sales-info {
    border-left: 3px solid #28a745;
}

.quantity-input {
    width: 80px;
    text-align: center;
}

.quantity-input::-webkit-outer-spin-button,
.quantity-input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.quantity-input[type=number] {
    -moz-appearance: textfield;
}

@media (max-width: 768px) {
    .display-4 {
        font-size: 2rem;
    }
    
    .stats-card h3 {
        font-size: 1.5rem;
    }
    
    .price-tag {
        font-size: 1.2rem;
    }
}
</style>
@endpush