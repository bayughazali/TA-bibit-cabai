@extends('layouts.app')

@section('title', 'Detail Pesanan - Bibit Cabai')

@section('styles')
<style>
    body { background: #f8f9fa; font-family: 'Segoe UI', sans-serif; }

    .page-hero {
        background: linear-gradient(135deg, #28a745, #20c997);
        padding: 35px 0; color: white;
    }

    .detail-card {
        background: white; border-radius: 16px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.07);
        padding: 25px; margin-bottom: 20px;
    }

    .section-title {
        font-size: 1rem; font-weight: 700; color: #333;
        margin-bottom: 18px; padding-bottom: 10px;
        border-bottom: 2px solid #f0f0f0;
    }

    .order-status {
        padding: 6px 16px; border-radius: 20px;
        font-size: 0.85rem; font-weight: 600; display: inline-block;
    }

    .status-pending    { background: #fff3cd; color: #856404; }
    .status-processing { background: #cce5ff; color: #004085; }
    .status-shipped    { background: #d1ecf1; color: #0c5460; }
    .status-delivered  { background: #d4edda; color: #155724; }
    .status-cancelled  { background: #f8d7da; color: #721c24; }
    .payment-paid      { background: #d4edda; color: #155724; }
    .payment-pending   { background: #fff3cd; color: #856404; }

    .info-row { display: flex; padding: 10px 0; border-bottom: 1px solid #f8f9fa; }
    .info-row:last-child { border-bottom: none; }
    .info-label { width: 160px; color: #6c757d; font-size: 0.9rem; flex-shrink: 0; }
    .info-value { flex: 1; font-weight: 500; color: #333; }

    .product-img {
        width: 60px; height: 60px; border-radius: 10px;
        object-fit: cover; border: 1px solid #e9ecef;
    }

    .product-img-placeholder {
        width: 60px; height: 60px; border-radius: 10px;
        background: #f0f0f0; display: flex;
        align-items: center; justify-content: center;
        color: #aaa; font-size: 1.3rem;
    }

    .timeline { position: relative; padding-left: 25px; }
    .timeline::before {
        content: ''; position: absolute; left: 8px; top: 0; bottom: 0;
        width: 2px; background: #e9ecef;
    }

    .timeline-item { position: relative; margin-bottom: 20px; }
    .timeline-item::before {
        content: ''; position: absolute; left: -21px; top: 4px;
        width: 12px; height: 12px; border-radius: 50%;
        background: #dee2e6; border: 2px solid white;
    }

    .timeline-item.done::before { background: #28a745; }
    .timeline-item.cancelled::before { background: #dc3545; }

    .total-row {
        display: flex; justify-content: space-between;
        padding: 8px 0; font-size: 0.9rem;
    }

    .total-row.grand {
        font-size: 1.1rem; font-weight: 700;
        color: #28a745; border-top: 2px solid #f0f0f0;
        padding-top: 12px; margin-top: 4px;
    }

    /* ── Card pembatalan ── */
    .cancel-action-card {
        border-radius: 14px; padding: 20px;
        border: 1.5px dashed #f5c6cb;
        background: #fff5f5; margin-bottom: 20px;
    }

    .cancel-status-card {
        border-radius: 14px; padding: 20px;
        margin-bottom: 20px;
    }

    .cancel-status-card.pending  { background: #fffbf0; border: 1.5px solid #ffc107; }
    .cancel-status-card.approved { background: #fff5f5; border: 1.5px solid #dc3545; }
    .cancel-status-card.rejected { background: #f0fff4; border: 1.5px solid #28a745; }

    .cancel-status-badge {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 5px 14px; border-radius: 20px;
        font-size: 0.78rem; font-weight: 700;
        margin-bottom: 12px;
    }

    .cancel-status-badge.pending  { background: #fff3cd; color: #856404; }
    .cancel-status-badge.approved { background: #f8d7da; color: #721c24; }
    .cancel-status-badge.rejected { background: #d4edda; color: #155724; }

    .cancel-detail-row {
        display: flex; gap: 10px; font-size: 0.87rem;
        padding: 6px 0; border-bottom: 1px solid rgba(0,0,0,0.05);
    }

    .cancel-detail-row:last-child { border-bottom: none; }
    .cancel-detail-label { width: 120px; color: #6c757d; flex-shrink: 0; }
    .cancel-detail-value { flex: 1; color: #333; font-weight: 500; }

    .admin-note-box {
        border-radius: 8px; padding: 10px 14px;
        font-size: 0.85rem; margin-top: 8px;
    }

    .admin-note-box.approved { background: #fff0f0; border-left: 3px solid #dc3545; }
    .admin-note-box.rejected { background: #f0fff4; border-left: 3px solid #28a745; }

    /* Tombol Batalkan */
    .btn-batalkan {
        display: inline-flex; align-items: center; gap: 8px;
        background: #dc3545; color: white;
        border: none; padding: 10px 22px; border-radius: 10px;
        font-weight: 600; font-size: 0.9rem;
        transition: all 0.2s; text-decoration: none; cursor: pointer;
    }

    .btn-batalkan:hover {
        background: #c82333; color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(220,53,69,0.3);
        text-decoration: none;
    }
</style>
@endsection

@section('content')

<div class="page-hero">
    <div class="container d-flex align-items-center gap-3">
        <a href="{{ route('orders.my') }}" class="btn btn-outline-light btn-sm">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h5 class="mb-0">Detail Pesanan</h5>
            <small class="font-monospace">{{ $order->invoice_number }}</small>
        </div>
    </div>
</div>

<div class="container py-4">

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" style="border-radius:12px;">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" style="border-radius:12px;">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">

        {{-- Kolom Kiri --}}
        <div class="col-md-8">

            {{-- Status Pesanan --}}
            <div class="detail-card">
                <div class="section-title"><i class="fas fa-info-circle me-2 text-success"></i>Status Pesanan</div>

                <div class="d-flex gap-3 mb-4 flex-wrap">
                    <div>
                        <small class="text-muted d-block">Status Pesanan</small>
                        <span class="order-status status-{{ $order->order_status }}">
                            @switch($order->order_status)
                                @case('pending') <i class="fas fa-clock me-1"></i>Menunggu Konfirmasi @break
                                @case('processing') <i class="fas fa-cog me-1"></i>Sedang Diproses @break
                                @case('shipped') <i class="fas fa-truck me-1"></i>Sedang Dikirim @break
                                @case('delivered') <i class="fas fa-check-circle me-1"></i>Pesanan Selesai @break
                                @case('cancelled') <i class="fas fa-times-circle me-1"></i>Dibatalkan @break
                            @endswitch
                        </span>
                    </div>
                    <div>
                        <small class="text-muted d-block">Status Pembayaran</small>
                        <span class="order-status {{ $order->payment_status === 'paid' ? 'payment-paid' : 'payment-pending' }}">
                            @if($order->payment_status === 'paid')
                                <i class="fas fa-check me-1"></i>Lunas
                            @else
                                <i class="fas fa-hourglass me-1"></i>Belum Dibayar
                            @endif
                        </span>
                    </div>
                </div>

                @php
                    $statuses   = ['pending', 'processing', 'shipped', 'delivered'];
                    $currentIdx = array_search($order->order_status, $statuses);
                    $isCancelled = $order->order_status === 'cancelled';
                @endphp

                <div class="timeline">
                    @if($isCancelled)
                        <div class="timeline-item done">
                            <strong>Pesanan Diterima</strong>
                            <div class="text-muted" style="font-size:0.82rem;">{{ $order->created_at->format('d M Y, H:i') }}</div>
                        </div>
                        <div class="timeline-item cancelled">
                            <strong class="text-danger">Pesanan Dibatalkan</strong>
                            @if($order->cancellation && $order->cancellation->reviewed_at)
                                <div class="text-muted" style="font-size:0.82rem;">{{ $order->cancellation->reviewed_at->format('d M Y, H:i') }}</div>
                            @endif
                        </div>
                    @else
                        <div class="timeline-item {{ $currentIdx >= 0 ? 'done' : '' }}">
                            <strong>Pesanan Diterima</strong>
                            <div class="text-muted" style="font-size:0.82rem;">{{ $order->created_at->format('d M Y, H:i') }}</div>
                        </div>
                        <div class="timeline-item {{ $currentIdx >= 1 ? 'done' : '' }}">
                            <strong>Sedang Diproses</strong>
                            <div class="text-muted" style="font-size:0.82rem;">Admin sedang mempersiapkan pesanan</div>
                        </div>
                        <div class="timeline-item {{ $currentIdx >= 2 ? 'done' : '' }}">
                            <strong>Dikirim</strong>
                            <div class="text-muted" style="font-size:0.82rem;">Pesanan dalam perjalanan</div>
                        </div>
                        <div class="timeline-item {{ $currentIdx >= 3 ? 'done' : '' }}">
                            <strong>Selesai</strong>
                            <div class="text-muted" style="font-size:0.82rem;">Pesanan telah diterima</div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ── TOMBOL BATALKAN / STATUS PENGAJUAN ── --}}
            @php
                $isCancellable = in_array($order->order_status, ['pending','processing'])
                                 && !$order->cancellation;
                $cancellation  = $order->cancellation;
            @endphp

            {{-- A) Bisa dibatalkan → tampilkan tombol --}}
            @if($isCancellable)
            <div class="cancel-action-card">
                <div class="d-flex align-items-start gap-3">
                    <div style="font-size:1.6rem; line-height:1;">⚠️</div>
                    <div class="flex-grow-1">
                        <div class="fw-bold mb-1" style="font-size:0.95rem;">Ingin membatalkan pesanan ini?</div>
                        <p class="text-muted mb-3" style="font-size:0.85rem; line-height:1.5;">
                            Pengajuan pembatalan akan dikirim ke admin untuk ditinjau terlebih dahulu.
                            Pesanan <strong>tidak langsung dibatalkan</strong> sampai admin menyetujui.
                        </p>
                        <a href="{{ route('orders.cancel', $order->id) }}" class="btn-batalkan">
                            <i class="fas fa-times-circle"></i>Batalkan Pesanan
                        </a>
                    </div>
                </div>
            </div>
            @endif

            {{-- B) Sudah ada pengajuan → tampilkan status --}}
            @if($cancellation)
            <div class="cancel-status-card {{ $cancellation->status }}">
                <div class="cancel-status-badge {{ $cancellation->status }}">
                    @if($cancellation->status === 'pending')
                        <i class="fas fa-clock"></i> Pengajuan Pembatalan — Menunggu Konfirmasi Admin
                    @elseif($cancellation->status === 'approved')
                        <i class="fas fa-check-circle"></i> Pembatalan Disetujui
                    @else
                        <i class="fas fa-times-circle"></i> Pengajuan Pembatalan Ditolak
                    @endif
                </div>

                <div class="cancel-detail-row">
                    <span class="cancel-detail-label">Alasan</span>
                    <span class="cancel-detail-value">{{ $cancellation->reason }}</span>
                </div>
                @if($cancellation->description)
                <div class="cancel-detail-row">
                    <span class="cancel-detail-label">Keterangan</span>
                    <span class="cancel-detail-value">{{ $cancellation->description }}</span>
                </div>
                @endif
                <div class="cancel-detail-row">
                    <span class="cancel-detail-label">Diajukan</span>
                    <span class="cancel-detail-value">{{ $cancellation->created_at->format('d M Y, H:i') }}</span>
                </div>

                @if($cancellation->status === 'pending')
                    <p class="text-muted mt-2 mb-0" style="font-size:0.82rem;">
                        <i class="fas fa-info-circle me-1"></i>
                        Admin akan segera meninjau permintaanmu. Harap tunggu konfirmasi.
                    </p>
                @endif

                @if($cancellation->admin_note)
                <div class="admin-note-box {{ $cancellation->status }}">
                    <small class="text-muted d-block mb-1 fw-bold">Catatan dari Admin:</small>
                    {{ $cancellation->admin_note }}
                </div>
                @endif

                @if($cancellation->status === 'rejected')
                    <p class="text-muted mt-2 mb-0" style="font-size:0.82rem;">
                        <i class="fas fa-info-circle me-1"></i>
                        Pesanan tetap berjalan seperti biasa. Silakan tunggu pengiriman pesananmu.
                    </p>
                @endif
            </div>
            @endif

            {{-- Produk Dipesan --}}
            <div class="detail-card">
                <div class="section-title"><i class="fas fa-seedling me-2 text-success"></i>Produk Dipesan</div>

                @foreach($order->details as $detail)
                <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom">
                    @if($detail->product && $detail->product->image)
                        <img src="{{ asset('storage/' . $detail->product->image) }}"
                             class="product-img" alt="{{ $detail->product_name }}">
                    @else
                        <div class="product-img-placeholder">
                            <i class="fas fa-seedling"></i>
                        </div>
                    @endif
                    <div class="flex-grow-1">
                        <div class="fw-bold">{{ $detail->product_name }}</div>
                        <small class="text-muted">{{ $detail->quantity }} x Rp{{ number_format($detail->price, 0, ',', '.') }}</small>
                    </div>
                    <div class="text-end fw-bold">
                        Rp{{ number_format($detail->subtotal, 0, ',', '.') }}
                    </div>
                </div>
                @endforeach

                <div class="mt-3">
                    <div class="total-row">
                        <span class="text-muted">Subtotal</span>
                        <span>Rp{{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="total-row">
                        <span class="text-muted">Ongkos Kirim</span>
                        <span>Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    <div class="total-row grand">
                        <span>Total Pembayaran</span>
                        <span>Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

        </div>

        {{-- Kolom Kanan --}}
        <div class="col-md-4">

            <div class="detail-card">
                <div class="section-title"><i class="fas fa-map-marker-alt me-2 text-success"></i>Alamat Pengiriman</div>
                <div class="info-row">
                    <div class="info-label">Nama</div>
                    <div class="info-value">{{ $order->customer_name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Telepon</div>
                    <div class="info-value">{{ $order->customer_phone }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Alamat</div>
                    <div class="info-value">{{ $order->shipping_address }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Kecamatan</div>
                    <div class="info-value">{{ $order->city }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Provinsi</div>
                    <div class="info-value">{{ $order->province }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Kode Pos</div>
                    <div class="info-value">{{ $order->postal_code }}</div>
                </div>
            </div>

            <div class="detail-card">
                <div class="section-title"><i class="fas fa-credit-card me-2 text-success"></i>Info Pembayaran</div>
                <div class="info-row">
                    <div class="info-label">Metode</div>
                    <div class="info-value">
                        @switch($order->payment_method)
                            @case('transfer') Transfer Bank @break
                            @case('bri')      Transfer BRI @break
                            @case('qris')     QRIS @break
                            @case('cod')      COD (Bayar di Tempat) @break
                            @default {{ $order->payment_method }}
                        @endswitch
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Status</div>
                    <div class="info-value">
                        @if($order->payment_status === 'paid')
                            <span class="text-success fw-bold"><i class="fas fa-check me-1"></i>Lunas</span>
                        @else
                            <span class="text-warning fw-bold"><i class="fas fa-hourglass me-1"></i>Belum Dibayar</span>
                        @endif
                    </div>
                </div>
                @if($order->paid_at)
                <div class="info-row">
                    <div class="info-label">Dibayar</div>
                    <div class="info-value">{{ $order->paid_at->format('d M Y, H:i') }}</div>
                </div>
                @endif
                @if($order->notes)
                <div class="info-row">
                    <div class="info-label">Catatan</div>
                    <div class="info-value">{{ $order->notes }}</div>
                </div>
                @endif
            </div>

            <a href="{{ route('orders.my') }}" class="btn btn-outline-success w-100">
                <i class="fas fa-arrow-left me-2"></i>Kembali ke Pesanan
            </a>
        </div>

    </div>
</div>

@endsection