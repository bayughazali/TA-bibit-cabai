<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        // Validasi input dengan semua requirement
        $request->validate([
            'email' => [
                'required',
                'email',
                'regex:/@gmail\.com$/',
                'regex:/^\S+$/', // Tidak boleh mengandung spasi
            ],
            'password' => [
                'required',
                'min:8',
                'regex:/^\S+$/', // Tidak boleh mengandung spasi
            ],
            'remember' => 'required|accepted',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.regex' => 'Email harus menggunakan domain @gmail.com dan tidak boleh mengandung spasi.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal harus 8 karakter.',
            'password.regex' => 'Password tidak boleh mengandung spasi.',
            'remember.required' => 'Anda harus mencentang "Ingat saya".',
            'remember.accepted' => 'Anda harus mencentang "Ingat saya".',
        ]);

        // Ambil credentials yang sudah divalidasi
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        // Cari user dengan email yang diberikan dan pastikan bukan admin
        $user = User::where('email', $credentials['email'])
                    ->where(function ($query) {
                        $query->where('is_admin', false)
                              ->orWhereNull('is_admin');
                    })
                    ->first();

        // Verifikasi apakah user ada dan password cocok
        if (!$user) {
            return back()->withErrors([
                'email' => 'Email tidak terdaftar atau Anda mencoba login admin di halaman user.',
            ])->onlyInput('email');
        }

        // Verifikasi password
        if (!Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'password' => 'Password yang Anda masukkan salah.',
            ])->onlyInput('email');
        }

        // Login berhasil
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();
        
        // Pastikan session admin tidak ada
        session()->forget('is_admin');
        
        return redirect()->intended('/')->with('success', 'Selamat datang, ' . $user->name . '!');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda telah berhasil logout.');
    }
}