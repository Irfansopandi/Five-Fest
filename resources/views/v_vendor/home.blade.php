@extends('v_layouts.app')

@section('title', 'FiveFest - Be a Partner')

@section('content')
@php use Illuminate\Support\Str; @endphp



{{-- 3. COMPREHENSIVE GUIDE SECTION --}}
<section class="py-5 bg-white">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="fw-black display-5" style="color: #1e1b4b;">Panduan Lengkap <span class="text-purple-magic">Buat Event</span></h2>
            <p class="text-muted">Ikuti langkah mudah untuk mulai menjual tiket event mahakarya Anda.</p>
        </div>

        <div class="row g-4 align-items-stretch">
            {{-- Column 1 --}}
            <div class="col-lg-4 d-flex flex-column gap-3">
                <div class="card border-0 shadow-sm p-4 rounded-4 bg-light flex-fill guide-card">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="guide-badge">01</div>
                        <h5 class="fw-bold mb-0" style="color: #1e1b4b;">Persiapan Visual</h5>
                    </div>
                    <p class="text-muted small mb-0">Siapkan poster event terbaik Anda dengan rasio 724x340 pixel. Poster yang menarik meningkatkan minat pembeli hingga 40%.</p>
                </div>
                <div class="card border-0 shadow-sm p-4 rounded-4 bg-light flex-fill guide-card">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="guide-badge">02</div>
                        <h5 class="fw-bold mb-0" style="color: #1e1b4b;">Data Dasar Event</h5>
                    </div>
                    <p class="text-muted small mb-0">Isi Judul, Kategori (K-Pop, Indie, Festival, dll), serta Lokasi yang terintegrasi dengan Google Maps untuk memudahkan navigasi penonton.</p>
                </div>
                <div class="card border-0 shadow-sm p-4 rounded-4 bg-light flex-fill guide-card">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="guide-badge">03</div>
                        <h5 class="fw-bold mb-0" style="color: #1e1b4b;">Kategori Tiket</h5>
                    </div>
                    <p class="text-muted small mb-0">Buat tiket Seating (dengan nomor kursi) atau Standing. Tentukan harga dan kuota untuk setiap kategori secara fleksibel.</p>
                </div>
            </div>

            {{-- Column 2 (Image/Illustration) --}}
            <div class="col-lg-4 d-none d-lg-flex align-items-center justify-content-center text-center">
                <div class="sticky-top" style="top: 100px;">
                    <img src="https://img.freepik.com/free-vector/digital-marketing-concept-illustration_114360-1011.jpg" class="img-fluid rounded-5 shadow-lg" alt="Dashboard Preview" style="max-height: 400px; object-fit: cover;">
                </div>
            </div>

            {{-- Column 3 --}}
            <div class="col-lg-4 d-flex flex-column gap-3">
                <div class="card border-0 shadow-sm p-4 rounded-4 bg-light flex-fill guide-card">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="guide-badge">04</div>
                        <h5 class="fw-bold mb-0" style="color: #1e1b4b;">Deskripsi & S&K</h5>
                    </div>
                    <p class="text-muted small mb-0">Berikan informasi detail mengenai lineup artis, jadwal acara, dan syarat masuk yang harus dipatuhi oleh pengunjung.</p>
                </div>
                <div class="card border-0 shadow-sm p-4 rounded-4 bg-light flex-fill guide-card">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="guide-badge">05</div>
                        <h5 class="fw-bold mb-0" style="color: #1e1b4b;">Atur Penjualan</h5>
                    </div>
                    <p class="text-muted small mb-0">Tentukan waktu "War Ticket" Anda. Atur limit pembelian per transaksi untuk mencegah bot dan scalper.</p>
                </div>
                <div class="card border-0 shadow-sm p-4 rounded-4 bg-light flex-fill guide-card">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="guide-badge">06</div>
                        <h5 class="fw-bold mb-0" style="color: #1e1b4b;">Tarik Pendapatan</h5>
                    </div>
                    <p class="text-muted small mb-0">Pantau penjualan secara real-time. Dana penjualan tiket akan langsung masuk ke rekening yang Anda daftarkan.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .guide-card {
        border: 1px solid rgba(0,0,0,0.03) !important;
        transition: all 0.3s ease;
    }
    .guide-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(124, 58, 237, 0.08) !important;
        background: white !important;
        border-color: rgba(124, 58, 237, 0.15) !important;
    }
    .guide-badge {
        background: linear-gradient(135deg, #7c3aed, #db2777);
        color: white;
        width: 38px;
        height: 38px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1rem;
        box-shadow: 0 4px 10px rgba(124, 58, 237, 0.2);
    }
    .text-purple-magic {
        background: linear-gradient(45deg, #7c3aed, #db2777);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
</style>

{{-- 1. PRICING & SIMULATION SECTION --}}
<section class="py-5 bg-white overflow-hidden">
    <div class="container py-5">
        <div class="row align-items-center gy-5">
            <div class="col-lg-6" data-aos="fade-right">
                <h2 class="fw-black mb-4 display-5" style="color: #1e1b4b; letter-spacing: -1.5px;">Biaya Transaksi <span class="text-purple-magic">Paling Bersaing</span></h2>
                <p class="text-muted mb-5 fs-5 lh-base">Kami percaya pada pertumbuhan bersama. FiveFest hanya mengenakan biaya layanan kecil untuk setiap tiket yang terjual, tanpa biaya pendaftaran atau biaya tersembunyi lainnya.</p>
                
                <div class="card border-0 shadow-sm p-4 rounded-5 bg-light mb-4 border-start border-4 border-primary">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-bold h5 mb-0">Biaya Layanan Platform</span>
                        <span class="badge bg-purple-deep px-4 py-2 rounded-pill shadow-sm">Hanya 3%</span>
                    </div>
                    <p class="small text-muted mb-0">Biaya ini sudah mencakup pemeliharaan server, sistem antrean war ticket, dan dukungan teknis 24/7 selama event berlangsung.</p>
                </div>

                <div class="d-flex flex-wrap gap-3 mt-5">
                    <a href="{{ route('register.vendor.show') }}" class="btn btn-dark btn-lg rounded-pill px-5 fw-bold py-3 shadow hover-up">Daftar Sekarang</a>
                    <a href="https://wa.me/6285946653103" class="btn btn-outline-dark btn-lg rounded-pill px-5 fw-bold py-3 hover-up">Hubungi Sales</a>
                </div>
            </div>
            <div class="col-lg-5 offset-lg-1" data-aos="fade-left">
                <div class="card border-0 shadow-2xl p-5 rounded-5 bg-purple-deep text-white position-relative overflow-hidden card-glow">
                    <div class="position-absolute top-0 end-0 p-4 opacity-10"><i class="bi bi-calculator display-1"></i></div>
                    <h5 class="fw-bold mb-4 d-flex align-items-center"><i class="bi bi-lightning-fill text-warning me-2"></i> Simulasi Penjualan</h5>
                    <div class="mb-3">
                        <label class="small opacity-75 d-block mb-1 text-uppercase ls-1">Harga Tiket Anda</label>
                        <div class="fs-3 fw-bold">Rp 1.000.000</div>
                    </div>
                    <div class="mb-3 border-top border-white border-opacity-25 pt-3">
                        <label class="small opacity-75 d-block mb-1 text-uppercase ls-1">Fee Platform (3%)</label>
                        <div class="fs-5 fw-bold text-warning">- Rp 30.000</div>
                    </div>
                    <div class="mt-4 border-top border-white border-opacity-50 pt-3">
                        <label class="small opacity-75 d-block mb-1 text-uppercase ls-1">Pendapatan Bersih Anda</label>
                        <div class="fs-1 fw-black">Rp 970.000</div>
                    </div>
                    <div class="mt-4 bg-white bg-opacity-10 p-3 rounded-4">
                        <p class="small mb-0 opacity-90"><i class="bi bi-info-circle me-1"></i> Dana cair otomatis ke rekening terdaftar setelah rekonsiliasi data.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .ls-1 { letter-spacing: 1px; }
    .card-glow { box-shadow: 0 25px 50px -12px rgba(124, 58, 237, 0.5); }
    .hover-up:hover { transform: translateY(-5px); transition: 0.3s; }
</style>

{{-- 2. DASHBOARD CAPABILITIES --}}
<section class="py-5 bg-light" style="border-radius: 80px 80px 0 0;">
    <div class="container py-5">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="fw-black mb-3 display-6">Alat Tempur <span class="text-purple-magic">Promotor Modern</span></h2>
            <p class="text-muted fs-5">Semua yang Anda butuhkan untuk mengelola event dari skala kecil hingga stadion.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="card h-100 border-0 shadow-sm p-4 rounded-5 feature-card-hover">
                    <div class="mb-4 bg-primary-subtle rounded-4 d-inline-flex p-3 text-primary" style="align-self: flex-start;"><i class="bi bi-person-check fs-2"></i></div>
                    <h5 class="fw-bold mb-3">Manajemen Data Pembeli</h5>
                    <p class="text-muted small lh-lg">Dapatkan data detail pembeli (Nama, Email, HP) secara real-time untuk kebutuhan database marketing Anda di masa depan.</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="card h-100 border-0 shadow-sm p-4 rounded-5 feature-card-hover">
                    <div class="mb-4 bg-danger-subtle rounded-4 d-inline-flex p-3 text-danger" style="align-self: flex-start;"><i class="bi bi-ticket-detailed fs-2"></i></div>
                    <h5 class="fw-bold mb-3">Custom E-Ticket Design</h5>
                    <p class="text-muted small lh-lg">Kirim e-tiket profesional dengan QR Code unik langsung ke WhatsApp dan Email pembeli secara otomatis.</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                <div class="card h-100 border-0 shadow-sm p-4 rounded-5 feature-card-hover">
                    <div class="mb-4 bg-success-subtle rounded-4 d-inline-flex p-3 text-success" style="align-self: flex-start;"><i class="bi bi-phone-vibrate fs-2"></i></div>
                    <h5 class="fw-bold mb-3">Aplikasi Scan Offline</h5>
                    <p class="text-muted small lh-lg">Sistem check-in kami tetap bekerja stabil meski koneksi internet di lokasi acara sedang tidak stabil.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .feature-card-hover { transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    .feature-card-hover:hover { 
        transform: translateY(-15px) scale(1.02); 
        box-shadow: 0 25px 40px rgba(0,0,0,0.08) !important;
        background: white;
    }
</style>

{{-- 3. FAQ SECTION --}}
<section class="py-5 bg-white">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="fw-black mb-3">Pertanyaan <span class="text-purple-magic">Sering Diajukan</span></h2>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-8 px-4 px-md-0">
                <div class="accordion accordion-flush" id="faqAccordion">
                    <div class="accordion-item border-bottom py-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold bg-transparent shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                Berapa lama proses verifikasi akun vendor?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted small">
                                Proses verifikasi informasi dasar dan legalitas biasanya memakan waktu 1x24 jam pada hari kerja setelah dokumen diunggah secara lengkap.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-bottom py-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold bg-transparent shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                Apakah FiveFest mendukung pembayaran cicilan?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted small">
                                Ya, melalui Midtrans, pembeli tiket Anda dapat menggunakan fitur kartu kredit cicilan 0% atau layanan Paylater sesuai ketersediaan.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-bottom py-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold bg-transparent shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                Bagaimana jika saya butuh bantuan teknis saat hari-H?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted small">
                                Kami menyediakan tim Support On-Site (Opsional) atau Dedicated Technical Support Online yang siap siaga memantau sistem dari pembukaan gate hingga acara selesai.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- 4. PARTNERS MARQUEE (KEEPING THIS AS IT SHOWS CREDIBILITY) --}}
<section class="py-5 bg-white border-top border-bottom overflow-hidden">
    <div class="container py-4">
        <div class="marquee-container">
            <div class="marquee-content">
                <div class="marquee-item"><img src="{{ asset('storage/images/logo/logo mecima.png') }}" alt="Mecima Pro" onerror="this.src='{{ asset('storage/events/logo mecima.png') }}'"></div>
                <div class="marquee-item"><img src="{{ asset('storage/images/logo/logo ime.png') }}" alt="Ime Indonesia" onerror="this.src='{{ asset('storage/events/logo ime.png') }}'"></div>
                <div class="marquee-item"><img src="{{ asset('storage/images/logo/logo dyandra.png') }}" alt="Dyandra Global" onerror="this.src='{{ asset('storage/events/logo dyandra.png') }}'"></div>
                <div class="marquee-item"><img src="{{ asset('storage/images/logo/logo pk.png') }}" alt="PK Entertainment" onerror="this.src='{{ asset('storage/events/logo pk.png') }}'"></div>
                <div class="marquee-item"><img src="{{ asset('storage/images/logo/logo ck.png') }}" alt="CK Star" onerror="this.src='{{ asset('storage/events/logo ck.png') }}'"></div>
                
                <div class="marquee-item"><img src="{{ asset('storage/images/logo/logo mecima.png') }}" alt="Mecima Pro" onerror="this.src='{{ asset('storage/events/logo mecima.png') }}'"></div>
                <div class="marquee-item"><img src="{{ asset('storage/images/logo/logo ime.png') }}" alt="Ime Indonesia" onerror="this.src='{{ asset('storage/events/logo ime.png') }}'"></div>
                <div class="marquee-item"><img src="{{ asset('storage/images/logo/logo dyandra.png') }}" alt="Dyandra Global" onerror="this.src='{{ asset('storage/events/logo dyandra.png') }}'"></div>
                <div class="marquee-item"><img src="{{ asset('storage/images/logo/logo pk.png') }}" alt="PK Entertainment" onerror="this.src='{{ asset('storage/events/logo pk.png') }}'"></div>
                <div class="marquee-item"><img src="{{ asset('storage/images/logo/logo ck.png') }}" alt="CK Star" onerror="this.src='{{ asset('storage/events/logo ck.png') }}'"></div>
            </div>
        </div>
    </div>
</section>

<style>
    .marquee-container { width: 100%; overflow: hidden; padding: 20px 0; }
    .marquee-content { display: flex; width: max-content; animation: marquee 30s linear infinite; }
    .marquee-item { margin: 0 40px; flex-shrink: 0; opacity: 0.6; }
    .marquee-item img { height: 40px; filter: grayscale(1); transition: 0.3s; }
    .marquee-item:hover img { filter: grayscale(0); opacity: 1; }
    @keyframes marquee { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
</style>

@endsection
