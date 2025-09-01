<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #059669, #047857);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .code {
            background: #ffffff;
            border: 2px solid #059669;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            font-size: 32px;
            font-weight: bold;
            color: #059669;
            letter-spacing: 5px;
            margin: 20px 0;
        }
        .warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Portal Karawang</h1>
        <h2>Kode Reset Password</h2>
    </div>

    <div class="content">
        <p>Halo,</p>

        <p>Anda telah meminta untuk mereset password akun admin Portal Karawang.</p>

        <p>Berikut adalah kode verifikasi Anda:</p>

        <div class="code">
            {{ $code }}
        </div>

        <div class="warning">
            <strong>Peringatan:</strong>
            <ul>
                <li>Kode ini hanya berlaku selama 15 menit</li>
                <li>Jangan bagikan kode ini kepada siapapun</li>
                <li>Jika Anda tidak meminta reset password, abaikan email ini</li>
            </ul>
        </div>

        <p>Masukkan kode di atas pada halaman verifikasi untuk melanjutkan proses reset password.</p>

        <p>Jika Anda mengalami kesulitan, hubungi administrator sistem.</p>

        <p>Terima kasih,<br>
        Tim Portal Karawang</p>
    </div>

    <div class="footer">
        <p>Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</p>
        <p>&copy; {{ date('Y') }} Portal Karawang. All rights reserved.</p>
    </div>
</body>
</html>
