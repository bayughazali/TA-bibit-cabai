<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Services\FonnteService;

class AdminForgotPasswordController extends Controller
{
    // ─────────────────────────────────────────────────────────
    // STEP 1 — Tampilkan form input email
    // ─────────────────────────────────────────────────────────

protected $fonnte;  // ✅

public function __construct(FonnteService $fonnte)
{
    $this->fonnte = $fonnte;
}

    public function showForgotForm()
    {
        
        return view('admin.forgot-password');
    }

    // ─────────────────────────────────────────────────────────
    // STEP 1 — Proses: validasi email & kirim OTP
    // ─────────────────────────────────────────────────────────
   public function sendOtp(Request $request)
{
    $method = $request->input('method', 'email');

    if ($method === 'whatsapp') {
        return $this->sendOtpViaWhatsapp($request);
    }

    return $this->sendOtpViaEmail($request);
}

private function sendOtpViaEmail(Request $request)
{
    $request->validate([
        'email' => ['required', 'email'],
    ], [
        'email.required' => 'Email wajib diisi.',
        'email.email'    => 'Format email tidak valid.',
    ]);

    $admin = User::where('email', $request->email)
                 ->where('is_admin', true)
                 ->first();

    if (!$admin) {
        return back()->withErrors([
            'email' => 'Email tidak terdaftar sebagai akun admin.',
        ])->withInput();
    }

    $otp = $this->generateAndStoreOtp($request->email);

    Mail::send('emails.admin-otp', [
        'otp'  => $otp,
        'name' => $admin->name ?? 'Admin',
    ], function ($message) use ($request) {
        $message->to($request->email)
                ->subject('Kode Verifikasi Reset Password Admin - Bibit Cabai');
    });

    $request->session()->put('admin_reset_email', $request->email);
    $request->session()->put('admin_reset_method', 'email');

    return redirect()->route('admin.password.verify-otp-form')
                     ->with('success', 'Kode OTP telah dikirim ke email admin Anda. Berlaku 3 menit.');
}

private function sendOtpViaWhatsapp(Request $request)
{
   $request->validate([
    'phone' => ['required', 'min:10', 'max:15'],
], [
    'phone.required' => 'Nomor WhatsApp wajib diisi.',
    'phone.min'      => 'Nomor WhatsApp minimal 10 digit.',
    'phone.max'      => 'Nomor WhatsApp maksimal 15 digit.',
]);

$phone = preg_replace('/\D/', '', $request->phone);

$admin = User::where('is_admin', true)
             ->where(function ($q) use ($phone, $request) {
                 $q->where('phone', $phone)
                   ->orWhere('phone', '+' . $phone)
                   ->orWhere('phone', $request->phone)
                   ->orWhere('phone', '0' . ltrim($phone, '0'))
                   ->orWhere('phone', '+62' . ltrim($phone, '0'));
             })
             ->first();

$phoneFormatted = FonnteService::formatPhone($phone);

    if (!$admin) {
        return back()->withErrors([
            'phone' => 'Nomor ini tidak terdaftar sebagai admin.',
        ])->withInput();
    }

    $otp = $this->generateAndStoreOtp($admin->email);

    $pesan = "🛡️ *Bibit Cabai Bondowoso — Admin*\n\n"
           . "Halo {$admin->name},\n\n"
           . "Kode verifikasi reset password admin Anda:\n\n"
           . "*{$otp}*\n\n"
           . "Berlaku selama *3 menit*.\n"
           . "Jangan berikan kode ini kepada siapapun.";

    \Illuminate\Support\Facades\Log::info('Fonnte Admin Debug', [
    'phone_input'     => $request->phone,
    'phone_formatted' => $phoneFormatted,
    'admin_found'     => $admin->name,
    'admin_phone_db'  => $admin->phone,
]);

$sent = $this->fonnte->sendMessage($phoneFormatted, $pesan);

if (!$sent) {
    \Illuminate\Support\Facades\Log::error('Fonnte Admin GAGAL', [
        'phone_formatted' => $phoneFormatted,
    ]);
    return back()->with('error', 'Gagal mengirim pesan WhatsApp. Coba lagi.');
}

    $request->session()->put('admin_reset_email', $admin->email);
    $request->session()->put('admin_reset_method', 'whatsapp');
    $request->session()->put('admin_reset_phone_display', '****' . substr($request->phone, -4));

    return redirect()->route('admin.password.verify-otp-form')
                     ->with('success', 'Kode OTP dikirim ke WhatsApp admin Anda. Berlaku 3 menit.');
}

private function generateAndStoreOtp(string $email): string
{
    $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

    DB::table('password_reset_tokens')->where('email', $email)->delete();
    DB::table('password_reset_tokens')->insert([
        'email'      => $email,
        'token'      => Hash::make($otp),
        'created_at' => Carbon::now(),
    ]);

    return $otp;
}

    // ─────────────────────────────────────────────────────────
    // STEP 2 — Tampilkan form input OTP
    // ─────────────────────────────────────────────────────────
    public function showVerifyOtpForm(Request $request)
    {
        if (!$request->session()->has('admin_reset_email')) {
            return redirect()->route('admin.password.request')
                             ->with('error', 'Silakan masukkan email admin terlebih dahulu.');
        }

        return view('admin.verify-otp');
    }

    // ─────────────────────────────────────────────────────────
    // STEP 2 — Proses: verifikasi OTP
    // ─────────────────────────────────────────────────────────
   // STEP 2 — Proses: verifikasi OTP
public function verifyOtp(Request $request)
{
    $request->validate([
        'otp' => ['required', 'digits:6'],
    ], [
        'otp.required' => 'Kode OTP wajib diisi.',
        'otp.digits'   => 'Kode OTP harus 6 digit angka.',
    ]);

    $email = $request->session()->get('admin_reset_email');

    if (!$email) {
        return redirect()->route('admin.password.request')
                         ->with('error', 'Sesi tidak valid. Silakan ulangi dari awal.');
    }

    $record = DB::table('password_reset_tokens')->where('email', $email)->first();

    if (!$record) {
        return back()->with('error', 'Kode OTP tidak ditemukan. Silakan kirim ulang.');
    }

    // ✅ CEK EXPIRED — hapus record & paksa user kirim ulang
   if (Carbon::now()->diffInSeconds(Carbon::parse($record->created_at)) >= 180) {
        DB::table('password_reset_tokens')->where('email', $email)->delete();
        return back()->with('otp_expired', true)  // flag khusus untuk view
                     ->with('error', 'Kode OTP sudah kadaluarsa (3 menit). Silakan kirim ulang kode.');
    }

    if (!Hash::check($request->otp, $record->token)) {
    return back()
        ->with('error', 'Kode OTP salah. Waktu berkurang 30 detik.')
        ->with('otp_wrong', true); // ← flag untuk kurangi timer di frontend
}

    $verifiedToken = Str::random(60);
    DB::table('password_reset_tokens')
        ->where('email', $email)
        ->update(['token' => Hash::make($verifiedToken)]);

    $request->session()->put('admin_reset_token', $verifiedToken);

    return redirect()->route('admin.password.reset-form')
                     ->with('success', 'Kode berhasil diverifikasi! Silakan buat password baru.');
}
    // ─────────────────────────────────────────────────────────
    // STEP 2 — Kirim ulang OTP
    // ─────────────────────────────────────────────────────────
   public function resendOtp(Request $request)
{
    $email  = $request->session()->get('admin_reset_email');
    $method = $request->session()->get('admin_reset_method', 'email');

    if (!$email) {
        return redirect()->route('admin.password.request')
                         ->with('error', 'Sesi tidak valid.');
    }

    $admin = User::where('email', $email)->where('is_admin', true)->first();
    if (!$admin) {
        return redirect()->route('admin.password.request')
                         ->with('error', 'Akun admin tidak ditemukan.');
    }

    $otp = $this->generateAndStoreOtp($email);

    if ($method === 'whatsapp') {
        $phone = FonnteService::formatPhone(
            preg_replace('/^\+/', '', $admin->phone)
        );
        $pesan = "🛡️ *Bibit Cabai Bondowoso — Admin*\n\n"
               . "Halo {$admin->name},\n\n"
               . "Kode verifikasi baru Anda:\n\n"
               . "*{$otp}*\n\n"
               . "Berlaku selama *3 menit*.";

        $sent = $this->fonnte->sendMessage($phone, $pesan);
        if (!$sent) {
            return back()->with('error', 'Gagal mengirim ulang ke WhatsApp.');
        }
    } else {
        Mail::send('emails.admin-otp', [
            'otp'  => $otp,
            'name' => $admin->name ?? 'Admin',
        ], function ($message) use ($email) {
            $message->to($email)
                    ->subject('Kode OTP Baru (Kirim Ulang) - Bibit Cabai Admin');
        });
    }

    return back()->with('success', 'Kode OTP baru telah dikirim.');
}

    // ─────────────────────────────────────────────────────────
    // STEP 3 — Tampilkan form password baru
    // ─────────────────────────────────────────────────────────
    public function showResetForm(Request $request)
    {
        if (!$request->session()->has('admin_reset_token') ||
            !$request->session()->has('admin_reset_email')) {
            return redirect()->route('admin.password.request')
                             ->with('error', 'Sesi tidak valid. Silakan ulangi dari awal.');
        }

        return view('admin.reset-password');
    }

    // ─────────────────────────────────────────────────────────
    // STEP 3 — Proses: simpan password baru
    // ─────────────────────────────────────────────────────────
   // ─────────────────────────────────────────────────────────
// STEP 3 — Proses: simpan password baru
// ─────────────────────────────────────────────────────────
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

    $email = $request->session()->get('admin_reset_email');
    $token = $request->session()->get('admin_reset_token');

    if (!$email || !$token) {
        return redirect()->route('admin.password.request')
                         ->with('error', 'Sesi tidak valid. Silakan ulangi dari awal.');
    }

    $record = DB::table('password_reset_tokens')->where('email', $email)->first();

    if (!$record || !Hash::check($token, $record->token)) {
        return redirect()->route('admin.password.request')
                         ->with('error', 'Token tidak valid. Silakan ulangi dari awal.');
    }

    // ✅ CEK PASSWORD LAMA — tolak jika sama dengan password sekarang
    $admin = User::where('email', $email)->where('is_admin', true)->first();

    if ($admin && Hash::check($request->password, $admin->password)) {
        return back()->withErrors([
            'password' => 'Password baru tidak boleh sama dengan password yang sedang digunakan.',
        ])->withInput();
    }

    User::where('email', $email)
        ->where('is_admin', true)
        ->update(['password' => Hash::make($request->password)]);

    DB::table('password_reset_tokens')->where('email', $email)->delete();
    $request->session()->forget(['admin_reset_email', 'admin_reset_token']);

    return redirect()->route('admin.login.form')
                     ->with('success', 'Password admin berhasil diubah! Silakan login dengan password baru.');
}
}