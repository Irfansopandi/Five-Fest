@extends('v_layouts.app')

@section('title', $event->title)

@section('content')
<style>
    .event-hero {
        position: relative;
        background: #1a1a1a;
        padding: 60px 0;
        color: white;
        overflow: hidden;
    }
    .hero-bg {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background-image: url("{{ asset('storage/' . $event->image) }}");
        background-size: cover;
        background-position: center;
        filter: blur(20px) brightness(0.4);
        transform: scale(1.1);
        z-index: 1;
    }
    .hero-container { position: relative; z-index: 2; }

    .nav-tabs-loket {
        display: flex;
        gap: 30px;
        border-bottom: 2px solid #f1f5f9;
        margin-bottom: 30px;
    }
    .nav-link-loket {
        border: none;
        background: none;
        padding: 12px 0;
        font-weight: 700;
        color: #94a3b8;
        position: relative;
        transition: 0.3s;
    }
    .nav-link-loket.active { color: #8b5cf6; }
    .nav-link-loket.active::after {
        content: "";
        position: absolute;
        bottom: -2px; left: 0; width: 100%; height: 4px;
        background: #8b5cf6;
        border-radius: 10px;
    }
    .update-dot {
        position: absolute;
        top: 5px;
        right: -8px;
        width: 10px;
        height: 10px;
        background: #ef4444;
        border-radius: 50%;
        box-shadow: 0 0 0 2px white;
        animation: pulse-red 1.5s infinite;
        z-index: 5;
    }
    @keyframes pulse-red {
        0% { transform: scale(0.9); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
        70% { transform: scale(1.2); box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
        100% { transform: scale(0.9); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
    }

    .countdown-box {
        background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        color: white;
        padding: 1.5rem;
        border-radius: 20px;
        text-align: center;
    }
    .countdown-timer { display: flex; justify-content: center; gap: 15px; }
    .timer-part { display: flex; flex-direction: column; }
    .timer-num { font-size: 1.8rem; font-weight: 800; line-height: 1; }
    .timer-label { font-size: 0.7rem; opacity: 0.8; text-transform: uppercase; margin-top: 5px; }

    .ticket-item-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 20px;
        transition: 0.3s;
        margin-bottom: 12px;
    }
    .ticket-item-card:hover { border-color: #8b5cf6; box-shadow: 0 8px 15px rgba(0,0,0,0.05); }

    .sticky-card {
        position: sticky;
        top: 100px;
        border-radius: 24px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
    }

    /* ===== FAB Toggle Button ===== */
    .sp-toggle-fab {
        position: fixed;
        bottom: 28px; right: 24px;
        z-index: 100001;  /* selalu di atas bar (99998) */
        background: linear-gradient(135deg, #7c3aed, #a855f7);
        color: #fff; border: none; cursor: pointer;
        border-radius: 50%;
        width: 60px; height: 60px;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 6px 24px rgba(124,58,237,0.4);
        transition: bottom 0.3s ease;
        animation: sp-fab-bounce 2.5s ease-in-out infinite;
    }
    .sp-toggle-fab:hover {
        /* transform: scale(1.8); */
        box-shadow: 0 8px 28px rgba(96, 15, 235, 0.662);
    }
    .sp-toggle-fab i { font-size: 1.5rem; }

    #sp-custom-bar {
        position: fixed;
        bottom: 0; left: 0; right: 0;
        z-index: 99999;
        background: #000;
    }
    @keyframes sp-fab-bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }

    @media (max-width: 768px) {
        /* ===== HERO ===== */
        .event-hero {
            padding: 0 !important;
        }

        /* Sembunyikan background blur di mobile */
        .hero-bg {
            display: none !important;
        }

        /* Poster tampil full width di atas */
        .event-hero .col-md-4 {
            display: block !important;
            padding: 0 !important;
        }
        .event-hero .col-md-4 img {
            width: 100% !important;
            max-height: 280px !important;
            object-fit: cover !important;
            border-radius: 0 !important;
        }

        /* Info event di bawah poster */
        .event-hero .col-md-8 {
            text-align: left !important;
            padding: 16px !important;
            background: #1a1a1a !important;
        }

        .event-hero h1 {
            font-size: 1.2rem !important;
            margin-bottom: 10px !important;
        }

        .event-hero .d-flex.flex-wrap.gap-4 {
            flex-direction: column !important;
            gap: 6px !important;
            font-size: 0.8rem !important;
            margin-bottom: 12px !important;
        }

        .event-hero .d-inline-flex {
            font-size: 0.75rem !important;
            padding: 6px 12px !important;
        }

        /* Row tidak perlu align-items-center di mobile */
        .event-hero .row {
            flex-direction: column !important;
            margin: 0 !important;
            gap: 0 !important;
        }

        .event-hero .col-md-4,
        .event-hero .col-md-8 {
            width: 100% !important;
            max-width: 100% !important;
        }

        /* ===== MAIN CONTENT ===== */
        .container.py-5 {
            padding-top: 16px !important;
            padding-bottom: 100px !important;
        }

        /* ===== TAB NAVIGASI ===== */
        .nav-tabs-loket {
            gap: 0 !important;
            overflow-x: auto !important;
            overflow-y: hidden !important;
            -webkit-overflow-scrolling: touch !important;
            scrollbar-width: none !important;
            flex-wrap: nowrap !important;
            margin-bottom: 20px !important;
            padding-bottom: 0 !important;
        }
        .nav-tabs-loket::-webkit-scrollbar { display: none; }
        .nav-link-loket {
            white-space: nowrap !important;
            padding: 10px 16px !important;
            font-size: 0.82rem !important;
        }

        /* ===== SIDEBAR HIDDEN ===== */
        .col-lg-4 { display: none !important; }
        .col-lg-8 {
            width: 100% !important;
            max-width: 100% !important;
        }

        /* ===== MOBILE STICKY BAR ===== */
        .mobile-sticky-bar {
            display: flex !important;
        }

        /* ===== TICKET CARD ===== */
        .ticket-item-card {
            padding: 14px !important;
        }
        .ticket-item-card h6 { font-size: 0.88rem !important; }
        .ticket-item-card h4 { font-size: 1.1rem !important; }

        /* ===== SP FAB ===== */
        .sp-toggle-fab {
            width: 50px !important;
            height: 50px !important;
            right: 16px !important;
            bottom: 80px !important;
            z-index: 100001 !important;
            transition: bottom 0.3s ease !important;
        }
        .sp-toggle-fab i { font-size: 1.2rem; }

        #sp-custom-bar {
            z-index: 99998 !important;
        }
        #sp-iframe-wrap iframe {
            pointer-events: all !important;
            display: block !important;
        }
    }
    
</style>

<div class="event-hero">
    <div class="hero-bg"></div>
    <div class="container hero-container">
        <div class="row align-items-center g-4">
            <div class="col-md-4 col-lg-3">
                <img src="{{ asset('storage/' . $event->image) }}" class="img-fluid rounded-4 shadow-lg" alt="Poster {{ $event->title }}">
            </div>
            <div class="col-md-8 col-lg-9 text-start">
                <span class="badge px-3 py-2 mb-3" style="background: rgba(139, 92, 246, 0.2); color: #c4b5fd; border: 1px solid rgba(139, 92, 246, 0.3);">
                    <i class="bi bi-tag-fill me-1"></i> {{ $event->category }}
                </span>
                <h1 class="fw-bold mb-4 text-white">{{ $event->title }}</h1>
                <div class="d-flex flex-wrap gap-4 text-white-50 mb-4">
                    <div><i class="bi bi-geo-alt-fill text-warning me-2"></i> {{ $event->venue }}</div>
                    <div><i class="bi bi-calendar3 text-warning me-2"></i> {{ $event->date->format('d M Y') }}</div>
                    <div><i class="bi bi-clock-fill text-warning me-2"></i> {{ \Carbon\Carbon::parse($event->time)->format('H:i') }} WIB</div>
                </div>

                {{-- Vendor Info --}}
                <div class="d-flex align-items-center bg-white bg-opacity-10 p-2 px-3 rounded-pill d-inline-flex border border-white border-opacity-20">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($event->vendor->name ?? 'F') }}&background=8b5cf6&color=fff" class="rounded-circle me-2" style="width: 30px; height: 30px; object-fit: cover;">
                    <span class="small text-white fw-600">Diselenggarakan oleh <strong class="text-white">{{ $event->vendor->name ?? 'FiveFest Official' }}</strong></span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Spotify Embed Player (di luar hero, fixed bottom) --}}
@if(!empty($spotifyTracks) && isset($spotifyTracks[0]['embed_id']))
@php
    $embedType = $spotifyTracks[0]['embed_type'] ?? 'playlist';
    $embedId   = $spotifyTracks[0]['embed_id'];
@endphp


<button id="sp-toggle" class="sp-toggle-fab" title="Putar Musik Event" onclick="toggleSpotify()">
    <i class="bi bi-music-note-beamed" id="sp-toggle-icon"></i>
</button>
@endif

<div class="container py-5">
    <div class="row g-5">
        <div class="col-lg-8">
            @php $isTenantView = auth()->check() && auth()->user()->role === 'tenant'; @endphp
            <div class="nav nav-tabs-loket" id="eventTab" role="tablist">
                <button class="nav-link-loket active" data-bs-toggle="tab" data-bs-target="#panel-deskripsi">Deskripsi</button>
                @if(!$isTenantView)
                <button class="nav-link-loket" data-bs-toggle="tab" data-bs-target="#panel-tiket">Kategori Tiket</button>
                @endif
                <button class="nav-link-loket" data-bs-toggle="tab" data-bs-target="#panel-sk">S&K</button>
                <button class="nav-link-loket" data-bs-toggle="tab" data-bs-target="#panel-venue">{{ $isTenantView ? 'Denah Tenant' : 'Denah Venue' }}</button>
                <button class="nav-link-loket position-relative" data-bs-toggle="tab" data-bs-target="#panel-info">
                    Informasi
                    @if($event->last_update_at && \Carbon\Carbon::parse($event->last_update_at)->gt(now()->subDay()))
                        <span class="update-dot"></span>
                    @endif
                </button>
            </div>

            <div class="tab-content">
                {{-- Panel Deskripsi --}}
                <div class="tab-pane fade show active" id="panel-deskripsi">
                    <h5 class="fw-bold mb-4">Tentang Acara</h5>
                    <div class="text-muted lh-lg">
                        {!! $event->description !!}
                    </div>
                </div>

                {{-- Panel Tiket --}}
                @if(!$isTenantView)
                <div class="tab-pane fade" id="panel-tiket">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="fw-bold mb-0">Daftar Kategori Tiket</h5>
                        @if($event->seat_plan || $event->seatplan)
                        <button class="btn btn-outline-primary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalSeatplan">
                            <i class="bi bi-map-fill me-2"></i>Lihat Peta Kursi (Seatmap)
                        </button>
                        @endif
                    </div>
                    <p class="text-muted small mb-4"><i class="bi bi-info-circle me-1"></i> Maksimal pembelian: <strong>{{ $event->max_tickets_per_user ?? 4 }} tiket</strong> per transaksi.</p>

                    @foreach($event->ticket_categories as $ticket)
                    <div class="ticket-item-card d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fw-bold text-dark mb-1">{{ $ticket->name }}</h6>
                            <p class="small text-muted mb-2"><i class="bi bi-info-circle me-1"></i> {{ $ticket->benefits ?? 'Akses masuk area event' }}</p>
                            <span class="badge bg-light text-dark border">Sisa: {{ $ticket->quota }}</span>
                        </div>
                        <div class="text-end">
                            <h4 class="fw-bold text-primary mb-0">Rp{{ number_format($ticket->price, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

                {{-- Panel S&K --}}
                <div class="tab-pane fade" id="panel-sk">
                    <h5 class="fw-bold mb-4">Syarat & Ketentuan Resmi</h5>
                    @if($event->terms_image)
                        <img src="{{ asset('storage/' . $event->terms_image) }}" class="img-fluid rounded-4 shadow-sm mb-4 border" alt="Poster S&K">
                    @endif
                    <div class="bg-light p-4 rounded-4 border">
                        <div class="text-muted small lh-lg">
                            {!! $event->terms ?? 'Harap membawa identitas asli saat penukaran tiket di lokasi.' !!}
                        </div>
                    </div>
                </div>

                {{-- Panel Denah Venue / Tenant --}}
                <div class="tab-pane fade" id="panel-venue">
                    <h5 class="fw-bold mb-4">{{ $isTenantView ? 'Peta & Lokasi Stand Tenant' : 'Peta & Lokasi Venue' }}</h5>
                    @php
                        $mapImage = $isTenantView && $event->booth_map ? $event->booth_map : $event->venue_map;
                    @endphp
                    @if($mapImage)
                        <div class="mb-4">
                            <h6 class="small fw-bold text-muted text-uppercase mb-3">{{ $isTenantView ? 'Denah Posisi Stand' : 'Denah Fasilitas (Gate, Toilet, dll)' }}</h6>
                            <img src="{{ asset('storage/' . $mapImage) }}" class="img-fluid rounded-4 border shadow-sm w-100" alt="Map">
                        </div>
                    @else
                        <div class="mb-4 bg-light p-4 rounded-4 border text-center">
                            <i class="bi bi-map text-muted opacity-50 fs-1 mb-2 d-block"></i>
                            <h6 class="fw-bold text-dark mb-2">Denah Belum Tersedia</h6>
                            <p class="text-muted small mb-0">{{ $event->map_notice ?? 'Peta denah akan diinformasikan lebih lanjut oleh pihak penyelenggara.' }}</p>
                        </div>
                    @endif

                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                        <div class="card-body p-0 position-relative">
                            <div class="p-4">
                                <h6 class="fw-bold mb-1"><i class="bi bi-geo-alt-fill me-2 text-danger"></i>Lokasi Venue</h6>
                                <p class="small text-muted mb-3">{{ $event->venue }}</p>
                                @if($event->venue_location_url)
                                <a href="{{ $event->venue_location_url }}" target="_blank" class="btn btn-ff-primary btn-sm rounded-pill px-4 shadow-sm">
                                    <i class="bi bi-google me-2"></i> Buka di Google Maps
                                </a>
                                @endif
                            </div>
                            <div class="venue-preview-overlay" style="height: 150px;">
                                <iframe src="https://maps.google.com/maps?q={{ urlencode($event->venue) }}&t=&z=14&ie=UTF8&iwloc=&output=embed" style="border:0; width:100%; height:100%;" allowfullscreen="" loading="lazy"></iframe>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Panel Informasi --}}
                <div class="tab-pane fade" id="panel-info">
                    <h5 class="fw-bold mb-4">Update Informasi Terbaru</h5>

                    @forelse($event->event_updates as $update)
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mx-auto mb-4" style="max-width: 500px; border: 1px solid #efefef;">
                        <div class="p-3 d-flex align-items-center bg-white">
                            <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold me-2 shadow-sm" style="width: 38px; height: 38px; font-size: 14px; background: linear-gradient(45deg, #7c3aed 0%, #a855f7 100%);">
                                {{ substr($event->vendor->name ?? 'F', 0, 1) }}
                            </div>
                            <div>
                                <span class="fw-bold small d-block">{{ $event->vendor->name ?? 'FiveFest Official' }}</span>
                                <span class="text-muted" style="font-size: 10px;">{{ $update->created_at->diffForHumans() }}</span>
                            </div>
                        </div>

                        @if($update->images && count($update->images) > 0)
                            <div id="carouselUpdate-{{ $update->id }}" class="carousel slide" data-bs-ride="false">
                                <div class="carousel-indicators">
                                    @foreach($update->images as $idx => $img)
                                        <button type="button" data-bs-target="#carouselUpdate-{{ $update->id }}" data-bs-slide-to="{{ $idx }}" class="{{ $idx == 0 ? 'active' : '' }}"></button>
                                    @endforeach
                                </div>
                                <div class="carousel-inner">
                                    @foreach($update->images as $idx => $img)
                                        <div class="carousel-item {{ $idx == 0 ? 'active' : '' }}">
                                            <img src="{{ asset('storage/' . $img) }}" class="d-block w-100" alt="Update Image" style="min-height: 300px; max-height: 500px; object-fit: cover;">
                                        </div>
                                    @endforeach
                                </div>
                                @if(count($update->images) > 1)
                                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselUpdate-{{ $update->id }}" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#carouselUpdate-{{ $update->id }}" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    </button>
                                @endif
                            </div>
                        @endif

                        @if($update->video)
                            <div class="bg-dark">
                                <video class="w-100" controls style="max-height: 500px;">
                                    <source src="{{ asset('storage/' . $update->video) }}" type="video/mp4">
                                </video>
                            </div>
                        @endif

                        <div class="p-3 bg-white">
                            <div class="mb-1">
                                <span class="fw-bold small me-2">{{ $event->vendor->name ?? 'Official' }}</span>
                                <span class="small text-dark">{!! nl2br(e($update->caption)) !!}</span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle shadow-sm" style="width: 100px; height: 100px;">
                                <i class="bi bi-megaphone display-4 text-muted"></i>
                            </div>
                        </div>
                        <h6 class="fw-bold text-dark">Belum ada update</h6>
                        <p class="text-muted small">Vendor belum memberikan pengumuman tambahan untuk event ini.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Sidebar Sticky --}}
        <div class="col-lg-4">
            <div class="card sticky-card p-4 border-0 shadow-sm">
                <div class="card-body p-0">
                    @if(isset($isTenantView) && $isTenantView)
                        <p class="text-muted small mb-1 fw-bold text-uppercase"><i class="bi bi-shop me-1"></i> Biaya Sewa Stand</p>
                        <h2 class="fw-bold text-purple-magic mb-3">
                            Rp{{ number_format($event->tenant_booth_price ?? 0, 0, ',', '.') }}
                        </h2>
                        <div class="bg-light p-3 rounded-3 mb-4 small border">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Harga Sewa Dasar</span>
                                <strong>Rp{{ number_format($event->tenant_booth_price ?? 0, 0, ',', '.') }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Platform Fee (3%)</span>
                                <strong>Rp{{ number_format(($event->tenant_booth_price ?? 0) * 0.03, 0, ',', '.') }}</strong>
                            </div>
                            <hr class="my-2 border-dashed">
                            <div class="d-flex justify-content-between fw-bold text-dark mb-3">
                                <span>Estimasi Total</span>
                                <span>Rp{{ number_format(($event->tenant_booth_price ?? 0) * 1.03, 0, ',', '.') }}</span>
                            </div>

                            @if($event->tenant_quota)
                                @php
                                    $approvedCount = $event->tenants()->where('status', 'approved')->count();
                                    $remaining = max(0, $event->tenant_quota - $approvedCount);
                                @endphp
                                <div class="p-2 bg-white rounded border border-primary border-opacity-25 text-center">
                                    <span class="d-block small text-muted text-uppercase mb-1" style="font-size: 0.7rem;">Ketersediaan Slot Booth</span>
                                    <h6 class="mb-0 fw-bold" style="color: #6366f1;">Sisa {{ $remaining }} dari {{ $event->tenant_quota }} Slot</h6>
                                </div>
                            @endif
                        </div>
                    @else
                        <p class="text-muted small mb-1">Harga Mulai Dari</p>
                        <h2 class="fw-bold text-purple-magic mb-4">
                            Rp{{ number_format($event->ticket_categories->min('price'), 0, ',', '.') }}
                        </h2>
                    @endif

                    <hr class="my-4 border-dashed">

                    @auth
                        @if(!$isTenantView && $event->open_sale_at && \Carbon\Carbon::now()->lt(\Carbon\Carbon::parse($event->open_sale_at)))
                            <div class="countdown-box mb-4 shadow-sm">
                                <p class="small fw-bold mb-2"><i class="bi bi-lightning-fill me-1"></i> WAR STARTING IN:</p>
                                <div id="countdown-timer" class="countdown-timer">
                                    <div class="timer-part"><span class="timer-num" id="days">00</span><span class="timer-label">Hari</span></div>
                                    <div class="timer-part"><span class="timer-num" id="hours">00</span><span class="timer-label">Jam</span></div>
                                    <div class="timer-part"><span class="timer-num" id="minutes">00</span><span class="timer-label">Menit</span></div>
                                    <div class="timer-part"><span class="timer-num" id="seconds">00</span><span class="timer-label">Detik</span></div>
                                </div>
                            </div>
                            <button class="btn btn-secondary w-100 py-3 fw-bold rounded-pill" disabled>BELUM DIBUKA</button>
                        @else
                            @if($isTenantView)
                                @if(!$event->is_tenant_open)
                                    <div class="alert alert-light border small text-center mb-0 text-muted fw-bold">
                                        <i class="bi bi-info-circle me-1"></i> Pendaftaran Tenant Ditutup
                                    </div>
                                @elseif(auth()->user()->verification_status === 'pending')
                                    <button class="btn btn-secondary w-100 py-3 fw-bold rounded-pill shadow-sm" disabled>
                                        <i class="bi bi-shield-lock me-2"></i> Akun Belum Diverifikasi
                                    </button>
                                    <div class="form-text text-center mt-2 small">Menunggu Admin FiveFest.</div>
                                @else
                                    @php
                                        $existingJoin = \App\Models\EventTenant::where('event_id', $event->id)
                                                        ->where('tenant_id', auth()->id())
                                                        ->first();
                                    @endphp
                                    @if($existingJoin && $existingJoin->status != 'rejected')
                                        @if($existingJoin->status == 'pending')
                                            <button class="btn btn-warning w-100 py-3 fw-bold rounded-pill shadow-sm text-dark" disabled style="background-color: #fef08a; border-color: #fde047;">
                                                <i class="bi bi-hourglass-split me-2"></i> Pengajuan Diproses
                                            </button>
                                        @elseif($existingJoin->status == 'approved')
                                            @if($event->tenant_booth_price > 0 && $existingJoin->payment_status == 'pending')
                                                @if($existingJoin->snap_token)
                                                    <button type="button" class="btn btn-primary w-100 py-3 fw-bold rounded-pill shadow-lg" onclick="payBooth('{{ $existingJoin->snap_token }}')" style="background: linear-gradient(135deg, #a855f7 0%, #6366f1 100%); border: none;">
                                                        <i class="bi bi-wallet2 me-2"></i> Lanjutkan Pembayaran
                                                    </button>
                                                @else
                                                    <form action="{{ route('tenant.booths.pay', $existingJoin->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-pill shadow-lg" style="background: linear-gradient(135deg, #a855f7 0%, #6366f1 100%); border: none;">
                                                            <i class="bi bi-wallet2 me-2"></i> Bayar Sewa Booth
                                                        </button>
                                                    </form>
                                                @endif
                                            @elseif($event->tenant_booth_price == 0 || $existingJoin->payment_status == 'paid')
                                                <div class="alert alert-success border-success text-center mb-3 py-2 fw-bold small">
                                                    <i class="bi bi-check-circle-fill me-1"></i> Booth Lunas / Terkonfirmasi
                                                </div>
                                                <a href="{{ route('tenant.booths.index') }}" class="btn btn-success w-100 py-3 fw-bold rounded-pill shadow-lg" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none;">
                                                    <i class="bi bi-shop me-2"></i> Lihat Detail Booth
                                                </a>
                                            @endif
                                        @endif
                                    @else
                                        @if($existingJoin && $existingJoin->status == 'rejected')
                                            <div class="alert alert-danger text-center small py-2 mb-3 fw-bold">Pengajuan Sebelumnya Ditolak</div>
                                        @endif
                                        @php
                                            $approvedCount = $event->tenants()->where('status', 'approved')->count();
                                            $isQuotaFull = $event->tenant_quota && $approvedCount >= $event->tenant_quota;
                                        @endphp
                                        @if($isQuotaFull)
                                            <button class="btn btn-danger w-100 py-3 fw-bold rounded-pill shadow-sm" disabled>
                                                <i class="bi bi-exclamation-circle-fill me-2"></i> Kuota Tenant Penuh
                                            </button>
                                        @else
                                            <a href="{{ route('tenant.event.join.step-form', $event->id) }}" class="btn btn-primary w-100 py-3 fw-bold rounded-pill shadow-lg" style="background: linear-gradient(135deg, #a855f7 0%, #7c3aed 100%); border: none;">
                                                DAFTAR BUKA STAND
                                            </a>
                                        @endif
                                    @endif
                                @endif
                            @else
                                <a href="{{ route('booking.create', $event->id) }}" class="btn btn-ff-primary w-100 py-3 fw-bold rounded-pill shadow-lg">
                                    BELI TIKET SEKARANG
                                </a>
                            @endif
                        @endif
                    @else
                        @if($event->open_sale_at && \Carbon\Carbon::now()->lt(\Carbon\Carbon::parse($event->open_sale_at)))
                            <div class="countdown-box mb-4 shadow-sm">
                                <p class="small fw-bold mb-2"><i class="bi bi-lightning-fill me-1"></i> WAR STARTING IN:</p>
                                <div id="countdown-timer-guest" class="countdown-timer">
                                    <div class="timer-part"><span class="timer-num" id="days-g">00</span><span class="timer-label">Hari</span></div>
                                    <div class="timer-part"><span class="timer-num" id="hours-g">00</span><span class="timer-label">Jam</span></div>
                                    <div class="timer-part"><span class="timer-num" id="minutes-g">00</span><span class="timer-label">Menit</span></div>
                                    <div class="timer-part"><span class="timer-num" id="seconds-g">00</span><span class="timer-label">Detik</span></div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-light border small text-center mb-3">
                                Login dulu yuk untuk pesan tiket!
                            </div>
                        @endif
                        <a href="{{ route('login') }}" class="btn btn-dark w-100 py-3 fw-bold rounded-pill">
                            MASUK / DAFTAR
                        </a>
                    @endauth

                    <div class="mt-4 d-flex align-items-center justify-content-center gap-2 text-muted">
                        <i class="bi bi-shield-lock fs-4"></i>
                        <span style="font-size: 0.7rem;">Transaksi aman & terenkripsi.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- ===== MOBILE STICKY BOTTOM BAR ===== --}}
<div class="mobile-sticky-bar" style="
    display: none;
    position: fixed;
    bottom: 0; left: 0; right: 0;
    background: white;
    border-top: 1px solid #e2e8f0;
    padding: 12px 16px;
    z-index: 9999;
    align-items: center;
    gap: 12px;
    box-shadow: 0 -4px 20px rgba(0,0,0,0.08);
">
    <div>
        <div class="text-muted" style="font-size:0.65rem;">
            @if(isset($isTenantView) && $isTenantView) Biaya Sewa Stand @else Harga mulai dari @endif
        </div>
        <div class="fw-bold" style="font-size:1rem; color:#7c3aed;">
            @if(isset($isTenantView) && $isTenantView)
                Rp{{ number_format($event->tenant_booth_price ?? 0, 0, ',', '.') }}
            @else
                Rp{{ number_format($event->ticket_categories->min('price'), 0, ',', '.') }}
            @endif
        </div>
    </div>
    <div class="flex-grow-1">
        @auth
            @if(isset($isTenantView) && $isTenantView)
                @if(!$event->is_tenant_open)
                    <button class="btn w-100 fw-bold rounded-pill py-2 btn-secondary" disabled>
                        Pendaftaran Ditutup
                    </button>
                @elseif(auth()->user()->verification_status === 'pending')
                    <button class="btn w-100 fw-bold rounded-pill py-2 btn-secondary" disabled>
                        <i class="bi bi-shield-lock me-1"></i> Belum Diverifikasi
                    </button>
                @else
                    @php
                        $existingJoinMobile = \App\Models\EventTenant::where('event_id', $event->id)
                                            ->where('tenant_id', auth()->id())
                                            ->first();
                        $approvedCountMobile = $event->tenants()->where('status', 'approved')->count();
                        $isQuotaFullMobile = $event->tenant_quota && $approvedCountMobile >= $event->tenant_quota;
                    @endphp

                    @if($existingJoinMobile && $existingJoinMobile->status == 'pending')
                        <button class="btn w-100 fw-bold rounded-pill py-2" disabled
                            style="background-color: #fef08a; border-color: #fde047; color: #713f12;">
                            <i class="bi bi-hourglass-split me-1"></i> Pengajuan Diproses
                        </button>

                    @elseif($existingJoinMobile && $existingJoinMobile->status == 'approved')
                        @if($event->tenant_booth_price > 0 && $existingJoinMobile->payment_status == 'pending')
                            @if($existingJoinMobile->snap_token)
                                <button type="button" class="btn w-100 fw-bold rounded-pill py-2"
                                    onclick="payBooth('{{ $existingJoinMobile->snap_token }}')"
                                    style="background: linear-gradient(135deg, #a855f7 0%, #6366f1 100%); color:white; border:none;">
                                    <i class="bi bi-wallet2 me-1"></i> Lanjutkan Pembayaran
                                </button>
                            @else
                                <form action="{{ route('tenant.booths.pay', $existingJoinMobile->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn w-100 fw-bold rounded-pill py-2"
                                        style="background: linear-gradient(135deg, #a855f7 0%, #6366f1 100%); color:white; border:none;">
                                        <i class="bi bi-wallet2 me-1"></i> Bayar Sewa Booth
                                    </button>
                                </form>
                            @endif
                        @elseif($event->tenant_booth_price == 0 || $existingJoinMobile->payment_status == 'paid')
                            <a href="{{ route('tenant.booths.index') }}" class="btn w-100 fw-bold rounded-pill py-2"
                                style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color:white; border:none;">
                                <i class="bi bi-shop me-1"></i> Lihat Detail Booth
                            </a>
                        @endif

                    @elseif($existingJoinMobile && $existingJoinMobile->status == 'rejected')
                        @if($isQuotaFullMobile)
                            <button class="btn btn-danger w-100 fw-bold rounded-pill py-2" disabled>
                                <i class="bi bi-exclamation-circle-fill me-1"></i> Kuota Penuh
                            </button>
                        @else
                            <a href="{{ route('tenant.event.join.step-form', $event->id) }}"
                               class="btn w-100 fw-bold rounded-pill py-2"
                               style="background: linear-gradient(135deg, #a855f7 0%, #7c3aed 100%); color:white; border:none;">
                                DAFTAR ULANG
                            </a>
                        @endif

                    @else
                        @if($isQuotaFullMobile)
                            <button class="btn btn-danger w-100 fw-bold rounded-pill py-2" disabled>
                                <i class="bi bi-exclamation-circle-fill me-1"></i> Kuota Tenant Penuh
                            </button>
                        @else
                            <a href="{{ route('tenant.event.join.step-form', $event->id) }}"
                               class="btn w-100 fw-bold rounded-pill py-2"
                               style="background: linear-gradient(135deg, #a855f7 0%, #7c3aed 100%); color:white; border:none;">
                                DAFTAR BUKA STAND
                            </a>
                        @endif
                    @endif
                @endif
            @else
                <a href="{{ route('booking.create', $event->id) }}"
                   class="btn w-100 fw-bold rounded-pill py-2"
                   style="background: linear-gradient(135deg, #a855f7 0%, #7c3aed 100%); color:white; border:none;">
                    Lihat Tiket
                </a>
            @endif
        @else
            <a href="{{ route('login') }}"
               class="btn btn-dark w-100 fw-bold rounded-pill py-2">
                MASUK / DAFTAR
            </a>
        @endauth
    </div>
</div>


{{-- Modal Seatplan --}}
@if($event->seat_plan || $event->seatplan)
<div class="modal fade" id="modalSeatplan" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-0">
                <h6 class="modal-title fw-bold">Denah Venue - {{ $event->title }}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0 bg-dark text-center">
                @php $seatPlanImg = $event->seat_plan ?? $event->seatplan; @endphp
                <img src="{{ asset('storage/' . $seatPlanImg) }}" class="img-fluid" alt="Seatplan" style="max-height: 85vh;">
            </div>
        </div>
    </div>
</div>
@endif

<script>
@if($event->open_sale_at && \Carbon\Carbon::now()->lt(\Carbon\Carbon::parse($event->open_sale_at)))
    const countDownDate = new Date("{{ \Carbon\Carbon::parse($event->open_sale_at)->format('Y-m-d H:i:s') }}").getTime();
    const x = setInterval(function() {
        const now = new Date().getTime();
        const distance = countDownDate - now;
        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
        const elements = [
            { d: 'days', h: 'hours', m: 'minutes', s: 'seconds' },
            { d: 'days-g', h: 'hours-g', m: 'minutes-g', s: 'seconds-g' }
        ];
        elements.forEach(el => {
            if(document.getElementById(el.d)) document.getElementById(el.d).innerHTML = days.toString().padStart(2, '0');
            if(document.getElementById(el.h)) document.getElementById(el.h).innerHTML = hours.toString().padStart(2, '0');
            if(document.getElementById(el.m)) document.getElementById(el.m).innerHTML = minutes.toString().padStart(2, '0');
            if(document.getElementById(el.s)) document.getElementById(el.s).innerHTML = seconds.toString().padStart(2, '0');
        });
        if (distance < 0) { clearInterval(x); location.reload(); }
    }, 1000);
@endif

@if($event->last_update_at && \Carbon\Carbon::parse($event->last_update_at)->gt(now()->subHours(24)))
    document.addEventListener('DOMContentLoaded', function() {
        const hasSeenUpdate = localStorage.getItem('seen_update_{{ $event->id }}_{{ $event->last_update_at }}');
        if (!hasSeenUpdate) {
            Swal.fire({
                title: 'Update Info Baru!',
                text: 'Vendor baru saja memperbarui informasi operasional event ini. Cek di tab Informasi ya!',
                icon: 'info', toast: true, position: 'top-end',
                showConfirmButton: false, timer: 5000, timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });
            localStorage.setItem('seen_update_{{ $event->id }}_{{ $event->last_update_at }}', 'true');
        }
    });
@endif
</script>

@if(isset($isTenantView) && $isTenantView && isset($existingJoin) && $existingJoin && $existingJoin->status == 'approved' && $existingJoin->payment_status == 'pending')
<script src="https://app.{{ config('midtrans.is_production') ? '' : 'sandbox.' }}midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    function payBooth(snapToken) {
        window.snap.pay(snapToken, {
            onSuccess: function(result){ window.location.href = "{{ route('tenant.booths.index') }}?order_id=" + result.order_id + "&transaction_status=" + result.transaction_status; },
            onPending: function(result){ window.location.reload(); },
            onError: function(result){ Swal.fire('Gagal!', 'Terjadi kesalahan saat memproses pembayaran.', 'error'); },
            onClose: function(){}
        });
    }
</script>
@endif

@if(!empty($spotifyTracks) && isset($spotifyTracks[0]['embed_id']))
<script>
let spPlaying = false;
let spWrap    = null;

document.addEventListener('DOMContentLoaded', function () {
    spWrap = document.createElement('div');
    spWrap.id = 'sp-custom-bar';
    Object.assign(spWrap.style, {
        display      : 'none',
        position     : 'fixed',
        bottom       : '0',
        left         : '0',
        right        : '0',
        zIndex       : '99998',
        background   : '#000',
        pointerEvents: 'all',
    });

    const iframeWrap = document.createElement('div');
    iframeWrap.id = 'sp-iframe-wrap';
    spWrap.appendChild(iframeWrap);
    document.body.appendChild(spWrap);

    // Tombol X mobile: elemen fixed TERPISAH di body, bukan di dalam spWrap
    const xBtn = document.createElement('button');
    xBtn.id = 'sp-mobile-close';
    xBtn.innerHTML = '<i class="bi bi-x-lg"></i>';
    xBtn.onclick = closeSpotify;
    Object.assign(xBtn.style, {
        display       : 'none',
        position      : 'fixed',
        zIndex        : '99995',
        width         : '32px',
        height        : '32px',
        borderRadius  : '50%',
        background    : 'linear-gradient(135deg, #7c3aed, #a855f7)',
        color         : '#fff',
        border        : 'none',
        cursor        : 'pointer',
        fontSize      : '12px',
        alignItems    : 'center',
        justifyContent: 'center',
        boxShadow     : '0 2px 8px rgba(124,58,237,0.4)',
        right         : '12px',
    });
    document.body.appendChild(xBtn);
});

function openSpotify() {
    spWrap.style.display = 'block';
    setTimeout(function() {
        const barHeight = spWrap.offsetHeight || 152;
        const fab       = document.getElementById('sp-toggle');
        const icon      = document.getElementById('sp-toggle-icon');
        const topBar    = document.getElementById('sp-top-bar'); 
        const isMobile  = window.innerWidth <= 768;
        if (isMobile) {
            if (fab) fab.style.display = 'none';
            const xBtn = document.getElementById('sp-mobile-close');
            if (xBtn) {
                xBtn.style.display = 'flex';
                xBtn.style.bottom = (barHeight + 8) + 'px';
            }
        } else {
            if (fab) {
                fab.style.bottom    = (barHeight + 16) + 'px';
                fab.style.animation = 'none';
            }
            if (icon) icon.className = 'bi bi-x-lg';
        }
        document.body.style.paddingBottom = (barHeight + 20) + 'px';
        const iframeWrap = document.getElementById('sp-iframe-wrap');
        if (!iframeWrap.querySelector('iframe')) {
            iframeWrap.innerHTML = `
                <iframe 
                    src="https://open.spotify.com/embed/{{ $embedType }}/{{ $embedId }}?utm_source=generator&theme=0&autoplay=1" 
                    width="100%" height="152" frameborder="0"
                    allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"
                    allowfullscreen
                    style="display:block;">
                </iframe>`;
        }
    }, 50);
    spPlaying = true;
}

function closeSpotify() {
    if (spWrap) spWrap.style.display = 'none';


    document.body.style.paddingBottom = '';

    const fab  = document.getElementById('sp-toggle');
    const icon = document.getElementById('sp-toggle-icon');
    const xBtn = document.getElementById('sp-mobile-close');

    if (xBtn) xBtn.style.display = 'none';
    if (fab) fab.style.cssText = '';
    if (icon) {
        icon.className      = 'bi bi-music-note-beamed';
        icon.style.fontSize = '';
    }

    spPlaying = false;
}

function toggleSpotify() {
    spPlaying ? closeSpotify() : openSpotify();
}
</script>
@endif


@endsection