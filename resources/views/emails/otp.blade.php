
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode Verifikasi Reset Password</title>
</head>
<body style="margin:0;padding:0;background-color:#f4f4f4;font-family:Arial,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f4;padding:30px 0;">
        <tr>
            <td align="center">
                <table width="560" cellpadding="0" cellspacing="0"
                       style="background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
 
                    {{-- Header --}}
                    <tr>
                        <td style="background:#198754;padding:30px;text-align:center;">
                            <h1 style="color:#ffffff;margin:0;font-size:22px;">🌱 Bibit Cabai Bondowoso</h1>
                            <p style="color:rgba(255,255,255,0.85);margin:8px 0 0;font-size:14px;">
                                Reset Password
                            </p>
                        </td>
                    </tr>
 
                    {{-- Body --}}
                    <tr>
                        <td style="padding:36px 40px;">
                            <p style="color:#333;font-size:16px;margin:0 0 16px;">
                                Halo, <strong>{{ $name }}</strong>!
                            </p>
                            <p style="color:#555;font-size:14px;line-height:1.6;margin:0 0 24px;">
                                Kami menerima permintaan reset password untuk akun Anda.
                                Gunakan kode berikut untuk memverifikasi identitas Anda:
                            </p>
 
                            {{-- OTP Box --}}
                            <div style="text-align:center;margin:0 0 28px;">
                                <div style="display:inline-block;background:#f0fff4;border:2px dashed #198754;
                                            border-radius:12px;padding:20px 40px;">
                                    <p style="color:#198754;font-size:42px;font-weight:bold;
                                              letter-spacing:12px;margin:0;font-family:monospace;">
                                        {{ $otp }}
                                    </p>
                                </div>
                                <p style="color:#888;font-size:13px;margin:12px 0 0;">
                                    ⏱ Kode ini berlaku selama <strong>10 menit</strong>
                                </p>
                            </div>
 
                            <p style="color:#555;font-size:14px;line-height:1.6;margin:0 0 12px;">
                                Jika Anda tidak meminta reset password, abaikan email ini.
                                Akun Anda tetap aman.
                            </p>
                        </td>
                    </tr>
 
                    {{-- Footer --}}
                    <tr>
                        <td style="background:#f8f9fa;padding:20px 40px;text-align:center;border-top:1px solid #eee;">
                            <p style="color:#aaa;font-size:12px;margin:0;">
                                © {{ date('Y') }} Shop Bibit Cabai Bondowoso. Semua hak dilindungi.
                            </p>
                        </td>
                    </tr>
 
                </table>
            </td>
        </tr>
    </table>
</body>
</html>