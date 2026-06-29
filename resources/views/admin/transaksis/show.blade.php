<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - Bibit Cabai Admin</title>
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

        .btn-topbar-edit {
            background: linear-gradient(135deg, #ca8a04, #a16207);
            color: white;
        }

        .btn-topbar-edit:hover { box-shadow: 0 4px 12px rgba(202,138,4,0.35); color: white; }

        .btn-topbar-print {
            background: linear-gradient(135deg, #0891b2, #0e7490);
            color: white;
        }

        .btn-topbar-print:hover { box-shadow: 0 4px 12px rgba(8,145,178,0.35); color: white; }

        .btn-topbar-delete {
            background: #fef2f2;
            color: #dc2626;
            border: 1.5px solid #fecaca;
        }

        .btn-topbar-delete:hover { background: #fee2e2; color: #dc2626; }

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
            justify-content: space-between;
            gap: 10px;
        }

        .info-card-header-left { display: flex; align-items: center; gap: 10px; }

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
        .icon-yellow { background: #fefce8; color: #ca8a04; }

        .info-card-header h3 { font-size: 0.9rem; font-weight: 700; color: #111827; margin: 0; }
        .info-card-header p  { font-size: 0.75rem; color: #6b7280; margin: 0; }

        .info-card-body { padding: 20px; }

        /* ===== BADGES ===== */
        .badge-status {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.02em;
        }

        .badge-pending    { background: #fef9c3; color: #854d0e; }
        .badge-processing { background: #dbeafe; color: #1e40af; }
        .badge-shipped    { background: #e0f2fe; color: #0c4a6e; }
        .badge-delivered  { background: #dcfce7; color: #14532d; }
        .badge-cancelled  { background: #fee2e2; color: #991b1b; }
        .badge-paid       { background: #dcfce7; color: #14532d; }
        .badge-failed     { background: #fee2e2; color: #991b1b; }

        /* ===== INFO TABLE ===== */
        .detail-row {
            display: flex;
            padding: 8px 0;
            border-bottom: 1px solid #f9fafb;
            font-size: 0.84rem;
            gap: 12px;
        }

        .detail-row:last-child { border-bottom: none; }

        .detail-label {
            width: 140px;
            flex-shrink: 0;
            color: #6b7280;
            font-weight: 500;
        }

        .detail-value { color: #111827; flex: 1; }

        /* ===== STATUS UPDATE FORMS ===== */
        .status-update-group {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .status-update-item label {
            font-size: 0.78rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 5px;
            display: block;
        }

        .input-group-sm .form-select,
        .input-group-sm .form-control {
            font-size: 0.82rem;
            padding: 7px 10px;
            border: 1.5px solid #e5e7eb;
            border-radius: 0 8px 8px 0;
        }

        .input-group-sm .input-group-text {
            background: #f9fafb;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px 0 0 8px;
            font-size: 0.8rem;
            color: #6b7280;
            border-right: none;
        }

        .input-group-sm .btn {
            border-radius: 0 8px 8px 0;
            font-size: 0.8rem;
            padding: 7px 12px;
            border: 1.5px solid transparent;
        }

        /* ===== ORDER ITEMS TABLE ===== */
        .items-table { width: 100%; border-collapse: collapse; font-size: 0.84rem; }

        .items-table thead th {
            background: #f9fafb;
            padding: 10px 14px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280;
            border-bottom: 1px solid #f3f4f6;
        }

        .items-table tbody td {
            padding: 12px 14px;
            border-bottom: 1px solid #f9fafb;
            color: #374151;
        }

        .items-table tbody tr:last-child td { border-bottom: none; }

        .items-table tfoot td {
            padding: 12px 14px;
            border-top: 2px solid #f3f4f6;
            font-weight: 700;
            color: #111827;
        }

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
            .detail-label { width: 110px; }
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
                        &nbsp;/&nbsp;{{ $transaksi->invoice_number }}
                    </div>
                    <h2>Order Details</h2>
                    <p>{{ $transaksi->invoice_number }}</p>
                </div>
            </div>
            <div class="topbar-actions">
                <a href="{{ route('admin.orders') }}" class="btn-topbar btn-topbar-back">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali</span>
                </a>
                <a href="{{ route('admin.transaksis.edit', $transaksi->id) }}" class="btn-topbar btn-topbar-edit">
                    <i class="fas fa-edit"></i>
                    <span>Edit</span>
                </a>
                <a href="{{ route('admin.transaksis.print', $transaksi->id) }}" class="btn-topbar btn-topbar-print" target="_blank">
                    <i class="fas fa-print"></i>
                    <span>Print</span>
                </a>
                <!-- <button type="button" class="btn-topbar btn-topbar-delete"
                        data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <i class="fas fa-trash"></i>
                    <span>Hapus</span>
                </button> -->
            </div>
        </header>

        <!-- Content -->
        <div class="content-area fade-in">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row g-3">

                <!-- ===== KOLOM KIRI (8/12) ===== -->
                <div class="col-lg-8">

                    <!-- Order Information -->
                    <div class="info-card">
                        <div class="info-card-header">
                            <div class="info-card-header-left">
                                <div class="info-card-icon icon-blue">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <div>
                                    <h3>Order Information</h3>
                                    <p>Detail & status pesanan</p>
                                </div>
                            </div>
                            <span class="badge-status badge-{{ $transaksi->order_status }}">
                                {{ ucfirst($transaksi->order_status) }}
                            </span>
                        </div>
                        <div class="info-card-body">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="section-divider">Invoice Details</div>
                                    <div class="detail-row">
                                        <span class="detail-label">Invoice</span>
                                        <span class="detail-value"><strong>{{ $transaksi->invoice_number }}</strong></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label">Tanggal Order</span>
                                        <span class="detail-value">{{ $transaksi->created_at->format('d M Y, H:i') }}</span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label">Payment Status</span>
                                        <span class="detail-value">
                                            <span class="badge-status badge-{{ $transaksi->payment_status }}">
                                                {{ ucfirst($transaksi->payment_status) }}
                                            </span>
                                        </span>
                                    </div>
                                    @if($transaksi->tracking_number)
                                    <div class="detail-row">
                                        <span class="detail-label">Tracking No.</span>
                                        <span class="detail-value"><code style="background:#f3f4f6;padding:2px 6px;border-radius:4px;font-size:0.8rem;">{{ $transaksi->tracking_number }}</code></span>
                                    </div>
                                    @endif
                                </div>

                                <div class="col-md-6">
                                    <div class="section-divider">Update Status</div>
                                    <div class="status-update-group">
                                        <div class="status-update-item">
                                            <label>Order Status</label>
                                            <form action="{{ route('admin.transaksis.update-status', $transaksi->id) }}" method="POST">
                                                @csrf
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text"><i class="fas fa-box"></i></span>
                                                    <select name="order_status" class="form-select" id="order_status">
                                                        @foreach(['pending','processing','shipped','delivered','cancelled'] as $s)
                                                            <option value="{{ $s }}" {{ $transaksi->order_status == $s ? 'selected' : '' }}>
                                                                {{ ucfirst($s) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <button type="submit" class="btn btn-primary" style="border-radius:0 8px 8px 0;">
                                                        <i class="fas fa-save"></i>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="status-update-item">
                                            <label>Payment Status</label>
                                            <form action="{{ route('admin.transaksis.update-payment-status', $transaksi->id) }}" method="POST">
                                                @csrf
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text"><i class="fas fa-credit-card"></i></span>
                                                    <select name="payment_status" class="form-select" id="payment_status">
                                                        @foreach(['pending','paid','failed'] as $p)
                                                            <option value="{{ $p }}" {{ $transaksi->payment_status == $p ? 'selected' : '' }}>
                                                                {{ ucfirst($p) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <button type="submit" class="btn btn-success" style="border-radius:0 8px 8px 0;">
                                                        <i class="fas fa-save"></i>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="info-card">
                        <div class="info-card-header">
                            <div class="info-card-header-left">
                                <div class="info-card-icon icon-green">
                                    <i class="fas fa-box"></i>
                                </div>
                                <div>
                                    <h3>Order Items</h3>
                                    <p>Daftar produk dalam pesanan</p>
                                </div>
                            </div>
                        </div>
                        <div style="overflow-x:auto;">
                            <table class="items-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Produk</th>
                                        <th class="text-center">Harga</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transaksi->details as $index => $detail)
                                    <tr>
                                        <td style="color:#9ca3af;font-size:0.78rem;">{{ $index + 1 }}</td>
                                        <td><strong>{{ $detail->product->name ?? 'Product Not Found' }}</strong></td>
                                        <td class="text-center">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            <span style="background:#f3f4f6;padding:2px 10px;border-radius:12px;font-size:0.8rem;font-weight:600;">
                                                {{ $detail->quantity }}
                                            </span>
                                        </td>
                                        <td class="text-end" style="font-weight:600;">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4" class="text-end" style="color:#6b7280;font-size:0.84rem;">Total Amount</td>
                                        <td class="text-end" style="color:var(--green-main);font-size:1rem;">
                                            Rp {{ number_format($transaksi->total_amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                </div>
                <!-- Akhir col-lg-8 -->

                <!-- ===== KOLOM KANAN (4/12) ===== -->
                <div class="col-lg-4">

                    <!-- Customer Information -->
                    <div class="info-card">
                        <div class="info-card-header">
                            <div class="info-card-header-left">
                                <div class="info-card-icon icon-purple">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <h3>Customer</h3>
                                    <p>Informasi pembeli</p>
                                </div>
                            </div>
                        </div>
                        <div class="info-card-body">
                            <div class="detail-row">
                                <span class="detail-label"><i class="fas fa-user text-success me-1" style="font-size:0.75rem;"></i> Nama</span>
                                <span class="detail-value">{{ $transaksi->customer_name }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label"><i class="fas fa-phone text-success me-1" style="font-size:0.75rem;"></i> Telepon</span>
                                <span class="detail-value">{{ $transaksi->customer_phone }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label"><i class="fas fa-envelope text-success me-1" style="font-size:0.75rem;"></i> Email</span>
                                <span class="detail-value">{{ $transaksi->customer_email ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Information -->
                    <div class="info-card">
                        <div class="info-card-header">
                            <div class="info-card-header-left">
                                <div class="info-card-icon icon-orange">
                                    <i class="fas fa-truck"></i>
                                </div>
                                <div>
                                    <h3>Pengiriman</h3>
                                    <p>Alamat & info pengiriman</p>
                                </div>
                            </div>
                        </div>
                        <div class="info-card-body">
                            <p style="font-size:0.75rem;font-weight:600;color:#9ca3af;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:6px;">Alamat Pengiriman</p>
                            <address style="font-size:0.84rem;color:#374151;line-height:1.7;margin-bottom:12px;font-style:normal;">
                                {{ $transaksi->shipping_address }}<br>
                                {{ $transaksi->city }}, {{ $transaksi->province }}<br>
                                {{ $transaksi->postal_code }}
                            </address>
                            @if($transaksi->shipping_method)
                            <div class="detail-row">
                                <span class="detail-label">Metode</span>
                                <span class="detail-value">{{ $transaksi->shipping_method }}</span>
                            </div>
                            @endif
                            @if($transaksi->tracking_number)
                            <div class="detail-row">
                                <span class="detail-label">Tracking</span>
                                <span class="detail-value"><code style="background:#f3f4f6;padding:2px 6px;border-radius:4px;font-size:0.78rem;">{{ $transaksi->tracking_number }}</code></span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Notes -->
                    @if($transaksi->notes)
                    <div class="info-card">
                        <div class="info-card-header">
                            <div class="info-card-header-left">
                                <div class="info-card-icon icon-yellow">
                                    <i class="fas fa-sticky-note"></i>
                                </div>
                                <div>
                                    <h3>Catatan</h3>
                                    <p>Catatan tambahan pesanan</p>
                                </div>
                            </div>
                        </div>
                        <div class="info-card-body">
                            <p style="font-size:0.84rem;color:#374151;margin:0;">{{ $transaksi->notes }}</p>
                        </div>
                    </div>
                    @endif
{{-- Bukti Pembayaran & Tombol Approve --}}
                    @if($transaksi->payment_method !== 'cod')
                    <div class="info-card">
                        <div class="info-card-header">
                            <div class="info-card-header-left">
                                <div class="info-card-icon icon-green">
                                    <i class="fas fa-receipt"></i>
                                </div>
                                <div>
                                    <h3>Bukti Pembayaran</h3>
                                    <p>Konfirmasi pembayaran pembeli</p>
                                </div>
                            </div>
                        </div>
                        <div class="info-card-body">
                            @if($transaksi->payment_proof)
                                <img src="{{ Storage::url($transaksi->payment_proof) }}" 
                                     alt="Bukti Bayar" 
                                     class="img-fluid rounded mb-3 w-100"
                                     style="max-height:350px;object-fit:contain;border:1px solid #e5e7eb;">

                                @if($transaksi->payment_status === 'paid')
                                    <div class="alert alert-success mb-0" style="font-size:0.84rem;">
                                        <i class="fas fa-check-circle me-2"></i>
                                        Dikonfirmasi lunas pada 
                                        <strong>{{ $transaksi->confirmed_at ? \Carbon\Carbon::parse($transaksi->confirmed_at)->format('d M Y H:i') : '-' }}</strong>
                                    </div>
                                @elseif($transaksi->payment_status === 'waiting_confirmation')
                                    <div class="alert alert-warning mb-3" style="font-size:0.84rem;">
                                        <i class="fas fa-clock me-2"></i>
                                        Menunggu konfirmasi admin
                                    </div>
                                    <form action="{{ route('admin.transaksis.approve', $transaksi->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" 
                                                class="btn btn-success w-100 fw-bold"
                                                onclick="return confirm('Konfirmasi pembayaran ini sebagai LUNAS?')"
                                                style="border-radius:8px;">
                                            <i class="fas fa-check-circle me-2"></i>Approve — Tandai Lunas
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.transaksis.approve', $transaksi->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" 
                                                class="btn btn-success w-100 fw-bold"
                                                onclick="return confirm('Konfirmasi pembayaran ini sebagai LUNAS?')"
                                                style="border-radius:8px;">
                                            <i class="fas fa-check-circle me-2"></i>Approve — Tandai Lunas
                                        </button>
                                    </form>
                                @endif
                            @else
                                <div class="text-center py-3" style="color:#9ca3af;">
                                    <i class="fas fa-image fa-2x mb-2 d-block"></i>
                                    <span style="font-size:0.84rem;">Pembeli belum upload bukti pembayaran</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
                <!-- Akhir col-lg-4 -->

            </div>
        </div>
    </div>

    <!-- ===== DELETE MODAL ===== -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border:none;border-radius:14px;overflow:hidden;">
                <div class="modal-header" style="background:#dc2626;color:white;border:none;">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Hapus
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding:24px;">
                    <p style="color:#374151;margin-bottom:8px;">Apakah Anda yakin ingin menghapus pesanan ini?</p>
                    <p style="font-weight:700;color:#111827;margin-bottom:12px;">Invoice: {{ $transaksi->invoice_number }}</p>
                    <p style="color:#dc2626;font-size:0.84rem;margin:0;">
                        <i class="fas fa-exclamation-triangle me-1"></i> Tindakan ini tidak dapat dibatalkan!
                    </p>
                </div>
                <div class="modal-footer" style="border:none;padding:16px 24px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('admin.transaksis.destroy', $transaksi->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-1"></i> Hapus Pesanan
                        </button>
                    </form>
                </div>
            </div>
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

        document.getElementById('order_status').addEventListener('change', function () {
            if (confirm('Update order status to "' + this.value + '"?')) {
                this.form.submit();
            } else {
                this.form.reset();
            }
        });

        document.getElementById('payment_status').addEventListener('change', function () {
            if (confirm('Update payment status to "' + this.value + '"?')) {
                this.form.submit();
            } else {
                this.form.reset();
            }
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