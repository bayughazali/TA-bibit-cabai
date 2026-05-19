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
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f8f9fa; }
        .admin-container { display: flex; min-height: 100vh; }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, #28a745, #20c997);
            color: white;
            min-height: 100vh;
            position: fixed;
            top: 0; left: 0;
            z-index: 1000;
        }
        .sidebar-header {
            padding: 25px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar-header h3 { font-size: 1.8rem; font-weight: bold; margin-bottom: 5px; }
        .sidebar-header p { opacity: 0.8; font-size: 0.9rem; }
        .sidebar-menu { padding: 20px 0; }
        .menu-item {
            display: block;
            padding: 15px 25px;
            color: white;
            text-decoration: none;
            transition: all 0.3s;
            border-left: 4px solid transparent;
        }
        .menu-item:hover, .menu-item.active {
            background: rgba(255,255,255,0.2);
            color: white;
            text-decoration: none;
            border-left-color: #fff;
            transform: translateX(5px);
        }
        .menu-item i { width: 20px; margin-right: 10px; }

        /* ===== MAIN CONTENT ===== */
        .main-content { margin-left: 280px; flex: 1; min-height: 100vh; }
        .admin-header {
            background: white;
            padding: 20px 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-bottom: 1px solid #dee2e6;
        }
        .content-area { padding: 30px; }
        .breadcrumb { background: transparent; padding: 0; margin-bottom: 10px; }
        .breadcrumb-item a { color: #28a745; text-decoration: none; }

        /* ===== BACK BUTTON ===== */
        .back-button {
            position: fixed;
            top: 20px; right: 20px;
            z-index: 1100;
            background: linear-gradient(45deg, #dc3545, #c82333);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
        }
        .back-button:hover {
            background: linear-gradient(45deg, #c82333, #a71e2a);
            color: white;
            transform: translateY(-2px);
        }

        /* ===== FADE IN ===== */
        .fade-in { animation: fadeIn 0.3s ease-in-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ===== CARD ===== */
        .card { border: none; border-radius: 12px; }
        .card-header {
            background: #f8f9fa !important;
            border-radius: 12px 12px 0 0 !important;
            border-bottom: 1px solid #dee2e6;
        }

        /* ===== BADGES ===== */
        .badge-status {
            padding: 5px 12px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .badge-pending    { background: #fff3cd; color: #856404; }
        .badge-processing { background: #d1ecf1; color: #0c5460; }
        .badge-shipped    { background: #cce5ff; color: #004085; }
        .badge-delivered  { background: #d4edda; color: #155724; }
        .badge-cancelled  { background: #f8d7da; color: #721c24; }
        .badge-paid       { background: #d4edda; color: #155724; }
        .badge-failed     { background: #f8d7da; color: #721c24; }

        /* ===== INFO TABLE ===== */
        .info-table td { padding: 6px 0; border: none; vertical-align: top; }
        .info-table td:first-child { font-weight: 600; width: 40%; color: #495057; }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .sidebar { width: 100%; position: relative; }
            .main-content { margin-left: 0; }
        }
    </style>
</head>

<body>

{{-- Back to Website --}}
<a href="{{ route('home') }}" class="back-button">
    <i class="fas fa-arrow-left me-2"></i>Kembali ke Website
</a>

<div class="admin-container">

    {{-- ===== SIDEBAR ===== --}}
    <nav class="sidebar">
        <div class="sidebar-header">
            <h3>🌱 Bibit Cabai</h3>
            <p>Admin Dashboard</p>
            <small class="text-light">{{ Auth::user()->name ?? 'Admin User' }}</small>
        </div>
        <div class="sidebar-menu">
            <a href="{{ route('admin.dashboard') }}" class="menu-item">
                <i class="fas fa-tachometer-alt"></i>Dashboard
            </a>
            <a href="{{ route('admin.products.index') }}" class="menu-item">
                <i class="fas fa-seedling"></i>Kelola Produk
            </a>
            <a href="{{ route('admin.orders') }}" class="menu-item active">
                <i class="fas fa-shopping-cart"></i>Pesanan
            </a>
            <a href="{{ route('admin.cancellations') }}" class="menu-item">
                <i class="fas fa-times-circle"></i>Pengajuan Batal
            </a>
            <a href="{{ route('admin.users') }}" class="menu-item">
                <i class="fas fa-users"></i>Pengguna
            </a>
            <a href="{{ route('admin.laporan') }}" class="menu-item">
                <i class="fas fa-chart-line"></i>Laporan
            </a>
            <!-- <a href="{{ route('admin.settings') }}" class="menu-item">
                <i class="fas fa-cog"></i>Pengaturan
            </a> -->
            <div class="mt-4 px-3">
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm w-100">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    {{-- ===== MAIN CONTENT ===== --}}
    <div class="main-content">

        {{-- Header --}}
        <div class="admin-header">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="fas fa-home"></i> Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.orders') }}">Pesanan</a>
                    </li>
                    <li class="breadcrumb-item active">{{ $transaksi->invoice_number }}</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2>Order Details</h2>
                    <p class="mb-0 text-muted">{{ $transaksi->invoice_number }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.orders') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back to Orders
                    </a>
                    <a href="{{ route('admin.transaksis.edit', $transaksi->id) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                    <a href="{{ route('admin.transaksis.print', $transaksi->id) }}" class="btn btn-info btn-sm" target="_blank">
                        <i class="fas fa-print me-1"></i> Print Invoice
                    </a>
                    <button type="button" class="btn btn-danger btn-sm"
                            data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="fas fa-trash me-1"></i> Delete
                    </button>
                </div>
            </div>
        </div>

        {{-- Content --}}
        <div class="content-area fade-in">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">

                {{-- ===== KOLOM KIRI (8/12) ===== --}}
                <div class="col-lg-8">

                    {{-- Order Information & Status --}}
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 fw-bold text-primary">
                                <i class="fas fa-file-alt me-2"></i>Order Information
                            </h6>
                            <span class="badge-status badge-{{ $transaksi->order_status }}">
                                {{ ucfirst($transaksi->order_status) }}
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                {{-- Invoice Details --}}
                                <div class="col-md-6">
                                    <h6 class="text-primary mb-3">Invoice Details</h6>
                                    <table class="info-table table table-sm table-borderless">
                                        <tr>
                                            <td>Invoice Number:</td>
                                            <td><strong>{{ $transaksi->invoice_number }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Order Date:</td>
                                            <td>{{ $transaksi->created_at->format('d M Y, H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <td>Payment Status:</td>
                                            <td>
                                                <span class="badge-status badge-{{ $transaksi->payment_status }}">
                                                    {{ ucfirst($transaksi->payment_status) }}
                                                </span>
                                            </td>
                                        </tr>
                                        @if($transaksi->tracking_number)
                                        <tr>
                                            <td>Tracking Number:</td>
                                            <td><code>{{ $transaksi->tracking_number }}</code></td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>

                                {{-- Update Status --}}
                                <div class="col-md-6">
                                    <h6 class="text-primary mb-3">Update Status</h6>

                                    <form action="{{ route('admin.transaksis.update-status', $transaksi->id) }}" method="POST" class="mb-3">
                                        @csrf
                                        <label class="form-label fw-semibold small">Order Status</label>
                                        <div class="input-group input-group-sm">
                                            <select name="order_status" class="form-select" id="order_status">
                                                @foreach(['pending','processing','shipped','delivered','cancelled'] as $s)
                                                    <option value="{{ $s }}" {{ $transaksi->order_status == $s ? 'selected' : '' }}>
                                                        {{ ucfirst($s) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save"></i>
                                            </button>
                                        </div>
                                    </form>

                                    <form action="{{ route('admin.transaksis.update-payment-status', $transaksi->id) }}" method="POST">
                                        @csrf
                                        <label class="form-label fw-semibold small">Payment Status</label>
                                        <div class="input-group input-group-sm">
                                            <select name="payment_status" class="form-select" id="payment_status">
                                                @foreach(['pending','paid','failed'] as $p)
                                                    <option value="{{ $p }}" {{ $transaksi->payment_status == $p ? 'selected' : '' }}>
                                                        {{ ucfirst($p) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-save"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Order Items --}}
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 fw-bold text-primary">
                                <i class="fas fa-box me-2"></i>Order Items
                            </h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Product</th>
                                            <th class="text-center">Price</th>
                                            <th class="text-center">Qty</th>
                                            <th class="text-end">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($transaksi->details as $index => $detail)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td><strong>{{ $detail->product->name ?? 'Product Not Found' }}</strong></td>
                                            <td class="text-center">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                                            <td class="text-center">{{ $detail->quantity }}</td>
                                            <td class="text-end">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <th colspan="4" class="text-end">Total Amount:</th>
                                            <th class="text-end text-success">
                                                Rp {{ number_format($transaksi->total_amount, 0, ',', '.') }}
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                {{-- Akhir col-lg-8 --}}

                {{-- ===== KOLOM KANAN (4/12) ===== --}}
                <div class="col-lg-4">

                    {{-- Customer Information --}}
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 fw-bold text-primary">
                                <i class="fas fa-user me-2"></i>Customer Information
                            </h6>
                        </div>
                        <div class="card-body">
                            <table class="info-table table table-sm table-borderless">
                                <tr>
                                    <td><i class="fas fa-user text-success me-1"></i> Name:</td>
                                    <td>{{ $transaksi->customer_name }}</td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-phone text-success me-1"></i> Phone:</td>
                                    <td>{{ $transaksi->customer_phone }}</td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-envelope text-success me-1"></i> Email:</td>
                                    <td>{{ $transaksi->customer_email ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    {{-- Shipping Information --}}
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 fw-bold text-primary">
                                <i class="fas fa-truck me-2"></i>Shipping Information
                            </h6>
                        </div>
                        <div class="card-body">
                            <p class="fw-semibold small text-muted mb-1">Shipping Address</p>
                            <address class="mb-3" style="line-height:1.7;">
                                {{ $transaksi->shipping_address }}<br>
                                {{ $transaksi->city }}, {{ $transaksi->province }}<br>
                                {{ $transaksi->postal_code }}
                            </address>

                            @if($transaksi->shipping_method)
                                <p class="fw-semibold small text-muted mb-1">Shipping Method</p>
                                <p class="mb-3">{{ $transaksi->shipping_method }}</p>
                            @endif

                            @if($transaksi->tracking_number)
                                <p class="fw-semibold small text-muted mb-1">Tracking Number</p>
                                <p class="mb-0"><code class="bg-light px-2 py-1 rounded">{{ $transaksi->tracking_number }}</code></p>
                            @endif
                        </div>
                    </div>

                    {{-- Notes --}}
                    @if($transaksi->notes)
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 fw-bold text-primary">
                                <i class="fas fa-sticky-note me-2"></i>Notes
                            </h6>
                        </div>
                        <div class="card-body">
                            <p class="mb-0">{{ $transaksi->notes }}</p>
                        </div>
                    </div>
                    @endif

                </div>
                {{-- Akhir col-lg-4 --}}

            </div>
        </div>
        {{-- Akhir content-area --}}

    </div>
    {{-- Akhir main-content --}}

</div>
{{-- Akhir admin-container --}}

{{-- ===== DELETE MODAL ===== --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirm Delete
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this order?</p>
                <p><strong>Invoice: {{ $transaksi->invoice_number }}</strong></p>
                <p class="text-danger mb-0">
                    <i class="fas fa-exclamation-triangle me-1"></i> This action cannot be undone!
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.transaksis.destroy', $transaksi->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i> Delete Order
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Auto-confirm saat status berubah
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
</script>
</body>
</html>