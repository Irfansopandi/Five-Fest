<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'fivefest')</title>

    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nprogress@0.2.0/nprogress.min.css">
    <script src="https://cdn.jsdelivr.net/npm/nprogress@0.2.0/nprogress.min.js"></script>
    <link rel="icon" href="/favicon.ico?v=5" type="image/x-icon">
    <link rel="shortcut icon" href="/favicon.ico?v=5" type="image/x-icon">
    <link rel="apple-touch-icon" href="/favicon.ico?v=5">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        /* =========================================
           GLOBAL RESET & BASE STYLES
           ========================================= */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        html, body {
            overflow-x: hidden;
            background-color: #fcfcfe;
            color: #1e1b4b;
            font-family: 'Inter', 'Segoe UI', sans-serif;
        }

        /* NProgress Loading Bar Customization */
        #nprogress .bar {
            background: linear-gradient(90deg, #a855f7, #ec4899, #22d3ee) !important;
            height: 3px !important;
            z-index: 99999 !important;
        }
        #nprogress .peg {
            box-shadow: 0 0 10px #a855f7, 0 0 5px #a855f7 !important;
        }

        .text-purple-magic { color: #8b5cf6 !important; }
        .bg-purple-deep { background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%); }

        .btn-ff-primary {
            background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
            color: white; border: none; transition: 0.4s;
        }
        .btn-ff-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(109,40,217,0.35);
            color: white;
        }

        /* =========================================
           HERO SECTION
           ========================================= */
        .hero-wrapper {
            margin-top: 35px;
            border-radius: 50px;
            overflow: hidden;
            box-shadow: 0 30px 60px -12px rgba(109,40,217,0.25);
            position: relative;
        }

        .hero-img-box {
            height: 620px;
            width: 100%;
            object-fit: cover;
            filter: brightness(0.6);
            transition: transform 12s linear;
        }

        .carousel-item.active .hero-img-box { transform: scale(1.15); }

        .hero-overlay-dark {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(to top, rgba(15,23,42,0.9) 0%, transparent 60%);
        }

        .carousel-item::after {
            content: "";
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.3);
        }

        .hero-caption-box {
            position: absolute; bottom: 12%; left: 8%;
            color: white; z-index: 100; max-width: 700px;
        }
        .hero-caption-box h1 {
            font-weight: 900; font-size: 4.5rem;
            line-height: 1; margin-bottom: 25px; letter-spacing: -2px;
        }
        .hero-caption-box p {
            font-size: 1.3rem; opacity: 0.8;
            margin-bottom: 40px; font-weight: 300;
        }

        /* =========================================
           HELPERS
           ========================================= */
        .feature-img { width:100%; height:200px; object-fit:cover; border-radius:15px; }
        .logo-img { width:45px; height:45px; object-fit:cover; border-radius:50%; }
        body.auth-page nav { display:none !important; }
        body.auth-page footer { margin-top:0 !important; }

        /* Scrollbars */
        .no-scrollbar::-webkit-scrollbar { display:none; }
        .no-scrollbar { -ms-overflow-style:none; scrollbar-width:none; }

        /* Animations */
        .fade-in-up-view { opacity:0; transform:translateY(40px); transition:0.8s ease-out; }
        .fade-in-up-view.active { opacity:1; transform:translateY(0); }

        /* Partners */
        .partner-logo { filter:grayscale(1); opacity:0.5; transition:0.4s; max-height:50px; }
        .partner-logo:hover { filter:grayscale(0); opacity:1; transform:scale(1.1); }
    </style>

    {{-- =============================================
         TRENDING SECTION CSS
         ============================================= --}}
    <style>
        .trending-wrap {
            background: radial-gradient(circle at top right, #2d1b4e 0%, #1a0b2e 60%, #12081f 100%) !important;
            border-radius: 60px;
            margin: 50px auto;
            padding: 60px 40px;
            max-width: 1200px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
            border: 1px solid rgba(255,255,255,0.05);
        }
        .trending-wrap::before {
            content:''; position:absolute; top:-100px; left:-100px;
            width:350px; height:350px;
            background:radial-gradient(circle,rgba(168,85,247,0.08) 0%,transparent 70%);
            pointer-events:none;
        }
        .trending-card-container {
            display:flex; align-items:center; justify-content:center;
            position:relative; perspective:1000px; padding:30px 20px;
        }
        .top-rank-number {
            position:absolute; left:-15px; top:50%;
            transform:translateY(-50%);
            font-size:8rem; font-weight:900;
            color:rgba(255,255,255,0.06);
            -webkit-text-stroke:1px rgba(255,255,255,0.1);
            line-height:1; font-style:italic; z-index:1;
            transition:all 0.6s cubic-bezier(0.23,1,0.32,1); user-select:none;
        }
        .trending-card-container:hover .top-rank-number {
            color:rgba(168,85,247,0.15);
            -webkit-text-stroke:1px rgba(168,85,247,0.3);
            transform:translateY(-50%) translateX(-10px) scale(1.05);
        }
        .top-concert-card {
            width:100%; max-width:280px; z-index:5;
            border-radius:35px; overflow:hidden;
            aspect-ratio:1/1; position:relative; display:block;
            border:1px solid rgba(255,255,255,0.15);
            box-shadow:0 15px 30px rgba(0,0,0,0.4);
            transition:all 0.6s cubic-bezier(0.23,1,0.32,1);
            transform-style:preserve-3d;
        }
        .top-concert-card:hover {
            transform:scale(1.05) rotateY(-10deg) rotateX(5deg);
            border-color:#a855f7; box-shadow:-15px 20px 40px rgba(0,0,0,0.5);
        }
        .top-concert-card img { width:100%; height:100%; object-fit:cover; transition:0.8s ease; }
        .trending-cards-row {
            display: flex;
            flex-wrap: nowrap;
            overflow-x: auto;
            gap: 0;
            padding: 0 20px 20px;
            scrollbar-width: none;
            -ms-overflow-style: none;
            justify-content: center;
        }
        .trending-cards-row::-webkit-scrollbar { display: none; }
        .trending-card-col {
            flex: 0 0 320px;
            max-width: 320px;
        }
    </style>

    {{-- =============================================
         EVENT CARDS CSS
         ============================================= --}}
    <style>
        .event-card, .ff-card {
            background-color:#ffffff !important; border-radius:40px;
            border:1px solid #f1f5f9;
            transition:all 0.4s cubic-bezier(0.165,0.84,0.44,1);
            position:relative; overflow:hidden;
            display:flex; flex-direction:column; height:100%;
        }
        .event-card:hover, .ff-card:hover {
            transform:translateY(-15px);
            box-shadow:0 40px 80px -20px rgba(0,0,0,0.15) !important;
            border-color:#e2e8f0;
        }
        .event-img-wrapper, .ff-img-top {
            height:260px; overflow:hidden; position:relative;
            border-radius:40px 40px 0 0;
        }
        .event-img, .ff-img-top img {
            width:100%; height:100%; object-fit:cover; transition:transform 0.8s ease;
        }
        .event-card:hover .event-img, .ff-card:hover .ff-img-top img { transform:scale(1.1); }
        .category-badge, .ff-badge {
            position:absolute; top:25px; left:25px;
            padding:10px 22px; border-radius:50px;
            font-weight:800; font-size:0.7rem; color:#fff;
            backdrop-filter:blur(12px);
            border:1px solid rgba(255,255,255,0.3);
            z-index:5; text-transform:uppercase; letter-spacing:0.5px;
            box-shadow:0 4px 10px rgba(0,0,0,0.1);
        }
        .concert-card-hover:hover {
            transform:translateY(-10px) !important;
            box-shadow:0 15px 30px rgba(139,92,246,0.15) !important;
        }
    </style>

    {{-- =============================================
         GENRE SECTION CSS
         ============================================= --}}
    <style>
        .genre-clean-section { background:#f8fafc; padding:40px 0; }
        .fw-black { font-weight:900; }
        .tracking-tight { letter-spacing:-1.5px; }
        .text-purple-accent { color:#a855f7; }
        .genre-link { text-decoration:none !important; }
        .genre-card-clean {
            background:#ffffff; border:1px solid #edf2f7;
            border-radius:30px; padding:35px 15px; text-align:center;
            transition:all 0.4s cubic-bezier(0.165,0.84,0.44,1);
            box-shadow:0 4px 6px rgba(0,0,0,0.02);
        }
        .genre-card-clean:hover {
            transform:translateY(-12px); border-color:#a855f7;
            box-shadow:0 20px 40px rgba(168,85,247,0.1);
        }
        .genre-icon-wrapper {
            width:65px; height:65px; border-radius:20px;
            display:flex; align-items:center; justify-content:center;
            margin:0 auto 18px; color:white; font-size:1.6rem; transition:0.3s;
        }
        .genre-card-clean:hover .genre-icon-wrapper { transform:scale(1.1) rotate(-8deg); }
        .genre-title-modern { color:#1e293b; font-weight:800; font-size:0.95rem; margin-bottom:8px; }
        .genre-hover-dot {
            width:6px; height:6px; background:#a855f7; border-radius:50%;
            margin:0 auto; opacity:0; transform:translateY(10px); transition:0.3s;
        }
        .genre-card-clean:hover .genre-hover-dot { opacity:1; transform:translateY(0); }
        .kpop-bg { background:linear-gradient(135deg,#f472b6,#ec4899); }
        .jazz-bg { background:linear-gradient(135deg,#60a5fa,#3b82f6); }
        .rock-bg { background:linear-gradient(135deg,#fb7185,#f43f5e); }
        .indie-bg { background:linear-gradient(135deg,#34d399,#10b981); }
        .pop-bg { background:linear-gradient(135deg,#a855f7,#7c3aed); }
    </style>

    {{-- =============================================
         TESTIMONIAL CSS
         ============================================= --}}
    <style>
        .testimonial-cta-wrap {
            background:linear-gradient(135deg,#4c1d95 0%,#7c3aed 100%) !important;
            border-radius:50px; margin:50px auto; padding:60px 0;
            max-width:1200px; box-shadow:0 35px 60px -15px rgba(0,0,0,0.3);
            overflow:hidden;
        }
        .testi-card-cta {
            background:rgba(255,255,255,0.1); backdrop-filter:blur(8px);
            border:1px solid rgba(255,255,255,0.2) !important;
            padding:35px; border-radius:35px; transition:all 0.3s ease;
        }
        .testi-card-cta:hover { background:rgba(255,255,255,0.15); }
        .testi-text-white { color:#ffffff; font-size:1rem; line-height:1.6; margin-bottom:25px; }
        .rating-stars { color:#fbbf24; font-size:0.9rem; }
        .avatar-highlight {
            width:50px; height:50px; background:#f1e2ff; color:#1e1b4b;
            border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:800;
        }
        .testi-circle-1 {
            position:absolute; width:300px; height:300px;
            background:rgba(255,255,255,0.05); border-radius:50%; top:-150px; right:-50px;
        }
        .testi-circle-2 {
            position:absolute; width:200px; height:200px;
            background:rgba(255,255,255,0.05); border-radius:50%; bottom:-100px; left:-50px;
        }
        
        .testi-scroll-inner {
            display: flex !important;
            flex-wrap: nowrap !important;
            animation: testiMarquee 20s linear infinite;
            width: max-content;
        }

        .testi-scroll-inner:hover {
            animation-play-state: paused;
        }

        @keyframes testiMarquee {
            0%   { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
    </style>

    {{-- =============================================
         MARQUEE CSS
         ============================================= --}}
    <style>
        .marquee-container { position:relative; width:100%; overflow:hidden; padding:20px 0; }
        .marquee-content { display:flex; width:max-content; animation:marquee 30s linear infinite; }
        .marquee-item {
            margin:0 40px; flex-shrink:0;
            display:flex; align-items:center; justify-content:center;
            opacity:0.6; transition:opacity 0.3s ease;
        }
        .marquee-item:hover { opacity:1; }
        .marquee-item img { height:45px; width:auto; filter:grayscale(1); transition:filter 0.3s ease; }
        .marquee-item:hover img { filter:grayscale(0); }
        @keyframes marquee { 0%{transform:translateX(0);} 100%{transform:translateX(-50%);} }
    </style>

    {{-- =============================================
         PROMO BANNER CSS
         ============================================= --}}
    <style>
        .promo-img { height:180px; object-fit:cover; object-position:center; }
        .promo-nav-btn {
            width:40px; height:40px; background-color:#ffffff; color:#333;
            border-radius:50%; display:flex; align-items:center; justify-content:center;
            box-shadow:0 4px 10px rgba(0,0,0,0.1); font-size:1.2rem; transition:all 0.3s ease;
        }
        .promo-nav-btn:hover { background-color:#f8f9fa; transform:scale(1.1); }
    </style>

    {{-- =============================================
         FOOTER CSS
         ============================================= --}}
    <style>
        footer.main-footer {
            background:linear-gradient(90deg,#4c1d95 0%,#6d28d9 100%) !important;
            border-top:1px solid rgba(255,255,255,0.1); color:white;
        }
        footer .text-white-80 { color:rgba(255,255,255,0.8) !important; }
        footer .text-white-10 { color:rgba(255,255,255,0.7) !important; }
        footer .hover-link { transition:all 0.3s ease; text-decoration:none; }
        footer .hover-link:hover { color:#debcfc !important; padding-left:8px; }
        footer .social-icon {
            width:42px; height:42px; background:rgba(255,255,255,0.1);
            display:inline-flex; align-items:center; justify-content:center;
            border-radius:12px; color:white; text-decoration:none;
            transition:all 0.3s; border:1px solid rgba(255,255,255,0.1);
        }
        footer .social-icon:hover {
            background:white; color:#6d28d9;
            transform:translateY(-5px) rotate(8deg); box-shadow:0 10px 20px rgba(0,0,0,0.2);
        }
        footer hr { opacity:0.1; border-color:white; }
        .text-highlight-footer {
            background:linear-gradient(135deg,#fde047 0%,#d97706 100%);
            -webkit-background-clip:text; -webkit-text-fill-color:transparent;
            display:inline-block; filter:drop-shadow(0 0 10px rgba(251,191,36,0.4));
        }
        .hover-link { transition:all 0.3s ease; position:relative; }
        .hover-link:hover { color:#fefcff !important; padding-left:8px; }
        .hover-link::before {
            content:''; position:absolute; width:0; height:1.5px;
            bottom:-2px; left:0; background-color:#764ba2; transition:width 0.3s ease;
        }
        .hover-link:hover::before { width:30px; }
        .social-icon {
            display:inline-flex; align-items:center; justify-content:center;
            width:40px; height:40px; background:rgba(255,255,255,0.1);
            border-radius:50%; color:#cbd5e1; transition:all 0.3s ease;
        }
        .social-icon:hover { background:#764ba2; color:white; transform:translateY(-3px); }
        @keyframes bounceWithShadow {
            0%,100%{transform:translateY(0);box-shadow:0 0 10px rgba(12,174,233,0.5);}
            50%{transform:translateY(-25px);box-shadow:0 15px 30px rgba(43,213,255,0.3);}
        }
        .logo-bounce { animation:bounceWithShadow 2.5s ease-in-out infinite; }
    </style>

    {{-- =============================================
         NAVBAR OVERRIDES (dropdown arrow, nav-link)
         ============================================= --}}
    <style>
        .dropdown-toggle::after { display:none; }
        .nav-link { color:rgba(255,255,255,0.85) !important; transition:0.3s; }
        .nav-link:hover, .nav-link.active { color:#fff !important; }
    </style>

    {{-- =============================================
         MOBILE SEARCH BAR 
         ============================================= --}}
    <style>
        .mobile-search-bar { 
        display: none !important; 
    }
    
    /* Tampilkan HANYA di mobile */
        @media (max-width: 768px) {
            .mobile-search-bar {
                display: flex !important;
                background: white;
                border-radius: 50px;
                padding: 11px 18px;
                margin: 12px 16px 0;
                box-shadow: 0 2px 12px rgba(0,0,0,0.08);
                border: 1px solid #e2e8f0;
                align-items: center;
                gap: 10px;
                cursor: pointer;
            }
            .mobile-search-bar i { color: #94a3b8; font-size: 0.95rem; }
            .mobile-search-bar input {
                border: none !important; outline: none !important; width: 100% !important;
                font-size: 0.88rem !important; color: #94a3b8 !important;
                background: transparent !important;
            }
        }
    </style>

    {{-- =============================================
         HERO MOBILE
         ============================================= --}}
    <style>
       @media (max-width: 768px) {
        .hero-wrapper {
            margin: 10px 12px 0 !important;
            border-radius: 20px !important;
        }
        .hero-img-box {
            height: 260px !important;
            transform: none !important;
            transition: none !important;
            filter: brightness(0.75) !important;
        }
        .carousel-item.active .hero-img-box { transform: none !important; }
        .hero-overlay-dark {
            background: linear-gradient(to top, rgba(15,23,42,0.6) 0%, transparent 70%) !important;
        }
        .carousel-item::after {
            background: rgba(0,0,0,0.2) !important;
        }
        .hero-caption-box {
            top: 50% !important;
            bottom: auto !important;
            transform: translateY(-50%) !important;
            left: 20px !important;
            right: 20px !important;
            max-width: calc(100% - 40px) !important;
            display: block !important;
        }
        .hero-caption-box h1 {
            font-size: 1.25rem !important; line-height: 1.2 !important;
            margin-bottom: 6px !important;
            font-weight: 800 !important;
        }
        .hero-caption-box p {
            font-size: 0.68rem !important; 
            margin-bottom: 0 !important;
            display: -webkit-box !important;
            -webkit-line-clamp: 2 !important;
            -webkit-box-orient: vertical !important;
            overflow: hidden !important;
            opacity: 0.85 !important;
        }
        .hero-caption-box .btn {
            display: none !important;
        }
        .hero-caption-box .badge {
            font-size: 0.55rem !important; 
            padding: 4px 8px !important; 
            margin-bottom: 6px !important;
            margin-top: 0 !important;
        }
        .carousel-indicators button {
            width: 6px !important; 
            height: 6px !important;
            border-radius: 50% !important; 
            opacity: 0.5 !important; 
            margin: 0 3px !important;
        }
        .carousel-indicators .active {
            width: 20px !important; 
            border-radius: 4px !important; 
            opacity: 1 !important; 
        }
    }
    </style>

    {{-- =============================================
         KONSER MENDATANG SECTION - MOBILE HORIZONTAL SCROLL
         ============================================= --}}
    <style>
    @media (max-width: 768px) {
        #upcoming-concerts { padding: 24px 0 !important; }
        #upcoming-concerts > .container {
            padding-left: 0 !important;
            padding-right: 0 !important;
            max-width: 100% !important;
        }
        #upcoming-concerts .container > .d-flex.justify-content-between {
            padding: 0 16px !important;
            margin-bottom: 16px !important;
        }
        #upcoming-concerts .header-content h3 { font-size: 1.4rem !important; }
        #upcoming-concerts a[href*="search"] { font-size: 0.78rem !important; padding: 7px 14px !important; white-space: nowrap !important; }

        /* Row scroll horizontal */
        #upcoming-concerts .row.g-4.mb-5 {
            display: flex !important;
            flex-wrap: nowrap !important;
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch !important;
            gap: 14px !important;
            padding: 6px 16px 20px !important;
            margin: 0 !important;
            scrollbar-width: none !important;
            align-items: stretch !important;
        }
        #upcoming-concerts .row.g-4.mb-5::-webkit-scrollbar { display: none !important; }

        /* Setiap kolom card */
        #upcoming-concerts .col-lg-4 {
            flex: 0 0 220px !important;
            max-width: 220px !important;
            min-width: 220px !important;
            padding: 0 !important;
            display: flex !important;
        }

        /* Card wrapper */
        #upcoming-concerts .card {
            border-radius: 20px !important;
            display: flex !important;
            flex-direction: column !important;
            width: 100% !important;
            height: auto !important;
            overflow: hidden !important;
        }

        /* Gambar — tinggi tetap, tidak terpotong */
        #upcoming-concerts .card-img-mobile {
            height: 160px !important;
            min-height: 160px !important;
            max-height: 160px !important;
            overflow: hidden !important;
            flex-shrink: 0 !important;
        }
        #upcoming-concerts .card-img-mobile img {
            height: 160px !important;
            width: 100% !important;
            object-fit: cover !important;
            display: block !important;
        }

        /* Body card */
        #upcoming-concerts .card-body {
            padding: 12px !important;
            display: flex !important;
            flex-direction: column !important;
            flex: 1 !important;
        }

        #upcoming-concerts .card-body h4 {
            font-size: 0.82rem !important;
            min-height: auto !important;
            margin-bottom: 5px !important;
            line-height: 1.3 !important;
        }
        #upcoming-concerts .card-body .small { font-size: 0.7rem !important; }
        #upcoming-concerts .card-body .mb-2 { margin-bottom: 3px !important; }
        #upcoming-concerts .card-body .mb-4 { margin-bottom: 6px !important; }

        /* Footer card: harga + tombol */
        #upcoming-concerts .card-body .d-flex.justify-content-between.align-items-center {
            display: flex !important;
            flex-direction: row !important;
            flex-wrap: nowrap !important;
            align-items: center !important;
            justify-content: space-between !important;
            gap: 6px !important;
            margin-top: auto !important;
            padding-top: 8px !important;
            border-top: 1px solid #f1f5f9 !important;
            min-height: 48px !important;
        }

        /* Blok harga kiri */
        #upcoming-concerts .card-body .d-flex.justify-content-between > div:first-child {
            flex: 1 1 auto !important;
            min-width: 0 !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: center !important;
        }
        #upcoming-concerts .card-body small.text-muted {
            font-size: 0.58rem !important;
            display: block !important;
            white-space: nowrap !important;
            text-transform: uppercase !important;
            color: #94a3b8 !important;
        }
        #upcoming-concerts .card-body .fw-bold.fs-4 {
            font-size: 0.82rem !important;
            font-weight: 800 !important;
            white-space: nowrap !important;
            display: block !important;
            color: #8b5cf6 !important;
            line-height: 1.2 !important;
        }

        /* Tombol kanan — tidak menyusut, tidak terpotong */
        #upcoming-concerts .card-body .btn {
            flex-shrink: 0 !important;
            padding: 6px 12px !important;
            font-size: 0.68rem !important;
            white-space: nowrap !important;
            border-radius: 50px !important;
            width: auto !important;
            display: inline-flex !important;
            align-items: center !important;
            align-self: center !important;
            height: fit-content !important;
        }
        /* Badge kategori - perkecil di mobile */
        #upcoming-concerts .card-img-mobile .position-absolute {
            font-size: 0.55rem !important;
            padding: 3px 8px !important;
            margin: 6px !important;
            letter-spacing: 0.3px !important;
            }
        }
    </style>

    {{-- =============================================
         TRENDING SECTION - MOBILE
         ============================================= --}}
    <style>
        @media (max-width: 768px) {
        .trending-wrap { 
            overflow: visible !important;
            border-radius: 30px !important;
            margin: 20px 16px !important;
            padding: 24px 0 20px !important;}
        .trending-header { padding: 0 16px !important; margin-bottom: 12px !important; }
        .trending-header h2 { font-size: 1.3rem !important; }
        .trending-header p { font-size: 0.76rem !important; }
        .trending-cards-row {
            justify-content: flex-start !important;
            padding: 0 16px 16px !important;
            gap: 0 !important;
        }
        .trending-card-col { flex: 0 0 165px !important; max-width: 165px !important; }
        .trending-card-container { padding: 16px 10px !important; }
        .top-rank-number { font-size: 4rem !important; left: -6px !important; }
        .top-concert-card { max-width: 145px !important; border-radius: 16px !important; }
    }
    </style>

    {{-- =============================================
         GENRE/KATEGORI - MOBILE
         ============================================= --}}
    <style>
        @media (max-width: 768px) {
        .genre-clean-section { padding: 24px 0 !important; }
        .genre-clean-section .text-center.mb-5 { margin-bottom: 14px !important; padding: 0 16px; }
        .genre-clean-section h4 { font-size: 1.3rem !important; }

        /* UBAH GRID JADI HORIZONTAL SCROLL */
        .genre-clean-section .row.g-4.row-cols-2.row-cols-md-5 {
            display: flex !important; flex-wrap: nowrap !important;
            overflow-x: auto !important; -webkit-overflow-scrolling: touch !important;
            scroll-snap-type: x mandatory !important;
            gap: 10px !important; padding: 6px 16px 16px !important;
            margin: 0 !important; max-width: 100% !important;
            justify-content: flex-start !important; scrollbar-width: none !important;
        }
        .genre-clean-section .row.g-4.row-cols-2.row-cols-md-5::-webkit-scrollbar { display: none !important; }
        .genre-clean-section .col {
            flex: 0 0 95px !important; max-width: 95px !important;
            scroll-snap-align: start !important; padding: 0 !important;
        }
        .genre-card-clean { padding: 18px 8px 14px !important; border-radius: 18px !important; }
        .genre-icon-wrapper {
            width: 46px !important; height: 46px !important;
            border-radius: 13px !important; font-size: 1.15rem !important;
            margin: 0 auto 10px !important;
        }
        .genre-title-modern { font-size: 0.72rem !important; }
    }
    </style>

    {{-- =============================================
         PARTNERS MARQUEE - MOBILE
         ============================================= --}}
    <style>
        @media (max-width: 768px) {
            .py-5.bg-white.border-top h2 { font-size: 1rem !important; }
            .marquee-item img { height: 28px !important; }
            .marquee-item { margin: 0 20px !important; }
        }
    </style>

    {{-- =============================================
         TESTIMONIAL - MOBILE HORIZONTAL SCROLL
         ============================================= --}}
    <style>
        @media (max-width: 768px) {
            .testimonial-cta-wrap {
                border-radius: 24px !important; margin: 16px 12px !important;
                padding: 28px 0 20px !important; max-width: calc(100% - 24px) !important;
            }

            .testimonial-cta-wrap h2 { font-size: 1.3rem !important; }
            .testi-scroll-inner > div { 
                flex: 0 0 260px !important; 
                max-width: 260px !important;
                min-width: 260px !important;}
            .testi-card-cta {
                padding: 20px 18px !important; 
                border-radius: 18px !important; 
                height: 100% !important;
                box-sizing: border-box !important;
            }
            .testi-text-white { font-size: 0.82rem !important; margin-bottom: 16px !important; }
            .testi-circle-1, .testi-circle-2 { display: none !important; }
            /* Perlambat animasi di mobile */
            .testi-scroll-inner { animation-duration: 15s !important; }
        }
    </style>

    {{-- =============================================
     FOOTER - MOBILE
     ============================================= --}}
    <style>

    @media (min-width: 769px) {
        footer.main-footer {
            padding-top: 2rem !important;
            padding-bottom: 1rem !important;
        }
        footer .row.gy-4 { 
            --bs-gutter-y: 0 !important; 
        }
        footer .mt-4.pt-3 {
            margin-top: 1rem !important;
            padding-top: 1rem !important;
        }

        footer .row.gy-4 > div:nth-child(2),
        footer .row.gy-4 > div:nth-child(3) {
            margin-top: 0rem !important;
            padding-top: 0rem !important;
            border-top: none !important;
            padding-left: 10px !important;
        }
    }
    @media (max-width: 768px) {
        footer.main-footer {
            padding: 32px 0 20px !important;
        }
        footer .container { padding: 0 24px !important; }

        /* Semua kolom full width, stacked vertikal */
        footer .row.gy-4 > div {
            width: 100% !important;
            flex: 0 0 100% !important;
            max-width: 100% !important;
            text-align: left !important;
            margin-bottom: 0px !important;
        }

        /* Kolom Tautan Cepat & Support: side by side di mobile */
        footer .row.gy-4 > div:nth-child(2),
        footer .row.gy-4 > div:nth-child(3) {
            flex: 0 0 50% !important;
            max-width: 50% !important;
            width: 50% !important;
        }

        /* Tambah jarak antara brand/kontak dan tautan di bawahnya */
        footer .row.gy-4 > div:nth-child(2) {
            margin-top: 0px !important;
            padding-top: 24px !important;
            border-top: 1px solid rgba(255,255,255,0.1) !important;
        }
        footer .row.gy-4 > div:nth-child(3) {
            margin-top: 0px !important;
            padding-top: 24px !important;
        }

        /* Brand - left aligned */
        footer .row.gy-4 > div:first-child p {
            font-size: 0.85rem !important;
            max-width: 100% !important;
            margin: 0 0 10px !important;
            text-align: left !important;
        }
        footer .row.gy-4 > div:first-child .mt-4 {
            display: flex !important;
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 6px !important;
        }

        /* Social icons - SELALU horizontal & rata kiri */
        footer .d-flex.gap-2 { 
            flex-direction: row !important;
            flex-wrap: nowrap !important;
            justify-content: flex-start !important; 
        }
        footer .social-icon {
            width: 38px !important;
            height: 38px !important;
            border-radius: 50% !important;
        }

        /* Heading section */
        footer h6 { 
            font-size: 0.85rem !important; 
            margin-bottom: 12px !important;
            text-align: left !important;
        }

        /* Link list */
        footer .hover-link { 
            font-size: 0.85rem !important; 
        }
        footer ul.list-unstyled li {
            text-align: left !important;
        }

        /* Social section wrapper */
        footer .mt-4.pt-3 {
            text-align: left !important;
        }
        footer .mt-4.pt-3 > .d-flex {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 12px !important;
        }
        footer .mt-4.pt-3 p {
            text-align: left !important;
            font-size: 0.82rem !important;
            margin-bottom: 0 !important;
        }

        /* Bottom bar */
        footer hr { margin: 20px 0 12px !important; }
        footer .row.align-items-center .col-md-6 { 
            text-align: left !important; 
        }
        footer .row.align-items-center p,
        footer .row.align-items-center small {
            font-size: 0.75rem !important;
            text-align: left !important;
            display: block !important;
        }
    }
    </style>

    {{-- =============================================
         MODE INDICATOR (ADMIN/VENDOR) - MOBILE
         ============================================= --}}
    <style>
        @media (max-width: 768px) {
            .mode-indicator { margin: 12px 16px 0 !important; }
            .mode-indicator .container {
                padding: 12px 14px !important; border-radius: 16px !important;
                flex-direction: column !important; gap: 10px !important;
                align-items: flex-start !important;
            }
            .mode-indicator .btn { width: 100% !important; text-align: center !important; }
        }
    </style>

    {{-- =============================================
         EVENT CARDS - MOBILE GENERIC
         ============================================= --}}
    <style>
        @media (max-width: 768px) {
            .event-img-wrapper, .ff-img-top { height: 200px; }
            .event-card, .ff-card { border-radius: 25px; }
            .promo-img { height: 120px; }
            .promo-nav-btn { width: 30px; height: 30px; font-size: 1rem; }
        }
    </style>

    @stack('styles')
</head>
<body>

    {{-- NAVBAR --}}
    @include('components.navbar')

    {{-- CONTENT --}}
    @yield('content')

    {{-- =============================================
         FOOTER
    ============================================= --}}
    @if(!isset($hideFooter))
    <footer class="pt-4 pb-3 main-footer">
    <div class="container" style="max-width: 1100px;">
        <div class="row gy-0 align-items-start flex-wrap align-items-stretch ">

            {{-- BRAND --}}
            <div class="col-lg-5 col-md-6 col-12">
                <h4 class="fw-bold mb-3">FIVE<span class="text-highlight-footer">FEST</span></h4>
                <p class="text-white-10 small lh-lg">
                    Gerbang tepercaya Anda menuju pengalaman musik live yang tak terlupakan.
                    Temukan, pesan, dan nikmati konser terbaik di kota Anda.
                </p>
                <div class="mt-4">
                    <a href="mailto:hello@fivefest.id" class="text-decoration-none d-block mb-2 text-white-80 small">
                        <i class="bi bi-envelope me-2 text-highlight-footer"></i> hello@fivefest.id
                    </a>
                    <a href="https://wa.me/6285946653103" class="text-decoration-none d-block text-white-80 small" target="_blank">
                        <i class="bi bi-telephone me-2 text-highlight-footer"></i> +62 859-4665-3103
                    </a>
                </div>
            </div>

            {{-- TAUTAN CEPAT --}}
            <div class="col-lg-3 col-md-6 col-6 mt-3 mt-lg-0">
                <h6 class="fw-bold text-uppercase mb-3 small" style="letter-spacing:1px;">Tautan Cepat</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ url('/') }}" class="text-white-80 hover-link">Home</a></li>
                    <li class="mb-2"><a href="{{ url('#') }}" class="text-white-80 hover-link">All Events</a></li>
                    <li class="mb-2"><a href="{{ url('/about') }}" class="text-white-80 hover-link">About Us</a></li>
                    <li class="mb-2"><a href="{{ url('/contact') }}" class="text-white-80 hover-link">Contact</a></li>
                </ul>
            </div>

            {{-- SUPPORT --}}
            <div class="col-lg-3 col-md-6 col-6 mt-3 mt-lg-0">
                <h6 class="fw-bold text-uppercase mb-3 small" style="letter-spacing:1px;">Support</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ url('/terms') }}" class="text-white-80 hover-link">Terms of Service</a></li>
                    <li class="mb-2"><a href="{{ url('/terms') }}" class="text-white-80 hover-link">Privacy Policy</a></li>
                    <li class="mb-2"><a href="{{ url('/terms') }}" class="text-white-80 hover-link">Refund Policy</a></li>
                    <li class="mb-2"><a href="{{ url('/contact') }}" class="text-white-80 hover-link">Help Center</a></li>
                </ul>
            </div>

        </div>

        {{-- SOCIAL MEDIA - FULL WIDTH BAWAH --}}
        <div class="mt-4 pt-3" style="border-top: 1px solid rgba(255,255,255,0.1);">
            <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
                <div>
                    <p class="text-white-80 small mb-2 mb-md-0">Ikuti kami untuk penawaran eksklusif dan informasi konser terkini!</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="#" class="social-icon"><i class="bi bi-facebook"></i></a>
                    <a href="https://www.instagram.com/irfan_sopandi_/" class="social-icon" target="_blank"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="social-icon"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" class="social-icon"><i class="bi bi-tiktok"></i></a>
                    <a href="https://www.youtube.com/@irfansofandi9130" class="social-icon" target="_blank"><i class="bi bi-youtube"></i></a>
                </div>
            </div>
        </div>

        <hr class="my-4">

        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <p class="mb-0 text-white-80 small">
                    &copy; <span id="year">2026</span> <strong>Five Fest</strong>. Hak cipta dilindungi undang-undang.
                </p>
            </div>
            <div class="col-md-6 text-center text-md-end mt-2 mt-md-0">
                <small class="text-white-80">
                    Dibuat dengan <i class="bi bi-heart-fill text-danger"></i> oleh Kelompok 5
                </small>
            </div>
        </div>
    </div>
</footer>
@endif

    {{-- SCRIPTS --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>

    <script>
        // Auto year
        document.getElementById("year").textContent = new Date().getFullYear();

        // AOS
        AOS.init({ duration:900, easing:'ease-out-quart', once:true, offset:50 });

        // NProgress Global Transition Feedback
        if (typeof NProgress !== 'undefined') {
            NProgress.configure({ showSpinner: false, speed: 400 });
            window.addEventListener('beforeunload', function () {
                NProgress.start();
            });
            document.addEventListener('click', function (e) {
                const link = e.target.closest('a');
                if (link && link.href && !link.target && !link.href.startsWith('javascript:') && !link.href.includes('#') && link.origin === window.location.origin) {
                    NProgress.start();
                }
            });
            document.addEventListener('submit', function (e) {
                const form = e.target;
                if (form && !form.target) {
                    NProgress.start();
                    const btn = form.querySelector('button[type="submit"]');
                    if (btn && !btn.classList.contains('no-loader')) {
                        btn.style.opacity = '0.85';
                        btn.style.pointerEvents = 'none';
                        btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Memproses...`;
                    }
                }
            });
        }

        // SweetAlert session messages
        @if(session('success'))
            Swal.fire({ icon:'success', title:'Berhasil!', text:'{{ session('success') }}', confirmButtonColor:'#8b5cf6' });
        @endif
        @if(session('error'))
            Swal.fire({ icon:'error', title:'Oops...', text:'{{ session('error') }}', confirmButtonColor:'#8b5cf6' });
        @endif
    </script>

    @stack('scripts')
</body>
</html>