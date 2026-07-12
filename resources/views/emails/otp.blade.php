<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            line-height: 1.6;
            color: #334155;
            margin: 0;
            padding: 0;
            background-color: #f1f5f9;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.05);
        }
        .header {
            background: linear-gradient(135deg, #8b5cf6 0%, #3b82f6 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        .content {
            padding: 40px;
            text-align: center;
        }
        .otp-code {
            font-size: 36px;
            font-weight: 800;
            color: #8b5cf6;
            letter-spacing: 10px;
            margin: 30px 0;
            padding: 20px;
            background-color: #f8fafc;
            border: 2px dashed #cbd5e1;
            border-radius: 16px;
            display: inline-block;
        }
        .footer {
            text-align: center;
            padding: 30px;
            background-color: #f8fafc;
            color: #64748b;
            font-size: 14px;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin: 0; font-size: 28px;">Reset Kata Sandi</h1>
            <p style="margin: 10px 0 0; opacity: 0.9;">Permintaan reset kata sandi akun FiveFest</p>
        </div>
        
        <div class="content">
            <h2 style="color: #0f172a; margin-top: 0;">Halo!</h2>
            <p>Kami menerima permintaan untuk mereset kata sandi akun FiveFest Anda. Berikut adalah kode OTP Anda:</p>
            
            <div class="otp-code">
                {{ $otp }}
            </div>
            
            <p>Masukkan kode ini di halaman verifikasi untuk membuat kata sandi baru. Kode ini hanya berlaku selama 15 menit.</p>
            <p style="color: #ef4444; font-size: 14px; margin-top: 30px;">Jika Anda tidak merasa meminta reset kata sandi, abaikan email ini.</p>
        </div>

        <div class="footer">
            <p style="margin: 0;">&copy; {{ date('Y') }} FiveFest. Hak Cipta Dilindungi.</p>
            <p style="margin: 5px 0 0;">Butuh bantuan? Hubungi support@fivefest.com</p>
        </div>
    </div>
</body>
</html>
