@extends('layouts.app')

@section('title', 'Batalkan Pesanan - Bibit Cabai')

@section('styles')
<style>
    body { background: #f8f9fa; font-family: 'Segoe UI', sans-serif; }

    .page-hero {
        background: linear-gradient(135deg, #28a745, #20c997);
        padding: 35px 0; color: white;
    }

    .cancel-wrap {
        max-width: 680px; margin: 0 auto; padding: 30px 15px;
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

    .order-summary-box {
        background: #f8f9fa; border-radius: 12px;
        padding: 16px 18px; margin-bottom: 22px;
        border-left: 4px solid #dc3545;
    }

    .invoice-num { font-size: 0.8rem; color: #6c757d; font-family: monospace; }

    .product-img {
        width: 48px; height: 48px; border-radius: 8px;
        object-fit: cover; border: 1px solid #e9ecef; flex-shrink: 0;
    }

    .product-placeholder {
        width: 48px; height: 48px; border-radius: 8px;
        background: #f0f0f0; display: flex; flex-shrink: 0;
        align-items: center; justify-content: center; color: #aaa;
    }

    .reason-list { display: flex; flex-direction: column; gap: 10px; }

    .reason-item input[type="radio"] { display: none; }

    .reason-label {
        display: flex; align-items: center; gap: 14px;
        padding: 13px 16px; border-radius: 10px;
        border: 2px solid #e9ecef; cursor: pointer;
        transition: all 0.18s; font-size: 0.92rem; background: white;
        user-select: none;
    }

    .reason-label:hover { border-color: #dc3545; background: #fff8f8; }

    .reason-item input[type="radio"]:checked + .reason-label {
        border-color: #dc3545; background: #fff0f0;
        color: #c0392b; font-weight: 600;
    }

    .radio-dot {
        width: 20px; height: 20px; border-radius: 50%;
        border: 2px solid #dee2e6; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        transition: all 0.18s;
    }

    .reason-item input[type="radio"]:checked + .reason-label .radio-dot {
        border-color: #dc3545; background: #dc3545;
    }

    .reason-item input[type="radio"]:checked + .reason-label .radio-dot::after {
        content: ''; width: 8px; height: 8px;
        border-radius: 50%; background: white; display: block;
    }

    .warning-box {
        background: #fff3cd; border: 1px solid #ffc107;
        border-radius: 10px; padding: 13px 16px;
        color: #856404; font-size: 0.87rem; line-height: 1.5;
    }

    .btn-submit-cancel {
        width: 100%; padding: 13px;
        background: #dc3545; color: white; border: none;
        border-radius: 10px; font-weight: 700; font-size: 1rem;
        transition: all 0.2s; cursor: pointer; letter-spacing: 0.3px;
    }

    .btn-submit-cancel:hover {
        background: #c82333;
        box-shadow: 0 4px 14px rgba(220,53,69,0.35);
        transform: translateY(-1px);
    }

    .btn-kembali {
        display: block; text-align: center; margin-top: 13px;
        color: #6c757d; font-size: 0.88rem; text-decoration: none;
    }

    .btn-kembali:hover { color: #333; text-decoration: underline; }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        20%       { transform: translateX(-8px); }
        40%       { transform: translateX(8px); }
        60%       { transform: translateX(-5px); }
        80%       { transform: translateX(5px); }
    }
</style>
@endsection

@section('content')

<div class="page-hero">
    <div class="container d-flex align-items-center gap-3">
        <a href="{{ route('orders.detail', $transaksi->id) }}" class="btn btn-outline-light btn-sm">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h5 class="mb-0"><i class="fas fa-times-circle me-2"></i>Batalkan Pesanan</h5>
            <small class="font-monospace">{{ $transaksi->invoice_number }}</small>
        </div>
    </div>
</div>

<div class="cancel-wrap">
    <div class="detail-card">
        <div class="section-title">
            <i class="fas fa-receipt me-2 text-danger"></i>Pesanan yang Akan Dibatalkan
        </div>

        <div class="order-summary-box">
            <div class="d-flex justify-content-between mb-2">
                <span class="invoice-num"><i class="fas fa-receipt me-1"></i>{{ $transaksi->invoice_number }}</span>
                <small class="text-muted">{{ $transaksi->created_at->format('d M Y, H:i') }}</small>
            </div>

            @foreach($transaksi->details->take(2) as $detail)
            <div class="d-flex align-items-center gap-3 mb-2">
                @if($detail->product && $detail->product->image)
                    <img src="{{ asset('storage/' . $detail->product->image) }}" class="product-img" alt="">
                @else
                    <div class="product-placeholder"><i class="fas fa-seedling"></i></div>
                @endif
                <div class="flex-grow-1">
                    <div class="fw-bold" style="font-size:0.88rem;">{{ $detail->product_name }}</div>
                    <small class="text-muted">{{ $detail->quantity }} × Rp{{ number_format($detail->price, 0, ',', '.') }}</small>
                </div>
                <div class="fw-bold text-danger" style="font-size:0.88rem;">
                    Rp{{ number_format($detail->subtotal, 0, ',', '.') }}
                </div>
            </div>
            @endforeach

            @if($transaksi->details->count() > 2)
                <small class="text-muted">
                    <i class="fas fa-plus me-1"></i>{{ $transaksi->details->count() - 2 }} produk lainnya
                </small>
            @endif

            <div class="d-flex justify-content-between border-top pt-2 mt-2">
                <span class="fw-bold">Total Pembayaran</span>
                <span class="fw-bold text-danger">Rp{{ number_format($transaksi->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="warning-box mb-4">
    <i class="fas fa-exclamation-triangle me-2"></i>
    @if($transaksi->isAutoCancel())
        <strong>Perhatian!</strong> Pesanan ini menggunakan <strong>COD</strong> dan belum diproses,
        sehingga akan <strong>langsung dibatalkan</strong> tanpa perlu menunggu konfirmasi admin.
    @else
        <strong>Perhatian!</strong> Pengajuan pembatalan ini akan dikonfirmasi oleh admin terlebih dahulu.
        Pesanan <strong>tidak langsung dibatalkan</strong> — kamu akan mendapat notifikasi setelah admin merespons.
    @endif
</div>

        <form action="{{ route('orders.cancel.store', $transaksi->id) }}" method="POST" id="cancelForm">
            @csrf

            <div class="section-title">
                <i class="fas fa-question-circle me-2 text-danger"></i>Pilih Alasan Pembatalan
            </div>

            @error('reason')
                <div class="alert alert-danger py-2 mb-3" style="border-radius:8px; font-size:0.87rem;">
                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                </div>
            @enderror

            <div class="reason-list mb-4">
                @foreach($reasons as $value => $label)
                <div class="reason-item">
                    <input type="radio" name="reason"
                           id="reason_{{ $loop->index }}"
                           value="{{ $value }}"
                           {{ old('reason') == $value ? 'checked' : '' }}>
                    <label class="reason-label" for="reason_{{ $loop->index }}">
                        <span class="radio-dot"></span>
                        {{ $label }}
                    </label>
                </div>
                @endforeach
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold" style="font-size:0.92rem;">
                    Keterangan Tambahan
                    <span class="text-muted fw-normal">(opsional)</span>
                </label>
                <textarea name="description"
                          class="form-control @error('description') is-invalid @enderror"
                          rows="3" maxlength="500"
                          placeholder="Ceritakan lebih detail alasanmu (opsional)...">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">Maks. 500 karakter</small>
            </div>

            <button type="button" class="btn-submit-cancel" id="btnSubmitCancel">
                <i class="fas fa-paper-plane me-2"></i>Kirim Pengajuan Pembatalan
            </button>
        </form>

        <a href="{{ route('orders.detail', $transaksi->id) }}" class="btn-kembali">
            <i class="fas fa-arrow-left me-1"></i>Batal, kembali ke detail pesanan
        </a>
    </div>
</div>

{{-- Modal Konfirmasi --}}
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:400px;">
        <div class="modal-content border-0" style="border-radius:16px; overflow:hidden;">
            <div class="modal-body text-center p-4">
                <div class="mb-3" style="font-size:2.8rem;">😔</div>
                <h5 class="fw-bold mb-2">Yakin ingin membatalkan?</h5>
               <p class="text-muted mb-4" style="font-size:0.88rem; line-height:1.5;">
    @if($transaksi->isAutoCancel())
        Pesanan COD ini akan <strong>langsung dibatalkan</strong> sekarang juga.
    @else
        Pengajuan akan dikirim ke admin untuk ditinjau.
        Pesanan tidak langsung dibatalkan sampai admin menyetujui.
    @endif
</p>
                <div class="d-flex gap-2">
                    <button type="button"
                            class="btn btn-outline-secondary flex-fill"
                            data-bs-dismiss="modal">
                        Tidak Jadi
                    </button>
                    <button type="button"
                            class="btn btn-danger flex-fill"
                            id="btnConfirmSubmit">
                        Ya, Ajukan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Script langsung di dalam section content --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {

        document.getElementById('btnSubmitCancel').addEventListener('click', function () {
            const selected = document.querySelector('input[name="reason"]:checked');
            if (!selected) {
                const list = document.querySelector('.reason-list');
                list.style.animation = 'none';
                list.offsetHeight;
                list.style.animation = 'shake 0.4s ease';
                alert('Pilih salah satu alasan pembatalan terlebih dahulu.');
                return;
            }
            const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
            modal.show();
        });

        document.getElementById('btnConfirmSubmit').addEventListener('click', function () {
            document.getElementById('cancelForm').submit();
        });

    });
</script>

@endsection