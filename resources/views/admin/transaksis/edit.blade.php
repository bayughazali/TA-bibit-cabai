<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Order - Bibit Cabai Admin</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --sidebar-width: 260px;
            --green-dark: #15803d;
            --green-main: #16a34a;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f1f5f2;
            overflow-x: hidden;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--green-dark) 0%, #14532d 100%);
            color: white;
            min-height: 100vh;
            position: fixed;
            top: 0; left: 0;
            z-index: 1050;
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 24px 20px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.12);
            flex-shrink: 0;
        }

        .sidebar-header .brand-icon { font-size: 2rem; margin-bottom: 6px; display: block; }
        .sidebar-header h3 { font-size: 1.35rem; font-weight: 700; margin-bottom: 2px; }
        .sidebar-header .subtitle { opacity: 0.65; font-size: 0.78rem; margin-bottom: 6px; }
        .sidebar-header .admin-name {
            display: inline-block;
            background: rgba(255,255,255,0.15);
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 500;
        }

        .sidebar-menu { padding: 12px 0; flex: 1; }

        .menu-section-label {
            font-size: 0.68rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            opacity: 0.45;
            padding: 12px 20px 6px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: rgba(255,255,255,0.82);
            text-decoration: none;
            transition: all 0.2s;
            border-left: 3px solid transparent;
            font-size: 0.9rem;
        }

        .menu-item i { width: 18px; font-size: 0.95rem; flex-shrink: 0; text-align: center; }

        .menu-item:hover {
            background: rgba(255,255,255,0.1);
            color: white;
            text-decoration: none;
            border-left-color: rgba(255,255,255,0.5);
        }

        .menu-item.active {
            background: rgba(255,255,255,0.15);
            color: white;
            border-left-color: #fff;
            font-weight: 600;
        }

        .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid rgba(255,255,255,0.12);
            flex-shrink: 0;
        }

        .btn-logout {
            width: 100%;
            background: rgba(255,255,255,0.12);
            color: white;
            border: 1.5px solid rgba(255,255,255,0.3);
            padding: 9px 16px;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-logout:hover {
            background: rgba(255,255,255,0.22);
            border-color: rgba(255,255,255,0.5);
            color: white;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.45);
            z-index: 1040;
            backdrop-filter: blur(2px);
        }

        .sidebar-overlay.show { display: block; }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ===== TOPBAR ===== */
        .topbar {
            background: white;
            height: 64px;
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 1px 0 #e5e7eb;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar-left { display: flex; align-items: center; gap: 14px; }

        .hamburger-btn {
            display: none;
            background: none;
            border: none;
            color: #374151;
            font-size: 1.25rem;
            padding: 6px;
            cursor: pointer;
            border-radius: 6px;
            transition: background 0.15s;
        }

        .hamburger-btn:hover { background: #f3f4f6; }

        .topbar-breadcrumb { font-size: 0.8rem; color: #6b7280; margin-bottom: 2px; }
        .topbar-breadcrumb a { color: var(--green-main); text-decoration: none; }
        .topbar-breadcrumb a:hover { text-decoration: underline; }
        .topbar-title h2 { font-size: 1.1rem; font-weight: 700; color: #111827; margin: 0; }
        .topbar-title p { font-size: 0.75rem; color: #6b7280; margin: 0; }

        .topbar-actions { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }

        .btn-topbar {
            border: none;
            padding: 8px 14px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.8rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
            white-space: nowrap;
            cursor: pointer;
        }

        .btn-topbar:hover { transform: translateY(-1px); text-decoration: none; }

        .btn-topbar-back {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            color: white;
        }

        .btn-topbar-back:hover { box-shadow: 0 4px 12px rgba(220,38,38,0.35); color: white; }

        .btn-topbar-save {
            background: linear-gradient(135deg, var(--green-main), var(--green-dark));
            color: white;
        }

        .btn-topbar-save:hover { box-shadow: 0 4px 12px rgba(22,163,74,0.35); color: white; }

        /* ===== CONTENT AREA ===== */
        .content-area { padding: 20px 24px 32px; flex: 1; }

        /* ===== INFO CARD ===== */
        .info-card {
            background: white;
            border-radius: 14px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 10px rgba(0,0,0,0.04);
            overflow: hidden;
            margin-bottom: 16px;
        }

        .info-card-header {
            padding: 16px 20px;
            border-bottom: 1px solid #f3f4f6;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-card-icon {
            width: 34px; height: 34px;
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.9rem;
            flex-shrink: 0;
        }

        .icon-blue   { background: #eff6ff; color: #2563eb; }
        .icon-green  { background: #f0fdf4; color: var(--green-main); }
        .icon-orange { background: #fff7ed; color: #ea580c; }
        .icon-purple { background: #faf5ff; color: #7c3aed; }
        .icon-gray   { background: #f3f4f6; color: #6b7280; }

        .info-card-header h3 { font-size: 0.9rem; font-weight: 700; color: #111827; margin: 0; }
        .info-card-header p  { font-size: 0.75rem; color: #6b7280; margin: 0; }

        .info-card-body { padding: 20px; }

        /* ===== SECTION DIVIDER ===== */
        .section-divider {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #9ca3af;
            padding-bottom: 10px;
            border-bottom: 1px solid #f3f4f6;
            margin-bottom: 16px;
        }

        /* ===== FORM STYLES ===== */
        .form-label-custom {
            font-size: 0.78rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 5px;
            display: block;
        }

        .form-control-custom,
        .form-select-custom {
            font-size: 0.84rem;
            padding: 8px 12px;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            width: 100%;
            color: #111827;
            background: white;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }

        .form-control-custom:focus,
        .form-select-custom:focus {
            border-color: var(--green-main);
            box-shadow: 0 0 0 3px rgba(22,163,74,0.12);
        }

        .form-control-custom.readonly,
        .form-control-custom[readonly] {
            background: #f9fafb;
            color: #6b7280;
            cursor: not-allowed;
        }

        .form-control-custom.is-invalid,
        .form-select-custom.is-invalid {
            border-color: #dc2626;
        }

        .invalid-feedback-custom {
            font-size: 0.75rem;
            color: #dc2626;
            margin-top: 4px;
        }

        textarea.form-control-custom { resize: vertical; min-height: 80px; }

        .lock-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 0.7rem;
            color: #9ca3af;
            background: #f3f4f6;
            padding: 2px 8px;
            border-radius: 10px;
            margin-left: 8px;
            font-weight: 500;
        }

        /* ===== ORDER SUMMARY TABLE ===== */
        .summary-table { width: 100%; border-collapse: collapse; font-size: 0.83rem; }
        .summary-table td { padding: 7px 0; color: #374151; border-bottom: 1px solid #f9fafb; }
        .summary-table td:last-child { text-align: right; font-weight: 600; }
        .summary-table tfoot td {
            border-top: 2px solid #f3f4f6;
            border-bottom: none;
            font-weight: 700;
            color: var(--green-main);
            font-size: 0.9rem;
            padding-top: 10px;
        }

        /* ===== FADE IN ===== */
        .fade-in { animation: fadeIn 0.3s ease; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 900px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); box-shadow: 4px 0 24px rgba(0,0,0,0.2); }
            .main-content { margin-left: 0; }
            .hamburger-btn { display: flex; align-items: center; justify-content: center; }
        }

        @media (max-width: 767px) {
            .content-area { padding: 14px 14px 24px; }
            .topbar { padding: 0 14px; height: auto; min-height: 58px; flex-wrap: wrap; gap: 8px; padding-top: 10px; padding-bottom: 10px; }
            .topbar-actions .btn-topbar span { display: none; }
            .topbar-actions .btn-topbar { padding: 8px 10px; }
        }
    </style>
</head>
<body>

    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    <!-- ===== SIDEBAR ===== -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <span class="brand-icon">🌱</span>
            <h3>Bibit Cabai</h3>
            <p class="subtitle">Admin Dashboard</p>
            <span class="admin-name">{{ Auth::user()->name ?? 'Admin User' }}</span>
        </div>
        <div class="sidebar-menu">
            <div class="menu-section-label">Menu Utama</div>
            <a href="{{ route('admin.dashboard') }}" class="menu-item">
                <i class="fas fa-tachometer-alt"></i><span>Dashboard</span>
            </a>
            <a href="{{ route('admin.products.index') }}" class="menu-item">
                <i class="fas fa-seedling"></i><span>Kelola Produk</span>
            </a>
            <a href="{{ route('admin.orders') }}" class="menu-item active">
                <i class="fas fa-shopping-cart"></i><span>Pesanan</span>
            </a>
            <a href="{{ route('admin.cancellations') }}" class="menu-item">
                <i class="fas fa-times-circle"></i><span>Pengajuan Batal</span>
            </a>
            <a href="{{ route('admin.users') }}" class="menu-item">
                <i class="fas fa-users"></i><span>Pengguna</span>
            </a>
            <a href="{{ route('admin.laporan') }}" class="menu-item">
                <i class="fas fa-chart-line"></i><span>Laporan</span>
            </a>
        </div>
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i><span>Logout</span>
                </button>
            </form>
        </div>
    </nav>

    <!-- ===== MAIN ===== -->
    <div class="main-content">

        <!-- Topbar -->
        <header class="topbar">
            <div class="topbar-left">
                <button class="hamburger-btn" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="topbar-title">
                    <div class="topbar-breadcrumb">
                        <a href="{{ route('admin.dashboard') }}"><i class="fas fa-home"></i> Dashboard</a>
                        &nbsp;/&nbsp;<a href="{{ route('admin.orders') }}">Pesanan</a>
                        &nbsp;/&nbsp;<a href="{{ route('admin.transaksis.show', $transaksi->id) }}">{{ $transaksi->invoice_number }}</a>
                        &nbsp;/&nbsp;Edit
                    </div>
                    <h2>Edit Order</h2>
                    <p>{{ $transaksi->invoice_number }}</p>
                </div>
            </div>
            <div class="topbar-actions">
                <a href="{{ route('admin.transaksis.show', $transaksi->id) }}" class="btn-topbar btn-topbar-back">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali</span>
                </a>
                <button type="submit" form="editOrderForm" class="btn-topbar btn-topbar-save">
                    <i class="fas fa-save"></i>
                    <span>Simpan</span>
                </button>
            </div>
        </header>

        <!-- Content -->
        <div class="content-area fade-in">

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i><strong>Error!</strong>
                    <ul class="mb-0 mt-1">
                        @foreach ($errors->all() as $error)
                            <li style="font-size:0.85rem;">{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form id="editOrderForm" action="{{ route('admin.transaksis.update', $transaksi->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    <!-- ===== KOLOM KIRI (8/12) ===== -->
                    <div class="col-lg-8">

                        <!-- Order Details (editable) -->
                        <div class="info-card">
                            <div class="info-card-header">
                                <div class="info-card-icon icon-blue">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <div>
                                    <h3>Order Details</h3>
                                    <p>Status & informasi pesanan yang dapat diubah</p>
                                </div>
                            </div>
                            <div class="info-card-body">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="section-divider">Invoice</div>
                                        <div class="mb-3">
                                            <label class="form-label-custom">Invoice Number</label>
                                            <input type="text" class="form-control-custom readonly"
                                                   value="{{ $transaksi->invoice_number }}" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label-custom">Tanggal Order</label>
                                            <input type="text" class="form-control-custom readonly"
                                                   value="{{ $transaksi->created_at->format('d M Y, H:i') }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="section-divider">Update Status</div>
                                        <div class="mb-3">
                                            <label class="form-label-custom">Order Status <span style="color:#dc2626;">*</span></label>
                                            <select name="order_status" class="form-select-custom @error('order_status') is-invalid @enderror" required>
                                                @foreach(['pending','processing','shipped','delivered','cancelled'] as $s)
                                                    <option value="{{ $s }}" {{ old('order_status', $transaksi->order_status) == $s ? 'selected' : '' }}>
                                                        {{ ucfirst($s) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('order_status')
                                                <div class="invalid-feedback-custom">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label-custom">Payment Status <span style="color:#dc2626;">*</span></label>
                                            <select name="payment_status" class="form-select-custom @error('payment_status') is-invalid @enderror" required>
                                                @foreach(['pending','paid','failed'] as $p)
                                                    <option value="{{ $p }}" {{ old('payment_status', $transaksi->payment_status) == $p ? 'selected' : '' }}>
                                                        {{ ucfirst($p) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('payment_status')
                                                <div class="invalid-feedback-custom">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3 mt-1">
                                    <div class="col-md-6">
                                        <label class="form-label-custom">Tracking Number</label>
                                        <input type="text" name="tracking_number"
                                               class="form-control-custom @error('tracking_number') is-invalid @enderror"
                                               value="{{ old('tracking_number', $transaksi->tracking_number) }}"
                                               placeholder="Masukkan nomor resi...">
                                        @error('tracking_number')
                                            <div class="invalid-feedback-custom">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label-custom">Catatan / Notes</label>
                                        <textarea name="notes"
                                                  class="form-control-custom @error('notes') is-invalid @enderror"
                                                  placeholder="Catatan tambahan...">{{ old('notes', $transaksi->notes) }}</textarea>
                                        @error('notes')
                                            <div class="invalid-feedback-custom">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Customer Information (readonly) -->
                        <div class="info-card">
                            <div class="info-card-header">
                                <div class="info-card-icon icon-purple">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <h3>Customer Information</h3>
                                    <p>Informasi pembeli <span class="lock-badge"><i class="fas fa-lock"></i> Tidak dapat diubah</span></p>
                                </div>
                            </div>
                            <div class="info-card-body">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="form-label-custom">Nama Customer</label>
                                        <input type="text" class="form-control-custom" value="{{ $transaksi->customer_name }}" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label-custom">Telepon</label>
                                        <input type="text" class="form-control-custom" value="{{ $transaksi->customer_phone }}" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label-custom">Email</label>
                                        <input type="text" class="form-control-custom" value="{{ $transaksi->customer_email ?? '-' }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Information (readonly) -->
                        <div class="info-card">
                            <div class="info-card-header">
                                <div class="info-card-icon icon-orange">
                                    <i class="fas fa-truck"></i>
                                </div>
                                <div>
                                    <h3>Pengiriman</h3>
                                    <p>Alamat & info pengiriman <span class="lock-badge"><i class="fas fa-lock"></i> Tidak dapat diubah</span></p>
                                </div>
                            </div>
                            <div class="info-card-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label-custom">Alamat Pengiriman</label>
                                        <textarea class="form-control-custom" rows="3" readonly>{{ $transaksi->shipping_address }}</textarea>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label-custom">Kota</label>
                                        <input type="text" class="form-control-custom" value="{{ $transaksi->city }}" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label-custom">Provinsi</label>
                                        <input type="text" class="form-control-custom" value="{{ $transaksi->province }}" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label-custom">Kode Pos</label>
                                        <input type="text" class="form-control-custom" value="{{ $transaksi->postal_code }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- Akhir col-lg-8 -->

                    <!-- ===== KOLOM KANAN (4/12) ===== -->
                    <div class="col-lg-4">

                        <!-- Order Summary -->
                        <div class="info-card">
                            <div class="info-card-header">
                                <div class="info-card-icon icon-green">
                                    <i class="fas fa-box"></i>
                                </div>
                                <div>
                                    <h3>Order Summary</h3>
                                    <p>Ringkasan produk pesanan</p>
                                </div>
                            </div>
                            <div class="info-card-body">
                                <table class="summary-table">
                                    <tbody>
                                        @foreach($transaksi->details as $detail)
                                        <tr>
                                            <td>{{ $detail->product->name ?? 'N/A' }}</td>
                                            <td style="text-align:center;color:#9ca3af;">x{{ $detail->quantity }}</td>
                                            <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2" style="font-size:0.8rem;color:#6b7280;">Total Amount</td>
                                            <td style="color:var(--green-main);font-size:0.95rem;">Rp {{ number_format($transaksi->total_amount, 0, ',', '.') }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="info-card">
                            <div class="info-card-header">
                                <div class="info-card-icon icon-gray">
                                    <i class="fas fa-cog"></i>
                                </div>
                                <div>
                                    <h3>Aksi</h3>
                                    <p>Simpan atau batalkan perubahan</p>
                                </div>
                            </div>
                            <div class="info-card-body" style="display:flex;flex-direction:column;gap:10px;">
                                <button type="submit" form="editOrderForm"
                                        style="width:100%;padding:10px 16px;border-radius:9px;border:none;
                                               background:linear-gradient(135deg,var(--green-main),var(--green-dark));
                                               color:white;font-weight:700;font-size:0.875rem;cursor:pointer;
                                               display:flex;align-items:center;justify-content:center;gap:8px;
                                               transition:all 0.2s;">
                                    <i class="fas fa-save"></i> Update Order
                                </button>
                                <a href="{{ route('admin.transaksis.show', $transaksi->id) }}"
                                   style="width:100%;padding:10px 16px;border-radius:9px;
                                          border:1.5px solid #e5e7eb;background:#f9fafb;
                                          color:#374151;font-weight:600;font-size:0.875rem;cursor:pointer;
                                          display:flex;align-items:center;justify-content:center;gap:8px;
                                          text-decoration:none;transition:all 0.2s;">
                                    <i class="fas fa-times"></i> Batal
                                </a>
                            </div>
                        </div>

                    </div>
                    <!-- Akhir col-lg-4 -->

                </div>
            </form>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('sidebarOverlay').classList.toggle('show');
        }
        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('open');
            document.getElementById('sidebarOverlay').classList.remove('show');
        }
        window.addEventListener('resize', function() {
            if (window.innerWidth > 900) closeSidebar();
        });

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.alert').forEach(function(alert) {
                setTimeout(function() {
                    const a = bootstrap.Alert.getOrCreateInstance(alert);
                    if (a) a.close();
                }, 5000);
            });
        });
    </script>
</body>
</html>