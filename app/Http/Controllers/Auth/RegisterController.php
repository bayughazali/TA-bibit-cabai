<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        // Validasi dasar terlebih dahulu
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:12|max:255',
            'email' => 'required|string|email|max:255|unique:users|regex:/@gmail\.com$/|regex:/^\S+$/',
            'phone' => 'required|string|regex:/^[0-9]+$/|min:12|max:20|regex:/^\S+$/',
            'address' => 'required|string|min:12|max:500',
            'password' => 'required|string|min:8|confirmed|regex:/^\S+$/',
            'agree' => 'required|accepted',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.min' => 'Nama minimal harus 12 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'email.regex' => 'Email harus menggunakan domain @gmail.com dan tidak boleh mengandung spasi.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'phone.regex' => 'Nomor telepon hanya boleh berisi angka dan tidak boleh mengandung spasi.',
            'phone.min' => 'Nomor telepon minimal harus 12 karakter.',
            'address.required' => 'Alamat wajib diisi.',
            'address.min' => 'Alamat minimal harus 12 karakter.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal harus 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.regex' => 'Password tidak boleh mengandung spasi.',
            'agree.required' => 'Anda harus menyetujui syarat dan ketentuan.',
            'agree.accepted' => 'Anda harus menyetujui syarat dan ketentuan.',
        ]);

        // Validasi password familiar
        $validator->after(function ($validator) use ($request) {
            if ($request->has('password') && $this->isCommonPassword($request->password)) {
                $validator->errors()->add('password', 'Password yang Anda gunakan terlalu umum dan sering digunakan. Silakan pilih password yang lebih unik untuk keamanan akun Anda.');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'password' => Hash::make($validated['password']),
            'is_admin' => false, // Pastikan user biasa bukan admin
        ]);

        // Arahkan ke login dengan pesan sukses
        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login dengan akun Anda.');
    }

    /**
     * Cek apakah password termasuk password yang umum/familiar
     */
    private function isCommonPassword(string $password): bool
    {
        $commonPasswords = [
            // Password numerik umum
            '12345678', '123456789', '1234567890',
            '87654321', '11111111', '22222222', '33333333', '44444444', '55555555',
            '66666666', '77777777', '88888888', '99999999', '00000000',
            
            // Password alfabetik umum
            'password', 'Password', 'PASSWORD', 'password123', 'Password123',
            'qwerty123', 'qwertyui', 'asdfghjk', 'zxcvbnm123',
            'abcd1234', 'admin123', 'user1234', 'welcome123',
            
            // Password Indonesia umum
            'indonesia', 'Indonesia', 'garuda123', 'nusantara',
            'jakarta123', 'bali2023', 'surabaya', 'bandung123',
            'password1', 'rahasia123', 'bismillah',
            
            // Kombinasi tanggal umum
            '01011990', '01011995', '01012000', '17081945',
            '12345abc', 'abc12345', '123qwe123', 'qwe12345',
            
            // Pattern keyboard umum
            'qwerty12', 'asdf1234', 'zxcv1234', '1qaz2wsx',
            '1q2w3e4r', 'qazwsxed', 'plokijuh',
            
            // Password dengan nama umum
            'admin1234', 'guest1234', 'test1234', 'demo1234',
            'sample123', 'example123', 'default123',
            
            // Password sosial media umum  
            'facebook', 'instagram', 'whatsapp', 'twitter123',
            'gmail123', 'yahoo123', 'google123',
            
            // Password gaming umum
            'minecraft', 'roblox123', 'fortnite', 'pubg1234',
            'mobilelegend', 'freefire', 'valorant',
            
            // Password device umum
            'android123', 'iphone123', 'samsung123', 'nokia1234',
            
            // Tahun umum
            '20232023', '20242024', '19901990', '20002000',
        ];

        return in_array(strtolower($password), array_map('strtolower', $commonPasswords));
    }
}