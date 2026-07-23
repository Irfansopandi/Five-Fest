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
            max-width: 720px;
            margin: 0 auto;
        }

        /* PDF Export mode: Forces 100% zoom and resets offsets */
        body.pdf-export-mode {
            min-width: 720px !important;
            width: 720px !important;
            padding: 0 !important;
            margin: 0 !important;
            background: #f1f5f9 !important;
        }
        body.pdf-export-mode .ticket-print-wrapper {
            width: 720px !important;
            max-width: 720px !important;
            padding: 20px !important;
            margin: 0 !important;
        }
        body.pdf-export-mode .ticket-container {
            zoom: 1 !important;
            max-width: 720px !important;
            margin: 0 auto 20px auto !important;
        }
        body.pdf-export-mode .footer-info {
            zoom: 1 !important;
            margin-top: 20px !important;
        }

        /* PREMIUM TICKET STUB DESIGN */
        .ticket-container {
            width: 100%;
            max-width: 720px;
            margin: 0 auto;
            filter: drop-shadow(0 20px 40px rgba(0,0,0,0.1));
        }

        .ticket-stub {
            background: white;
            display: flex;
            border-radius: 30px;
            overflow: hidden;
            position: relative;
            min-height: 420px;
        }

        /* Left Side: Main Info */
        .ticket-main {
            flex: 1;
            padding: 0;
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .ticket-hero {
            height: 220px;
            position: relative;
            overflow: hidden;
        }

        .ticket-hero img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .ticket-hero::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, transparent 100%);
        }

        .ticket-event-tag {
            position: absolute;
            top: 20px;
            left: 20px;
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
            color: white;
            padding: 6px 15px;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 800;
            letter-spacing: 1px;
            border: 1px solid rgba(255,255,255,0.3);
            z-index: 2;
            text-transform: uppercase;
        }

        .ticket-event-info {
            position: absolute;
            bottom: 20px;
            left: 25px;
            right: 25px;
            color: white;
            z-index: 2;
        }

        .ticket-info-body {
            padding: 15px 35px 35px 35px;
            background: white;
        }

        .info-item label {
            display: block;
            color: #94a3b8;
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 5px;
            letter-spacing: 0.5px;
        }

        .info-item span {
            display: block;
            color: #1e293b;
            font-weight: 800;
            font-size: 1.05rem;
        }

        /* Perforation Line */
        .ticket-divider {
            width: 2px;
            background-image: linear-gradient(#e2e8f0 50%, transparent 50%);
            background-size: 2px 15px;
            position: relative;
        }

        .ticket-divider::before, .ticket-divider::after {
            content: '';
            position: absolute;
            width: 40px;
            height: 40px;
            background: #f1f5f9; /* Match body background */
            border-radius: 50%;
            left: -20px;
            z-index: 3;
        }

        .ticket-divider::before { top: -20px; }
        .ticket-divider::after { bottom: -20px; }

        /* Right Side: QR & Control */
        .ticket-side {
            width: 200px;
            background: #f8fafc;
            padding: 25px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .qr-box {
            background: white;
            padding: 15px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            margin-bottom: 15px;
        }

        .qr-box img {
            width: 110px;
            height: 110px;
        }

        .booking-code-badge {
            background: #7c3aed;
            color: white;
            padding: 6px 12px;
            border-radius: 12px;
            font-family: 'Space Mono', monospace;
            font-weight: 700;
            font-size: 0.8rem;
            letter-spacing: 2px;
            margin-bottom: 10px;
            box-shadow: 0 10px 20px rgba(124, 58, 237, 0.2);
            text-transform: uppercase;
            display: inline-block;
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

        @media (max-width: 768px) {
            body {
                padding: 15px !important;
            }
            .mb-5.d-flex.justify-content-between.align-items-center.no-print {
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: 15px !important;
            }
            .mb-5.d-flex.justify-content-between.align-items-center.no-print > .d-flex {
                flex-direction: column-reverse !important;
                width: 100% !important;
                gap: 10px !important;
            }
            .mb-5.d-flex.justify-content-between.align-items-center.no-print a,
            .mb-5.d-flex.justify-content-between.align-items-center.no-print button {
                width: 100% !important;
                padding: 12px 20px !important;
                border-radius: 15px !important;
                text-align: center !important;
                display: block !important;
            }
            /* Keep ticket horizontal design exactly the same as desktop, but zoom to fit screen */
            .ticket-container {
                zoom: 0.46 !important;
                max-width: 720px !important;
                margin: 0 auto 20px auto !important;
            }
            .footer-info {
                zoom: 0.46 !important;
                padding: 20px !important;
                border-radius: 20px !important;
                margin-top: 20px !important;
            }
            .info-list {
                grid-template-columns: 1fr !important;
                gap: 10px !important;
            }
        }

        @media print {
            body { padding: 0; background: white; }
            .no-print { display: none !important; }
            .ticket-print-wrapper { max-width: 100%; padding: 0; }
            .ticket-stub { box-shadow: none; border: 1px solid #e2e8f0; }
            .ticket-divider::before, .ticket-divider::after { background: white; }
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
            <div class="d-flex gap-3 align-items-center">
                <a href="{{ route('my-tickets') }}" class="btn btn-outline-secondary px-4 py-3 rounded-pill fw-bold border-2">
                    <i class="bi bi-arrow-left me-2"></i> Kembali ke Tiket Saya
                </a>
                <button onclick="downloadPDF(false)" class="btn btn-dark px-4 py-3 rounded-pill fw-bold shadow-lg">
                    <i class="bi bi-download me-2"></i> Unduh E-Ticket (PDF)
                </button>
            </div>
        </div>

        @foreach($booking->tickets as $index => $ticket)
        <div class="ticket-container mb-5">
            <div class="ticket-stub">
                <div class="ticket-main">
                    <!-- Event Image Banner with Text Overlay (Smaller Fonts) -->
                    <div class="ticket-hero">
                        <img src="{{ asset('storage/' . $booking->event->image) }}" alt="Event Banner">
                        <div class="ticket-event-tag">Official Digital Ticket #{{ $index + 1 }}</div>
                        <div class="ticket-event-info">
                            <h4 class="fw-bold text-white mb-1" style="font-size: 1.15rem; letter-spacing: -0.5px; line-height: 1.2;">{{ $booking->event->title }}</h4>
                            <p class="mb-0 opacity-90 text-white" style="font-size: 0.7rem;"><i class="bi bi-geo-alt-fill me-1"></i> {{ $booking->event->venue }}</p>
                        </div>
                    </div>

                    <!-- Ticket Details in a Clean Table (Immune to Float/Flex wrapping bugs in PDF rendering) -->
                    <div class="ticket-info-body">
                        <table style="width: 100%; border-collapse: collapse; border: none; table-layout: fixed;">
                            <!-- Row 1: Labels -->
                            <tr>
                                <td style="width: 33.33%; padding-bottom: 4px; vertical-align: top; text-align: left;">
                                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.6rem; letter-spacing: 0.5px;">Pemegang Tiket</small>
                                </td>
                                <td style="width: 33.33%; padding-bottom: 4px; vertical-align: top; text-align: center;">
                                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.6rem; letter-spacing: 0.5px;">Tanggal</small>
                                </td>
                                <td style="width: 33.33%; padding-bottom: 4px; vertical-align: top; text-align: right; padding-right: 15px;">
                                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.6rem; letter-spacing: 0.5px;">Waktu</small>
                                </td>
                            </tr>
                            <!-- Row 1: Values -->
                            <tr>
                                <td style="padding-bottom: 15px; vertical-align: top; text-align: left; padding-right: 10px;">
                                    <span class="fw-bold text-dark" style="font-size: 0.95rem; display: block; line-height: 1.2;">{{ strtoupper($booking->user->name) }}</span>
                                </td>
                                <td style="padding-bottom: 15px; vertical-align: top; text-align: center;">
                                    <span class="fw-bold text-dark" style="font-size: 0.95rem; display: block; line-height: 1.2;">{{ $booking->event->date->format('d M Y') }}</span>
                                </td>
                                <td style="padding-bottom: 15px; vertical-align: top; text-align: right; padding-right: 15px;">
                                    <span class="fw-bold text-dark" style="font-size: 0.95rem; display: block; line-height: 1.2;">{{ \Carbon\Carbon::parse($booking->event->time)->format('H:i') }} WIB</span>
                                </td>
                            </tr>
                            <!-- Separator Row -->
                            <tr>
                                <td colspan="3" style="padding: 5px 0 15px 0; vertical-align: middle;">
                                    <div style="border-top: 1px dashed #e2e8f0; width: 100%;"></div>
                                </td>
                            </tr>
                            <!-- Row 2: Labels -->
                            <tr>
                                <td style="padding-bottom: 4px; vertical-align: top; text-align: left;">
                                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.6rem; letter-spacing: 0.5px;">Kategori</small>
                                </td>
                                <td style="padding-bottom: 4px; vertical-align: top; text-align: center;">
                                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.6rem; letter-spacing: 0.5px;">Booking ID</small>
                                </td>
                                <td style="padding-bottom: 4px; vertical-align: top; text-align: right; padding-right: 15px;">
                                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.6rem; letter-spacing: 0.5px;">No. Tiket</small>
                                </td>
                            </tr>
                            <!-- Row 2: Values -->
                            <tr>
                                <td style="vertical-align: top; text-align: left;">
                                    <span class="fw-bold text-primary" style="font-size: 0.95rem; color: #7c3aed !important; display: block; line-height: 1.2;">{{ $booking->ticket_category->name }}</span>
                                </td>
                                <td style="vertical-align: top; text-align: center;">
                                    <span class="fw-bold text-dark" style="font-size: 0.95rem; display: block; line-height: 1.2;">{{ $booking->booking_code }}</span>
                                </td>
                                <td style="vertical-align: top; text-align: right; padding-right: 15px;">
                                    <span class="fw-bold text-dark" style="font-size: 0.95rem; display: block; line-height: 1.2;">{{ $ticket->seat_number ?? 'FREE SEATING' }}</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="ticket-divider"></div>

                <div class="ticket-side">
                    <div class="qr-box">
                        <img src="{{ route('ticket.qrcode', $ticket->ticket_code) }}" alt="QR Code">
                    </div>
                    <div class="booking-code-badge" style="font-size: 0.9rem;">{{ $ticket->ticket_code }}</div>
                    <p class="text-muted small mb-0 mt-3 px-3">Scan QR code ini di pintu masuk. Satu QR berlaku untuk satu kali masuk.</p>
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

    <!-- PDF Loading Screen -->
    <div id="pdf-loader" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.95); z-index: 9999; display: flex; flex-direction: column; align-items: center; justify-content: center;">
        <div class="spinner-border mb-3" role="status" style="width: 3rem; height: 3rem; color: #7c3aed;">
            <span class="visually-hidden">Loading...</span>
        </div>
        <h5 class="fw-bold text-dark">Menyiapkan E-Tiket PDF Anda...</h5>
        <p class="text-muted small">File akan terunduh secara otomatis.</p>
    </div>

    <!-- html2pdf.js library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        function downloadPDF(autoRedirect = false) {
            const element = document.querySelector('.ticket-print-wrapper');
            const loader = document.getElementById('pdf-loader');
            
            // Add class to body to force desktop horizontal layouts and zoom = 1 during PDF generation
            document.body.classList.add('pdf-export-mode');
            
            // Hide elements that shouldn't be in the PDF
            const noPrintElements = document.querySelectorAll('.no-print');
            noPrintElements.forEach(el => el.style.setProperty('display', 'none', 'important'));
            if (loader) loader.style.setProperty('display', 'none', 'important');

            // Wait for repaint to finish before exporting
            setTimeout(() => {
                const opt = {
                    margin:       10,
                    filename:     'ticket-{{ $booking->booking_code }}.pdf',
                    image:        { type: 'jpeg', quality: 0.98 },
                    html2canvas:  { 
                        scale: 2, 
                        useCORS: true, 
                        logging: false,
                        scrollX: 0,
                        scrollY: 0
                    },
                    jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
                };

                html2pdf().set(opt).from(element).save().then(() => {
                    // Restore layout for screen display
                    document.body.classList.remove('pdf-export-mode');
                    
                    // Restore hidden elements
                    noPrintElements.forEach(el => el.style.removeProperty('display'));
                    if (loader) {
                        loader.style.setProperty('display', 'flex', 'important');
                        loader.innerHTML = `
                            <i class="bi bi-check-circle-fill text-success mb-3" style="font-size: 3.5rem;"></i>
                            <h4 class="fw-bold text-dark mb-2">E-Tiket Berhasil Diunduh!</h4>
                            <p class="text-muted small">Mengalihkan halaman...</p>
                        `;
                    }

                    if (autoRedirect) {
                        setTimeout(() => {
                            window.location.href = "{{ route('my-tickets') }}";
                        }, 1200);
                    } else {
                        if (loader) {
                            setTimeout(() => {
                                loader.style.setProperty('display', 'none', 'important');
                            }, 1000);
                        }
                    }
                }).catch(err => {
                    console.error('PDF export failed:', err);
                    document.body.classList.remove('pdf-export-mode');
                    noPrintElements.forEach(el => el.style.removeProperty('display'));
                    if (loader) loader.style.setProperty('display', 'none', 'important');
                    alert('Gagal mengunduh PDF secara otomatis. Silakan gunakan browser yang didukung.');
                });
            }, 250); // 250ms repaint buffer
        }

        // Trigger auto download on page load after a slight delay
        window.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                downloadPDF(true);
            }, 1200);
        });
    </script>
</body>
</html>