@extends('v_layouts.app')

@section('title', 'Buat Kata Sandi Baru')

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

    .password-group {
        position: relative;
        display: flex;
    }
    .password-group .form-control {
        border-right: none !important;
    }
    .btn-eye {
        background: #f8f9fa;
        border: none;
        border-radius: 0 12px 12px 0;
        padding: 0 16px;
        color: #94a3b8;
        font-size: 1rem;
        transition: color 0.2s;
        box-shadow: 0 2px 6px rgba(0,0,0,0.06);
    }
    .btn-eye:hover { color: #6d28d9; }
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
                            <h3 class="fw-bold text-dark mb-1">Buat Kata Sandi Baru</h3>
                            <p class="text-muted small">Silakan buat kata sandi baru untuk akun Anda.</p>
                        </div>

                        <form action="{{ route('password.reset.update') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                    <label class="form-label fw-semibold small text-secondary">Kata Sandi Baru</label>
                                    <div class="input-group password-group">
                                        <input type="password" name="password" id="password"
                                            class="form-control form-control-lg bg-light border-0 shadow-sm @error('password') is-invalid @enderror"
                                            placeholder="Minimal 8 karakter" required style="border-radius: 12px 0 0 12px;">
                                        <button type="button" class="btn-eye" onclick="resetTogglePassword('password', this)">
                                            <i class="bi bi-eye-slash"></i>                                        
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="text-danger mt-1 small">
                                            <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-semibold small text-secondary">Ulangi Kata Sandi Baru</label>
                                    <div class="input-group password-group">
                                        <input type="password" name="password_confirmation" id="password_confirmation"
                                            class="form-control form-control-lg bg-light border-0 shadow-sm"
                                            placeholder="Ulangi kata sandi" required style="border-radius: 12px 0 0 12px;">
                                        <button type="button" class="btn-eye" onclick="resetTogglePassword('password_confirmation', this)">
                                            <i class="bi bi-eye-slash"></i>
                                        </button>
                                    </div>
                                </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100 text-white fw-bold shadow-sm" style="border-radius: 12px;">
                                SIMPAN KATA SANDI <i class="bi bi-shield-check ms-2"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
function resetTogglePassword(fieldId, btn) {
    const input = document.getElementById(fieldId);
    const icon = btn.querySelector('i');
    const isHidden = input.type === 'password';

    input.type = isHidden ? 'text' : 'password';
    icon.className = isHidden ? 'bi bi-eye' : 'bi bi-eye-slash'; 
}
</script>
@endpush
@endsection
