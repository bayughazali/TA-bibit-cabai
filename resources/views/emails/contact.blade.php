<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        .container { background: #fff; max-width: 600px; margin: auto; border-radius: 8px; padding: 30px; }
        .header { background: #198754; color: white; padding: 20px; border-radius: 6px; text-align: center; margin: -30px -30px 25px; }
        .label { font-weight: bold; color: #198754; }
        .row { margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 15px; }
        .message-box { background: #f8f9fa; padding: 15px; border-radius: 6px; border-left: 4px solid #198754; }
        .footer { text-align: center; color: #aaa; font-size: 12px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>🌶️ Shop Bibit Cabai Bondowoso</h2>
            <p>Pesan Masuk dari Contact Us</p>
        </div>
        <div class="row"><span class="label">👤 Nama:</span><p>{{ $data['name'] }}</p></div>
        <div class="row"><span class="label">📧 Email:</span><p>{{ $data['email'] }}</p></div>
        <div class="row"><span class="label">📱 No. Telepon:</span><p>{{ $data['phone'] ?? '-' }}</p></div>
        <div class="row"><span class="label">📌 Subjek:</span><p>{{ $data['subject'] }}</p></div>
        <div class="row">
            <span class="label">💬 Pesan:</span>
            <div class="message-box">{{ $data['message'] }}</div>
        </div>
        <div class="footer">Email dikirim otomatis dari website Shop Bibit Cabai Bondowoso</div>
    </div>
</body>
</html>