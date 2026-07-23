@extends('v_layouts.app')

@section('title', 'Pemesanan Berhasil')

@section('content')

<style>
    /* Override semua warna primary menjadi ungu */
    .bg-primary {
        background-color: #8b5cf6 !important;
    }
    
    .bg-primary.bg-opacity-10 {
        background-color: rgba(139, 92, 246, 0.1) !important;
    }
    
    .text-primary {
        color: #8b5cf6 !important;
    }
    
    i.text-primary {
        color: #8b5cf6 !important;
    }
    
    .badge.bg-primary {
        background-color: #8b5cf6 !important;
    }
    
    .btn-primary {
        background-color: #8b5cf6 !important;
        border-color: #8b5cf6 !important;
    }
    
    .btn-primary:hover,
    .btn-primary:focus,
    .btn-primary:active {
        background-color: #7c3aed !important;
        border-color: #7c3aed !important;
    }
    
    .list-group-item.active,
    a.list-group-item.active {
        background-color: #8b5cf6 !important;
        border-color: #8b5cf6 !important;
    }
    
    .btn-outline-primary {
        color: #8b5cf6 !important;
        border-color: #8b5cf6 !important;
    }
    
    .btn-outline-primary:hover {
        background-color: #8b5cf6 !important;
        border-color: #8b5cf6 !important;
        color: white !important;
    }

    @media (max-width: 768px) {
        .card-body.p-5 {
            padding: 24px !important;
        }
        .card-body h2 {
            font-size: 1.6rem !important;
        }
        .py-5 {
            padding-top: 2rem !important;
            padding-bottom: 2rem !important;
        }
    }
</style>

<section class="py-5 bg-light min-vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7" data-aos="zoom-in">
                
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-body p-5 text-center">
                        

                        <!-- Success Icon -->
                        <div class="mb-4">
                            <div class="bg-success bg-opacity-10 rounded-circle mx-auto d-flex align-items-center justify-content-center" 
                                style="width: 100px; height: 100px;">
                                <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                            </div>
                        </div>


                        <h2 class="fw-bold mb-3">Pemesanan Dikonfirmasi!</h2>
                        <p class="text-muted mb-4">Pembayaran Anda berhasil. Kami telah mengirimkan e-tiket ke email Anda.</p>
                        <div class="alert alert-info">
                            <i class="bi bi-envelope"></i>
                            E-tiket telah dikirim ke <strong>{{ Auth::user()->email }}</strong>
                        </div>

                        <!-- Booking Details -->
                        <div class="card bg-light border-0 text-start mb-4">
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-4 text-center">Detail Pemesanan</h5>
                                
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <small class="text-muted">Kode Pemesanan</small>
                                        <p class="fw-bold mb-0">{{ $booking->booking_code }}</p>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Status</small>
                                        <p class="mb-0"><span class="badge bg-success">Dikonfirmasi</span></p>
                                    </div>
                                </div>

                                <hr>

                                <div class="mb-3">
                                    <small class="text-muted">Event</small>
                                    <p class="fw-bold mb-0">{{ $booking->event->title }}</p>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-6">
                                        <small class="text-muted">Tanggal</small>
                                        <p class="mb-0">{{ $booking->event->date->format('M d, Y') }}</p>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Waktu</small>
                                        <p class="mb-0">{{ \Carbon\Carbon::parse($booking->event->time)->format('H:i') }} WIB</p>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <small class="text-muted">Venue</small>
                                    <p class="mb-0">{{ $booking->event->venue }}</p>
                                </div>

                                <hr>

                                <div class="row">
                                    <div class="col-6">
                                        <small class="text-muted">Ticket</small>
                                        <p class="mb-0">{{ $booking->quantity }} ticket(s)</p>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Jumlah yang Dibayar</small>
                                        <p class="fw-bold text-primary mb-0">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2">
                            <a href="{{ url('/show-ticket/'.$booking->id) }}" class="btn btn-primary btn-lg rounded-pill">
                                <i class="bi bi-qr-code me-2"></i>Lihat E-Ticket (QR)
                            </a>
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary rounded-pill">
                                <i class="bi bi-house me-2"></i>Kembali ke Home
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

@endsection