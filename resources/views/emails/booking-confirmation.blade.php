<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: white;
        }
        .header {
            text-align: center;
            padding: 30px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .success-icon {
            font-size: 60px;
            margin-bottom: 10px;
        }
        .content {
            padding: 30px;
        }
        .booking-code {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
            text-align: center;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
            margin: 20px 0;
            letter-spacing: 2px;
        }
        .ticket-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #dee2e6;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #6c757d;
        }
        .info-value {
            text-align: right;
            color: #212529;
        }
        .total-price {
            font-size: 20px;
            font-weight: bold;
            color: #28a745;
        }
        .instructions {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
        }
        .instructions h3 {
            margin-top: 0;
            color: #856404;
        }
        .instructions ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .instructions li {
            margin: 8px 0;
            color: #856404;
        }
        .footer {
            text-align: center;
            padding: 20px;
            background-color: #f8f9fa;
            color: #6c757d;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="success-icon">✓</div>
            <h1>Pemesanan Dikonfirmasi!</h1>
        </div>
        
        <div class="content">
            <p>Halo <strong>{{ $booking->user->name }}</strong>,</p>
            <p>Terima kasih atas pemesanan Anda! Pembayaran telah berhasil dikonfirmasi.</p>
            
            <div class="booking-code">
                {{ $booking->booking_code }}
            </div>
            
            <div class="ticket-info">
                <h3 style="margin-top: 0; color: #212529;">Detail Event</h3>
                
                <div class="info-row">
                    <span class="info-label">Event</span>
                    <span class="info-value">{{ $booking->event->title }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Tanggal</span>
                    <span class="info-value">{{ \Carbon\Carbon::parse($booking->event->date)->format('d M Y') }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Waktu</span>
                    <span class="info-value">{{ \Carbon\Carbon::parse($booking->event->time)->format('H:i') }} WIB</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Venue</span>
                    <span class="info-value">{{ $booking->event->venue }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Jumlah Tiket</span>
                    <span class="info-value">{{ $booking->quantity }} tiket</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Total Pembayaran</span>
                    <span class="info-value total-price">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                </div>
            </div>
            
            <div class="instructions">
                <h3>📋 Petunjuk Penting:</h3>
                <ul>
                    <li>Simpan email ini sebagai bukti pemesanan</li>
                    <li>Tunjukkan <strong>kode booking</strong> saat memasuki venue</li>
                    <li>Datang minimal <strong>30 menit</strong> sebelum acara dimulai</li>
                    <li>Bawa identitas diri (KTP/SIM/Paspor)</li>
                </ul>
            </div>
            
            <div style="text-align: center; margin: 30px 0;">
                <p>Pertanyaan? Hubungi kami:</p>
                <p>📧 support@ticketingapp.com | 📱 +62 812-3456-7890</p>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>{{ config('app.name') }}</strong></p>
            <p>Email ini dikirim secara otomatis, mohon tidak membalas.</p>
            <p style="font-size: 12px; margin-top: 10px;">
                © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>