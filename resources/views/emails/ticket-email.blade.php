<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        }
        .event-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 16px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .booking-code {
            font-size: 28px;
            font-weight: 800;
            color: #8b5cf6;
            text-align: center;
            padding: 20px;
            background-color: #f8fafc;
            border: 2px dashed #cbd5e1;
            border-radius: 16px;
            margin: 25px 0;
            letter-spacing: 3px;
        }
        .ticket-details {
            background-color: #f8fafc;
            padding: 25px;
            border-radius: 16px;
            margin: 25px 0;
            border: 1px solid #f1f5f9;
        }
        .detail-row {
            padding: 12px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #64748b;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .detail-value {
            color: #0f172a;
            font-size: 16px;
            margin-top: 4px;
            font-weight: 500;
        }
        .qr-section {
            text-align: center;
            padding: 28px 16px;
            background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 20px;
            margin: 20px 0;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 20px rgba(139,92,246,0.06);
            overflow: hidden;
        }
        .qr-section h3 {
            margin-top: 0;
            margin-bottom: 18px;
            color: #1e293b;
            font-size: 17px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .qr-wrapper {
            display: block;
            margin: 0 auto;
            padding: 14px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.10);
            border: 1px solid #f1f5f9;
            width: 248px;
            max-width: 100%;
            box-sizing: border-box;
        }
        .notes {
            background-color: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 20px;
            margin: 25px 0;
            border-radius: 0 12px 12px 0;
        }
        .notes h3 {
            margin-top: 0;
            color: #1e3a8a;
            font-size: 16px;
        }
        .notes ul {
            margin: 10px 0;
            padding-left: 20px;
            font-size: 14px;
        }
        .notes li {
            margin: 8px 0;
            color: #1e40af;
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
        {{-- ===== HEADER ===== --}}
        <div class="header">
            <h1 style="margin: 0; font-size: 28px;">&#127915; E-Tiket Anda</h1>
            <p style="margin: 10px 0 0 0; font-size: 16px; opacity: 0.9;">{{ $booking->booking_code }}</p>
            <span style="background-color: #28a745; padding: 8px 20px; border-radius: 20px; font-size: 14px; display: inline-block; margin-top: 15px; font-weight: 600;">
                &#10003; Pembayaran Dikonfirmasi
            </span>
        </div>
        
        <div class="content">
            <p style="font-size: 16px;">Halo <strong>{{ $booking->user->name }}</strong>,</p>
            <p>Terima kasih atas pembelian tiket Anda! Berikut adalah e-tiket untuk event:</p>
            
            {{-- ===== EVENT IMAGE ===== --}}
            @if($booking->event->image)
                <img src="{{ url('storage/' . $booking->event->image) }}"
                    alt="{{ $booking->event->title }}"
                    class="event-image"
                    style="width: 100%; height: 220px; object-fit: cover; border-radius: 16px; margin-bottom: 25px; box-shadow: 0 6px 20px rgba(0,0,0,0.12);">
            @else
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                            height: 220px;
                            border-radius: 16px;
                            text-align: center;
                            color: white;
                            font-size: 22px;
                            font-weight: bold;
                            margin-bottom: 25px;
                            display: flex;
                            align-items: center;
                            justify-content: center;">
                    {{ $booking->event->title }}
                </div>
            @endif
            
            <h2 style="text-align: center; color: #1e293b; margin: 20px 0; font-size: 22px;">
                {{ $booking->event->title }}
            </h2>
            
            @if($booking->event->artist)
            <p style="text-align: center; color: #6c757d; margin-bottom: 30px;">
                &#127908; {{ $booking->event->artist }}
            </p>
            @endif
            
            {{-- ===== BOOKING CODE ===== --}}
            <div class="booking-code">
                {{ $booking->booking_code }}
            </div>
            
            {{-- ===== DETAIL TIKET ===== --}}
            <div class="ticket-details">
                <div class="detail-row">
                    <div class="detail-label">&#128197; Tanggal</div>
                    <div class="detail-value">{{ \Carbon\Carbon::parse($booking->event->date)->format('l, d F Y') }}</div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-label">&#128336; Waktu</div>
                    <div class="detail-value">{{ \Carbon\Carbon::parse($booking->event->time)->format('H:i') }} WIB</div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-label">&#128205; Venue</div>
                    <div class="detail-value">{{ $booking->event->venue }}</div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-label">&#128100; Pemegang Tiket</div>
                    <div class="detail-value">{{ $booking->user->name }}</div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-label">&#127915; Kategori</div>
                    <div class="detail-value">{{ $booking->ticket_category->name ?? '-' }}</div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">&#127915; Jumlah Tiket</div>
                    <div class="detail-value">{{ $booking->quantity }} tiket</div>
                </div>

                @if($booking->seat_number)
                <div class="detail-row">
                    <div class="detail-label">&#128186; No. Tiket</div>
                    <div class="detail-value">{{ $booking->tickets->pluck('ticket_code')->implode(', ') }}</div>
                </div>
                @endif

                <div class="detail-row">
                    <div class="detail-label">&#128176; Total Pembayaran</div>
                    <div class="detail-value" style="color: #059669; font-weight: 700;">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                </div>
            </div>
            
            {{-- ===== DIVIDER ===== --}}
            <hr style="border: none; border-top: 2px dashed #e2e8f0; margin: 25px 0;">

            {{-- ===== TIKET DIGITAL DENGAN QR CODE ===== --}}
            <h3 style="text-align: center; color: #1e293b; margin-bottom: 5px;">&#127903; Tiket Digital Anda</h3>
            <p style="text-align: center; color: #64748b; font-size: 14px; margin-top: 0;">Tunjukkan QR Code di bawah ini saat masuk venue</p>

            @foreach($booking->tickets as $index => $ticket)
            <div class="qr-section">
                <h3>&#127915; Tiket #{{ $index + 1 }}</h3>

                {{-- QR Code dari API Publik --}}
                <div class="qr-wrapper" style="margin: 0 auto;">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&color=1e1b4b&bgcolor=ffffff&qzone=2&data={{ urlencode($ticket->ticket_code) }}"
                         alt="QR Code {{ $ticket->ticket_code }}"
                         width="220" height="220"
                         style="display: block; width: 220px; height: 220px; border-radius: 8px;">
                </div>

                {{-- Separator --}}
                <div style="width: 60px; height: 3px; background: linear-gradient(90deg, #8b5cf6, #3b82f6); border-radius: 2px; margin: 18px auto 16px;"></div>

                {{-- Info Tiket --}}
                <div>
                    <div style="font-size: 11px; color: #94a3b8; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 6px;">Kode Tiket</div>
                    <div style="font-size: 20px; font-weight: 800; color: #1e1b4b; letter-spacing: 3px; font-family: monospace;">{{ $ticket->ticket_code }}</div>

                    @if($ticket->seat_number)
                    <div style="margin-top: 14px; padding: 10px 20px; background: #f5f3ff; border-radius: 10px; display: inline-block;">
                        <div style="font-size: 11px; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px;">No. Tiket</div>
                        <div style="font-size: 16px; font-weight: 700; color: #7c3aed; margin-top: 2px;">{{ $ticket->seat_number }}</div>
                    </div>
                    @endif

                    <div style="margin-top: 14px;">
                        <span style="display: inline-block; background: #dcfce7; color: #15803d; padding: 5px 16px; border-radius: 20px; font-size: 12px; font-weight: 700; letter-spacing: 0.5px;">
                            &#10003; {{ ucfirst($ticket->status) }}
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
            
            {{-- ===== CATATAN PENTING ===== --}}
            <div class="notes">
                <h3>&#8505;&#65039; Informasi Penting</h3>
                <ul>
                    <li>Harap tiba setidaknya <strong>30 menit</strong> sebelum acara dimulai</li>
                    <li>Bawa <strong>kartu identitas</strong> yang sah sesuai nama pemegang tiket</li>
                    <li>Tiket ini <strong>tidak dapat dipindahtangankan</strong> dan tidak dapat dikembalikan</li>
                    <li>Simpan email ini atau ambil screenshot QR Code sebagai bukti tiket</li>
                    <li>Setiap QR Code hanya dapat di-scan <strong>1 kali</strong> saat masuk venue</li>
                </ul>
            </div>
            
            <div style="text-align: center; margin: 30px 0;">
                <p style="color: #64748b;">Pertanyaan? Hubungi kami:</p>
                <p style="color: #334155;">&#128231; fivefest@gmail.com | &#128241; +62 812-3456-7890</p>
            </div>
        </div>
        
        {{-- ===== FOOTER ===== --}}
        <div class="footer">
            <p style="margin: 0;"><strong>FIVE FEST</strong></p>
            <p style="margin: 5px 0;">Terima kasih telah mempercayai kami!</p>
            <p style="font-size: 12px; margin-top: 10px; color: #94a3b8;">
                &copy; {{ date('Y') }} Five Fest. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>