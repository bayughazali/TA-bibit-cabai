<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Management - Bibit Cabai Admin</title>
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
            top: 0;
            left: 0;
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
        .topbar-title h2 { font-size: 1.1rem; font-weight: 700; color: #111827; margin: 0; }
        .topbar-title p { font-size: 0.75rem; color: #6b7280; margin: 0; }

        .btn-back-website {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            color: white;
            border: none;
            padding: 9px 18px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.825rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .btn-back-website:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(220,38,38,0.35);
            color: white;
            text-decoration: none;
        }

        /* ===== CONTENT AREA ===== */
        .content-area { padding: 20px 24px 24px; flex: 1; }

        /* ===== STATS GRID ===== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 18px 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 10px rgba(0,0,0,0.04);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-left: 4px solid;
        }

        .stat-card.primary { border-left-color: #2563eb; }
        .stat-card.warning { border-left-color: #d97706; }
        .stat-card.info    { border-left-color: #0891b2; }
        .stat-card.success { border-left-color: var(--green-main); }

        .stat-label {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 4px;
        }

        .stat-card.primary .stat-label { color: #2563eb; }
        .stat-card.warning .stat-label { color: #d97706; }
        .stat-card.info    .stat-label { color: #0891b2; }
        .stat-card.success .stat-label { color: var(--green-main); }

        .stat-value { font-size: 1.5rem; font-weight: 800; color: #111827; }
        .stat-icon  { font-size: 1.75rem; color: #e5e7eb; }

        /* ===== TABLE CARD ===== */
        .table-card {
            background: white;
            border-radius: 14px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 10px rgba(0,0,0,0.04);
            overflow: hidden;
        }

        .table-card-header {
    padding: 18px 20px;
    border-bottom: 1px solid #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: nowrap;
}

        .section-title {
            font-size: 1rem;
            font-weight: 700;
            color: #111827;
            display: flex;
            align-items: center;
            gap: 8px;
        }

     .table-controls {
    display: flex;
    flex-direction: column;
    gap: 8px;
    align-items: flex-end;
    flex-shrink: 0;
}

        /* Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th, .data-table td {
            padding: 11px 14px;
            text-align: left;
            border-bottom: 1px solid #f3f4f6;
            font-size: 0.875rem;
        }

        .data-table th {
            background: #f9fafb;
            font-weight: 600;
            color: #6b7280;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            white-space: nowrap;
        }

        .data-table tbody tr:hover { background-color: #f9fafb; }

        /* Badges */
        .badge-status {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.72rem;
            font-weight: 600;
            display: inline-block;
        }

        .badge-pending    { background: #fef3c7; color: #92400e; }
        .badge-processing { background: #dbeafe; color: #1e40af; }
        .badge-shipped    { background: #e0f2fe; color: #075985; }
        .badge-delivered  { background: #dcfce7; color: #166534; }
        .badge-cancelled  { background: #fee2e2; color: #991b1b; }
        .badge-paid       { background: #dcfce7; color: #166534; }
        .badge-failed     { background: #fee2e2; color: #991b1b; }

        /* Selects with badge style */
        select.badge-status {
            border: none;
            cursor: pointer;
            appearance: auto;
            -webkit-appearance: auto;
        }

        /* Action Buttons */
        .btn-action {
            padding: 6px 10px;
            border: none;
            border-radius: 6px;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .btn-action:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
            text-decoration: none;
            color: inherit;
        }

        .btn-view  { background: #0891b2; color: white; }
        .btn-edit  { background: #d97706; color: white; }
        .btn-print { background: #6b7280; color: white; }

        /* Cancelled row */
        tr.row-cancelled { background-color: #f3f4f6 !important; opacity: 0.75; }
        tr.row-cancelled td { color: #9ca3af !important; }
        tr.row-cancelled strong, tr.row-cancelled .text-success { color: #9ca3af !important; }
        tr.row-cancelled select, tr.row-cancelled .btn-action { pointer-events: none; opacity: 0.5; cursor: not-allowed; }

        /* Pagination */
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
            border: 1px solid #e5e7eb;
            color: var(--green-main);
            font-size: 0.83rem;
            text-decoration: none;
            background: white;
        }

        .pagination .page-item.active .page-link {
            background: var(--green-main);
            border-color: var(--green-main);
            color: white;
        }

        .pagination .page-item.disabled .page-link { color: #d1d5db; pointer-events: none; }
        .pagination .page-item .page-link:hover { background: #f0fdf4; border-color: var(--green-main); }

        .table-footer {
            padding: 14px 20px;
            border-top: 1px solid #f3f4f6;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        /* Alert */
        .alert-success-custom {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
        }

        .fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1100px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 900px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); box-shadow: 4px 0 24px rgba(0,0,0,0.2); }
            .main-content { margin-left: 0; }
            .hamburger-btn { display: flex; align-items: center; justify-content: center; }
        }

        @media (max-width: 600px) {
            .stats-grid { grid-template-columns: 1fr 1fr; gap: 10px; }
            .stat-card { padding: 14px 12px; }
            .stat-value { font-size: 1.3rem; }
            .stat-icon { font-size: 1.4rem; }
            .content-area { padding: 14px 14px 20px; }
            .topbar { padding: 0 14px; }
            .btn-back-website span { display: none; }
            .btn-back-website { padding: 9px 12px; }
            .table-card-header { flex-direction: column; align-items: flex-start; }
            .table-controls { width: 100%; }
            .table-controls .form-select, .table-controls a { flex: 1; }
            .table-footer { flex-direction: column; align-items: flex-start; gap: 10px; }
            .data-table th, .data-table td { padding: 9px 10px; font-size: 0.8rem; }
        }

        @media (max-width: 380px) {
            .stats-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>

<body>

    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    <!-- Sidebar -->
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
                <button type="submit" class="btn-logout" onclick="return confirm('Apakah Anda yakin ingin logout?')">
                    <i class="fas fa-sign-out-alt"></i><span>Logout</span>
                </button>
            </form>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">

        <!-- Topbar -->
        <header class="topbar">
            <div class="topbar-left">
                <button class="hamburger-btn" id="hamburgerBtn" onclick="toggleSidebar()" aria-label="Toggle menu">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="topbar-title">
                    <div class="topbar-breadcrumb">
                        <a href="{{ route('admin.dashboard') }}"><i class="fas fa-home"></i> Dashboard</a>
                        &nbsp;/&nbsp;Pesanan
                    </div>
                    <h2>Orders Management</h2>
                </div>
            </div>
            <a href="{{ route('home') }}" class="btn-back-website">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali ke Website</span>
            </a>
        </header>

        <!-- Content -->
        <div class="content-area fade-in">

            @if(session('success'))
            <div class="alert-success-custom">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            <!-- Stats Grid -->
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
                        <div class="stat-label">Pending</div>
                        <div class="stat-value">{{ \App\Models\Transaksi::where('order_status', 'pending')->count() }}</div>
                    </div>
                    <div class="stat-icon"><i class="fas fa-clock"></i></div>
                </div>
                <div class="stat-card info">
                    <div>
                        <div class="stat-label">Processing</div>
                        <div class="stat-value">{{ \App\Models\Transaksi::where('order_status', 'processing')->count() }}</div>
                    </div>
                    <div class="stat-icon"><i class="fas fa-box"></i></div>
                </div>
                <div class="stat-card success">
                    <div>
                        <div class="stat-label">Delivered</div>
                        <div class="stat-value">{{ \App\Models\Transaksi::where('order_status', 'delivered')->count() }}</div>
                    </div>
                    <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                </div>
            </div>

            <!-- Table Card -->
           <div class="table-card-header">
    <div class="section-title">
        <i class="fas fa-shopping-cart text-success"></i>
        Daftar Pesanan ({{ $orders->total() }})
    </div>
    <div class="table-controls">
        <select class="form-select form-select-sm" id="filterStatus" onchange="filterOrders()" style="min-width:160px;">
            <option value="">Semua Status</option>
            <option value="pending">Pending</option>
            <option value="processing">Processing</option>
            <option value="shipped">Shipped</option>
            <option value="delivered">Delivered</option>
            <option value="cancelled">Cancelled</option>
        </select>
        <select class="form-select form-select-sm" id="filterPayment" onchange="filterOrders()" style="min-width:160px;">
            <option value="">Semua Payment Status</option>
            <option value="pending">Pending</option>
            <option value="paid">Paid</option>
            <option value="failed">Failed</option>
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
                                <th>Invoice</th>
                                <th>Customer</th>
                                <th>Phone</th>
                                <th>Order Status</th>
                                <th>Payment Status</th>
                                <th>Metode</th>
                                <th>Total</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                           <tr data-status="{{ $order->order_status }}"
                            data-payment="{{ $order->payment_status }}"
                            class="{{ $order->order_status === 'cancelled' ? 'row-cancelled' : '' }}">
                                <td><strong>{{ $order->invoice_number }}</strong></td>
                                <td>{{ $order->customer_name }}</td>
                                <td>{{ $order->customer_phone }}</td>
                                <td>
                                    <select class="badge-status badge-{{ $order->order_status }} border-0"
                                        onchange="updateStatus({{ $order->id }}, 'order_status', this.value, this)">
                                        <option value="pending"    {{ $order->order_status == 'pending'    ? 'selected' : '' }}>Pending</option>
                                        <option value="processing" {{ $order->order_status == 'processing' ? 'selected' : '' }}>Processing</option>
                                        <option value="shipped"    {{ $order->order_status == 'shipped'    ? 'selected' : '' }}>Shipped</option>
                                        <option value="delivered"  {{ $order->order_status == 'delivered'  ? 'selected' : '' }}>Delivered</option>
                                        <option value="cancelled"  {{ $order->order_status == 'cancelled'  ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                    @if($order->cancellation && $order->cancellation->status === 'pending')
                                        <br><span class="badge-status badge-warning mt-1" style="font-size:0.68rem;">⚠ Minta Batal</span>
                                    @endif
                                </td>
                                <td>
                                    <select class="badge-status badge-{{ $order->payment_status }} border-0"
                                        onchange="updateStatus({{ $order->id }}, 'payment_status', this.value, this)">
                                        <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="paid"    {{ $order->payment_status == 'paid'    ? 'selected' : '' }}>Paid</option>
                                        <option value="failed"  {{ $order->payment_status == 'failed'  ? 'selected' : '' }}>Failed</option>
                                    </select>
                                </td>
                                <td>
                                    <span class="badge-status" style="background:#f3f4f6;color:#374151;">
                                        {{ ucwords(str_replace('_', ' ', $order->payment_method ?? '-')) }}
                                    </span>
                                </td>
                                <td><strong class="text-success">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></td>
                                <td><small style="color:#6b7280;">{{ $order->created_at->format('d M Y H:i') }}</small></td>
                                <td>
                                    <div class="d-flex gap-1 flex-nowrap">
                                        <a href="{{ route('admin.transaksis.show', $order->id) }}" class="btn-action btn-view" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.transaksis.edit', $order->id) }}" class="btn-action btn-edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('admin.transaksis.print', $order->id) }}" class="btn-action btn-print" title="Print Invoice" target="_blank">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" style="text-align:center; padding:40px; color:#9ca3af;">
                                    <i class="fas fa-inbox" style="font-size:2rem; display:block; margin-bottom:10px; opacity:0.3;"></i>
                                    Belum ada pesanan
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="table-footer">
                    <small style="color:#6b7280;">
                        Menampilkan {{ $orders->firstItem() ?? 0 }} sampai {{ $orders->lastItem() ?? 0 }}
                        dari {{ $orders->total() }} pesanan
                    </small>
                    <div>{{ $orders->links() }}</div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Sidebar toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('show');
            document.body.style.overflow = sidebar.classList.contains('open') ? 'hidden' : '';
        }

        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
            document.body.style.overflow = '';
        }

        window.addEventListener('resize', function() {
            if (window.innerWidth > 900) closeSidebar();
        });

        // Filter orders
        function filterOrders() {
    const filterStatus  = document.getElementById('filterStatus').value.toLowerCase();
    const filterPayment = document.getElementById('filterPayment').value.toLowerCase();
    const rows = document.querySelectorAll('#ordersTable tbody tr[data-status]');
    rows.forEach(row => {
        const status  = row.getAttribute('data-status');
        const payment = row.getAttribute('data-payment');
        const matchStatus  = filterStatus  === '' || status  === filterStatus;
        const matchPayment = filterPayment === '' || payment === filterPayment;
        row.style.display = (matchStatus && matchPayment) ? '' : 'none';
    });
}

        // Update status via AJAX
        function updateStatus(orderId, field, value, selectEl) {
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
                    const badgeClasses = ['badge-pending','badge-processing','badge-shipped','badge-delivered','badge-cancelled','badge-paid','badge-failed'];
                    selectEl.classList.remove(...badgeClasses);
                    selectEl.classList.add('badge-' + value);

                    if (field === 'order_status') {
                        const row = selectEl.closest('tr');
                        row.setAttribute('data-status', value);
                        if (value === 'cancelled') {
                            row.classList.add('row-cancelled');
                        } else {
                            row.classList.remove('row-cancelled');
                        }
                    }

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
            toast.style.cssText = 'position:fixed;bottom:24px;right:24px;background:#16a34a;color:white;padding:10px 20px;border-radius:8px;z-index:9999;font-size:14px;font-weight:500;box-shadow:0 4px 12px rgba(0,0,0,0.15);';
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 2500);
        }
    </script>
</body>
</html>