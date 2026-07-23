<nav class="navbar navbar-expand-lg navbar-dark sticky-top py-3 main-navbar">
    <div class="container px-4">

        {{-- LOGO SECTION --}}
        <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
            <div class="logo-wrapper">
                <img src="{{ asset('storage/images/logo/logo.png') }}" alt="FiveFest" class="brand-logo" onerror="this.src='{{ asset('storage/images/logo/logow.png') }}'">
            </div>
            <div class="brand-text-wrapper ms-2">
                <span class="brand-name">FIVE<span class="text-highlight">FEST</span></span>
                <small class="brand-tagline d-none d-sm-block">Ultimate Event Experience</small>
            </div>
        </a>

        {{-- DESKTOP: Nav collapse (hidden on mobile, kita pakai panel sendiri) --}}
        <div class="collapse navbar-collapse justify-content-center d-none d-lg-flex" id="navbarNav">
            <ul class="navbar-nav nav-custom gap-1 gap-lg-3 align-items-center">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">
                        <i class="bi bi-house-door-fill me-1"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('about') ? 'active' : '' }}" href="{{ url('/about') }}">
                        <i class="bi bi-info-circle-fill me-1"></i> About
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('contact') ? 'active' : '' }}" href="{{ url('/contact') }}">
                        <i class="bi bi-telephone-fill me-1"></i> Contact
                    </a>
                </li>
                @auth
                    @if(Auth::user()->role === 'vendor')
                        <li class="nav-item">
                            <a class="nav-link creator-link fw-bold {{ request()->routeIs('vendor.dashboard') ? 'active' : '' }}" href="{{ route('vendor.dashboard') }}">
                                <i class="bi bi-grid-1x2-fill me-1"></i> Dashboard
                            </a>
                        </li>
                    @endif
                @else
                    <li class="nav-item d-flex align-items-center">
                        <a class="nav-link creator-link" href="{{ route('vendor.landing') }}">
                            <i class="bi bi-plus-circle-fill me-1"></i> Buat Event
                        </a>
                    </li>
                    <li class="nav-item d-flex align-items-center">
                        <a class="nav-link creator-link" href="{{ route('register.tenant.show') }}">
                            <i class="bi bi-shop me-1"></i> Join Tenant
                        </a>
                    </li>
                @endauth
            </ul>
        </div>

        {{-- DESKTOP: Right side --}}
        <div class="d-none d-lg-flex align-items-center justify-content-end gap-3 actions-right">
            <button class="btn-search-trigger" onclick="toggleSearch()" title="Cari Event">
                <i class="bi bi-search"></i>
            </button>

            @guest
                <a href="{{ route('login') }}" class="btn-login">Masuk</a>
                <a href="{{ route('register') }}" class="btn-register-premium">Gabung Sekarang</a>
            @else
                <a href="{{ route('my-messages') }}" class="btn-search-trigger d-flex align-items-center justify-content-center position-relative text-decoration-none me-2" title="Pesan Bantuan">
                    <i class="bi bi-envelope"></i>
                    @php
                        $repliedCount = \App\Models\ContactMessage::where('email', Auth::user()->email)
                            ->where('status', 'replied')
                            ->where('is_read_by_user', false)
                            ->count();
                    @endphp
                    @if($repliedCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle" style="width:10px;height:10px;margin-top:10px;margin-left:-10px;"></span>
                    @endif
                </a>
                <div class="dropdown">
                    <a class="user-profile-dropdown dropdown-toggle" href="#" data-bs-toggle="dropdown" data-bs-auto-close="true">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=8b5cf6&color=fff" class="user-avatar" alt="User">
                        <span class="user-name ms-2 d-none d-xl-inline">{{ Auth::user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end profile-dropdown-menu">
                        <div class="px-3 py-2 border-bottom mb-2">
                            <small class="text-muted d-block">Halo,</small>
                            <span class="fw-bold">{{ Auth::user()->name }}</span>
                        </div>
                        <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="bi bi-person me-2"></i>Profil</a></li>
                        @if(Auth::user()->role === 'vendor')
                            <li><a class="dropdown-item" href="{{ route('vendor.dashboard') }}"><i class="bi bi-graph-up-arrow me-2"></i>Dashboard Jualan</a></li>
                        @endif
                        @if(Auth::user()->role === 'tenant')
                            <li><a class="dropdown-item" href="{{ route('tenant.booths.index') }}"><i class="bi bi-shop me-2"></i>Status Booth</a></li>
                        @elseif(Auth::user()->role === 'admin')
                            <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                        @elseif (Auth::user()->role === 'owner')
                            <li><a class="dropdown-item" href="{{ route('owner.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                        @elseif(Auth::user()->role === 'vendor_staff')
                            <li><a class="dropdown-item" href="{{ route('vendor.staff.scanner') }}"><i class="bi bi-qr-code-scan me-2"></i>Scanner Merchandise</a></li>
                        @elseif(Auth::user()->role === 'user')
                            <li><a class="dropdown-item" href="{{ route('my-tickets') }}"><i class="bi bi-ticket-perforated me-2"></i>Tiket Saya</a></li>
                        @endif
                        <li><hr class="dropdown-divider"></li>
                        <li>
                           <form action="{{ route('logout') }}" method="POST" id="logoutFormNavbar">
                                @csrf
                                <button type="button" onclick="confirmLogout('logoutFormNavbar')" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>Keluar
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endguest
        </div>

        {{-- MOBILE: Tombol hamburger (hanya di mobile) --}}
        <button class="mobile-hamburger d-lg-none" id="mobileMenuToggle" type="button" aria-label="Buka Menu">
            <span></span>
            <span></span>
            <span></span>
        </button>

    </div>
</nav>

{{-- ============================================================
     SEARCH OVERLAY (Desktop & Mobile)
     ============================================================ --}}
<div id="searchBarOverlay" class="search-overlay-container">
    <div class="search-box-wrapper">
        <div class="container h-100 d-flex align-items-center justify-content-center">
            <div class="search-card-main">
                <div class="search-header d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold text-dark mb-0">Cari Konser Impian</h4>
                    <button class="btn-close-search" onclick="toggleSearch()"><i class="bi bi-x-lg"></i></button>
                </div>
                <form action="{{ route('search') }}" method="GET">
                    <div class="input-group-search">
                        <i class="bi bi-search search-icon-input"></i>
                        <input type="text" name="query" class="form-control-search" placeholder="Cari artis, venue, atau kota..." autocomplete="off" autofocus>
                        <button type="submit" class="btn-search-submit">Cari</button>
                    </div>
                </form>
                <div class="search-quick-tags mt-4">
                    <small class="text-muted d-block mb-2">Pencarian Populer:</small>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('search', ['query' => 'K-Pop']) }}" class="quick-tag">#KPop</a>
                        <a href="{{ route('search', ['query' => 'Festival']) }}" class="quick-tag">#Festival</a>
                        <a href="{{ route('search', ['query' => 'Jakarta']) }}" class="quick-tag">#Jakarta</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================
     MOBILE SLIDE-IN PANEL (Loket Style)
     ============================================================ --}}

{{-- Backdrop --}}
<div id="mobileBackdrop"></div>

{{-- Panel --}}
<div id="mobileNavPanel">

    {{-- === HEADER PANEL === --}}
    <div class="mnp-header">
        {{-- Close button --}}
        <button id="mobileMenuClose" class="mnp-close-btn" aria-label="Tutup">
            <i class="bi bi-x-lg"></i>
        </button>

        @auth
        {{-- User yang sudah login --}}
        <div class="mnp-user-info">
            <div class="mnp-avatar">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=ffffff&color=7c3aed&size=80" alt="{{ Auth::user()->name }}">
            </div>
            <div class="mnp-user-text">
                <div class="mnp-user-name">{{ Auth::user()->name }}</div>
                <div class="mnp-user-email">{{ Auth::user()->email }}</div>
                <span class="mnp-role-badge">
                    @if(Auth::user()->role === 'admin') <i class="bi bi-shield-fill me-1"></i>Admin
                    @elseif (Auth::user()->role === 'owner') <i class="bi bi-crown-fill me-1"></i>Owner
                    @elseif(Auth::user()->role === 'vendor') <i class="bi bi-megaphone-fill me-1"></i>Vendor
                    @elseif(Auth::user()->role === 'vendor_staff') <i class="bi bi-qr-code-scan me-1"></i>Staff
                    @elseif(Auth::user()->role === 'tenant') <i class="bi bi-shop me-1"></i>Tenant
                    @else <i class="bi bi-ticket-fill me-1"></i>Member @endif
                </span>
            </div>
        </div>
        @else
        {{-- Guest - tombol auth --}}
        <div class="mnp-logo-row">
            <img src="{{ asset('storage/images/logo/logo.png') }}" alt="FiveFest" class="mnp-logo-img">
            <span class="mnp-logo-text">FIVE<span style="color:#fbbf24;">FEST</span></span>
        </div>
        <div class="mnp-auth-buttons">
            <a href="{{ route('register') }}" class="mnp-btn-daftar">Daftar</a>
            <a href="{{ route('login') }}" class="mnp-btn-masuk">Masuk</a>
        </div>
        @endauth
    </div>

    {{-- === BODY PANEL === --}}
    <div class="mnp-body">

        {{-- Search bar di dalam panel --}}
        <div class="mnp-search-wrap">
            <form action="{{ route('search') }}" method="GET" class="mnp-search-bar" style="text-decoration:none;">
            <button type="submit" style="background:none;border:none;padding:0;flex-shrink:0;cursor:pointer;">
                <i class="bi bi-search" style="color:#94a3b8;font-size:0.9rem;"></i>
            </button>                
    <input type="text" name="query" placeholder="Cari event, artist, atau vanue..."
                    style="border: none; background:transparent; outline:none; width:100%; font-size:0.88rem; color:#1e293b">
            </form>
        </div>

        {{-- Section: Menu Utama --}}
        <div class="mnp-section-label">Menu Utama</div>

        <a href="{{ url('/') }}" class="mnp-nav-item {{ request()->is('/') ? 'active' : '' }}">
            <div class="mnp-icon-wrap" style="background:#f5f3ff;">
                <i class="bi bi-house-fill" style="color:#7c3aed;"></i>
            </div>
            <span>Home</span>
            <i class="bi bi-chevron-right mnp-arrow"></i>
        </a>

        <a href="{{ route('search') }}" class="mnp-nav-item">
            <div class="mnp-icon-wrap" style="background:#fdf4ff;">
                <i class="bi bi-ticket-perforated-fill" style="color:#a855f7;"></i>
            </div>
            <span>Semua Event</span>
            <i class="bi bi-chevron-right mnp-arrow"></i>
        </a>

        <a href="{{ url('/about') }}" class="mnp-nav-item {{ request()->is('about') ? 'active' : '' }}">
            <div class="mnp-icon-wrap" style="background:#eff6ff;">
                <i class="bi bi-info-circle-fill" style="color:#3b82f6;"></i>
            </div>
            <span>About</span>
            <i class="bi bi-chevron-right mnp-arrow"></i>
        </a>

        <a href="{{ url('/contact') }}" class="mnp-nav-item {{ request()->is('contact') ? 'active' : '' }}">
            <div class="mnp-icon-wrap" style="background:#f0fdf4;">
                <i class="bi bi-telephone-fill" style="color:#22c55e;"></i>
            </div>
            <span>Kontak</span>
            <i class="bi bi-chevron-right mnp-arrow"></i>
        </a>

        {{-- Section: Berdasarkan role --}}
        @auth
        <div class="mnp-section-label">Akun Saya</div>

        <a href="{{ route('profile') }}" class="mnp-nav-item">
            <div class="mnp-icon-wrap" style="background:#f5f3ff;">
                <i class="bi bi-person-fill" style="color:#7c3aed;"></i>
            </div>
            <span>Profil Saya</span>
            <i class="bi bi-chevron-right mnp-arrow"></i>
        </a>


        @if(Auth::user()->role === 'admin')
        <a href="{{ route('admin.dashboard') }}" class="mnp-nav-item">
            <div class="mnp-icon-wrap" style="background:#f0fdf4;">
                <i class="bi bi-shield-check" style="color:#16a34a;"></i>
            </div>
            <span>Admin Panel</span>
            <i class="bi bi-chevron-right mnp-arrow"></i>
        </a>

        @elseif (Auth::user()->role === 'owner')
        <a href="{{ route('owner.dashboard') }}" class="mnp-nav-item">
            <div class="mnp-icon-wrap" style="background: #f5f3ff">
                <i class="bi bi-speedometer2" style="color:#7c3aed;"></i>
            </div>
            <span>Owner Dashboard</span>
            <i class="bi bi-chevron-right mnp-arrow"></i>
        </a>

        @elseif(Auth::user()->role === 'vendor')
        <a href="{{ route('vendor.dashboard') }}" class="mnp-nav-item">
            <div class="mnp-icon-wrap" style="background:#eff6ff;">
                <i class="bi bi-grid-1x2-fill" style="color:#3b82f6;"></i>
            </div>
            <span>Vendor Dashboard</span>
            <i class="bi bi-chevron-right mnp-arrow"></i>
        </a>

        @elseif(Auth::user()->role === 'vendor_staff')
        <a href="{{ route('vendor.staff.scanner') }}" class="mnp-nav-item">
            <div class="mnp-icon-wrap" style="background:#f5f3ff;">
                <i class="bi bi-qr-code-scan" style="color:#7c3aed;"></i>
            </div>
            <span>Scanner Merchandise</span>
            <i class="bi bi-chevron-right mnp-arrow"></i>
        </a>

        @elseif(Auth::user()->role === 'tenant')
        <a href="{{ route('tenant.booths.index') }}" class="mnp-nav-item">
            <div class="mnp-icon-wrap" style="background:#fff7ed;">
                <i class="bi bi-shop" style="color:#f97316;"></i>
            </div>
            <span>Status Booth</span>
            <i class="bi bi-chevron-right mnp-arrow"></i>
        </a>

        @else
        <a href="{{ route('my-tickets') }}" class="mnp-nav-item">
            <div class="mnp-icon-wrap" style="background:#fdf4ff;">
                <i class="bi bi-ticket-perforated-fill" style="color:#a855f7;"></i>
            </div>
            <span>Tiket Saya</span>
            <i class="bi bi-chevron-right mnp-arrow"></i>
        </a>

        <a href="{{ route('my-messages') }}" class="mnp-nav-item">
            <div class="mnp-icon-wrap" style="background:#f0f9ff;">
                <i class="bi bi-envelope-fill" style="color:#0ea5e9;"></i>
            </div>
            <span>Pesan</span>
            @php
                $mobileRepliedCount = \App\Models\ContactMessage::where('email', Auth::user()->email)
                    ->where('status', 'replied')
                    ->where('is_read_by_user', false)
                    ->count();
            @endphp
            @if($mobileRepliedCount > 0)
                <span class="mnp-badge">{{ $mobileRepliedCount }}</span>
            @else
                <i class="bi bi-chevron-right mnp-arrow"></i>
            @endif
        </a>
        @endif

        @else
        {{-- Guest: extra links --}}
        <div class="mnp-section-label">Untuk Kamu</div>

        <a href="{{ route('vendor.landing') }}" class="mnp-nav-item">
            <div class="mnp-icon-wrap" style="background:#fef9c3;">
                <i class="bi bi-plus-circle-fill" style="color:#ca8a04;"></i>
            </div>
            <span>Buat Event</span>
            <i class="bi bi-chevron-right mnp-arrow"></i>
        </a>

        <a href="{{ route('register.tenant.show') }}" class="mnp-nav-item">
            <div class="mnp-icon-wrap" style="background:#fff7ed;">
                <i class="bi bi-shop" style="color:#f97316;"></i>
            </div>
            <span>Join Tenant</span>
            <i class="bi bi-chevron-right mnp-arrow"></i>
        </a>
        @endauth

        {{-- Pusat Bantuan (selalu tampil) --}}
        <div class="mnp-section-label">Lainnya</div>
        <a href="{{ url('my-messages') }}" class="mnp-nav-item">
            <div class="mnp-icon-wrap" style="background:#f0f9ff;">
                <i class="bi bi-envelope-fill" style="color:#0ea5e9;"></i>
            </div>
            <span>Pusat Bantuan</span>
            <i class="bi bi-chevron-right mnp-arrow"></i>
        </a>

    </div>

    {{-- === FOOTER PANEL (logout) === --}}
    @auth
    <div class="mnp-footer">
        <form action="{{ route('logout') }}" method="POST" id="logoutFormMobile">
            @csrf
            <button type="button" onclick="confirmLogout('logoutFormMobile')" class="mnp-logout-btn">
                <i class="bi bi-box-arrow-right me-2"></i> Keluar dari Akun
            </button>
        </form>
    </div>
    @endauth

</div>{{-- end #mobileNavPanel --}}


{{-- ============================================================
     CSS NAVBAR (DESKTOP TETAP + MOBILE PANEL)
     ============================================================ --}}
<style>
    /* UTILS & VARS */
    :root {
        --ff-gradient: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
        --ff-gradient-blue: linear-gradient(135deg, #22d3ee 0%, #6366f1 100%);
        --ff-purple-soft: #f5f3ff;
    }

    /* ====== NAVBAR BASE ====== */
    .main-navbar {
        background: linear-gradient(90deg, #4c1d95 0%, #6d28d9 100%) !important;
        backdrop-filter: blur(10px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        border-bottom: 1px solid rgba(255,255,255,0.1);
        transition: all 0.3s ease;
        z-index: 1055 !important;
    }

    .main-navbar .dropdown-menu {
        position: fixed !important;
        z-index: 9999 !important;
    }

    .main-navbar .dropdown {
        position: relative;
    }
    

    /* ====== LOGO ====== */
    .logo-wrapper {
        padding: 2px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 0 15px rgba(255,255,255,0.3);
    }
    .brand-logo { width: 38px; height: 38px; border-radius: 50%; object-fit: cover; }
    .brand-name { font-weight: 900; letter-spacing: 2px; color: #fff; font-size: 1.4rem; line-height: 1; }
    .text-highlight {
        background: var(--ff-gradient-blue);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        filter: drop-shadow(0 0 10px rgba(34,211,238,0.4));
    }
    .brand-tagline { font-size: 0.65rem; text-transform: uppercase; color: rgba(255,255,255,0.7); letter-spacing: 1.5px; margin-top: 2px; }

    /* ====== DESKTOP NAV LINKS ====== */
    .nav-custom .nav-link {
        color: rgba(255,255,255,0.8) !important;
        font-weight: 500;
        padding: 8px 20px !important;
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
        display: inline-flex; align-items: center; white-space: nowrap;
    }
    .nav-custom .nav-link:hover, .nav-custom .nav-link.active {
        background: var(--ff-gradient) !important;
        color: #fff !important;
        font-weight: 700;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(168,85,247,0.5);
    }
    .nav-custom .nav-link i { transition: transform 0.4s cubic-bezier(0.68,-0.55,0.265,1.55); }
    .nav-custom .nav-link:hover i { transform: rotate(90deg) scale(1.2); }

    /* ====== DESKTOP BUTTONS ====== */
    .btn-search-trigger {
        background: rgba(255,255,255,0.1); border: none; color: white;
        width: 40px; height: 40px; border-radius: 12px; transition: 0.3s;
        display: flex; align-items: center; justify-content: center;
    }
    .btn-search-trigger:hover { background: white; color: #6d28d9; transform: rotate(90deg); }
    .btn-login { color: white; text-decoration: none; font-weight: 600; transition: 0.3s; white-space: nowrap; }
    .btn-login:hover { background: var(--ff-gradient); color: #fff; padding: 8px 20px; border-radius: 50px; }
    .btn-register-premium {
        background: #fff; color: #6d28d9; padding: 10px 25px; border-radius: 50px;
        font-weight: 800; text-decoration: none; font-size: 0.9rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1); transition: all 0.3s; white-space: nowrap;
    }
    .btn-register-premium:hover { transform: translateY(-2px); background: var(--ff-gradient); color: #fff; }
    .user-profile-dropdown {
        background: rgba(255,255,255,0.1); padding: 5px 15px 5px 5px;
        border-radius: 50px; color: white; text-decoration: none;
        display: flex; align-items: center; transition: 0.3s;
    }
    .user-profile-dropdown:hover { background: rgba(255,255,255,0.2); color: white; }
    .user-avatar { width: 32px; height: 32px; border-radius: 50%; object-fit: cover; border: 2px solid #fff; }
    .profile-dropdown-menu { border-radius: 15px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.15); padding: 10px; min-width: 200px; }
    .profile-dropdown-menu .dropdown-item { border-radius: 8px; padding: 10px 15px; transition: 0.2s; }
    .profile-dropdown-menu .dropdown-item:hover { background: var(--ff-purple-soft); color: #6d28d9; transform: translateX(5px); }

    /* ====== SEARCH OVERLAY ====== */
    .search-overlay-container {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(15,23,42,0.9); backdrop-filter: blur(15px);
        z-index: 9999; display: none; opacity: 0; transition: all 0.3s ease;
    }
    .search-overlay-container.active { display: block !important; opacity: 1; }
    .search-box-wrapper { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; padding: 20px; }
    .search-card-main { background: white; width: 100%; max-width: 700px; padding: 40px; border-radius: 30px; box-shadow: 0 30px 60px rgba(0,0,0,0.4); }
    .btn-close-search { background: #f1f5f9; border: none; width: 40px; height: 40px; border-radius: 12px; color: #64748b; transition: 0.3s; cursor: pointer; }
    .btn-close-search:hover { background: #e11d48; color: white; transform: rotate(90deg); }
    .input-group-search { position: relative; display: flex; align-items: center; background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 20px; padding: 8px 10px; transition: 0.3s; }
    .input-group-search:focus-within { border-color: #8b5cf6; background: white; box-shadow: 0 0 0 4px rgba(139,92,246,0.1); }
    .search-icon-input { font-size: 1.4rem; color: #94a3b8; margin: 0 15px; }
    .form-control-search { background: transparent; border: none; padding: 12px 0; font-size: 1.1rem; font-weight: 500; color: #1e293b; width: 100%; }
    .form-control-search:focus { outline: none; }
    .btn-search-submit { background: var(--ff-gradient); color: white; border: none; padding: 10px 25px; border-radius: 15px; font-weight: 700; transition: 0.3s; cursor: pointer; }
    .btn-search-submit:hover { transform: scale(1.05); box-shadow: 0 5px 15px rgba(168,85,247,0.4); }
    .quick-tag { text-decoration: none; color: #64748b; background: #f1f5f9; padding: 6px 15px; border-radius: 50px; font-size: 0.85rem; font-weight: 600; transition: 0.3s; }
    .quick-tag:hover { background: #8b5cf6; color: white; }

    /* ====== MOBILE HAMBURGER ====== */
    .mobile-hamburger {
        background: rgba(255,255,255,0.12);
        border: none;
        border-radius: 10px;
        width: 40px; height: 40px;
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        gap: 5px; cursor: pointer; padding: 0;
        transition: background 0.2s;
    }
    .mobile-hamburger:focus { outline: none; box-shadow: none; }
    .mobile-hamburger:hover { background: rgba(255,255,255,0.22); }
    .mobile-hamburger span {
        display: block; width: 20px; height: 2px;
        background: white; border-radius: 2px;
        transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
    }
    .mobile-hamburger.open span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
    .mobile-hamburger.open span:nth-child(2) { opacity: 0; transform: scaleX(0); }
    .mobile-hamburger.open span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

    /* ====== BACKDROP ====== */
    #mobileBackdrop {
        display: none;
        position: fixed; top: 0; left: 0;
        width: 100vw; height: 100vh;
        background: rgba(0,0,0,0.55);
        backdrop-filter: blur(3px);
        z-index: 1065;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    #mobileBackdrop.active { display: block; opacity: 1; }

    /* ====== MOBILE PANEL ====== */
    #mobileNavPanel {
        position: fixed;
        top: 0; right: -100%;
        width: 82vw; max-width: 320px;
        height: 100vh;
        background: #fff;
        z-index: 1070;
        transition: right 0.32s cubic-bezier(0.4,0,0.2,1);
        display: flex; flex-direction: column;
        overflow: hidden;
        box-shadow: -6px 0 30px rgba(0,0,0,0.18);
    }
    #mobileNavPanel.open { right: 0; }

    /* Panel Header */
    .mnp-header {
        background: linear-gradient(135deg, #4c1d95 0%, #6d28d9 100%);
        padding: 20px 20px 24px;
        flex-shrink: 0;
        position: relative;
    }
    .mnp-close-btn {
        position: absolute; top: 14px; right: 14px;
        width: 32px; height: 32px;
        background: rgba(255,255,255,0.18); border: none;
        border-radius: 50%; color: white; font-size: 0.85rem;
        cursor: pointer; display: flex; align-items: center; justify-content: center;
        transition: background 0.2s;
    }
    .mnp-close-btn:hover { background: rgba(255,255,255,0.3); }

    /* Logo row (guest) */
    .mnp-logo-row { display: flex; align-items: center; gap: 10px; margin-bottom: 16px; }
    .mnp-logo-img { width: 32px; height: 32px; border-radius: 50%; object-fit: cover; background: white; padding: 1px; }
    .mnp-logo-text { font-weight: 900; font-size: 1rem; color: white; letter-spacing: -0.5px; }

    /* Auth buttons (guest) */
    .mnp-auth-buttons { display: flex; gap: 8px; }
    .mnp-btn-daftar {
        flex: 1; text-align: center; padding: 11px 0;
        border-radius: 12px; background: rgba(255,255,255,0.15);
        color: white !important; font-weight: 700; text-decoration: none;
        font-size: 0.9rem; border: 1px solid rgba(255,255,255,0.3); transition: 0.2s;
    }
    .mnp-btn-daftar:hover { background: rgba(255,255,255,0.25); }
    .mnp-btn-masuk {
        flex: 1; text-align: center; padding: 11px 0;
        border-radius: 12px; background: #fbbf24;
        color: #1e1b4b !important; font-weight: 800; text-decoration: none;
        font-size: 0.9rem; transition: 0.2s;
    }
    .mnp-btn-masuk:hover { background: #f59e0b; }

    /* User info (auth) */
    .mnp-user-info { display: flex; align-items: center; gap: 12px; }
    .mnp-avatar img { width: 46px; height: 46px; border-radius: 50%; border: 2px solid rgba(255,255,255,0.4); flex-shrink: 0; }
    .mnp-user-name { color: white; font-weight: 800; font-size: 0.95rem; line-height: 1.2; }
    .mnp-user-email { color: rgba(255,255,255,0.6); font-size: 0.72rem; margin-top: 2px; word-break: break-all; }
    .mnp-role-badge {
        display: inline-block; margin-top: 6px;
        background: rgba(255,255,255,0.15); color: rgba(255,255,255,0.9);
        font-size: 0.7rem; font-weight: 700; padding: 3px 10px; border-radius: 50px;
    }

    /* Panel Body */
    .mnp-body { flex: 1; overflow-y: auto; padding: 8px 0; }
    .mnp-body::-webkit-scrollbar { width: 3px; }
    .mnp-body::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }

    /* Search in panel */
    .mnp-search-wrap { padding: 12px 16px 4px; }
    .mnp-search-bar {
        display: flex; align-items: center; gap: 10px;
        background: #f8fafc; border: 1.5px solid #e2e8f0;
        border-radius: 50px; padding: 10px 18px;
        text-decoration: none; color: #94a3b8; font-size: 0.88rem;
        transition: 0.2s;
    }
    .mnp-search-bar:hover { border-color: #a855f7; color: #7c3aed; background: #faf5ff; }
    .mnp-search-bar i { font-size: 0.9rem; }

    /* Section label */
    .mnp-section-label {
        padding: 14px 20px 4px;
        font-size: 0.67rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 1.5px; color: #94a3b8;
    }

    /* Nav items */
    .mnp-nav-item {
        display: flex; align-items: center; gap: 13px;
        padding: 11px 20px;
        color: #1e293b !important; text-decoration: none !important;
        font-weight: 600; font-size: 0.9rem;
        border-bottom: 1px solid #f8fafc;
        transition: all 0.18s ease;
    }
    .mnp-nav-item:hover, .mnp-nav-item.active {
        background: #f8f5ff;
        padding-left: 24px;
        color: #7c3aed !important;
    }
    .mnp-nav-item.active .mnp-icon-wrap { transform: scale(1.1); }

    .mnp-icon-wrap {
        width: 36px; height: 36px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; font-size: 1rem;
        transition: transform 0.2s;
    }
    .mnp-nav-item span { flex: 1; }

    .mnp-arrow { color: #cbd5e1; font-size: 0.72rem; flex-shrink: 0; }

    /* Badge notif */
    .mnp-badge {
        background: #e11d48; color: white;
        font-size: 0.68rem; font-weight: 800;
        padding: 2px 7px; border-radius: 50px; flex-shrink: 0;
    }

    /* Panel Footer */
    .mnp-footer {
        padding: 14px 16px;
        border-top: 1px solid #f1f5f9;
        flex-shrink: 0;
        background: white;
    }
    .mnp-logout-btn {
        width: 100%; padding: 12px;
        border-radius: 12px; border: 1.5px solid #fecdd3;
        background: #fff1f2; color: #e11d48;
        font-weight: 700; font-size: 0.9rem;
        cursor: pointer; display: flex; align-items: center; justify-content: center;
        transition: 0.2s;
    }
    .mnp-logout-btn:hover { background: #ffe4e6; }

    /* ====== HIDE PANEL ON DESKTOP ====== */
    @media (min-width: 992px) {
        #mobileNavPanel,
        #mobileBackdrop,
        .mobile-hamburger { display: none !important; }
    }

    @media (max-width: 991px) {
        .mobile-hamburger {
            position: relative;
            z-index: 1061 !important;
        }
    }
</style>

{{-- ============================================================
     JAVASCRIPT
     ============================================================ --}}
<script>
    // === Search Overlay ===
    function toggleSearch() {
        const overlay = document.getElementById('searchBarOverlay');
        overlay.classList.toggle('active');
        if (overlay.classList.contains('active')) {
            setTimeout(() => {
                const input = document.querySelector('.form-control-search');
                if (input) input.focus();
            }, 100);
        }
    }
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            const overlay = document.getElementById('searchBarOverlay');
            if (overlay && overlay.classList.contains('active')) toggleSearch();
        }
    });

    // === Mobile Panel ===
    (function () {
        var toggleBtn  = document.getElementById('mobileMenuToggle');
        var closeBtn   = document.getElementById('mobileMenuClose');
        var backdrop   = document.getElementById('mobileBackdrop');
        var panel      = document.getElementById('mobileNavPanel');

        function openPanel() {
            panel.classList.add('open');
            backdrop.classList.add('active');
            toggleBtn && toggleBtn.classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closePanel() {
            panel.classList.remove('open');
            backdrop.classList.remove('active');
            toggleBtn && toggleBtn.classList.remove('open');
            document.body.style.overflow = '';
        }

        if (toggleBtn) toggleBtn.addEventListener('click', openPanel);
        if (closeBtn)  closeBtn.addEventListener('click', closePanel);
        if (backdrop)  backdrop.addEventListener('click', closePanel);

        // Tutup saat klik link di panel
        if (panel) {
            panel.querySelectorAll('a').forEach(function (link) {
                link.addEventListener('click', closePanel);
            });
        }
    })();

    // === Logout Confirmation ===
function confirmLogout(formId) {
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
            document.getElementById(formId).submit();
        }
    });
}

// Fix dropdown position agar tidak terpotong overflow
document.querySelectorAll('.main-navbar .dropdown').forEach(function(dropdown) {
    dropdown.addEventListener('show.bs.dropdown', function() {
        const toggle = this.querySelector('.dropdown-toggle');
        const menu = this.querySelector('.dropdown-menu');
        const rect = toggle.getBoundingClientRect();

        document.body.appendChild(menu);
        menu.style.position = 'fixed';
        menu.style.top = (rect.bottom + 2) + 'px';
        menu.style.left = 'auto';
        menu.style.right = (window.innerWidth - rect.right) + 'px';
        menu.style.zIndex = '999999';
        menu.style.display = 'block';
    });

    dropdown.addEventListener('hide.bs.dropdown', function() {
        const menu = document.querySelector('.profile-dropdown-menu');
        if (menu) {
            this.appendChild(menu);
            menu.style.cssText = '';
        }
    });
});
</script>