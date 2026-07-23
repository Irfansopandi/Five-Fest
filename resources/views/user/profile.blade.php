@extends('v_layouts.app')

@section('title', 'Profil Saya')

@section('content')

<style>
    :root {
        --primary-purple: #8b5cf6;
        --soft-purple: #f5f3ff;
        --dark-text: #1e293b;
    }

    .bg-main { background-color: #f8fafc; min-height: 100vh; padding: 60px 0; }
    
    .profile-card {
        border: none;
        border-radius: 24px;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.04);
    }

    .list-group-profile .list-group-item {
        border: none;
        margin-bottom: 8px;
        border-radius: 12px !important;
        font-weight: 600;
        color: #64748b;
        transition: 0.3s;
        padding: 12px 20px;
    }

    .list-group-profile .list-group-item.active {
        background-color: var(--soft-purple) !important;
        color: var(--primary-purple) !important;
    }

    .list-group-profile .list-group-item:hover:not(.active) {
        background-color: #f1f5f9;
        color: var(--dark-text);
        padding-left: 25px;
    }

    .form-label { font-weight: 700; color: #475569; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; }
    .form-control-custom {
        border-radius: 14px;
        padding: 12px 18px;
        border: 1.5px solid #e2e8f0;
        font-size: 0.95rem;
        transition: 0.3s;
    }
    .form-control-custom:focus {
        border-color: var(--primary-purple);
        box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.1);
    }

    .avatar-wrapper {
        position: relative;
        width: 120px;
        height: 120px;
        margin: 0 auto;
    }
    .avatar-circle {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #8b5cf6 0%, #d8b4fe 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 3.5rem;
        box-shadow: 0 8px 20px rgba(139, 92, 246, 0.2);
    }

    .btn-gradient {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        color: white;
        border: none;
        border-radius: 14px;
        font-weight: 700;
        padding: 12px 30px;
        transition: 0.3s;
    }
    .btn-gradient:hover { transform: translateY(-2px); box-shadow: 0 8px 15px rgba(139, 92, 246, 0.3); color: white; }

    .list-group-item.text-danger, 
        button.btn-link {
            transition: all 0.3s ease;
    }
    .list-group-item.text-danger:hover,
        button.btn-link:hover {
            background-color: rgba(239, 68, 68, 0.1) !important;
            box-shadow: none !important;
            transform: none !important;
            border-radius: 12px !important;
    }

    .input-password-wrapper {
        position: relative;
    }
    .input-password-wrapper .form-control-custom {
        padding-right: 48px;
    }
    .btn-toggle-password {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        padding: 0;
        font-size: 1.1rem;
        line-height: 1;
        transition: color 0.2s;
    }
    .btn-toggle-password:hover { color: var(--primary-purple); }
    @media (max-width: 991px) {
        #profileTab {
            background: #c1c9d1;
            border-radius: 12px;
            padding: 4px;
            gap: 4px;
        }
        #profileTab .nav-link {
            border-radius: 10px;
            color: #6281ad;
            font-size: 0.85rem;
            padding: 8px 12px;
            background: transparent;
        }
        #profileTab .nav-link.active {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed) !important;
            color: white !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        /* Di mobile, keamanan hidden by default */
        #tab-security { display: none; }

        #tab-info, #tab-security {
            transition: opacity 0.3s ease, transform 0.3s ease;
            opacity: 1;
            transform: translateX(0);
        }
        .tab-hidden {
            display: none !important;
        }
        .tab-fade-out {
            opacity: 0;
            transform: translateX(-20px);
        }
        .tab-fade-in {
            opacity: 0;
            transform: translateX(20px);
        }
    }


</style>

<section class="bg-main">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-white px-4 py-2 rounded-pill shadow-sm d-inline-flex">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
                <li class="breadcrumb-item active fw-bold" style="color: var(--primary-purple);">Profil Saya</li>
            </ol>
        </nav>

        <div class="row g-4">
            {{-- SIDEBAR --}}
            <div class="col-lg-3">
                <div class="card profile-card p-2">
                    <div class="card-body">
                        <div class="text-center mb-4 pt-3">
                            <div class="avatar-wrapper mb-3">
                                <div class="avatar-circle">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                            </div>
                            <h5 class="fw-bold text-dark mb-1">{{ Auth::user()->name }}</h5>
                            <span class="badge rounded-pill mb-4" style="background: var(--soft-purple); color: var(--primary-purple);">
                                <i class="bi bi-shield-check me-1"></i>{{ ucfirst(Auth::user()->role) }}
                            </span>
                        </div>
                        
                        <div class="list-group list-group-profile">
                            <a href="{{ route('profile') }}" class="list-group-item list-group-item-action active">
                                <i class="bi bi-person-vcard me-3"></i>Data Profil
                            </a>

                            {{-- MENU ADMIN --}}
                            @if(Auth::user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action">
                                    <i class="bi bi-speedometer2 me-3"></i>Admin Dashboard
                                </a>

                            {{-- MENU OWNER --}}
                            @elseif (Auth::user()->role === 'owner')
                                <a href="{{ route('owner.dashboard') }}" class="list-group-item list-group-item-action">
                                    <i class="bi bi-speedometer2 me-3"></i>Owner Dashboard
                                </a>

                            {{-- MENU VENDOR --}}
                            @elseif(Auth::user()->role === 'vendor')
                                <a href="{{ route('vendor.dashboard') }}" class="list-group-item list-group-item-action border-0 fw-semibold"
                                     style="border-radius:12px;color:#64748b;padding:12px 20px;transition:0.3s;">
                                    <i class="bi bi-graph-up-arrow me-3"></i>Dashboard Jualan
                                </a>
                            
                            {{-- MENU VENDOR STAFF --}}
                            @elseif(Auth::user()->role === 'vendor_staff')
                            <a href="{{ route('vendor.staff.scanner') }}" class="list-group-item list-group-item-action">
                                <i class="bi bi-qr-code-scan me-3"></i>Scanner Merchandise
                            </a>

                            {{-- MENU USER BIASA & TENANT --}}
                            @else
                                @if(Auth::user()->role === 'tenant')
                                    <a href="{{ route('tenant.booths.index') }}" class="list-group-item list-group-item-action">
                                        <i class="bi bi-shop me-3"></i>Status Booth
                                    </a>
                                @elseif (Auth::user()->role === 'vendor_staff')
                                    <a href="{{ route('vendor.staff.scanner') }}" class="list-group-item list-group-item-action">
                                        <i class="bi bi-qr-code-scan me-3"></i>Scanner Merchandise
                                    </a>
                                @else
                                    <a href="{{ route('my-tickets') }}" class="list-group-item list-group-item-action">
                                        <i class="bi bi-ticket-detailed me-3"></i>Tiket Saya
                                    </a>
                                    <a href="{{ route('order-history') }}" class="list-group-item list-group-item-action">
                                        <i class="bi bi-bag-check me-3"></i>Riwayat Pesanan
                                    </a>
                                @endif
                            @endif

                        </div>

                        <hr class="my-4 opacity-50">

                        <form action="{{ route('logout') }}" method="POST" id="logoutFormProfile" class="px-1">
                            @csrf
                            <button type="button" onclick="confirmLogout('logoutFormProfile')" class="btn btn-link text-danger text-decoration-none fw-bold w-100 text-start" style="padding: 12px 20px;">
                                <i class="bi bi-box-arrow-left me-3"></i>Keluar Akun
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- FORM CONTENT --}}
            <div class="col-lg-9">
                <div class="card profile-card">
                    <div class="card-body p-lg-5">

                        {{-- TAB NAVIGATION (mobile only) --}}
                        <ul class="nav nav-pills d-lg-none mb-4" id="profileTab">
                            <li class="nav-item flex-fill">
                                <button class="nav-link active w-100 fw-bold" onclick="switchTab('info', event)">
                                    <i class="bi bi-person-gear me-1"></i> Profil
                                </button>
                            </li>
                            <li class="nav-item flex-fill">
                                <button class="nav-link w-100 fw-bold" onclick="switchTab('security', event)">
                                    <i class="bi bi-shield-lock me-1"></i> Keamanan
                                </button>
                            </li>
                        </ul>

                        {{-- INFORMASI PROFIL --}}
                        <div id="tab-info">
                            <div class="d-flex align-items-center mb-5">
                                <div class="p-3 rounded-4 bg-primary bg-opacity-10 text-primary me-4">
                                    <i class="bi bi-person-gear fs-2"></i>
                                </div>
                                <div>
                                    <h3 class="fw-bold text-dark mb-1">Informasi Profil</h3>
                                    <p class="text-muted mb-0">Kelola informasi dasar akun <strong>{{ Auth::user()->name }}</strong> di sini.</p>
                                </div>
                            </div>

                            @if(session('success'))
                                <div class="alert alert-success border-0 rounded-4 p-3 mb-4 d-flex align-items-center">
                                    <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                                    <div>{{ session('success') }}</div>
                                </div>
                            @endif

                            @if($errors->any())
                                <div class="alert alert-danger border-0 rounded-4 p-3 mb-4">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('profile.update') }}" method="POST">
                                @csrf @method('PUT')
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label">Nama Lengkap</label>
                                        <input type="text" name="name" class="form-control form-control-custom" value="{{ Auth::user()->name }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Alamat Email</label>
                                        @if(Auth::user()->role === 'vendor')
                                            <input type="email" class="form-control form-control-custom bg-light" value="{{ Auth::user()->email }}" readonly>
                                            <small class="text-info mt-1 d-block"><i class="bi bi-shield-lock me-1"></i>Email dikunci untuk akun Vendor.</small>
                                        @else
                                            <input type="email" name="email" class="form-control form-control-custom" value="{{ Auth::user()->email }}" required>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Nomor WhatsApp</label>
                                        <input type="number" name="phone" class="form-control form-control-custom" value="{{ Auth::user()->phone }}" placeholder="Contoh: 0812xxxx">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Jenis Akun</label>
                                        <input type="text" class="form-control form-control-custom bg-light" value="{{ ucfirst(Auth::user()->role) }}" readonly>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label text-muted opacity-75 small">Terdaftar Sejak: {{ Auth::user()->created_at->format('d F Y') }}</label>
                                    </div>
                                    <div class="col-12 mt-4 text-end">
                                        <button type="submit" class="btn btn-gradient px-5 shadow-sm">Simpan Perubahan</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        {{-- DIVIDER (desktop only) --}}
                        <div class="my-5 border-top opacity-50 d-none d-lg-block"></div>

                        {{-- KEAMANAN --}}
                        <div id="tab-security">
                            <div class="d-flex align-items-center mb-5">
                                <div class="p-3 rounded-4 bg-danger bg-opacity-10 text-danger me-4">
                                    <i class="bi bi-shield-lock fs-2"></i>
                                </div>
                                <div>
                                    <h3 class="fw-bold text-dark mb-1">Keamanan</h3>
                                    <p class="text-muted mb-0">Ubah kata sandi secara berkala biar akun tetap aman.</p>
                                </div>
                            </div>

                            @if(Auth::user()->google_id)
                                {{-- USER GOOGLE: tampilkan notif, form disabled --}}
                                <div class="alert border-0 rounded-4 p-4 d-flex align-items-center gap-3"
                                    style="background: #fef9c3; color: #854d0e;">
                                    <i class="bi bi-google fs-3" style="color: #ea4335;"></i>
                                    <div>
                                        <div class="fw-bold mb-1">Akun terhubung via Google</div>
                                        <div class="small">Kamu login menggunakan Google, sehingga tidak bisa mengubah kata sandi di sini. Kelola keamanan akunmu melalui pengaturan Google.</div>
                                    </div>
                                </div>

                                <form>
                                    <div class="row g-4 mt-1" style="opacity: 0.45; pointer-events: none;">
                                        <div class="col-md-12">
                                            <label class="form-label">Kata Sandi Saat Ini</label>
                                            <div class="input-password-wrapper">
                                                <input type="password" class="form-control form-control-custom" disabled placeholder="••••••••">
                                                <button type="button" class="btn-toggle-password" disabled><i class="bi bi-eye"></i></button>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Kata Sandi Baru</label>
                                            <div class="input-password-wrapper">
                                                <input type="password" class="form-control form-control-custom" disabled placeholder="••••••••">
                                                <button type="button" class="btn-toggle-password" disabled><i class="bi bi-eye"></i></button>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Konfirmasi Kata Sandi</label>
                                            <div class="input-password-wrapper">
                                                <input type="password" class="form-control form-control-custom" disabled placeholder="••••••••">
                                                <button type="button" class="btn-toggle-password" disabled><i class="bi bi-eye"></i></button>
                                            </div>
                                        </div>
                                        <div class="col-12 mt-4 text-end">
                                            <button type="button" class="btn btn-outline-secondary fw-bold px-5 rounded-pill" disabled>Ubah Kata Sandi</button>
                                        </div>
                                    </div>
                                </form>

                            @else
                                {{-- USER BIASA: form aktif --}}
                                <form action="{{ route('password.update') }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="row g-4">
                                        <div class="col-md-12">
                                            <label class="form-label">Kata Sandi Saat Ini</label>
                                            <div class="input-password-wrapper">
                                                <input type="password" name="current_password" id="current_password"
                                                    class="form-control form-control-custom" required>
                                                <button type="button" class="btn-toggle-password" onclick="togglePassword('current_password', this)">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Kata Sandi Baru</label>
                                            <div class="input-password-wrapper">
                                                <input type="password" name="password" id="new_password"
                                                    class="form-control form-control-custom" required>
                                                <button type="button" class="btn-toggle-password" onclick="togglePassword('new_password', this)">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Konfirmasi Kata Sandi</label>
                                            <div class="input-password-wrapper">
                                                <input type="password" name="password_confirmation" id="confirm_password"
                                                    class="form-control form-control-custom" required>
                                                <button type="button" class="btn-toggle-password" onclick="togglePassword('confirm_password', this)">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-12 mt-4 text-end">
                                            <button type="submit" class="btn btn-outline-primary fw-bold px-5 rounded-pill">Ubah Kata Sandi</button>
                                        </div>
                                    </div>
                                </form>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>

function switchTab(tab, e) {
    const isDesktop = window.innerWidth >= 992;
    if (isDesktop) return;

    const info     = document.getElementById('tab-info');
    const security = document.getElementById('tab-security');
    const current  = tab === 'info' ? security : info;
    const next     = tab === 'info' ? info : security;

    current.style.opacity   = '0';
    current.style.transform = 'translateX(-20px)';

    setTimeout(() => {
        current.style.display = 'none';
        next.style.display   = 'block';
        next.style.opacity   = '0';
        next.style.transform = 'translateX(20px)';
        next.offsetHeight;
        next.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
        next.style.opacity    = '1';
        next.style.transform  = 'translateX(0)';
    }, 250);

    document.querySelectorAll('#profileTab .nav-link').forEach(el => el.classList.remove('active'));
    e.target.classList.add('active');
}

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

function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon  = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
}
</script>
@endpush

@endsection