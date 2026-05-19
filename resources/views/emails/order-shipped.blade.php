<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: auto; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #28a745, #20c997); color: white; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 26px; }
        .header p { margin: 8px 0 0; opacity: 0.9; }
        .icon { font-size: 50px; margin-bottom: 10px; }
        .body { padding: 30px; }
        .greeting { font-size: 16px; color: #333; margin-bottom: 20px; }
        .tracking-box { background: #e8f5e9; border: 2px dashed #28a745; border-radius: 10px; padding: 20px; text-align: center; margin: 20px 0; }
        .tracking-box .label { font-size: 12px; color: #666; text-transform: uppercase; letter-spacing: 1px; }
        .tracking-box .number { font-size: 22px; font-weight: bold; color: #28a745; font-family: monospace; margin-top: 6px; }
        .section { margin-bottom: 24px; }
        .section-title { font-size: 13px; font-weight: bold; text-transform: uppercase; color: #888; letter-spacing: 1px; margin-bottom: 10px; border-bottom: 1px solid #eee; padding-bottom: 6px; }
        .info-row { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px; }
        .info-label { color: #555; }
        .info-value { font-weight: bold; color: #222; text-align: right; }
        .total-box { background: #f1f8e9; border: 2px solid #a5d6a7; border-radius: 8px; padding: 16px 20px; }
        .total-final { display: flex; justify-content: space-between; font-size: 18px; font-weight: bold; color: #28a745; }
        .steps { margin: 20px 0; }
        .step { display: flex; align-items: flex-start; margin-bottom: 14px; }
        .step-num { background: #28a745; color: white; border-radius: 50%; width: 26px; height: 26px; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: bold; flex-shrink: 0; margin-right: 12px; margin-top: 2px; }
        .step-text { font-size: 14px; color: #444; }
        .footer { background: #f9f9f9; padding: 16px 30px; text-align: center; font-size: 12px; color: #aaa; border-top: 1px solid #eee; }
        .whatsapp-btn { display: inline-block; background: #25D366; color: white; padding: 12px 28px; border-radius: 25px; text-decoration: none; font-weight: bold; margin-top: 16px; font-size: 15px; }
    </style>
</head>
<body>
<div class="container">

    <div class="header">
        <div class="icon">📦</div>
        <h1>Pesanan Sedang Dikirim!</h1>
        <p>{{ $transaksi->created_at->format('d F Y') }}</p>
    </div>

    <div class="body">

        <p class="greeting">
            Halo <strong>{{ $transaksi->customer_name }}</strong>, kabar baik! 🎉<br>
            Pesanan Anda dengan invoice <strong>{{ $transaksi->invoice_number }}</strong> 
            sudah dalam perjalanan menuju alamat Anda.
        </p>

        {{-- Tracking Number --}}
        @if($transaksi->tracking_number)
        <div class="tracking-box">
            <div class="label">Nomor Resi Pengiriman</div>
            <div class="number">{{ $transaksi->tracking_number }}</div>
            <div style="font-size:12px; color:#666; margin-top:8px;">
                Simpan nomor resi ini untuk melacak paket Anda
            </div>
        </div>
        @endif

        {{-- Detail Pesanan --}}
        <div class="section">
            <div class="section-title">🌶️ Detail Pesanan</div>
            @foreach($transaksi->details as $detail)
            <div class="info-row">
                <span class="info-label">{{ $detail->product_name }}</span>
                <span class="info-value">{{ $detail->quantity }} bibit</span>
            </div>
            @endforeach
        </div>

        {{-- Alamat Pengiriman --}}
        <div class="section">
            <div class="section-title">📍 Dikirim ke</div>
            <div style="font-size:14px; color:#444; line-height:1.8;">
                {{ $transaksi->shipping_address }}<br>
                Kec. {{ $transaksi->city }}, {{ $transaksi->province }}<br>
                Kode Pos: {{ $transaksi->postal_code }}
            </div>
        </div>

        {{-- Total --}}
        <div class="total-box">
            <div class="total-final">
                <span>Total Pembayaran</span>
                <span>Rp {{ number_format($transaksi->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Instruksi --}}
        <div class="section" style="margin-top:24px;">
            <div class="section-title">📋 Apa yang harus dilakukan?</div>
            <div class="steps">
                <div class="step">
                    <div class="step-num">1</div>
                    <div class="step-text">Pantau pengiriman menggunakan nomor resi di atas</div>
                </div>
                <div class="step">
                    <div class="step-num">2</div>
                    <div class="step-text">Pastikan ada orang di rumah untuk menerima paket</div>
                </div>
                <div class="step">
                    <div class="step-num">3</div>
                    <div class="step-text">Periksa kondisi paket saat diterima sebelum menandatangani</div>
                </div>
                <div class="step">
                    <div class="step-num">4</div>
                    <div class="step-text">Jika ada kendala, hubungi kami via WhatsApp</div>
                </div>
            </div>
        </div>

        <div style="text-align:center;">
            <a href="https://wa.me/62081331830561?text=Halo, saya ingin menanyakan pesanan {{ $transaksi->invoice_number }}" 
               class="whatsapp-btn">
                💬 Hubungi via WhatsApp
            </a>
        </div>

    </div>

    <div class="footer">
        Shop Bibit Cabai Bondowoso · 081331830561<br>
        Email ini dikirim otomatis, tidak perlu dibalas.
    </div>

</div>
</body>
</html>