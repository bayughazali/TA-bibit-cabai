<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: auto; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { background: #2e7d32; color: white; padding: 20px 30px; }
        .header h1 { margin: 0; font-size: 22px; }
        .header p { margin: 5px 0 0; opacity: 0.85; font-size: 14px; }
        .body { padding: 30px; }
        .invoice-badge { display: inline-block; background: #e8f5e9; color: #2e7d32; font-weight: bold; padding: 6px 16px; border-radius: 20px; font-size: 14px; margin-bottom: 20px; }
        .section { margin-bottom: 24px; }
        .section-title { font-size: 13px; font-weight: bold; text-transform: uppercase; color: #888; letter-spacing: 1px; margin-bottom: 10px; border-bottom: 1px solid #eee; padding-bottom: 6px; }
        .info-row { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px; }
        .info-label { color: #555; }
        .info-value { font-weight: bold; color: #222; text-align: right; max-width: 60%; }
        .total-box { background: #f1f8e9; border: 2px solid #a5d6a7; border-radius: 8px; padding: 16px 20px; margin-top: 10px; }
        .total-row { display: flex; justify-content: space-between; margin-bottom: 6px; font-size: 14px; }
        .total-final { display: flex; justify-content: space-between; font-size: 18px; font-weight: bold; color: #2e7d32; margin-top: 10px; padding-top: 10px; border-top: 1px dashed #a5d6a7; }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 12px; font-size: 12px; font-weight: bold; }
        .badge-pending { background: #fff3cd; color: #856404; }
        .badge-payment { background: #d1ecf1; color: #0c5460; }
        .cta { text-align: center; margin-top: 24px; }
        .cta a { background: #2e7d32; color: white; padding: 12px 30px; border-radius: 6px; text-decoration: none; font-weight: bold; font-size: 15px; display: inline-block; }
        .footer { background: #f9f9f9; padding: 16px 30px; text-align: center; font-size: 12px; color: #aaa; border-top: 1px solid #eee; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>🛒 Ada Pesanan Baru!</h1>
        <p>{{ $transaksi->created_at->format('d F Y, H:i') }} WIB</p>
    </div>

    <div class="body">
        <div class="invoice-badge">{{ $transaksi->invoice_number }}</div>

        <!-- Info Pelanggan -->
        <div class="section">
            <div class="section-title">👤 Data Pelanggan</div>
            <div class="info-row">
                <span class="info-label">Nama</span>
                <span class="info-value">{{ $transaksi->customer_name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Telepon</span>
                <span class="info-value">{{ $transaksi->customer_phone }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email</span>
                <span class="info-value">{{ $transaksi->customer_email }}</span>
            </div>
        </div>

        <!-- Alamat Pengiriman -->
        <div class="section">
            <div class="section-title">📦 Alamat Pengiriman</div>
            <div class="info-row">
                <span class="info-label">Alamat</span>
                <span class="info-value">{{ $transaksi->shipping_address }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Kecamatan</span>
                <span class="info-value">{{ $transaksi->city }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Provinsi</span>
                <span class="info-value">{{ $transaksi->province ?? 'Jawa Timur' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Kode Pos</span>
                <span class="info-value">{{ $transaksi->postal_code }}</span>
            </div>
        </div>

        <!-- Detail Produk -->
        <div class="section">
            <div class="section-title">🌶️ Detail Produk</div>
            @foreach($transaksi->details as $detail)
            <div class="info-row">
                <span class="info-label">{{ $detail->product_name }}</span>
                <span class="info-value">{{ $detail->quantity }} bibit × Rp {{ number_format($detail->price, 0, ',', '.') }}</span>
            </div>
            @endforeach
        </div>

        <!-- Ringkasan Pembayaran -->
        <div class="section">
            <div class="section-title">💳 Ringkasan Pembayaran</div>
            <div class="total-box">
                <div class="total-row">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($transaksi->subtotal, 0, ',', '.') }}</span>
                </div>
                @if($transaksi->discount > 0)
                <div class="total-row" style="color: #e53935;">
                    <span>Diskon (15%)</span>
                    <span>-Rp {{ number_format($transaksi->discount, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="total-row">
                    <span>Ongkos Kirim</span>
                    <span>{{ $transaksi->shipping_cost == 0 ? 'GRATIS' : 'Rp ' . number_format($transaksi->shipping_cost, 0, ',', '.') }}</span>
                </div>
                <div class="total-final">
                    <span>Total Pembayaran</span>
                    <span>Rp {{ number_format($transaksi->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>

            <div style="margin-top: 12px;">
                <div class="info-row">
                    <span class="info-label">Metode Pembayaran</span>
                    <span class="info-value">
                        <span class="badge badge-payment">
                            {{ strtoupper($transaksi->payment_method) }}
                        </span>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status Pesanan</span>
                    <span class="info-value">
                        <span class="badge badge-pending">⏳ PENDING</span>
                    </span>
                </div>
            </div>
        </div>

        @if($transaksi->notes)
        <div class="section">
            <div class="section-title">📝 Catatan Pelanggan</div>
            <p style="font-size: 14px; color: #444; background: #f9f9f9; padding: 10px; border-radius: 6px;">{{ $transaksi->notes }}</p>
        </div>
        @endif

        <div class="cta">
            <a href="{{ url('/admin/transaksi/' . $transaksi->id) }}">
                Lihat Detail di Dashboard →
            </a>
        </div>
    </div>

    <div class="footer">
        Shop Bibit Cabai Bondowoso · Notifikasi otomatis, tidak perlu dibalas.
    </div>
</div>
</body>
</html>