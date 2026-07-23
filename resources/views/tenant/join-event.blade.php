@extends('v_layouts.app')
@section('title', 'Buka Stand di ' . $event->title)

@section('content')
<style>
    /* Light Theme Styling for Guide & Simulation Sections */
    .tenant-guide-bg {
        background-color: #ffffff;
        color: #1e1b4b;
    }
    .tenant-sim-bg {
        background-color: #f8fafc;
        color: #1e1b4b;
    }
    .text-orange-custom {
        color: #f97316;
    }
    .text-purple-custom {
        color: #a855f7;
    }
    .guide-card {
        background-color: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 15px;
        padding: 25px;
        position: relative;
        overflow: hidden;
        height: 100%;
        transition: 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02);
    }
    .guide-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(168, 85, 247, 0.1);
        border-color: #a855f7;
    }
    .guide-number {
        font-size: 4rem;
        font-weight: 900;
        color: rgba(168, 85, 247, 0.08);
        position: absolute;
        top: -5px;
        right: 15px;
        line-height: 1;
        transition: 0.3s;
    }
    .guide-card:hover .guide-number {
        color: rgba(168, 85, 247, 0.2);
        transform: scale(1.1);
    }
    .guide-badge {
        font-size: 0.75rem;
        padding: 6px 14px;
        border-radius: 20px;
        display: inline-block;
        margin-top: 15px;
        background: #f1f5f9;
        color: #475569;
        font-weight: 700;
    }
    .sim-card {
        background: linear-gradient(135deg, #a855f7 0%, #7c3aed 100%);
        border-radius: 25px;
        padding: 40px;
        color: #fff;
    }
    .info-card {
        background-color: #ffffff;
        border-radius: 15px;
        padding: 25px;
        border: 1px solid #edf2f7;
        margin-bottom: 20px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.02);
        transition: 0.3s;
    }
    .info-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
    }

    /* Styling for Fasilitas & FAQ */
    .fasilitas-card {
        background: #fff;
        border-radius: 16px;
        padding: 30px;
        height: 100%;
        border: 1px solid #edf2f7;
        box-shadow: 0 4px 6px rgba(0,0,0,0.02);
        transition: 0.3s;
    }
    .fasilitas-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
    }
    .fasilitas-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 20px;
    }
    .faq-accordion .accordion-item {
        border: none;
        border-bottom: 1px solid #edf2f7;
        background: transparent;
    }
    .faq-accordion .accordion-button {
        background: transparent;
        font-weight: 700;
        color: #1e1b4b;
        padding: 20px 0;
        box-shadow: none !important;
    }
    .faq-accordion .accordion-button:not(.collapsed) {
        color: #a855f7;
    }
    .faq-accordion .accordion-body {
        padding: 0 0 20px 0;
        color: #64748b;
    }
    .marquee-container {
        overflow: hidden;
        white-space: nowrap;
        position: relative;
        width: 100%;
        padding: 10px 0;
    }
    .marquee-container::before,
    .marquee-container::after {
        content: "";
        position: absolute;
        top: 0;
        width: 100px;
        height: 100%;
        z-index: 2;
    }
    .marquee-container::before {
        left: 0;
        background: linear-gradient(to right, #ffffff, transparent);
    }
    .marquee-container::after {
        right: 0;
        background: linear-gradient(to left, #ffffff, transparent);
    }
    .marquee-track {
        display: inline-flex;
        animation: scroll-logos 15s linear infinite;
        align-items: center;
    }
    .marquee-track:hover {
        animation-play-state: paused;
    }
    .vendor-logo-text {
        filter: grayscale(100%) opacity(50%);
        transition: 0.3s;
        cursor: default;
        white-space: nowrap;
    }
    .vendor-logo-text:hover {
        filter: grayscale(0%) opacity(100%);
        color: #1e1b4b !important;
    }
    @keyframes scroll-logos {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }


    /* slider vendor */
    .concert-card-hover:hover {
        transform: translateY(-10px) !important;
        box-shadow: 0 15px 30px rgba(139, 92, 246, 0.15) !important;
    }
    .marquee-container {
        position: relative;
        width: 100%;
        overflow: hidden;
        padding: 20px 0;
    }
    .marquee-content {
        display: flex;
        width: max-content;
        animation: marquee 30s linear infinite;
    }
    .marquee-item {
        margin: 0 40px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0.6;
        transition: opacity 0.3s ease;
    }
    .marquee-item:hover {
        opacity: 1;
    }
    .marquee-item img {
        height: 45px;
        width: auto;
        filter: grayscale(1);
        transition: filter 0.3s ease;
    }
    .marquee-item:hover img {
        filter: grayscale(0);
    }
    .brand-text-marquee {
        font-weight: 900;
        font-size: 1.2rem;
        color: #6d28d9;
        letter-spacing: -0.5px;
    }
    .brand-text-marquee .text-highlight {
        color: #a855f7;
    }

    @keyframes marquee {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }
</style>

<div class="tenant-guide-bg py-5">
    <div class="container py-5">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="fw-bold display-5" style="letter-spacing: -1px;">Panduan Lengkap <span class="text-orange-custom">Jadi Tenant</span> Event</h2>
            <p class="text-muted fs-5">Ikuti langkah mudah untuk membuka booth UMKM-mu di event terbaik.</p>
        </div>

        <div class="row g-4 align-items-center">
            <!-- Col 1 -->
            <div class="col-md-4">
                <div class="guide-card mb-4" style="border-left: 5px solid #f97316;" data-aos="fade-up" data-aos-delay="100">
                    <div class="guide-number">01</div>
                    <h5 class="fw-bold mb-3 text-dark">Daftar Akun UMKM</h5>
                    <p class="text-muted small mb-0">Buat akun dengan memilih tipe "Tenant / UMKM". Isi nama usaha, jenis produk (kuliner, fashion, kerajinan, dll), dan kontak penanggung jawab.</p>
                </div>
                <div class="guide-card mb-4" style="border-left: 5px solid #f97316;" data-aos="fade-up" data-aos-delay="200">
                    <div class="guide-number">02</div>
                    <h5 class="fw-bold mb-3 text-dark">Pilih Event & Ajukan Diri</h5>
                    <p class="text-muted small mb-0">Lihat daftar event yang membuka pendaftaran tenant, lalu ajukan usahamu. Lokasi booth akan ditentukan oleh vendor acara sesuai kategori usaha.</p>
                    <div class="guide-badge"><i class="bi bi-geo-alt-fill text-danger me-1"></i> Spot ditentukan vendor</div>
                </div>
                <div class="guide-card" style="border-left: 5px solid #f97316;" data-aos="fade-up" data-aos-delay="300">
                    <div class="guide-number">03</div>
                    <h5 class="fw-bold mb-3 text-dark">Upload Dokumen</h5>
                    <p class="text-muted small mb-0">Lampirkan foto produk, NIB atau izin usaha, serta foto booth sebelumnya (opsional) untuk mempercepat proses verifikasi.</p>
                </div>
            </div>

            <!-- Col 2: Center Image -->
            <div class="col-md-4 text-center" data-aos="zoom-in" data-aos-delay="200">
                <div class="bg-white p-4 rounded-4 shadow mx-auto position-relative border" style="max-width: 300px; border-color: #f8fafc !important;">
                    <img src="{{ asset('storage/images/booth_illustration.png') }}" class="img-fluid rounded" alt="Booth Illustration">
                    <div class="mt-4 badge bg-white text-orange-custom border border-warning rounded-pill px-4 py-2 shadow-sm fs-6">
                        <i class="bi bi-check-circle-fill text-success me-1"></i> Booth Terverifikasi
                    </div>
                </div>
            </div>

            <!-- Col 3 -->
            <div class="col-md-4">
                <div class="guide-card mb-4" style="border-left: 5px solid #a855f7;" data-aos="fade-up" data-aos-delay="400">
                    <div class="guide-number">04</div>
                    <h5 class="fw-bold mb-3 text-dark">Terima Konfirmasi Booth</h5>
                    <p class="text-muted small mb-0">Vendor akan menginformasikan nomor booth dan lokasimu di area event. Kamu akan mendapat notifikasi beserta detail denah penempatan.</p>
                    <div class="guide-badge" style="background: #f3e8ff; color: #7e22ce;"><i class="bi bi-bell-fill text-warning me-1"></i> Notifikasi otomatis</div>
                </div>
                <div class="guide-card mb-4" style="border-left: 5px solid #a855f7;" data-aos="fade-up" data-aos-delay="500">
                    <div class="guide-number">05</div>
                    <h5 class="fw-bold mb-3 text-dark">Bayar Sewa Booth</h5>
                    <p class="text-muted small mb-0">Selesaikan pembayaran sewa booth sesuai nominal yang ditetapkan vendor. Bukti pembayaran dikirim otomatis ke email terdaftar.</p>
                </div>
                <div class="guide-card" style="border-left: 5px solid #a855f7;" data-aos="fade-up" data-aos-delay="600">
                    <div class="guide-number">06</div>
                    <h5 class="fw-bold mb-3 text-dark">Berjualan di Hari H</h5>
                    <p class="text-muted small mb-0">Datang sesuai jadwal setup yang ditentukan vendor. Semua hasil penjualanmu 100% menjadi milikmu — tanpa potongan komisi.</p>
                    <div class="guide-badge" style="background: #f3e8ff; color: #7e22ce;"><i class="bi bi-cash-coin text-warning me-1"></i> Omzet 100% milikmu</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="tenant-sim-bg py-5 border-top border-light">
    <div class="container py-5">
        <div class="row align-items-center g-5">
            <!-- Left Info -->
            <div class="col-lg-6 pe-lg-5" data-aos="fade-right">
                <h2 class="fw-bold display-5 mb-4" style="letter-spacing: -1px;">Sewa Booth, <br><span class="text-purple-custom">Omzet 100% Milikmu</span></h2>
                <p class="text-muted mb-5 fs-5">Vendor hanya menyediakan tempat. Semua hasil penjualanmu langsung masuk ke kantongmu sendiri — tanpa potongan komisi, tanpa bagi hasil.</p>

                <div class="info-card" style="border-left: 5px solid #a855f7;">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="fw-bold mb-0 text-dark">Biaya Sewa Booth</h5>
                        <span class="badge" style="background-color: #f3e8ff; color: #7e22ce; border-radius: 20px; padding: 6px 12px;">Flat per Event</span>
                    </div>
                    <p class="text-muted small mb-0">Sudah termasuk meja, listrik dasar, dan akses denah digital event. Dibayar di muka saat konfirmasi pendaftaran.</p>
                </div>

                <div class="info-card" style="border-left: 5px solid #ef4444;">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="fw-bold mb-0 text-dark">Fee Platform</h5>
                        <span class="badge bg-danger" style="background-color: #fee2e2 !important; color: #b91c1c !important; border-radius: 20px; padding: 6px 12px;">3%</span>
                    </div>
                    <p class="text-muted small mb-0">Terdapat potongan biaya layanan platform sebesar 3% dari setiap transaksi. Sisanya 97% langsung masuk ke pendapatan Anda.</p>
                </div>

                <div class="mt-5 d-flex flex-wrap gap-3">
                    @php
                        $existingJoin = null;
                        if(auth()->check()){
                            $existingJoin = \App\Models\EventTenant::where('event_id', $event->id)
                                            ->where('tenant_id', auth()->id())
                                            ->first();
                        }
                    @endphp

                    @if($existingJoin && $existingJoin->status != 'rejected')
                        @if($existingJoin->status == 'pending')
                            <div class="alert alert-warning border-warning rounded-pill px-4 py-3 mb-0 d-flex align-items-center fw-bold shadow-sm">
                                <i class="bi bi-hourglass-split me-2 fs-5"></i> Pengajuan Sedang Diproses
                            </div>
                        @elseif($existingJoin->status == 'approved')
                            <a href="{{ route('tenant.booths.index') }}" class="btn btn-success px-5 py-3 fw-bold rounded-pill shadow-sm" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none;">
                                <i class="bi bi-wallet2 me-2"></i> Lanjut Pembayaran
                            </a>
                        @endif
                    @else
                        @if($existingJoin && $existingJoin->status == 'rejected')
                            <div class="alert alert-danger rounded-pill px-4 py-3 mb-0 d-flex align-items-center fw-bold shadow-sm">
                                <i class="bi bi-x-circle-fill me-2 fs-5"></i> Pengajuan Ditolak
                            </div>
                        @endif
                        
                        @if(isset($isQuotaFull) && $isQuotaFull)
                            <div class="alert alert-danger rounded-pill px-4 py-3 mb-0 d-flex align-items-center fw-bold shadow-sm">
                                <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i> Kuota Tenant Penuh
                            </div>
                        @else
                            <a href="{{ route('event.detail', $event->id) }}" class="btn btn-primary px-5 py-3 fw-bold rounded-pill shadow-sm" style="background: linear-gradient(135deg, #a855f7 0%, #7c3aed 100%); border: none;">
                                Daftar Sekarang
                            </a>
                        @endif
                    @endif
                    
                    @php
                        $vendorPhone = $event->vendor->phone ?? '';
                        $vendorPhone = preg_replace('/[^0-9]/', '', $vendorPhone);
                        if (str_starts_with($vendorPhone, '0')) {
                            $vendorPhone = '62' . substr($vendorPhone, 1);
                        }
                        
                        if ($vendorPhone) {
                            $message = urlencode("Halo, saya tertarik untuk menyewa booth/stand tenant di event " . $event->title . ". Apakah ada informasi lebih lanjut?");
                            $waUrl = "https://wa.me/" . $vendorPhone . "?text=" . $message;
                        } else {
                            $waUrl = route('contact');
                        }
                    @endphp
                    <a href="{{ $waUrl }}" target="_blank" class="btn btn-outline-dark px-5 py-3 fw-bold rounded-pill shadow-sm">Hubungi Sales</a>
                </div>
            </div>

            <!-- Right Simulation -->
            <div class="col-lg-6" data-aos="fade-left" data-aos-delay="200">
                <div class="sim-card shadow-lg">
                    <div class="d-flex align-items-center mb-5">
                        <i class="bi bi-lightning-charge-fill text-warning fs-3 me-3"></i>
                        <h4 class="fw-bold mb-0">Simulasi Pendapatan Booth</h4>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="small fw-bold text-uppercase opacity-75">Harga Rata-rata</span>
                        <div style="flex-grow: 1; height: 4px; background: rgba(255,255,255,0.2); border-radius: 2px; margin: 0 15px; position: relative;">
                            <div style="position: absolute; width: 16px; height: 16px; background: #fff; border-radius: 50%; top: -6px; left: 30%; box-shadow: 0 2px 5px rgba(0,0,0,0.2);"></div>
                        </div>
                        <span class="fw-bold">Rp 50rb</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="small fw-bold text-uppercase opacity-75">Est. Transaksi</span>
                        <div style="flex-grow: 1; height: 4px; background: rgba(255,255,255,0.2); border-radius: 2px; margin: 0 15px; position: relative;">
                            <div style="position: absolute; width: 16px; height: 16px; background: #fff; border-radius: 50%; top: -6px; left: 50%; box-shadow: 0 2px 5px rgba(0,0,0,0.2);"></div>
                        </div>
                        <span class="fw-bold">100 trx</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="small fw-bold text-uppercase opacity-75">Biaya Sewa Booth</span>
                        <div style="flex-grow: 1; height: 4px; background: rgba(255,255,255,0.2); border-radius: 2px; margin: 0 15px; position: relative;">
                            <div style="position: absolute; width: 16px; height: 16px; background: #fff; border-radius: 50%; top: -6px; left: 20%; box-shadow: 0 2px 5px rgba(0,0,0,0.2);"></div>
                        </div>
                        <span class="fw-bold">Rp 500rb</span>
                    </div>

                    <hr class="border-white opacity-25 my-4">

                    <div class="mb-3">
                        <span class="small fw-bold text-uppercase opacity-75 d-block mb-1">Estimasi Omzet</span>
                        <h2 class="fw-bold mb-0">Rp 5.000.000</h2>
                    </div>
                    
                    <div class="mb-3">
                        <span class="small fw-bold text-uppercase opacity-75 d-block mb-1">Fee Platform (3%)</span>
                        <h5 class="fw-bold mb-0 text-warning">- Rp 150.000</h5>
                    </div>

                    <div class="mb-4">
                        <span class="small fw-bold text-uppercase opacity-75 d-block mb-1">Biaya Sewa Booth</span>
                        <h5 class="fw-bold mb-0 text-warning">- Rp 500.000</h5>
                    </div>

                    <hr class="border-white opacity-25 my-4">

                    <div class="mb-4">
                        <span class="small fw-bold text-uppercase opacity-75 d-block mb-2">Pendapatan Bersih Anda</span>
                        <h1 class="fw-bold display-4 mb-0" style="text-shadow: 0 2px 10px rgba(0,0,0,0.1);">Rp 4.350.000</h1>
                    </div>

                    <div class="mt-4 p-3 rounded-3" style="background: rgba(0,0,0,0.1); border: 1px solid rgba(255,255,255,0.1);">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-info-circle text-white me-3 mt-1"></i>
                            <small class="lh-base text-white opacity-100">Hasil penjualan bersih setelah dipotong biaya layanan (3%). Vendor event tidak mengambil bagian tambahan dari omzetmu.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Fasilitas Tenant Section -->
<div class="py-5 bg-white">
    <div class="container py-5">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="fw-bold display-6" style="letter-spacing: -1px;">Fasilitas <span class="text-purple-custom">Tenant Modern</span></h2>
            <p class="text-muted fs-5">Semua yang Anda butuhkan untuk memaksimalkan penjualan di event bergengsi.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="fasilitas-card">
                    <div class="fasilitas-icon" style="background: #e0f2fe; color: #0284c7;">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-3">Manajemen Pembayaran</h5>
                    <p class="text-muted small mb-0">Selesaikan transaksi sewa booth dengan mudah menggunakan berbagai metode pembayaran digital yang aman dan otomatis terverifikasi.</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="fasilitas-card">
                    <div class="fasilitas-icon" style="background: #fce7f3; color: #db2777;">
                        <i class="bi bi-shop"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-3">Akses Denah Digital</h5>
                    <p class="text-muted small mb-0">Dapatkan informasi penempatan lokasi booth Anda secara transparan dan detail langsung dari dashboard setelah disetujui oleh vendor.</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                <div class="fasilitas-card">
                    <div class="fasilitas-icon" style="background: #dcfce7; color: #16a34a;">
                        <i class="bi bi-megaphone"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-3">Dukungan Exposure</h5>
                    <p class="text-muted small mb-0">Tingkatkan visibilitas usaha Anda karena profil UMKM Anda akan dapat dilihat oleh ribuan calon pengunjung event melalui aplikasi.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FAQ Section -->
<div class="py-5" style="background-color: #f8fafc;">
    <div class="container py-5">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="fw-bold display-6" style="letter-spacing: -1px;">Pertanyaan <span class="text-purple-custom">Sering Diajukan</span></h2>
        </div>

        <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="100">
            <div class="col-md-8 px-4 px-md-0">
                <div class="accordion faq-accordion" id="faqTenant">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                Berapa lama proses persetujuan pengajuan stand?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqTenant">
                            <div class="accordion-body">
                                Biasanya vendor event akan meninjau pengajuan Anda dalam waktu 1-3 hari kerja. Notifikasi persetujuan atau penolakan akan dikirimkan melalui email dan juga muncul di dashboard akun Anda.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                Bagaimana sistem pembayaran sewa booth?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqTenant">
                            <div class="accordion-body">
                                Pembayaran dilakukan 100% di muka setelah pengajuan stand Anda disetujui oleh vendor. Kami mendukung pembayaran praktis menggunakan e-Wallet (OVO, GoPay), QRIS, maupun Transfer Bank.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                Apakah disediakan listrik dan meja untuk jualan?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqTenant">
                            <div class="accordion-body">
                                Fasilitas dasar seperti titik listrik dan meja umumnya sudah di-cover oleh biaya sewa booth. Namun, spesifikasi detail fasilitas dapat berbeda untuk setiap event, dan akan diinformasikan pada rincian booth.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Partners Marquee Section -->
<section class="py-5 bg-white border-top border-bottom overflow-hidden">
    <div class="container py-4">
        <div class="marquee-container">
            <div class="marquee-content">
                {{-- Logo Set 1 --}}
                <div class="marquee-item"><img src="{{ asset('storage/images/logo/logo mecima.png') }}" alt="Mecima Pro" onerror="this.src='{{ asset('storage/events/logo mecima.png') }}'"></div>
                <div class="marquee-item"><img src="{{ asset('storage/images/logo/logo ime.png') }}" alt="Ime Indonesia" onerror="this.src='{{ asset('storage/events/logo ime.png') }}'"></div>
                <div class="marquee-item"><img src="{{ asset('storage/images/logo/logo dyandra.png') }}" alt="Dyandra Global" onerror="this.src='{{ asset('storage/events/logo dyandra.png') }}'"></div>
                <div class="marquee-item"><img src="{{ asset('storage/images/logo/logo pk.png') }}" alt="PK Entertainment" onerror="this.src='{{ asset('storage/events/logo pk.png') }}'"></div>
                <div class="marquee-item"><img src="{{ asset('storage/images/logo/logo ck.png') }}" alt="CK Star" onerror="this.src='{{ asset('storage/events/logo ck.png') }}'"></div>
                <div class="marquee-item"><img src="{{ asset('storage/images/logo/logo groovy.jpg') }}" alt="Groove" style="height: 50px;"></div>
                
                {{-- Logo Set 2 (Duplicate for seamless loop) --}}
                <div class="marquee-item"><img src="{{ asset('storage/images/logo/logo mecima.png') }}" alt="Mecima Pro" onerror="this.src='{{ asset('storage/events/logo mecima.png') }}'"></div>
                <div class="marquee-item"><img src="{{ asset('storage/images/logo/logo ime.png') }}" alt="Ime Indonesia" onerror="this.src='{{ asset('storage/events/logo ime.png') }}'"></div>
                <div class="marquee-item"><img src="{{ asset('storage/images/logo/logo dyandra.png') }}" alt="Dyandra Global" onerror="this.src='{{ asset('storage/events/logo dyandra.png') }}'"></div>
                <div class="marquee-item"><img src="{{ asset('storage/images/logo/logo pk.png') }}" alt="PK Entertainment" onerror="this.src='{{ asset('storage/events/logo pk.png') }}'"></div>
                <div class="marquee-item"><img src="{{ asset('storage/images/logo/logo ck.png') }}" alt="CK Star" onerror="this.src='{{ asset('storage/events/logo ck.png') }}'"></div>
                <div class="marquee-item"><img src="{{ asset('storage/images/logo/logo groovy.jpg') }}" alt="Groove" style="height: 50px;"></div>
            </div>
        </div>
    </div>
</section>

@endsection

