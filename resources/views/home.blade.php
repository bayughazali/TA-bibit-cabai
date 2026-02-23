@extends('layouts.app')

@section('title', 'Home - Shop Bibit Cabai Bondowoso')

@section('styles')
<style>
    .product-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 25px rgba(0,0,0,0.15);
    }
    
    .product-image {
        height: 250px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .product-card:hover .product-image {
        transform: scale(1.05);
    }
    
    .badge-bestseller {
        position: absolute;
        top: 10px;
        right: 10px;
        background: linear-gradient(45deg, #ff6b6b, #ee5a24);
        color: white;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: bold;
        z-index: 2;
        box-shadow: 0 2px 10px rgba(238, 90, 36, 0.3);
    }
    
    .price-text {
        font-size: 1.3rem;
        font-weight: bold;
        color: #28a745;
    }
    
    .hero-image {
        max-height: 400px;
        object-fit: cover;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    
    .quantity-input {
        width: 80px;
        text-align: center;
    }
</style>
@endsection

@section('content')
<div class="container-fluid p-0">
    <!-- Hero Section -->
    <section class="bg-success text-white py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">Bibit Cabai Berkualitas</h1>
                    <p class="lead mb-4">Dapatkan bibit cabai terbaik dari Bondowoso untuk hasil panen yang optimal.</p>
                    <a href="{{ route('products.best-selling') }}" class="btn btn-light btn-lg">
                        <i class="fas fa-fire me-2"></i>Lihat Produk Terlaris
                    </a>
                </div>
                <div class="col-lg-6 text-center">
                    <img src="{{ asset('images/cabai-hero.jpg') }}" 
                         alt="Bibit Cabai" 
                         class="img-fluid hero-image"
                         onerror="this.src='https://via.placeholder.com/400x300/28a745/ffffff?text=Bibit+Cabai+Hero'">
                </div>
            </div>
        </div>
    </section>

    <!-- Produk Terlaris Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold text-success">BIBIT TERLARIS</h2>
                <p class="lead text-muted">Produk pilihan petani Bondowoso</p>
            </div>

            @if($featuredProducts->count() > 0)
            <div class="row g-4">
                @foreach($featuredProducts as $product)
                <div class="col-lg-4 col-md-6">
                    <div class="card product-card h-100 position-relative">
                        @if($product->total_sold > 0)
                        <div class="badge-bestseller">
                            <i class="fas fa-fire"></i> Terlaris
                        </div>
                        @endif
                        
                        <div class="overflow-hidden">
                            <img src="{{ $product->image_url }}" 
                                 class="card-img-top product-image" 
                                 alt="{{ $product->name }}"
                                 loading="lazy"
                                 onerror="this.src='https://via.placeholder.com/300x250/28a745/ffffff?text={{ urlencode($product->name) }}'">
                        </div>
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <div class="price-text mb-3">{{ $product->formatted_price }}</div>
                            
                            @if($product->total_sold > 0)
                            <small class="text-muted mb-3">
                                <i class="fas fa-chart-line me-1"></i>
                                Terjual: {{ number_format($product->total_sold) }} bibit
                            </small>
                            @endif
                            
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <small class="text-muted">
                                        <i class="fas fa-boxes me-1"></i>
                                        Stok: {{ number_format($product->stock) }}
                                    </small>
                                    @if($product->stock > 0)
                                        <span class="badge bg-success">Tersedia</span>
                                    @else
                                        <span class="badge bg-danger">Habis</span>
                                    @endif
                                </div>
                                
                                @if($product->stock > 0)
                                    <div class="input-group mb-3">
                                        <button class="btn btn-outline-success" type="button" onclick="decreaseQty({{ $product->id }})">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <input type="number" class="form-control quantity-input" id="qty-{{ $product->id }}" value="1" min="1" max="{{ $product->stock }}">
                                        <button class="btn btn-outline-success" type="button" onclick="increaseQty({{ $product->id }}, {{ $product->stock }})">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                    
                                    <button class="btn btn-success w-100" onclick="checkout({{ $product->id }})">
                                        <i class="fas fa-shopping-cart me-1"></i>
                                        Beli Sekarang
                                    </button>
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

            <div class="text-center mt-5">
                <a href="{{ route('products.best-selling') }}" class="btn btn-outline-success btn-lg">
                    Lihat Semua Produk Terlaris <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
            @else
            <div class="text-center">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Belum ada data penjualan. Produk terlaris akan muncul setelah ada transaksi.
                </div>
                
                <!-- Sample Products Placeholder -->
                <div class="row g-4 mt-4">
                    <div class="col-lg-4 col-md-6">
                        <div class="card product-card h-100">
                            <img src="https://via.placeholder.com/300x250/28a745/ffffff?text=Bibit+Cabai+Merah" 
                                 class="card-img-top product-image" 
                                 alt="Sample Product">
                            <div class="card-body">
                                <h5 class="card-title">Bibit Cabai Merah (Sample)</h5>
                                <div class="price-text mb-3">Rp 15.000</div>
                                <button class="btn btn-secondary w-100" disabled>
                                    <i class="fas fa-shopping-cart me-1"></i>Sample Product
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="text-center">
                        <div class="bg-success text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="fas fa-seedling fa-2x"></i>
                        </div>
                        <h5>Bibit Berkualitas</h5>
                        <p class="text-muted">Bibit pilihan dengan kualitas terjamin dan hasil optimal.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="text-center">
                        <div class="bg-success text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="fas fa-truck fa-2x"></i>
                        </div>
                        <h5>Pengiriman Cepat</h5>
                        <p class="text-muted">Pengiriman ke seluruh Indonesia dengan packaging aman.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="text-center">
                        <div class="bg-success text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="fas fa-medal fa-2x"></i>
                        </div>
                        <h5>Garansi Tumbuh</h5>
                        <p class="text-muted">Garansi bibit tumbuh atau uang kembali 100%.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="text-center">
                        <div class="bg-success text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="fas fa-headset fa-2x"></i>
                        </div>
                        <h5>Support 24/7</h5>
                        <p class="text-muted">Tim support siap membantu Anda kapan saja.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
function increaseQty(productId, maxStock) {
    const input = document.getElementById('qty-' + productId);
    let value = parseInt(input.value);
    if (value < maxStock) {
        input.value = value + 1;
    }
}

function decreaseQty(productId) {
    const input = document.getElementById('qty-' + productId);
    let value = parseInt(input.value);
    if (value > 1) {
        input.value = value - 1;
    }
}

function checkout(productId) {
    const quantity = document.getElementById('qty-' + productId).value;
    window.location.href = `/checkout?product_id=${productId}&quantity=${quantity}`;
}
</script>
@endsection