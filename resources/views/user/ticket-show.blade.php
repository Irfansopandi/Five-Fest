@extends('v_layouts.app')

@section('title', 'E-Tiket - ' . $booking->booking_code)

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');

    .ticket-page {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: #f8fafc;
        min-height: 100vh;
        padding-top: 100px;
        padding-bottom: 100px;
    }

    /* PREMIUM TICKET STUB DESIGN */
    .ticket-container {
        max-width: 950px;
        margin: 0 auto;
        filter: drop-shadow(0 30px 60px rgba(124, 58, 237, 0.15));
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
        bottom: 25px;
        left: 35px;
        right: 35px;
        color: white;
        z-index: 2;
    }

    .ticket-info-body {
        padding: 35px;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 25px;
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
        background: #f8fafc;
        border-radius: 50%;
        left: -20px;
        z-index: 3;
    }

    .ticket-divider::before { top: -20px; }
    .ticket-divider::after { bottom: -20px; }

    /* Right Side: QR & Control */
    .ticket-side {
        width: 320px;
        background: #f8fafc;
        padding: 40px;
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
        margin-bottom: 25px;
    }

    .qr-box svg {
        width: 160px;
        height: 160px;
    }

    .booking-code-badge {
        background: #7c3aed;
        color: white;
        padding: 10px 24px;
        border-radius: 12px;
        font-family: 'Space Mono', monospace;
        font-weight: 700;
        font-size: 1.1rem;
        letter-spacing: 2px;
        margin-bottom: 10px;
        box-shadow: 0 10px 20px rgba(124, 58, 237, 0.2);
    }

    /* BUTTONS */
    .action-container {
        max-width: 950px;
        margin: 40px auto 0;
        display: flex;
        gap: 20px;
    }

    .btn-action {
        flex: 1;
        padding: 20px;
        border-radius: 24px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        text-decoration: none;
        font-size: 1rem;
    }

    .btn-back {
        background: white;
        color: #64748b;
        border: 2px solid #e2e8f0;
    }

    .btn-download {
        background: linear-gradient(135deg, #7c3aed 0%, #db2777 100%);
        color: white;
        box-shadow: 0 15px 35px rgba(124, 58, 237, 0.3);
        border: none;
    }

    .btn-action:hover {
        transform: translateY(-8px) scale(1.02);
        opacity: 1;
        color: white;
    }
    
    .btn-back:hover {
        background: #f1f5f9;
        color: #1e293b;
        border-color: #cbd5e1;
    }

    @media (max-width: 992px) {
        .ticket-stub {
            flex-direction: column;
        }
        .ticket-divider {
            width: 100%;
            height: 2px;
            background-image: linear-gradient(to right, #e2e8f0 50%, transparent 50%);
            background-size: 15px 2px;
        }
        .ticket-divider::before { left: -20px; top: -20px; }
        .ticket-divider::after { right: -20px; bottom: auto; top: -20px; left: auto; }
        .ticket-side { width: 100%; padding: 40px 20px; }
        .ticket-info-body { grid-template-columns: 1fr 1fr; padding: 25px; }
    }

    @media (max-width: 576px) {
        .ticket-info-body { grid-template-columns: 1fr; }
        .action-container { flex-direction: column; }
    }
</style>

<section class="ticket-page">
    <div class="container">
        
        @foreach($booking->tickets as $index => $ticket)
        <div class="ticket-container mb-5" data-aos="zoom-in" data-aos-delay="{{ $index * 100 }}">
            <div class="ticket-stub">
                
                <div class="ticket-main">
                    <div class="ticket-hero">
                        <img src="{{ asset('storage/' . $booking->event->image) }}" alt="Event Banner">
                        <div class="ticket-event-tag">Official Digital Ticket #{{ $index + 1 }}</div>
                        <div class="ticket-event-info">
                            <h2 class="fw-bold mb-1" style="font-size: 1.8rem; letter-spacing: -0.5px;">{{ $booking->event->title }}</h2>
                            <p class="mb-0 opacity-90 small"><i class="bi bi-geo-alt-fill me-1"></i> {{ $booking->event->venue }}</p>
                        </div>
                    </div>

                    <div class="ticket-info-body">
                        <div class="info-item">
                            <label>Pemegang Tiket</label>
                            <span>{{ strtoupper($booking->user->name) }}</span>
                        </div>
                        <div class="info-item">
                            <label>Tanggal</label>
                            <span>{{ $booking->event->date->format('d M Y') }}</span>
                        </div>
                        <div class="info-item">
                            <label>Waktu</label>
                            <span>{{ \Carbon\Carbon::parse($booking->event->time)->format('H:i') }} WIB</span>
                        </div>
                        <div class="info-item">
                            <label>Kategori</label>
                            <span style="color: #7c3aed;">{{ $booking->ticket_category->name }}</span>
                        </div>
                        <div class="info-item">
                            <label>Booking ID</label>
                            <span>{{ $booking->booking_code }}</span>
                        </div>
                        <div class="info-item">
                            <label>No. Tiket</label>
                            <span>{{ $ticket->seat_number ?? 'FREE SEATING' }}</span>
                        </div>
                    </div>
                </div>

                <div class="ticket-divider"></div>

                <div class="ticket-side">
                    <div class="qr-box">
                        <img src="{{ route('ticket.qrcode', $ticket->ticket_code) }}" alt="QR Code" style="width: 200px; height: 200px;">
                    </div>
                    <div class="booking-code-badge" style="font-size: 0.9rem;">{{ $ticket->ticket_code }}</div>
                    <p class="text-muted small mb-0 mt-3 px-3">Scan QR code ini di pintu masuk. Satu QR berlaku untuk satu kali masuk.</p>
                </div>

            </div>
        </div>
        @endforeach

        <div class="action-container" data-aos="fade-up" data-aos-delay="200">
            <a href="{{ route('my-tickets') }}" class="btn-action btn-back">
                <i class="bi bi-arrow-left"></i> Kembali ke Tiket Saya
            </a>
            <a href="{{ route('ticket.download', $booking->id) }}" class="btn-action btn-download">
                <i class="bi bi-download"></i> Unduh Semua E-Tiket (PDF)
            </a>
        </div>

        <div class="mt-5 text-center text-muted small" data-aos="fade-up" data-aos-delay="300">
            <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
                <i class="bi bi-shield-check-fill text-success fs-5"></i>
                <span class="fw-bold">Verified Digital Asset</span>
            </div>
            <p class="mb-0">Dibuat secara otomatis oleh sistem FiveFest pada {{ now()->format('d M Y, H:i') }} WIB.</p>
        </div>

    </div>
</section>
@endsection