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
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f8f9fa; }
        .admin-container { display: flex; min-height: 100vh; }

        .sidebar {
            width: 280px; background: linear-gradient(180deg, #28a745, #20c997);
            color: white; min-height: 100vh; position: fixed; top: 0; left: 0; z-index: 1000;
        }
        .sidebar-header {
            padding: 25px 20px; text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar-header h3 { font-size: 1.8rem; font-weight: bold; margin-bottom: 5px; }
        .sidebar-header p { opacity: 0.8; font-size: 0.9rem; }
        .sidebar-menu { padding: 20px 0; }
        .menu-item {
            display: block; padding: 15px 25px; color: white;
            text-decoration: none; transition: all 0.3s; border-left: 4px solid transparent;
        }
        .menu-item:hover, .menu-item.active {
            background: rgba(255,255,255,0.2); color: white;
            border-left-color: #fff; transform: translateX(5px); text-decoration: none;
        }
        .menu-item i { width: 20px; margin-right: 10px; }

        .main-content { margin-left: 280px; flex: 1; min-height: 100vh; }
        .admin-header {
            background: white; padding: 20px 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-bottom: 1px solid #dee2e6;
        }
        .content-area { padding: 30px; }
        .breadcrumb { background: transparent; padding: 0; margin-bottom: 10px; }
        .breadcrumb-item a { color: #28a745; text-decoration: none; }

        .back-button {
            position: fixed; top: 20px; right: 20px; z-index: 1100;
            background: linear-gradient(45deg, #dc3545, #c82333);
            color: white; border: none; padding: 12px 20px;
            border-radius: 25px; font-weight: 600; text-decoration: none; transition: all 0.3s;
        }
        .back-button:hover { color: white; transform: translateY(-2px); }

        .fade-in { animation: fadeIn 0.3s ease-in-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            .sidebar { width: 100%; position: relative; }
            .main-content { margin-left: 0; }
        }
    </style>
</head>

<body>

<a href="{{ route('home') }}" class="back-button">
    <i class="fas fa-arrow-left me-2"></i>Kembali ke Website
</a>

<div class="admin-container">

    {{-- Sidebar --}}
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
                <i class="fas fa-cog"></i>Pengaturan -->
            </a>
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

    {{-- Main Content --}}
    <div class="main-content">
        <div class="admin-header">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fas fa-home"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.orders') }}">Pesanan</a></li>
                    <li class="breadcrumb-item active">Edit {{ $transaksi->invoice_number }}</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2>Edit Order</h2>
                    <p class="mb-0">{{ $transaksi->invoice_number }}</p>
                </div>
                <a href="{{ route('admin.transaksis.show', $transaksi->id) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        <div class="content-area fade-in">

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <strong>Error!</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('admin.transaksis.update', $transaksi->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">

                    {{-- Kolom Kiri --}}
                    <div class="col-lg-8">

                        {{-- Order Details --}}
                        <div class="card shadow mb-4" style="border-radius:12px;">
                            <div class="card-header py-3" style="background:#f8f9fa; border-radius:12px 12px 0 0;">
                                <h6 class="m-0 fw-bold text-primary">Order Details</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Invoice Number</label>
                                    <input type="text" class="form-control bg-light"
                                           value="{{ $transaksi->invoice_number }}" readonly>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Order Status <span class="text-danger">*</span></label>
                                            <select class="form-select @error('order_status') is-invalid @enderror"
                                                    name="order_status" required>
                                                @foreach(['pending','processing','shipped','delivered','cancelled'] as $s)
                                                    <option value="{{ $s }}"
                                                        {{ old('order_status', $transaksi->order_status) == $s ? 'selected' : '' }}>
                                                        {{ ucfirst($s) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('order_status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Payment Status <span class="text-danger">*</span></label>
                                            <select class="form-select @error('payment_status') is-invalid @enderror"
                                                    name="payment_status" required>
                                                @foreach(['pending','paid','failed'] as $p)
                                                    <option value="{{ $p }}"
                                                        {{ old('payment_status', $transaksi->payment_status) == $p ? 'selected' : '' }}>
                                                        {{ ucfirst($p) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('payment_status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Tracking Number</label>
                                    <input type="text" class="form-control @error('tracking_number') is-invalid @enderror"
                                           name="tracking_number"
                                           value="{{ old('tracking_number', $transaksi->tracking_number) }}"
                                           placeholder="Enter tracking number">
                                    @error('tracking_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Notes</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror"
                                              name="notes" rows="3"
                                              placeholder="Enter any notes...">{{ old('notes', $transaksi->notes) }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Customer Information (readonly) --}}
                        <div class="card shadow mb-4" style="border-radius:12px;">
                            <div class="card-header py-3" style="background:#f8f9fa; border-radius:12px 12px 0 0;">
                                <h6 class="m-0 fw-bold text-primary">
                                    Customer Information
                                    <small class="text-muted fw-normal ms-2" style="font-size:0.75rem;">
                                        <i class="fas fa-lock"></i> Tidak dapat diubah
                                    </small>
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Customer Name</label>
                                    <input type="text" class="form-control bg-light"
                                           value="{{ $transaksi->customer_name }}" readonly>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Phone</label>
                                            <input type="text" class="form-control bg-light"
                                                   value="{{ $transaksi->customer_phone }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Email</label>
                                            <input type="text" class="form-control bg-light"
                                                   value="{{ $transaksi->customer_email ?? '-' }}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Shipping Information (readonly) --}}
                        <div class="card shadow mb-4" style="border-radius:12px;">
                            <div class="card-header py-3" style="background:#f8f9fa; border-radius:12px 12px 0 0;">
                                <h6 class="m-0 fw-bold text-primary">
                                    Shipping Information
                                    <small class="text-muted fw-normal ms-2" style="font-size:0.75rem;">
                                        <i class="fas fa-lock"></i> Tidak dapat diubah
                                    </small>
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Shipping Address</label>
                                    <textarea class="form-control bg-light" rows="3"
                                              readonly>{{ $transaksi->shipping_address }}</textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">City</label>
                                            <input type="text" class="form-control bg-light"
                                                   value="{{ $transaksi->city }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Province</label>
                                            <input type="text" class="form-control bg-light"
                                                   value="{{ $transaksi->province }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Postal Code</label>
                                            <input type="text" class="form-control bg-light"
                                                   value="{{ $transaksi->postal_code }}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    {{-- Akhir col-lg-8 --}}

                    {{-- Kolom Kanan --}}
                    <div class="col-lg-4">

                        {{-- Order Summary --}}
                        <div class="card shadow mb-4" style="border-radius:12px;">
                            <div class="card-header py-3" style="background:#f8f9fa; border-radius:12px 12px 0 0;">
                                <h6 class="m-0 fw-bold text-primary">Order Summary</h6>
                            </div>
                            <div class="card-body">
                                <h5 class="text-primary">{{ $transaksi->invoice_number }}</h5>
                                <p class="text-muted mb-3">{{ $transaksi->created_at->format('d M Y, H:i') }}</p>
                                <table class="table table-sm table-borderless">
                                    <tbody>
                                        @foreach($transaksi->details as $detail)
                                        <tr>
                                            <td>{{ $detail->product->name ?? 'N/A' }}</td>
                                            <td class="text-end">x{{ $detail->quantity }}</td>
                                            <td class="text-end">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="border-top">
                                        <tr>
                                            <th colspan="2">Total:</th>
                                            <th class="text-end">Rp {{ number_format($transaksi->total_amount, 0, ',', '.') }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="card shadow" style="border-radius:12px;">
                            <div class="card-body">
                                <button type="submit" class="btn btn-primary w-100 mb-2">
                                    <i class="fas fa-save me-1"></i> Update Order
                                </button>
                                <a href="{{ route('admin.transaksis.show', $transaksi->id) }}"
                                   class="btn btn-secondary w-100">
                                    <i class="fas fa-times me-1"></i> Cancel
                                </a>
                            </div>
                        </div>

                    </div>
                    {{-- Akhir col-lg-4 --}}

                </div>
            </form>

        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>