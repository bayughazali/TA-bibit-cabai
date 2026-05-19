<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Pembatalan - Admin</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f8f9fa; }
        .admin-container { display: flex; min-height: 100vh; }

        .sidebar {
            width: 280px; background: linear-gradient(180deg, #28a745, #20c997);
            color: white; min-height: 100vh; position: fixed; top: 0; left: 0; z-index: 1000;
        }
        .sidebar-header { padding: 25px 20px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar-header h3 { font-size: 1.8rem; font-weight: bold; margin-bottom: 5px; }
        .sidebar-header p { opacity: 0.8; font-size: 0.9rem; }
        .sidebar-menu { padding: 20px 0; }
        .menu-item {
            display: block; padding: 15px 25px; color: white;
            text-decoration: none; transition: all 0.3s; border-left: 4px solid transparent;
        }
        .menu-item:hover, .menu-item.active {
            background: rgba(255,255,255,0.2); color: white;
            border-left-color: #fff; transform: translateX(5px);
        }
        .menu-item i { width: 20px; margin-right: 10px; }

        .main-content { margin-left: 280px; flex: 1; }
        .admin-header {
            background: white; padding: 20px 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-bottom: 1px solid #dee2e6;
        }
        .content-area { padding: 30px; }

        .badge-status { padding: 4px 10px; border-radius: 12px; font-size: 0.75rem; font-weight: 600; }
        .badge-pending   { background: #fff3cd; color: #856404; }
        .badge-approved  { background: #d4edda; color: #155724; }
        .badge-rejected  { background: #f8d7da; color: #721c24; }

        .card { border: none; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .card-header { background: white; border-bottom: 2px solid #f8f9fa; border-radius: 12px 12px 0 0 !important; }

        .back-button {
            position: fixed; top: 20px; right: 20px; z-index: 1100;
            background: linear-gradient(45deg, #dc3545, #c82333);
            color: white; border: none; padding: 12px 20px;
            border-radius: 25px; font-weight: 600; text-decoration: none; transition: all 0.3s;
        }
        .back-button:hover { color: white; transform: translateY(-2px); }

        .table th { background: #f8f9fa; font-weight: 600; color: #495057; font-size: 0.85rem; }
        .table td { vertical-align: middle; }

       .action-btn { padding: 5px 12px; border-radius: 6px; font-size: 0.8rem; font-weight: 600; border: none; cursor: pointer; }
.action-btn.btn-success { background-color: #28a745 !important; color: white !important; opacity: 1 !important; }
.action-btn.btn-danger  { background-color: #dc3545 !important; color: white !important; opacity: 1 !important; }
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
            <small class="text-light">{{ Auth::user()->name ?? 'Admin' }}</small>
        </div>
        <div class="sidebar-menu">
            <a href="{{ route('admin.dashboard') }}" class="menu-item">
                <i class="fas fa-tachometer-alt"></i>Dashboard
            </a>
            <a href="{{ route('admin.products.index') }}" class="menu-item">
                <i class="fas fa-seedling"></i>Kelola Produk
            </a>
            <a href="{{ route('admin.orders') }}" class="menu-item">
                <i class="fas fa-shopping-cart"></i>Pesanan
            </a>
            <a href="{{ route('admin.cancellations') }}" class="menu-item active">
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

    {{-- Main Content --}}
    <div class="main-content">
        <div class="admin-header">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Pengajuan Pembatalan</li>
                </ol>
            </nav>
            <h2>Pengajuan Pembatalan Pesanan</h2>
            <p class="mb-0">Kelola semua permintaan pembatalan dari pelanggan.</p>
        </div>

        <div class="content-area">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Stats --}}
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card p-3 border-start border-warning border-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-warning fw-bold small">MENUNGGU REVIEW</div>
                                <div class="fs-3 fw-bold">{{ $cancellations->where('status','pending')->count() }}</div>
                            </div>
                            <i class="fas fa-clock fa-2x text-muted"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-3 border-start border-success border-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-success fw-bold small">DISETUJUI</div>
                                <div class="fs-3 fw-bold">{{ $cancellations->where('status','approved')->count() }}</div>
                            </div>
                            <i class="fas fa-check-circle fa-2x text-muted"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-3 border-start border-danger border-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-danger fw-bold small">DITOLAK</div>
                                <div class="fs-3 fw-bold">{{ $cancellations->where('status','rejected')->count() }}</div>
                            </div>
                            <i class="fas fa-times-circle fa-2x text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabel --}}
            <div class="card">
                <div class="card-header py-3">
                    <h6 class="mb-0 fw-bold text-success">
                        <i class="fas fa-times-circle me-2"></i>
                        Daftar Pengajuan Pembatalan ({{ $cancellations->total() }})
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-3">Invoice</th>
                                    <th>Pelanggan</th>
                                    <th>Alasan</th>
                                    <th>Keterangan</th>
                                    <th>Tgl Pengajuan</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cancellations as $item)
                                <tr>
                                    <td class="ps-3">
                                        <strong class="d-block">{{ $item->transaksi->invoice_number ?? '-' }}</strong>
                                        <small class="text-muted">
                                            Rp{{ number_format($item->transaksi->total_amount ?? 0, 0, ',', '.') }}
                                        </small>
                                    </td>
                                    <td>
                                        <strong>{{ $item->user->name ?? '-' }}</strong><br>
                                        <small class="text-muted">{{ $item->user->email ?? '-' }}</small>
                                    </td>
                                    <td style="max-width:180px;">
                                        <small>{{ $item->reason }}</small>
                                    </td>
                                    <td style="max-width:200px;">
                                        <small class="text-muted">{{ $item->description ?? '-' }}</small>
                                    </td>
                                    <td>
                                        <small>{{ $item->created_at->format('d M Y') }}</small><br>
                                        <small class="text-muted">{{ $item->created_at->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <span class="badge-status badge-{{ $item->status }}">
                                            {{ $item->status === 'pending' ? 'Menunggu' : ($item->status === 'approved' ? 'Disetujui' : 'Ditolak') }}
                                        </span>
                                        @if($item->reviewed_at)
                                            <br><small class="text-muted">{{ $item->reviewed_at->format('d M Y') }}</small>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($item->status === 'pending')
                                            {{-- Tombol Setujui --}}
                                            <form action="{{ route('admin.cancellations.approve', $item->id) }}" method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Setujui pembatalan pesanan {{ $item->transaksi->invoice_number ?? '' }}?')">
                                                @csrf
                                                <textarea name="admin_note" style="display:none"> disetujui</textarea>
                                                <button type="submit" class="action-btn btn-success text-white me-1">
                                                    <i class="fas fa-check me-1"></i>Setujui
                                                </button>
                                            </form>

                                            {{-- Tombol Tolak --}}
                                            <button type="button"
                                                    class="action-btn btn-danger text-white"
                                                    onclick="showRejectModal({{ $item->id }}, '{{ $item->transaksi->invoice_number ?? '' }}')">
                                                <i class="fas fa-times me-1"></i>Tolak
                                            </button>
                                        @else
                                            @if($item->status === 'approved')
                                                <span class="action-btn btn-success">Disetujui</span>
                                            @elseif($item->status === 'rejected')
                                                <span class="action-btn btn-danger">Ditolak</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                        <p class="text-muted">Belum ada pengajuan pembatalan</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($cancellations->hasPages())
                    <div class="d-flex justify-content-between align-items-center p-3 border-top">
                        <small class="text-muted">
                            Menampilkan {{ $cancellations->firstItem() }} - {{ $cancellations->lastItem() }}
                            dari {{ $cancellations->total() }} pengajuan
                        </small>
                        {{ $cancellations->links() }}
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Modal Tolak --}}
<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content border-0" style="border-radius:14px; overflow:hidden;">
            <div class="modal-header bg-danger text-white">
                <h6 class="modal-title fw-bold mb-0">
                    <i class="fas fa-times-circle me-2"></i>Tolak Pengajuan
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <p class="mb-3">Pesanan: <strong id="rejectInvoice"></strong></p>
                    <label class="form-label fw-semibold">
                        Alasan Penolakan <span class="text-danger">*</span>
                    </label>
                    <textarea name="admin_note" class="form-control" rows="3"
                              placeholder="Jelaskan alasan penolakan kepada pelanggan..."
                              required maxlength="500"></textarea>
                    <small class="text-muted">Wajib diisi. Maks. 500 karakter.</small>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger fw-bold">
                        <i class="fas fa-times me-1"></i>Tolak Pengajuan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function showRejectModal(id, invoice) {
    document.getElementById('rejectInvoice').textContent = invoice;
    document.getElementById('rejectForm').action = `/admin/cancellations/${id}/reject`;
    new bootstrap.Modal(document.getElementById('rejectModal')).show();
}
</script>

</body>
</html>