<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    // ─────────────────────────────────────────────
    // STEP 1 — Show form: enter email
    // ─────────────────────────────────────────────
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    // ─────────────────────────────────────────────
    // STEP 1 — Handle: send OTP to email
    // ─────────────────────────────────────────────
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'regex:/@gmail\.com$/'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
            'email.regex'    => 'Email harus menggunakan domain @gmail.com.',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email tidak ditemukan di sistem kami.',
            ])->withInput();
        }

        // Generate 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Delete any existing token for this email
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Store hashed OTP + expiry (10 minutes)
        DB::table('password_reset_tokens')->insert([
            'email'      => $request->email,
            'token'      => Hash::make($otp),
            'created_at' => Carbon::now(),
        ]);

        // Send OTP via email
        Mail::send('emails.otp', ['otp' => $otp, 'name' => $user->name], function ($message) use ($request) {
            $message->to($request->email)
                    ->subject('Kode Verifikasi Reset Password - Bibit Cabai Bondowoso');
        });

        // Store email in session for next steps
        $request->session()->put('reset_email', $request->email);

        return redirect()->route('password.verify-otp-form')
                         ->with('success', 'Kode verifikasi telah dikirim ke email Anda. Berlaku selama 10 menit.');
    }

    // ─────────────────────────────────────────────
    // STEP 2 — Show form: enter OTP
    // ─────────────────────────────────────────────
    public function showVerifyOtpForm(Request $request)
    {
        if (!$request->session()->has('reset_email')) {
            return redirect()->route('password.request')
                             ->with('error', 'Silakan masukkan email Anda terlebih dahulu.');
        }

        return view('auth.verify-otp');
    }

    // ─────────────────────────────────────────────
    // STEP 2 — Handle: verify OTP
    // ─────────────────────────────────────────────
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'digits:6'],
        ], [
            'otp.required' => 'Kode OTP wajib diisi.',
            'otp.digits'   => 'Kode OTP harus 6 digit angka.',
        ]);

        $email = $request->session()->get('reset_email');

        if (!$email) {
            return redirect()->route('password.request')
                             ->with('error', 'Sesi tidak valid. Silakan ulangi dari awal.');
        }

        $record = DB::table('password_reset_tokens')
                    ->where('email', $email)
                    ->first();

        if (!$record) {
            return back()->with('error', 'Kode OTP tidak ditemukan. Silakan kirim ulang.');
        }

        // Check expiry: 10 minutes
        $createdAt = Carbon::parse($record->created_at);
        if (Carbon::now()->diffInMinutes($createdAt) > 10) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            return back()->with('error', 'Kode OTP sudah kadaluarsa. Silakan kirim ulang kode.');
        }

        // Verify OTP
        if (!Hash::check($request->otp, $record->token)) {
            return back()->with('error', 'Kode OTP salah. Periksa kembali kode yang Anda masukkan.');
        }

        // Generate a verified token for the next step
        $verifiedToken = Str::random(60);
        DB::table('password_reset_tokens')
            ->where('email', $email)
            ->update(['token' => Hash::make($verifiedToken)]);

        $request->session()->put('reset_token', $verifiedToken);

        return redirect()->route('password.reset-form')
                         ->with('success', 'Kode berhasil diverifikasi! Silakan buat password baru.');
    }

    // ─────────────────────────────────────────────
    // STEP 2 — Handle: resend OTP
    // ─────────────────────────────────────────────
    public function resendOtp(Request $request)
    {
        $email = $request->session()->get('reset_email');

        if (!$email) {
            return redirect()->route('password.request')
                             ->with('error', 'Sesi tidak valid. Silakan masukkan email Anda kembali.');
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            return redirect()->route('password.request')
                             ->with('error', 'Email tidak ditemukan.');
        }

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        DB::table('password_reset_tokens')->where('email', $email)->delete();
        DB::table('password_reset_tokens')->insert([
            'email'      => $email,
            'token'      => Hash::make($otp),
            'created_at' => Carbon::now(),
        ]);

        Mail::send('emails.otp', ['otp' => $otp, 'name' => $user->name], function ($message) use ($email) {
            $message->to($email)
                    ->subject('Kode Verifikasi Reset Password (Kirim Ulang) - Bibit Cabai Bondowoso');
        });

        return back()->with('success', 'Kode OTP baru telah dikirim ke email Anda.');
    }

    // ─────────────────────────────────────────────
    // STEP 3 — Show form: enter new password
    // ─────────────────────────────────────────────
    public function showResetForm(Request $request)
    {
        if (!$request->session()->has('reset_token') || !$request->session()->has('reset_email')) {
            return redirect()->route('password.request')
                             ->with('error', 'Sesi tidak valid. Silakan ulangi dari awal.');
        }

        return view('auth.reset-password');
    }

    // ─────────────────────────────────────────────
    // STEP 3 — Handle: save new password
    // ─────────────────────────────────────────────
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password'              => ['required', 'min:8', 'confirmed', 'regex:/^\S+$/'],
            'password_confirmation' => ['required'],
        ], [
            'password.required'  => 'Password baru wajib diisi.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.regex'     => 'Password tidak boleh mengandung spasi.',
        ]);

        $email = $request->session()->get('reset_email');
        $token = $request->session()->get('reset_token');

        if (!$email || !$token) {
            return redirect()->route('password.request')
                             ->with('error', 'Sesi tidak valid. Silakan ulangi dari awal.');
        }

        $record = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (!$record || !Hash::check($token, $record->token)) {
            return redirect()->route('password.request')
                             ->with('error', 'Token tidak valid. Silakan ulangi dari awal.');
        }

        // Update password
        User::where('email', $email)->update([
            'password' => Hash::make($request->password),
        ]);

        // Clean up
        DB::table('password_reset_tokens')->where('email', $email)->delete();
        $request->session()->forget(['reset_email', 'reset_token']);

        return redirect()->route('login')
                         ->with('success', 'Password berhasil diubah! Silakan login dengan password baru Anda.');
    }
}