<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    protected string $token;
    protected string $apiUrl = 'https://api.fonnte.com/send';

    public function __construct()
    {
        $this->token = config('services.fonnte.token');
    }

    /**
     * Kirim pesan WhatsApp via Fonnte
     *
     * @param string $phone  Nomor WA tujuan (format: 628xxx)
     * @param string $message Isi pesan
     * @return bool
     */
 public function sendMessage(string $phone, string $message): bool
{
    try {
        // Bersihkan format nomor — hapus semua non-digit
        $phoneCleaned = preg_replace('/\D/', '', $phone);

        // Pastikan diawali 62
        if (str_starts_with($phoneCleaned, '0')) {
            $phoneCleaned = '62' . substr($phoneCleaned, 1);
        } elseif (!str_starts_with($phoneCleaned, '62')) {
            $phoneCleaned = '62' . $phoneCleaned;
        }

        Log::info('Fonnte kirim ke: ' . $phoneCleaned);

        $response = Http::withHeaders([
            'Authorization' => $this->token,
        ])->post($this->apiUrl, [
            'target'      => $phoneCleaned,
            'message'     => $message,
            'countryCode' => '62',
        ]);

        $body = $response->json();

        if ($response->successful() && isset($body['status']) && $body['status'] === true) {
            return true;
        }

        Log::error('Fonnte send failed', ['response' => $body]);
        return false;

    } catch (\Exception $e) {
        Log::error('Fonnte exception: ' . $e->getMessage());
        return false;
    }
}

    /**
     * Format nomor WA ke format internasional
     * Misal: 08123 → 628123, +628123 → 628123
     */
   public static function formatPhone(string $phone): string
{
    $phone = preg_replace('/[^\d]/', '', $phone); // hapus +, strip, spasi, dll

        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        } elseif (str_starts_with($phone, '62')) {
            // sudah benar
        } else {
            $phone = '62' . $phone;
        }

        return $phone;
    }
}