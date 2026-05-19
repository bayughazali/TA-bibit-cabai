<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductRecommendationController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\AdminForgotPasswordController; // ← tambahkan ini

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');


// Produk terlaris
Route::get('/produk-terlaris', [ProductRecommendationController::class, 'index'])
    ->name('products.best-selling');
// Detail produk (public)
Route::get('/produk/{id}', [ProductController::class, 'publicShow'])->name('products.show');

// Contact Us
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

/*
|--------------------------------------------------------------------------
| Checkout Routes - PINDAH KE SINI (di luar admin group)
|--------------------------------------------------------------------------
*/

Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::post('/process', [CheckoutController::class, 'process'])->name('process');
    Route::get('/success/{id}', [CheckoutController::class, 'success'])->name('success');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('api')->name('api.')->group(function () {
    Route::get('/best-selling-products', [ProductRecommendationController::class, 'apiGetBestSelling'])
        ->name('best-selling-products');
});
Route::middleware('auth')->group(function () {
    // Profil
    Route::get('/profile',                  [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update',          [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password',        [ProfileController::class, 'updatePassword'])->name('profile.password');
 
    // Pesanan saya
    Route::get('/pesanan-saya',             [ProfileController::class, 'orders'])->name('orders.my');
    Route::get('/pesanan-saya/{id}',        [ProfileController::class, 'orderDetail'])->name('orders.detail');

 Route::get('/orders/{id}/cancel',  [ProfileController::class, 'cancelForm'])
        ->name('orders.cancel');          // ← nama harus orders.cancel
    Route::post('/orders/{id}/cancel', [ProfileController::class, 'cancelStore'])
        ->name('orders.cancel.store');    // ← nama harus orders.cancel.store


});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    
   Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('admin.login');
    })->name('login.form');
    
    Route::post('/login', [AdminController::class, 'login'])->name('login');

    Route::prefix('password')->name('password.')->group(function () {  // ← ganti 'admin' jadi 'password'
        Route::get('/forgot-password',  [AdminForgotPasswordController::class, 'showForgotForm'])->name('request');
        Route::post('/forgot-password', [AdminForgotPasswordController::class, 'sendOtp'])->name('email');
        Route::get('/verify-otp',       [AdminForgotPasswordController::class, 'showVerifyOtpForm'])->name('verify-otp-form');
        Route::post('/verify-otp',      [AdminForgotPasswordController::class, 'verifyOtp'])->name('verify-otp');
        Route::post('/resend-otp',      [AdminForgotPasswordController::class, 'resendOtp'])->name('resend-otp');
        Route::get('/reset-password',   [AdminForgotPasswordController::class, 'showResetForm'])->name('reset-form');
        Route::post('/reset-password',  [AdminForgotPasswordController::class, 'resetPassword'])->name('update');
    });
});
    
    // Protected admin routes (requires authentication)
   Route::middleware(['auth', 'is_admin'])->group(function () {
        
      
        // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    // Di dalam group admin (bersama route lainnya):
 Route::get('/laporan', [AdminController::class, 'laporan'])->name('laporan');
 Route::get('/laporan/export', function() {
    return redirect()->route('admin.laporan')->with('info', 'Fitur export sedang dalam pengembangan.');
})->name('laporan.export');
Route::get('/transaksis/{id}', [TransaksiController::class, 'show'])->name('transaksis.show');
// nama lengkapnya: admin.transaksis.show

// HAPUS line 136 yang lama:
// Route::get('/admin/reports', [AdminController::class, 'reports'])->name('admin.reports');

        // Product CRUD Routes
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.delete');

        // Transaksi Management Routes
        Route::get('/transaksis', [TransaksiController::class, 'index'])->name('transaksis.index');
        Route::get('/transaksis/{id}', [TransaksiController::class, 'show'])->name('transaksis.show');
        Route::get('/transaksis/{id}/edit', [TransaksiController::class, 'edit'])->name('transaksis.edit');
        Route::put('/transaksis/{id}', [TransaksiController::class, 'update'])->name('transaksis.update');
        Route::delete('/transaksis/{id}', [TransaksiController::class, 'destroy'])->name('transaksis.destroy');
        Route::post('/transaksis/{id}/update-status', [TransaksiController::class, 'updateStatus'])->name('transaksis.update-status');
        Route::post('/transaksis/{id}/update-payment-status', [TransaksiController::class, 'updatePaymentStatus'])->name('transaksis.update-payment-status');
        Route::post('/transaksis/{id}/cancel', [TransaksiController::class, 'cancel'])->name('transaksis.cancel');
        Route::get('/transaksis-export', [TransaksiController::class, 'export'])->name('transaksis.export');
        Route::get('/transaksis/{id}/print', [TransaksiController::class, 'printInvoice'])->name('transaksis.print');
        Route::post('/orders/{id}/update-status', [TransaksiController::class, 'updateStatus'])->name('orders.updateStatus');
        // Cancellation Admin Routes
        Route::get('/cancellations', [TransaksiController::class, 'cancellations'])
    ->name('cancellations');
Route::post('/cancellations/{id}/approve', [TransaksiController::class, 'approveCancellation'])
    ->name('cancellations.approve');
Route::post('/cancellations/{id}/reject', [TransaksiController::class, 'rejectCancellation'])
    ->name('cancellations.reject');

        // Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        // Other admin routes
        Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
        
        // Admin logout
        Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
    });

    
}); // ← Penutup admin group yang benar (HANYA SATU)

//lupa password
        Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])
     ->name('password.request');
 
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendOtp'])
     ->name('password.email');
 
Route::get('/verify-otp', [ForgotPasswordController::class, 'showVerifyOtpForm'])
     ->name('password.verify-otp-form');
 
Route::post('/verify-otp', [ForgotPasswordController::class, 'verifyOtp'])
     ->name('password.verify-otp');
 
Route::post('/resend-otp', [ForgotPasswordController::class, 'resendOtp'])
     ->name('password.resend-otp');
 
Route::get('/reset-password', [ForgotPasswordController::class, 'showResetForm'])
     ->name('password.reset-form');
 
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])
     ->name('password.update');
 
