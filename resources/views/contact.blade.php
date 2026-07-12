@extends('v_layouts.app')

@section('title', 'Hubungi Kami - FiveFest')

@section('content')

<style>
    /* 1. Warna Hero Deep Purple ala About tapi tetap Fresh */
    :root {
        --primary-purple: #8b5cf6;
        --deep-purple: #1e1b4b;
        --bg-light: #f8fafc;
    }

    .contact-hero {
        background: linear-gradient(135deg, #1e1b4b 0%, #4c1d95 100%);
        padding: 100px 0 60px;
        border-bottom: 1px solid #e2e8f0;
        color: white; /* Tulisan jadi putih karena background gelap */
    }

    .info-card {
        transition: 0.3s;
        border-radius: 20px;
        background: #ffffff;
    }

    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(139, 92, 246, 0.1) !important;
    }

    .icon-box-modern {
        width: 55px;
        height: 55px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        transition: 0.3s;
    }

    .form-card-modern {
        border: none;
        border-radius: 30px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.05);
        background: #ffffff;
    }

    .form-control-modern {
        border-radius: 15px;
        padding: 15px 20px;
        border: 1.5px solid #f1f5f9;
        background: #f8fafc;
        transition: 0.3s;
    }

    .form-control-modern:focus {
        background: #ffffff;
        border-color: var(--primary-purple);
        box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.1);
    }

    .btn-send {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        border: none;
        border-radius: 15px;
        font-weight: 700;
        padding: 16px 40px;
        color: white;
        transition: 0.3s;
    }

    .btn-send:hover {
        transform: scale(1.02);
        box-shadow: 0 10px 20px rgba(139, 92, 246, 0.3);
        color: white;
    }

    .social-btn {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.3s;
        text-decoration: none;
    }

    .social-btn:hover {
        transform: translateY(-3px);
    }

    .accordion-item {
        border-radius: 15px !important;
        margin-bottom: 15px;
        border: 1px solid #f1f5f9 !important;
        overflow: hidden;
    }

    .accordion-button:not(.collapsed) {
        background-color: #f5f3ff;
        color: var(--primary-purple);
    }

    @media (max-width: 991px) {
        /* Hero */
        .contact-hero {
            padding: 60px 0 40px !important;
        }

        .contact-hero h1 {
            font-size: 2rem !important;
        }

        .contact-hero .lead {
            font-size: 0.9rem !important;
        }

        /* Section padding */
        .py-5.bg-white .container {
            padding-top: 30px !important;
            padding-bottom: 30px !important;
            padding-left: 20px !important;
            padding-right: 20px !important;
        }

        /* Info cards lebih compact */
        .info-card {
            padding: 14px !important;
            margin-bottom: 12px !important;
            border-radius: 16px !important;
        }

        .icon-box-modern {
            width: 44px !important;
            height: 44px !important;
            border-radius: 12px !important;
            font-size: 1.1rem !important;
            flex-shrink: 0 !important;
        }

        .info-card h6 {
            font-size: 0.85rem !important;
        }

        .info-card p.small {
            font-size: 0.7rem !important;
        }

        /* Form card */
        .form-card-modern .card-body {
            padding: 24px 20px !important;
        }

        .form-card-modern h3 {
            font-size: 1.2rem !important;
            margin-bottom: 20px !important;
        }

        .form-control-modern {
            padding: 11px 14px !important;
            font-size: 0.85rem !important;
            border-radius: 12px !important;
        }

        .btn-send {
            padding: 13px !important;
            font-size: 0.9rem !important;
            border-radius: 12px !important;
            width: 100% !important;
        }

        /* Sosmed buttons */
        .social-btn {
            width: 40px !important;
            height: 40px !important;
            border-radius: 10px !important;
        }

        /* Maps */
         .ratio.ratio-16x9 {
            border-radius: 12px !important;
            margin-bottom: 8px !important;
        }

        .info-card .text-muted.small {
            font-size: 0.75rem !important;
            text-align: left !important;
        }

        .col-lg-4 .h6{
            font-size: 0.9rem !important;
            margin-top: 8px !important;
        }

        .d-flex.gap-3 .social-btn  {
            width: 42px !important;
            height: 42px !important;
        }

        .col-lg-8 {
            margin-top: 8px !important;
        }

        .form-card-modern {
            border-radius: 20px !important;
        }

        .form-label {
            foont-size: 0.82rem !important;
            font-weight: 600 !important;
            margin-bottom: 5px !important;
            color: #374151 !important;
        }

        .row.g-4 > [class*="col-"] {
            padding-top: 0 !important;
            padding-bottom: 0 !important;
            margin-bottom: 14px !important;
        }

        input[type="file"].form-control-modern {
            padding: 8px 12px !important;
            font-size: 0.8rem !important;
        }

        .form-control-modern + small {
            font-size: 0.72rem !important;
        }


        .row.g-4 {
            gap:0 !important;
            row-gap: 14px !important;
        }

        /* Teks justify */
        .info-card p,
        .contact-hero .lead {
            text-align: center !important;
        }

        /* Saluran bantuan header */
        .col-lg-4 .mb-5 {
            margin-bottom: 20px !important;
        }

        .col-lg-4 h3 {
            font-size: 1.3rem !important;
        }
        .col-lg-8 .form-card-modern {
            margin-top: 24px !important;
        }

        section[style*="background-color: #f8fafc"] .container {
            padding-left: 20px !important;
            padding-right: 20px !important;
            padding-top: 30px !important;
            padding-bottom: 30px !important;
        }

        /* Header FAQ */
        section[style*="background-color: #f8fafc"] h2 {
            font-size: 1.5rem !important;
        }

        section[style*="background-color: #f8fafc"] p.text-muted {
            font-size: 0.85rem !important;
        }

        /* Accordion */
        .accordion-item {
            border-radius: 12px !important;
            margin-bottom: 10px !important;
        }

        .accordion-button {
            font-size: 0.875rem !important;
            padding: 14px 16px !important;
            line-height: 1.4 !important;
        }

        .accordion-body {
            font-size: 0.83rem !important;
            padding: 12px 16px 16px !important;
            text-align: justify !important;
        }
    }
</style>

<section class="contact-hero text-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8" data-aos="fade-up">
                <span class="badge px-3 py-2 rounded-pill mb-3" style="background: rgba(255, 255, 255, 0.1); color: #c4b5fd; border: 1px solid rgba(255, 255, 255, 0.2);">
                    CONTACT US
                </span>
                <h1 class="display-3 fw-bold mb-4 text-white">Ada yang Bisa Kami <span style="color: #c4b5fd;">Bantu?</span></h1>
                <p class="lead mb-0 opacity-75">
                    Tim FiveFest siap dengerin curhatan atau pertanyaan kamu seputar tiket konser. Jangan ragu buat sapa kami ya!
                </p>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-white">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-lg-4" data-aos="fade-right">
                <div class="mb-5">
                    <h3 class="fw-bold text-dark mb-3">Saluran Bantuan</h3>
                    <p class="text-muted">Pilih cara ternyaman buat kamu hubungi kami.</p>
                </div>

                <div class="info-card border p-4 mb-4 shadow-sm">
                    <div class="d-flex align-items-center">
                        <div class="icon-box-modern bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-telephone-fill"></i>
                        </div>
                        <div class="ms-3">
                            <p class="text-muted small mb-0">No. Telepon</p>
                            <h6 class="fw-bold mb-0">+62 812-3456-7890</h6>
                        </div>
                    </div>
                </div>

                <div class="info-card border p-4 mb-4 shadow-sm">
                    <div class="d-flex align-items-center">
                        <div class="icon-box-modern bg-success bg-opacity-10 text-success">
                            <i class="bi bi-envelope-paper-fill"></i>
                        </div>
                        <div class="ms-3">
                            <p class="text-muted small mb-0">Email Support</p>
                            <h6 class="fw-bold mb-0">support@fivefest.com</h6>
                        </div>
                    </div>
                </div>

                <div class="info-card border p-3 mb-4 shadow-sm">
                    <p class="fw-bold small mb-2 text-primary"><i class="bi bi-geo-alt-fill me-1"></i> Temui Kami Di:</p>
                    <div class="ratio ratio-16x9 rounded-4 overflow-hidden mb-2">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126906.56846141386!2d107.23415170067623!3d-6.30303887565507!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6977ec8f02908f%3A0x401576d14193020!2sKarawang%2C%20Karawang%20Regency%2C%20West%20Java!5e0!3m2!1sen!2sid!4v1712345678901!5m2!1sen!2sid" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                    <p class="text-muted small mb-0 mt-2">Jl. Karawang No. 123, Karawang Barat, 41311.</p>
                </div>

                <h6 class="fw-bold mb-3">Temukan Kami di Sosmed</h6>
                <div class="d-flex gap-3">
                    <a href="#" class="social-btn bg-primary text-white"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="social-btn bg-danger text-white"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="social-btn bg-dark text-white"><i class="bi bi-tiktok"></i></a>
                    <a href="#" class="social-btn bg-success text-white"><i class="bi bi-whatsapp"></i></a>
                </div>
            </div>

            <div class="col-lg-8" data-aos="fade-left">
                <div class="card form-card-modern shadow-lg">
                    <div class="card-body p-4 p-md-5">
                        <h3 class="fw-bold mb-4">Kirim Pesan Cepat</h3>

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <h5 class="alert-heading fw-bold mb-2">Terjadi Kesalahan</h5>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ url('/contact/send') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" name="name" class="form-control form-control-modern" placeholder="Isi Nama Lengkap Kamu" required value="{{ old('name', auth()->check() ? auth()->user()->name : '') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email Aktif</label>
                                    <input type="email" name="email" class="form-control form-control-modern" placeholder="email@contoh.com" required value="{{ old('email', auth()->check() ? auth()->user()->email : '' )}}">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Subjek Pertanyaan</label>
                                    <select name="subject" class="form-select form-control-modern" required>
                                        <option value="">Pilih keperluan...</option>
                                        <option value="booking" {{ old('subject') == 'booking' ? 'selected' : '' }}>Masalah Booking Tiket</option>
                                        <option value="refund" {{ old('subject') == 'refund' ? 'selected' : '' }}>Refund Dana</option>
                                        <option value="partnership" {{ old('subject') == 'partnership' ? 'selected' : '' }}>Kerjasama / Partnership</option>
                                        <option value="other" {{ old('subject') == 'other' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Pesan</label>
                                    <textarea name="message" class="form-control form-control-modern" rows="5" placeholder="Tulis pesan kamu di sini ya..." required>{{ old('message') }}</textarea>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">
                                        <i class="bi bi-image me-2"></i>Upload Foto (Opsional)
                                    </label>
                                    <div class="position-relative">
                                        <input type="file" name="photo" id="photoInput" class="form-control form-control-modern" accept="image/*" onchange="previewPhoto(event)">
                                        <small class="text-muted d-block mt-2">Maksimal ukuran: 5MB. Format: JPG, PNG, GIF</small>
                                    </div>
                                    <div id="photoPreview" class="mt-3"></div>
                                </div>
                                <div class="col-md-12 text-end">
                                    <button type="submit" class="btn btn-send w-100">
                                        Kirim Sekarang <i class="bi bi-send ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modals Section -->
@auth
@if(isset($messages))
    @foreach($messages as $msg)
        @if($msg->status === 'replied' && $msg->admin_notes)
        <div class="modal fade" id="replyModal{{ $msg->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 30px; overflow: hidden;">
                    <div class="modal-header bg-success bg-opacity-10 border-0 p-4">
                        <h5 class="modal-title fw-bold text-success mb-0">
                            <i class="bi bi-chat-left-dots-fill me-2"></i>Balasan Admin
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4 p-md-5">
                        <div class="mb-4">
                            <label class="small fw-bold text-muted text-uppercase mb-2 d-block">Pesan Kamu:</label>
                            <div class="p-3 bg-light rounded-3 border-start border-4 border-primary">
                                <p class="mb-0 text-muted fst-italic">"{{ $msg->message }}"</p>
                            </div>
                        </div>

                        <div>
                            <label class="small fw-bold text-success text-uppercase mb-2 d-block">Balasan Admin:</label>
                            <div class="p-4 rounded-4" style="background: #f0fdf4; border: 1px solid #dcfce7;">
                                <p class="mb-3 text-dark fw-medium lh-base" style="font-size: 1.05rem;">
                                    {!! nl2br(e($msg->admin_notes)) !!}
                                </p>
                                <div class="d-flex align-items-center gap-2 pt-3 border-top border-success border-opacity-10 mt-2">
                                    <i class="bi bi-calendar-check text-success"></i>
                                    <small class="text-muted">
                                        Dibalas pada: <strong>{{ $msg->replied_at ? $msg->replied_at->format('d M Y, H:i') : ($msg->updated_at ? $msg->updated_at->format('d M Y, H:i') : '-') }}</strong>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-light w-100 rounded-pill fw-bold py-3" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endforeach
@endif
@endauth

<section class="py-5" style="background-color: #f8fafc;">
    <div class="container py-5">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="display-6 fw-bold mb-3">FAQ Sederhana</h2>
            <p class="text-muted">Cek dulu di sini ya, siapa tahu pertanyaan kamu sudah terjawab!</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="accordion accordion-flush" id="faqAccordion">
                    <div class="accordion-item shadow-sm border" data-aos="fade-up">
                        <h2 class="accordion-header">
                            <button class="accordion-button fw-bold py-4" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                Bagaimana cara dapat E-Tiket setelah bayar?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted pb-4">
                                E-Tiket bakal muncul otomatis di menu <strong>Tiket Saya</strong> di profil Isa setelah pembayaran terverifikasi.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item shadow-sm border" data-aos="fade-up" data-aos-delay="100">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold py-4" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                Apakah pembayaran di FiveFest aman?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted pb-4">
                                Aman 100%! Kami pake sistem enkripsi standar bank dan gateway resmi.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item shadow-sm border" data-aos="fade-up" data-aos-delay="200">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold py-4" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                Bisakah saya melakukan refund?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted pb-4">
                                Refund hanya bisa dilakukan jika acara resmi dibatalkan atau ditunda oleh pihak promotor.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item shadow-sm border" data-aos="fade-up" data-aos-delay="300">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold py-4" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                Bagaimana jika email tiket saya tidak masuk?
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted pb-4">
                                Kamu bisa login ke akun FiveFest, lalu cek menu <strong>Tiket Saya</strong>. Semua tiket kamu ada di sana tanpa perlu buka email.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function previewPhoto(event) {
    const preview = document.getElementById('photoPreview');
    const file = event.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `
                <div class="position-relative" style="display: inline-block;">
                    <img src="${e.target.result}" alt="Preview" class="rounded" style="max-width: 200px; max-height: 200px; object-fit: cover;">
                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 rounded-circle" onclick="clearPhoto()" style="width: 30px; height: 30px; padding: 0;">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
            `;
        }
        reader.readAsDataURL(file);
    }
}

function clearPhoto() {
    document.getElementById('photoInput').value = '';
    document.getElementById('photoPreview').innerHTML = '';
}
</script>

@endsection