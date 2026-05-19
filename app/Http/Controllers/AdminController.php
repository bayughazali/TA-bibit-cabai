<?php

// File: app/Http/Controllers/Admin/AdminController.php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    /**
     * Show login form.
     */
    public function showLoginForm()
    {
        return view('admin.login');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        // Debug: Log request data
        Log::info('Login attempt', [
            'method' => $request->method(),
            'all_data' => $request->all(),
            'only_email' => $request->only(['email']),
            'email_value' => $request->input('email'),
            'has_email' => $request->has('email'),
            'filled_email' => $request->filled('email'),
        ]);

        // Validasi dengan pesan error yang jelas
        $validated = $request->validate([
            'email' => [
                'required',
                'string',
                'email:rfc,dns',
                'max:255'
            ],
            'password' => [
                'required',
                'string',
                'min:6'
            ],
        ], [
            'email.required' => 'Email wajib diisi',
            'email.string' => 'Email harus berupa teks',
            'email.email' => 'Format email tidak valid',
            'email.max' => 'Email terlalu panjang',
            'password.required' => 'Password wajib diisi',
            'password.string' => 'Password harus berupa teks',
            'password.min' => 'Password minimal 6 karakter',
        ]);

        // Debug: Log validated data
        Log::info('Validated data', $validated);

        $credentials = [
            'email' => $validated['email'],
            'password' => $validated['password']
        ];

        // Cek apakah user dengan email ini ada
        $user = User::where('email', $credentials['email'])->first();
        
        if (!$user) {
            Log::warning('User not found', ['email' => $credentials['email']]);
            return back()->withErrors([
                'email' => 'Akun admin dengan email ini tidak ditemukan',
            ])->withInput($request->only('email'));
        }

        // Coba login
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            Log::info('Login successful', ['user_id' => Auth::id()]);
            
            return redirect()
                ->intended(route('admin.dashboard'))
                ->with('success', 'Selamat datang, ' . Auth::user()->name . '!');
        }

        Log::warning('Login failed - wrong credentials', ['email' => $credentials['email']]);

        return back()->withErrors([
            'email' => 'Email atau password salah. Silakan coba lagi.',
        ])->withInput($request->only('email'));
    }

    /**
     * Show the admin dashboard.
     */
public function dashboard()
{
     $this->syncProductLabels();
    // Total bibit terjual dari tabel transaksi_details
    $totalSold = \App\Models\TransaksiDetail::whereHas('transaksi', function($q) {
        $q->whereIn('payment_status', ['paid', 'lunas', 'success']);
    })->sum('quantity');

    // Jika masih 0, ambil semua tanpa filter payment_status
    if ($totalSold == 0) {
        $totalSold = \App\Models\TransaksiDetail::sum('quantity');
    }

    // Stok menipis = produk dengan stok > 0 dan stok < 1000
    $lowStock = Product::where('stock', '>', 0)
                       ->where('stock', '<', 1000)
                       ->count();

    $stats = [
        'total_products'  => Product::count(),
        'active_products' => Product::where('status', 'aktif')->count(),
        'total_sold'      => number_format($totalSold, 0, ',', '.'),
        'low_stock'       => $lowStock,
    ];

    $recent_products = Product::latest()->take(5)->get();

    // Grafik pendapatan per hari (30 hari terakhir) - semua transaksi
    $salesChart = \App\Models\Transaksi::selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
        ->where('created_at', '>=', now()->subDays(30))
        ->groupBy('date')
        ->orderBy('date', 'asc')
        ->get();

    $chartLabels = $salesChart->pluck('date')
        ->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))
        ->toArray();
    $chartData = $salesChart->pluck('total')->toArray();

    return view('admin.dashboard', compact('stats', 'recent_products', 'chartLabels', 'chartData'));
}
    /**
     * Show orders management.
     */
    public function orders()
{
    $orders = \App\Models\Transaksi::with(['details.product'])
        ->orderBy('created_at', 'desc')
        ->paginate(15);
    
    return view('admin.orders', compact('orders'));
}

    /**
     * Show users management.
     */
    public function users()
    {
    $users = User::orderBy('id', 'desc')->paginate(15);
    
    $totalUsers    = User::count();
    $totalAdmins   = User::where('is_admin', 1)->count();
    $verifiedUsers = User::whereNotNull('email_verified_at')->count();
    $activeUsers   = User::where('created_at', '>=', now()->subDays(30))->count();

    return view('admin.users', compact(
        'users', 'totalUsers', 'totalAdmins', 'verifiedUsers', 'activeUsers'
    ));
    }

    /**
     * Show reports.
     */
    public function reports()
    {
        return view('admin.reports');
    }

    /**
     * Show settings.
     */
    public function settings()
    {
        return view('admin.settings');
    }

    /**
     * Logout admin.
     */
    public function logout(Request $request)
    {
        Log::info('Admin logout', ['user_id' => Auth::id()]);
        
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')
            ->with('success', 'Anda telah berhasil logout');
    }
 /**
     * Show reports / laporan penjualan.
     * Ganti method laporan() yang ada di AdminController dengan ini.
     */
    public function laporan(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate   = $request->input('end_date',   now()->toDateString());

        // ── Query transaksi ───────────────────────────────────────────────
        $query = \App\Models\Transaksi::with(['details.product'])
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);

        // Filter order_status (bukan payment_status)
        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        // Filter berdasarkan produk (lewat relasi details)
        if ($request->filled('product_id')) {
            $query->whereHas('details', function ($q) use ($request) {
                $q->where('product_id', $request->product_id);
            });
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(15);

        // ── Ringkasan ─────────────────────────────────────────────────────
        $summaryQuery = \App\Models\Transaksi::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        if ($request->filled('status')) {
            $summaryQuery->where('order_status', $request->status);
        }
        if ($request->filled('product_id')) {
            $summaryQuery->whereHas('details', fn($q) => $q->where('product_id', $request->product_id));
        }

        $totalPendapatan = $summaryQuery->sum('total_amount');
        $totalTransaksi  = $summaryQuery->count();

        $totalProdukKeluar = \App\Models\TransaksiDetail::whereHas('transaksi', function ($q) use ($startDate, $endDate, $request) {
            $q->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            if ($request->filled('status')) {
                $q->where('order_status', $request->status);
            }
        })
        ->when($request->filled('product_id'), fn($q) => $q->where('product_id', $request->product_id))
        ->sum('transaksi_details.quantity');

        $summary = [
            'total_pendapatan'    => $totalPendapatan,
            'total_transaksi'     => $totalTransaksi,
            'total_produk_keluar' => $totalProdukKeluar,
            'rata_per_transaksi'  => $totalTransaksi > 0 ? round($totalPendapatan / $totalTransaksi) : 0,
        ];

        // ── Produk terlaris ───────────────────────────────────────────────
        $productSales = \App\Models\TransaksiDetail::selectRaw('
                transaksi_details.product_id,
                products.name as product_name,
                SUM(transaksi_details.quantity) as total_qty,
                SUM(transaksi_details.subtotal) as total_revenue
            ')
            ->join('products', 'transaksi_details.product_id', '=', 'products.id')
            ->whereHas('transaksi', function ($q) use ($startDate, $endDate, $request) {
                $q->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
                if ($request->filled('status')) {
                    $q->where('order_status', $request->status);
                }
            })
            ->when($request->filled('product_id'), fn($q) => $q->where('transaksi_details.product_id', $request->product_id))
            ->groupBy('transaksi_details.product_id', 'products.name')
            ->orderByDesc('total_qty')
            ->get();

        $topProducts = $productSales->take(7);

        // ── Grafik harian ─────────────────────────────────────────────────
        $salesChart = \App\Models\Transaksi::selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->when($request->filled('status'), fn($q) => $q->where('order_status', $request->status))
            ->when($request->filled('product_id'), fn($q) => $q->whereHas('details', fn($d) => $d->where('product_id', $request->product_id)))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $chartLabels = $salesChart->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))->toArray();
        $chartData   = $salesChart->pluck('total')->toArray();

        $products = \App\Models\Product::orderBy('name')->get();

        return view('admin.laporan', compact(
            'orders', 'summary', 'productSales', 'topProducts',
            'chartLabels', 'chartData', 'products'
        ));
    }

/**
 * Sync label produk berdasarkan total terjual dari transaksi nyata
 */
private function syncProductLabels()
{
    // Ambil top 10 produk terlaris berdasarkan transaksi nyata
    $bestSellingIds = \Illuminate\Support\Facades\DB::table('transaksi_details')
        ->select('product_id', \Illuminate\Support\Facades\DB::raw('SUM(quantity) as total_sold'))
        ->groupBy('product_id')
        ->orderByDesc('total_sold')
        ->take(10)
        ->pluck('product_id')
        ->toArray();

    // Reset semua label dulu
    Product::where('stock', '>', 0)
           ->whereNotIn('id', $bestSellingIds)
           ->update(['label' => 'tersedia']);

    Product::where('stock', '<=', 0)
           ->update(['label' => 'habis']);

    // Set label terlaris untuk top 10
    if (!empty($bestSellingIds)) {
        Product::whereIn('id', $bestSellingIds)
               ->where('stock', '>', 0)
               ->update(['label' => 'terlaris']);
    }
}
    
}
