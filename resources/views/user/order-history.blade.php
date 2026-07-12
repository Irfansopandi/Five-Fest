@extends('v_layouts.app')

@section('title', 'Riwayat Pesanan')

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
    
   /* Hover effect*/
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

    #logoutFormHistory button:hover {
        background-color: rgba(239, 68, 68, 0.1) !important;
        box-shadow: none !important;
        transform: none !important;
        border-radius: 12px !important;
    }

    /* Tab style */
    #tab-history, #tab-unpaid {
        color: #64748b;
        background: transparent;
        border: none;
        font-size: 1rem;
        padding: 8px 12px !important;
        line-height: 1.5 !important;
        display: block !important;
    }

    #tab-history.active, #tab-unpaid.active {
        background: #8b5cf6 !important;
        color: white !important;
        box-shadow: 0 2px 8px rgba(139,92,246,0.3);
    }

    #tab-history:hover:not(.active), #tab-unpaid:hover:not(.active) {
        background: rgba(139,92,246,0.1) !important;
        color: #8b5cf6 !important;
    }

    #tab-history:hover, #tab-unpaid:hover {
        box-shadow: none !important;
        transform: none !important;
    }

    #tab-history:focus, #tab-unpaid:focus {
        box-shadow: none !important;
        outline: none !important;
    }

    @media (max-width: 767px) {
        .form-select {
            max-width: 100%;
        }

        .col-md-3 .form-select,
        .col-md-6 .form-select,
        .col-md-6 .input-group {
            width: 100% !important;
            max-width: 100% !important;
        }

        .card-body {
            overflow-x: hidden !important;
        }

        .col-lg-9 .card > .card-body {
            padding: 0.75rem !important;
        }

        /* Hapus margin bawah header section */
        .col-lg-9 .card > .card-body > .mb-2 {
            margin-bottom: 0.5rem !important;
        }

        .col-lg-9 .card > .card-body > .mb-2,
        .col-lg-9 .card > .card-body > div[style*="e2e8f0"] {
            margin-bottom: 0.5rem !important;
        }

        .booking-card {
            margin-bottom: 0.5rem !important;
        }

        #tab-history, #tab-unpaid {
            font-size: 0.82rem !important;
            padding:  7px 8px !important;
        }
    }



</style>


<section class="py-5 bg-light min-vh-100">
    <div class="container">
        
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-2" data-aos="fade-down">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none" style="color: #7c3aed;">Home</a></li>
                <li class="breadcrumb-item active">Riwayat Pesanan</li>
            </ol>
        </nav>

        <div class="row g-4 mt-1">
            
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
                            <a href="{{ route('profile') }}" class="list-group-item list-group-item-action border-0 fw-semibold"
                            style="border-radius:12px; color:#64748b; padding:12px 20px; transition:0.3s; margin-bottom:4px;">
                                <i class="bi bi-person-vcard me-3"></i>Data Profil
                            </a>

                            @if(Auth::user()->role !== 'tenant')
                                <a href="{{ route('my-tickets') }}" class="list-group-item list-group-item-action border-0 fw-semibold"
                                style="border-radius:12px; color:#64748b; padding:12px 20px; transition:0.3s; margin-bottom:4px;">
                                    <i class="bi bi-ticket-detailed me-3"></i>Tiket Saya
                                </a>
                            @endif

                            <a href="{{ route('order-history') }}" class="list-group-item list-group-item-action border-0 fw-semibold active"
                            style="border-radius:12px; padding:12px 20px; background-color:#f5f3ff !important; color:#8b5cf6 !important; margin-bottom:4px;">
                                <i class="bi bi-clock-history me-3"></i>Riwayat Pesanan
                            </a>

                            @if(Auth::user()->role === 'tenant')
                                <a href="{{ route('tenant.booths.index') }}" class="list-group-item list-group-item-action border-0 fw-semibold"
                                style="border-radius:12px; color:#64748b; padding:12px 20px; transition:0.3s; margin-bottom:4px;">
                                    <i class="bi bi-shop me-3"></i>Status Booth
                                </a>
                            @endif
                        </div>

                        <hr class="my-4 opacity-50">

                        <form action="{{ route('logout') }}" method="POST" id="logoutFormHistory">
                            @csrf
                            <button type="button" onclick="confirmLogoutHistory()" 
                                class="btn btn-link text-danger text-decoration-none fw-bold w-100 text-start" style="padding: 12px 20px;">
                                <i class="bi bi-box-arrow-left me-3"></i>Keluar Akun
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-3 p-md-5">
                        <div class="mb-2 mb-md-4">
                        @php
                            $historyCount = $bookings->filter(fn($b) => 
                                $b->booking_status !== 'cancelled' && 
                                $b->payment_status !== 'cancelled' &&
                                !($b->payment_status === 'pending' && $b->created_at->copy()->addMinutes(5)->lt(now()))
                            )->count();
                            
                            $failedCount2 = $bookings->filter(fn($b) => 
                                $b->booking_status === 'cancelled' || 
                                $b->payment_status === 'cancelled' ||
                                ($b->payment_status === 'pending' && $b->created_at->copy()->addMinutes(5)->lt(now()))
                            )->count();
                        @endphp

                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <h3 class="fw-bold mb-0">Riwayat Pesanan</h3>
                                <span class="badge bg-primary px-3 py-2" id="badge-history">{{ $historyCount }} Pesanan</span>
                                <span class="badge bg-danger px-3 py-2 d-none" id="badge-failed">{{ $failedCount2 }} Dibatalkan</span>
                            </div>

                            <!-- Show Entries -->
                            <form method="GET" action="{{ route('order-history') }}" class="d-flex align-items-center gap-2 flex-nowrap">
                                @if(request('search'))
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                @endif
                                @if(request('status'))
                                    <input type="hidden" name="status" value="{{ request('status') }}">
                                @endif
                                @if(request('sort'))
                                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                                @endif
                                <label class="text-muted small mb-0 text-nowrap">Tampilkan:</label>
                                <select name="per_page" class="form-select form-select-sm" style="width:auto;" onchange="this.form.submit()">
                                    <option value="5" {{ request('per_page', 10) == 5 ? 'selected' : '' }}>5</option>
                                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ request('per_page', 10) == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('per_page', 10) == 100 ? 'selected' : '' }}>100</option>
                                </select>
                                <span class="text-muted small text-nowrap">entri</span>
                            </form>
                        </div>
                    </div>

                    <!-- Tab Navigation -->
                    <div class="mb-0 mb-md-3" style="background:#e2e8f0; border-radius:12px; padding:3px;">
                        <div class="d-flex gap-1">
                            <button id="tab-history" onclick="switchTab('history')"
                                class="btn fw-semibold flex-fill position-relative"
                                style="border-radius:10px; padding:6px 0; transition:0.2s;">
                                <i class="bi bi-ticket-detailed me-1"></i>Riwayat Tiket
                            </button>
                            <button id="tab-unpaid" onclick="switchTab('unpaid')"
                                class="btn fw-semibold flex-fill position-relative"
                                style="border-radius:10px; padding:6px 0; transition:0.2s;">
                                <i class="bi bi-x-circle me-1"></i>Dibatalkan
                                @php
                                    $failedCount = \App\Models\Booking::where('user_id', Auth::id())
                                        ->where(function($q) {
                                            $q->where('booking_status', 'cancelled')
                                            ->orWhere('payment_status', 'cancelled')
                                            ->orWhere(function($q2) {
                                                $q2->where('payment_status', 'pending')
                                                    ->where('created_at', '<', now()->subMinutes(5));
                                            });
                                        })
                                        ->count();
                                @endphp
                                @if($failedCount > 0)
                                    <span class="badge bg-danger position-absolute" style="top:4px; right:6px; font-size:0.65rem; line-height:1">{{ $failedCount }}</span>
                                @endif
                            </button>
                        </div>
                    </div>
                        <!-- Orders List -->
                        @forelse($bookings as $booking)
                            @php
                                // Hitung waktu kedaluwarsa (5 menit setelah booking dibuat)
                                $expiryTime = $booking->created_at->copy()->addMinutes(5);
                                $now = \Carbon\Carbon::now();
                                $timeRemaining = $now->diffInMinutes($expiryTime, false);
                                $isExpired = $timeRemaining <= 0;
                                $isUrgent = $timeRemaining > 0 && $timeRemaining <= 2;
                                $isCaution = $timeRemaining > 2 && $timeRemaining <= 4;

                                // Tentukan class warning
                                $warningClass = '';
                                if ($booking->payment_status === 'pending') {
                                    if ($isUrgent) {
                                        $warningClass = 'expiry-warning';
                                    } elseif ($isCaution) {
                                        $warningClass = 'expiry-caution';
                                    }
                                }
                                @endphp

                            @php
                                $tabStatus = 'history';
                                if ($booking->booking_status === 'cancelled' 
                                    || $booking->payment_status === 'cancelled'
                                    || ($booking->payment_status === 'pending' && $isExpired)) {
                                    $tabStatus = 'unpaid';
                                }
                            @endphp
                            <div class="card mb-2 mb-md-3 border hover-lift booking-card {{ $warningClass }}"                                data-status="{{ $tabStatus }}">
                                 {{-- timer peringatan untuk pending order --}}
                                 @if ($booking->payment_status === 'pending' && !$isExpired)
                                     <div class="card-header border-0 {{ $isUrgent ? 'bg-danger' : ($isCaution ? 'bg-warning' : 'bg-info') }} bg-opacity-10 py-2">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="bi {{ $isUrgent ? 'bi-alarm-fill text-danger' : ($isCaution ? 'bi-exclamation-triangle-fill text-warning' : 'bi-info-circle-fill text-info') }}"></i>
                                                <small class="fw-bold {{ $isUrgent ? 'text-danger' : ($isCaution ? 'text-warning' : 'text-info') }}">
                                                    @if ($isUrgent)
                                                        SEGERA BAYAR! Pesan akan dibatalkan otomatis dalam :
                                                    @elseif ($isCaution)
                                                        Waktu pembayaran tersisa :
                                                    @else
                                                        Batas waktu pembayaran :
                                                    @endif
                                                </small>
                                            </div>
                                            <div class="countdown-timer {{ $isUrgent ? 'pulse-animation' : ''}}"
                                                data-expiry = "{{ $expiryTime->toIso8601String() }}"
                                                data-booking-id = "{{ $booking->id }}">
                                                @php
                                                    $minutes = $timeRemaining > 0 ? $timeRemaining : 0;
                                                    $seconds = $now->diffInSeconds($expiryTime, false) % 60;
                                                    if ($seconds < 0) $seconds = 0;
                                                @endphp
                                                <span class="badge {{ $isUrgent ? 'bg-danger' : ($isCaution ? 'bg-warning text-dark' : 'bg-info') }} fs-6">
                                                    <i class="bi bi-clock-fill me-1"></i>
                                                    <span class="minutes">{{ $minutes }}</span> menit 
                                                    <span class="seconds">{{ $seconds }}</span> detik
                                                </span>
                                            </div>
                                        </div>
                                     </div>
                                    @elseif ($booking->payment_status === 'pending' && $isExpired)
                                        <div class="card-header border-0 bg-dark bg-opacity-10 py-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="bi bi-x-circle-fill text-muted flex-shrink-0"></i>
                                                <small class="text-muted">Melewati batas waktu pembayaran, akan segera dibatalkan</small>
                                            </div>
                                        </div>
                                    @endif

                                <div class="card-body p-3 p-md-4">
                                {{-- MOBILE LAYOUT --}}
                                    <div class="d-md-none">
                                        <div class="d-flex gap-3 mb-3">
                                            <img src="{{ asset('storage/' . $booking->event->image) }}"
                                                class="rounded flex-shrink-0"
                                                alt="{{ $booking->event->title }}"
                                                style="width:80px;height:80px;object-fit:cover;">
                                            <div class="min-w-0">
                                                <h6 class="fw-bold mb-1 lh-sm">{{ $booking->event->title }}</h6>
                                                <p class="text-muted mb-0" style="font-size:0.75rem;">
                                                    <i class="bi bi-calendar me-1"></i>{{ $booking->event->date->format('d M Y') }}
                                                </p>
                                                <p class="text-muted mb-0" style="font-size:0.75rem;">
                                                    <i class="bi bi-geo-alt me-1"></i>{{ $booking->event->venue }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div class="d-flex gap-1 flex-wrap">
                                                <span class="badge bg-light text-dark border" style="font-size:0.7rem;">
                                                    <i class="bi bi-ticket-perforated me-1"></i>{{ $booking->quantity }} Tiket
                                                </span>
                                                <span class="badge bg-light text-dark border font-monospace" style="font-size:0.7rem;">
                                                    {{ $booking->booking_code }}
                                                </span>
                                            </div>
                                            @if($booking->payment_status === 'paid' && $booking->booking_status === 'confirmed')
                                                <span class="badge bg-success" style="font-size:0.7rem;"><i class="bi bi-check-circle me-1"></i>Dikonfirmasi</span>
                                            @elseif($booking->payment_status === 'pending' && !$isExpired)
                                                <span class="badge bg-warning text-dark" style="font-size:0.7rem;"><i class="bi bi-clock me-1"></i>Pending</span>
                                            @elseif($isExpired || $booking->booking_status === 'cancelled')
                                                <span class="badge bg-danger" style="font-size:0.7rem;"><i class="bi bi-x-circle me-1"></i>{{ $isExpired ? 'Kedaluwarsa' : 'Dibatalkan' }}</span>
                                            @else
                                                <span class="badge bg-secondary" style="font-size:0.7rem;">{{ ucfirst($booking->booking_status) }}</span>
                                            @endif
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mb-3 p-2 rounded-3" style="background:#f8fafc;">
                                            <div>
                                                <div class="text-muted" style="font-size:0.72rem;">Total Pembayaran</div>
                                                <div class="fw-bold text-primary">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                                            </div>
                                            <div class="text-end">
                                                <div class="text-muted" style="font-size:0.72rem;">Dipesan</div>
                                                <div style="font-size:0.8rem;">{{ $booking->created_at->format('d M Y') }}</div>
                                            </div>
                                        </div>

                                        <div class="d-flex gap-2">
                                            @if($booking->payment_status === 'paid' && $booking->booking_status === 'confirmed')
                                                <a href="{{ route('ticket.show', $booking->id) }}" class="btn btn-primary btn-sm flex-fill text-nowrap" style="font-size:0.8rem;">
                                                    <i class="bi bi-ticket-detailed me-1"></i>Lihat Tiket
                                                </a>
                                                <a href="{{ route('ticket.download', $booking->id) }}" class="btn btn-outline-secondary btn-sm px-3 d-flex align-item-center justify-contect-center" style="font-size:0.8rem; min-width: 70px;">
                                                    <i class="bi bi-download"></i> Unduh
                                                </a>
                                            @elseif($booking->payment_status === 'pending' && !$isExpired)
                                                <a href="{{ route('booking.payment', $booking->id) }}" class="btn btn-warning btn-sm flex-fill text-dark {{ $isUrgent ? 'pulse-animation' : '' }}" style="font-size:0.8rem;">
                                                    <i class="bi bi-credit-card me-1"></i>Bayar Sekarang
                                                </a>
                                            @endif
                                            <button class="btn btn-outline-primary btn-sm {{ ($booking->payment_status === 'paid' || ($booking->payment_status === 'pending' && !$isExpired)) ? '' : 'flex-fill' }}"
                                                    data-bs-toggle="collapse" data-bs-target="#detail-{{ $booking->id }}" style="font-size:0.8rem;">
                                                <i class="bi bi-info-circle me-1"></i>Detail
                                            </button>
                                        </div>
                                    </div>

                                    {{-- DESKTOP LAYOUT (original) --}}
                                    <div class="d-none d-md-block">
                                        <div class="row align-items-center">
                                            <div class="col-md-2">
                                                <img src="{{ asset('storage/' . $booking->event->image) }}"
                                                    class="img-fluid rounded"
                                                    alt="{{ $booking->event->title }}"
                                                    style="height: 100px; width: 100%; object-fit: cover;">
                                            </div>
                                            <div class="col-md-5">
                                                <h5 class="fw-bold mb-0">{{ $booking->event->title }}</h5>
                                                <p class="text-muted mb-1 small"><i class="bi bi-calendar me-2"></i>{{ $booking->event->date->format('l, d F Y') }}</p>
                                                <p class="text-muted mb-1 small"><i class="bi bi-clock me-2"></i>{{ \Carbon\Carbon::parse($booking->event->time)->format('H:i') }} WIB</p>
                                                <p class="text-muted mb-1 small"><i class="bi bi-geo-alt me-2"></i>{{ $booking->event->venue }}</p>
                                                <div class="mt-2">
                                                    <span class="badge bg-light text-dark border me-2"><i class="bi bi-ticket-perforated me-1"></i>{{ $booking->quantity }} Tiket</span>
                                                    <span class="badge bg-light text-dark border font-monospace">{{ $booking->booking_code }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <small class="text-muted d-block mb-1">Tanggal Pemesanan</small>
                                                <p class="mb-2 fw-medium">{{ $booking->created_at->format('d M Y, H:i') }}</p>
                                                <small class="text-muted d-block mb-1">Total Pembayaran</small>
                                                <h5 class="text-primary fw-bold mb-2">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</h5>
                                                @if($booking->payment_status === 'paid' && $booking->booking_status === 'confirmed')
                                                    <span class="badge bg-success px-3 py-2"><i class="bi bi-check-circle me-1"></i>Dikonfirmasi</span>
                                                @elseif($booking->payment_status === 'pending' && !$isExpired)
                                                    <span class="badge bg-warning text-dark px-3 py-2"><i class="bi bi-clock me-1"></i>Menunggu Pembayaran</span>
                                                @elseif($isExpired || $booking->booking_status === 'cancelled')
                                                    <span class="badge bg-danger px-3 py-2"><i class="bi bi-x-circle me-1"></i>{{ $isExpired ? 'kedaluwarsa' : 'Dibatalkan' }}</span>
                                                @else
                                                    <span class="badge bg-secondary px-3 py-2"><i class="bi bi-info-circle me-1"></i>{{ ucfirst($booking->booking_status) }}</span>
                                                @endif
                                            </div>
                                            <div class="col-md-2 text-end">
                                                <div class="d-grid gap-2">
                                                    @if($booking->payment_status === 'paid' && $booking->booking_status === 'confirmed')
                                                        <a href="{{ route('ticket.show', $booking->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-ticket-detailed me-1"></i>Lihat Tiket</a>
                                                        <a href="{{ route('ticket.download', $booking->id) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-download me-1"></i>Unduh</a>
                                                    @elseif($booking->payment_status === 'pending' && !$isExpired)
                                                        <a href="{{ route('booking.payment', $booking->id) }}" class="btn btn-warning btn-sm text-dark {{ $isUrgent ? 'pulse-animation' : '' }}"><i class="bi bi-credit-card me-1"></i>Bayar Sekarang</a>
                                                    @endif
                                                    <button class="btn btn-outline-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#detail-{{ $booking->id }}">
                                                        <i class="bi bi-info-circle me-1"></i>Detail
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Collapsible Detail (shared) --}}
                                    <div class="collapse mt-3" id="detail-{{ $booking->id }}">
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-6 mb-3 mb-md-0">
                                                <h6 class="fw-bold mb-3">Informasi Pemesan</h6>
                                                <table class="table table-sm table-borderless">
                                                    <tr><td class="text-muted" width="150">Nama</td><td>: {{ $booking->user->name }}</td></tr>
                                                    <tr><td class="text-muted">Email</td><td>: {{ $booking->user->email }}</td></tr>
                                                    <tr><td class="text-muted">No. Telepon</td><td>: {{ $booking->user->phone ?? 'Tidak tersedia' }}</td></tr>
                                                    <tr>
                                                        <td class="text-muted">Status Bayar</td>
                                                        <td>:
                                                            @if($booking->payment_status === 'paid') <span class="badge bg-success">Lunas</span>
                                                            @elseif($booking->payment_status === 'pending' && !$isExpired) <span class="badge bg-warning">Menunggu</span>
                                                            @else <span class="badge bg-danger">{{ $isExpired ? 'Kedaluwarsa' : 'Gagal' }}</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @if($booking->payment_status === 'pending' && !$isExpired)
                                                        <tr><td class="text-muted">Batas Bayar</td><td>: {{ $expiryTime->format('d M Y, H:i') }} WIB</td></tr>
                                                    @endif
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="fw-bold mb-3">Rincian Pembayaran</h6>
                                                <table class="table table-sm table-borderless">
                                                    <tr><td class="text-muted" width="150">Harga Tiket</td><td class="text-end">Rp {{ number_format($booking->event->price, 0, ',', '.') }}</td></tr>
                                                    <tr><td class="text-muted">Jumlah</td><td class="text-end">x {{ $booking->quantity }}</td></tr>
                                                    <tr><td class="text-muted">Metode</td><td class="text-end">{{ strtoupper($booking->payment_method) }}</td></tr>
                                                    <tr class="border-top"><td class="fw-bold">Total</td><td class="text-end fw-bold text-primary">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td></tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="bi bi-inbox text-muted" style="font-size: 5rem; opacity: 0.3;"></i>
                                <h5 class="fw-bold mt-4 mb-2">Belum Ada Riwayat Pesanan</h5>
                                <p class="text-muted mb-4">Anda belum melakukan pemesanan apapun</p>
                                <a href="{{ route('home') }}" class="btn btn-primary">
                                    <i class="bi bi-search me-2"></i>Jelajahi Event
                                </a>
                            </div>
                        @endforelse

                        <!-- Pagination -->
                        @if($bookings->hasPages())
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <p class="text-muted mb-0">
                                    Menampilkan {{ $bookings->firstItem() }} - {{ $bookings->lastItem() }} 
                                    dari {{ $bookings->total() }} pesanan
                                </p>
                                {{ $bookings->links() }}
                            </div>
                        @endif

                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Custom CSS -->
<style>
.hover-lift {
    transition: all 0.3s ease;
}

.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
}

.font-monospace {
    font-family: 'Courier New', monospace;
}
</style>



<script>
document.addEventListener('DOMContentLoaded', function() {
    // Countdown timers
    function updateCountdowns() {
        const timers = document.querySelectorAll('.countdown-timer');
        timers.forEach(timer => {
            const expiryDate = new Date(timer.dataset.expiry);
            const now = new Date();
            const diff = expiryDate - now;
            if (diff <= 0) { location.reload(); return; }
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);
            const minutesEl = timer.querySelector('.minutes');
            const secondsEl = timer.querySelector('.seconds');
            if (minutesEl) minutesEl.textContent = minutes;
            if (secondsEl) secondsEl.textContent = seconds;
        });
    }
    updateCountdowns();
    const interval = setInterval(updateCountdowns, 1000);
    window.addEventListener('beforeunload', () => clearInterval(interval));

    // Tab awal dari URL
    const tab = new URLSearchParams(window.location.search).get('tab') || 'history';
    switchTab(tab);
});

function confirmLogoutHistory() {
    Swal.fire({
        title: 'Keluar Akun?',
        text: 'Apakah kamu yakin ingin keluar dari FiveFest?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e11d48',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="bi bi-box-arrow-right me-1"></i> Ya, Keluar',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('logoutFormHistory').submit();
        }
    });
}

function switchTab(tab) {
    document.getElementById('tab-history').classList.toggle('active', tab === 'history');
    document.getElementById('tab-unpaid').classList.toggle('active', tab === 'unpaid');

    // Toggle badge
    document.getElementById('badge-history').classList.toggle('d-none', tab !== 'history');
    document.getElementById('badge-failed').classList.toggle('d-none', tab !== 'unpaid');

    document.querySelectorAll('.booking-card[data-status]').forEach(card => {
        if (tab === 'history') {
            card.style.display = card.dataset.status === 'history' ? '' : 'none';
        } else {
            card.style.display = card.dataset.status === 'unpaid' ? '' : 'none';
        }
    });

    const url = new URL(window.location);
    url.searchParams.set('tab', tab);
    window.history.replaceState({}, '', url);
}


</script>


@endsection