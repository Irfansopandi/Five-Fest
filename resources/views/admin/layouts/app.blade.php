<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - Five Fest</title>

    <link rel="icon" type="image/png" href="{{ asset('storage/images/logo/logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow-x: hidden; 
            background: #f8f9fa;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            width: 260px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: width 0.3s ease, transform 0.3s ease;
            z-index: 1000;
            overflow-y: auto;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            border-right: 1px solid rgba(255,255,255,0.05);
        }

        .sidebar.collapsed { width: 80px; }

        /* ── Brand header ── */
        .sidebar-header {
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            flex-shrink: 0;
            padding: 20px 15px !important;
        }

        .sidebar.collapsed .sidebar-header {
            padding: 24px 15px;
            justify-content: center;
        }

        .logo-img {
            width: 50px; height: 50px;
            border-radius: 50%;
            flex-shrink: 0;
            object-fit: cover;
            border: 3px solid #8b5cf6;
            box-shadow: 0 0 15px rgba(139, 92, 246, 0.4);
        }

        .logo-text {
            font-weight: 800;
            letter-spacing: 1px;
            color: #ffffff;
            font-size: 1.2rem;
            white-space: nowrap;
            transition: all 0.3s ease;
            flex-grow: 1;
            margin: 0 0 0 12px;
            text-shadow: 0 0 10px rgba(0,229,255,0.4);
        }

        .sidebar.collapsed .logo-text { display: none; }

        .toggle-sidebar-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            border: none;
            width: 32px; height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            cursor: pointer;
            flex-shrink: 0;
        }

        .toggle-sidebar-btn:hover { background: rgba(255,255,255,0.3); }

        /* ── User card ── */
        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            margin: 16px;
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 14px;
            flex-shrink: 0;
        }

        .user-avatar {
            width: 38px; height: 38px;
            border-radius: 10px;
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

        .sidebar.collapsed .sidebar-user {
            margin: 16px 8px;
            padding: 10px;
            justify-content: center;
        }

        .sidebar.collapsed .user-info { display: none; }

        /* ── Navigation ── */
        .sidebar-nav {
            padding: 0 12px 24px;
            flex-grow: 1;
            overflow-y: auto;
        }

        .sidebar.collapsed .sidebar-nav { padding: 0 8px; }

        .nav-section-label {
            font-size: 0.65rem;
            font-weight: 800;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.45);
            padding: 24px 12px 8px;
            white-space: nowrap;
        }

        .sidebar.collapsed .nav-section-label { display: none; }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 10px 14px;
            margin-bottom: 2px;
            border-radius: 10px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .sidebar.collapsed .nav-link {
            padding: 12px;
            justify-content: center;
        }

        .sidebar .nav-link i {
            font-size: 1.1rem;
            min-width: 24px;
            flex-shrink: 0;
        }

        .sidebar .nav-link span {
            margin-left: 10px;
        }

        .sidebar.collapsed .nav-link span,
        .sidebar.collapsed .nav-link .ms-auto { display: none; }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.2);
        }

        .sidebar .nav-link.active {
            font-weight: 600;
            background: rgba(255,255,255,0.25);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        /* Logout */
        .nav-link.danger { 
            color: #fda4af !important; 
            margin-top: 10px;
        }
        .nav-link.danger:hover { background: rgba(244,63,94,0.15) !important; }

        /* ── Main content ── */
        .main-content {
            margin-left: 260px;
            transition: margin-left 0.3s ease;
            padding: 32px;
            min-height: 100vh;
        }

        .main-content.no-padding {
            padding: 0;
            height: 100vh;
            overflow: hidden;
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
            border-radius: 12px;
            display: none;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            cursor: pointer;
        }

        .sidebar-overlay {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.4);
            backdrop-filter: blur(4px);
            z-index: 999;
            display: none;
        }

        .sidebar-overlay.show { display: block; }

        @media (max-width: 768px) {
            .mobile-menu-btn { display: flex; }
            .sidebar { transform: translateX(-100%); width: 260px !important; }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0 !important; padding: 20px; padding-top: 80px; }
            .toggle-sidebar-btn { display: none; }
        }

        /* Utilities */
        .rounded-4 { border-radius: 1rem !important; }
        .fw-800 { font-weight: 800; }
        .fw-700 { font-weight: 700; }
        .fw-600 { font-weight: 600; }
        .text-xs { font-size: 0.75rem; }
        .shadow-sm { box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1) !important; }
        .text-indigo { color: #667eea !important; }
        .bg-indigo { background-color: #667eea !important; }
        .btn-indigo { background-color: #667eea; color: white; transition: all 0.3s; }
        .btn-indigo:hover { background-color: #764ba2; color: white; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3); }

        /* ===== STAT CARDS (GLOBAL) ===== */
        .stat-card {
            border-radius: 14px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            cursor: default;
            overflow: hidden;
            position: relative;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            top: -20px;
            right: -20px;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(255,255,255,0.12);
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        }

        .stat-card--purple {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        .stat-card--blue {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            box-shadow: 0 4px 15px rgba(79, 172, 254, 0.3);
        }
        .stat-card--green {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            box-shadow: 0 4px 15px rgba(67, 233, 123, 0.3);
        }
        .stat-card--orange {
            background: linear-gradient(135deg, #fa7c58 0%, #fb9d3e 100%);
            box-shadow: 0 4px 15px rgba(250, 124, 88, 0.3);
        }
        .stat-card--red {
            background: linear-gradient(135deg, #f43f5e 0%, #e11d48 100%);
            box-shadow: 0 4px 15px rgba(244, 63, 94, 0.3);
        }

        .stat-card__icon {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            flex-shrink: 0;
        }

        .stat-card__body {
            flex-grow: 1;
            min-width: 0;
        }

        .stat-card__label {
            color: rgba(255,255,255,0.85);
            font-size: 0.8rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-card__value {
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
            line-height: 1.2;
            margin: 2px 0 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .stat-card__link {
            color: rgba(255,255,255,0.8);
            font-size: 0.78rem;
            text-decoration: none;
            transition: color 0.2s;
        }

        .stat-card__link:hover {
            color: white;
        }
        .btn-indigo:hover { background-color: #764ba2; color: white; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3); }

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
    <div class="sidebar-header">
        <img src="{{ asset('storage/images/logo/logo.png') }}" alt="Five Fest" class="logo-img">
        <h4 class="logo-text">FIVE FEST</h4>
        <button class="toggle-sidebar-btn" id="toggleSidebar" title="Toggle Sidebar">
            <i class="bi bi-list fs-5" id="toggleIcon"></i>
        </button>
    </div>

    <!-- User card -->
    <div class="sidebar-user">
        <div class="user-avatar">
            @if(auth()->user()->avatar ?? false)
                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="avatar" style="width:100%; height:100%; object-fit:cover;">
            @else
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
            @endif
        </div>
        <div class="user-info">
            <div class="user-name">{{ auth()->user()->name ?? 'Admin' }}</div>
            <div class="user-role">Administrator</div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav">

        <div class="nav-section-label">DASHBOARD</div>

        <a href="{{ route('admin.dashboard') }}"
           class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>
        
        <a href="{{ route('admin.contact.index') }}"
           class="nav-link {{ request()->routeIs('admin.contact*') ? 'active' : '' }}">
            <i class="bi bi-envelope-open"></i>
            <span>Pesan Kontak</span>
        </a>

        <div class="nav-section-label">TRANSAKSI</div>

        <a href="{{ route('admin.sales') }}"
           class="nav-link {{ request()->routeIs('admin.sales') ? 'active' : '' }}">
            <i class="bi-receipt"></i>
            <span>Daftar Transaksi</span>
        </a>

        <a href="{{ route('admin.tickets.index') }}"
           class="nav-link {{ request()->routeIs('admin.tickets*') ? 'active' : '' }}">
            <i class="bi bi-ticket-perforated"></i>
            <span>Daftar Tiket</span>
        </a>

        <a href="{{ route('admin.income') }}"
           class="nav-link {{ request()->routeIs('admin.income') ? 'active' : '' }}">
            <i class="bi bi-wallet2"></i>
            <span>Penghasilan Vendor</span>
        </a>

        <div class="nav-section-label">KEUANGAN PLATFORM</div>

        <a href="{{ route('admin.finance.service-fee') }}"
           class="nav-link {{ request()->routeIs('admin.finance.service-fee*') || request()->routeIs('admin.finance.tenant-service-fee*') ? 'active' : '' }}">
            <i class="bi bi-cash-stack"></i>
            <span>Pajak Jasa Platform</span>
        </a>

        <a href="{{ route('admin.finance.tax') }}"
           class="nav-link {{ request()->routeIs('admin.finance.tax') ? 'active' : '' }}">
            <i class="bi bi-bank"></i>
            <span>Rekening Pajak</span>
        </a>

        <a href="{{ route('admin.refund.index') }}"
            class="nav-link {{ request()->routeIs('admin.refund*') ? 'active' : '' }}">
                <i class="bi bi-arrow-return-left"></i>
                <span>Manajemen Refund</span>
                @php
                    $pendingRefunds = \App\Models\EventTenant::where('refund_status', 'approved')->count();
                @endphp
                @if($pendingRefunds > 0)
                    <span class="badge bg-danger ms-auto rounded-pill" style="font-size:0.65rem;">
                        {{ $pendingRefunds }}
                    </span>
                @endif
            </a>

        <a class="nav-link {{ request()->routeIs('admin.reports.*') && !request()->routeIs('admin.reports.owner.*') ? 'active' : 'collapsed' }}" 
            data-bs-toggle="collapse" href="#laporanCollapse" role="button">
            <i class="bi bi-graph-up"></i>
            <span>Laporan</span>
            <i class="bi bi-chevron-down ms-auto small opacity-50"></i>
        </a>
        <div class="collapse {{ request()->routeIs('admin.reports.*') && !request()->routeIs('admin.reports.owner.*') ? 'show' : '' }}" id="laporanCollapse">
            <div class="ps-4">
                <a href="{{ route('admin.reports.user.form') }}" class="nav-link small py-1">
                    <i class="bi bi-dot"></i> Pengguna
                </a>
                <a href="{{ route('admin.reports.sales.form') }}" class="nav-link small py-1">
                    <i class="bi bi-dot"></i> Penjualan vendor
                </a>
                <a href="{{ route('admin.reports.tenant.form') }}" class="nav-link small py-1">
                    <i class="bi bi-dot"></i> Sewa Booth Tenant
                </a>
            </div>
        </div>
        <a href="{{ route('admin.reports.owner.form') }}"
            class="nav-link {{ request()->routeIs('admin.reports.owner.*') ? 'active' : '' }}">
        <i class="bi bi-send-fill"></i>
        <span>Kirim Laporan</span>
        </a>
        <!-- <hr style="border-color: rgba(255,255,255,0.15);"> -->
        <div class="nav-section-label">AKUN</div>

        <a href="{{ route('admin.users.index') }}"
           class="nav-link {{ request()->routeIs('admin.users*') && !request()->has('verification_status') ? 'active' : '' }}">
            <i class="bi bi-people"></i>
            <span>Manager Pengguna</span>
        </a>
        
        <a href="{{ route('admin.vendor.verification') }}" 
            class="nav-link {{ request()->routeIs('admin.vendor.verification') ? 'active' : '' }}">
            <i class="bi bi-shield-check"></i>
            <span>Verifikasi Vendor</span>
        </a>
        
        <a href="{{ route('admin.tenant.verification') }}" 
           class="nav-link {{ request()->routeIs('admin.tenant.verification') ? 'active' : '' }}">
            <i class="bi bi-shop-window"></i>
            <span>Daftar Tenant</span>
        </a>


        <hr style="border-color: rgba(255, 255, 255, 0.02);">
        <div class="nav-section-label">LAINNYA</div>

        <a href="{{ route('home') }}" class="nav-link">
            <i class="bi bi-house"></i>
            <span>Lihat Situs</span>
        </a>

        <form action="{{ route('logout') }}" method="POST" id="logoutForm">
            @csrf
            <button type="button" onclick="confirmLogout()" class="nav-link danger border-0 bg-transparent w-100 text-start">
                <i class="bi bi-box-arrow-right"></i>
                <span>Keluar Akun</span>
            </button>
        </form>

    </nav>
</div>

<!-- Main Content -->
<div class="main-content" id="mainContent">
    @yield('content')
</div>

<!-- Success/Error Alerts Container -->
@if(session('success'))
<div id="alertContainer" data-alert-type="success" data-alert-message="{{ base64_encode(session('success')) }}"></div>
@endif

@if(session('error'))
<div id="alertContainer" data-alert-type="error" data-alert-message="{{ base64_encode(session('error')) }}"></div>
@endif

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

        localStorage.setItem('adminSidebarCollapsed', isCollapsed);
    });

    mobileMenuBtn.addEventListener('click', () => {
        sidebar.classList.add('show');
        sidebarOverlay.classList.add('show');
    });

    sidebarOverlay.addEventListener('click', () => {
        sidebar.classList.remove('show');
        sidebarOverlay.classList.remove('show');
    });

    window.addEventListener('DOMContentLoaded', function () {
        if (window.innerWidth > 768) {
            const isCollapsed = localStorage.getItem('adminSidebarCollapsed') === 'true';
            if (isCollapsed) {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('expanded');
                toggleIcon.classList.replace('bi-list', 'bi-chevron-right');
            }
        }
    });

    function confirmLogout() {
        Swal.fire({
            title: 'keluar?',
            text: 'Apakah anda yakin ingin keluar?.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Keluar',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then(result => {
            if (result.isConfirmed) {
                document.getElementById('logoutForm').submit();
            }
        });
    }

    // Success/Error Alerts
    document.addEventListener('DOMContentLoaded', function() {
        var alertContainer = document.getElementById('alertContainer');
        if (alertContainer) {
            var alertType = alertContainer.dataset.alertType;
            var encodedMessage = alertContainer.dataset.alertMessage;
            var message = atob(encodedMessage);
            
            if (alertType === 'success') {
                Swal.fire({
                    title: 'Berhasil!',
                    text: message,
                    icon: 'success',
                    confirmButtonColor: '#8b5cf6'
                });
            } else if (alertType === 'error') {
                Swal.fire({
                    title: 'Opps!',
                    text: message,
                    icon: 'error',
                    confirmButtonColor: '#8b5cf6'
                });
            }
        }
    });
</script>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        once: true,
        offset: 50
    });
</script>

@stack('scripts')
</body>
</html>