@extends('layouts.app')

@section('title', 'Pesanan Saya - Bibit Cabai')

@section('styles')
<style>
    body { background: #f8f9fa; font-family: 'Segoe UI', sans-serif; }

    .page-hero {
        background: linear-gradient(135deg, #28a745, #20c997);
        padding: 35px 0;
        color: white;
    }

    .nav-profile .nav-link {
        color: #6c757d; border-radius: 8px;
        padding: 10px 16px; font-weight: 500;
    }

    .nav-profile .nav-link.active,
    .nav-profile .nav-link:hover {
        background: #e8f5e9; color: #28a745;
    }

    .nav-profile .nav-link i { width: 20px; }

    .profile-card {
        background: white; border-radius: 16px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.07);
        padding: 25px;
    }

    .filter-tabs .nav-link {
        color: #6c757d; border-radius: 20px;
        padding: 6px 16px; font-size: 0.85rem; font-weight: 500;
    }

    .filter-tabs .nav-link.active { background: #28a745; color: white; }
    .filter-tabs .nav-link:hover:not(.active) { background: #f0f0f0; }

    .order-card {
        border: 1px solid #e9ecef; border-radius: 12px;
        padding: 20px; margin-bottom: 16px;
        transition: box-shadow 0.2s;
    }

    .order-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.08); }

    /* ── Pesanan pending/processing punya border merah tipis kalau bisa dibatalkan ── */
    .order-card.cancellable {
        border-color: #f5c6cb;
    }

    .order-status {
        padding: 4px 12px; border-radius: 20px;
        font-size: 0.75rem; font-weight: 600;
    }

    .status-pending    { background: #fff3cd; color: #856404; }
    .status-processing { background: #cce5ff; color: #004085; }
    .status-shipped    { background: #d1ecf1; color: #0c5460; }
    .status-delivered  { background: #d4edda; color: #155724; }
    .status-cancelled  { background: #f8d7da; color: #721c24; }

    .payment-paid    { background: #d4edda; color: #155724; }
    .payment-pending { background: #fff3cd; color: #856404; }

    /* Badge pengajuan pembatalan sedang pending */
    .cancel-badge {
        background: #fff3cd; color: #856404;
        border: 1px dashed #ffc107;
        padding: 3px 10px; border-radius: 20px;
        font-size: 0.72rem; font-weight: 600;
    }

    .product-img {
        width: 55px; height: 55px; border-radius: 8px;
        object-fit: cover; border: 1px solid #e9ecef;
    }

    .product-img-placeholder {
        width: 55px; height: 55px; border-radius: 8px;
        background: #f0f0f0; display: flex;
        align-items: center; justify-content: center;
        color: #aaa; font-size: 1.2rem;
    }

    .stat-mini {
        text-align: center; padding: 15px;
        border-radius: 10px; background: #f8f9fa;
    }

    .stat-mini .num { font-size: 1.5rem; font-weight: 700; color: #28a745; }
    .stat-mini .lbl { font-size: 0.75rem; color: #6c757d; }

    .invoice-num { font-size: 0.8rem; color: #6c757d; font-family: monospace; }

    /* Tombol batalkan */
    .btn-cancel-order {
        font-size: 0.8rem; padding: 5px 14px;
        border-radius: 20px; border: 1.5px solid #dc3545;
        color: #dc3545; background: transparent;
        font-weight: 600; transition: all 0.2s; text-decoration: none;
        display: inline-flex; align-items: center; gap: 5px;
    }

    .btn-cancel-order:hover {
        background: #dc3545; color: white;
        text-decoration: none;
    }
</style>
@endsection

@section('content')

<div class="page-hero">
    <div class="container">
        <h4 class="mb-0"><i class="fas fa-shopping-bag me-2"></i>Pesanan Saya</h4>
        <small>Pantau status semua pesanan kamu di sini</small>
    </div>
</div>

<div class="container py-4">
    <div class="row g-4">

        {{-- Sidebar --}}
        <div class="col-md-3">
            <div class="profile-card p-0 overflow-hidden mb-3">
                <div class="p-3 border-bottom">
                    <small class="text-muted text-uppercase fw-bold" style="font-size:0.7rem;">Menu</small>
                </div>
                <nav class="nav flex-column nav-profile p-2">
                    <a href="{{ route('profile') }}" class="nav-link">
                        <i class="fas fa-user me-2"></i>Profil Saya
                    </a>
                    <a href="{{ route('orders.my') }}" class="nav-link active">
                        <i class="fas fa-shopping-bag me-2"></i>Pesanan Saya
                    </a>
                    <hr class="my-2">
                    <a href="{{ route('home') }}" class="nav-link text-muted">
                        <i class="fas fa-home me-2"></i>Kembali ke Beranda
                    </a>
                </nav>
            </div>

            {{-- Statistik Mini --}}
            <div class="profile-card">
                <small class="text-muted text-uppercase fw-bold d-block mb-3" style="font-size:0.7rem;">Ringkasan</small>
                <div class="row g-2">
                    <div class="col-6">
                        <div class="stat-mini">
                            <div class="num">{{ $totalOrders }}</div>
                            <div class="lbl">Total</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-mini">
                            <div class="num">{{ $pendingOrders }}</div>
                            <div class="lbl">Menunggu</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-mini">
                            <div class="num">{{ $shippedOrders }}</div>
                            <div class="lbl">Dikirim</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-mini">
                            <div class="num">{{ $doneOrders }}</div>
                            <div class="lbl">Selesai</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Konten Pesanan --}}
        <div class="col-md-9">
            <div class="profile-card">

                {{-- Flash Message --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" style="border-radius:10px;">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Filter Tabs --}}
                <div class="d-flex flex-wrap gap-2 mb-4">
                    <nav class="nav filter-tabs flex-wrap gap-1">
                        <a href="{{ route('orders.my') }}"
                           class="nav-link {{ $status === 'all' ? 'active' : '' }}">
                            Semua ({{ $totalOrders }})
                        </a>
                        <a href="{{ route('orders.my', ['status' => 'pending']) }}"
                           class="nav-link {{ $status === 'pending' ? 'active' : '' }}">
                            Menunggu ({{ $pendingOrders }})
                        </a>
                        <a href="{{ route('orders.my', ['status' => 'processing']) }}"
                           class="nav-link {{ $status === 'processing' ? 'active' : '' }}">
                            Diproses ({{ $processOrders }})
                        </a>
                        <a href="{{ route('orders.my', ['status' => 'shipped']) }}"
                           class="nav-link {{ $status === 'shipped' ? 'active' : '' }}">
                            Dikirim ({{ $shippedOrders }})
                        </a>
                        <a href="{{ route('orders.my', ['status' => 'delivered']) }}"
                           class="nav-link {{ $status === 'delivered' ? 'active' : '' }}">
                            Selesai ({{ $doneOrders }})
                        </a>
                        <a href="{{ route('orders.my', ['status' => 'cancelled']) }}"
                           class="nav-link {{ $status === 'cancelled' ? 'active' : '' }}">
                            Dibatalkan ({{ $cancelOrders }})
                        </a>
                    </nav>
                </div>

                {{-- Daftar Pesanan --}}
                @forelse($orders as $order)

               @php
                $isCancellable = $order->canBeCancelled();
                $hasPending    = $order->cancellation && $order->cancellation->status === 'pending';
                @endphp

                <div class="order-card {{ $isCancellable ? 'cancellable' : '' }}">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <div class="invoice-num mb-1">
                                <i class="fas fa-receipt me-1"></i>{{ $order->invoice_number }}
                            </div>
                            <small class="text-muted">{{ $order->created_at->format('d M Y, H:i') }}</small>
                        </div>
                        <div class="d-flex gap-2 flex-wrap justify-content-end align-items-center">
                            {{-- Badge pengajuan sedang menunggu --}}
                            @if($hasPending)
                                <span class="cancel-badge">
                                    <i class="fas fa-clock me-1"></i>Menunggu Konfirmasi Batal
                                </span>
                            @endif

                            <span class="order-status status-{{ $order->order_status }}">
                                @switch($order->order_status)
                                    @case('pending') <i class="fas fa-clock me-1"></i>Menunggu @break
                                    @case('processing') <i class="fas fa-cog me-1"></i>Diproses @break
                                    @case('shipped') <i class="fas fa-truck me-1"></i>Dikirim @break
                                    @case('delivered') <i class="fas fa-check-circle me-1"></i>Selesai @break
                                    @case('cancelled') <i class="fas fa-times-circle me-1"></i>Dibatalkan @break
                                @endswitch
                            </span>
                            <span class="order-status {{ $order->payment_status === 'paid' ? 'payment-paid' : 'payment-pending' }}">
                                @if($order->payment_status === 'paid')
                                    <i class="fas fa-check me-1"></i>Lunas
                                @else
                                    <i class="fas fa-hourglass me-1"></i>Belum Bayar
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="mb-3">
                        @foreach($order->details->take(2) as $detail)
                        <div class="d-flex align-items-center gap-3 mb-2">
                            @if($detail->product && $detail->product->image)
                                <img src="{{ asset('storage/' . $detail->product->image) }}"
                                     class="product-img" alt="{{ $detail->product_name }}">
                            @else
                                <div class="product-img-placeholder">
                                    <i class="fas fa-seedling"></i>
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <div class="fw-bold" style="font-size:0.9rem;">{{ $detail->product_name }}</div>
                                <small class="text-muted">{{ $detail->quantity }} x Rp{{ number_format($detail->price, 0, ',', '.') }}</small>
                            </div>
                            <div class="text-end">
                                <small class="fw-bold">Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</small>
                            </div>
                        </div>
                        @endforeach

                        @if($order->details->count() > 2)
                            <small class="text-muted">
                                <i class="fas fa-plus me-1"></i>{{ $order->details->count() - 2 }} produk lainnya
                            </small>
                        @endif
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <div>
                            <small class="text-muted">Total Pembayaran</small>
                            <div class="fw-bold text-success" style="font-size:1.1rem;">
                                Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="d-flex gap-2 align-items-center">
                            {{-- Tombol Batalkan Pesanan --}}
                            @if($isCancellable)
                                <a href="{{ route('orders.cancel', $order->id) }}" class="btn-cancel-order">
                                    <i class="fas fa-times-circle"></i>Batalkan
                                </a>
                            @endif

                            <a href="{{ route('orders.detail', $order->id) }}"
                               class="btn btn-outline-success btn-sm">
                                <i class="fas fa-eye me-1"></i>Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>

                @empty
                <div class="text-center py-5">
                    <i class="fas fa-shopping-bag fa-3x text-muted mb-3 d-block"></i>
                    <h6 class="text-muted">Belum ada pesanan</h6>
                    <a href="{{ route('home') }}" class="btn btn-success mt-2">
                        <i class="fas fa-seedling me-2"></i>Mulai Belanja
                    </a>
                </div>
                @endforelse

                @if($orders->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $orders->appends(['status' => $status])->links() }}
                </div>
                @endif

            </div>
        </div>
    </div>
</div>

@endsection