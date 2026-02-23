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
        $stats = [
            'total_products' => Product::count(),
            'active_products' => Product::where('status', 'aktif')->count(),
            'out_of_stock' => Product::where('stock', 0)->count(),
            'low_stock' => Product::where('stock', '>', 0)->where('stock', '<=', 5)->count(),
        ];

        $recent_products = Product::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_products'));
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
        return view('admin.users');
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

// Tambahkan method ini ke dalam AdminController.php Anda

/**
 * Display orders page with shipping and status information
 */


}