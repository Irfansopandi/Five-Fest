@extends('v_layouts.app')

@section('title', 'Tiket Saya')

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
    
    /* Hover effect */
    .list-group-item-action:not(.active):hover {
        background-color: #f1f5f9 !important;
        color: #1e293b !important;
        padding-left: 25px !important;
    }

    /* Active state tetap soft purple */
    .list-group-item-action.active {
        background-color: #f5f3ff !important;
        color: #8b5cf6 !important;
    }

    .list-group-item-action.active:hover {
        background-color: #f5f3ff !important;
        color: #8b5cf6 !important;
        padding-left: 20px !important;
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
    .nav-link.active {
        background-color: #8b5cf6 !important;
    }

    .btn-link:hover, .btn-link:focus {
        background-color: rgba(239, 68, 68, 0.1) !important;
        box-shadow: none !important;
    }

    .list-group-item.text-danger, 
        button.btn-link {
            transition: 0.3s ease;
    
        }
    
    .list-group-item.text-danger:hover, 
        button.btn-link:hover {
            background-color: rgba(239, 68, 68, 0.1) !important;
            box-shadow: none !important;
            transform: none !important;
            border-radius: 12px !important;
        }

    

    
</style>


<section class="py-5 bg-light min-vh-100">
    <div class="container">
        
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" data-aos="fade-down">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none" style="color: #7c3aed;">Home</a></li>
                <li class="breadcrumb-item active">Tiket Saya</li>
            </ol>
        </nav>

        <div class="row g-4 mt-3">
            
            <!-- Sidebar -->
            <div class="col-lg-3" data-aos="fade-right">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-3">
                        <div class="text-center mb-4 pt-3">
                            <div class="avatar-wrapper mb-3" style="width:120px;height:120px;margin:0 auto;">
                                <div style="width:100%;height:100%;background:linear-gradient(135deg,#8b5cf6 0%,#d8b4fe 100%);border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-size:3.5rem;box-shadow:0 8px 20px rgba(139,92,246,0.2);">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                            </div>
                            <h5 class="fw-bold text-dark mb-1">{{ Auth::user()->name }}</h5>
                            <span class="badge rounded-pill mb-4" style="background:#f5f3ff;color:#8b5cf6;">
                                <i class="bi bi-shield-check me-1"></i>{{ ucfirst(Auth::user()->role) }}
                            </span>
                        </div>

                        <div class="list-group" style="gap:4px;">
                            <a href="{{ route('profile') }}"
                                class="list-group-item list-group-item-action border-0 fw-semibold"
                                style="border-radius:12px;color:#64748b;padding:12px 20px;transition:0.3s;">
                                <i class="bi bi-person-vcard me-3"></i>Data Profil
                            </a>
                            <a href="{{ route('my-tickets') }}"
                                class="list-group-item list-group-item-action border-0 fw-semibold active"
                                style="border-radius:12px;padding:12px 20px;background:#f5f3ff !important;color:#8b5cf6 !important;">
                                <i class="bi bi-ticket-perforated me-3"></i>Tiket Saya
                            </a>
                            <a href="{{ route('order-history') }}"
                                class="list-group-item list-group-item-action border-0 fw-semibold"
                                style="border-radius:12px;color:#64748b;padding:12px 20px;transition:0.3s;">
                                <i class="bi bi-clock-history me-3"></i>Riwayat Pesanan
                            </a>
                        </div>

                        <hr class="my-4 opacity-50">

                        <form action="{{ route('logout') }}" method="POST" id="logoutFormTicket" class="px-1">
                            @csrf
                            <button type="button" onclick="confirmLogout('logoutFormTicket')"
                                class="btn btn-link text-danger text-decoration-none fw-bold w-100 text-start" style="padding: 12px 20px;">
                                <i class="bi bi-box-arrow-left me-3"></i>Keluar Akun
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9" data-aos="fade-left">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-3 p-md-5">
                        <h3 class="fw-bold mb-4">Tiket Saya</h3>

                        <!-- Tabs -->
                        <ul class="nav nav-pills mb-4" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#upcoming">
                                    <i class="bi bi-calendar-event me-2"></i>Mendatang
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#past">
                                    <i class="bi bi-clock-history me-2"></i>Acara Sebelumnya
                                </button>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content">
                            
                            <!-- Upcoming Tickets -->
                            <div class="tab-pane fade-show active" id="upcoming">
                                @forelse($upcomingBookings as $booking)
                                    <div class="card mb-3 border hover-lift" data-aos="fade-up">
                                        <div class="card-body p-3 p-md-4">
                                            {{-- MOBILE LAYOUT --}}
                                            <div class="d-md-none">
                                                <div class="d-flex gap-3 mb-3 mt-1">
                                                    <img src="{{ asset('storage/' . $booking->event->image) }}"
                                                        class="rounded flex-shrink-0"
                                                        alt="{{ $booking->event->title }}"
                                                        style="width:75px;height:75px;object-fit:cover;margin-top:4px;">
                                                    <div>
                                                        <h6 class="fw-bold mb-1 lh-sm">{{ $booking->event->title }}</h6>
                                                        <p class="text-muted mb-0" style="font-size:0.75rem;">
                                                            <i class="bi bi-calendar me-1"></i>{{ $booking->event->date->format('d M Y') }}
                                                        </p>
                                                        <p class="text-muted mb-0" style="font-size:0.75rem;">
                                                            <i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($booking->event->time)->format('H:i') }} WIB
                                                        </p>
                                                        <p class="text-muted mb-0" style="font-size:0.75rem;">
                                                            <i class="bi bi-geo-alt me-1"></i>{{ $booking->event->venue }}
                                                        </p>
                                                        <p class="text-muted mb-0" style="font-size:0.75rem;">
                                                            <i class="bi bi-ticket-perforated me-1"></i>{{ $booking->quantity }} tiket · {{ $booking->booking_code }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="d-flex gap-2 align-items-center my-3 p-3 rounded-3" style="background:#f8fafc;">
                                                    {{-- Kiri: detail info --}}
                                                    <div class="flex-fill">
                                                        <p class="text-muted mb-1" style="font-size:0.72rem; text-transform:uppercase; letter-spacing:0.5px;">Kode Booking</p>
                                                        <p class="fw-bold font-monospace mb-2" style="font-size:0.8rem;">{{ $booking->booking_code }}</p>
                                                        <p class="text-muted mb-1" style="font-size:0.72rem; text-transform:uppercase; letter-spacing:0.5px;">Jumlah Tiket</p>
                                                        <p class="fw-bold mb-2" style="font-size:0.85rem;">{{ $booking->quantity }} tiket</p>
                                                        <span class="badge bg-success px-3 py-1">
                                                            <i class="bi bi-check-circle me-1"></i>Dikonfirmasi
                                                        </span>
                                                    </div>
                                                    {{-- Kanan: QR Code --}}
                                                    <div class="text-center flex-shrink-0">
                                                        <div class="bg-white p-1 rounded border d-inline-block">
                                                            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(90)->generate($booking->booking_code) !!}
                                                        </div>
                                                        <p class="mb-0 mt-1 text-muted" style="font-size:0.65rem;">Scan QR</p>
                                                    </div>
                                                </div>

                                                <div class="d-flex gap-2 mt-2">
                                                    <a href="{{ route('ticket.show', $booking->id) }}" class="btn btn-primary btn-sm flex-fill text-nowrap" style="font-size:0.8rem;">
                                                        <i class="bi bi-ticket-detailed me-1"></i>Lihat Tiket
                                                    </a>
                                                    <a href="{{ route('ticket.download', $booking->id) }}" class="btn btn-outline-secondary btn-sm px-3 d-flex align-items-center justify-content-center gap-1" style="font-size:0.8rem; min-width:70px;">
                                                        <i class="bi bi-download"></i>Unduh
                                                    </a>
                                                </div>
                                            </div>

                                            {{-- DESKTOP LAYOUT --}}
                                            <div class="d-none d-md-block">
                                                <div class="row align-items-center">
                                                    <div class="col-md-2">
                                                        <img src="{{ asset('storage/' . $booking->event->image) }}"
                                                            class="img-fluid rounded"
                                                            alt="{{ $booking->event->title }}"
                                                            style="height: 100px; width: 100%; object-fit: cover;">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <h5 class="fw-bold mb-2">{{ $booking->event->title }}</h5>
                                                        <p class="text-muted mb-1 small"><i class="bi bi-calendar me-2"></i>{{ $booking->event->date->format('l, F d, Y') }}</p>
                                                        <p class="text-muted mb-1 small"><i class="bi bi-clock me-2"></i>{{ \Carbon\Carbon::parse($booking->event->time)->format('H:i') }} WIB</p>
                                                        <p class="text-muted mb-1 small"><i class="bi bi-geo-alt me-2"></i>{{ $booking->event->venue }}</p>
                                                        <p class="text-muted mb-0 small"><i class="bi bi-ticket-perforated me-2"></i>{{ $booking->quantity }} ticket(s) • {{ $booking->booking_code }}</p>
                                                    </div>
                                                    <div class="col-md-3 text-center">
                                                        <div class="bg-white p-2 rounded border d-inline-block">
                                                            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(120)->generate($booking->booking_code) !!}
                                                        </div>
                                                        <p class="small text-muted mb-0 mt-1">{{ $booking->booking_code }}</p>
                                                    </div>
                                                    <div class="col-md-3 text-end">
                                                        <span class="badge bg-success mb-3 px-3 py-2">Dikonfirmasi</span>
                                                        <div class="d-grid gap-2">
                                                            <a href="{{ route('ticket.show', $booking->id) }}" class="btn btn-primary btn-sm">
                                                                <i class="bi bi-ticket-detailed me-2"></i>Lihat Tiket
                                                            </a>
                                                            <a href="{{ route('ticket.download', $booking->id) }}" class="btn btn-outline-secondary btn-sm">
                                                                <i class="bi bi-download me-2"></i>Unduh
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-5">
                                        <i class="bi bi-ticket-perforated text-muted" style="font-size: 5rem; opacity: 0.3;"></i>
                                        <h5 class="fw-bold mt-4 mb-2">Tidak Ada Tiket Mendatang</h5>
                                        <p class="text-muted mb-4">Anda belum membeli tiket apa pun</p>
                                        <a href="{{ route('home') }}" class="btn btn-primary">
                                            <i class="bi bi-search me-2"></i>Telusuri Acara
                                        </a>
                                    </div>
                                @endforelse
                                
                                @if($upcomingBookings->hasPages())
                                    <div class="d-flex justify-content-center mt-4">
                                        {{ $upcomingBookings->appends(request()->except('upcoming_page'))->links() }}
                                    </div>
                                @endif
                            </div>

                            <!-- Past Tickets -->
                            <div class="tab-pane fade" id="past">
                                @forelse($pastBookings as $booking)
                                    <div class="card mb-3 border" data-aos="fade-up">
                                        <div class="card-body p-4">
                                            <div class="row align-items-center">
                                                <div class="col-md-2">
                                                    <img src="{{ asset('storage/' . $booking->event->image) }}" 
                                                        class="img-fluid rounded opacity-75" 
                                                        alt="{{ $booking->event->title }}"
                                                        style="height: 100px; width: 100%; object-fit: cover;">
                                                </div>
                                                
                                                <div class="col-md-7">
                                                    <h5 class="fw-bold mb-2">{{ $booking->event->title }}</h5>
                                                    <p class="text-muted mb-1 small">
                                                        <i class="bi bi-calendar me-2"></i>
                                                        {{ $booking->event->date->format('l, F d, Y') }}
                                                    </p>
                                                    <p class="text-muted mb-0 small">
                                                        <i class="bi bi-ticket-perforated me-2"></i>
                                                        {{ $booking->quantity }} ticket(s) • {{ $booking->booking_code }}
                                                    </p>
                                                </div>

                                                <div class="col-md-3 text-end">
                                                    <span class="badge bg-secondary mb-2">Dihadiri</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-5">
                                        <i class="bi bi-clock-history text-muted" style="font-size: 5rem; opacity: 0.3;"></i>
                                        <h5 class="fw-bold mt-4 mb-2">Tidak Ada Acara Sebelumnya</h5>
                                        <p class="text-muted">Acara yang Anda hadiri akan muncul di sini</p>
                                    </div>
                                @endforelse
                                
                                @if($pastBookings->hasPages())
                                    <div class="d-flex justify-content-center mt-4">
                                        {{ $pastBookings->appends(request()->except('past_page'))->links() }}
                                    </div>
                                @endif
                            </div>

                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection