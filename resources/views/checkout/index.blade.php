@extends('layouts.app')

@section('title', 'Checkout - Shop Bibit Cabai Bondowoso')

@section('styles')
<style>
    .ongkir-info {
        background: #e8f5e9;
        border-left: 4px solid #4caf50;
        padding: 15px;
        margin-top: 10px;
        border-radius: 5px;
        display: none;
    }
    
    .ongkir-loading {
        display: none;
        color: #666;
    }
    
    .total-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        margin-top: 20px;
    }
</style>
@endsection

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Form Checkout</h4>
                </div>
                <div class="card-body">
                    <!-- Informasi Produk -->
                    <div class="alert alert-info">
                        <h5>{{ $product->name }}</h5>
                        <p class="mb-1">Harga: Rp {{ number_format($product->price, 0, ',', '.') }} /bibit</p>
                        <p class="mb-0">Jumlah: <strong>{{ $quantity }} bibit</strong></p>
                    </div>

                    <!-- Form Checkout -->
                    <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="{{ $quantity }}">
                        <input type="hidden" name="shipping_cost" id="shipping_cost_input" value="0">
                        
                        <h5 class="mb-3">Data Pembeli</h5>
                        
                                                <div class="mb-3">
                            <label class="form-label">Nama Lengkap *</label>
                            <input type="text" name="name" 
                                class="form-control @error('name') is-invalid @enderror" 
                                value="{{ old('name') }}" 
                                placeholder="Minimal 8 karakter" 
                                minlength="8" required>
                            <small class="text-muted">Minimal 8 karakter</small>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Nomor Telepon *</label>
                            <input type="text" name="phone" 
                                class="form-control @error('phone') is-invalid @enderror" 
                                value="{{ old('phone') }}" 
                                placeholder="+6281234567890" 
                                minlength="11" required>
                            <small class="text-muted">Harus diawali +62, contoh: +6281234567890</small>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                      <div class="mb-3">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" 
                            class="form-control @error('email') is-invalid @enderror" 
                            value="{{ old('email') }}" 
                            placeholder="contoh@email.com"
                            minlength="8" required>
                        <small class="text-muted">Minimal 8 karakter</small>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                        
                        <h5 class="mb-3 mt-4">Alamat Pengiriman</h5>
                        
                        <div class="alert alert-warning">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Pengiriman hanya untuk wilayah Bondowoso</strong>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Kecamatan *</label>
                            <select name="city" id="kecamatan" class="form-control @error('city') is-invalid @enderror" required>
                                <option value="">-- Pilih Kecamatan --</option>
                                <option value="Bondowoso" {{ old('city') == 'Bondowoso' ? 'selected' : '' }}>Bondowoso (Pusat Kota)</option>
                                <option value="Grujugan" {{ old('city') == 'Grujugan' ? 'selected' : '' }}>Grujugan</option>
                                <option value="Jambesari Darus Sholah" {{ old('city') == 'Jambesari Darus Sholah' ? 'selected' : '' }}>Jambesari Darus Sholah</option>
                                <option value="Klabang" {{ old('city') == 'Klabang' ? 'selected' : '' }}>Klabang</option>
                                <option value="Tenggarang" {{ old('city') == 'Tenggarang' ? 'selected' : '' }}>Tenggarang</option>
                                <option value="Binakal" {{ old('city') == 'Binakal' ? 'selected' : '' }}>Binakal</option>
                                <option value="Prajekan" {{ old('city') == 'Prajekan' ? 'selected' : '' }}>Prajekan</option>
                                <option value="Botolinggo" {{ old('city') == 'Botolinggo' ? 'selected' : '' }}>Botolinggo</option>
                                <option value="Maesan" {{ old('city') == 'Maesan' ? 'selected' : '' }}>Maesan</option>
                                <option value="Tamanan" {{ old('city') == 'Tamanan' ? 'selected' : '' }}>Tamanan</option>
                                <option value="Wonosari" {{ old('city') == 'Wonosari' ? 'selected' : '' }}>Wonosari</option>
                                <option value="Pujer" {{ old('city') == 'Pujer' ? 'selected' : '' }}>Pujer</option>
                                <option value="Tlogosari" {{ old('city') == 'Tlogosari' ? 'selected' : '' }}>Tlogosari</option>
                                <option value="Sukosari" {{ old('city') == 'Sukosari' ? 'selected' : '' }}>Sukosari</option>
                                <option value="Sumberwringin" {{ old('city') == 'Sumberwringin' ? 'selected' : '' }}>Sumberwringin</option>
                                <option value="Tegalampel" {{ old('city') == 'Tegalampel' ? 'selected' : '' }}>Tegalampel</option>
                                <option value="Sempol" {{ old('city') == 'Sempol' ? 'selected' : '' }}>Sempol</option>
                                <option value="Pakem" {{ old('city') == 'Pakem' ? 'selected' : '' }}>Pakem</option>
                                <option value="Curahdami" {{ old('city') == 'Curahdami' ? 'selected' : '' }}>Curahdami</option>
                                <option value="Ijen" {{ old('city') == 'Ijen' ? 'selected' : '' }}>Ijen</option>
                                <option value="Tapen" {{ old('city') == 'Tapen' ? 'selected' : '' }}>Tapen</option>
                                <option value="Wringin" {{ old('city') == 'Wringin' ? 'selected' : '' }}>Wringin</option>
                                <option value="Taman Krocok" {{ old('city') == 'Taman Krocok' ? 'selected' : '' }}>Taman Krocok</option>
                            </select>
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            <div class="ongkir-loading mt-2">
                                <i class="fas fa-spinner fa-spin me-2"></i>Menghitung ongkir...
                            </div>
                            
                            <div class="ongkir-info" id="ongkirInfo">
                                <i class="fas fa-truck me-2"></i>
                                <strong>Ongkir ke <span id="ongkirKecamatan"></span>: 
                                    <span id="ongkirAmount" class="text-success">Rp 0</span>
                                </strong>
                                <div id="ongkirDetail" class="small mt-1 text-muted"></div>
                            </div>
                        </div>
                        
                       <div class="mb-3">
                                <label class="form-label">Alamat Lengkap *</label>
                                <textarea name="address" 
                                        class="form-control @error('address') is-invalid @enderror" 
                                        rows="4" 
                                        placeholder="Contoh: Jl. Merdeka No. 12, RT 02/RW 05, Desa Kademangan, Arah dari pasar ke utara 200m" 
                                        minlength="20" required>{{ old('address') }}</textarea>
                                <small class="text-muted">
                                    Wajib lengkap: Nama Jalan · No. Rumah · RT/RW · Desa/Kelurahan · Ancer-ancer
                                </small>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        
                        <input type="hidden" name="province" value="Jawa Timur">
                        
                       <div class="mb-3">
                            <label class="form-label">Kode Pos *</label>
                            <input type="text" name="postal_code" 
                                class="form-control @error('postal_code') is-invalid @enderror" 
                                value="{{ old('postal_code', '68200') }}" 
                                placeholder="68200" 
                                maxlength="5" required>
                            @error('postal_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                     <h5 class="mb-3 mt-4">Metode Pembayaran</h5>

            <div class="mb-3">
                <label class="form-label">Pilih Metode Pembayaran *</label>
                <select name="payment_method" id="paymentMethod" class="form-control @error('payment_method') is-invalid @enderror" required>
                    <option value="">-- Pilih Metode Pembayaran --</option>
                    <option value="qris" {{ old('payment_method') == 'qris' ? 'selected' : '' }}>
                        📱 QRIS (Scan & Pay)
                    </option>
                    <option value="bri" {{ old('payment_method') == 'bri' ? 'selected' : '' }}>
                        🏦 Transfer Bank BRI
                    </option>
                    <option value="dana" {{ old('payment_method') == 'dana' ? 'selected' : '' }}>
                        💳 DANA
                    </option>
                    <option value="seabank" {{ old('payment_method') == 'seabank' ? 'selected' : '' }}>
                        🏦 SeaBank
                    </option>
                    <option value="shopepay" {{ old('payment_method') == 'shopee' ? 'selected' : '' }}>
                        🛒 ShopeePay
                    </option>
                    <option value="cod" {{ old('payment_method') == 'cod' ? 'selected' : '' }}>
                        💵 COD (Cash on Delivery)
                    </option>
                </select>
                @error('payment_method')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Info Pembayaran -->
            <div id="paymentInfo" style="display: none;">
                <!-- QRIS Info -->
               <!-- Bagian QRIS Info - Ganti bagian ini di checkout.blade.php Anda -->

<!-- QRIS Info -->
<div id="qrisInfo" class="alert alert-info" style="display: none;">
    <h6><strong>📱 Pembayaran via QRIS</strong></h6>
    
    <!-- Info NMID -->
    <div class="alert alert-light border-1 mb-3">
        <small class="text-muted d-block mb-1"><strong>Data QRIS Anda:</strong></small>
        <p class="mb-1">Nama: <strong>ACHMAD BAYU AL GHOZALI</strong></p>
        <p class="mb-0">NMID: <strong>ID1025440155548</strong></p>
    </div>
    
    <div class="text-center my-3">
        <!-- QR Code DANA Anda -->
        <div id="qrCodeContainer" class="bg-white p-3 d-inline-block rounded shadow-sm">
            <!-- PENTING: Ganti src di bawah dengan QR Code QRIS Dana Anda -->
            <!-- Opsi 1: Jika Anda punya file QR Code lokal -->
            <img src="{{ asset('images/qris.jpg') }}" 
                 alt="QRIS DANA" 
                 style="width: 250px; height: 250px;" 
                 class="border p-2 bg-white">
            
            <!-- Opsi 2: Jika ingin generate dinamis, gunakan NMID Anda -->
            <!-- Uncomment jika ingin menggunakan -->
            <!-- <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=00020101021226670016COM.NOBUBANK.WWW01189360050300000898740214545400008987400303UME51440014ID.CO.QRIS.WWW0215ID10200000000150303UME5204481253033605802ID5925ACHMAD BAYU AL GHOZALI6010Bondowoso61056820062070703A016304" 
                alt="QRIS DANA" 
                style="width: 250px; height: 250px;" 
                class="border p-2 bg-white"> -->
        </div>
    </div>
    
    <div class="text-center">
        <p class="mb-1"><strong>Scan QR Code dengan aplikasi berikut:</strong></p>
        <div class="d-flex justify-content-center gap-2 flex-wrap mt-2">
            <span class="badge bg-primary">GoPay</span>
            <span class="badge bg-info">OVO</span>
            <span class="badge bg-success">DANA</span>
            <span class="badge bg-warning text-dark">ShopeePay</span>
            <span class="badge bg-danger">LinkAja</span>
            <span class="badge bg-secondary">Mobile Banking</span>
        </div>
    </div>
    
    <hr>
    
    <div class="alert alert-warning mb-0">
        <strong>⚠️ Penting:</strong>
        <small class="d-block mt-2">
            <strong>Jika QR Code tidak muncul:</strong><br>
            1. Download QR Code QRIS dari aplikasi DANA Anda<br>
            2. Upload ke folder <code>public/images/qris-dana.png</code> di server<br>
            3. Atau gunakan opsi 2 di komentar kode di atas
        </small>
    </div>
    
    <small class="text-muted d-block mt-3">
        <strong>Cara Bayar:</strong><br>
        1. Buka aplikasi pembayaran digital Anda (GoPay, OVO, DANA, dll)<br>
        2. Pilih menu "Scan QRIS" atau "Scan QR"<br>
        3. Arahkan kamera ke QR Code di atas<br>
        4. Nominal akan otomatis terisi sesuai total pesanan<br>
        5. Tekan Bayar dan ikuti instruksi aplikasi<br>
        6. Konfirmasi pembayaran akan langsung diterima
    </small>
</div>
                <!-- BRI Info -->
                <div id="briInfo" class="alert alert-info" style="display: none;">
                    <h6><strong>🏦 Transfer ke Rekening BRI</strong></h6>
                    <p class="mb-1">No. Rekening: <strong>1234-5678-9012-3456</strong></p>
                    <p class="mb-1">Atas Nama: <strong>Shop Bibit Cabai Bondowoso</strong></p>
                    <small class="text-muted">Silakan transfer sesuai total pembayaran yang tertera</small>
                </div>

                <!-- DANA Info -->
                <div id="danaInfo" class="alert alert-info" style="display: none;">
                    <h6><strong>💳 Transfer ke DANA</strong></h6>
                    <p class="mb-1">Nomor DANA: <strong>0813-3183-0561</strong></p>
                    <p class="mb-1">Atas Nama: <strong>Shop Bibit Cabai Bondowoso</strong></p>
                    <small class="text-muted">Buka aplikasi DANA → Transfer → Masukkan nomor di atas</small>
                </div>

                <!-- SeaBank Info -->
                <div id="seabankInfo" class="alert alert-info" style="display: none;">
                    <h6><strong>🏦 Transfer ke SeaBank</strong></h6>
                    <p class="mb-1">No. Rekening: <strong>901234567890</strong></p>
                    <p class="mb-1">Atas Nama: <strong>Shop Bibit Cabai Bondowoso</strong></p>
                    <small class="text-muted">Transfer melalui aplikasi SeaBank atau mobile banking</small>
                </div>

                <!-- ShopeePay Info -->
                <div id="shopeeInfo" class="alert alert-info" style="display: none;">
                    <h6><strong>🛒 Transfer ke ShopeePay</strong></h6>
                    <p class="mb-1">Nomor ShopeePay: <strong>0813-3183-0561</strong></p>
                    <p class="mb-1">Atas Nama: <strong>Shop Bibit Cabai Bondowoso</strong></p>
                    <small class="text-muted">Buka aplikasi Shopee → ShopeePay → Transfer → Ke Pengguna</small>
                </div>

                <!-- COD Info -->
                <div id="codInfo" class="alert alert-warning" style="display: none;">
                    <h6><strong>💵 Cash on Delivery (COD)</strong></h6>
                    <p class="mb-1">✅ Bayar tunai saat barang diterima</p>
                    <p class="mb-1">✅ Pastikan uang pas sesuai total pembayaran</p>
                    <small class="text-muted">Pembayaran dilakukan langsung kepada kurir saat pengiriman</small>
                </div>
            </div>

        <div class="alert alert-success mt-3">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Konfirmasi Pembayaran:</strong> Setelah pembayaran berhasil, mohon konfirmasi melalui WhatsApp ke <strong>081331830561</strong> dengan menyertakan bukti pembayaran dan nomor invoice.
        </div>
                                
                        <div class="mb-3">
                            <label class="form-label">Catatan (Opsional)</label>
                            <textarea name="notes" class="form-control" rows="2" 
                                      placeholder="Tambahkan catatan jika ada">{{ old('notes') }}</textarea>
                        </div>
                        
                        <!-- Ringkasan Total -->
                        <div class="total-section">
                            <h5 class="mb-3"><i class="fas fa-receipt me-2"></i>Ringkasan Pembayaran</h5>
                            
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal ({{ $quantity }} bibit)</span>
                                <span><strong id="subtotalDisplay">Rp {{ number_format($product->price * $quantity, 0, ',', '.') }}</strong></span>
                            </div>
                            
                            <div class="d-flex justify-content-between mb-2">
                                <span>Ongkos Kirim</span>
                                <span><strong id="ongkirDisplay">Rp 0</strong></span>
                            </div>
                            
                            <hr>
                            
                            <div class="d-flex justify-content-between">
                                <h5 class="mb-0">Total Pembayaran</h5>
                                <h5 class="mb-0 text-success"><strong id="totalDisplay">Rp {{ number_format($product->price * $quantity, 0, ',', '.') }}</strong></h5>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-check-circle me-2"></i>Proses Checkout
                            </button>
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const kecamatanSelect = document.getElementById('kecamatan');
    const ongkirInfo = document.getElementById('ongkirInfo');
    const ongkirLoading = document.querySelector('.ongkir-loading');
    const quantity = {{ $quantity }};
    const subtotal = {{ $product->price * $quantity }};
    
    // Data ongkir per kecamatan
    const ongkirData = {
        'Bondowoso': 50000,
        'Grujugan': 75000,
        'Jambesari Darus Sholah': 250000,
        'Klabang': 50000,
        'Tenggarang': 50000,
        'Binakal': 12000,
        'Prajekan': 75000,
        'Botolinggo': 10000,
        'Maesan': 125000,
        'Tamanan': 100000,
        'Wonosari': 20000,
        'Pujer': 50000,
        'Tlogosari': 75000,
        'Sukosari': 50000,
        'Sumberwringin': 75000,
        'Tegalampel': 75000,
        'Sempol': 150000,
        'Pakem': 175000,
        'Curahdami': 10000,
        'Ijen': 20000,
        'Tapen': 30000,
        'Wringin': 100000,
        'Taman Krocok': 50000
    };
    
  // Handler untuk perhitungan ongkir
    kecamatanSelect.addEventListener('change', function() {
        const kecamatan = this.value;
        
        if (!kecamatan) {
            ongkirInfo.style.display = 'none';
            updateTotal(0);
            return;
        }
        
        ongkirLoading.style.display = 'block';
        ongkirInfo.style.display = 'none';
        
       setTimeout(() => {
            const baseOngkir = ongkirData[kecamatan] || 15000;
            let totalOngkir = baseOngkir;
            let detail = `Ongkir ke ${kecamatan}: Rp ${formatRupiah(baseOngkir)}`;

            // Gratis ongkir >= 800 bibit
            if (quantity >= 800) {
                totalOngkir = 0;
                detail = '🎉 GRATIS ONGKIR untuk pembelian 800+ bibit!';
            }
            // >= 1000 bibit: gratis ongkir + diskon 15%
            if (quantity >= 1000) {
                totalOngkir = 0;
                detail = '🎉 GRATIS ONGKIR + Diskon 15% untuk pembelian 1000+ bibit!';
            }

            document.getElementById('ongkirKecamatan').textContent = kecamatan;
            document.getElementById('ongkirAmount').textContent = 'Rp ' + formatRupiah(totalOngkir);
            document.getElementById('ongkirDetail').textContent = detail;
            document.getElementById('shipping_cost_input').value = totalOngkir;
            
            ongkirLoading.style.display = 'none';
            ongkirInfo.style.display = 'block';
            
            updateTotal(totalOngkir);
        }, 500);
    });
    
    // Handler untuk metode pembayaran
    const paymentMethod = document.getElementById('paymentMethod');
    const paymentInfo = document.getElementById('paymentInfo');
    const qrisInfo = document.getElementById('qrisInfo');
    const briInfo = document.getElementById('briInfo');
    const danaInfo = document.getElementById('danaInfo');
    const seabankInfo = document.getElementById('seabankInfo');
    const shopeeInfo = document.getElementById('shopeeInfo');
    const codInfo = document.getElementById('codInfo');
    
    paymentMethod.addEventListener('change', function() {
        const method = this.value;
        
        // Sembunyikan semua info
        paymentInfo.style.display = 'none';
        qrisInfo.style.display = 'none';
        briInfo.style.display = 'none';
        danaInfo.style.display = 'none';
        seabankInfo.style.display = 'none';
        shopeeInfo.style.display = 'none';
        codInfo.style.display = 'none';
        
        // Tampilkan info sesuai metode yang dipilih
        if (method) {
            paymentInfo.style.display = 'block';
            
            switch(method) {
                case 'qris':
                    qrisInfo.style.display = 'block';
                    break;
                case 'bri':
                    briInfo.style.display = 'block';
                    break;
                case 'dana':
                    danaInfo.style.display = 'block';
                    break;
                case 'seabank':
                    seabankInfo.style.display = 'block';
                    break;
                case 'shopepay':
                    shopeeInfo.style.display = 'block';
                    break;
                case 'cod':
                    codInfo.style.display = 'block';
                    break;
            }
        }
    });
    
   function updateTotal(ongkir) {
        // Hitung diskon
        let diskon = 0;
        let diskonLabel = '';

        if (quantity >= 1000) {
            diskon = subtotal * 0.15;
            diskonLabel = '🎉 Diskon 15% untuk pembelian 1000+ bibit!';
        }

        const finalTotal = (subtotal - diskon) + ongkir;

        document.getElementById('ongkirDisplay').textContent = 'Rp ' + formatRupiah(ongkir);
        document.getElementById('totalDisplay').textContent  = 'Rp ' + formatRupiah(finalTotal);

        // Tampilkan/sembunyikan baris diskon
        let diskonEl = document.getElementById('diskonRow');
        if (!diskonEl && diskon > 0) {
            // Buat baris diskon jika belum ada
            const ongkirRow = document.querySelector('.total-section hr');
            const rowHtml = `
                <div class="d-flex justify-content-between mb-2 text-danger" id="diskonRow">
                    <span>${diskonLabel}</span>
                    <span><strong>-Rp ${formatRupiah(diskon)}</strong></span>
                </div>`;
            ongkirRow.insertAdjacentHTML('beforebegin', rowHtml);
        } else if (diskonEl) {
            if (diskon > 0) {
                diskonEl.style.display = 'flex';
                diskonEl.querySelector('span').textContent = diskonLabel;
                diskonEl.querySelector('strong').textContent = '-Rp ' + formatRupiah(diskon);
            } else {
                diskonEl.style.display = 'none';
            }
        }
    }
    
    function formatRupiah(angka) {
        return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
});
</script>
@endsection