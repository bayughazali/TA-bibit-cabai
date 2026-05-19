<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan - Bibit Cabai</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f8f9fa; }
        .admin-container { display: flex; min-height: 100vh; }

        /* ===== SIDEBAR (sama persis dengan dashboard) ===== */
        .sidebar { width: 280px; background: linear-gradient(180deg, #28a745, #20c997); color: white; min-height: 100vh; position: fixed; top: 0; left: 0; z-index: 1000; transition: all 0.3s ease; }
        .sidebar-header { padding: 25px 20px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar-header h3 { font-size: 1.8rem; font-weight: bold; margin-bottom: 5px; }
        .sidebar-header p { opacity: 0.8; font-size: 0.9rem; }
        .sidebar-menu { padding: 20px 0; }
        .menu-item { display: block; padding: 15px 25px; color: white; text-decoration: none; transition: all 0.3s; border-left: 4px solid transparent; cursor: pointer; }
        .menu-item:hover, .menu-item.active { background: rgba(255,255,255,0.1); color: white; text-decoration: none; border-left-color: #fff; transform: translateX(5px); }
        .menu-item i { width: 20px; margin-right: 10px; }

        /* ===== MAIN CONTENT ===== */
        .main-content { margin-left: 280px; flex: 1; min-height: 100vh; }
        .admin-header { background: white; padding: 20px 30px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-bottom: 1px solid #dee2e6; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }
        .admin-header-left h2 { margin-bottom: 2px; }
        .admin-header-left p { margin: 0; color: #6c757d; font-size: 0.9rem; }
        .content-area { padding: 30px; }

        /* Back Button */
        .back-button { position: fixed; top: 20px; right: 20px; z-index: 1100; background: linear-gradient(45deg, #dc3545, #c82333); color: white; border: none; padding: 12px 20px; border-radius: 25px; font-weight: 600; transition: all 0.3s ease; text-decoration: none; }
        .back-button:hover { background: linear-gradient(45deg, #c82333, #a71e2a); transform: translateY(-2px); color: white; text-decoration: none; }

        /* ===== FILTER BAR ===== */
        .filter-bar {
            background: white;
            border-radius: 12px;
            padding: 20px 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            margin-bottom: 28px;
            display: flex;
            align-items: flex-end;
            flex-wrap: wrap;
            gap: 16px;
        }
        .filter-bar .form-label { font-weight: 600; color: #495057; font-size: 0.85rem; margin-bottom: 6px; }
        .filter-bar .form-control, .filter-bar .form-select {
            border-radius: 8px;
            border: 1.5px solid #dee2e6;
            font-size: 0.9rem;
            padding: 9px 13px;
            transition: border-color 0.2s;
        }
        .filter-bar .form-control:focus, .filter-bar .form-select:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 3px rgba(40,167,69,0.12);
        }
        .btn-filter {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            border: none;
            padding: 10px 22px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s;
        }
        .btn-filter:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(40,167,69,0.35); color: white; }
        .btn-export {
            background: white;
            color: #28a745;
            border: 2px solid #28a745;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-export:hover { background: #28a745; color: white; text-decoration: none; }

        /* ===== RINGKASAN CARDS ===== */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 28px;
        }
        .summary-card {
            background: white;
            border-radius: 12px;
            padding: 22px 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            border-top: 4px solid;
            transition: transform 0.25s;
        }
        .summary-card:hover { transform: translateY(-4px); }
        .summary-card.pendapatan { border-top-color: #28a745; }
        .summary-card.transaksi  { border-top-color: #007bff; }
        .summary-card.produk     { border-top-color: #fd7e14; }
        .summary-card.rata       { border-top-color: #6f42c1; }
        .summary-val { font-size: 1.8rem; font-weight: 700; line-height: 1.2; }
        .summary-val.green  { color: #28a745; }
        .summary-val.blue   { color: #007bff; }
        .summary-val.orange { color: #fd7e14; }
        .summary-val.purple { color: #6f42c1; }
        .summary-lbl { color: #6c757d; font-size: 0.88rem; font-weight: 500; margin-top: 4px; }
        .summary-icon { float: right; font-size: 2rem; opacity: 0.18; margin-top: -6px; }

        /* ===== CHART SECTION ===== */
        .section-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            margin-bottom: 28px;
            overflow: hidden;
        }
        .section-header {
            padding: 18px 22px;
            font-weight: 700;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 10px;
            color: white;
        }
        .section-header.green  { background: linear-gradient(135deg, #28a745, #20c997); }
        .section-header.blue   { background: linear-gradient(135deg, #007bff, #0056b3); }
        .section-header.orange { background: linear-gradient(135deg, #fd7e14, #e55a00); }
        .section-body { padding: 22px; }

        /* ===== TABEL ===== */
        .table-responsive { border-radius: 8px; overflow: hidden; }
        .table-laporan { font-size: 0.9rem; margin: 0; }
        .table-laporan thead th {
            background: #f1f5f9;
            color: #495057;
            font-weight: 700;
            border-bottom: 2px solid #dee2e6;
            padding: 13px 15px;
            white-space: nowrap;
        }
        .table-laporan tbody td { padding: 12px 15px; vertical-align: middle; border-bottom: 1px solid #f0f0f0; }
        .table-laporan tbody tr:hover { background: #f8fff9; }
        .table-laporan tbody tr:last-child td { border-bottom: none; }

        /* Badge status */
        .badge-status { padding: 5px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 600; }
        .badge-selesai   { background: #d4edda; color: #155724; }
        .badge-proses    { background: #cce5ff; color: #004085; }
        .badge-batal     { background: #f8d7da; color: #721c24; }
        .badge-kirim     { background: #fff3cd; color: #856404; }

        /* Progress bar produk */
        .progress-thin { height: 6px; border-radius: 10px; background: #e9ecef; }
        .progress-fill-green  { background: linear-gradient(90deg, #28a745, #20c997); }
        .progress-fill-orange { background: linear-gradient(90deg, #fd7e14, #ffc107); }

        /* Empty state */
        .empty-state { text-align: center; padding: 50px 20px; color: #adb5bd; }
        .empty-state i { font-size: 3.5rem; margin-bottom: 15px; display: block; }

        /* Pagination */
        .page-link { color: #28a745; border-color: #dee2e6; }
        .page-item.active .page-link { background-color: #28a745; border-color: #28a745; }
        .page-link:hover { color: #155724; border-color: #28a745; }

        @media (max-width: 768px) {
            .sidebar { width: 100%; position: relative; min-height: auto; }
            .main-content { margin-left: 0; }
            .summary-grid { grid-template-columns: 1fr 1fr; }
            .filter-bar { flex-direction: column; align-items: stretch; }
        }
    </style>
</head>
<body>
    <!-- Back Button -->
    <a href="{{ route('home') }}" class="back-button">
        <i class="fas fa-arrow-left me-2"></i>Kembali ke Website
    </a>

    <div class="admin-container">
        <!-- ===== SIDEBAR ===== -->
        <nav class="sidebar">
            <div class="sidebar-header">
                <h3>🌱 Bibit Cabai</h3>
                <p>Admin Dashboard</p>
                <small class="text-light">{{ Auth::user()->name ?? 'Admin User' }}</small>
            </div>
            <div class="sidebar-menu">
                <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>Dashboard
                </a>
                <a href="{{ route('admin.products.index') }}" class="menu-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <i class="fas fa-seedling"></i>Kelola Produk
                </a>
                <a href="{{ route('admin.orders') }}" class="menu-item {{ request()->routeIs('admin.orders') ? 'active' : '' }}">
                    <i class="fas fa-shopping-cart"></i>Pesanan
                </a>
                 {{-- ↓ TAMBAHKAN DI SINI ↓ --}}
                <a href="{{ route('admin.cancellations') }}" class="menu-item">
                    <i class="fas fa-times-circle"></i>Pengajuan Batal
                </a>
                <a href="{{ route('admin.users') }}" class="menu-item {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>Pengguna
                </a>
                <a href="{{ route('admin.laporan') }}" class="menu-item {{ request()->routeIs('admin.laporan') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i>Laporan
                </a>
                <!-- <a href="{{ route('admin.settings') }}" class="menu-item {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i>Pengaturan
                </a> -->
                <div class="mt-4 px-3">
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm w-100"
                                onclick="return confirm('Apakah Anda yakin ingin logout?')">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </nav>

        <!-- ===== MAIN CONTENT ===== -->
        <div class="main-content">
            <div class="admin-header">
                <div class="admin-header-left">
                    <h2>📊 Laporan Penjualan</h2>
                    <p>Detail pendapatan, transaksi, dan produk yang keluar</p>
                </div>
                <a href="{{ route('admin.laporan.export') }}" class="btn-export">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
            </div>

            <div class="content-area">

                {{-- ===== FILTER BAR ===== --}}
                <div class="filter-bar">
                    <form method="GET" action="{{ route('admin.laporan') }}" style="display:contents">
                        <div>
                            <label class="form-label">Dari Tanggal</label>
                            <input type="date" name="start_date" class="form-control"
                                   value="{{ request('start_date', now()->startOfMonth()->toDateString()) }}"
                                   style="min-width:160px">
                        </div>
                        <div>
                            <label class="form-label">Sampai Tanggal</label>
                            <input type="date" name="end_date" class="form-control"
                                   value="{{ request('end_date', now()->toDateString()) }}"
                                   style="min-width:160px">
                        </div>
                        <div>
                            <label class="form-label">Status Pesanan</label>
                            <select name="status" class="form-select" style="min-width:160px">
                                <option value="">Semua Status</option>
                                <option value="delivered"  {{ request('status')=='delivered'  ? 'selected':'' }}>Selesai</option>
                                <option value="processing" {{ request('status')=='processing' ? 'selected':'' }}>Diproses</option>
                                <option value="shipped"    {{ request('status')=='shipped'    ? 'selected':'' }}>Dikirim</option>
                                <option value="cancelled"  {{ request('status')=='cancelled'  ? 'selected':'' }}>Dibatal</option>
                                <option value="pending"    {{ request('status')=='pending'    ? 'selected':'' }}>Pending</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Produk</label>
                            <select name="product_id" class="form-select" style="min-width:180px">
                                <option value="">Semua Produk</option>
                                @foreach($products ?? [] as $product)
                                    <option value="{{ $product->id }}" {{ request('product_id')==$product->id ? 'selected':'' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn-filter">
                            <i class="fas fa-search me-1"></i> Tampilkan
                        </button>
                        <a href="{{ route('admin.laporan') }}" class="btn btn-outline-secondary" style="border-radius:8px;padding:10px 18px;font-size:.9rem">
                            <i class="fas fa-redo me-1"></i> Reset
                        </a>
                    </form>
                </div>

                {{-- ===== RINGKASAN ===== --}}
                <div class="summary-grid">
                    <div class="summary-card pendapatan">
                        <i class="fas fa-wallet summary-icon text-success"></i>
                        <div class="summary-val green">Rp {{ number_format($summary['total_pendapatan'] ?? 0, 0, ',', '.') }}</div>
                        <div class="summary-lbl">Total Pendapatan</div>
                    </div>
                    <div class="summary-card transaksi">
                        <i class="fas fa-receipt summary-icon text-primary"></i>
                        <div class="summary-val blue">{{ number_format($summary['total_transaksi'] ?? 0) }}</div>
                        <div class="summary-lbl">Total Transaksi</div>
                    </div>
                    <div class="summary-card produk">
                        <i class="fas fa-box-open summary-icon text-warning"></i>
                        <div class="summary-val orange">{{ number_format($summary['total_produk_keluar'] ?? 0) }}</div>
                        <div class="summary-lbl">Produk Terjual (unit)</div>
                    </div>
                    <div class="summary-card rata">
                        <i class="fas fa-calculator summary-icon text-purple"></i>
                        <div class="summary-val purple">Rp {{ number_format($summary['rata_per_transaksi'] ?? 0, 0, ',', '.') }}</div>
                        <div class="summary-lbl">Rata-rata per Transaksi</div>
                    </div>
                </div>

                {{-- ===== GRAFIK PENDAPATAN ===== --}}
                <div class="section-card">
                    <div class="section-header green">
                        <i class="fas fa-chart-area"></i> Grafik Pendapatan Harian
                    </div>
                    <div class="section-body">
                        @if(isset($chartLabels) && count($chartLabels) > 0)
                            <canvas id="revenueChart" height="90"></canvas>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-chart-area"></i>
                                <p>Belum ada data grafik untuk periode ini.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    {{-- ===== GRAFIK PRODUK TERLARIS ===== --}}
                    <div class="col-lg-5">
                        <div class="section-card mb-0 h-100">
                            <div class="section-header orange">
                                <i class="fas fa-fire"></i> Produk Terlaris
                            </div>
                            <div class="section-body">
                                @if(isset($topProducts) && count($topProducts) > 0)
                                    <canvas id="topProductChart" height="220"></canvas>
                                @else
                                    <div class="empty-state">
                                        <i class="fas fa-seedling"></i>
                                        <p>Belum ada data produk terlaris.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- ===== TABEL PRODUK KELUAR ===== --}}
                    <div class="col-lg-7">
                        <div class="section-card mb-0 h-100">
                            <div class="section-header blue">
                                <i class="fas fa-boxes"></i> Detail Produk Keluar
                            </div>
                            <div class="section-body p-0">
                                @if(isset($productSales) && count($productSales) > 0)
                                    <div class="table-responsive">
                                        <table class="table table-laporan">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Nama Produk</th>
                                                    <th class="text-center">Terjual (unit)</th>
                                                    <th class="text-end">Pendapatan</th>
                                                    <th>Proporsi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($productSales as $i => $item)
                                                <tr>
                                                    <td class="text-muted">{{ $i + 1 }}</td>
                                                    <td>
                                                        <strong>{{ $item->product_name }}</strong>
                                                        <br><small class="text-muted">{{ $item->category ?? '-' }}</small>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-light text-dark border fw-bold">{{ number_format($item->total_qty) }}</span>
                                                    </td>
                                                    <td class="text-end fw-bold text-success">
                                                        Rp {{ number_format($item->total_revenue, 0, ',', '.') }}
                                                    </td>
                                                    <td style="min-width:100px">
                                                        @php
                                                            $pct = $summary['total_produk_keluar'] > 0
                                                                ? round($item->total_qty / $summary['total_produk_keluar'] * 100)
                                                                : 0;
                                                        @endphp
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div class="progress progress-thin flex-grow-1">
                                                                <div class="progress-fill-green" style="width:{{ $pct }}%;height:100%;border-radius:10px"></div>
                                                            </div>
                                                            <small class="text-muted">{{ $pct }}%</small>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="empty-state">
                                        <i class="fas fa-box-open"></i>
                                        <p>Belum ada data produk untuk periode ini.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== TABEL DETAIL TRANSAKSI ===== --}}
                <div class="section-card">
                    <div class="section-header green">
                        <i class="fas fa-list-alt"></i> Riwayat Transaksi Detail
                    </div>
                    <div class="section-body p-0">
                        @if(isset($orders) && $orders->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-laporan">
                                    <thead>
                                        <tr>
                                            <th>No. Order</th>
                                            <th>Tanggal</th>
                                            <th>Pelanggan</th>
                                            <th>Produk</th>
                                            <th class="text-center">Qty</th>
                                            <th class="text-end">Total</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($orders as $order)
                                        <tr>
                                            <td><code class="text-success">#{{ $order->invoice_number ?? $order->id }}</code></td>
                                            <td class="text-muted" style="white-space:nowrap">
                                                {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}<br>
                                                <small>{{ \Carbon\Carbon::parse($order->created_at)->format('H:i') }}</small>
                                            </td>
                                            <td>
                                                <strong>{{ $order->customer_name ?? $order->user->name ?? 'Guest' }}</strong><br>
                                                <small class="text-muted">{{ $order->customer_email ?? $order->user->email ?? '-' }}</small>
                                            </td>
                                            <td>
                                                {{-- Relasi di Transaksi adalah 'details', bukan 'items' --}}
                                                @forelse($order->details ?? [] as $detail)
                                                    <div>{{ $detail->product->name ?? '-' }}</div>
                                                @empty
                                                    <span class="text-muted">-</span>
                                                @endforelse
                                            </td>
                                            <td class="text-center">
                                                @forelse($order->details ?? [] as $detail)
                                                    <div>{{ $detail->quantity }}</div>
                                                @empty
                                                    <span class="text-muted">-</span>
                                                @endforelse
                                            </td>
                                            <td class="text-end fw-bold text-success" style="white-space:nowrap">
                                                Rp {{ number_format($order->total_amount ?? 0, 0, ',', '.') }}
                                            </td>
                                            <td class="text-center">
                                                {{-- Status sesuai nilai enum di DB: pending, processing, shipped, delivered, cancelled --}}
                                                @php $orderStatus = $order->order_status ?? 'pending'; @endphp
                                                @if($orderStatus == 'delivered')
                                                    <span class="badge-status badge-selesai">✔ Selesai</span>
                                                @elseif($orderStatus == 'shipped')
                                                    <span class="badge-status badge-kirim">🚚 Dikirim</span>
                                                @elseif($orderStatus == 'cancelled')
                                                    <span class="badge-status badge-batal">✖ Batal</span>
                                                @elseif($orderStatus == 'processing')
                                                    <span class="badge-status badge-proses">⚙ Diproses</span>
                                                @else
                                                    <span class="badge-status" style="background:#e9ecef;color:#6c757d">⏳ Pending</span>
                                                @endif
                                                {{-- Payment status --}}
                                                <br>
                                                @php $payStatus = $order->payment_status ?? 'pending'; @endphp
                                                @if($payStatus == 'paid')
                                                    <small class="text-success fw-bold">💳 Paid</small>
                                                @elseif($payStatus == 'failed')
                                                    <small class="text-danger fw-bold">✖ Failed</small>
                                                @else
                                                    <small class="text-warning fw-bold">⏳ Pending</small>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.transaksis.show', $order->id) }}"
                                                   class="btn btn-sm btn-outline-success" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- Pagination --}}
                            @if($orders->hasPages())
                            <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top">
                                <small class="text-muted">
                                    Menampilkan {{ $orders->firstItem() }}–{{ $orders->lastItem() }} dari {{ $orders->total() }} transaksi
                                </small>
                                {{ $orders->appends(request()->query())->links() }}
                            </div>
                            @endif
                        @else
                            <div class="empty-state">
                                <i class="fas fa-receipt"></i>
                                <p>Belum ada transaksi untuk periode yang dipilih.</p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>{{-- end content-area --}}
        </div>{{-- end main-content --}}
    </div>{{-- end admin-container --}}

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
    // ===== GRAFIK PENDAPATAN HARIAN =====
    @if(isset($chartLabels) && count($chartLabels) > 0)
    const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctxRevenue, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [
                {
                    type: 'line',
                    label: 'Tren',
                    data: {!! json_encode($chartData) !!},
                    borderColor: '#20c997',
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    pointRadius: 3,
                    tension: 0.4,
                    order: 1
                },
                {
                    type: 'bar',
                    label: 'Pendapatan (Rp)',
                    data: {!! json_encode($chartData) !!},
                    backgroundColor: 'rgba(40,167,69,0.15)',
                    borderColor: '#28a745',
                    borderWidth: 1.5,
                    borderRadius: 6,
                    order: 2
                }
            ]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: true, position: 'top' },
                tooltip: {
                    callbacks: {
                        label: ctx => ctx.dataset.label + ': Rp ' + Number(ctx.parsed.y).toLocaleString('id-ID')
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f0f0f0' },
                    ticks: { callback: v => 'Rp ' + Number(v).toLocaleString('id-ID') }
                },
                x: { grid: { display: false } }
            }
        }
    });
    @endif

    // ===== GRAFIK PRODUK TERLARIS (Doughnut) =====
    @if(isset($topProducts) && count($topProducts) > 0)
    const ctxProd = document.getElementById('topProductChart').getContext('2d');
    new Chart(ctxProd, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($topProducts->pluck('product_name')) !!},
            datasets: [{
                data: {!! json_encode($topProducts->pluck('total_qty')) !!},
                backgroundColor: ['#28a745','#20c997','#007bff','#fd7e14','#6f42c1','#dc3545','#ffc107'],
                borderWidth: 2,
                borderColor: '#fff',
                hoverOffset: 8
            }]
        },
        options: {
            responsive: true,
            cutout: '60%',
            plugins: {
                legend: { position: 'bottom', labels: { padding: 12, font: { size: 12 } } },
                tooltip: {
                    callbacks: {
                        label: ctx => ctx.label + ': ' + Number(ctx.parsed).toLocaleString('id-ID') + ' unit'
                    }
                }
            }
        }
    });
    @endif
    </script>
</body>
</html>