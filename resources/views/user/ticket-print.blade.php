<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Tiket - {{ $booking->booking_code }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Space+Mono:wght@700&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f1f5f9;
            padding: 40px;
            margin: 0;
        }

        .ticket-print-wrapper {
            width: 100%;
            max-width: 1000px;
            margin: 0 auto;
        }

        /* REAL CONCERT TICKET STYLE */
        .real-ticket {
            background: white;
            border-radius: 24px;
            display: flex;
            height: 350px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        .ticket-left {
            flex: 1;
            padding: 0;
            display: flex;
            position: relative;
        }

        .ticket-img-container {
            width: 280px;
            height: 100%;
            position: relative;
        }

        .ticket-img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .ticket-content {
            flex: 1;
            padding: 35px 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .event-badge {
            display: inline-block;
            background: #7c3aed;
            color: white;
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 800;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .event-title {
            font-size: 1.8rem;
            font-weight: 800;
            color: #1e293b;
            line-height: 1.1;
            margin-bottom: 20px;
            letter-spacing: -0.5px;
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
        }

        .detail-item label {
            display: block;
            font-size: 0.65rem;
            color: #94a3b8;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 4px;
            letter-spacing: 0.5px;
        }

        .detail-item span {
            font-size: 1rem;
            font-weight: 700;
            color: #334155;
            display: block;
        }

        .ticket-right {
            width: 250px;
            background: #f8fafc;
            border-left: 3px dashed #cbd5e1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            position: relative;
        }

        /* Perforation Circle Cuts */
        .real-ticket::before, .real-ticket::after {
            content: '';
            position: absolute;
            width: 40px;
            height: 40px;
            background: #f1f5f9;
            border-radius: 50%;
            right: 230px;
            z-index: 2;
        }
        .real-ticket::before { top: -20px; }
        .real-ticket::after { bottom: -20px; }

        .qr-placeholder {
            width: 150px;
            height: 150px;
            background: white;
            padding: 12px;
            border-radius: 16px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }

        .qr-placeholder svg, .qr-placeholder img {
            width: 100%;
            height: 100%;
        }

        .code-text {
            font-family: 'Space Mono', monospace;
            font-size: 1.2rem;
            font-weight: 700;
            color: #1e293b;
            letter-spacing: 2px;
            background: #e2e8f0;
            padding: 5px 15px;
            border-radius: 8px;
        }

        .footer-info {
            margin-top: 40px;
            padding: 30px;
            background: white;
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }

        .info-card h6 {
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .info-list li {
            font-size: 0.85rem;
            color: #64748b;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .info-list li i {
            color: #22c55e;
            font-size: 1rem;
        }

        @media print {
            body { padding: 0; background: white; }
            .no-print { display: none !important; }
            .ticket-print-wrapper { max-width: 100%; padding: 0; }
            .real-ticket { box-shadow: none; border: 1px solid #e2e8f0; }
            .real-ticket::before, .real-ticket::after { background: white; }
        }
    </style>
</head>
<body>
    <div class="ticket-print-wrapper">
        
        <div class="mb-5 d-flex justify-content-between align-items-center no-print">
            <div>
                <h3 class="fw-bold mb-1">E-Ticket Konfirmasi</h3>
                <p class="text-muted mb-0">Cetak tiket ini untuk akses masuk ke area event.</p>
            </div>
            <button onclick="window.print()" class="btn btn-dark px-4 py-3 rounded-pill fw-bold shadow-lg">
                <i class="bi bi-printer-fill me-2"></i> Cetak / Simpan PDF
            </button>
        </div>

        @foreach($booking->tickets as $index => $ticket)
        <div class="real-ticket mb-5">
            <div class="ticket-left">
                <div class="ticket-img-container">
                    <img src="{{ asset('storage/' . $booking->event->image) }}" alt="Event Banner">
                </div>
                <div class="ticket-content">
                    <div>
                        <div class="event-badge">Official Entry Ticket #{{ $index + 1 }}</div>
                        <h1 class="event-title">{{ $booking->event->title }}</h1>
                        <div class="details-grid mt-4">
                            <div class="detail-item">
                                <label>Tanggal</label>
                                <span>{{ $booking->event->date->format('d M Y') }}</span>
                            </div>
                            <div class="detail-item">
                                <label>Waktu</label>
                                <span>{{ \Carbon\Carbon::parse($booking->event->time)->format('H:i') }} WIB</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="details-grid">
                        <div class="detail-item">
                            <label>Pemegang Tiket</label>
                            <span>{{ strtoupper($booking->user->name) }}</span>
                        </div>
                        <div class="detail-item">
                            <label>Kategori & No. Tiket</label>
                            <span style="color: #7c3aed;">{{ $booking->ticket_category->name }} ({{ $ticket->seat_number ?? 'Free Seating' }})</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="ticket-right">
                <div class="qr-placeholder">
                    <img src="{{ route('ticket.qrcode', $ticket->ticket_code) }}" alt="QR Code">
                </div>
                <div class="code-text" style="font-size: 0.9rem;">{{ $ticket->ticket_code }}</div>
                <div class="mt-3" style="font-size: 0.6rem; color: #94a3b8; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">
                    Scan to Verify
                </div>
            </div>
        </div>
        @if(($index + 1) % 2 == 0)
            <div style="page-break-after: always;"></div>
        @endif
        @endforeach

        <div class="footer-info">
            <div class="info-card">
                <h6><i class="bi bi-info-circle-fill text-primary"></i> Informasi & Ketentuan Penting</h6>
                <ul class="info-list">
                    <li><i class="bi bi-check-circle-fill"></i> Tiba di lokasi 60 menit sebelum pintu dibuka untuk menghindari antrean.</li>
                    <li><i class="bi bi-check-circle-fill"></i> Bawa identitas asli (KTP/SIM/Paspor) yang sesuai dengan nama tiket.</li>
                    <li><i class="bi bi-check-circle-fill"></i> Tiket hanya berlaku untuk 1 kali penggunaan (single entry).</li>
                    <li><i class="bi bi-check-circle-fill"></i> Dilarang membawa kamera profesional tanpa izin khusus.</li>
                    <li><i class="bi bi-check-circle-fill"></i> Tiket tidak dapat dipindahtangankan atau dikembalikan (No Refund).</li>
                    <li><i class="bi bi-check-circle-fill"></i> Jaga kerahasiaan kode QR dan kode pemesanan tiket Anda.</li>
                </ul>
            </div>
        </div>

        <div class="mt-5 text-center text-muted small no-print">
            <p>© {{ date('Y') }} FiveFest Ticketing System. All Rights Reserved.</p>
        </div>

    </div>
</body>
</html>