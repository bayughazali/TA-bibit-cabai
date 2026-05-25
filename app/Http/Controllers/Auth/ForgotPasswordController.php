<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\FonnteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    protected FonnteService $fonnte;

    public function __construct(FonnteService $fonnte)
    {
        $this->fonnte = $fonnte;
    }

    // ──────────────────────────────────────────────
    // STEP 1 — Show form: pilih metode (email / WA)
    // ──────────────────────────────────────────────
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    // ──────────────────────────────────────────────
    // STEP 1 — Handle: kirim OTP (email ATAU WA)
    // ──────────────────────────────────────────────
    public function sendOtp(Request $request)
    {
        $method = $request->input('method', 'email'); // 'email' atau 'whatsapp'

        if ($method === 'whatsapp') {
            return $this->sendOtpViaWhatsapp($request);
        }

        return $this->sendOtpViaEmail($request);
    }

    // ── Kirim OTP via Email ──
    private function sendOtpViaEmail(Request $request)
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
            return back()->withErrors(['email' => 'Email tidak ditemukan.'])->withInput();
        }

        $otp = $this->generateAndStoreOtp($request->email);

        Mail::send('emails.otp', ['otp' => $otp, 'name' => $user->name], function ($m) use ($request) {
            $m->to($request->email)
              ->subject('Kode Verifikasi Reset Password - Bibit Cabai Bondowoso');
        });

        $request->session()->put('reset_email', $request->email);
        $request->session()->put('reset_method', 'email');

        return redirect()->route('password.verify-otp-form')
                 ->with('success', 'Kode verifikasi dikirim ke email. Berlaku 3 menit.');
    }

    // ── Kirim OTP via WhatsApp ──
    private function sendOtpViaWhatsapp(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'digits_between:10,15'],
        ], [
            'phone.required'        => 'Nomor WhatsApp wajib diisi.',
            'phone.digits_between'  => 'Nomor WhatsApp tidak valid (10-15 digit).',
        ]);

        $phoneFormatted = FonnteService::formatPhone($request->phone);

      $user = User::where('phone', $phoneFormatted)
            ->orWhere('phone', '+' . $phoneFormatted)
            ->orWhere('phone', $request->phone)
            ->orWhere('phone', '0' . ltrim($request->phone, '0'))
            ->first();

        if (!$user) {
            return back()->withErrors([
                'phone' => 'Nomor WhatsApp tidak ditemukan di sistem kami.',
            ])->withInput();
        }

        // Gunakan email user sebagai key di password_reset_tokens
        $otp = $this->generateAndStoreOtp($user->email);

        $pesan = "🌶️ *Bibit Cabai Bondowoso*\n\n"
               . "Halo {$user->name},\n\n"
               . "Kode verifikasi reset password Anda:\n\n"
               . "*{$otp}*\n\n"
               . "Berlaku selama *3 menit*.\n"
               . "Jangan berikan kode ini kepada siapapun.";

        $sent = $this->fonnte->sendMessage($phoneFormatted, $pesan);

        if (!$sent) {
            return back()->with('error', 'Gagal mengirim pesan WhatsApp. Coba lagi.');
        }

        $request->session()->put('reset_email', $user->email);
        $request->session()->put('reset_method', 'whatsapp');
        $request->session()->put('reset_phone_display', '****' . substr($request->phone, -4));

        return redirect()->route('password.verify-otp-form')
                 ->with('success', 'Kode verifikasi dikirim ke WhatsApp Anda. Berlaku 3 menit.');
    }

    // ── Helper: generate & simpan OTP ──
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

    // ──────────────────────────────────────────────
    // STEP 2 — Show form: verifikasi OTP
    // ──────────────────────────────────────────────
    public function showVerifyOtpForm(Request $request)
    {
        if (!$request->session()->has('reset_email')) {
            return redirect()->route('password.request')
                             ->with('error', 'Silakan masukkan email atau nomor WA Anda terlebih dahulu.');
        }

        return view('auth.verify-otp');
    }

    // ──────────────────────────────────────────────
    // STEP 2 — Handle: verifikasi OTP
    // ──────────────────────────────────────────────
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

       $record = DB::table('password_reset_tokens')->where('email', $email)->first();
        if (!$record) {
            return back()->with('error', 'Kode OTP tidak ditemukan. Silakan kirim ulang.');
        }

        $createdAt = Carbon::parse($record->created_at);
        $diffSeconds = Carbon::now()->diffInSeconds($createdAt);

        if ($diffSeconds > 180) { // 3 menit = 180 detik
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            return back()->with('error', 'Kode OTP sudah kadaluarsa. Silakan minta kirim kode lagi.');
        }

        if (!Hash::check($request->otp, $record->token)) {
            return back()->with('error', 'Kode OTP salah. Periksa kembali kode Anda.');
        }

        $verifiedToken = Str::random(60);
        DB::table('password_reset_tokens')->where('email', $email)
            ->update(['token' => Hash::make($verifiedToken)]);

        $request->session()->put('reset_token', $verifiedToken);

        return redirect()->route('password.reset-form')
                         ->with('success', 'Kode berhasil diverifikasi! Silakan buat password baru.');
    }

    // ──────────────────────────────────────────────
    // STEP 2 — Handle: kirim ulang OTP
    // ──────────────────────────────────────────────
    public function resendOtp(Request $request)
    {
        $email  = $request->session()->get('reset_email');
        $method = $request->session()->get('reset_method', 'email');

        if (!$email) {
            return redirect()->route('password.request')
                             ->with('error', 'Sesi tidak valid.');
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            return redirect()->route('password.request')->with('error', 'User tidak ditemukan.');
        }

        $otp = $this->generateAndStoreOtp($email);

        if ($method === 'whatsapp') {
           $phone = FonnteService::formatPhone(
            preg_replace('/^\+/', '', $user->phone)
            );
            $pesan = "🌶️ *Bibit Cabai Bondowoso*\n\n"
                   . "Halo {$user->name},\n\n"
                   . "Kode verifikasi baru Anda:\n\n"
                   . "*{$otp}*\n\n"
                   . "Berlaku selama *3 menit*.";

            $sent = $this->fonnte->sendMessage($phone, $pesan);
            if (!$sent) {
                return back()->with('error', 'Gagal mengirim ulang ke WhatsApp.');
            }
        } else {
            Mail::send('emails.otp', ['otp' => $otp, 'name' => $user->name], function ($m) use ($email) {
                $m->to($email)->subject('Kode Verifikasi (Kirim Ulang) - Bibit Cabai Bondowoso');
            });
        }

        return back()->with('success', 'Kode OTP baru telah dikirim.');
    }

    // ──────────────────────────────────────────────
    // STEP 3 — Show & Handle reset password
    // (sama seperti kode lama kamu — tidak berubah)
    // ──────────────────────────────────────────────
    public function showResetForm(Request $request)
    {
        if (!$request->session()->has('reset_token') || !$request->session()->has('reset_email')) {
            return redirect()->route('password.request')
                             ->with('error', 'Sesi tidak valid. Silakan ulangi dari awal.');
        }
        return view('auth.reset-password');
    }

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
            return redirect()->route('password.request')->with('error', 'Sesi tidak valid.');
        }

        $record = DB::table('password_reset_tokens')->where('email', $email)->first();
        if (!$record || !Hash::check($token, $record->token)) {
            return redirect()->route('password.request')->with('error', 'Token tidak valid.');
        }

        User::where('email', $email)->update(['password' => Hash::make($request->password)]);

        DB::table('password_reset_tokens')->where('email', $email)->delete();
        $request->session()->forget(['reset_email', 'reset_token', 'reset_method', 'reset_phone_display']);

        return redirect()->route('login')
                         ->with('success', 'Password berhasil diubah! Silakan login.');
    }
}