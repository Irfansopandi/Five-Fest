@extends('v_layouts.app')

@section('title', 'Register')

@push('styles')
<style>
    /* Base Setup & Reset Navbar/Footer */
    body.auth-page nav { display: none !important; }
    body.auth-page footer { display: none !important; }

    /* Animasi Bounce Logo */
    @keyframes softBounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-25px); }
    }

    .logo-bounce {
        animation: softBounce 3.5s ease-in-out infinite;
        filter: drop-shadow(0 0 20px rgba(0, 245, 255, 0.4));
    }

    /* Glassmorphism Card */
    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 2rem !important;
    }

    /* Background Dinamis */
    .bg-gradient-animate {
        background: linear-gradient(-45deg, #667eea, #764ba2, #4facfe, #8e37d7);
        background-size: 400% 400%;
        animation: gradientBG 15s ease infinite;
        position: relative;
        overflow: hidden;
    }

    @keyframes gradientBG {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    /* Form Styling */
    .form-control {
        border-radius: 12px !important;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        background-color: #ffffff !important;
        border-color: #8b5cf6 !important;
        box-shadow: 0 0 0 0.25rem rgba(139, 92, 246, 0.15) !important;
    }

    /* Password Toggle Icon */
    .password-wrapper {
        position: relative;
    }

    .toggle-password {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        z-index: 10;
        color: #6c757d;
    }

    .btn-register {
        background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%);
        border: none;
        padding: 0.875rem;
        transition: all 0.3s ease;
    }

    .btn-register:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(139, 92, 246, 0.4);
    }

    /* Google Button Styling */
    .btn-google {
        background: #ffffff;
        color: #444;
        border: 1px solid #ddd;
        padding: 0.875rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-weight: 600;
        font-size: 0.95rem;
    }

    .btn-google:hover {
        background: #f8f9fa;
        border-color: #ccc;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        transform: translateY(-1px);
    }

    .divider {
        display: flex;
        align-items: center;
        text-align: center;
        margin: 1rem 0;
        color: #999;
        font-size: 0.8rem;
    }

    .divider::before,
    .divider::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid #eee;
    }

    .divider:not(:empty)::before { margin-right: .5em; }
    .divider:not(:empty)::after { margin-left: .5em; }

    @media (max-width: 768px) {
        .glass-card {
            width: 90% !important;
            max-width: 360px !important;
            margin: 0 auto !important;
        }
        .glass-card .card-body {
            padding: 1.5rem 1.25rem !important;
        }

        .row .col-md-6 {
            width: 100% !important;
            flex: 0 0 100% !important;
            max-width: 100% !important;
        }

        /* Input lebih compact */
        .form-control-lg { 
            font-size: 0.88rem !important; 
            padding: 0.6rem 1rem !important; 
        }

        .form-label { font-size: 0.78rem !important; margin-bottom: 4px !important; }

        .mb-3 { margin-bottom: 0.6rem !important; }
        .mb-4 { margin-bottom: 0.75rem !important; }

        /* Tombol */
        .btn-register { padding: 0.65rem !important; font-size: 0.88rem !important; }
        .btn-google   { padding: 0.65rem !important; font-size: 0.85rem !important; }

        /* Form check */
        .form-check-label { font-size: 0.78rem !important; }

        /* Text bawah */
        .text-center.text-muted.small { font-size: 0.78rem !important; }

        }
</style>
@endpush

@section('content')

<script>
    document.body.classList.add('auth-page');
</script>

<section class="bg-gradient-animate d-flex align-items-center justify-content-center" style="min-height: 100vh; padding: 2rem 0;">
    <div class="container position-relative" style="z-index: 1;">
        <div class="row g-4 align-items-center justify-content-center">
            
            <!-- LEFT SIDE - INFO -->
            <div class="col-lg-5 d-none d-lg-block text-white text-center" data-aos="fade-right">
                <div class="position-relative d-inline-block mb-4">
                    <img src="{{ asset('storage/images/logo/logo.png') }}"
                         alt="Five Fest Logo"
                         class="img-fluid rounded-circle logo-bounce shadow-lg"
                         style="width: 250px; height: 250px; object-fit: cover; background: #fff; padding: 20px; border: 5px solid rgba(255,255,255,0.2);">
                </div>
                <h2 class="fw-bold mb-3">Gabung <span style="color: #00F5FF;">Five Fest</span></h2>
                <p class="fs-5 opacity-75 mb-4 mx-auto" style="max-width: 400px;">Rasakan kemudahan memesan tiket konser impian dalam satu genggaman.</p>
            </div>

            <!-- RIGHT SIDE - REGISTER FORM -->
            <div class="col-lg-6 col-md-10" data-aos="fade-left">
                <div class="card border-0 shadow-lg glass-card">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-3 d-lg-none">
                                <img src="{{ asset('storage/images/logo/logo.png') }}"
                                    class="rounded-circle shadow"
                                    style="width:70px;height:70px;object-fit:cover;border:3px solid rgba(139,92,246,0.2);">
                            <div class="fw-bold mt-2" style="color:#7c3aed;font-size:0.85rem;letter-spacing:1px;">FIVE FEST</div>
                        </div>
                        <div class="mb-4 text-center text-md-start">
                            <h3 class="fw-bold text-dark mb-1">Buat Akun</h3>
                            <p class="text-muted small">Lengkapi data untuk mulai memesan tiket</p>
                        </div>

                        <form action="{{ route('register.post') }}" method="POST">
                            @csrf

                            <!-- Baris 1: Nama Lengkap & No. Telepon Berdampingan -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold small text-secondary">Nama Lengkap</label>
                                    <input type="text" name="name" class="form-control form-control-lg bg-light border-0 shadow-sm @error('name') is-invalid @enderror" 
                                           placeholder="Nama lengkap" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold small text-secondary">No. Telepon</label>
                                    <input type="number" name="phone" class="form-control form-control-lg bg-light border-0 shadow-sm @error('phone') is-invalid @enderror" 
                                           placeholder="0812xxxxxx" value="{{ old('phone') }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Baris 2: Email Panjang (Sejajar dengan kolom di atasnya) -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold small text-secondary">Email</label>
                                <input type="email" name="email" class="form-control form-control-lg bg-light border-0 shadow-sm @error('email') is-invalid @enderror" 
                                       placeholder="email@contoh.com" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Baris 3: Password & Konfirmasi -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold small text-secondary">Kata Sandi</label>
                                    <div class="password-wrapper">
                                        <input type="password" name="password" id="password" class="form-control form-control-lg bg-light border-0 shadow-sm @error('password') is-invalid @enderror" 
                                               placeholder="••••••••" required>
                                        <i class="bi bi-eye-slash toggle-password" onclick="togglePass('password', this)"></i>
                                        @error('password')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold small text-secondary">Konfirmasi</label>
                                    <div class="password-wrapper">
                                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control form-control-lg bg-light border-0 shadow-sm" 
                                               placeholder="••••••••" required>
                                        <i class="bi bi-eye-slash toggle-password" onclick="togglePass('password_confirmation', this)"></i>
                                    </div>
                                </div>
                            </div>

                                <div class="form-check mb-4">
                                    <input class="form-check-input @error('terms') is-invalid @enderror" type="checkbox" name="terms" id="terms" required>
                                    <label class="form-check-label small text-muted" for="terms">
                                        Saya menyetujui <a href="{{ url('/terms') }}" target="_blank" class="text-primary text-decoration-none fw-bold">Syarat & Ketentuan</a> yang berlaku.
                                    </label>
                                    @error('terms')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            <button type="submit" class="btn btn-register btn-lg w-100 text-white fw-bold rounded-3 shadow-sm mb-3">
                                DAFTAR SEKARANG <i class="bi bi-person-plus ms-2"></i>
                            </button>

                            <div class="divider">ATAU</div>

                            <a href="{{ route('auth.redirect') }}" class="btn btn-google btn-lg w-100 rounded-3 shadow-sm mb-4">
                                <img src="https://www.gstatic.com/images/branding/product/1x/googleg_48dp.png" alt="Google" style="width: 20px;">
                                Daftar dengan Google
                            </a>

                            <p class="text-center text-muted small mb-0">
                                Sudah punya akun? <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">Masuk</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<script>
    function togglePass(id, el) {
        const input = document.getElementById(id);
        if (input.type === "password") {
            input.type = "text";
            el.classList.replace('bi-eye-slash', 'bi-eye');
        } else {
            input.type = "password";
            el.classList.replace('bi-eye', 'bi-eye-slash');
        }
    }
    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Registrasi Gagal',
            html: `
                <ul class="text-start small">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            `,
            confirmButtonColor: '#8b5cf6'
        });
    @endif
</script>

@endsection