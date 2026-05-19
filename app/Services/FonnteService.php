<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FonnteService
{
    public function sendOTP($nomor, $otp)
    {
        // Format nomor: 08xxx → 628xxx
        $nomor = '62' . ltrim($nomor, '0');

        $response = Http::withHeaders([
            'Authorization' => env('FONNTE_TOKEN'),
        ])->post('https://api.fonnte.com/send', [
            'target' => $nomor,
            'message' => "Kode OTP reset password Anda: *$otp*\n\nBerlaku selama 10 menit. Jangan berikan kode ini kepada siapapun.",
        ]);

        return $response->json();
    }
}