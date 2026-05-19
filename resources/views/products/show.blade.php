@extends('layouts.app')

@section('title', $product->name . ' - Shop Bibit Cabai Bondowoso')

@section('styles')
<style>
    .product-detail-image {
        width: 100%;
        max-height: 450px;
        object-fit: cover;
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    .price-text {
        font-size: 2rem;
        font-weight: bold;
        color: #28a745;
    }
    .info-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.08);
    }
    .quantity-input {
        width: 80px;
        text-align: center;
    }
    .badge-stock {
        font-size: 0.9rem;
        padding: 6px 14px;
        border-radius: 20px;
    }
</style>
@endsection

@section('content')
<div class="container py-5">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-success">Home</a></li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row g-5">
        {{-- Gambar Produk --}}
        <div class="col-lg-6">
            <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/500x450/28a745/ffffff?text=' . urlencode($product->name) }}"
                 alt="{{ $product->name }}"
                 class="product-detail-image"
                 onerror="this.src='https://via.placeholder.com/500x450/28a745/ffffff?text={{ urlencode($product->name) }}'">
        </div>

        {{-- Info Produk --}}
        <div class="col-lg-6">
            <h1 class="fw-bold mb-2">{{ $product->name }}</h1>

            @if($product->category)
            <span class="badge bg-success-subtle text-success border border-success mb-3">
                {{ $product->category }}
            </span>
            @endif

            <div class="price-text mb-3">
                Rp {{ number_format($product->price, 0, ',', '.') }}
            </div>

            {{-- Status Stok --}}
            <div class="d-flex align-items-center gap-3 mb-4">
                <span class="text-muted">
                    <i class="fas fa-boxes me-1"></i>Stok: <strong>{{ number_format($product->stock) }}</strong>
                </span>
                @if($product->stock > 0)
                    <span class="badge bg-success badge-stock">Tersedia</span>
                @else
                    <span class="badge bg-danger badge-stock">Stok Habis</span>
                @endif
            </div>

            {{-- Total Terjual --}}
            @if(isset($product->sold) && $product->sold > 0)
            <p class="text-muted mb-4">
                <i class="fas fa-chart-line me-1 text-success"></i>
                Terjual: <strong>{{ number_format($product->sold) }} bibit</strong>
            </p>
            @endif

            {{-- Aksi Beli --}}
            @if($product->stock > 0)
            <div class="card info-card p-4 mb-4">
                <label class="form-label fw-semibold">Jumlah</label>
                <div class="input-group mb-3" style="max-width: 200px;">
                    <button class="btn btn-outline-success" type="button" onclick="decreaseQty()">
                        <i class="fas fa-minus"></i>
                    </button>
                    <input type="number" class="form-control quantity-input" id="qty-detail"
                           value="1" min="1" max="{{ $product->stock }}">
                    <button class="btn btn-outline-success" type="button" onclick="increaseQty({{ $product->stock }})">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                <button class="btn btn-success btn-lg w-100" onclick="checkout({{ $product->id }})">
                    <i class="fas fa-shopping-cart me-2"></i>Beli Sekarang
                </button>
            </div>
            @else
            <button class="btn btn-secondary btn-lg w-100 mb-4" disabled>
                <i class="fas fa-ban me-2"></i>Stok Habis
            </button>
            @endif

            {{-- Deskripsi --}}
            @if($product->description)
            <div class="card info-card p-4">
                <h5 class="fw-bold mb-3"><i class="fas fa-info-circle me-2 text-success"></i>Deskripsi Produk</h5>
                <p class="text-muted mb-0" style="line-height: 1.8;">{{ $product->description }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Tombol Kembali --}}
    <div class="mt-5">
        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali ke Beranda
        </a>
    </div>

</div>

<script>
const isLoggedIn = @json(auth()->check());
const loginUrl = "{{ route('login') }}";

function increaseQty(maxStock) {
    const input = document.getElementById('qty-detail');
    if (parseInt(input.value) < maxStock) input.value = parseInt(input.value) + 1;
}

function decreaseQty() {
    const input = document.getElementById('qty-detail');
    if (parseInt(input.value) > 1) input.value = parseInt(input.value) - 1;
}

function checkout(productId) {
    if (!isLoggedIn) {
        window.location.href = loginUrl;
        return;
    }
    const quantity = document.getElementById('qty-detail').value;
    window.location.href = `/checkout?product_id=${productId}&quantity=${quantity}`;
}
</script>
@endsection