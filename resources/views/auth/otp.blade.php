@extends('v_layouts.app')

@section('title', 'Verifikasi OTP')

@push('styles')
<style>
    body.auth-page nav { display: none !important; }
    body.auth-page footer { display: none !important; }

    .bg-gradient-animate {
        background: linear-gradient(-45deg, #667eea, #764ba2, #4facfe, #8e37d7);
        background-size: 400% 400%;
        animation: gradientBG 15s ease infinite;
        position: relative;
        overflow: hidden;
        height: 100vh;
    }

    @keyframes gradientBG {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 2rem !important;
    }

    .otp-input {
        width: 100%;
        letter-spacing: 0.5rem;
        text-align: center;
        font-size: 1.5rem;
        font-weight: bold;
    }
</style>
@endpush

@section('content')
<script>document.body.classList.add('auth-page');</script>

<section class="bg-gradient-animate d-flex align-items-center justify-content-center" style="min-height: 100vh; width: 100%;">
    <div class="container position-relative" style="z-index: 1;">
        <div class="row align-items-center justify-content-center"> 
            <div class="col-lg-5 col-md-8" data-aos="zoom-in">
                <div class="card border-0 shadow-lg glass-card">
                    <div class="card-body p-4 p-md-5">

                        <div class="mb-4 text-center">
                            <h3 class="fw-bold text-dark mb-1">Verifikasi OTP</h3>
                            <p class="text-muted small">Cek inbox email Anda. Kami telah mengirimkan 6 digit OTP.</p>
                        </div>

                        <form action="{{ route('password.verify') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label fw-semibold small text-secondary">Kode OTP (6 Digit)</label>
                                <input type="text" name="otp" class="form-control form-control-lg bg-light border-0 shadow-sm otp-input @error('otp') is-invalid @enderror" placeholder="••••••" maxlength="6" required style="border-radius: 12px;" autocomplete="off">
                                @error('otp')
                                    <div class="invalid-feedback mt-1 small text-center">
                                        <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100 text-white fw-bold shadow-sm mb-3" style="border-radius: 12px;">
                                VERIFIKASI <i class="bi bi-check-circle ms-2"></i>
                            </button>

                            <p class="text-center text-muted small mb-0">
                                Tidak menerima email? <a href="{{ route('password.request') }}" class="text-primary fw-bold text-decoration-none">Kirim Ulang</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
