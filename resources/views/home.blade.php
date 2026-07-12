@extends('v_layouts.app')
@section('title', 'FiveFest - Digital Concert Experience')

@section('content')
@php use Illuminate\Support\Str; @endphp

{{-- ============================================================
     MOBILE SEARCH BAR 
     ============================================================ --}}
<form action="{{ route('search') }}" method="GET"
      class="mobile-search-bar d-flex align-items-center gap-2"
      style="cursor:text;">
    <button type="submit" style="background:none;border:none;padding:0;flex-shrink:0;cursor:pointer;">
        <i class="bi bi-search" style="color:#94a3b8;font-size:0.95rem;"></i>
    </button>    
<input type="text" name="query" 
           placeholder="Cari event, artis, atau venue..."
           autocomplete="off"
           style="border:none;background:transparent;outline:none;width:100%;cursor:text;-webkit-user-select:text;user-select:text;pointer-events:auto;">
</form>

{{-- ============================================================
     HERO CAROUSEL
     ============================================================ --}}
<div class="container">
    <div class="hero-wrapper fade-in-up-view active">
        <div id="fivefestHero" class="carousel slide" data-bs-ride="carousel" data-bs-interval="8000">

            <div class="carousel-indicators">
                <button type="button" data-bs-target="#fivefestHero" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#fivefestHero" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#fivefestHero" data-bs-slide-to="2"></button>
                @guest
                <button type="button" data-bs-target="#fivefestHero" data-bs-slide-to="3"></button>
                @endguest
            </div>

            <div class="carousel-inner text-start">

                {{-- Slide 1 --}}
                <div class="carousel-item active">
                    <img src="{{ asset('storage/images/slider/fivefest1.jpg') }}" class="d-block w-100 hero-img-box" alt="Main Festival">
                    <div class="hero-overlay-dark"></div>
                    <div class="hero-caption-box">
                        <span class="badge bg-purple-deep px-3 py-2 rounded-pill mb-4">MEMBER EXCLUSIVE</span>
                        <h1>Panggung <br><span class="text-warning">Terbesar</span> Menantimu.</h1>
                        <p>Nikmati sistem pemesanan tiket termudah dengan akses baris depan untuk semua artis papan atas dunia di FiveFest.</p>
                        <a href="#upcoming-concerts" class="btn btn-warning btn-lg rounded-pill px-5 fw-bold">Dapatkan Tiket</a>
                    </div>
                </div>

                {{-- Slide 2 --}}
                <div class="carousel-item">
                    <img src="{{ asset('storage/images/slider/fivefest2.jpg') }}" class="d-block w-100 hero-img-box" alt="International Artist">
                    <div class="hero-overlay-dark"></div>
                    <div class="hero-caption-box">
                        <span class="badge bg-danger px-3 py-2 rounded-pill mb-4">TRENDING NOW</span>
                        <h1>Sensasi <br><span class="text-warning">K-Pop</span> Global.</h1>
                        <p>Tur dunia yang paling dinanti hadir di Indonesia. Pastikan kamu menjadi bagian dari sejarah musik malam ini.</p>
                        <a href="#upcoming-concerts" class="btn btn-ff-primary btn-lg rounded-pill px-5 fw-bold">Cek Jadwal</a>
                    </div>
                </div>

                {{-- Slide 3 --}}
                <div class="carousel-item">
                    <img src="{{ asset('storage/images/slider/fivefest3.jpg') }}" class="d-block w-100 hero-img-box" alt="Orchestra Session">
                    <div class="hero-overlay-dark"></div>
                    <div class="hero-caption-box">
                        <span class="badge bg-info px-3 py-2 rounded-pill mb-4">NIGHT</span>
                        <h1>Alunan yang <br><span class="text-warning">Megah.</span></h1>
                        <p>Hadirkan ketenangan dalam setiap melodi. Dapatkan diskon presale khusus kategori minggu ini.</p>
                        <a href="#upcoming-concerts" class="btn btn-warning btn-lg rounded-pill px-5 fw-bold">Booking Spot</a>
                    </div>
                </div>

                {{-- Slide 4 — Tenant (hanya untuk guest) --}}
                @guest
                <div class="carousel-item">
                    <img src="{{ asset('storage/images/slider/side1.jpeg') }}" class="d-block w-100 hero-img-box" alt="Tenant Registration"
                         onerror="this.src='{{ asset('storage/images/slider/fivefest2.jpg') }}'">
                    <div class="hero-overlay-dark"></div>
                    <div class="hero-caption-box">
                        <span class="badge bg-primary px-3 py-2 rounded-pill mb-4">FOR TENANTS</span>
                        <h1>Buka Stand & <br><span class="text-warning">Berjualan di Sini.</span></h1>
                        <p>Dapatkan peluang emas untuk berjualan di festival terbesar tahun ini. Daftar sebagai tenant sekarang.</p>
                        <a href="{{ route('register.tenant.show') }}" class="btn btn-ff-primary btn-lg rounded-pill px-5 fw-bold text-white shadow">Daftar Jadi Tenant</a>
                    </div>
                </div>
                @endguest

            </div>
        </div>
    </div>
</div>

{{-- ============================================================
     MODE INDICATOR (Admin / Vendor only)
     ============================================================ --}}
@auth
    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'vendor' || auth()->user()->role === 'owner')
    <div class="mode-indicator mt-4">
        <div class="container d-flex justify-content-between align-items-center bg-white p-3 rounded-4 shadow-sm border">
            <div class="d-flex align-items-center">
                <div class="bg-purple-deep text-white rounded-3 p-2 me-3">
                    <i class="bi bi-person-badge"></i>
                </div>
                <div>
                    <span class="d-block fw-bold small">DASHBOARD AKSES</span>
                    <small class="text-muted">Halo, {{ auth()->user()->name }}. Kamu masuk sebagai {{ auth()->user()->role }}.</small>
                </div>
            </div>
            <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('vendor.dashboard') }}"
               class="btn btn-dark rounded-pill px-4 fw-bold shadow-sm">Buka Kontrol</a>
        </div>
    </div>
    @endif
@endauth

{{-- ============================================================
     KONSER MENDATANG
     ============================================================ --}}
<section class="py-5" id="upcoming-concerts">
    <div class="container">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-5" data-aos="fade-up">
            <div class="header-content">
                <h3 class="mb-0"
                    style="font-size:3rem;font-weight:900;letter-spacing:-1px;color:#1e1b4b;">
                    Konser <span style="background:linear-gradient(45deg,#8b5cf6,#d946ef);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">Mendatang</span>
                </h3>
                <div class="mt-2" style="width:60px;height:6px;background:#8b5cf6;border-radius:50px;"></div>
                <p class="d-md-none text-muted small mt-1 mb-0">
                    <i class="bi bi-arrow-right me-1"></i> Geser untuk lihat semua
                </p>
            </div>
            <a href="{{ route('search') }}"
               class="d-inline-block text-decoration-none"
               style="color:#1e293b;font-weight:700;padding:12px 30px;border:2px solid #f1f5f9;border-radius:100px;transition:all 0.3s ease;"
               onmouseover="this.style.borderColor='#8b5cf6';this.style.color='#8b5cf6';this.style.transform='translateX(5px)'"
               onmouseout="this.style.borderColor='#f1f5f9';this.style.color='#1e293b';this.style.transform='translateX(0)'">
                Explore All <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>

        {{-- Grid Cards --}}
        <div class="row g-4 mb-5">
            @forelse($events as $event)
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 150 }}">
                <div class="card h-100 border-0 shadow-sm concert-card-hover"
                     style="border-radius:35px;overflow:hidden;transition:all 0.3s ease;background:#fff;">

                    {{-- Gambar + Badge --}}
                    <div class="position-relative card-img-mobile" style="height:250px;overflow:hidden;">
                        <img src="{{ asset('storage/' . $event->image) }}"
                        class="w-100"
                        style="object-fit:cover;width:100%;height:250px;display:block;"
                        alt="{{ $event->title }}"
                        onerror="this.style.background='#e2e8f0';this.src='data:image/svg+xml,%3Csvg xmlns=\'http: //www.w3.org/2000/svg\' width=\'100\' height=\'100\'%3E%3C/svg%3E'">
                        <div class="position-absolute top-0 start-0 m-3 px-4 py-2 text-white fw-bold shadow-sm"
                             style="background:#5b21b6;border-radius:50px;font-size:0.75rem;z-index:5;letter-spacing:1px;">
                            {{ strtoupper($event->category) }}
                        </div>
                    </div>

                    {{-- Body --}}
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-3 text-dark" style="font-size:1.3rem;line-height:1.3;min-height:3.4rem;">
                            {{ Str::limit($event->title, 45) }}
                        </h4>
                        <div class="mb-2 small text-muted d-flex align-items-center">
                            <i class="bi bi-calendar3 me-3" style="color:#8b5cf6;"></i>
                            {{ $event->date->format('d M Y') }}
                        </div>
                        <div class="mb-4 small text-muted d-flex align-items-center">
                            <i class="bi bi-geo-alt-fill text-danger me-3"></i>
                            {{ Str::limit($event->venue, 30) }}
                        </div>

                        {{-- Footer Card --}}
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top border-light">
                            <div>
                                <small class="text-muted d-block" style="font-size:0.65rem;text-transform:uppercase;">Harga Mulai</small>
                                <span class="fw-bold fs-4" style="color:#8b5cf6;">
                                    Rp{{ number_format($event->ticket_categories->min('price') ?? 0, 0, ',', '.') }}
                                </span>
                            </div>

                            @if(auth()->check() && auth()->user()->role === 'tenant')
                                @if(!$event->is_tenant_open)
                                    <button class="btn px-3 py-2 rounded-pill fw-bold text-white shadow-sm"
                                            style="background:#94a3b8;border:none;cursor:not-allowed;font-size:0.8rem;" disabled>
                                        TIDAK BUKA
                                    </button>
                                @elseif(auth()->user()->verification_status === 'pending')
                                    <button class="btn px-4 py-2 rounded-pill fw-bold text-white shadow-sm"
                                            style="background:#6c757d;border:none;cursor:not-allowed;" disabled>
                                        PENDING
                                    </button>
                                @else
                                    @php
                                        $hasJoined = \App\Models\EventTenant::where('event_id',$event->id)->where('tenant_id',auth()->id())->exists();
                                        $tenantRoute = $hasJoined ? route('event.detail',$event->id) : route('tenant.event.join',$event->id);
                                    @endphp
                                    <div class="text-end">
                                        <a href="{{ $tenantRoute }}"
                                           class="btn px-4 py-2 rounded-pill fw-bold text-white shadow-sm mb-1"
                                           style="background:linear-gradient(135deg,#a855f7 0%,#6366f1 100%);border:none;">
                                            OPEN TENANT
                                        </a>
                                        @if($event->tenant_quota)
                                            @php
                                                $approvedCount = $event->tenants()->where('status','approved')->count();
                                                $remaining = max(0,$event->tenant_quota - $approvedCount);
                                            @endphp
                                            <div class="small mt-1" style="color:#6366f1;font-size:0.75rem;">
                                                Sisa: {{ $remaining }} / {{ $event->tenant_quota }} Slot
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            @else
                                <a href="{{ route('event.detail', $event->id) }}"
                                   class="btn px-4 py-2 rounded-pill fw-bold text-white shadow-sm"
                                   style="background:linear-gradient(135deg,#a855f7 0%,#6366f1 100%);border:none;transition:0.3s;"
                                   onmouseover="this.style.background='linear-gradient(135deg,#9333ea 0%,#4f46e5 100%)';this.style.transform='scale(1.05)'"
                                   onmouseout="this.style.background='linear-gradient(135deg,#a855f7 0%,#6366f1 100%)';this.style.transform='scale(1)'">
                                    Lihat Detail
                                </a>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-search-heart display-1 text-muted opacity-25"></i>
                <p class="mt-4 fs-5 text-muted">Belum ada konser terjadwal.</p>
            </div>
            @endforelse
        </div>

    </div>
</section>

{{-- ============================================================
     TRENDING NOW
     ============================================================ --}}
<section class="trending-wrap">

    {{-- Header --}}
    <div class="trending-header d-flex align-items-center justify-content-between mb-4" style="padding:0 40px;">
        <div>
            <h2 style="color:#ffffff;font-weight:900;font-size:2.2rem;letter-spacing:-1px;margin-bottom:8px;">
                Trending <span style="background:linear-gradient(45deg,#a855f7,#d946ef);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">Now</span>
            </h2>
            <p style="color:rgba(255,255,255,0.6);font-size:0.95rem;display:flex;align-items:center;">
                <span style="width:8px;height:8px;background:#a855f7;border-radius:50%;display:inline-block;margin-right:12px;box-shadow:0 0 15px #a855f7;"></span>
                Konser paling hits minggu ini. Jangan sampai kehabisan!
            </p>
        </div>
        <div class="d-none d-lg-block">
            <div style="display:inline-block;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);padding:8px 20px;border-radius:100px;color:#ffffff;font-weight:800;font-size:0.65rem;letter-spacing:1.5px;">
                TOP CHARTS FIVEFEST
            </div>
        </div>
    </div>

    {{-- Cards scroll --}}
    <div class="trending-cards-row">
        @forelse($trending as $index => $event)
        <div class="trending-card-col" data-aos="fade-up" data-aos-delay="{{ $loop->index * 150 }}">
            <div class="trending-card-container">
                <h1 class="top-rank-number">{{ $index + 1 }}</h1>
                <a href="{{ route('event.detail', $event->id) }}" class="top-concert-card" title="{{ $event->title }}">
                    <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}">
                </a>
            </div>
        </div>
        @empty
        <div style="color:rgba(255,255,255,0.5);padding:20px 40px;">Belum ada event trending saat ini.</div>
        @endforelse
    </div>
</section>

{{-- ============================================================
     GENRE DISCOVERY
     ============================================================ --}}
<section class="py-5 genre-clean-section">
    <div class="container py-4">
        <div class="text-center mb-5" data-aos="fade-up">
            <h4 class="fw-black mb-2 text-dark display-6 tracking-tight">
                Eksplor Berdasarkan <span class="text-purple-accent">Genre</span>
            </h4>
            <p class="text-muted">Pilih jenis musik favoritmu dan temukan keseruannya.</p>
        </div>

        <div class="row g-4 row-cols-2 row-cols-md-5 justify-content-center mx-auto">

            <div class="col" data-aos="fade-up" data-aos-delay="100">
                <a href="{{ route('search', ['category' => 'k-pop']) }}" class="genre-link">
                    <div class="genre-card-clean">
                        <div class="genre-icon-wrapper kpop-bg"><i class="bi bi-star-fill"></i></div>
                        <h6 class="genre-title-modern">K-Pop</h6>
                        <div class="genre-hover-dot"></div>
                    </div>
                </a>
            </div>

            <div class="col" data-aos="fade-up" data-aos-delay="200">
                <a href="{{ route('search', ['category' => 'festival']) }}" class="genre-link">
                    <div class="genre-card-clean">
                        <div class="genre-icon-wrapper pop-bg"><i class="bi bi-fire"></i></div>
                        <h6 class="genre-title-modern">Festival</h6>
                        <div class="genre-hover-dot"></div>
                    </div>
                </a>
            </div>

            <div class="col" data-aos="fade-up" data-aos-delay="300">
                <a href="{{ route('search', ['category' => 'indie']) }}" class="genre-link">
                    <div class="genre-card-clean">
                        <div class="genre-icon-wrapper indie-bg"><i class="bi bi-flower1"></i></div>
                        <h6 class="genre-title-modern">Indie</h6>
                        <div class="genre-hover-dot"></div>
                    </div>
                </a>
            </div>

            <div class="col" data-aos="fade-up" data-aos-delay="400">
                <a href="{{ route('search', ['category' => 'orchestra']) }}" class="genre-link">
                    <div class="genre-card-clean">
                        <div class="genre-icon-wrapper jazz-bg"><i class="bi bi-music-player"></i></div>
                        <h6 class="genre-title-modern">Orchestra</h6>
                        <div class="genre-hover-dot"></div>
                    </div>
                </a>
            </div>

            <div class="col" data-aos="fade-up" data-aos-delay="500">
                <a href="{{ route('search', ['category' => 'pop']) }}" class="genre-link">
                    <div class="genre-card-clean">
                        <div class="genre-icon-wrapper rock-bg"><i class="bi bi-music-note"></i></div>
                        <h6 class="genre-title-modern">Pop</h6>
                        <div class="genre-hover-dot"></div>
                    </div>
                </a>
            </div>

        </div>
    </div>
</section>

{{-- ============================================================
     PARTNERS MARQUEE
     ============================================================ --}}
<section class="py-5 bg-white border-top border-bottom overflow-hidden">
    <div class="container py-4">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="fw-bold" style="color:#1e1b4b;font-size:1.8rem;">
                Mereka sudah sukses bikin event keren di <span class="text-purple-accent">FiveFest</span>
            </h2>
        </div>
        <div class="marquee-container">
            <div class="marquee-content">
                <div class="marquee-item"><img src="{{ asset('storage/images/logo/logo mecima.png') }}" alt="Mecima Pro"></div>
                <div class="marquee-item"><img src="{{ asset('storage/images/logo/logo ime.png') }}" alt="Ime Indonesia"></div>
                <div class="marquee-item"><img src="{{ asset('storage/images/logo/logo dyandra.png') }}" alt="Dyandra Global"></div>
                <div class="marquee-item"><img src="{{ asset('storage/images/logo/logo pk.png') }}" alt="PK Entertainment"></div>
                <div class="marquee-item"><img src="{{ asset('storage/images/logo/logo ck.png') }}" alt="CK Star"></div>
                <div class="marquee-item"><img src="{{ asset('storage/images/logo/logo groovy.jpg') }}" alt="Groove" style="height:50px;"></div>
                {{-- Duplicate for seamless loop --}}
                <div class="marquee-item"><img src="{{ asset('storage/images/logo/logo mecima.png') }}" alt="Mecima Pro"></div>
                <div class="marquee-item"><img src="{{ asset('storage/images/logo/logo ime.png') }}" alt="Ime Indonesia"></div>
                <div class="marquee-item"><img src="{{ asset('storage/images/logo/logo dyandra.png') }}" alt="Dyandra Global"></div>
                <div class="marquee-item"><img src="{{ asset('storage/images/logo/logo pk.png') }}" alt="PK Entertainment"></div>
                <div class="marquee-item"><img src="{{ asset('storage/images/logo/logo ck.png') }}" alt="CK Star"></div>
                <div class="marquee-item"><img src="{{ asset('storage/images/logo/logo groovy.jpg') }}" alt="Groove" style="height:50px;"></div>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================
     TESTIMONIALS
     ============================================================ --}}
<section class="testimonial-cta-wrap position-relative overflow-hidden">
    <div class="testi-circle-1"></div>
    <div class="testi-circle-2"></div>

    <div class="container position-relative z-10 py-4">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="text-white fw-bold display-5 mb-2">Apa Kata Sahabat FiveFest?</h2>
            <p class="text-white-50">Cerita seru dari mereka yang sudah nge-war tiket di FiveFest.</p>
        </div>

        {{-- Wrapper overflow hidden --}}
        <div style="overflow:hidden; padding:20px 0; margin:0 -12px;">
            <div class="testi-scroll-inner">

                {{-- Card 1 --}}
                <div style="flex:0 0 380px; padding:0 12px;">
                    <div class="testi-card-cta">
                        <div class="rating-stars mb-3">
                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        </div>
                        <p class="testi-text-white">"War tiket Tulus lancar banget di sini. E-tiket langsung masuk sedetik setelah bayar lewat QRIS!"</p>
                        <div class="testi-user d-flex align-items-center">
                            <div class="avatar-highlight shadow-sm">JS</div>
                            <div class="ms-3">
                                <h6 class="text-white fw-bold mb-0">John Smith</h6>
                                <small class="text-white-50">Festival Goer</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card 2 --}}
                <div style="flex:0 0 380px; padding:0 12px;">
                    <div class="testi-card-cta">
                        <div class="rating-stars mb-3">
                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        </div>
                        <p class="testi-text-white">"Suka banget sama tampilannya yang modern. Gak bingung pas pilih seatplan konser K-Pop kemarin."</p>
                        <div class="testi-user d-flex align-items-center">
                            <div class="avatar-highlight shadow-sm">SW</div>
                            <div class="ms-3">
                                <h6 class="text-white fw-bold mb-0">Sarah Wijaya</h6>
                                <small class="text-white-50">K-Popers Karawang</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card 3 --}}
                <div style="flex:0 0 380px; padding:0 12px;">
                    <div class="testi-card-cta">
                        <div class="rating-stars mb-3">
                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        </div>
                        <p class="testi-text-white">"Sistem refund-nya beneran diproses kalau ada kendala acara. Adminnya fast respon banget!"</p>
                        <div class="testi-user d-flex align-items-center">
                            <div class="avatar-highlight shadow-sm">BS</div>
                            <div class="ms-3">
                                <h6 class="text-white fw-bold mb-0">Budi Santoso</h6>
                                <small class="text-white-50">Penikmat Jazz</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- DUPLIKAT untuk seamless loop --}}
                <div style="flex:0 0 380px; padding:0 12px;">
                    <div class="testi-card-cta">
                        <div class="rating-stars mb-3">
                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        </div>
                        <p class="testi-text-white">"War tiket Tulus lancar banget di sini. E-tiket langsung masuk sedetik setelah bayar lewat QRIS!"</p>
                        <div class="testi-user d-flex align-items-center">
                            <div class="avatar-highlight shadow-sm">JS</div>
                            <div class="ms-3">
                                <h6 class="text-white fw-bold mb-0">John Smith</h6>
                                <small class="text-white-50">Festival Goer</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="flex:0 0 380px; padding:0 12px;">
                    <div class="testi-card-cta">
                        <div class="rating-stars mb-3">
                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        </div>
                        <p class="testi-text-white">"Suka banget sama tampilannya yang modern. Gak bingung pas pilih seatplan konser K-Pop kemarin."</p>
                        <div class="testi-user d-flex align-items-center">
                            <div class="avatar-highlight shadow-sm">SW</div>
                            <div class="ms-3">
                                <h6 class="text-white fw-bold mb-0">Sarah Wijaya</h6>
                                <small class="text-white-50">K-Popers Karawang</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="flex:0 0 380px; padding:0 12px;">
                    <div class="testi-card-cta">
                        <div class="rating-stars mb-3">
                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        </div>
                        <p class="testi-text-white">"Sistem refund-nya beneran diproses kalau ada kendala acara. Adminnya fast respon banget!"</p>
                        <div class="testi-user d-flex align-items-center">
                            <div class="avatar-highlight shadow-sm">BS</div>
                            <div class="ms-3">
                                <h6 class="text-white fw-bold mb-0">Budi Santoso</h6>
                                <small class="text-white-50">Penikmat Jazz</small>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    // Intersection observer untuk animasi hero
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) entry.target.classList.add('active');
        });
    }, { threshold: 0.1 });
    document.querySelectorAll('.fade-in-up-view').forEach(el => observer.observe(el));
</script>
@endpush

@endsection