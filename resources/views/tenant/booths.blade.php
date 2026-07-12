@extends('v_layouts.app')

@section('title', 'Status Booth Saya')

@section('content')

<style>
    .bg-primary { background-color: #8b5cf6 !important; }
    .bg-primary.bg-opacity-10 { background-color: rgba(139, 92, 246, 0.1) !important; }
    .text-primary { color: #8b5cf6 !important; }
    i.text-primary { color: #8b5cf6 !important; }
    .badge.bg-primary { background-color: #8b5cf6 !important; }
    .btn-primary { background-color: #8b5cf6 !important; border-color: #8b5cf6 !important; }
    .btn-primary:hover, .btn-primary:focus, .btn-primary:active { background-color: #7c3aed !important; border-color: #7c3aed !important; }
    .list-group-item.active, a.list-group-item.active { background-color: #8b5cf6 !important; border-color: #8b5cf6 !important; }
    
    /* Interactive Popover Styling */
    .hover-purple {
        transition: all 0.2s ease;
        text-decoration: none;
    }
    .hover-purple:hover {
        color: #7c3aed !important;
        transform: translateY(-0.5px);
    }
    .popover {
        border: none !important;
        box-shadow: 0 10px 30px rgba(0,0,0,0.12) !important;
        border-radius: 12px !important;
        background: #ffffff !important;
    }
    .popover-body {
        padding: 12px 16px !important;
    }

    /* Hover effect  */
    .list-group-item-action:not(.active):hover {
        background-color: #f1f5f9 !important;
        color: #1e293b !important;
        padding-left: 25px !important;
    }

    /* Active state tetap soft purple */
    .list-group-item-action.active {
        background-color: #f5f3ff !important;
        color: #8b5cf6 !important;
    }

    .list-group-item-action.active:hover {
        background-color: #f5f3ff !important;
        color: #8b5cf6 !important;
        padding-left: 20px !important;
}
    
    /* Chevron Rotation Animation */
    .bi-chevron-down {
        transition: transform 0.2s ease-in-out;
    }
    [aria-expanded="true"] .bi-chevron-down {
        transform: rotate(180deg);
    }

    .list-group-item.text-danger,
       button.btn-link{
        transition: all 0.2s ease;
       }

    .list-group-item.text-danger:hover,
    button.btn-link:hover {
        background-color: rgba(239, 68, 68, 0.1) !important;
        box-shadow: none !important;
        transform: none !important;
        border-radius: 12px !important;
    }

    @media (max-width: 991px) {
    /* Sembunyikan tabel di mobile */
        .booth-table { display: none !important; }
        /* Tampilkan card list */
        .booth-cards { display: block !important; }
    }
    @media (min-width: 992px) {
        .booth-cards { display: none !important; }
        .booth-table table { min-width: unset !important; }
        .booth-table th, .booth-table td { white-space: nowrap; }
        .booth-table th:first-child, 
        .booth-table td:first-child { 
            max-width: 250px;
            width: 250px; 
        }

        /* Kompres padding */
        .booth-table th { padding: 12px 10px !important; font-size: 0.72rem !important; }
        .booth-table td { padding: 12px 10px !important; font-size: 0.82rem !important; }
        .booth-table td:first-child { padding-left: 16px !important; }
        .booth-table td:last-child { padding-right: 16px !important; }

        /* Avatar lebih kecil */
        .booth-table .rounded-circle[style*="40px"] {
            width: 34px !important;
            height: 34px !important;
        }

        /* Badge lebih compact */
        .booth-table .badge { 
            font-size: 0.7rem !important; 
            padding: 5px 10px !important;
            white-space: nowrap;
        }

        /* Tombol lebih compact */
        .booth-table .btn-sm { 
            font-size: 0.75rem !important; 
            padding: 5px 12px !important;
            white-space: nowrap;
        }
    }
</style>

<section class="py-5 bg-light min-vh-100">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" data-aos="fade-down">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none" style="color: #7c3aed;">Home</a></li>
                <li class="breadcrumb-item active">Status Booth Saya</li>
            </ol>
        </nav>

        <div class="row g-4 mt-3">
            <!-- Sidebar -->
            <div class="col-lg-3" data-aos="fade-right">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-3">
                        <div class="text-center mb-4 pt-3">
                            <div class="avatar-wrapper mb-3" style="width:120px;height:120px;margin:0 auto;">
                                <div style="width:100%;height:100%;background:linear-gradient(135deg,#8b5cf6 0%,#d8b4fe 100%);border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-size:3.5rem;box-shadow:0 8px 20px rgba(139,92,246,0.2);">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                            </div>
                            <h5 class="fw-bold text-dark mb-1">{{ Auth::user()->name }}</h5>
                            <span class="badge rounded-pill mb-4" style="background:#f5f3ff;color:#8b5cf6;">
                                <i class="bi bi-shield-check me-1"></i>{{ ucfirst(Auth::user()->role) }}
                            </span>
                        </div>

                        <div class="list-group" style="gap:4px;">
                            <a href="{{ route('profile') }}"
                            class="list-group-item list-group-item-action border-0 fw-semibold"
                            style="border-radius:12px;color:#64748b;padding:12px 20px;transition:0.3s;">
                                <i class="bi bi-person-vcard me-3"></i>Data Profil
                            </a>
                            <a href="{{ route('tenant.booths.index') }}"
                            class="list-group-item list-group-item-action border-0 fw-semibold active"
                            style="border-radius:12px;padding:12px 20px;background:#f5f3ff !important;color:#8b5cf6 !important;">
                                <i class="bi bi-shop me-3"></i>Status Booth
                            </a>
                        </div>

                        <hr class="my-4 opacity-50">

                        <form action="{{ route('logout') }}" method="POST" id="logoutFormBooths" class="px-1">
                            @csrf
                            <button type="button" onclick="confirmLogout('logoutFormBooths')" 
                            class="btn btn-link text-danger text-decoration-none fw-bold w-100 text-start" style="padding: 12px 20px;">
                                <i class="bi bi-box-arrow-left me-3"></i>Keluar Akun
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9" data-aos="fade-left">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h4 class="fw-bold mb-1" style="color: #1e1b4b;">Status Booth Saya</h4>
                        <p class="text-muted mb-0 small">Pantau status pendaftaran booth Anda dan lakukan pembayaran jika disetujui.</p>
                    </div>
                </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-0">
            @if($booths->isEmpty())
                <div class="text-center py-5">
                    <div class="bg-light rounded-circle d-inline-flex p-4 mb-3">
                        <i class="bi bi-shop fs-1 text-muted"></i>
                    </div>
                    <h5 class="fw-bold">Belum ada pendaftaran booth</h5>
                    <p class="text-muted mb-0">Anda belum mendaftar booth di event manapun.</p>
                    <a href="{{ route('home') }}" class="btn btn-primary rounded-pill px-4 mt-3">Cari Event</a>
                </div>
            @else
            {{-- TABEL (desktop) --}}
            <div class="table-responsive booth-table">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 text-muted small fw-bold text-uppercase ls-1 py-3">Event</th>
                            <th class="text-muted small fw-bold text-uppercase ls-1 py-3">Tanggal Event</th>
                            <th class="text-muted small fw-bold text-uppercase ls-1 py-3">Biaya Sewa</th>
                            <th class="text-muted small fw-bold text-uppercase ls-1 py-3">Status Pengajuan</th>
                            <th class="text-muted small fw-bold text-uppercase ls-1 py-3">Status Pembayaran</th>
                            <th class="text-end pe-4 text-muted small fw-bold text-uppercase ls-1 py-3">Aksi / Maps</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($booths as $b)
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:40px;height:40px;flex-shrink:0;">
                                        <i class="bi bi-calendar-event"></i>
                                    </div>
                                    <div style="max-width:210px; min-width:0px;">
                                        <h6 class="fw-bold mb-0 text-dark text-truncate"
                                        title= "{{ $b->event->title ?? 'Event Dihapus' }}">
                                        {{ $b->event->title ?? 'Event Dihapus' }}</h6>
                                    </div>
                                </div>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($b->event->date)->format('d M Y') }}</td>
                            <td>
                                @if($b->event->tenant_booth_price > 0)
                                    @php $basePrice=$b->event->tenant_booth_price; $fee=$basePrice*0.03; $total=$basePrice+$fee; @endphp
                                    <div class="d-flex flex-column align-items-start">
                                        <span class="fw-bold text-dark" style="font-size:0.95rem;">Rp{{ number_format($total,0,',','.') }}</span>
                                        <button class="btn p-0 border-0 text-muted small mt-1 hover-purple d-inline-flex align-items-center gap-1"
                                                type="button" data-bs-toggle="collapse" data-bs-target="#feeCollapse{{ $b->id }}"
                                                style="font-size:0.72rem;outline:none;box-shadow:none;">
                                            <i class="bi bi-chevron-down"></i> Detail Biaya
                                        </button>
                                        <div class="collapse mt-2" id="feeCollapse{{ $b->id }}" style="min-width:150px;">
                                            <div class="p-2 bg-light rounded-3 border" style="font-size:0.72rem;">
                                                <div class="d-flex justify-content-between gap-3 mb-1">
                                                    <span class="text-secondary">Sewa Dasar:</span>
                                                    <span class="fw-semibold text-dark">Rp{{ number_format($basePrice,0,',','.') }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between gap-3">
                                                    <span class="text-secondary">Fee (3%):</span>
                                                    <span class="fw-semibold text-dark">Rp{{ number_format($fee,0,',','.') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="badge bg-success-subtle text-success-emphasis rounded-pill px-3 fw-bold" style="font-size:0.75rem;">Gratis</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $scBg = match($b->status) { 'approved'=>'bg-success-subtle text-success-emphasis','rejected'=>'bg-danger-subtle text-danger-emphasis',default=>'bg-warning-subtle text-warning-emphasis' };
                                    $scIcon = match($b->status) { 'approved'=>'bi-check-circle-fill','rejected'=>'bi-x-circle-fill',default=>'bi-clock-fill' };
                                    $sl = match($b->status) { 'approved'=>'Disetujui','rejected'=>'Ditolak',default=>'Menunggu Review' };
                                @endphp
                                <span class="badge {{ $scBg }} rounded-pill px-3 py-2 fw-semibold d-inline-flex align-items-center gap-1" style="font-size:0.75rem;">
                                    <i class="bi {{ $scIcon }}"></i> {{ $sl }}
                                </span>
                            </td>
                            <td>
                                @if($b->status === 'approved' && $b->event->tenant_booth_price > 0)
                                    @php
                                        $pcBg = match(true) {
                                            $b->payment_status==='paid'&&$b->refund_status==='rejected'=>'bg-danger-subtle text-danger-emphasis',
                                            $b->payment_status==='paid'&&$b->refund_status==='approved'=>'bg-info-subtle text-info-emphasis',
                                            $b->payment_status==='paid'=>'bg-success-subtle text-success-emphasis',
                                            $b->payment_status==='refund_requested'=>'bg-warning-subtle text-warning-emphasis',
                                            $b->payment_status==='refunded'=>'bg-secondary-subtle text-secondary-emphasis',
                                            default=>'bg-danger-subtle text-danger-emphasis'
                                        };
                                        $pcIcon = match(true) {
                                            $b->payment_status==='paid'&&$b->refund_status==='rejected'=>'bi-x-circle',
                                            $b->payment_status==='paid'&&$b->refund_status==='approved'=>'bi-hourglass-split',
                                            $b->payment_status==='paid'=>'bi-wallet2',
                                            $b->payment_status==='refund_requested'=>'bi-hourglass-split',
                                            $b->payment_status==='refunded'=>'bi-arrow-left-right',
                                            default=>'bi-exclamation-circle'
                                        };
                                        $pl = match(true) {
                                            $b->payment_status==='paid'&&$b->refund_status==='rejected'=>'Refund Ditolak',
                                            $b->payment_status==='paid'&&$b->refund_status==='approved'=>'Diproses Admin',
                                            $b->payment_status==='paid'=>'Lunas',
                                            $b->payment_status==='refund_requested'=>'Minta Refund',
                                            $b->payment_status==='refunded'=>'Telah Refund',
                                            default=>'Belum Bayar'
                                        };
                                    @endphp
                                    <span class="badge {{ $pcBg }} rounded-pill px-3 py-2 fw-semibold d-inline-flex align-items-center gap-1" style="font-size:0.75rem;">
                                        <i class="bi {{ $pcIcon }}"></i> {{ $pl }}
                                    </span>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td class="text-end pe-4 text-nowrap">
                                @if($b->status === 'approved')
                                    @if($b->event->tenant_booth_price > 0 && $b->payment_status === 'pending')
                                        @if($b->snap_token)
                                            <button type="button" class="btn btn-primary btn-sm rounded-pill px-4 py-2 fw-bold shadow-sm" onclick="payBooth('{{ $b->snap_token }}')">
                                                <i class="bi bi-wallet2 me-1"></i> Lanjutkan Pembayaran
                                            </button>
                                        @else
                                            <form action="{{ route('tenant.booths.pay', $b->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 py-2 fw-bold shadow-sm">
                                                    <i class="bi bi-wallet2 me-1"></i> Bayar Sewa
                                                </button>
                                            </form>
                                        @endif
                                    @elseif($b->event->tenant_booth_price == 0 || $b->payment_status === 'paid')
                                        <div class="d-flex flex-column align-items-end gap-2">
                                            @if($b->event->booth_map)
                                                <button type="button" class="btn btn-success btn-sm rounded-pill px-3 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#mapModal{{ $b->id }}">
                                                    <i class="bi bi-map me-1"></i> Lihat Maps Booth
                                                </button>
                                            @elseif($b->event->map_notice)
                                                <button type="button" class="btn btn-sm rounded-pill px-3 fw-bold border border-warning"
                                                    style="background-color:rgba(255,193,7,0.1);font-size:0.75rem;"
                                                    data-bs-toggle="collapse" data-bs-target="#mapNotice{{ $b->id }}">
                                                    <i class="bi bi-info-circle me-1"></i>Info Denah
                                                    <i class="bi bi-chevron-down ms-1" style="font-size:0.65rem;"></i>
                                                </button>
                                                <div class="collapse mt-1" id="mapNotice{{ $b->id }}">
                                                    <div class="p-2 rounded-3 border border-warning text-start text-dark"
                                                        style="background-color:rgba(255,193,7,0.08);font-size:0.72rem;max-width:200px;white-space:normal;">
                                                        {{ $b->event->map_notice }}
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted small">Vendor belum mengunggah map</span>
                                            @endif

                                            @if($b->event->tenant_booth_price > 0 && $b->payment_status === 'paid')
                                                @if($b->refund_status === 'rejected')
                                                    <button type="button" class="btn btn-outline-warning btn-sm rounded-pill px-3 fw-bold" style="font-size:0.75rem;"
                                                        onclick="showRefundRejected('{{ addslashes($b->refund_reject_reason ?? '-') }}')">
                                                        <i class="bi bi-info-circle me-1"></i> Lihat Alasan Tolak
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-bold form-refund-btn"
                                                        style="font-size:0.75rem;" data-id="{{ $b->id }}"
                                                        data-action="{{ route('tenant.booths.refund', $b->id) }}">
                                                        Ajukan Refund Ulang
                                                    </button>
                                                @elseif($b->refund_status === 'none' || $b->refund_status === null)
                                                    <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-bold form-refund-btn"
                                                        style="font-size:0.75rem;" data-id="{{ $b->id }}"
                                                        data-action="{{ route('tenant.booths.refund', $b->id) }}">
                                                        Batal & Ajukan Refund
                                                    </button>
                                                @elseif($b->refund_status === 'requested')
                                                    <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-3 py-2" style="font-size:0.75rem;">
                                                        <i class="bi bi-hourglass-split me-1"></i> Menunggu Vendor
                                                    </span>
                                                @elseif($b->refund_status === 'approved')
                                                    <span class="badge bg-info-subtle text-info-emphasis rounded-pill px-3 py-2" style="font-size:0.75rem;">
                                                        <i class="bi bi-hourglass-split me-1"></i> Diproses Admin
                                                    </span>
                                                @endif
                                            @endif
                                        </div>
                                    @endif
                                @elseif($b->status === 'rejected')
                                    <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-bold shadow-sm"
                                        onclick="showRejectedNotification('{{ addslashes($b->event->title ?? 'Event') }}')">
                                        <i class="bi bi-exclamation-circle me-1"></i> Info Penolakan
                                    </button>
                                @else
                                    <button class="btn btn-secondary btn-sm rounded-pill px-3 disabled">Menunggu</button>
                                @endif
                            </td>
                        </tr>

                        {{-- Map Modal --}}
                        @if($b->event->booth_map && ($b->event->tenant_booth_price == 0 || $b->payment_status === 'paid'))
                        <div class="modal fade" id="mapModal{{ $b->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content border-0 rounded-4 shadow-lg">
                                    <div class="modal-header border-bottom-0 pb-0 px-4 pt-4">
                                        <h5 class="modal-title fw-bold">Maps Booth: {{ $b->event->title }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body p-4 text-center">
                                        <img src="{{ asset('storage/' . $b->event->booth_map) }}" alt="Booth Map" class="img-fluid rounded-3 border">
                                        <div class="alert alert-info mt-3 mb-0 text-start">
                                            <i class="bi bi-info-circle-fill me-2"></i> Silakan tunjukkan bukti pendaftaran dan map ini saat hari-H.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- CARD LIST (mobile) --}}
            <div class="booth-cards p-3">
                @foreach($booths as $b)
                @php
                    $basePrice = $b->event->tenant_booth_price ?? 0;
                    $fee = $basePrice * 0.03;
                    $total = $basePrice + $fee;
                    $sl = match($b->status) { 'approved'=>'Disetujui','rejected'=>'Ditolak',default=>'Menunggu Review' };
                    $scBg = match($b->status) { 'approved'=>'bg-success-subtle text-success-emphasis','rejected'=>'bg-danger-subtle text-danger-emphasis',default=>'bg-warning-subtle text-warning-emphasis' };
                    $scIcon = match($b->status) { 'approved'=>'bi-check-circle-fill','rejected'=>'bi-x-circle-fill',default=>'bi-clock-fill' };
                @endphp
                <div class="card border-0 shadow-sm rounded-4 mb-3">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0"
                                style="width:42px;height:42px;background:linear-gradient(135deg,#8b5cf6,#a855f7);">
                                <i class="bi bi-calendar-event"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark" style="font-size:0.92rem;">{{ $b->event->title ?? 'Event Dihapus' }}</div>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($b->event->date)->format('d M Y') }}</small>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small">Biaya Sewa</span>
                            @if($basePrice > 0)
                                <span class="fw-bold" style="font-size:0.88rem;">Rp{{ number_format($total,0,',','.') }}</span>
                            @else
                                <span class="badge bg-success-subtle text-success-emphasis rounded-pill px-2">Gratis</span>
                            @endif
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small">Status Pengajuan</span>
                            <span class="badge {{ $scBg }} rounded-pill px-2 py-1" style="font-size:0.72rem;">
                                <i class="bi {{ $scIcon }} me-1"></i>{{ $sl }}
                            </span>
                        </div>

                        @if($b->status === 'approved' && $basePrice > 0)
                        @php
                            $pl = match(true) {
                                $b->payment_status==='paid'&&$b->refund_status==='rejected'=>'Refund Ditolak',
                                $b->payment_status==='paid'&&$b->refund_status==='approved'=>'Diproses Admin',
                                $b->payment_status==='paid'=>'Lunas',
                                $b->payment_status==='refund_requested'=>'Minta Refund',
                                $b->payment_status==='refunded'=>'Telah Refund',
                                default=>'Belum Bayar'
                            };
                            $pcBg = match(true) {
                                $b->payment_status==='paid'&&$b->refund_status==='rejected'=>'bg-danger-subtle text-danger-emphasis',
                                $b->payment_status==='paid'&&$b->refund_status==='approved'=>'bg-info-subtle text-info-emphasis',
                                $b->payment_status==='paid'=>'bg-success-subtle text-success-emphasis',
                                $b->payment_status==='refund_requested'=>'bg-warning-subtle text-warning-emphasis',
                                $b->payment_status==='refunded'=>'bg-secondary-subtle text-secondary-emphasis',
                                default=>'bg-danger-subtle text-danger-emphasis'
                            };
                        @endphp
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small">Status Bayar</span>
                            <span class="badge {{ $pcBg }} rounded-pill px-2 py-1" style="font-size:0.72rem;">{{ $pl }}</span>
                        </div>
                        @endif

                        <hr class="my-2 opacity-25">

                        <div class="d-flex flex-wrap gap-2 justify-content-end">
                            @if($b->status === 'approved')
                                @if($basePrice > 0 && $b->payment_status === 'pending')
                                    @if($b->snap_token)
                                        <button class="btn btn-primary btn-sm rounded-pill px-3 fw-bold w-100" onclick="payBooth('{{ $b->snap_token }}')">
                                            <i class="bi bi-wallet2 me-1"></i>Lanjutkan Bayar
                                        </button>
                                    @else
                                        <form action="{{ route('tenant.booths.pay', $b->id) }}" method="POST" class="w-100">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold w-100">
                                                <i class="bi bi-wallet2 me-1"></i>Bayar Sewa
                                            </button>
                                        </form>
                                    @endif
                                @elseif($basePrice == 0 || $b->payment_status === 'paid')
                                    @if($b->event->booth_map)
                                        <button class="btn btn-success btn-sm rounded-pill px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#mapModal{{ $b->id }}">
                                            <i class="bi bi-map me-1"></i>Lihat Map
                                        </button>
                                    @elseif($b->event->map_notice)
                                        <button class="btn btn-sm rounded-pill px-3 fw-bold border border-warning w-100"
                                            style="background:rgba(255,193,7,0.1);font-size:0.75rem;"
                                            data-bs-toggle="collapse" data-bs-target="#mapNoticeMobile{{ $b->id }}">
                                            <i class="bi bi-info-circle me-1"></i>Info Denah
                                        </button>
                                        <div class="collapse w-100" id="mapNoticeMobile{{ $b->id }}">
                                            <div class="p-2 rounded-3 border border-warning text-dark mt-1"
                                                style="background:rgba(255,193,7,0.08);font-size:0.75rem;">
                                                {{ $b->event->map_notice }}
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted small">Vendor belum mengunggah map</span>
                                    @endif

                                    @if($basePrice > 0 && $b->payment_status === 'paid')
                                        @if($b->refund_status === 'none' || $b->refund_status === null)
                                            <button class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-bold form-refund-btn w-100"
                                                data-id="{{ $b->id }}" data-action="{{ route('tenant.booths.refund', $b->id) }}">
                                                Batal & Ajukan Refund
                                            </button>
                                        @elseif($b->refund_status === 'rejected')
                                            <button class="btn btn-outline-warning btn-sm rounded-pill px-3 fw-bold"
                                                onclick="showRefundRejected('{{ addslashes($b->refund_reject_reason ?? '-') }}')">
                                                <i class="bi bi-info-circle me-1"></i>Alasan Tolak
                                            </button>
                                            <button class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-bold form-refund-btn"
                                                data-id="{{ $b->id }}" data-action="{{ route('tenant.booths.refund', $b->id) }}">
                                                Refund Ulang
                                            </button>
                                        @elseif($b->refund_status === 'requested')
                                            <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-3 py-2 w-100 text-center" style="font-size:0.75rem;">
                                                <i class="bi bi-hourglass-split me-1"></i>Menunggu Vendor
                                            </span>
                                        @elseif($b->refund_status === 'approved')
                                            <span class="badge bg-info-subtle text-info-emphasis rounded-pill px-3 py-2 w-100 text-center" style="font-size:0.75rem;">
                                                <i class="bi bi-hourglass-split me-1"></i>Diproses Admin
                                            </span>
                                        @endif
                                    @endif
                                @endif
                            @elseif($b->status === 'rejected')
                                <button class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-bold w-100"
                                    onclick="showRejectedNotification('{{ addslashes($b->event->title ?? 'Event') }}')">
                                    <i class="bi bi-exclamation-circle me-1"></i>Info Penolakan
                                </button>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary-emphasis rounded-pill px-3 py-2 w-100 text-center">
                                    <i class="bi bi-clock me-1"></i>Menunggu Review
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
        </div>
        @if($booths->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $booths->links() }}
            </div>
        @endif
    </div>
        </div>
    </div>
</section>

@push('scripts')
<script src="https://app.{{ config('midtrans.is_production') ? '' : 'sandbox.' }}midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    function payBooth(token) {
        window.snap.pay(token, {
            onSuccess: function(result){
                window.location.href = window.location.pathname + "?status=success&order_id=" + result.order_id + "&transaction_status=" + result.transaction_status;
            },
            onPending: function(result){
                window.location.href = window.location.pathname + "?status=pending&order_id=" + result.order_id + "&transaction_status=" + result.transaction_status;
            },
            onError: function(result){
                Swal.fire({
                    title: 'Gagal',
                    text: 'Terjadi kesalahan saat memproses pembayaran Anda.',
                    icon: 'error',
                    confirmButtonColor: '#ef4444'
                });
            },
            onClose: function(){
                // just close the popup
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Bootstrap Popovers
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
        var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl)
        });

        // Tooltip dihapus karena diganti collapse (tidak berfungsi di HP)
        // var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        // tooltipTriggerList.map(function(el) {
        //     return new bootstrap.Tooltip(el);
        // });

        document.querySelectorAll('.form-refund-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const action = this.getAttribute('data-action');
                Swal.fire({
                    title: 'Batalkan Sewa?',
                    html: `
                        <p class="text-muted mb-3">Masukkan alasan pembatalan booth ini.</p>
                        <textarea id="refund-reason" class="form-control" rows="3" 
                            placeholder="Contoh: Tidak jadi ikut event karena..."></textarea>
                    `,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Batalkan!',
                    cancelButtonText: 'Tutup',
                    preConfirm: () => {
                        const reason = document.getElementById('refund-reason').value;
                        if (!reason) {
                            Swal.showValidationMessage('Alasan refund wajib diisi!');
                            return false;
                        }
                        return reason;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = action;
                        form.innerHTML = `
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="refund_reason" value="${result.value}">
                        `;
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        });
    });

    function showRefundRejected(reason) {
        Swal.fire({
            title: 'Refund Ditolak Vendor',
            html: `
                <p class="text-muted">Alasan penolakan:</p>
                <div class="alert alert-danger text-start">${reason}</div>
                <p class="small text-muted">Anda masih bisa mengajukan refund ulang.</p>
            `,
            icon: 'warning',
            confirmButtonColor: '#7c3aed',
            confirmButtonText: 'Mengerti'
        });
    }

    function showRejectedNotification(eventName) {
        Swal.fire({
            title: 'Pengajuan Ditolak',
            text: 'Mohon maaf, pengajuan booth Anda untuk event "' + eventName + '" belum dapat disetujui oleh Vendor. Jangan patah semangat, mari cari event lain yang cocok untuk bisnis Anda!',
            icon: 'error',
            confirmButtonColor: '#7c3aed',
            confirmButtonText: 'Cari Event Lain'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ route('home') }}";
            }
        });
    }

    function confirmLogout(formId) {
        Swal.fire({
            title: 'Keluar Akun?',
            text: 'Apakah kamu yakin ingin keluar dari FiveFest?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="bi bi-box-arrow-right me-1"></i> Ya, Keluar',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    }
</script>
@endpush
@endsection