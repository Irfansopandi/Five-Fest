@extends('v_layouts.app')

@section('title', 'Daftar Vendor - FiveFest')

@push('styles')
<style>
    /* ── Layout ── */
    body.auth-page nav,
    body.auth-page footer { display: none !important; }

    .bg-vendor {
        background: linear-gradient(-45deg, #4f46e5, #7c3aed, #ec4899, #8b5cf6);
        background-size: 400% 400%;
        animation: gradientBG 15s ease infinite;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 0;
    }

    @keyframes gradientBG {
        0%   { background-position: 0%   50%; }
        50%  { background-position: 100% 50%; }
        100% { background-position: 0%   50%; }
    }

    /* ── Card ── */
    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 2rem !important;
        border: none;
        box-shadow: 0 20px 40px rgba(0,0,0,.2);
    }

    /* ── Step Indicator ── */
    .step-indicator {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        position: relative;
    }

    .step-indicator::before {
        content: "";
        position: absolute;
        top: 50%;
        left: 20px;
        right: 20px;
        height: 3px;
        background: #e2e8f0;
        z-index: 1;
        transform: translateY(-50%);
        border-radius: 2px;
    }

    .step-indicator .progress-line {
        position: absolute;
        top: 50%;
        left: 20px;
        height: 3px;
        background: linear-gradient(90deg, #8b5cf6, #ec4899);
        z-index: 1;
        transform: translateY(-50%);
        border-radius: 2px;
        width: 0%;
        transition: width .5s ease;
    }

    .step {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: #fff;
        border: 3px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 15px;
        position: relative;
        z-index: 2;
        transition: all .3s ease;
        color: #94a3b8;
    }

    .step.active {
        background: #8b5cf6;
        border-color: #8b5cf6;
        color: #fff;
        box-shadow: 0 0 0 5px rgba(139,92,246,.2);
    }

    .step.completed {
        background: #10b981;
        border-color: #10b981;
        color: #fff;
        box-shadow: 0 0 0 5px rgba(16,185,129,.15);
    }

    /* ── Form sections ── */
    .form-section { display: none; }

    .form-section.active {
        display: block;
        animation: fadeIn .4s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0);    }
    }

    /* ── Inputs ── */
    .form-control-modern {
        border-radius: 12px;
        padding: .75rem 1rem;
        border: 1.5px solid #e2e8f0;
        background: #f8fafc;
        transition: all .3s ease;
        font-size: 14.5px;
    }

    .form-control-modern:focus {
        background: #fff;
        border-color: #8b5cf6;
        box-shadow: 0 0 0 4px rgba(139,92,246,.1);
        outline: none;
    }

    .form-control-modern.is-valid   { border-color: #10b981 !important; background-image: none; }
    .form-control-modern.is-invalid { border-color: #ef4444 !important; background-image: none; }

    /* ── Field wrapper (icon + input) ── */
    .field-wrap {
        position: relative;
    }

    .field-wrap .form-control-modern {
        padding-right: 2.6rem;   /* ruang untuk satu icon */
    }

    /* Password field butuh ruang untuk toggle eye saja */
    .field-wrap.has-toggle .form-control-modern {
        padding-right: 2.8rem;
    }

    /* Status icon (✓ / ✗) — selalu di kanan */
    .field-wrap .status-icon {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 15px;
        pointer-events: none;
        line-height: 1;
    }

    /* Toggle eye — di kanan input password */
    .field-wrap .toggle-pw {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        padding: 0;
        cursor: pointer;
        color: #94a3b8;
        font-size: 16px;
        z-index: 5;
        line-height: 1;
        transition: color .2s;
    }

    .field-wrap .toggle-pw:hover { color: #8b5cf6; }

    /* Error text */
    .field-err {
        display: block;
        font-size: 11.5px;
        color: #ef4444;
        margin-top: 4px;
        min-height: 16px;
    }

    /* ── Buttons ── */
    .btn-next, .btn-submit {
        border-radius: 50px;
        padding: .75rem 2rem;
        font-weight: 700;
        letter-spacing: .3px;
        transition: all .25s ease;
    }

    .btn-next:not(:disabled):hover   { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(99,102,241,.35); }
    .btn-submit:not(:disabled):hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(16,185,129,.35); }

    .btn:disabled {
        opacity: .5;
        cursor: not-allowed;
        transform: none !important;
        box-shadow: none !important;
    }
</style>
@endpush

@section('content')
<script>document.body.classList.add('auth-page');</script>

<div class="bg-vendor">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card glass-card">
                    <div class="card-body p-4 p-md-5">

                        {{-- Header --}}
                        <div class="text-center mb-4">
                            <img src="{{ asset('storage/images/logo/logo.png') }}" style="height:60px;" class="mb-3" alt="Logo FiveFest">
                            <h3 class="fw-bold">Buka Markas Eventmu</h3>
                            <p class="text-muted">Lengkapi data untuk bergabung sebagai Creator di FiveFest</p>
                        </div>

                        {{-- Step Indicator --}}
                        <div class="step-indicator mb-4">
                            <div class="progress-line" id="progress-line"></div>
                            <div class="step active" id="step-dot-1">1</div>
                            <div class="step"        id="step-dot-2">2</div>
                        </div>

                        <form
                            action="{{ auth()->check() ? route('register.vendor.reapply.post') : route('register.vendor') }}"
                            method="POST"
                            enctype="multipart/form-data"
                            id="regForm">
                            @csrf

                            {{-- ══════════════════════════════════════════════
                                 SECTION 1 — Informasi Akun & Dasar
                            ══════════════════════════════════════════════ --}}
                            <div class="form-section active" id="section-1">
                                <h5 class="fw-bold mb-4">
                                    <i class="bi bi-person-circle me-2 text-primary"></i>Informasi Akun & Dasar
                                </h5>

                                <div class="row g-3">
                                    {{-- Nama --}}
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Nama Lengkap / Perusahaan</label>
                                        <div class="field-wrap">
                                            <input type="text" id="s1-name" name="name"
                                                class="form-control form-control-modern @error('name') is-invalid @enderror"
                                                value="{{ old('name') }}"
                                                placeholder="Contoh: Mecima Pro" required>
                                            <span class="status-icon" id="ic-s1-name"></span>
                                        </div>
                                        <span class="field-err" id="e-s1-name"></span>
                                    </div>

                                    {{-- Telepon --}}
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">No. Telepon Aktif</label>
                                        <div class="field-wrap">
                                            <input type="tel" id="s1-phone" name="phone"
                                                class="form-control form-control-modern @error('phone') is-invalid @enderror"
                                                value="{{ old('phone') }}"
                                                placeholder="0812xxxx" required>
                                            <span class="status-icon" id="ic-s1-phone"></span>
                                        </div>
                                        <span class="field-err" id="e-s1-phone"></span>
                                    </div>

                                    {{-- Email --}}
                                    <div class="col-12">
                                        <label class="form-label small fw-semibold">Email Bisnis</label>
                                        <div class="field-wrap">
                                            <input type="email" id="s1-email" name="email"
                                                class="form-control form-control-modern @error('email') is-invalid @enderror"
                                                value="{{ old('email') }}"
                                                placeholder="vendor@bisnis.com" required>
                                            <span class="status-icon" id="ic-s1-email"></span>
                                        </div>
                                        <span class="field-err" id="e-s1-email"></span>
                                    </div>

                                    {{-- Kata Sandi --}}
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Kata Sandi</label>
                                        <div class="field-wrap has-toggle">
                                            <input type="password" id="s1-pass" name="password"
                                                class="form-control form-control-modern @error('password') is-invalid @enderror"
                                                placeholder="Minimal 8 karakter" required>
                                            <button type="button" class="toggle-pw" data-target="s1-pass" tabindex="-1">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                        <span class="field-err" id="e-s1-pass"></span>
                                    </div>

                                    {{-- Konfirmasi Sandi --}}
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Konfirmasi Sandi</label>
                                        <div class="field-wrap has-toggle">
                                            <input type="password" id="s1-conf" name="password_confirmation"
                                                class="form-control form-control-modern"
                                                placeholder="Ulangi kata sandi" required>
                                            <button type="button" class="toggle-pw" data-target="s1-conf" tabindex="-1">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                        <span class="field-err" id="e-s1-conf"></span>
                                    </div>
                                </div>

                                <div class="mt-5 d-flex justify-content-end">
                                    <button type="button" id="btn-next" class="btn btn-primary btn-next" onclick="nextStep()">
                                        Lanjut ke Informasi Legal <i class="bi bi-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- ══════════════════════════════════════════════
                                 SECTION 2 — Informasi Legal & Dokumen
                            ══════════════════════════════════════════════ --}}
                            <div class="form-section" id="section-2">
                                <h5 class="fw-bold mb-4">
                                    <i class="bi bi-shield-check me-2 text-success"></i>Informasi Legal & Dokumen
                                </h5>

                                <div class="row g-3">
                                    {{-- Tipe Identitas --}}
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Tipe Identitas</label>
                                        <select name="document_type" id="docType" class="form-select form-control-modern">
                                            <option value="individu">Individu / Perorangan</option>
                                            <option value="badan_hukum">Badan Hukum (PT/CV)</option>
                                        </select>
                                    </div>

                                    {{-- Nomor NPWP --}}
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Nomor NPWP</label>
                                        <input type="text" name="npwp_number"
                                            class="form-control form-control-modern @error('npwp_number') is-invalid @enderror"
                                            placeholder="00.000.000.0-000.000"
                                            value="{{ old('npwp_number') }}" required>
                                        @error('npwp_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    {{-- Nama NPWP --}}
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Nama Sesuai NPWP</label>
                                        <input type="text" name="npwp_name"
                                            class="form-control form-control-modern @error('npwp_name') is-invalid @enderror"
                                            placeholder="Sesuai kartu NPWP"
                                            value="{{ old('npwp_name') }}" required>
                                        @error('npwp_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    {{-- Upload NPWP --}}
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Upload NPWP <span class="text-muted fw-normal">(PDF/JPG/PNG, maks. 5 MB)</span></label>
                                        <input type="file" name="npwp_file"
                                            class="form-control form-control-modern @error('npwp_file') is-invalid @enderror"
                                            accept=".pdf,.jpg,.jpeg,.png" required>
                                        @error('npwp_file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    {{-- Alamat NPWP --}}
                                    <div class="col-12">
                                        <label class="form-label small fw-semibold">Alamat Sesuai NPWP</label>
                                        <textarea name="npwp_address" rows="2"
                                            class="form-control form-control-modern @error('npwp_address') is-invalid @enderror"
                                            placeholder="Alamat lengkap sesuai kartu NPWP..." required>{{ old('npwp_address') }}</textarea>
                                        @error('npwp_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    {{-- Badan Hukum (conditional) --}}
                                    <div id="legal-badan-hukum" class="col-12 d-none">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold">Nomor NIB</label>
                                                <input type="text" name="nib_number"
                                                    class="form-control form-control-modern @error('nib_number') is-invalid @enderror"
                                                    placeholder="Nomor Induk Berusaha"
                                                    value="{{ old('nib_number') }}">
                                                @error('nib_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold">Upload Anggaran Dasar <span class="text-muted fw-normal">(PDF/JPG/PNG, maks. 5 MB)</span></label>
                                                <input type="file" name="anggaran_dasar_file"
                                                    class="form-control form-control-modern @error('anggaran_dasar_file') is-invalid @enderror"
                                                    accept=".pdf,.jpg,.jpeg,.png">
                                                @error('anggaran_dasar_file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-5 d-flex justify-content-between">
                                    <button type="button" class="btn btn-light btn-next border fw-bold" onclick="prevStep()">
                                        <i class="bi bi-arrow-left me-2"></i>Kembali
                                    </button>
                                    <button type="submit" id="btn-submit" class="btn btn-success btn-submit shadow" disabled>
                                        Daftar & Ajukan Verifikasi <i class="bi bi-check-circle ms-2"></i>
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>

                <p class="text-center text-white mt-4 opacity-75">
                    Sudah punya akun vendor?
                    <a href="{{ route('login') }}" class="text-white fw-bold">Masuk di sini</a>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
/* ══════════════════════════════════════════════════════════
   SECTION 1 — Validasi real-time
══════════════════════════════════════════════════════════ */
const s1Rules = {
    's1-name' : v => !v ? 'Nama wajib diisi.'
                        : v.length < 3 ? 'Nama minimal 3 karakter.' : '',
    's1-phone': v => !v ? 'No. telepon wajib diisi.'
                        : !/^(\+62|0)[0-9]{8,13}$/.test(v) ? 'Format tidak valid (contoh: 08123456789).' : '',
    's1-email': v => !v ? 'Email wajib diisi.'
                        : !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v) ? 'Format email tidak valid.' : '',
    's1-pass' : v => !v ? 'Kata sandi wajib diisi.' : v.length < 8 ? 'Minimal 8 karakter.' : '',
    's1-conf' : v => {
        const pw = document.getElementById('s1-pass').value;
        return !v ? 'Konfirmasi sandi wajib diisi.' : v !== pw ? 'Kata sandi tidak cocok.' : '';
    },
};

const s1Touched = {};

function validateS1Field(id) {
    const el  = document.getElementById(id);
    const ic  = document.getElementById('ic-' + id);
    const em  = document.getElementById('e-' + id);
    if (!el) return true;

    const val = el.value.trim();
    const msg = s1Rules[id]?.(val) ?? '';

    // Jangan tampilkan error sebelum user mulai mengetik
    if (!s1Touched[id] && !val) {
        el.classList.remove('is-valid', 'is-invalid');
        if (ic) ic.innerHTML = '';
        if (em) em.textContent = '';
        return true;
    }

    // Password & konfirmasi: hanya tampilkan error text, tanpa status icon
    const isPasswordField = (id === 's1-pass' || id === 's1-conf');

    if (msg) {
        el.classList.add('is-invalid');
        el.classList.remove('is-valid');
        if (ic && !isPasswordField) ic.innerHTML = `<i class="bi bi-x-circle-fill" style="color:#ef4444"></i>`;
        if (em) em.textContent = msg;
        return false;
    } else {
        el.classList.add('is-valid');
        el.classList.remove('is-invalid');
        if (ic && !isPasswordField) ic.innerHTML = `<i class="bi bi-check-circle-fill" style="color:#10b981"></i>`;
        if (em) em.textContent = '';
        return true;
    }
}

function checkS1Complete() {
    const allOk = Object.keys(s1Rules).every(id => {
        const el = document.getElementById(id);
        return el && s1Rules[id](el.value.trim()) === '';
    });
    const btn = document.getElementById('btn-next');
    if (btn) btn.disabled = !allOk;
}

// Pasang listener ke semua field Section 1
Object.keys(s1Rules).forEach(id => {
    const el = document.getElementById(id);
    if (!el) return;
    ['input', 'blur'].forEach(evt => {
        el.addEventListener(evt, () => {
            s1Touched[id] = true;
            validateS1Field(id);
            // Validasi ulang konfirmasi saat password berubah
            if (id === 's1-pass' && s1Touched['s1-conf']) validateS1Field('s1-conf');
            checkS1Complete();
        });
    });
});

checkS1Complete(); // Cek kondisi awal

/* ══════════════════════════════════════════════════════════
   TOGGLE PASSWORD
══════════════════════════════════════════════════════════ */
document.querySelectorAll('.toggle-pw').forEach(btn => {
    btn.addEventListener('click', () => {
        const input = document.getElementById(btn.dataset.target);
        const icon  = btn.querySelector('i');
        const isHidden = input.type === 'password';
        input.type = isHidden ? 'text' : 'password';
        icon.classList.toggle('bi-eye',       !isHidden);
        icon.classList.toggle('bi-eye-slash',  isHidden);
    });
});

/* ══════════════════════════════════════════════════════════
   STEP NAVIGATION
══════════════════════════════════════════════════════════ */
function setProgressLine(step) {
    // step 1 = 0%, step 2 = 100%
    document.getElementById('progress-line').style.width = step === 2 ? 'calc(100% - 40px)' : '0%';
}

function disableSection1Required() {
    document.querySelectorAll('#section-1 input').forEach(input => {
        input.removeAttribute('required');
    });
}

function nextStep() {
    // Tandai semua field sebagai touched dan validasi
    let allOk = true;
    Object.keys(s1Rules).forEach(id => {
        s1Touched[id] = true;
        if (!validateS1Field(id)) allOk = false;
    });

    disableSection1Required(); 

    if (!allOk) {
        Swal.fire({
            icon: 'warning',
            title: 'Data Belum Lengkap',
            text: 'Silakan perbaiki field yang bermasalah pada Informasi Dasar.',
            confirmButtonColor: '#8b5cf6',
        });
        return;
    }

    document.getElementById('section-1').classList.remove('active');
    document.getElementById('section-2').classList.add('active');
    document.getElementById('step-dot-1').className = 'step completed';
    document.getElementById('step-dot-2').className = 'step active';
    setProgressLine(2);
}

function prevStep() {
    document.getElementById('section-2').classList.remove('active');
    document.getElementById('section-1').classList.add('active');
    document.getElementById('step-dot-1').className = 'step active';
    document.getElementById('step-dot-2').className = 'step';
    setProgressLine(1);
}

/* ══════════════════════════════════════════════════════════
   SECTION 2 — Toggle Badan Hukum
══════════════════════════════════════════════════════════ */
document.getElementById('docType').addEventListener('change', function () {
    const isBadanHukum = this.value === 'badan_hukum';
    document.getElementById('legal-badan-hukum').classList.toggle('d-none', !isBadanHukum);
    checkS2Complete();
});

/* ══════════════════════════════════════════════════════════
   SECTION 2 — Validasi field
══════════════════════════════════════════════════════════ */
function validateS2Field(input) {
    const val     = input.value.trim();
    const docType = document.getElementById('docType').value;
    let msg = '';

    switch (input.name) {
        case 'npwp_number':
            if (!val) msg = 'Nomor NPWP wajib diisi.';
            else if (!/^\d{2}\.\d{3}\.\d{3}\.\d{1}-\d{3}\.\d{3}$/.test(val))
                msg = 'Format NPWP: 00.000.000.0-000.000';
            break;
        case 'npwp_name':
            if (!val) msg = 'Nama sesuai NPWP wajib diisi.';
            else if (val.length < 3) msg = 'Nama terlalu pendek.';
            break;
        case 'npwp_address':
            if (!val) msg = 'Alamat wajib diisi.';
            else if (val.length < 10) msg = 'Alamat terlalu singkat (minimal 10 karakter).';
            break;
        case 'npwp_file': {
            const file = input.files[0];
            if (!file) msg = 'Dokumen NPWP wajib diunggah.';
            else if (!['application/pdf','image/jpeg','image/jpg','image/png'].includes(file.type))
                msg = 'Format file harus PDF, JPG, atau PNG.';
            else if (file.size > 5 * 1024 * 1024) msg = 'Ukuran file maksimal 5 MB.';
            break;
        }
        case 'nib_number':
            if (docType === 'badan_hukum' && !val) msg = 'Nomor NIB wajib diisi untuk Badan Hukum.';
            break;
        case 'anggaran_dasar_file':
            if (docType === 'badan_hukum') {
                const file = input.files[0];
                if (!file) msg = 'Anggaran Dasar wajib diunggah.';
                else if (!['application/pdf','image/jpeg','image/jpg','image/png'].includes(file.type))
                    msg = 'Format file harus PDF, JPG, atau PNG.';
                else if (file.size > 5 * 1024 * 1024) msg = 'Ukuran file maksimal 5 MB.';
            }
            break;
    }

    // Cari elemen feedback (Bootstrap .invalid-feedback atau .field-err)
    const feedback = input.closest('.col-md-6, .col-12, .col-md-12')
                         ?.querySelector('.invalid-feedback, .field-err');

    if (msg) {
        input.classList.add('is-invalid');
        input.classList.remove('is-valid');
        if (feedback) feedback.textContent = msg;
    } else {
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
        if (feedback) feedback.textContent = '';
    }

    return !msg;
}

document.querySelectorAll('#section-2 input, #section-2 textarea').forEach(input => {
    ['blur', 'input', 'change'].forEach(evt =>
        input.addEventListener(evt, () => { validateS2Field(input); checkS2Complete(); })
    );
});

/* ══════════════════════════════════════════════════════════
   SECTION 2 — Enable/disable tombol Submit
══════════════════════════════════════════════════════════ */
function checkS2Complete() {
    const docType  = document.getElementById('docType').value;
    const npwpNum  = document.querySelector('[name="npwp_number"]').value.trim();
    const npwpName = document.querySelector('[name="npwp_name"]').value.trim();
    const npwpAddr = document.querySelector('[name="npwp_address"]').value.trim();
    const npwpFile = document.querySelector('[name="npwp_file"]').files[0];

    let ok = /^\d{2}\.\d{3}\.\d{3}\.\d{1}-\d{3}\.\d{3}$/.test(npwpNum)
          && npwpName.length >= 3
          && npwpAddr.length >= 10
          && !!npwpFile;

    if (docType === 'badan_hukum') {
        const nibNum = document.querySelector('[name="nib_number"]').value.trim();
        const adFile = document.querySelector('[name="anggaran_dasar_file"]').files[0];
        ok = ok && !!nibNum && !!adFile;
    }

    document.getElementById('btn-submit').disabled = !ok;
}

checkS2Complete();

/* ══════════════════════════════════════════════════════════
   SUBMIT — Validasi akhir sebelum kirim
══════════════════════════════════════════════════════════ */
document.getElementById('regForm').addEventListener('submit', function (e) {
    if (!document.getElementById('section-2').classList.contains('active')) return;

    let allValid = true;
    document.querySelectorAll('#section-2 input, #section-2 textarea').forEach(input => {
        // Lewati field Badan Hukum jika tipe bukan Badan Hukum
        if (input.closest('#legal-badan-hukum')
            && document.getElementById('docType').value !== 'badan_hukum') return;
        if (!validateS2Field(input)) allValid = false;
    });

    if (!allValid) {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'Dokumen Belum Lengkap',
            text: 'Silakan perbaiki field yang bermasalah sebelum mengirim.',
            confirmButtonColor: '#8b5cf6',
        });
    }
});

/* ══════════════════════════════════════════════════════════
   KONDISI KHUSUS: Reapply (user sudah login) → langsung Step 2
══════════════════════════════════════════════════════════ */
@if(auth()->check() && request()->routeIs('register.vendor.reapply'))
    disableSection1Required(); // Pastikan validasi Section 1 tidak menghalangi
    document.getElementById('section-1').classList.remove('active');
    document.getElementById('section-2').classList.add('active');
    document.getElementById('step-dot-1').className = 'step completed';
    document.getElementById('step-dot-2').className = 'step active';
    setProgressLine(2);
    checkS2Complete(); // Cek apakah tombol submit bisa langsung diaktifkan
@endif

/* ══════════════════════════════════════════════════════════
   TAMPILKAN ERROR DARI SERVER
══════════════════════════════════════════════════════════ */
@if ($errors->any())
    Swal.fire({
        icon: 'error',
        title: 'Terdapat Kesalahan',
        text: 'Periksa kembali inputan Anda.',
        confirmButtonColor: '#8b5cf6',
    });

    @if ($errors->hasAny(['npwp_number','npwp_name','npwp_file','npwp_address','nib_number','anggaran_dasar_file','document_type']))
        document.getElementById('section-1').classList.remove('active');
        document.getElementById('section-2').classList.add('active');
        document.getElementById('step-dot-1').className = 'step completed';
        document.getElementById('step-dot-2').className = 'step active';
        setProgressLine(2);
    @endif
@endif
</script>
@endsection