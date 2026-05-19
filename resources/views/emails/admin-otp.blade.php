<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"></head>
<body style="margin:0;padding:0;background:#f4f4f4;font-family:Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f4;padding:30px 0;">
    <tr><td align="center">
        <table width="520" cellpadding="0" cellspacing="0"
               style="background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
            {{-- Header --}}
            <tr>
                <td style="background:linear-gradient(135deg,#11998e 0%,#38ef7d 100%);padding:28px;text-align:center;">
                    <h1 style="color:#fff;margin:0;font-size:20px;">🌿 Admin - Bibit Cabai Bondowoso</h1>
                    <p style="color:rgba(255,255,255,0.85);margin:6px 0 0;font-size:13px;">Reset Password Administrator</p>
                </td>
            </tr>
            {{-- Body --}}
            <tr>
                <td style="padding:32px 36px;">
                    <p style="color:#333;font-size:15px;margin:0 0 14px;">
                        Halo, <strong>{{ $name }}</strong>!
                    </p>
                    <p style="color:#555;font-size:13px;line-height:1.6;margin:0 0 22px;">
                        Kami menerima permintaan reset password untuk akun <strong>admin</strong> Anda.
                        Gunakan kode berikut:
                    </p>
                    {{-- OTP --}}
                    <div style="text-align:center;margin:0 0 24px;">
                        <div style="display:inline-block;background:#f0fff4;border:2px dashed #11998e;
                                    border-radius:12px;padding:18px 36px;">
                            <p style="color:#11998e;font-size:40px;font-weight:bold;
                                      letter-spacing:12px;margin:0;font-family:monospace;">{{ $otp }}</p>
                        </div>
                        <p style="color:#888;font-size:12px;margin:10px 0 0;">
                            ⏱ Kode berlaku <strong>10 menit</strong>
                        </p>
                    </div>
                    <div style="background:#fff3cd;border:1px solid #ffc107;border-radius:8px;padding:12px 16px;margin-bottom:16px;">
                        <p style="color:#856404;font-size:12px;margin:0;">
                            ⚠️ <strong>Keamanan:</strong> Jangan bagikan kode ini kepada siapapun.
                            Jika Anda tidak meminta reset password, segera amankan akun Anda.
                        </p>
                    </div>
                </td>
            </tr>
            {{-- Footer --}}
            <tr>
                <td style="background:#f8f9fa;padding:16px 36px;text-align:center;border-top:1px solid #eee;">
                    <p style="color:#aaa;font-size:11px;margin:0;">
                        © {{ date('Y') }} Shop Bibit Cabai Bondowoso — Admin System
                    </p>
                </td>
            </tr>
        </table>
    </td></tr>
</table>
</body>
</html>