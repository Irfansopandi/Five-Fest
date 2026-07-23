<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Vendor') - Five Fest</title>

    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nprogress@0.2.0/nprogress.min.css">
    <script src="https://cdn.jsdelivr.net/npm/nprogress@0.2.0/nprogress.min.js"></script>
    <link rel="icon" href="/favicon.ico?v=5" type="image/x-icon">
    <link rel="shortcut icon" href="/favicon.ico?v=5" type="image/x-icon">
    <link rel="apple-touch-icon" href="/favicon.ico?v=5">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        body { overflow-x: hidden; }

        /* ===== SIDEBAR ===== */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            width: 260px;
            background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
            transition: width 0.3s ease, transform 0.3s ease;
            z-index: 1000;
            overflow-y: auto;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
        }

        .sidebar.collapsed { width: 80px; }

        /* ── Brand header ── */
        .sidebar-header {
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            flex-shrink: 0;
            padding: 10px 5px !important;
            gap: 0 !important;
        }

        .sidebar.collapsed .sidebar-header {
            padding: 24px 15px;
            flex-direction: column;
            gap: 8px;
        }

        .logo-img {
            width: 60px; height: 60px;
            border-radius: 50%;
            flex-shrink: 0;
            object-fit: cover;
            border: 3px solid #8b5cf6; /* Primary Purple (Matching Navbar Highlight) */
            box-shadow: 0 0 15px rgba(139, 92, 246, 0.5), /* Glow Ungu Utama */0 0 30px rgba(168, 85, 247, 0.3); /* Soft Neon Violet Layer */
            margin-top: 10px;
            margin-left: 10px;
        }

        .logo-text {
            font-weight: 700;
            letter-spacing: 1px;
            color: #ffffff;
            text-shadow: 0 0 15px rgba(0,229,255,0.6), 0 0 25px rgba(0,229,255,0.3);
            font-size: 1.25rem;
            white-space: nowrap;
            transition: all 0.3s ease;
            flex-grow: 1;
            margin: 0 0 0 8px;
        }

        .sidebar.collapsed .logo-text { display: none; }

        .toggle-sidebar-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            border: none;
            width: 35px; height: 35px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            cursor: pointer;
            flex-shrink: 0;
            margin-left: 2px;
        }

        .toggle-sidebar-btn:hover { background: rgba(255,255,255,0.3); }

        /* ── User card (gaya Lokét) ── */
        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 14px;
            margin: 12px 12px 4px;
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 12px;
            flex-shrink: 0;
        }

        .user-avatar {
            width: 38px; height: 38px;
            border-radius: 9px;
            background: rgba(255,255,255,0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            font-weight: 700;
            color: white;
            flex-shrink: 0;
            overflow: hidden;
            border: 2px solid rgba(255,255,255,0.3);
        }

        .user-avatar img { width: 100%; height: 100%; object-fit: cover; }

        .user-info { overflow: hidden; flex: 1; }

        .user-name {
            font-size: 0.85rem;
            font-weight: 600;
            color: white;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-role {
            font-size: 0.7rem;
            color: rgba(255,255,255,0.6);
            margin-top: 1px;
        }

        .user-chevron {
            color: rgba(255,255,255,0.5);
            font-size: 0.8rem;
            flex-shrink: 0;
        }

        .sidebar.collapsed .sidebar-user {
            margin: 12px 8px 4px;
            padding: 10px;
            justify-content: center;
        }

        .sidebar.collapsed .user-info,
        .sidebar.collapsed .user-chevron { display: none; }

        /* ── Navigation ── */
        .sidebar-nav {
            padding: 8px 12px 12px;
            flex-grow: 1;
            overflow-y: auto;
        }

        .sidebar.collapsed .sidebar-nav { padding: 8px; }

        /* Section label */
        .nav-section-label {
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.45);
            padding: 14px 10px 5px;
            white-space: nowrap;
            transition: opacity 0.2s, padding 0.2s;
        }

        .sidebar.collapsed .nav-section-label {
            opacity: 0;
            height: 0;
            padding: 0;
            overflow: hidden;
        }

        /* Nav links */
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 10px 12px;
            margin-bottom: 2px;
            border-radius: 8px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            white-space: nowrap;
            text-decoration: none;
            font-size: 0.875rem;
        }

        .sidebar.collapsed .nav-link {
            padding: 11px;
            justify-content: center;
        }

        .sidebar .nav-link i {
            min-width: 20px;
            font-size: 1.05rem;
            flex-shrink: 0;
            text-align: center;
        }

        .sidebar .nav-link span {
            margin-left: 10px;
            transition: opacity 0.2s ease;
        }

        .sidebar.collapsed .nav-link span { display: none; }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.2);
        }

        /* Divider */
        .sidebar hr {
            margin: 8px 0;
            border-color: rgba(255,255,255,0.15);
            opacity: 1;
        }

        .sidebar.collapsed hr { margin: 8px 5px; }

        /* Logout */
        #logoutForm { margin: 0; }
        #logoutForm button { width: 100%; text-align: left; }

        .nav-link.danger { color: rgba(255,180,180,0.9) !important; }
        .nav-link.danger:hover { background: rgba(239,68,68,0.15) !important; }

        /* ── Main content ── */
        .main-content {
            background: #f8f9fa;
            min-height: 100vh;
            margin-left: 260px;
            transition: margin-left 0.3s ease;
            padding: 20px;
        }

        .main-content.expanded { margin-left: 80px; }

        /* Mobile button */
        .mobile-menu-btn {
            position: fixed;
            top: 15px; left: 15px;
            z-index: 999;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            width: 45px; height: 45px;
            border-radius: 10px;
            display: none;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            cursor: pointer;
        }

        .sidebar-overlay {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
            display: none;
        }

        .sidebar-overlay.show { display: block; }

        @media (max-width: 768px) {
            .mobile-menu-btn { display: flex; }

            .sidebar {
                transform: translateX(-100%);
                width: 260px !important;
            }

            .sidebar.show { transform: translateX(0); }

            .sidebar.collapsed {
                transform: translateX(-100%);
                width: 260px !important;
            }

            .main-content {
                margin-left: 0 !important;
                padding-top: 70px;
            }

            .main-content.expanded { margin-left: 0 !important; }

            .toggle-sidebar-btn { display: none; }
        }

        /* Scrollbar */
        .sidebar-nav::-webkit-scrollbar { width: 5px; }
        .sidebar-nav::-webkit-scrollbar-track { background: rgba(255,255,255,0.05); }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 10px; }
        .sidebar-nav::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.3); }
    </style>
</head>
<body>

<!-- Mobile button -->
<button class="mobile-menu-btn" id="mobileMenuBtn">
    <i class="bi bi-list fs-4"></i>
</button>

<!-- Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">

    <!-- Brand -->
    <div class="sidebar-header px-4 pt-4">
        <img src="{{ asset('storage/images/logo/logo.png') }}" alt="Five Fest" class="logo-img me-2">
        <h4 class="logo-text">FIVE FEST</h4>
        <button class="toggle-sidebar-btn" id="toggleSidebar" title="Toggle Sidebar">
            <i class="bi bi-list fs-5" id="toggleIcon"></i>
        </button>
    </div>

    <!-- User card -->
    <div class="sidebar-user">
        <div class="user-avatar">
            @if(auth()->user()->avatar ?? false)
                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="avatar">
            @else
                {{ strtoupper(substr(auth()->user()->name ?? 'V', 0, 1)) }}
            @endif
        </div>
        <div class="user-info">
            <div class="user-name">{{ auth()->user()->name ?? 'Pengguna' }}</div>
            <div class="user-role">{{ ucfirst(auth()->user()->role ?? 'Guest') }}</div>
        </div>
        <i class="bi bi-chevron-expand user-chevron"></i>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav">

        <div class="nav-section-label">Menu Utama</div>

        @if(auth()->user()->role === 'vendor')
            <a href="{{ route('vendor.dashboard') }}"
               class="nav-link {{ request()->routeIs('vendor.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('vendor.events.index') }}" 
               class="nav-link {{ request()->routeIs('vendor.events.*') ? 'active' : '' }}">
                <i class="bi bi-calendar-event"></i>
                <span>Event Saya</span>
            </a>

            <a href="{{ route('vendor.tenants.index') }}" 
               class="nav-link {{ request()->routeIs('vendor.tenants.*') ? 'active' : '' }}">
                <i class="bi bi-shop-window"></i>
                <span>Pengajuan Tenant</span>
            </a>
            
            <div class="nav-section-label">TRANSAKSI</div>

            <a href="{{ route('vendor.bookings.index') }}"
               class="nav-link {{ request()->routeIs('vendor.bookings*') ? 'active' : '' }}">
                <i class="bi bi-ticket-perforated"></i>
                <span>Transaksi Tiket</span>
            </a>
            
            <a href="{{ route('vendor.pengguna-tiket') }}"
               class="nav-link {{ request()->routeIs('vendor.pengguna-tiket') ? 'active' : '' }}">
                <i class="bi bi-people"></i>
                <span>Pengguna Tiket</span>
            </a>

            <a href="{{ route('vendor.laporan') }}"
               class="nav-link {{ request()->routeIs('vendor.laporan') ? 'active' : '' }}">
                <i class="bi bi-graph-up"></i>
                <span>Laporan</span>
            </a>

            <a href="{{ route('vendor.merchandise.collection') }}"
                class="nav-link {{ request()->routeIs('vendor.merchandise.collection') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i>
                <span>Rekap Merchandise</span>
            </a>

            <a href="{{ route('vendor.scanner') }}"
               class="nav-link {{ request()->routeIs('vendor.scanner') ? 'active' : '' }}">
                <i class="bi bi-qr-code-scan"></i>
                <span>Scanner</span>
            </a>

            <div class="nav-section-label">TIM</div>

            <a href="{{ route('vendor.staff.index') }}"
            class="nav-link {{ request()->routeIs('vendor.staff.*') ? 'active' : '' }}">
                <i class="bi bi-person-badge"></i>
                <span>Manajemen Staf</span>
            </a>
        @endif

        {{-- ← Sidebar khusus vendor_staff --}}
        @if(auth()->user()->role === 'vendor_staff')
            <a href="{{ route('vendor.staff.scanner') }}"
            class="nav-link {{ request()->routeIs('vendor.staff.scanner') ? 'active' : '' }}">
                <i class="bi bi-qr-code-scan"></i>
                <span>Scanner Merchandise</span>
            </a>

            {{-- Info vendor induk --}}
            <div class="nav-section-label">INFO</div>
            <div class="nav-link" style="cursor:default; opacity:.7; font-size:.8rem;">
                <i class="bi bi-building"></i>
                <span>{{ auth()->user()->parentVendor->name ?? 'Vendor' }}</span>
            </div>
        @endif

        @if(auth()->user()->role === 'tenant')
            <a href="{{ route('tenant.booths.index') }}" 
               class="nav-link {{ request()->routeIs('tenant.booths.*') ? 'active' : '' }}">
                <i class="bi bi-shop"></i>
                <span>Status Booth</span>
            </a>
        @endif

        @if(auth()->user()->role === 'vendor')
            <hr>
            <div class="nav-section-label">Akun</div>

            <a href="{{ route('vendor.informasi-dasar') }}"
              class="nav-link {{ request()->routeIs('vendor.informasi-dasar') ? 'active': '' }}">
              <i class="bi bi-person-gear"></i>
              <span>Informasi Dasar</span>
            </a>

            <a href="{{ route('vendor.informasi-legal') }}"
              class="nav-link {{ request()->routeIs('vendor.informasi-legal') ? 'active': '' }}">
              <i class="bi bi-shield-check"></i>
              <span>Informasi Legal</span>
            </a>

            <a href="{{ route('vendor.rekening') }}"
              class="nav-link {{ request()->routeIs('vendor.rekening') ? 'active': '' }}">
              <i class="bi bi-credit-card"></i>
              <span>Rekening</span>
            </a>
        @endif
        <hr>
        <div class="nav-section-label">Lainnya</div>

        @php
            $lihatSitusUrl = auth()->user()->role === 'vendor_staff'
                ? route('home')          // staff -> homepage biasa
                : route('vendor.home');  // vendor -> halaman home vendor
        @endphp
        <a href="{{ $lihatSitusUrl }}" class="nav-link">
            <i class="bi bi-house"></i>
            <span>Lihat Situs</span>
        </a>

        <form action="{{ route('logout') }}" method="POST" id="logoutForm">
            @csrf
            <button type="button" onclick="confirmLogout()"
                class="nav-link danger border-0 bg-transparent">
                <i class="bi bi-box-arrow-right"></i>
                <span>Keluar</span>
            </button>
        </form>

    </nav>
</div>

<!-- Main Content -->
<div class="main-content" id="mainContent">
    @php
        $vStatus = auth()->user()->verification_status ?? 'pending';
        $isUnverified = auth()->user()->role === 'vendor' && $vStatus !== 'verified';
        $isInfoPage = request()->routeIs('vendor.informasi-dasar') || request()->routeIs('vendor.informasi-legal') || request()->routeIs('vendor.rekening');
        $shouldBlur = $isUnverified && !$isInfoPage;
    @endphp

   @if($shouldBlur)
    <div style="position: relative; min-height: calc(100vh - 40px);">
        <div class="unverified-container" id="unverifiedOverlay">
            <div class="card border-0 shadow-lg p-4 text-center unverified-card">
                @if($vStatus === 'rejected')
                    <div class="mb-3 text-danger">
                        <i class="bi bi-x-circle-fill" style="font-size: 4rem;"></i>
                    </div>
                    <h3 class="fw-bold mb-2">Verifikasi Ditolak</h3>
                    <p class="text-muted mb-3">Mohon maaf, berkas verifikasi kamu ditolak oleh admin.</p>
                    <div class="alert alert-danger rounded-4 border-0 mb-4 text-start">
                        <small class="fw-bold d-block mb-1 text-uppercase">Alasan Penolakan:</small>
                        {{ auth()->user()->rejection_reason ?? 'Informasi tidak valid atau dokumen tidak lengkap.' }}
                    </div>
                   <div class="d-grid gap-2">
                        <a href="{{ route('register.vendor.reapply') }}" class="btn rounded-pill py-3 fw-bold"
                        style="background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%); color: white; border: none; box-shadow: 0 4px 15px rgba(124, 58, 237, 0.4);">
                            <i class="bi bi-arrow-clockwise me-2"></i>Daftar Ulang
                        </a>
                    </div>
                @else
                    <div class="mb-3 text-warning">
                        <i class="bi bi-clock-history" style="font-size: 4rem;"></i>
                    </div>
                    <h3 class="fw-bold mb-2">Menunggu Verifikasi</h3>
                    <p class="text-muted mb-4">
                        Halo <strong>{{ auth()->user()->name }}</strong>! Akun kamu sedang dalam proses tinjauan admin.
                        Pastikan data profil dan dokumen legal sudah lengkap ya.
                    </p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('vendor.informasi-dasar') }}" class="btn rounded-pill py-3 fw-bold"
                        style="background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%); color: white; border: none; box-shadow: 0 4px 15px rgba(124, 58, 237, 0.4);">
                            <i class="bi bi-person-gear me-2"></i>Perbarui Informasi Dasar
                        </a>
                    </div>
                @endif
            </div>
        </div>
        <div style="filter: blur(8px); pointer-events: none; user-select: none;">
            @yield('content')
        </div>
    </div>
    @else
        @yield('content')
    @endif
</div>


<style>
    .unverified-container {
        position: fixed;
        top: 0;
        left: 260px;
        width: calc(100% - 260px);
        height: 100%;
        z-index: 1050;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.4);
        backdrop-filter: blur(4px);
        transition: left 0.3s ease, width 0.3s ease;
    }

    .main-content.expanded .unverified-container {
        left: 80px;
        width: calc(100% - 80px);
    }

    @media (max-width: 768px) {
        .unverified-container {
            left: 0 !important;
            width: 100% !important;
        }
    }

    .unverified-card {
        max-width: 500px;
        border-radius: 24px;
        background: white;
        margin: 20px;
    }

    @media (max-width: 768px) {
        .unverified-container {
            left: 0;
            width: 100%;
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    // NProgress Global Transition Feedback
    if (typeof NProgress !== 'undefined') {
        NProgress.configure({ showSpinner: false, speed: 400 });
        window.addEventListener('beforeunload', function () { NProgress.start(); });
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

    AOS.init({
        duration: 500,
        once: true,
        disable: window.innerWidth < 768
    });
</script>

<script>
    const toggleBtn      = document.getElementById('toggleSidebar');
    const toggleIcon     = document.getElementById('toggleIcon');
    const sidebar        = document.getElementById('sidebar');
    const mainContent    = document.getElementById('mainContent');
    const mobileMenuBtn  = document.getElementById('mobileMenuBtn');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    toggleBtn.addEventListener('click', function () {
        const isCollapsed = sidebar.classList.toggle('collapsed');
        mainContent.classList.toggle('expanded', isCollapsed);

        if (isCollapsed) {
            toggleIcon.classList.replace('bi-list', 'bi-chevron-right');
        } else {
            toggleIcon.classList.replace('bi-chevron-right', 'bi-list');
        }

        localStorage.setItem('vendorSidebarCollapsed', isCollapsed);
    });

    mobileMenuBtn.addEventListener('click', () => {
        sidebar.classList.add('show');
        sidebarOverlay.classList.add('show');
    });

    sidebarOverlay.addEventListener('click', () => {
        sidebar.classList.remove('show');
        sidebarOverlay.classList.remove('show');
    });

    if (window.innerWidth <= 768) {
        document.querySelectorAll('.sidebar .nav-link').forEach(link => {
            link.addEventListener('click', () => {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
            });
        });
    }

    window.addEventListener('DOMContentLoaded', function () {
        if (window.innerWidth > 768) {
            const isCollapsed = localStorage.getItem('vendorSidebarCollapsed') === 'true';
            if (isCollapsed) {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('expanded');
                toggleIcon.classList.replace('bi-list', 'bi-chevron-right');
            }
        }
    });

    if (typeof Swal !== 'undefined') {
        const successMsg = <?php echo json_encode(session('success')); ?>;
        const errorMsg   = <?php echo json_encode(session('error')); ?>;

        if (successMsg) {
            Swal.fire({
                title: 'Berhasil!',
                text: successMsg,
                icon: 'success',
                confirmButtonColor: '#8b5cf6'
            });
        }

        if (errorMsg) {
            Swal.fire({
                title: 'Opps!',
                text: errorMsg,
                icon: 'error',
                confirmButtonColor: '#8b5cf6'
            });
        }
    }

    function confirmLogout() {
        Swal.fire({
            title: 'Keluar?',
            text: 'Apakah Anda yakin ingin keluar?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="bi bi-box-arrow-right me-2"></i>Ya, Keluar',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            allowOutsideClick: false
        }).then(result => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Keluar...',
                    text: 'Harap tunggu',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => Swal.showLoading()
                });
                document.getElementById('logoutForm').submit();
            }
        });
    }
    @if(auth()->user()->show_verified_popup)
        Swal.fire({
            title: '<i class="bi bi-patch-check-fill text-success me-2"></i> Akun Terverifikasi!',
            text: 'Selamat! Akun vendor kamu telah disetujui. Sekarang kamu bisa mulai membuat event.',
            icon: 'success',
            confirmButtonColor: '#8b5cf6',
            confirmButtonText: 'Mulai Sekarang!'
        });
        @php auth()->user()->update(['show_verified_popup' => false]); @endphp
    @endif

</script>


@stack('scripts')
</body>
</html>

