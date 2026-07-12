@extends('v_layouts.app')

@section('title', 'Konfirmasi Pesanan - ' . $event->title)

@section('content')

<style>
    :root {
        --ff-indigo: #7c3aed;
        --ff-indigo-light: #8b5cf6;
        --ff-gradient: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
    }

    .booking-wrapper {
        background: #f8fafc;
        min-height: 100vh;
        padding-top: 120px;
        padding-bottom: 80px;
    }

    /* STEPPER */
    .booking-stepper {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 50px;
        gap: 20px;
    }

    .step-node {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        z-index: 2;
    }

    .step-circle {
        width: 50px;
        height: 50px;
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        color: #94a3b8;
        transition: 0.4s;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    .step-label {
        margin-top: 10px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #94a3b8;
        transition: 0.4s;
    }

    .step-line {
        width: 100px;
        height: 2px;
        background: #e2e8f0;
        margin-bottom: 25px;
    }

    .step-node.active .step-circle {
        background: var(--ff-gradient);
        border-color: transparent;
        color: white;
        transform: scale(1.15);
        box-shadow: 0 10px 20px rgba(139, 92, 246, 0.3);
    }

    .step-node.active .step-label { color: var(--ff-indigo); }
    
    .step-node.completed .step-circle {
        background: #10b981;
        border-color: transparent;
        color: white;
    }

    /* CARDS */
    .premium-card {
        background: white;
        border-radius: 30px;
        border: none;
        box-shadow: 0 15px 40px rgba(0,0,0,0.04);
        overflow: hidden;
    }

    .ticket-option-card {
        border: 2px solid #f1f5f9;
        border-radius: 20px;
        padding: 20px;
        cursor: pointer;
        transition: 0.3s;
        background: #fafafa;
        margin-bottom: 15px;
        display: block;
    }

    .ticket-option-card:hover {
        background: #f5f3ff;
    }

    .ticket-option-card.active {
        border-color: #8b5cf6;
        background: #f5f3ff;
        box-shadow: 0 8px 20px rgba(139, 92, 246, 0.1);
    }

    .form-control-premium {
        background: #f8fafc;
        border: 2px solid #e2e8f0;
        border-radius: 15px;
        padding: 12px 20px;
        font-weight: 500;
        transition: 0.3s;
    }

    .form-control-premium:focus {
        background: white;
        border-color: #8b5cf6;
        box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.1);
    }

    .summary-card {
        border: 2px solid #8b5cf6;
        background: white;
        border-radius: 30px;
        padding: 30px;
        position: sticky;
        top: 100px;
    }

    .btn-next-step {
        background: var(--ff-gradient);
        color: white;
        border: none;
        padding: 15px 30px;
        border-radius: 50px;
        font-weight: 800;
        width: 100%;
        transition: 0.3s;
        box-shadow: 0 10px 20px rgba(139, 92, 246, 0.3);
    }

    .btn-next-step:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(139, 92, 246, 0.4);
        color: white;
    }

    .timer-badge {
        background: #fffbeb;
        color: #d97706;
        padding: 8px 20px;
        border-radius: 50px;
        font-weight: 700;
        border: 1px solid #fde68a;
    }

    /* WAITING ROOM STYLES */
    .waiting-room-overlay {
        background: #ffffff;
        z-index: 10000;
        overflow-y: auto; /* Allow scrolling if content is long */
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
    }

    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .waiting-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        overflow: hidden;
        max-width: 1000px;
        width: 95%;
    }

    .waiting-sidebar {
        background: #ffffff;
        border-left: 1px solid #e2e8f0;
        padding: 40px;
    }

    .queue-status-box {
        background: #fff9db;
        border: 1px solid #ffec99;
        color: #856404;
        padding: 15px;
        border-radius: 10px;
        font-size: 0.9rem;
        margin-bottom: 20px;
    }

    .time-box {
        display: flex;
        gap: 10px;
        justify-content: center;
        margin: 20px 0;
    }

    .time-unit {
        background: #1e293b;
        color: white;
        padding: 15px;
        border-radius: 12px;
        text-align: center;
        min-width: 70px;
    }

    .time-unit .num {
        font-size: 1.5rem;
        font-weight: 800;
        display: block;
    }

    .time-unit .label {
        font-size: 0.7rem;
        text-transform: uppercase;
        opacity: 0.7;
    }

    .availability-tray {
        background: #1e293b;
        padding: 20px;
        color: white;
    }

    .availability-item {
        background: white;
        color: #1e293b;
        border-radius: 10px;
        padding: 15px;
        min-width: 180px;
        flex-shrink: 0;
    }

    .status-badge {
        font-size: 0.7rem;
        font-weight: 700;
        padding: 4px 8px;
        border-radius: 4px;
        text-transform: uppercase;
    }

    .status-available { background: #dcfce7; color: #166534; }
    .status-booked { background: #fee2e2; color: #991b1b; }
    .status-soldout { background: #f1f5f9; color: #475569; }

    .progress-custom {
        height: 12px;
        background: #f1f5f9;
        border-radius: 20px;
        overflow: hidden;
        margin: 25px 0;
        border: 1px solid #e2e8f0;
        position: relative;
    }

    .progress-bar-custom {
        background: linear-gradient(90deg, #4c1d95 0%, #8b5cf6 100%);
        height: 100%;
        width: 0%;
        transition: width 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 20px;
        position: relative;
    }

    .progress-bar-custom::after {
        content: "";
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(
            90deg,
            rgba(255, 255, 255, 0) 0%,
            rgba(255, 255, 255, 0.3) 50%,
            rgba(255, 255, 255, 0) 100%
        );
        animation: shimmer 2s infinite;
    }

    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }

    .merch-item-card {
        border: 2px solid #f1f5f9;
        border-radius: 20px;
        padding: 15px;
        transition: 0.3s;
        cursor: pointer;
        background: white;
    }
    .merch-item-card:hover { border-color: #c4b5fd; transform: translateY(-3px); }
    .merch-item-card.active { border-color: #8b5cf6; background: #f5f3ff; }

    /* ANIMATIONS */
    @keyframes float {
        0% { transform: translateY(0px) rotate(0deg); }
        25% { transform: translateY(-15px) rotate(2deg); }
        50% { transform: translateY(-25px) rotate(0deg); }
        75% { transform: translateY(-15px) rotate(-2deg); }
        100% { transform: translateY(0px) rotate(0deg); }
    }

    .mascot-animated {
        animation: float 4s ease-in-out infinite;
        filter: drop-shadow(0 20px 30px rgba(0,0,0,0.1));
    }

    .pulse-animation {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.05); opacity: 0.8; }
        100% { transform: scale(1); opacity: 1; }
    }

    /* Slider Verify */
    .slider-verify-container {
        position: relative;
        width: 100%;
        height: 50px;
        background: #e2e8f0;
        border-radius: 50px;
        overflow: hidden;
        border: 1px solid #cbd5e1;
    }
    .slider-verify-bg {
        position: absolute;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        font-size: 0.85rem;
        font-weight: 600;
        z-index: 1;
    }
    .slider-verify-handle {
        position: absolute;
        top: 5px;
        left: 5px;
        width: 40px;
        height: 40px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: grab;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        z-index: 2;
        color: #8b5cf6;
        transition: background 0.3s;
    }
    .slider-verify-handle:active { cursor: grabbing; }
    .slider-verify-handle.verified { background: #22c55e; color: white; cursor: default; left: calc(100% - 45px) !important; }
    .slider-verify-container.verified { background: #dcfce7; border-color: #86efac; }
    .slider-verify-container.verified .slider-verify-bg { color: #166534; }

    .modal-content-premium {
        border-radius: 30px;
        border: none;
        overflow: hidden;
    }
</style>

<div class="booking-wrapper">
    <div class="container">
        
        {{-- SOPHISTICATED WAITING ROOM OVERLAY --}}
        <div id="queueOverlay" class="waiting-room-overlay position-fixed top-0 start-0 w-100 h-100 d-flex flex-column align-items-center justify-content-center">
            <script>
                if (sessionStorage.getItem('queue_done_{{ $event->id }}')) {
                    document.getElementById('queueOverlay').style.display = 'none';
                    document.body.style.overflow = 'auto';
                }
            </script>
            <div class="waiting-card">
                <div class="row g-0">
                    <div class="col-lg-7 p-5 text-center d-flex flex-column align-items-center justify-content-center bg-light">
                        <div class="mb-4">
                            <div class="bg-primary text-white px-4 py-2 rounded-pill fw-bold mb-4 d-inline-block pulse-animation">WAITING ROOM</div>
                        </div>
                        <img src="{{ asset('storage/' . $event->image) }}" class="img-fluid mb-4 mascot-animated rounded-4 shadow-lg" style="max-height: 280px; width: auto;" alt="Event Poster" onerror="this.src='{{ asset('storage/images/mascot.png') }}'">
                        
                        <div class="availability-tray w-100 rounded-4">
                            <h6 class="text-start mb-3 small fw-bold">Informasi Ketersediaan Tiket</h6>
                            <div class="d-flex gap-3 overflow-auto pb-2 scrollbar-hide">
                                @foreach($event->ticket_categories as $ticket)
                                    <div class="availability-item">
                                        <div class="fw-bold small mb-1">{{ $ticket->name }}</div>
                                        @if($ticket->quota > 10)
                                            <span class="status-badge status-available">Tersedia</span>
                                        @elseif($ticket->quota > 0)
                                            <span class="status-badge status-booked">Terbatas</span>
                                        @else
                                            <span class="status-badge status-soldout">Habis</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 waiting-sidebar">
                        <div class="queue-status-box d-flex align-items-center gap-2">
                            <i class="bi bi-exclamation-circle-fill"></i>
                            Jangan tutup atau tinggalkan halaman ini agar antrean Anda tidak hilang.
                        </div>
                        
                        <h4 class="fw-bold mb-1">Anda dalam antrean</h4>
                        <p class="text-muted small mb-4">{{ $event->title }}</p>
                        
                        <p class="small mb-2">Mohon tunggu sebentar untuk mengakses halaman penjualan. Anda akan diberikan waktu 10 menit untuk menyelesaikan formulir pembelian.</p>
                        
                        <div class="text-center">
                            <span class="small fw-bold text-muted text-uppercase">Estimasi waktu tunggu:</span>
                            <div class="time-box">
                                <div class="time-unit">
                                    <span class="num" id="wait-min">00</span>
                                    <span class="label">Menit</span>
                                </div>
                                <div class="time-unit">
                                    <span class="num" id="wait-sec">00</span>
                                    <span class="label">Detik</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="progress-custom">
                            <div class="progress-bar-custom" id="queueProgress" style="width: 0%;"></div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-people-fill text-primary"></i>
                                <span class="small fw-bold" id="peopleCount">Menghitung...</span>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        <div class="text-muted" style="font-size: 0.7rem;">
                            Queue ID: <span class="fw-bold">{{ strtoupper(substr(md5($event->id . time()), 0, 16)) }}</span><br>
                            Last Updated: {{ date('d F Y H:i:s') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- HEADER & TIMER --}}
        <div class="d-flex flex-column align-items-center mb-5" data-aos="fade-down">
            <div class="timer-badge mb-3">
                <i class="bi bi-clock-history me-2"></i> 
                Selesaikan sebelum <span id="purchase-timer" class="fw-bold">07:00</span>
            </div>
            <h2 class="fw-black text-center" style="font-size: 2.5rem; letter-spacing: -1px;">Checkout <span class="text-primary">Tiket</span></h2>
        </div>

        {{-- STEPPER --}}
        <div class="booking-stepper" data-aos="fade-up">
            <div class="step-node active" id="node-1">
                <div class="step-circle">1</div>
                <div class="step-label">Kategori</div>
            </div>
            <div class="step-line"></div>
            <div class="step-node" id="node-2">
                <div class="step-circle">2</div>
                <div class="step-label">Merchandise</div>
            </div>
            <div class="step-line"></div>
            <div class="step-node" id="node-3">
                <div class="step-circle">3</div>
                <div class="step-label">Data Diri</div>
            </div>
            <div class="step-line"></div>
            <div class="step-node" id="node-4">
                <div class="step-circle">4</div>
                <div class="step-label">Review</div>
            </div>
            <div class="step-line"></div>
            <div class="step-node" id="node-5">
                <div class="step-circle">5</div>
                <div class="step-label">Bayar</div>
            </div>
        </div>
        @if(session('error'))
        <script>
            window.addEventListener('DOMContentLoaded', function() {
                document.getElementById('queueOverlay').style.display = 'none';
                Swal.fire({
                    icon: 'error',
                    title: 'Pemesanan Gagal',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#8b5cf6'
                });
            });
        </script>
        @endif

        @if($errors->any())
        <script>
            window.addEventListener('DOMContentLoaded', function() {
                document.getElementById('queueOverlay').style.display = 'none';
                let errorMessages = [];
                @foreach ($errors->all() as $error)
                    errorMessages.push("{{ $error }}");
                @endforeach
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    html: errorMessages.join('<br>'),
                    confirmButtonColor: '#8b5cf6'
                });
            });
        </script>
        @endif

        <form action="{{ route('booking.store', $event->id) }}" method="POST" id="bookingForm">
            @csrf
            
            <div class="row g-4 mt-2">
                {{-- LEFT SIDE: FORMS --}}
                <div class="col-lg-8" data-aos="fade-right">
                    
                    {{-- STEP 1: CATEGORY --}}
                    <div id="pane-1" class="booking-pane">
                        <div class="premium-card p-4 p-md-5">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="fw-bold mb-0">Pilih Kategori Tiket</h4>
                                @php $seatPlanImg = $event->seat_plan ?? $event->seatplan; @endphp
                                @if($seatPlanImg)
                                <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#seatmapModal">
                                    <i class="bi bi-map me-1"></i> LIHAT SEATMAP
                                </button>
                                @endif
                            </div>

                            <div class="alert alert-info border-0 bg-indigo-soft rounded-4 mb-4">
                                <i class="bi bi-info-circle-fill me-2 text-primary"></i>
                                <small>Nomor kursi akan ditentukan secara otomatis oleh sistem sesuai urutan pembayaran.</small>
                            </div>

                            @forelse($event->ticket_categories as $ticket)
                                <div class="ticket-option-card w-100 {{ $ticket->quota <= 0 ? 'opacity-50' : '' }}" 
                                     id="card-{{ $ticket->id }}"
                                     onclick="selectTicketCard('{{ $ticket->id }}')">
                                    <input type="radio" name="ticket_category_id" id="radio-{{ $ticket->id }}" value="{{ $ticket->id }}" 
                                           data-name="{{ $ticket->name }}" data-price="{{ $ticket->price }}" data-quota="{{ $ticket->quota }}"
                                           style="display: none;" {{ $ticket->quota <= 0 ? 'disabled' : '' }}>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <div class="ticket-icon me-3">
                                                <i class="bi bi-ticket-perforated fs-3 text-primary"></i>
                                            </div>
                                            <div>
                                                <h5 class="fw-bold mb-0 text-dark">{{ strtoupper($ticket->name) }}</h5>
                                                <small class="text-muted">{{ $ticket->benefits ?? 'Akses masuk standar' }}</small>
                                                <div class="mt-1">
                                                    @if($ticket->quota > 0)
                                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2">Tersedia {{ $ticket->quota }} Tiket</span>
                                                    @else
                                                        <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2">Habis Terjual</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <div class="fw-black fs-5 text-primary">Rp{{ number_format($ticket->price, 0, ',', '.') }}</div>
                                            <small class="text-muted">/ tiket</small>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-5 bg-light rounded-4 border mb-4">
                                    <i class="bi bi-ticket-detailed text-muted mb-2 opacity-50" style="font-size: 3rem;"></i>
                                    <h6 class="fw-bold text-dark mt-2">Tiket Belum Tersedia</h6>
                                    <p class="text-muted small mb-0 px-3">Mohon maaf, pihak penyelenggara belum menambahkan kategori tiket untuk event ini. Silakan kembali lagi nanti.</p>
                                </div>
                            @endforelse
                            
                            <div class="mt-4 pt-3 border-top" id="quantitySelector" style="display: none;">
                                <label class="fw-bold text-dark mb-2">Jumlah Tiket (Maksimal {{ $event->max_tickets_per_user ?? 4 }})</label>
                                <div class="d-flex align-items-center gap-3">
                                    <button type="button" class="btn btn-light rounded-circle" onclick="updateQuantity(-1)"><i class="bi bi-dash"></i></button>
                                    <input type="number" name="quantity" id="ticketQuantity" value="1" min="1" max="{{ $event->max_tickets_per_user ?? 4 }}" class="form-control text-center fw-bold" style="width: 70px; border-radius: 15px;" readonly>
                                    <button type="button" class="btn btn-light rounded-circle" onclick="updateQuantity(1)"><i class="bi bi-plus"></i></button>
                                </div>
                            </div>

                            <div class="mt-5">
                                <button type="button" class="btn-next-step" onclick="validateStep1()" {{ $event->ticket_categories->count() == 0 ? 'disabled style=cursor:not-allowed;' : '' }}>
                                    Lanjut ke Merchandise <i class="bi bi-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- STEP 2: MERCHANDISE ADD-ON --}}
                    <div id="pane-2" class="booking-pane d-none">
                        <div class="premium-card p-4 p-md-5">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="fw-bold mb-0">Tambah Merchandise?</h4>
                                <button type="button" class="btn btn-link text-decoration-none fw-bold" onclick="changeStep(3)">Lewati <i class="bi bi-chevron-right"></i></button>
                            </div>
                            <p class="text-muted small mb-4">Lengkapi pengalaman konsermu dengan merchandise eksklusif. Harga sudah termasuk pajak 11%.</p>

                            <div class="row g-3">
                                @forelse($event->merchandises as $merch)
                                <div class="col-md-6">
                                    <div class="merch-item-card d-flex flex-column gap-3" id="merch-card-{{ $merch->id }}">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="merch-img-container" style="width: 70px; height: 70px; flex-shrink: 0;">
                                                @if($merch->image)
                                                    <img src="{{ asset('storage/' . $merch->image) }}" class="rounded-3 w-100 h-100" style="object-fit: cover;" alt="{{ $merch->name }}">
                                                @else
                                                    <div class="bg-light rounded-3 w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                                                        <i class="bi bi-image fs-4"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="fw-bold mb-0 text-truncate" style="max-width: 150px;">{{ $merch->name }}</h6>
                                                <p class="text-primary fw-bold mb-0 small">Rp{{ number_format($merch->price, 0, ',', '.') }}</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                                            <span class="small text-muted fw-bold">Jumlah</span>
                                            <div class="d-flex align-items-center gap-2">
                                                <button type="button" class="btn btn-sm btn-light rounded-circle" onclick="updateMerchQty('{{ $merch->id }}', -1)"><i class="bi bi-dash"></i></button>
                                                <input type="number" name="merch_quantities[{{ $merch->id }}]" id="merch-qty-{{ $merch->id }}" value="0" min="0" class="form-control form-control-sm text-center fw-bold merch-qty-input" style="width: 50px; border-radius: 10px;" data-name="{{ $merch->name }}" data-price="{{ $merch->price }}" readonly>
                                                <input type="hidden" name="merch_ids[]" value="{{ $merch->id }}">
                                                <button type="button" class="btn btn-sm btn-light rounded-circle" onclick="updateMerchQty('{{ $merch->id }}', 1)"><i class="bi bi-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="col-12 text-center py-5">
                                    <i class="bi bi-bag-x text-muted display-4 mb-3"></i>
                                    <p class="text-muted">Tidak ada merchandise tersedia untuk event ini.</p>
                                </div>
                                @endforelse
                            </div>

                            <div class="mt-5 d-flex gap-3">
                                <button type="button" class="btn btn-light rounded-pill px-5 fw-bold" onclick="changeStep(1)">Kembali</button>
                                <button type="button" class="btn-next-step" onclick="changeStep(3)">Lanjut ke Data Diri <i class="bi bi-arrow-right ms-2"></i></button>
                            </div>
                        </div>
                    </div>

                    {{-- STEP 2: PERSONAL DATA --}}
                    <div id="pane-3" class="booking-pane d-none">
                        <div class="premium-card p-4 p-md-5">
                            <h4 class="fw-bold mb-4">Lengkapi Data Diri</h4>
                            <div class="alert alert-warning border-0 rounded-4 mb-4 small">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <strong>Penting:</strong> Satu identitas berlaku untuk seluruh tiket dalam transaksi ini. Pastikan pembeli utama hadir saat penukaran.
                            </div>
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted small">NAMA LENGKAP</label>
                                    <input type="text" value="{{ Auth::user()->name }}" class="form-control form-control-premium" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted small">EMAIL</label>
                                    <input type="email" value="{{ Auth::user()->email }}" class="form-control form-control-premium" readonly>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-bold text-muted small">NOMOR IDENTITAS (NIK/KTP)</label>
                                    <input type="text" name="identity_number" id="identity_number" placeholder="Contoh: 3273xxxxxxxxxxxx" class="form-control form-control-premium">
                                    <div class="invalid-feedback">Nomor identitas wajib diisi.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted small">TANGGAL LAHIR</label>
                                    <input type="date" name="birth_date" id="birth_date" class="form-control form-control-premium">
                                    <div class="invalid-feedback">Tanggal lahir wajib diisi.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted small">GENDER</label>
                                    <select name="gender" id="gender" class="form-select form-control-premium">
                                        <option value="">Pilih Gender</option>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                    <div class="invalid-feedback">Gender wajib diisi.</div>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-bold text-muted small">NOMOR WHATSAPP</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-2 border-end-0" style="border-radius: 15px 0 0 15px;">+62</span>
                                        <input type="text" name="phone" id="phone" placeholder="812xxxxxxxx" class="form-control form-control-premium" style="border-radius: 0 15px 15px 0;">
                                    </div>
                                    <div class="invalid-feedback d-block" id="phone-error" style="display: none !important;">Nomor WhatsApp wajib diisi.</div>
                                </div>
                            </div>

                            <div class="mt-5 d-flex gap-3">
                                <button type="button" class="btn btn-light rounded-pill px-5 fw-bold" onclick="changeStep(2)">Kembali</button>
                                <button type="button" class="btn-next-step" onclick="validateStep3()">Review Pesanan <i class="bi bi-arrow-right ms-2"></i></button>
                            </div>
                        </div>
                    </div>

                    {{-- STEP 4: REVIEW --}}
                    <div id="pane-4" class="booking-pane d-none">
                        <div class="premium-card p-4 p-md-5">
                            <h4 class="fw-bold mb-4">Review Pesanan</h4>
                            <div class="card bg-light border-0 rounded-4 p-4 mb-4">
                                <div class="row g-4">
                                    <div class="col-md-6"><small class="text-muted d-block mb-1">EVENT</small> <span class="fw-bold fs-5">{{ $event->title }}</span></div>
                                    <div class="col-md-6"><small class="text-muted d-block mb-1">TIKET</small> <span class="fw-bold fs-5"><span id="rev-ticket">-</span> (x<span id="rev-qty">1</span>)</span></div>
                                    <div class="col-12 border-top pt-3" id="rev-merch-section" style="display: none;"><small class="text-muted d-block mb-1">MERCHANDISE</small> <span class="fw-bold text-primary" id="rev-merch-list">-</span></div>
                                    <div class="col-md-6 border-top pt-3"><small class="text-muted d-block mb-1">NAMA PEMBELI</small> <span class="fw-bold">{{ Auth::user()->name }}</span></div>
                                    <div class="col-md-6 border-top pt-3"><small class="text-muted d-block mb-1">NOMOR WHATSAPP</small> <span class="fw-bold" id="rev-phone">-</span></div>
                                </div>
                            </div>

                            <div class="alert alert-info border-0 rounded-4 mb-4">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                Mohon periksa kembali data di atas. Jika sudah sesuai, silakan centang persetujuan di bawah.
                            </div>

                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="terms_1" onchange="updateReviewValidation()">
                                <label class="form-check-label small" for="terms_1">
                                    Saya telah membaca dan menyetujui <a href="#" class="text-primary fw-bold" data-bs-toggle="modal" data-bs-target="#termsModal">Syarat & Ketentuan</a>.
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="terms_2" onchange="updateReviewValidation()">
                                <label class="form-check-label small" for="terms_2">
                                    Saya menjamin data yang dimasukkan adalah benar.
                                </label>
                            </div>
                            <div id="terms-warning" class="text-danger small mb-4 animate__animated animate__fadeIn" style="display: none;">
                                <i class="bi bi-exclamation-triangle me-1"></i> Anda wajib menyetujui semua poin di atas.
                            </div>

                            <div class="d-flex gap-3">
                                <button type="button" class="btn btn-light rounded-pill px-5 fw-bold" onclick="changeStep(3)">Kembali</button>
                                <button type="button" class="btn-next-step opacity-50" id="btn-validate-4" onclick="validateStep4()" style="cursor: not-allowed;">Lanjut ke Pembayaran <i class="bi bi-arrow-right ms-2"></i></button>
                            </div>
                        </div>
                    </div>

                    {{-- STEP 5: PAYMENT --}}
                    <div id="pane-5" class="booking-pane d-none">
                        <div class="premium-card p-4 p-md-5 text-center">
                            <div class="mb-4">
                                <div class="bg-indigo-soft p-4 rounded-circle d-inline-block mb-3" style="background: #f5f3ff;">
                                    <i class="bi bi-shield-lock text-primary display-4"></i>
                                </div>
                                <h4 class="fw-bold">Verifikasi & Bayar</h4>
                                <p class="text-muted">Langkah terakhir sebelum pembayaran aman melalui Midtrans.</p>
                            </div>

                            {{-- ANTI-BOT: SECURE SLIDER --}}
                            <div class="bg-light p-4 rounded-4 mb-3 text-start">
                                <label class="fw-bold small mb-3"><i class="bi bi-shield-check me-1"></i> VERIFIKASI KEAMANAN</label>
                                <div class="slider-verify-container" id="sliderContainer">
                                    <div class="slider-verify-bg">Geser untuk verifikasi</div>
                                    <div class="slider-verify-handle" id="sliderHandle">
                                        <i class="bi bi-chevron-double-right"></i>
                                    </div>
                                </div>
                                <input type="hidden" name="bot_verified" id="botVerified" value="0">
                                <small class="text-muted mt-2 d-block">Geser ikon ke kanan untuk membuktikan Anda bukan bot.</small>
                            </div>
                            <div id="captcha-warning" class="text-danger small mb-4 animate__animated" style="display: none;">
                                <i class="bi bi-exclamation-triangle me-1"></i> Silakan selesaikan verifikasi keamanan di atas.
                            </div>

                            <div class="d-flex gap-3 justify-content-center">
                                <button type="button" class="btn-next-step opacity-50" id="btn-submit-booking" onclick="validateStep5()" style="cursor: not-allowed;">Bayar Sekarang <i class="bi bi-wallet2 ms-2"></i></button>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- RIGHT SIDE: SUMMARY --}}
                <div class="col-lg-4" data-aos="fade-left">
                    <div class="summary-card">
                        <h6 class="fw-bold text-muted mb-4 small letter-spacing-1">RINGKASAN PESANAN</h6>
                        
                        <div class="mb-4">
                            <h5 class="fw-bold text-dark mb-1">{{ $event->title }}</h5>
                            <div class="d-flex align-items-center text-muted small">
                                <i class="bi bi-calendar-event me-2"></i> {{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted small">Kategori</span>
                                <span class="fw-bold" id="sum-cat">-</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted small">Harga Tiket</span>
                                <span class="fw-bold" id="sum-price">Rp0</span>
                            </div>
                             <div id="sum-merch-row" style="display: none;">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="text-muted small">Merchandise</span>
                                    <div class="text-end">
                                        <div id="sum-merch-details" class="text-muted mb-1" style="font-size: 0.7rem; line-height: 1.2;"></div>
                                        <span class="fw-bold" id="sum-merch">Rp0</span>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-3" style="border-style: dashed;">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted small">Pajak (10%)</span>
                                <span class="fw-bold" id="sum-tax">Rp0</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted small">Biaya Layanan (3%)</span>
                                <span class="fw-bold text-success" id="sum-service">Rp0</span>
                            </div>
                        </div>

                        <div class="mt-4 pt-4 border-top">
                            <div class="d-flex justify-content-between align-items-end mb-2">
                                <span class="fw-bold text-dark">Total Pembayaran</span>
                                <h3 class="fw-black text-primary mb-0" id="sum-total">Rp0</h3>
                            </div>
                        </div>

                        <div class="mt-4 p-3 rounded-4 bg-light small text-muted">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-shield-lock-fill text-primary me-2"></i>
                                <span class="fw-bold text-dark">Secure Payment by Midtrans</span>
                            </div>
                            <p class="mb-0 x-small">Tersedia berbagai metode pembayaran: Virtual Account (BCA, Mandiri, BNI), QRIS, Kartu Kredit, dan E-Wallet.</p>
                        </div>
                        <div class="mt-2 p-3 rounded-4 bg-indigo-soft small text-muted" style="background: #f5f3ff;">
                            <i class="bi bi-info-circle-fill me-2 text-primary"></i> 
                            Tiket akan dikirimkan ke email Anda segera setelah pembayaran dikonfirmasi.
                        </div>
                    </div>
                </div>

            </div>
        </form>

    </div>
</div>

{{-- MODALS --}}
@if($event->seat_plan)
<div class="modal fade" id="seatmapModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content modal-content-premium">
            <div class="modal-header border-0 p-4 pb-0">
                <h5 class="modal-title fw-bold">Seatmap Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <img src="{{ asset('storage/' . $event->seat_plan) }}" class="img-fluid rounded-4 w-100" alt="Seatmap">
            </div>
        </div>
    </div>
</div>
@endif

<div class="modal fade" id="termsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content modal-content-premium">
            <div class="modal-header border-0 p-4 pb-0">
                <h5 class="modal-title fw-bold">Syarat & Ketentuan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <ol class="small text-muted">
                    <li>Tiket yang sudah dibeli tidak dapat ditukar atau diuangkan kembali.</li>
                    <li>Satu identitas (NIK) maksimal hanya dapat membeli 4 tiket dalam satu transaksi.</li>
                    <li>E-Ticket akan dikirimkan melalui email setelah pembayaran berhasil diverifikasi.</li>
                    <li>Penyelenggara berhak membatalkan pesanan jika ditemukan indikasi bot atau manipulasi data.</li>
                    <li>Penomoran kursi dilakukan secara otomatis oleh sistem berdasarkan waktu pembayaran tercepat.</li>
                    <li>Dilarang keras melakukan pendaftaran ganda atau menggunakan data palsu.</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="privacyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content modal-content-premium">
            <div class="modal-header border-0 p-4 pb-0">
                <h5 class="modal-title fw-bold">Kebijakan Privasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="small text-muted">
                    <p class="fw-bold text-dark">1. Pengumpulan Informasi</p>
                    <p>Kami mengumpulkan data diri Anda seperti Nama, Email, Nomor Identitas, dan Nomor WhatsApp hanya untuk keperluan verifikasi tiket dan pengiriman E-Ticket.</p>
                    <p class="fw-bold text-dark mt-3">2. Keamanan Data</p>
                    <p>Seluruh data transaksi dan identitas dilindungi dengan enkripsi standar industri. Kami tidak menyimpan data kartu kredit atau akun bank Anda; proses pembayaran dilakukan sepenuhnya melalui gateway Midtrans.</p>
                    <p class="fw-bold text-dark mt-3">3. Penggunaan Data</p>
                    <p>Data Anda dapat dibagikan kepada penyelenggara event (Vendor) hanya untuk proses validasi saat penukaran tiket di lokasi acara.</p>
                    <p class="fw-bold text-dark mt-3">4. Cookies</p>
                    <p>Kami menggunakan cookies untuk mengelola antrean (*Waiting Room*) dan memastikan sesi pemesanan Anda tetap terjaga selama durasi transaksi.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let currentTicketPrice = 0;
    let currentTicketName = '';
    let currentTicketMaxQuota = 0;
    let captchaResult = 0;



    function selectTicketCard(id) {
        const radio = document.getElementById('radio-' + id);
        if (radio && !radio.disabled) {
            radio.checked = true;
            
            // UI Update
            document.querySelectorAll('.ticket-option-card').forEach(el => el.classList.remove('active'));
            document.getElementById('card-' + id).classList.add('active');
            
            currentTicketPrice = parseInt(radio.dataset.price);
            currentTicketName = radio.dataset.name;
            currentTicketMaxQuota = parseInt(radio.dataset.quota);
            
            document.getElementById('quantitySelector').style.display = 'block';
            
            // Reset quantity to 1 when changing category
            document.getElementById('ticketQuantity').value = 1;
            updateSummary();
        }
    }

    function generateCaptcha() {
        const a = Math.floor(Math.random() * 10) + 1;
        const b = Math.floor(Math.random() * 10) + 1;
        captchaResult = a + b;
        document.getElementById('captcha-question').textContent = `${a} + ${b} = ?`;
    }

    function updateQuantity(change) {
        const input = document.getElementById('ticketQuantity');
        let val = parseInt(input.value) + change;
        
        let maxAllowed = Math.min({{ $event->max_tickets_per_user ?? 4 }}, currentTicketMaxQuota);
        
        if (val >= 1 && val <= maxAllowed) {
            input.value = val;
            updateSummary();
        } else if (val > maxAllowed) {
            Swal.fire({
                icon: 'warning',
                title: 'Batas Maksimal',
                text: 'Maksimal pembelian untuk kategori ini adalah ' + maxAllowed + ' tiket.',
                confirmButtonColor: '#8b5cf6'
            });
        }
    }

    function updateSummary() {
        const qty = parseInt(document.getElementById('ticketQuantity').value);
        const subtotalTickets = currentTicketPrice * qty;
        
        // Merch Total & Enforce Limit
        let merchTotal = 0;
        const selectedMerch = [];
        document.querySelectorAll('.merch-qty-input').forEach(el => {
            let mQty = parseInt(el.value);
            
            // AUTO CAP: If merch qty > ticket qty, cap it
            if (mQty > qty) {
                mQty = qty;
                el.value = qty;
                const mId = el.id.replace('merch-qty-', '');
                document.getElementById('merch-card-' + mId).classList.toggle('active', mQty > 0);
            }

            if (mQty > 0) {
                const mPrice = parseInt(el.dataset.price);
                const mName = el.dataset.name;
                merchTotal += mPrice * mQty;
                selectedMerch.push(`${mName} (x${mQty})`);
            }
        });

        const tax = subtotalTickets * 0.1;
        const serviceFee = subtotalTickets * 0.03;
        const total = subtotalTickets + merchTotal + tax + serviceFee;

        document.getElementById('sum-cat').textContent = currentTicketName + ' (x' + qty + ')';
        document.getElementById('sum-price').textContent = 'Rp' + subtotalTickets.toLocaleString('id-ID');
        
        if (merchTotal > 0) {
            document.getElementById('sum-merch-row').style.display = 'block';
            document.getElementById('sum-merch').textContent = 'Rp' + merchTotal.toLocaleString('id-ID');
            document.getElementById('sum-merch-details').innerHTML = selectedMerch.join('<br>');
        } else {
            document.getElementById('sum-merch-row').style.display = 'none';
        }

        document.getElementById('sum-tax').textContent = 'Rp' + tax.toLocaleString('id-ID');
        document.getElementById('sum-service').textContent = 'Rp' + serviceFee.toLocaleString('id-ID');
        document.getElementById('sum-total').textContent = 'Rp' + total.toLocaleString('id-ID');
        
        document.getElementById('rev-ticket').textContent = currentTicketName;
        document.getElementById('rev-qty').textContent = qty;

        if (selectedMerch.length > 0) {
            document.getElementById('rev-merch-section').style.display = 'block';
            document.getElementById('rev-merch-list').textContent = selectedMerch.join(', ');
        } else {
            document.getElementById('rev-merch-section').style.display = 'none';
        }
    }

    function updateMerchQty(id, change) {
        const input = document.getElementById('merch-qty-' + id);
        const card = document.getElementById('merch-card-' + id);
        const ticketQty = parseInt(document.getElementById('ticketQuantity').value);
        let val = parseInt(input.value) + change;
        
        if (val >= 0 && val <= ticketQty) {
            input.value = val;
            card.classList.toggle('active', val > 0);
            updateSummary();
        } else if (val > ticketQty) {
            Swal.fire({
                icon: 'warning',
                title: 'Batas Maksimal',
                text: 'Jumlah tiap merchandise tidak boleh melebihi jumlah tiket (' + ticketQty + ').',
                confirmButtonColor: '#8b5cf6'
            });
        }
    }

    function changeStep(step) {
        // Update Stepper Nodes
        document.querySelectorAll('.step-node').forEach((node, idx) => {
            node.classList.remove('active', 'completed');
            if (idx + 1 < step) node.classList.add('completed');
            if (idx + 1 === step) node.classList.add('active');
        });

        // Update Panes
        document.querySelectorAll('.booking-pane').forEach((pane, idx) => {
            pane.classList.toggle('d-none', idx + 1 !== step);
        });

        window.scrollTo(0, 0);
    }

    function validateStep1() {
        const ticket = document.querySelector('input[name="ticket_category_id"]:checked');
        if (!ticket) {
            Swal.fire({
                icon: 'warning',
                title: 'Pilih Tiket',
                text: 'Silakan pilih kategori tiket terlebih dahulu.',
                confirmButtonColor: '#8b5cf6'
            });
            return;
        }
        changeStep(2);
    }

    function validateStep3() {
        const identity = document.getElementById('identity_number').value;
        const birth = document.getElementById('birth_date').value;
        const gender = document.getElementById('gender').value;
        const phone = document.getElementById('phone').value;

        let valid = true;

        if (!identity) { document.getElementById('identity_number').classList.add('is-invalid'); valid = false; }
        else { document.getElementById('identity_number').classList.remove('is-invalid'); }

        if (!birth) { document.getElementById('birth_date').classList.add('is-invalid'); valid = false; }
        else { document.getElementById('birth_date').classList.remove('is-invalid'); }

        if (!gender) { document.getElementById('gender').classList.add('is-invalid'); valid = false; }
        else { document.getElementById('gender').classList.remove('is-invalid'); }

        if (!phone) { document.getElementById('phone').classList.add('is-invalid'); valid = false; }
        else { document.getElementById('phone').classList.remove('is-invalid'); }

        if (!valid) {
            Swal.fire({
                icon: 'error',
                title: 'Data Belum Lengkap',
                text: 'Silakan isi semua kolom data diri dengan benar.',
                confirmButtonColor: '#8b5cf6'
            });
            return;
        }

        document.getElementById('rev-phone').textContent = '+62 ' + phone;
        changeStep(4);
    }

    function updateReviewValidation() {
        const t1 = document.getElementById('terms_1').checked;
        const t2 = document.getElementById('terms_2').checked;
        const btn = document.getElementById('btn-validate-4');
        const warning = document.getElementById('terms-warning');

        if (t1 && t2) {
            btn.classList.remove('opacity-50');
            btn.style.cursor = 'pointer';
            warning.style.display = 'none';
        } else {
            btn.classList.add('opacity-50');
            btn.style.cursor = 'not-allowed';
        }
    }

    function validateStep4() {
        const t1 = document.getElementById('terms_1').checked;
        const t2 = document.getElementById('terms_2').checked;
        const warning = document.getElementById('terms-warning');

        if (!t1 || !t2) {
            warning.style.display = 'block';
            warning.classList.remove('animate__shakeX');
            void warning.offsetWidth; // Trigger reflow
            warning.classList.add('animate__shakeX');

            Swal.fire({ 
                icon: 'warning', 
                title: 'Persetujuan Wajib', 
                text: 'Silakan centang persetujuan Syarat & Ketentuan serta jaminan data sebelum melanjutkan ke pembayaran.', 
                confirmButtonColor: '#8b5cf6' 
            });
            return;
        }
        changeStep(5);
    }

    function validateStep5() {
        const botVerified = document.getElementById('botVerified').value;
        const warning = document.getElementById('captcha-warning');

        if (botVerified !== "1") {
            warning.style.display = 'block';
            warning.classList.remove('animate__shakeX');
            void warning.offsetWidth; // Trigger reflow
            warning.classList.add('animate__shakeX');

            Swal.fire({ 
                icon: 'error', 
                title: 'Verifikasi Keamanan', 
                text: 'Silakan geser slider untuk memverifikasi bahwa Anda bukan bot.', 
                confirmButtonColor: '#8b5cf6' 
            });
            return;
        }

        document.getElementById('bookingForm').submit();
    }

    // Timer Logic
    function startTimer(duration, display) {
        var timer = duration, minutes, seconds;
        var interval = setInterval(function () {
            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);
            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;
            display.textContent = minutes + ":" + seconds;
            if (--timer < 0) {
                clearInterval(interval);
                Swal.fire({
                    icon: 'error',
                    title: 'Waktu Habis',
                    text: 'Sesi pemesanan Anda telah berakhir.',
                    confirmButtonColor: '#8b5cf6'
                }).then(() => {
                    window.location.href = "{{ url('/') }}";
                });
            }
        }, 1000);
    }

    window.onload = function () {
        try {
            // Anti-skip queue check
            const queueKey = 'queue_done_{{ $event->id }}';
            const overlay = document.getElementById('queueOverlay');
            if (sessionStorage.getItem(queueKey)) {
                if (overlay) {
                    overlay.style.setProperty('display', 'none', 'important');
                    overlay.style.opacity = '0';
                    overlay.style.pointerEvents = 'none';
                }
                document.body.style.overflow = 'auto';
                document.documentElement.style.overflow = 'auto';
                startTimer(60 * 10, document.querySelector('#purchase-timer'));
                initSlider();
                return;
            }

            // Sophisticated War Ticket Queue Animation
            let queueOverlay = document.getElementById('queueOverlay');
            let waitMin = document.getElementById('wait-min');
            let waitSec = document.getElementById('wait-sec');
            let progressBar = document.getElementById('queueProgress');
            let peopleCount = document.getElementById('peopleCount');
            
            if (!queueOverlay) return;

            document.body.style.overflow = 'hidden'; 
            
            let initialSeconds = Math.floor(Math.random() * 4) + 4; // 4-8 seconds
            let secondsLeft = initialSeconds;
            let totalPeople = Math.floor(Math.random() * 5000) + 2000;
            
            let queueInterval = setInterval(function() {
                secondsLeft--;
                
                if (secondsLeft < 0) {
                    clearInterval(queueInterval);
                    if (peopleCount) peopleCount.textContent = 'Giliran Anda telah tiba!';
                    if (progressBar) progressBar.style.width = '100%';
                    
                    setTimeout(() => {
                        queueOverlay.style.opacity = '0';
                        queueOverlay.style.transition = 'opacity 0.8s ease';
                        queueOverlay.style.pointerEvents = 'none'; 
                        
                        sessionStorage.setItem(queueKey, 'true');

                        setTimeout(() => {
                            queueOverlay.style.display = 'none';
                            document.body.style.overflow = 'auto';
                            document.documentElement.style.overflow = 'auto';
                            startTimer(60 * 10, document.querySelector('#purchase-timer'));
                        }, 800);
                    }, 500);
                    return;
                }

                // Update time display
                let m = Math.floor(secondsLeft / 60);
                let s = secondsLeft % 60;
                if (waitMin) waitMin.textContent = m < 10 ? "0" + m : m;
                if (waitSec) waitSec.textContent = s < 10 ? "0" + s : s;
                
                // Update progress
                let progress = ((initialSeconds - secondsLeft) / initialSeconds) * 100;
                if (progressBar) progressBar.style.width = progress + '%';
                
                // Update people count
                let remainingPeople = Math.floor((secondsLeft / initialSeconds) * totalPeople);
                if (peopleCount) peopleCount.textContent = 'Mohon tunggu, ada ' + remainingPeople.toLocaleString() + ' orang di depan Anda.';
                
            }, 1000);

            initSlider();
        } catch (e) {
            console.error("Initialization error:", e);
            // Fallback: hide overlay if something crashes
            const overlay = document.getElementById('queueOverlay');
            if (overlay) overlay.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    };

    function initSlider() {
        const handle = document.getElementById('sliderHandle');
        const container = document.getElementById('sliderContainer');
        const verifiedInput = document.getElementById('botVerified');
        let isDragging = false;

        handle.addEventListener('mousedown', startDrag);
        handle.addEventListener('touchstart', startDrag);
        document.addEventListener('mousemove', drag);
        document.addEventListener('touchmove', drag);
        document.addEventListener('mouseup', endDrag);
        document.addEventListener('touchend', endDrag);

        function startDrag(e) {
            if (verifiedInput.value === "1") return;
            isDragging = true;
            handle.style.transition = 'none';
        }

        function drag(e) {
            if (!isDragging) return;
            const containerRect = container.getBoundingClientRect();
            const clientX = e.type === 'touchmove' ? e.touches[0].clientX : e.clientX;
            let x = clientX - containerRect.left - 25;
            const maxX = containerRect.width - 60;

            if (x < 0) x = 0;
            if (x > maxX) x = maxX;

            handle.style.left = x + 'px';
            
            if (x >= maxX - 5) {
                isDragging = false;
                handle.style.left = maxX + 'px';
                handle.innerHTML = '<i class="bi bi-check-lg"></i>';
                handle.classList.add('verified');
                container.classList.add('verified');
                verifiedInput.value = "1";
                
                // Update UI for button
                const submitBtn = document.getElementById('btn-submit-booking');
                const warning = document.getElementById('captcha-warning');
                submitBtn.classList.remove('opacity-50');
                submitBtn.style.cursor = 'pointer';
                if (warning) warning.style.display = 'none';
            }
        }

        function endDrag() {
            if (!isDragging) return;
            isDragging = false;
            if (verifiedInput.value === "0") {
                handle.style.transition = 'left 0.3s ease';
                handle.style.left = '5px';
            }
        }
    }
</script>

@endsection