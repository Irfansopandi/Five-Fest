@extends('v_layouts.app')

@section('title', 'E-Ticket - ' . $booking->booking_code)

@section('content')

<section class="py-5 bg-light min-vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8" data-aos="zoom-in">
                
                <!-- E-Ticket Card -->
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    
                    <!-- Header -->
                    <div class="card-header bg-primary text-white p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-1 fw-bold">E-Ticket</h4>
                                <small>{{ $booking->booking_code }}</small>
                            </div>
                            <div>
                                <span class="badge bg-success px-3 py-2">Dikonfirmasi</span>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-5">
                        
                        <!-- Event Info -->
                        <div class="text-center mb-4">
                            <img src="{{ asset('storage/' . $booking->event->image) }}" 
                                 class="img-fluid rounded-4 shadow mb-4" 
                                 alt="{{ $booking->event->title }}"
                                 style="max-height: 300px; object-fit: cover;">
                            
                            <h3 class="fw-bold mb-3">{{ $booking->event->title }}</h3>
                            
                            @if($booking->event->artist)
                                <p class="text-muted mb-4">
                                    <i class="bi bi-mic me-2"></i>{{ $booking->event->artist }}
                                </p>
                            @endif
                        </div>

                        <!-- Ticket Details -->
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-3">
                                    <small class="text-muted d-block mb-1">Tanggal</small>
                                    <strong>{{ $booking->event->date->format('l, F d, Y') }}</strong>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-3">
                                    <small class="text-muted d-block mb-1">Waktu</small>
                                    <strong>{{ \Carbon\Carbon::parse($booking->event->time)->format('H:i') }} WIB</strong>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="p-3 bg-light rounded-3">
                                    <small class="text-muted d-block mb-1">Venue</small>
                                    <strong>{{ $booking->event->venue }}</strong>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-3">
                                    <small class="text-muted d-block mb-1">Pemegang Tiket</small>
                                    <strong>{{ $booking->user->name }}</strong>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-3">
                                    <small class="text-muted d-block mb-1">Jumlah Tiket</small>
                                    <strong>{{ $booking->quantity }} tiket</strong>
                                </div>
                            </div>
                        </div>

                        <!-- QR Code -->
                        <div class="text-center mb-4">
                            <div class="bg-light p-4 rounded-3 d-inline-block">
                                <div style="width: 200px; height: 200px; background: white; display: flex; align-items: center; justify-content: center; border: 2px solid #dee2e6;">
                                    <div class="text-center">
                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ $booking->booking_code }}" alt="QR Code" style="width: 150px; height: 150px;">
                                        <p class="small text-muted mb-0 mt-3 fw-bold">{{ $booking->booking_code }}</p>
                                    </div>
                                </div>
                            </div>
                            <p class="text-muted small mt-3">Tunjukkan kode QR ini di pintu masuk</p>
                        </div>

                        <!-- Important Notes -->
                        <div class="alert alert-info rounded-3">
                            <h6 class="fw-bold mb-2"><i class="bi bi-info-circle me-2"></i>Informasi Penting</h6>
                            <ul class="small mb-0">
                                <li>Harap tiba setidaknya 30 menit sebelum acara dimulai</li>
                                <li>Bawa kartu identitas yang sah sesuai dengan nama pemegang tiket</li>
                                <li>Tiket ini tidak dapat dipindah tangankan dan tidak dapat dikembalikan</li>
                                <li>Tangkapan layar tiket ini diterima di pintu masuk</li>
                            </ul>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2 mt-4">
                            <a href="{{ route('booking.download', $booking->id) }}" 
                               class="btn btn-primary btn-lg" target="_blank" onclick="setTimeout(() => window.location.href='/', 1000)">
                                <i class="bi bi-download me-2"></i>Unduh Tiket PDF
                            </a>
                            <a href="{{ url('/') }}" 
                               class="btn btn-outline-secondary">
                                <i class="bi bi-house me-2"></i>Kembali ke Beranda
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

@endsection