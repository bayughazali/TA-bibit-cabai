    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Orders Management - Bibit Cabai Admin</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        
        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background: #f8f9fa;
            }
            
            .admin-container {
                display: flex;
                min-height: 100vh;
            }
            
            /* Sidebar */
            .sidebar {
                width: 280px;
                background: linear-gradient(180deg, #28a745, #20c997);
                color: white;
                min-height: 100vh;
                position: fixed;
                top: 0;
                left: 0;
                z-index: 1000;
            }
            
            .sidebar-header {
                padding: 25px 20px;
                text-align: center;
                border-bottom: 1px solid rgba(255,255,255,0.1);
            }
            
            .sidebar-header h3 {
                font-size: 1.8rem;
                font-weight: bold;
                margin-bottom: 5px;
            }
            
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
            
            /* Main Content */
            .main-content {
                margin-left: 280px;
                flex: 1;
                min-height: 100vh;
            }
            
            .admin-header {
                background: white;
                padding: 20px 30px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                border-bottom: 1px solid #dee2e6;
            }
            
            .content-area { padding: 30px; }
            
            .page-header {
                background: white;
                padding: 30px;
                border-radius: 12px;
                box-shadow: 0 4px 6px rgba(0,0,0,0.05);
                margin-bottom: 30px;
            }
            
            .breadcrumb { background: transparent; padding: 0; margin-bottom: 10px; }
            .breadcrumb-item a { color: #28a745; text-decoration: none; }
            
            /* Stats Cards */
            .stats-grid {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 20px;
                margin-bottom: 30px;
            }
            
            .stat-card {
                background: white;
                border-radius: 12px;
                padding: 20px;
                box-shadow: 0 4px 6px rgba(0,0,0,0.05);
                display: flex;
                justify-content: space-between;
                align-items: center;
                border-left: 4px solid;
            }
            
            .stat-card.primary { border-left-color: #007bff; }
            .stat-card.warning { border-left-color: #ffc107; }
            .stat-card.info    { border-left-color: #17a2b8; }
            .stat-card.success { border-left-color: #28a745; }
            
            .stat-label {
                font-size: 0.75rem;
                font-weight: 700;
                text-transform: uppercase;
                margin-bottom: 5px;
            }
            
            .stat-card.primary .stat-label { color: #007bff; }
            .stat-card.warning .stat-label { color: #ffc107; }
            .stat-card.info    .stat-label { color: #17a2b8; }
            .stat-card.success .stat-label { color: #28a745; }
            
            .stat-value { font-size: 1.5rem; font-weight: 700; color: #333; }
            .stat-icon { font-size: 2rem; color: #dee2e6; }
            
            /* Table */
            .data-table {
                width: 100%;
                border-collapse: collapse;
                background: white;
            }
            
            .data-table th, .data-table td {
                padding: 12px 15px;
                text-align: left;
                border-bottom: 1px solid #dee2e6;
            }
            
            .data-table th {
                background: #f8f9fa;
                font-weight: 600;
                color: #495057;
                font-size: 0.85rem;
            }
            
            .data-table tbody tr:hover { background-color: #f8f9fc; }
            
            /* Badges */
            .badge-status {
                padding: 4px 10px;
                border-radius: 12px;
                font-size: 0.75rem;
                font-weight: 600;
            }
            
            .badge-pending   { background: #fff3cd; color: #856404; }
            .badge-processing { background: #d1ecf1; color: #0c5460; }
            .badge-shipped   { background: #cce5ff; color: #004085; }
            .badge-delivered { background: #d4edda; color: #155724; }
            .badge-cancelled { background: #f8d7da; color: #721c24; }
            .badge-paid      { background: #d4edda; color: #155724; }
            .badge-failed    { background: #f8d7da; color: #721c24; }
            
            /* Action Buttons */
            .btn-action {
                padding: 6px 10px;
                border: none;
                border-radius: 6px;
                font-size: 0.8rem;
                cursor: pointer;
                transition: all 0.3s;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
            }
            
            .btn-action:hover {
                transform: translateY(-1px);
                box-shadow: 0 2px 4px rgba(0,0,0,0.2);
                text-decoration: none;
                color: inherit;
            }
            
            .btn-view  { background: #17a2b8; color: white; }
            .btn-edit  { background: #ffc107; color: #212529; }
            .btn-print { background: #6c757d; color: white; }
            
            /* Section Header */
            .section-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 20px;
                padding-bottom: 15px;
                border-bottom: 2px solid #f8f9fa;
            }
            
            .section-title { font-size: 1.3rem; font-weight: 600; color: #333; }
            
            /* Back Button */
            .back-button {
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 1100;
                background: linear-gradient(45deg, #dc3545, #c82333);
                color: white;
                border: none;
                padding: 12px 20px;
                border-radius: 25px;
                font-weight: 600;
                transition: all 0.3s;
                text-decoration: none;
            }
            
            .back-button:hover {
                background: linear-gradient(45deg, #c82333, #a71e2a);
                transform: translateY(-2px);
                color: white;
                text-decoration: none;
            }
            
            .fade-in {
                animation: fadeIn 0.3s ease-in-out;
            }
            
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            /* Pagination Fix */
.pagination {
    display: flex;
    gap: 4px;
    margin: 0;
    padding: 0;
    list-style: none;
    flex-wrap: wrap;
}

.pagination .page-item .page-link {
    padding: 6px 12px;
    border-radius: 6px;
    border: 1px solid #dee2e6;
    color: #28a745;
    font-size: 0.85rem;
    text-decoration: none;
    background: white;
}

.pagination .page-item.active .page-link {
    background: #28a745;
    border-color: #28a745;
    color: white;
}

.pagination .page-item.disabled .page-link {
    color: #adb5bd;
    pointer-events: none;
}

.pagination .page-item .page-link:hover {
    background: #e9f7ef;
    border-color: #28a745;
    color: #28a745;
}
/* Cancelled row styling */
tr.row-cancelled {
    background-color: #e9ecef !important;
    opacity: 0.75;
}

tr.row-cancelled td {
    color: #6c757d !important;
}

tr.row-cancelled strong,
tr.row-cancelled .text-success {
    color: #6c757d !important;
}

tr.row-cancelled select,
tr.row-cancelled .btn-action {
    pointer-events: none;
    opacity: 0.5;
    cursor: not-allowed;
}
            @media (max-width: 768px) {
                .sidebar { width: 100%; position: relative; }
                .main-content { margin-left: 0; }
                .stats-grid { grid-template-columns: repeat(2, 1fr); }
            }
        </style>
    </head>

    <body>
        <!-- Back Button -->
        <a href="{{ route('home') }}" class="back-button">
            <i class="fas fa-arrow-left me-2"></i>Kembali ke Website
        </a>

        <div class="admin-container">
            <!-- Sidebar -->
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
                    {{-- ↓ TAMBAHKAN DI SINI ↓ --}}
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

            <!-- Main Content -->
            <div class="main-content">
                <div class="admin-header">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-home"></i> Dashboard
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Pesanan</li>
                        </ol>
                    </nav>
                    <h2>Orders Management</h2>
                    <p class="mb-0">Kelola semua pesanan pelanggan di sini!</p>
                </div>

                <div class="content-area fade-in">

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Stats Cards -->
                    <div class="stats-grid">
                        <div class="stat-card primary">
                            <div>
                                <div class="stat-label">Total Orders</div>
                                <div class="stat-value">{{ $orders->total() }}</div>
                            </div>
                            <div class="stat-icon"><i class="fas fa-shopping-cart"></i></div>
                        </div>
                        <div class="stat-card warning">
                            <div>
                                <div class="stat-label">Pending Orders</div>
                                <div class="stat-value">{{ \App\Models\Transaksi::where('order_status', 'pending')->count() }}</div>
                            </div>
                            <div class="stat-icon"><i class="fas fa-clock"></i></div>
                        </div>
                        <div class="stat-card info">
                            <div>
                                <div class="stat-label">Processing Orders</div>
                                <div class="stat-value">{{ \App\Models\Transaksi::where('order_status', 'processing')->count() }}</div>
                            </div>
                            <div class="stat-icon"><i class="fas fa-box"></i></div>
                        </div>
                        <div class="stat-card success">
                            <div>
                                <div class="stat-label">Delivered Orders</div>
                                <div class="stat-value">{{ \App\Models\Transaksi::where('order_status', 'delivered')->count() }}</div>
                            </div>
                            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                        </div>
                    </div>

                    <!-- Orders Table -->
                    <div class="page-header">
                        <div class="section-header">
                            <h3 class="section-title">
                                <i class="fas fa-shopping-cart text-success me-2"></i>
                                Daftar Pesanan ({{ $orders->total() }})
                            </h3>
                            <div class="d-flex gap-2 align-items-center">
                                <select class="form-select form-select-sm" id="filterStatus" onchange="filterOrders()" style="width: 160px;">
                                    <option value="">Semua Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="processing">Processing</option>
                                    <option value="shipped">Shipped</option>
                                    <option value="delivered">Delivered</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                                <a href="{{ route('admin.transaksis.export') }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-file-excel me-1"></i>Export Excel
                                </a>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="data-table" id="ordersTable">
                              <thead>
                                    <tr>
                                        <th>Invoice Number</th>
                                        <th>Customer</th>
                                        <th>Phone</th>
                                        <th>Order Status</th>
                                        <th>Payment Status</th>
                                        <th>Metode Pembayaran</th>
                                        <th>Total</th>
                                        <th>Order Date</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($orders as $order)
                                    <tr data-status="{{ $order->order_status }}" 
                                        class="{{ $order->order_status === 'cancelled' ? 'row-cancelled' : '' }}">
                                        <td><strong>{{ $order->invoice_number }}</strong></td>
                                        <td>{{ $order->customer_name }}</td>
                                        <td>{{ $order->customer_phone }}</td>
                                        <td>
                                            <select class="badge-status badge-{{ $order->order_status }} border-0 cursor-pointer"
                                                style="cursor:pointer; appearance:auto;"
                                                onchange="updateStatus({{ $order->id }}, 'order_status', this.value, this)">
                                                <option value="pending"    {{ $order->order_status == 'pending'    ? 'selected' : '' }}>Pending</option>
                                                <option value="processing" {{ $order->order_status == 'processing' ? 'selected' : '' }}>Processing</option>
                                                <option value="shipped"    {{ $order->order_status == 'shipped'    ? 'selected' : '' }}>Shipped</option>
                                                <option value="delivered"  {{ $order->order_status == 'delivered'  ? 'selected' : '' }}>Delivered</option>
                                                <option value="cancelled"  {{ $order->order_status == 'cancelled'  ? 'selected' : '' }}>Cancelled</option>
                                            </select>
                                            @if($order->cancellation && $order->cancellation->status === 'pending')
                                                <br><span class="badge-status badge-warning mt-1" style="font-size:0.7rem;">
                                                    ⚠ Minta Batal
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <select class="badge-status badge-{{ $order->payment_status }} border-0"
                                                style="cursor:pointer; appearance:auto;"
                                                onchange="updateStatus({{ $order->id }}, 'payment_status', this.value, this)">
                                                <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="paid"    {{ $order->payment_status == 'paid'    ? 'selected' : '' }}>Paid</option>
                                                <option value="failed"  {{ $order->payment_status == 'failed'  ? 'selected' : '' }}>Failed</option>
                                            </select>
                                        </td>
                                        <td>
                                           <span class="badge-status" style="background:#ced4da;color:#212529;">
                                                {{ ucwords(str_replace('_', ' ', $order->payment_method ?? '-')) }}
                                            </span>
                                        </td>
                                        <td><strong class="text-success">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></td>
                                        <td><small>{{ $order->created_at->format('d M Y H:i') }}</small></td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('admin.transaksis.show', $order->id) }}" 
                                                class="btn-action btn-view" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.transaksis.edit', $order->id) }}" 
                                                class="btn-action btn-edit" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('admin.transaksis.print', $order->id) }}" 
                                                class="btn-action btn-print" title="Print Invoice" target="_blank">
                                                    <i class="fas fa-print"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                      @empty
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <small class="text-muted">
                                Menampilkan {{ $orders->firstItem() ?? 0 }} sampai {{ $orders->lastItem() ?? 0 }}
                                dari {{ $orders->total() }} pesanan
                            </small>
                            <div>{{ $orders->links() }}</div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Bootstrap JS -->
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function filterOrders() {
        const filterValue = document.getElementById('filterStatus').value.toLowerCase();
        const rows = document.querySelectorAll('#ordersTable tbody tr[data-status]');
        rows.forEach(row => {
            const status = row.getAttribute('data-status');
            row.style.display = (filterValue === '' || status === filterValue) ? '' : 'none';
        });
    }

   function updateStatus(orderId, field, value, selectEl) {
    // Jika status sudah cancelled, tidak bisa diubah
    if (field === 'order_status') {
        const row = selectEl.closest('tr');
        const currentStatus = row.getAttribute('data-status');
       if (currentStatus === 'cancelled' || currentStatus === 'delivered') {
    alert('Pesanan yang sudah selesai atau dibatalkan tidak dapat diubah kembali.');
    selectEl.value = currentStatus;
    return;
}
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(`/admin/orders/${orderId}/update-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ [field]: value })
        })
        .then(res => res.json())
        .then(data => {
           if (data.success) {
            // Update warna badge sesuai nilai baru
            const badgeClasses = ['badge-pending','badge-processing','badge-shipped','badge-delivered','badge-cancelled','badge-paid','badge-failed'];
            selectEl.classList.remove(...badgeClasses);
            selectEl.classList.add('badge-' + value);

            // Update data-status di row jika yang diubah adalah order_status
            if (field === 'order_status') {
                const row = selectEl.closest('tr');
                row.setAttribute('data-status', value);

                // Tambahkan/hapus class abu-abu jika status cancelled
                if (value === 'cancelled') {
                    row.classList.add('row-cancelled');
                } else {
                    row.classList.remove('row-cancelled');
                }
            }

            // Tampilkan notifikasi kecil
            showToast('Status berhasil diupdate!');
            } else {
                alert('Gagal update: ' + data.message);
            }
        })
        .catch(() => alert('Terjadi kesalahan koneksi.'));
    }

    function showToast(msg) {
        const toast = document.createElement('div');
        toast.innerText = msg;
        toast.style.cssText = 'position:fixed;bottom:20px;right:20px;background:#28a745;color:white;padding:10px 20px;border-radius:8px;z-index:9999;font-size:14px;';
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 2500);
    }
</script>
    </body>
    </html>