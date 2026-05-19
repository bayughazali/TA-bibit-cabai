@extends('layouts.app')

@section('title', 'Pesanan Berhasil - Shop Bibit Cabai Bondowoso')

@section('styles')
<style>
    .success-icon {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: linear-gradient(135deg, #28a745, #20c997);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 30px;
        animation: scaleIn 0.5s ease-out;
    }
    
    @keyframes scaleIn {
        0% {
            transform: scale(0);
            opacity: 0;
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }
    
    .success-card {
        border: none;
        box-shadow: 0 5px 25px rgba(0,0,0,0.1);
        border-radius: 15px;
    }
    
    .order-detail-row {
        border-bottom: 1px solid #e9ecef;
        padding: 12px 0;
    }
    
    .order-detail-row:last-child {
        border-bottom: none;
    }
    
    .bank-account {
        background: #f8f9fa;
        border: 2px dashed #28a745;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .copy-btn {
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .copy-btn:hover {
        transform: scale(1.1);
    }

 @media print {
    @page {
        size: A4;
        margin: 10mm;
    }

    body * {
        visibility: hidden;
        height: 0;
        overflow: hidden;
    }

    #print-area,
    #print-area * {
        visibility: visible;
        height: auto;
        overflow: visible;
    }

    #print-area {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        padding: 10px;
    }

    #print-area .card {
        box-shadow: none !important;
        border: 1px solid #dee2e6 !important;
        margin-top: 8px !important;
        border-radius: 8px !important;
    }

    #print-area .card-body {
        padding: 10px 14px !important;
    }

    #print-area h5 {
        font-size: 0.9rem !important;
        margin-bottom: 8px !important;
    }

    #print-area .order-detail-row {
        padding: 5px 0 !important;
    }

    #print-area p,
    #print-area span,
    #print-area td,
    #print-area small {
        font-size: 0.8rem !important;
    }

    #print-area img {
        width: 50px !important;
        height: 50px !important;
    }

    #print-area hr {
        margin: 5px 0 !important;
    }

    #print-area .mb-2,
    #print-area .mb-3 {
        margin-bottom: 4px !important;
    }
}
</style>
@endsection

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            {{-- Card Pesanan Berhasil (tidak ikut print) --}}
            <div class="card success-card">
                <div class="card-body p-5 text-center">
                    <div class="success-icon">
                        <i class="fas fa-check fa-3x text-white"></i>
                    </div>
                    <h2 class="fw-bold text-success mb-3">Pesanan Berhasil!</h2>
                    <p class="text-muted mb-4">Terima kasih atas pesanan Anda. Pesanan Anda sedang diproses.</p>
                    <div class="alert alert-success" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        Nomor Invoice: <strong>{{ $transaksi->invoice_number }}</strong>
                    </div>
                </div>
            </div>

            {{-- ===== MULAI AREA PRINT ===== --}}
            <div id="print-area">

                {{-- Card Detail Pesanan --}}
                <div class="card success-card mt-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">
                            <i class="fas fa-clipboard-list me-2 text-success"></i>Detail Pesanan
                        </h5>
                        <div class="order-detail-row">
                            <div class="row">
                                <div class="col-6"><strong>Invoice:</strong></div>
                                <div class="col-6 text-end">{{ $transaksi->invoice_number }}</div>
                            </div>
                        </div>
                        <div class="order-detail-row">
                            <div class="row">
                                <div class="col-6"><strong>Tanggal:</strong></div>
                                <div class="col-6 text-end">{{ $transaksi->created_at->format('d M Y H:i') }}</div>
                            </div>
                        </div>
                        <div class="order-detail-row">
                            <div class="row">
                                <div class="col-6"><strong>Status Pesanan:</strong></div>
                                <div class="col-6 text-end">
                                    <span class="badge bg-warning text-dark">Menunggu Pembayaran</span>
                                </div>
                            </div>
                        </div>
                        <div class="order-detail-row">
                            <div class="row">
                                <div class="col-6"><strong>Metode Pembayaran:</strong></div>
                                <div class="col-6 text-end">
                                    @if($transaksi->payment_method == 'qris')
                                        <i class="fas fa-qrcode me-1"></i>QRIS
                                    @elseif($transaksi->payment_method == 'bri')
                                        <i class="fas fa-university me-1"></i>Transfer Bank BRI
                                    @elseif($transaksi->payment_method == 'dana')
                                        <i class="fas fa-wallet me-1"></i>DANA
                                    @elseif($transaksi->payment_method == 'seabank')
                                        <i class="fas fa-university me-1"></i>SeaBank
                                    @elseif($transaksi->payment_method == 'shopee')
                                        <i class="fas fa-shopping-bag me-1"></i>ShopeePay
                                    @else
                                        <i class="fas fa-money-bill-wave me-1"></i>COD
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card Data Pembeli --}}
                <div class="card success-card mt-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">
                            <i class="fas fa-user me-2 text-success"></i>Data Pembeli
                        </h5>
                        <div class="order-detail-row">
                            <div class="row">
                                <div class="col-6"><strong>Nama:</strong></div>
                                <div class="col-6 text-end">{{ $transaksi->customer_name }}</div>
                            </div>
                        </div>
                        <div class="order-detail-row">
                            <div class="row">
                                <div class="col-6"><strong>Telepon:</strong></div>
                                <div class="col-6 text-end">{{ $transaksi->customer_phone }}</div>
                            </div>
                        </div>
                        <div class="order-detail-row">
                            <div class="row">
                                <div class="col-6"><strong>Email:</strong></div>
                                <div class="col-6 text-end">{{ $transaksi->customer_email }}</div>
                            </div>
                        </div>
                        <div class="order-detail-row">
                            <div class="row">
                                <div class="col-12"><strong>Alamat Pengiriman:</strong></div>
                                <div class="col-12 mt-2 text-muted">
                                    {{ $transaksi->shipping_address }}<br>
                                    {{ $transaksi->city }}, {{ $transaksi->province }} {{ $transaksi->postal_code }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card Produk yang Dipesan --}}
                <div class="card success-card mt-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">
                            <i class="fas fa-box me-2 text-success"></i>Produk yang Dipesan
                        </h5>
                        @foreach($transaksi->details as $detail)
                        <div class="d-flex align-items-center mb-3 p-3" style="background: #f8f9fa; border-radius: 10px;">
                            <img src="{{ $detail->product->image_url ?? 'https://via.placeholder.com/80x80/28a745/ffffff?text=Bibit' }}" 
                                 alt="{{ $detail->product_name }}" 
                                 class="rounded me-3"
                                 style="width: 80px; height: 80px; object-fit: cover;">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $detail->product_name }}</h6>
                                <p class="text-muted mb-0">{{ number_format($detail->quantity) }} bibit × Rp {{ number_format($detail->price, 0, ',', '.') }}</p>
                            </div>
                            <div class="text-end">
                                <strong class="text-success">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                        @endforeach
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($transaksi->subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Ongkos Kirim</span>
                            <span>Rp {{ number_format($transaksi->shipping_cost, 0, ',', '.') }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <h5 class="fw-bold">Total</h5>
                            <h5 class="fw-bold text-success">Rp {{ number_format($transaksi->total_amount, 0, ',', '.') }}</h5>
                        </div>
                    </div>
                </div>

            </div>
            {{-- ===== AKHIR AREA PRINT ===== --}}

            {{-- Card Informasi Transfer (tidak ikut print) --}}
            @if(in_array($transaksi->payment_method, ['qris', 'bri', 'dana', 'seabank', 'shopee']))
            <div class="card success-card mt-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">
                        <i class="fas fa-university me-2 text-success"></i>Informasi Pembayaran
                    </h5>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Silakan lakukan pembayaran sesuai metode yang Anda pilih
                    </div>
                    @if($transaksi->payment_method == 'qris')
                        <div class="payment-info">
                            <p class="mb-1"><strong>📌 Data QRIS:</strong></p>
                            <p class="mb-1">Nama: <strong>ACHMAD BAYU AL GHOZALI</strong></p>
                            <p class="mb-0">NMID: <strong>ID1025440155548</strong></p>
                        </div>
                        <div class="text-center my-3">
                            <div id="qrCodeContainer" class="bg-white p-3 d-inline-block rounded shadow-sm">
                                <img src="{{ asset('images/qris.jpg') }}" 
                                     alt="QRIS DANA" 
                                     style="width: 400px; height: 400px;" 
                                     class="border p-2 bg-white">
                                <p class="mb-2"><strong>Total Pembayaran:</strong></p>
                                <h4 class="text-success mb-3">Rp {{ number_format($transaksi->total_amount, 0, ',', '.') }}</h4>
                                <div class="d-flex justify-content-center gap-2 flex-wrap">
                                    <span class="badge bg-primary">GoPay</span>
                                    <span class="badge bg-info">OVO</span>
                                    <span class="badge bg-success">DANA</span>
                                    <span class="badge bg-warning text-dark">ShopeePay</span>
                                    <span class="badge bg-danger">LinkAja</span>
                                </div>
                                <small class="d-block mt-3 text-muted">
                                    Scan QR Code dengan aplikasi pembayaran digital apa saja yang mendukung QRIS
                                </small>
                            </div>
                        </div>
                    @elseif($transaksi->payment_method == 'bri')
                        <div class="bank-account">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/2/2e/BRI_2020.svg" 
                                         alt="BRI" style="height: 30px;" class="me-2">
                                    <strong>Bank BRI</strong>
                                </div>
                                <i class="fas fa-copy copy-btn text-success" onclick="copyToClipboard('1234567890123456')" title="Copy"></i>
                            </div>
                            <p class="mb-0">No. Rekening: <strong>1234-5678-9012-3456</strong></p>
                            <p class="mb-0 text-muted">a.n. Shop Bibit Cabai Bondowoso</p>
                        </div>
                    @elseif($transaksi->payment_method == 'dana')
                        <div class="bank-account">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div><strong>💳 DANA</strong></div>
                                <i class="fas fa-copy copy-btn text-success" onclick="copyToClipboard('081234567890')" title="Copy"></i>
                            </div>
                            <p class="mb-0">Nomor DANA: <strong>0812-3456-7890</strong></p>
                            <p class="mb-0 text-muted">a.n. Shop Bibit Cabai Bondowoso</p>
                        </div>
                    @elseif($transaksi->payment_method == 'seabank')
                        <div class="bank-account">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div><strong>🏦 SeaBank</strong></div>
                                <i class="fas fa-copy copy-btn text-success" onclick="copyToClipboard('901234567890')" title="Copy"></i>
                            </div>
                            <p class="mb-0">No. Rekening: <strong>901234567890</strong></p>
                            <p class="mb-0 text-muted">a.n. Shop Bibit Cabai Bondowoso</p>
                        </div>
                    @elseif($transaksi->payment_method == 'shopee')
                        <div class="bank-account">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div><strong>🛒 ShopeePay</strong></div>
                                <i class="fas fa-copy copy-btn text-success" onclick="copyToClipboard('081234567890')" title="Copy"></i>
                            </div>
                            <p class="mb-0">Nomor ShopeePay: <strong>0812-3456-7890</strong></p>
                            <p class="mb-0 text-muted">a.n. Shop Bibit Cabai Bondowoso</p>
                        </div>
                    @endif
                    <div class="alert alert-info mt-3 mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Setelah pembayaran berhasil, mohon konfirmasi melalui WhatsApp ke <strong>0813-3183-0561</strong> dengan menyertakan <strong>bukti pembayaran</strong> dan <strong>nomor invoice: {{ $transaksi->invoice_number }}</strong>
                    </div>
                </div>
            </div>
            @endif

            {{-- Card Tombol Aksi (tidak ikut print) --}}
            <div class="card success-card mt-4">
                <div class="card-body p-4 text-center">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <a href="{{ route('home') }}" class="btn btn-outline-success w-100">
                                <i class="fas fa-home me-2"></i>Kembali ke Beranda
                            </a>
                        </div>
                        <div class="col-md-6">
                            <button onclick="window.print()" class="btn btn-success w-100">
                                <i class="fas fa-print me-2"></i>Cetak Bukti Checkout
                            </button>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="https://wa.me/6282313155053?text=Halo, saya ingin konfirmasi pembayaran dengan invoice {{ $transaksi->invoice_number }}" 
                           target="_blank" 
                           class="btn btn-success w-100">
                            <i class="fab fa-whatsapp me-2"></i>Konfirmasi via WhatsApp
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('Nomor rekening berhasil disalin!');
    }, function(err) {
        console.error('Gagal menyalin: ', err);
    });
}
</script>
@endsection