@extends('v_layouts.app')
@section('title', 'Daftar Tenant - FiveFest')

@section('content')
<style>
    /* Ubah warna body di sini sesuai dengan warna digambar (ungu solid) */
    body { background-color: #6C5CE7 !important; color: #333; }
    
    .tenant-reg-wrapper { max-width: 800px; margin: 40px auto; padding: 40px; background: #fff; border-radius: 16px; color: #333; }
    
    .form-section-title { color: #6D28D9; font-size: 14px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 20px; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px; }
    .form-control-dark { background: #f8fafc; border: 1px solid #cbd5e1; color: #333; border-radius: 8px; padding: 12px 15px; }
    .form-control-dark:focus { outline: none !important; background: #fff; border-color: #6D28D9; box-shadow: 0 0 0 3px rgba(109,40,217,0.2) !important; color: #333; }
    .form-label { font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #1e293b; }
    .text-asterisk { color: #ef4444; }

    /* Custom eye icon style for clean placement without btn padding */
    .toggle-password {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        padding: 0;
        cursor: pointer;
        color: #64748b;
        font-size: 16px;
        z-index: 10;
        line-height: 1;
        transition: color 0.2s;
    }
    .toggle-password:hover {
        color: #6D28D9;
    }
    
    .btn-dark-outline { background: transparent; border: 1px solid #cbd5e1; color: #475569; border-radius: 8px; padding: 10px 24px; font-weight: 600; transition: 0.3s; text-decoration: none; display: inline-block; }
    .btn-dark-outline:hover { background: #f1f5f9; color: #1e293b; }
    .btn-primary-purple { background: #6D28D9; border: 1px solid #6D28D9; color: #fff; border-radius: 8px; padding: 10px 24px; font-weight: 600; transition: 0.3s; }
    .btn-primary-purple:hover { background: #5b21b6; color: #fff; }
    
    nav { background-color: #6C5CE7 !important; border-bottom: none !important; }

    /* Override heading colors for light form */
    h2.fw-bold { color: #1e293b !important; }
    p.text-muted { color: #64748b !important; }

    /* Fix custom/native select option sizing */
    select.form-control-dark {
        font-size: 14px !important;
        background-color: #f8fafc !important;
    }
    select.form-control-dark option {
        font-size: 14px !important;
        padding: 10px !important;
        background: #ffffff !important;
        color: #333333 !important;
    }

    @media (max-width: 768px) {
        .tenant-reg-wrapper {
            margin: 20px 10px !important;
            padding: 24px 16px !important;
            border-radius: 12px !important;
        }
        .btn-dark-outline, .btn-primary-purple {
            width: 100% !important;
            text-align: center !important;
            padding: 12px 24px !important;
        }
        .tenant-reg-wrapper .d-flex.justify-content-between.mt-5 {
            flex-direction: column-reverse !important;
            gap: 12px !important;
        }
    }
</style>

<div class="container pb-5">
    <div class="tenant-reg-wrapper shadow-lg">
        <h2 class="fw-bold mb-2">Daftar Jadi Tenant</h2>
        <p class="text-muted mb-5">Isi data di bawah ini untuk mendaftarkan akun tenant kamu di FiveFest. Langsung aktif tanpa perlu verifikasi admin!</p>

        @if($errors->any())
            <div class="alert alert-danger" style="background: #451a1a; color: #ff8a8a; border: none;">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register.tenant.post') }}" method="POST" id="tenantForm">
            @csrf
            
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Nama lengkap <span class="text-asterisk">*</span></label>
                    <input type="text" name="name" class="form-control form-control-dark" placeholder="Contoh: Budi Santoso" value="{{ old('name') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email <span class="text-asterisk">*</span></label>
                    <input type="email" name="email" class="form-control form-control-dark" placeholder="email@kamu.com" value="{{ old('email') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">No. WhatsApp <span class="text-asterisk">*</span></label>
                    <input type="number" name="phone" class="form-control form-control-dark" placeholder="08xxxxxxxxxx" value="{{ old('phone') }}" required>
                </div>
                <div class="col-md-6">  
                    <label class="form-label">Nama Usaha / Grup <span class="text-asterisk">*</span></label>
                    <input type="text" name="business_name" class="form-control form-control-dark" placeholder="Contoh: The Echoes Band" value="{{ old('business_name') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Kategori Usaha <span class="text-asterisk">*</span></label>
                    <select name="category" class="form-select form-control-dark" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="Makanan & Minuman" {{ old('category') == 'Makanan & Minuman' ? 'selected' : '' }}>Makanan & Minuman (F&B)</option>
                        <option value="Fashion & Aksesoris" {{ old('category') == 'Fashion & Aksesoris' ? 'selected' : '' }}>Fashion & Aksesoris</option>
                        <option value="Kriya & Kerajinan" {{ old('category') == 'Kriya & Kerajinan' ? 'selected' : '' }}>Kriya & Kerajinan Tangan</option>
                        <option value="Jasa & Layanan" {{ old('category') == 'Jasa & Layanan' ? 'selected' : '' }}>Jasa & Layanan</option>
                        <option value="Lainnya" {{ old('category') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Password <span class="text-asterisk">*</span></label>
                    <div class="position-relative">
                        <input type="password" name="password" id="password" class="form-control form-control-dark pe-5" placeholder="Minimal 8 karakter" required>
                        <button class="toggle-password" type="button" data-target="password">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Konfirmasi Password <span class="text-asterisk">*</span></label>
                    <div class="position-relative">
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control form-control-dark pe-5" placeholder="Ulangi password" required>
                        <button class="toggle-password" type="button" data-target="password_confirmation">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="mt-3 small text-muted"><span class="text-asterisk">*</span> Wajib diisi</div>

            <div class="d-flex justify-content-between mt-5">
                <a href="{{ route('home') }}" class="btn btn-dark-outline">Batal</a>
                <button type="submit" class="btn btn-primary-purple">Daftar Sekarang <i class="bi bi-shop-window"></i></button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('tenantForm').addEventListener('submit', function(e) {
        let pwd = document.getElementById('password').value;
        let pwdConfirm = document.getElementById('password_confirmation').value;
        let confirmInput = document.getElementById('password_confirmation');
        
        if (pwd !== pwdConfirm) {
            e.preventDefault();
            confirmInput.setCustomValidity('Konfirmasi password tidak sama dengan password.');
            confirmInput.reportValidity();
        } else {
            confirmInput.setCustomValidity('');
        }
    });

    document.getElementById('password_confirmation').addEventListener('input', function() {
        this.setCustomValidity('');
    });

    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    });
</script>
@endsection
