@extends('v_layouts.app')
@section('title', 'Form Pendaftaran Tenant - ' . $event->title)

@section('content')
<style>
    body {
        background-color: #f8fafc;
    }
    .wizard-container {
        max-width: 800px;
        margin: 50px auto;
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    .wizard-header {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
        color: #fff;
        padding: 40px;
        text-align: center;
    }
    .wizard-body {
        padding: 40px;
    }
    .step-indicator {
        display: flex;
        justify-content: space-between;
        margin-bottom: 40px;
        position: relative;
    }
    .step-indicator::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 30px;
        right: 30px;
        height: 3px;
        background: #e2e8f0;
        z-index: 1;
    }
    .step-progress {
        position: absolute;
        top: 20px;
        left: 30px;
        height: 3px;
        background: #a855f7;
        z-index: 2;
        transition: 0.4s ease;
        width: 0%;
    }
    .step-item {
        position: relative;
        z-index: 3;
        text-align: center;
        width: 60px;
    }
    .step-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e2e8f0;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin: 0 auto 10px;
        transition: 0.4s ease;
        border: 4px solid #fff;
    }
    .step-item.active .step-circle {
        background: #a855f7;
        color: #fff;
        box-shadow: 0 0 0 4px rgba(168, 85, 247, 0.2);
    }
    .step-item.completed .step-circle {
        background: #10b981;
        color: #fff;
    }
    .step-title {
        font-size: 0.8rem;
        font-weight: 600;
        color: #64748b;
        white-space: nowrap;
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
    }
    .step-item.active .step-title {
        color: #1e1b4b;
    }
    .form-step {
        display: none;
        animation: fadeIn 0.5s;
    }
    .form-step.active {
        display: block;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .btn-next {
        background: linear-gradient(135deg, #a855f7 0%, #7c3aed 100%);
        border: none;
        color: white;
    }
    .btn-next:hover {
        background: linear-gradient(135deg, #9333ea 0%, #6d28d9 100%);
        color: white;
    }
    .data-summary {
        background: #f1f5f9;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
    }

    /* validasi */

    .is-invalid {
        border-color: #dc3545 !important;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.15) !important;
    }
    .is-invalid ~ .invalid-feedback {
        display: block;
    }
</style>

<div class="container">
    <div class="wizard-container">
        <div class="wizard-header">
            <h3 class="fw-bold mb-2">Pengajuan Buka Stand (Tenant)</h3>
            <p class="mb-0 opacity-75">Event: {{ $event->title }}</p>
        </div>
        
        <div class="wizard-body">
            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="step-indicator">
                <div class="step-progress" id="stepProgress"></div>
                <div class="step-item active" id="step1-indicator">
                    <div class="step-circle">1</div>
                    <div class="step-title">Data Diri</div>
                </div>
                <div class="step-item" id="step2-indicator">
                    <div class="step-circle">2</div>
                    <div class="step-title">Info Bisnis</div>
                </div>
                <div class="step-item" id="step3-indicator">
                    <div class="step-circle">3</div>
                    <div class="step-title">Upload & Konfirmasi</div>
                </div>
            </div>

            <form action="{{ route('tenant.event.join.store', $event->id) }}" method="POST" enctype="multipart/form-data" id="tenantJoinForm" style="margin-top: 60px;">
                @csrf
                
                <!-- STEP 1: Data Diri -->
                <div class="form-step active" id="step1">
                    <h5 class="fw-bold mb-4"><i class="bi bi-person-badge text-purple me-2"></i>Konfirmasi Data Diri</h5>
                    <p class="text-muted small mb-4">Pastikan data diri Anda di bawah ini sudah benar. Data ini diambil dari profil akun Anda.</p>
                    
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Nama Lengkap</label>
                            <input type="text" name="name" id="input_name" class="form-control" value="{{ auth()->user()->name }}">
                            <div class="invalid-feedback">Nama lengkap wajib diisi.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" name="email" id="input_email" class="form-control" value="{{ auth()->user()->email }}">
                            <div class="invalid-feedback">Email diisi.</div>

                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nomor WhatsApp</label>
                            <input type="text" name="phone" id="input_phone" class="form-control" value="{{ auth()->user()->phone }}">
                            <div class="invalid-feedback">Nomor wajib diisi.</div>

                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-5">
                        <a href="{{ route('tenant.event.join', $event->id) }}" class="btn btn-light px-4 py-2 me-2 rounded-pill">Batal</a>
                        <button type="button" class="btn btn-next px-4 py-2 rounded-pill fw-bold" onclick="nextStep(2)">Lanjut ke Info Bisnis <i class="bi bi-arrow-right ms-2"></i></button>
                    </div>
                </div>

                <!-- STEP 2: Informasi Bisnis -->
                <div class="form-step" id="step2">
                    <h5 class="fw-bold mb-4"><i class="bi bi-shop text-purple me-2"></i>Informasi Usaha / Bisnis</h5>
                    <p class="text-muted small mb-4">Informasi bisnis yang akan digunakan untuk pengajuan stand di event ini.</p>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Nama Usaha / Merek</label>
                        <input type="text" name="business_name" class="form-control" value="{{ $tenantProfile->business_name ?? auth()->user()->name }}">
                        <div class="form-text">Nama usaha otomatis ditarik dari profil tenant Anda.</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Kategori Usaha <span class="text-danger">*</span></label>
                        <select name="category" id="input_category" class="form-select" required>
                            <div class="invalid-feedback">Kategori wajib dipilih.</div>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Makanan & Minuman" {{ ($tenantProfile->category ?? '') == 'Makanan & Minuman' ? 'selected' : '' }}>Makanan & Minuman (F&B)</option>
                            <option value="Fashion & Aksesoris" {{ ($tenantProfile->category ?? '') == 'Fashion & Aksesoris' ? 'selected' : '' }}>Fashion & Aksesoris</option>
                            <option value="Kriya & Kerajinan" {{ ($tenantProfile->category ?? '') == 'Kriya & Kerajinan' ? 'selected' : '' }}>Kriya & Kerajinan Tangan</option>
                            <option value="Jasa & Layanan" {{ ($tenantProfile->category ?? '') == 'Jasa & Layanan' ? 'selected' : '' }}>Jasa & Layanan</option>
                            <option value="Lainnya" {{ ($tenantProfile->category ?? 'Lainnya') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        <div class="form-text">Kategori ini akan tersimpan ke profil bisnis Anda.</div>
                    </div>

                    <div class="d-flex justify-content-between mt-5">
                        <button type="button" class="btn btn-outline-secondary px-4 py-2 rounded-pill fw-bold" onclick="prevStep(1)"><i class="bi bi-arrow-left me-2"></i> Kembali</button>
                        <button type="button" class="btn btn-next px-4 py-2 rounded-pill fw-bold" onclick="nextStep(3)">Lanjut ke Konfirmasi <i class="bi bi-arrow-right ms-2"></i></button>
                    </div>
                </div>

                <!-- STEP 3: Konfirmasi & Upload -->
                <div class="form-step" id="step3">
                    <h5 class="fw-bold mb-4"><i class="bi bi-check-circle-fill text-success me-2"></i>Upload Foto Booth & Konfirmasi</h5>
                    <p class="text-muted small mb-4">Langkah terakhir! Silakan unggah foto booth atau produk jualan Anda sebagai bahan pertimbangan bagi vendor event.</p>
                    
                    <div class="data-summary mb-4 border border-info">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-info-circle-fill text-info fs-4 me-3 mt-1"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Informasi Pengajuan</h6>
                                <p class="small text-muted mb-0">Setelah formulir dikirim, pengajuan Anda akan berstatus <strong>Menunggu Verifikasi</strong>. Vendor berhak menyetujui atau menolak pengajuan berdasarkan kuota dan kesesuaian kategori.</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4 p-4 border rounded-3 bg-light">
                        <label class="form-label fw-bold">Foto Booth / Stand Terkini <span class="text-danger">*</span></label>
                        <input type="file" name="booth_photo" class="form-control form-control-lg" required accept="image/*" id="boothPhotoInput">
                        <div class="form-text mt-2"><i class="bi bi-card-image me-1"></i> Unggah foto berformat JPG/PNG. Maksimal ukuran file 5MB.</div>
                        
                        <!-- Preview Image -->
                        <div class="mt-3 text-center d-none" id="previewContainer">
                            <img id="imagePreview" src="#" alt="Preview" class="img-thumbnail" style="max-height: 200px; border-radius: 10px;">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-5">
                        <button type="button" class="btn btn-outline-secondary px-4 py-2 rounded-pill fw-bold" onclick="prevStep(2)"><i class="bi bi-arrow-left me-2"></i> Kembali</button>
                        <button type="submit" class="btn btn-success px-5 py-2 rounded-pill fw-bold" id="submitBtn">Kirim Pengajuan <i class="bi bi-send-fill ms-2"></i></button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    function validateStep(step) {
        let isValid = true;
        let firstError = null;

        if (step ===  1) {
            const fields = [
                {id: 'input_name', label: 'Nama Lengkap'},
                {id: 'input_email', label: 'Email'},
                {id: 'input_phone', label: 'Nomor WhatsApp'},
            ];
            fields.forEach(f => {
                const el = document.getElementById(f.id);
                if (!el.value.trim()){
                    el.classList.add('is-invalid');
                    if(!firstError) firstError = el;
                    isValid = false;
                } else {
                    el.classList.remove('is-invalid');
                }

            });
        }

        if (step === 2) {
            const category = document.getElementById('input_category');
            if(!category.value) {
                category.classList.add('is-invalid');
                if(!firstError) firstError = category;
                isValid = false;
            } else {
                category.classList.remove('is-invalid');
            }
        }

        if (step === 3){
            const photo = document.getElementById('boothPhotoInput');
            if (!photo.files.length) {
                photo.classList.add('is-invalid');
                if(!firstError) firstError = photo;
                isValid = false;
            } else {
                photo.classList.remove('is-invalid');
            }
        }

        if(!isValid && firstError) {
            firstError.focus();
        }

        return isValid;
    }
    // Logic for Multi-Step Form Navigation
    function updateProgress(step) {
        let percent = 0;
        if(step === 1) percent = 0;
        if(step === 2) percent = 50;
        if(step === 3) percent = 100;
        document.getElementById('stepProgress').style.width = percent + '%';
        
        // Reset all classes
        for(let i=1; i<=3; i++) {
            let indicator = document.getElementById('step'+i+'-indicator');
            indicator.classList.remove('active', 'completed');
            if(i < step) indicator.classList.add('completed');
            if(i === step) indicator.classList.add('active');
        }
    }

    function nextStep(step) {
        const currentStep = step - 1;
        if (!validateStep(currentStep)) return;

        document.querySelectorAll('.form-step').forEach(el => el.classList.remove('active'));
        document.getElementById('step'+step).classList.add('active');
        updateProgress(step);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function prevStep(step) {
        document.querySelectorAll('.form-step').forEach(el => el.classList.remove('active'));
        document.getElementById('step'+step).classList.add('active');
        updateProgress(step);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // Image Preview Logic
    document.getElementById('boothPhotoInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imagePreview').src = e.target.result;
                document.getElementById('previewContainer').classList.remove('d-none');
            }
            reader.readAsDataURL(file);
        } else {
            document.getElementById('previewContainer').classList.add('d-none');
        }
    });

    // Form Submit handling
    document.getElementById('tenantJoinForm').addEventListener('submit', function() {
        let btn = document.getElementById('submitBtn');
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Mengirim...';
        btn.disabled = true;
    });
</script>
@endsection
