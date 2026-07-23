@extends('v_layouts.app')

@section('title', 'Login')

@push('styles')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    body.auth-page nav { display: none !important; }
    body.auth-page footer { display: none !important; }

    @keyframes softBounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-25px); }
    }

    .logo-bounce {
        animation: softBounce 3.5s ease-in-out infinite;
        filter: drop-shadow(0 0 15px rgba(0, 245, 255, 0.3));
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 2rem !important;
    }

    .bg-gradient-animate {
        background: linear-gradient(-45deg, #667eea, #764ba2, #4facfe, #8e37d7);
        background-size: 400% 400%;
        animation: gradientBG 15s ease infinite;
        position: relative;
        overflow: hidden;
        height: 100vh;
    }

    .x-small { font-size: 0.75rem; }

    @keyframes gradientBG {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    .password-field-container { position: relative; }

    .toggle-password {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        z-index: 10;
        color: #6c757d;
    }

    .btn-login {
        background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%);
        border: none;
        padding: 0.6rem;
        transition: all 0.3s ease;
    }

    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(139, 92, 246, 0.4);
    }

    .btn-google {
        background: #ffffff;
        color: #444;
        border: 1px solid #ddd;
        padding: 0.6rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-weight: 600;
        font-size: 0.9rem;
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
        margin: 0.75rem 0;
        color: #999;
        font-size: 0.75rem;
    }

    .divider::before,
    .divider::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid #eee;
    }

    .divider:not(:empty)::before { margin-right: .5em; }
    .divider:not(:empty)::after { margin-left: .5em; }

    .is-invalid {
        border: 1px solid #dc3545 !important;
    }
    .invalid-feedback {
        font-size: 0.8rem;
        font-weight: 500;
    }

    /* Math Captcha Styling */
    .math-captcha-box {
        background: linear-gradient(135deg, #f0f4ff, #e8ecff);
        border: 1.5px solid #c7d0f8;
        border-radius: 12px;
        padding: 0.6rem 1rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .math-captcha-question {
        font-size: 1rem;
        font-weight: 700;
        color: #4f46e5;
        white-space: nowrap;
        background: white;
        border-radius: 8px;
        padding: 0.3rem 0.7rem;
        border: 1px solid #c7d0f8;
        letter-spacing: 1px;
    }

    .math-captcha-input {
        width: 80px !important;
        text-align: center;
        font-weight: 700;
        font-size: 1rem;
        border-radius: 8px !important;
        border: 1.5px solid #c7d0f8 !important;
        background: white !important;
    }

    .math-captcha-input:focus {
        border-color: #8b5cf6 !important;
        box-shadow: 0 0 0 2px rgba(139, 92, 246, 0.15) !important;
        outline: none;
    }

    .btn-refresh-captcha {
        background: none;
        border: none;
        color: #8b5cf6;
        cursor: pointer;
        font-size: 1rem;
        padding: 0;
        transition: transform 0.3s;
    }

    .btn-refresh-captcha:hover {
        transform: rotate(180deg);
    }

    @media (max-width: 768px) {
        .glass-card {
            width: 90% !important;
            max-width: 360px !important;
            margin: 0 auto !important;
        }
        .glass-card .card-body {
            padding: 1.5rem 1.25rem !important;
        }
        .math-captcha-box {
            padding: 0.5rem 0.75rem !important;
            gap: 8px !important;
        }
        .math-captcha-question {
            font-size: 0.85rem !important;
            padding: 0.2rem 0.5rem !important;
        }
        .math-captcha-input {
            width: 70px !important;
            font-size: 0.85rem !important;
        }
    }
</style>
@endpush

@section('content')
    <script>document.body.classList.add('auth-page');</script>

    <section class="bg-gradient-animate d-flex align-items-center justify-content-center" style="min-height: 100vh; width: 100%;">
        <div class="container position-relative" style="z-index: 1;">
            <div class="row g-4 align-items-center justify-content-center">

                <div class="col-lg-6 d-none d-lg-block text-white text-center" data-aos="fade-right">
                    <div class="position-relative d-inline-block mb-2">
                        <img src="{{ asset('storage/images/logo/logo.png') }}" class="img-fluid rounded-circle logo-bounce shadow-lg" style="width: 180px; height: 180px; object-fit: cover; background: #fff; padding: 15px; border: 5px solid rgba(255,255,255,0.2);">
                    </div>
                    <h2 class="fw-bold mb-1">Selamat Datang di <span style="color: #00F5FF;">Five Fest</span></h2>
                    <p class="fs-6 opacity-75 mx-auto" style="max-width: 400px;">Gerbang Anda menuju pengalaman musik live yang tak terlupakan.</p>
                </div>

                <div class="col-lg-5 col-md-8" data-aos="fade-left">
                    <div class="card border-0 shadow-lg glass-card">
                        <div class="card-body p-3 p-md-4">
                            {{-- Logo (mobile only) --}}
                            <div class="text-center mb-3 d-lg-none">
                                <img src="{{ asset('storage/images/logo/logo.png') }}"
                                    class="rounded-circle shadow"
                                    style="width:70px;height:70px;object-fit:cover;border:3px solid rgba(139,92,246,0.2);">
                                <div class="fw-bold mt-2" style="color:#7c3aed;font-size:0.85rem;letter-spacing:1px;">FIVE FEST</div>
                            </div>

                            <div class="mb-2 text-center text-md-start">
                                <h4 class="fw-bold text-dark mb-0">Masuk</h4>
                                <p class="text-muted small mb-0">Silakan masuk ke akun Anda</p>
                            </div>

                            {{-- Alert Error Captcha --}}
                            @if($errors->has('captcha'))
                                <div class="alert alert-danger p-2 small mb-3 border-0" style="border-radius: 10px;">
                                    <i class="bi bi-exclamation-circle-fill me-2"></i> {{ $errors->first('captcha') }}
                                </div>
                            @endif

                            <form action="{{ route('login.post') }}" method="POST" id="loginForm">
                                @csrf

                                <div class="mb-2">
                                    <label class="form-label fw-semibold x-small text-secondary mb-1">Email</label>
                                    @php
                                        $savedEmail = \Illuminate\Support\Facades\Cookie::get('remember_email');
                                    @endphp
                                    <input type="email" name="email" class="form-control form-control-sm bg-light border-0 shadow-sm @error('email') is-invalid @enderror" placeholder="email@anda.com" value="{{ old('email', $savedEmail) }}" required style="font-size: 0.85rem; border-radius: 10px;">
                                    @error('email')
                                        <div class="invalid-feedback mt-0" style="font-size: 0.75rem;">
                                            <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-2">
                                    <div class="d-flex justify-content-between">
                                        <label class="form-label fw-semibold x-small text-secondary mb-1">Kata Sandi</label>
                                        <a href="{{ route('password.request') }}" class="x-small text-decoration-none fw-bold text-primary">Lupa?</a>
                                    </div>
                                    @php
                                        $savedPassword = \Illuminate\Support\Facades\Cookie::get('remember_password');
                                    @endphp
                                    <div class="password-field-container">
                                        <input type="password" name="password" id="passwordInput" class="form-control form-control-sm bg-light border-0 shadow-sm @error('password') is-invalid @enderror" placeholder="••••••••" value="{{ $savedPassword }}" required style="font-size: 0.85rem; border-radius: 10px; padding-right: 40px;">
                                        <i class="bi bi-eye-slash toggle-password" id="togglePassword" style="right: 12px;"></i>
                                        @error('password')
                                            <div class="invalid-feedback mt-0" style="font-size: 0.75rem;">
                                                <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- ===================== MATH CAPTCHA ===================== --}}
                                <div class="mb-2">
                                    <label class="form-label fw-semibold x-small text-secondary mb-1">
                                        <i class="bi bi-shield-check me-1 text-primary"></i> Verifikasi Keamanan
                                    </label>
                                    <div class="math-captcha-box">
                                        <span class="math-captcha-question" id="captchaQuestion">? + ? = ?</span>
                                        <span class="text-muted x-small">=</span>
                                        <input type="number"
                                               name="captcha_answer"
                                               id="captchaAnswer"
                                               class="form-control form-control-sm math-captcha-input @error('captcha') is-invalid @enderror"
                                               placeholder="?"
                                               required
                                               autocomplete="off">
                                        <button type="button" class="btn-refresh-captcha" onclick="generateCaptcha()" title="Ganti soal">
                                            <i class="bi bi-arrow-clockwise"></i>
                                        </button>
                                    </div>
                                    {{-- Hidden fields untuk validasi server-side --}}
                                    <input type="hidden" name="captcha_num1" id="captchaNum1">
                                    <input type="hidden" name="captcha_num2" id="captchaNum2">
                                    @error('captcha')
                                        <div class="text-danger mt-1" style="font-size: 0.75rem;">
                                            <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                {{-- ========================================================= --}}

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" style="width: 0.9em; height: 0.9em;" {{ \Illuminate\Support\Facades\Cookie::get('remember_email') ? 'checked' : '' }}>
                                    <label class="form-check-label x-small text-muted" for="remember">Ingat saya</label>
                                </div>

                                <button type="submit" class="btn btn-login btn-sm w-100 text-white fw-bold rounded-3 shadow-sm mb-2">
                                    MASUK <i class="bi bi-box-arrow-in-right ms-1"></i>
                                </button>

                                <div class="divider">ATAU</div>

                                <a href="{{ route('auth.redirect') }}" class="btn btn-google btn-sm w-100 rounded-3 shadow-sm mb-3">
                                    <img src="https://www.gstatic.com/images/branding/product/1x/googleg_48dp.png" alt="Google" style="width: 16px;">
                                    Masuk dengan Google
                                </a>

                                <p class="text-center text-muted x-small mb-0">
                                    Belum bergabung? <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none">Buat Akun</a>
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        // ============ MATH CAPTCHA ============
        function generateCaptcha() {
            const num1 = Math.floor(Math.random() * 8) + 1; // 1 to 8
            const maxNum2 = 10 - num1;
            const num2 = Math.floor(Math.random() * maxNum2) + 1; // 1 to (10-num1)

            document.getElementById('captchaQuestion').textContent = num1 + ' + ' + num2 + ' = ?';
            document.getElementById('captchaNum1').value = num1;
            document.getElementById('captchaNum2').value = num2;
            document.getElementById('captchaAnswer').value = '';
        }

        // Generate saat halaman load
        generateCaptcha();
        // ======================================

        // Toggle Password
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#passwordInput');

        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('bi-eye');
            this.classList.toggle('bi-eye-slash');
        });

        // SweetAlert
        @if(session('error'))
            Swal.fire({
                title: 'Opps!',
                text: "{{ session('error') }}",
                icon: 'error',
                confirmButtonColor: '#8b5cf6'
            });
        @endif

        @if(session('success'))
            Swal.fire({
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonColor: '#8b5cf6'
            });
        @endif
    </script>
@endsection