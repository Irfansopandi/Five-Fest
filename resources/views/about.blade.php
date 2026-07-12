@extends('v_layouts.app')

@section('title', 'Tentang Kami - FiveFest')

@section('content')
<style>
    /* 1. Modern Hero Section */
    .about-hero {
        background: linear-gradient(135deg, #1e1b4b 0%, #4c1d95 100%);
        padding: 60px 0 50px;
        color: white;
        border-bottom-left-radius: 40px;
        border-bottom-right-radius: 40px;
    }
    
    .logo-frame {
        background: white;
        padding: 16px;
        border-radius: 32px;
        box-shadow: 0 20px 40px rgba(139, 92, 246, 0.3);
        max-width: 300px;
        width: 100%;
        margin: 0 auto;
        transition: 0.5s;
    }
    .logo-frame img {
        border-radius: 20px; /* tumpulkan sudut gambar di dalam frame */
        display: block;
        width: 100%;
    }
    .logo-frame:hover { transform: scale(1.05) rotate(3deg); }

    .stat-box {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 14px 10px;
        border-radius: 16px;
        text-align: center;
    }

    /* 2. Section Badges & Typography */
    .section-badge {
        background: rgba(139, 92, 246, 0.1);
        color: #8b5cf6;
        padding: 8px 20px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.85rem;
        text-transform: uppercase;
        display: inline-block;
    }

    .text-gradient-purple {
        background: linear-gradient(45deg, #4c1d95, #8b5cf6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* 3. Visi & Misi Glass Cards */
    .vision-mission-card {
        background: white;
        border: 1px solid rgba(139, 92, 246, 0.1);
        border-radius: 24px;
        padding: 30px;
        transition: 0.4s ease;
        height: 100%;
        box-shadow: 0 10px 30px rgba(0,0,0,0.02);
    }

    .vision-mission-card:hover {
        transform: translateY(-10px);
        border-color: #8b5cf6;
        box-shadow: 0 20px 40px rgba(139, 92, 246, 0.1);
    }

    /* 4. Feature Icons (Ungu Theme) */
    .feature-icon-purple {
        width: 70px;
        height: 70px;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: 0.3s ease;
    }
    .feature-icon-purple:hover {
        background: #8b5cf6;
        transform: scale(1.1);
    }

    /* 5. Floating Badge */
    .floating-badge {
        position: absolute;
        background: white;
        padding: 15px 25px;
        border-radius: 20px;
        z-index: 10;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    .top-badge { top: -10px; right: -10px; }
    .bottom-badge { bottom: 20px; left: -20px; max-width: 240px; }

    .main-img-frame {
        transform: rotate(-2deg);
        transition: 0.5s ease;
        background: white;
        padding: 10px;
        border-radius: 25px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    }
    .main-img-frame:hover { transform: rotate(0deg) scale(1.02); }

    /* mobile hero section */
    @media (max-width: 991px) {
        .about-hero {
            padding: 40px 0 40px;
        }

        /* Buat layout 2 kolom: teks kiri, logo kanan — rata atas */
        .hero-inner-row {
            display: flex !important;
            flex-direction: row !important;
            align-items: flex-start !important;
            flex-wrap: nowrap !important;
            gap: 0 !important;
        }

        .hero-text-col {
            flex: 1 1 0 !important;
            min-width: 0 !important;
            padding-right: 12px !important;
        }

        .hero-logo-col {
            flex: 0 0 140px !important;
            width: 140px !important;
            max-width: 140px !important;
            padding-top: 0 !important;
        }

        .hero-logo-col .logo-frame {
            max-width: 130px !important;
            min-width: 110px !important;
            padding: 10px !important;
        }

        .hero-title {
            font-size: 1.4rem !important;
            line-height: 1.3 !important;
            margin-bottom: 8px !important;
        }

        .hero-lead {
            font-size: 0.8rem !important;
            line-height: 1.5 !important;
            margin-bottom: 16px !important;
        }

        .stat-box {
            padding: 10px 8px !important;
        }

        .stat-box h2 {
            font-size: 1.1rem !important;
            margin-bottom: 2px !important;
        }

        .stat-box small {
            font-size: 0.65rem !important;
        }

        /* about siap kami */
        .about-image-wrapper {
            padding: 30px 20px !important;
            margin: 0 10px !important;
        }

        .floating-badge {
            padding: 8px 12px !important;
            border-radius: 14px !important;
        }

        .top-badge {
            top: -6px !important;
            right: -6px !important;
        }

        .bottom-badge {
            bottom: 10px !important;
            left: -8px !important;
            max-width: 160px !important;
        }

        .floating-badge h6 {
            font-size: 0.75rem !important;
            margin-bottom: 1px !important;
        }

        .floating-badge small {
            font-size: 0.65rem !important;
        }

        .floating-badge h5 {
            font-size: 0.8rem !important;
            margin-bottom: 2px !important;
        }

        .floating-badge p {
            font-size: 0.7rem !important;
            margin-bottom: 0 !important;
        }

        .floating-badge > div > div:first-child {
            width: 30px !important;
            height: 30px !important;
            font-size: 0.75rem !important;
        }
        .main-img-frame {
            border-radius: 20px;
            transform: rotate(-2deg) !important;
        }
        .col-lg-6 .ps-lg-4 {
            padding: 0 16px !important;
        }

        .col-lg-6 .display-5 {
            font-size: 1.6rem !important;
        }

        .col-lg-6 .p-4 {
            padding: 14px !important;
        }

        .col-lg-6 .p-4 .fs-2 {
            font-size: 1.3rem !important;
        }
        .text-muted {
            text-align: justify !important;
        }

        .vision-mission-card {
            padding: 20px !important;
        }

        .vision-mission-card p,
        .vision-mission-card ul {
            text-align: justify !important;
        }

        .mengapa-section {
            border-radius: 30px !important;
            margin: 20px 12px !important;
            padding: 30px 0 !important;
        }

        /* Feature items lebih compact */
        .feature-icon-purple {
            width: 50px !important;
            height: 50px !important;
            border-radius: 14px !important;
            flex-shrink: 0 !important;
        }

        .feature-icon-purple i {
            font-size: 1.2rem !important;
        }

        /* Kurangi gap antar feature */
        .ps-lg-5 .d-flex.gap-4 {
            gap: 14px !important;
            margin-bottom: 20px !important;
        }

        /* Teks feature rata kanan kiri */
        .ps-lg-5 .d-flex p {
            text-align: justify !important;
            font-size: 0.85rem !important;
        }

        .ps-lg-5 h4 {
            font-size: 1rem !important;
            margin-bottom: 4px !important;
        }

    }
</style>

<!-- Hero Section -->
<section class="about-hero position-relative">
    <div class="container">
        <div class="hero-inner-row row align-items-center g-3 g-lg-5">

            {{-- Kolom Teks + Stats --}}
            <div class="col hero-text-col" data-aos="fade-right">
                <h1 class="display-3 fw-bold mb-2 mb-lg-4 hero-title">
                    Hidupkan Musikmu Bersama <span style="color: #c4b5fd;">FiveFest</span>
                </h1>

                {{-- Logo hanya muncul di mobile (di dalam kolom teks, kanan atas via float) --}}
                {{-- Logo desktop tetap di kolom kanan --}}

                <p class="lead mb-3 mb-lg-5 opacity-75 hero-lead">
                    Menghubungkan ribuan pecinta musik dengan panggung impian mereka sejak 2025. Kami percaya setiap konser adalah cerita yang layak dikenang selamanya.
                </p>

                <div class="row g-2 g-md-3">
                    <div class="col-6 col-md-4">
                        <div class="stat-box">
                            <h2 class="fw-bold mb-0">500K+</h2>
                            <small class="opacity-75">Tiket Terjual</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="stat-box">
                            <h2 class="fw-bold mb-0">2,000+</h2>
                            <small class="opacity-75">Event Sukses</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kolom Logo --}}
            <div class="col-auto col-lg-4 hero-logo-col text-center" data-aos="zoom-in">
                <div class="logo-frame">
                    <img src="{{ asset('storage/images/logo/logo.png') }}" class="img-fluid" alt="FiveFest Logo">
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Section Siapa Kami -->
<section class="py-5 mt-5 position-relative overflow-hidden">
    <div class="container">
        <div class="row align-items-center g-5">
           <div class="col-lg-6" data-aos="fade-right">
                <div class="about-image-wrapper position-relative" style="padding: 20px;">
                    <div class="main-img-frame">
                        <img src="{{ asset('storage/images/esy/about.png') }}" class="img-fluid rounded-4" alt="FiveFest Experience">
                    </div>

                    <div class="badges-wrapper">
                        <div class="floating-badge top-badge" data-aos="fade-down" data-aos-delay="300">
                            <div class="d-flex align-items-center gap-3">
                                <div style="width: 36px; height: 36px; background: #ffc107; color: white; display: flex; align-items: center; justify-content: center; border-radius: 50%; flex-shrink: 0;">
                                    <i class="bi bi-star-fill"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0" style="font-size: 0.85rem;">4.9/5 Rating</h6>
                                    <small class="text-muted">Kepuasan User</small>
                                </div>
                            </div>
                        </div>

                        <div class="floating-badge bottom-badge shadow-lg" data-aos="fade-up" data-aos-delay="400">
                            <h5 class="fw-bold mb-1" style="color: #4c1d95; font-size: 0.9rem;">#1 Ticket Web</h5>
                            <p class="small text-muted mb-0">Terpercaya oleh jutaan penggemar musik.</p>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="ps-lg-4">
                    <span class="section-badge mb-3">Siapa Kami</span>
                    <h2 class="display-5 fw-bold mb-4 text-dark lh-sm">Gerbang Anda Menuju <span class="text-gradient-purple">Musik Live</span></h2>
                    <p class="text-muted mb-4">
                        FiveFest bukan sekadar platform tiket; kami adalah <strong>jembatan emosional</strong> antara idola dan penggemar. 
                    </p>
                    <p class="text-muted mb-5">
                        Sejak berdiri, kami berkomitmen menghadirkan teknologi booking yang  <em>seamless</em>, aman, dan transparan. Dari festival hingga konser stadion megah, FiveFest memastikan Anda tidak melewatkan sejarah musik dunia.
                    </p>
                    <div class="p-4 bg-light rounded-4 d-flex align-items-center gap-4 border-start border-primary border-4 shadow-sm" style="border-color: #8b5cf6 !important;">
                        <div class="flex-shrink-0">
                            <div class="p-3 bg-white rounded-circle shadow-sm">
                                <i class="bi bi-shield-check fs-2" style="color: #8b5cf6;"></i>
                            </div>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1">Keamanan Terjamin</h5>
                            <p class="mb-0 text-muted small">Sertifikasi Keamanan Transaksi Tingkat Nasional (ISO 27001).</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section Visi Misi (RE-DESIGNED) -->
<section class="py-5">
    <div class="container px-4">
        <div class="row g-3 justify-content-center">
            <div class="col-12 col-md-5 " data-aos="fade-up">
                <div class="vision-mission-card">
                    <div class="icon-box bg-purple bg-opacity-10 rounded-4 d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 60px; height: 60px; background: rgba(139, 92, 246, 0.1);">
                        <i class="bi bi-bullseye fs-3" style="color: #8b5cf6;"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Misi Kami</h4>
                    <p class="text-muted">
                        Mendukung industri kreatif dengan memberikan akses pemesanan tiket yang paling mudah, cepat, dan terpercaya bagi siapa saja, di mana saja.
                    </p>
                    <ul class="list-unstyled mt-3">
                        <li class="mb-2 small"><i class="bi bi-check2 text-primary me-2" style="color: #8b5cf6 !important;"></i> Kemudahan Aksesibilitas</li>
                        <li class="mb-2 small"><i class="bi bi-check2 text-primary me-2" style="color: #8b5cf6 !important;"></i> Transaksi Instan</li>
                    </ul>
                </div>
            </div>
            <div class="col-12 col-md-5" data-aos="fade-up" data-aos-delay="100">
                <div class="vision-mission-card">
                    <div class="icon-box rounded-4 d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 60px; height: 60px; background: rgba(139, 92, 246, 0.1);">
                        <i class="bi bi-eye-fill fs-3" style="color: #8b5cf6;"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Visi Kami</h4>
                    <p class="text-muted">
                        Menjadi ekosistem hiburan digital nomor satu di dunia yang mampu memberikan pengalaman menonton konser yang tak terlupakan bagi jutaan orang.
                    </p>
                    <ul class="list-unstyled mt-3">
                        <li class="mb-2 small"><i class="bi bi-check2 text-primary me-2" style="color: #8b5cf6 !important;"></i> Standar Hiburan Dunia</li>
                        <li class="mb-2 small"><i class="bi bi-check2 text-primary me-2" style="color: #8b5cf6 !important;"></i> Koneksi Global</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section Mengapa Memilih Kami (Setema dengan Navbar/Hero) -->
<section class="mengapa-section py-5 my-5 mx-2 mx-md-5 overflow-hidden shadow-lg" 
         style="background: linear-gradient(135deg, #1e1b4b 0%, #4c1d95 100%); border-radius: 50px;">
    <div class="container py-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-5" data-aos="fade-right">
                <div class="section-badge mb-3" style="background: rgba(255,255,255,0.1); color: #c4b5fd; border: 1px solid rgba(255,255,255,0.2);">
                    Keunggulan Kami
                </div>
                <h2 class="display-5 fw-bold mb-4 text-white">Mengapa Memilih <span style="color: #c4b5fd;">FiveFest?</span></h2>
                <p class="text-white opacity-75 mb-4">
                    Kami mendefinisikan ulang cara Anda menikmati hiburan dengan rasa aman dan kenyamanan dalam setiap klik.
                </p>
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex align-items-center gap-3 text-white">
                        <i class="bi bi-patch-check-fill fs-4" style="color: #c4b5fd;"></i>
                        <span>Partner Resmi Promotor Internasional</span>
                    </div>
                    <div class="d-flex align-items-center gap-3 text-white">
                        <i class="bi bi-patch-check-fill fs-4" style="color: #c4b5fd;"></i>
                        <span>Sistem Anti-Bot & Calo Terintegrasi</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-7" data-aos="fade-left">
                <div class="ps-lg-5">
                    <div class="d-flex gap-4 mb-5 align-items-start">
                        <div class="feature-icon-purple">
                            <i class="bi bi-qr-code-scan fs-2 text-white"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold text-white mb-2">Smart E-Ticket System</h4>
                            <p class="text-white opacity-75">E-ticket dengan enkripsi QR dinamis yang menjamin keaslian tiket Anda.</p>
                        </div>
                    </div>
                    <div class="d-flex gap-4 mb-5 align-items-start">
                        <div class="feature-icon-purple">
                            <i class="bi bi-headset fs-2 text-white"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold text-white mb-2">Layanan Pelanggan 24/7</h4>
                            <p class="text-white opacity-75">Tim support kami siap membantu melalui Live Chat atau WhatsApp kapan saja.</p>
                        </div>
                    </div>
                    <div class="d-flex gap-4 align-items-start">
                        <div class="feature-icon-purple">
                            <i class="bi bi-wallet2 fs-2 text-white"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold text-white mb-2">Harga Jujur & Transparan</h4>
                            <p class="text-white opacity-75">Harga final yang Anda bayarkan tanpa biaya admin tersembunyi.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection