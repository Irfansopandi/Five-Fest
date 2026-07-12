@extends('v_vendor.v_layouts.app')

@section('title', 'Detail Transaksi Tiket')

@section('content')
<div class="container-fluid px-4 py-4" style="max-width: 900px;">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('vendor.pengguna-tiket') }}" class="btn btn-light border shadow-sm rounded-3 px-3 py-2 fw-medium text-muted hover-primary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
            <h4 class="fw-bold text-dark mb-0">Detail Transaksi</h4>
        </div>
    </div>

    <!-- Top Grid 2x2 -->
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <p class="text-muted small mb-1 fw-semibold">ID Transaksi</p>
                    <h5 class="fw-bold mb-0 text-dark">#{{ $ticket->booking_code }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <p class="text-muted small mb-2 fw-semibold">Status</p>
                    @if($ticket->payment_status === 'paid' && $ticket->booking_status === 'confirmed')
                        <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill fw-bold">
                            <i class="bi bi-check-circle-fill me-1"></i> Terkonfirmasi
                        </span>
                    @elseif($ticket->payment_status === 'pending')
                        <span class="badge bg-warning-subtle text-warning px-3 py-2 rounded-pill fw-bold">
                            <i class="bi bi-clock-history me-1"></i> Menunggu Pembayaran
                        </span>
                    @else
                        <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill fw-bold">
                            <i class="bi bi-x-circle-fill me-1"></i> {{ ucfirst($ticket->booking_status) }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <p class="text-muted small mb-1 fw-semibold">Tanggal Pembelian</p>
                    <h6 class="fw-bold mb-0 text-dark">{{ $ticket->created_at->format('d M Y, H:i') }} WIB</h6>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <p class="text-muted small mb-1 fw-semibold">Metode Pembayaran</p>
                    <h6 class="fw-bold mb-0 text-dark">
                        @php
                            $pm = $ticket->payment_method;
                            if (!$pm) {
                                $methodStr = 'Online Payment (Midtrans)';
                            } else {
                                if(str_contains($pm, 'bank_transfer') || str_contains($pm, 'bca') || str_contains($pm, 'bni') || str_contains($pm, 'bri') || str_contains($pm, 'mandiri')) {
                                    $methodStr = 'Transfer Bank (' . strtoupper(str_replace('bank_transfer_', '', $pm)) . ')';
                                } elseif(str_contains($pm, 'qris')) {
                                    $methodStr = 'QRIS';
                                } elseif(str_contains($pm, 'gopay')) {
                                    $methodStr = 'GoPay';
                                } elseif(str_contains($pm, 'shopeepay')) {
                                    $methodStr = 'ShopeePay';
                                } else {
                                    $methodStr = strtoupper(str_replace('_', ' ', $pm));
                                }
                            }
                        @endphp
                        {{ $methodStr }}
                    </h6>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Pembeli -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <h6 class="fw-bold mb-4 text-dark"><i class="bi bi-person me-2 text-primary"></i>Data Pembeli</h6>
            <div class="d-flex align-items-center mb-4">
                <div class="avatar-circle bg-primary-subtle text-primary me-3 fw-bold d-flex align-items-center justify-content-center rounded-circle" style="width: 48px; height: 48px; font-size: 1.2rem;">
                    {{ strtoupper(substr($ticket->user->name ?? $ticket->guest_name, 0, 1)) }}
                </div>
                <div>
                    <h6 class="fw-bold mb-1 text-dark">{{ $ticket->user->name ?? $ticket->guest_name }}</h6>
                    <p class="text-muted small mb-0">{{ $ticket->user->email ?? $ticket->guest_email }}</p>
                </div>
            </div>
            <hr class="text-muted opacity-25">
            <div class="row mt-3">
                <div class="col-6">
                    <p class="text-muted small mb-1">No. Telepon</p>
                    <h6 class="fw-semibold text-dark">{{ $ticket->user->phone ?? $ticket->guest_phone ?? '-' }}</h6>
                </div>
                <div class="col-6">
                    <p class="text-muted small mb-1">Kota</p>
                    <h6 class="fw-semibold text-dark">{{ $ticket->user->city ?? '-' }}</h6>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Event & Tiket -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <h6 class="fw-bold mb-4 text-dark"><i class="bi bi-calendar-event me-2 text-primary"></i>Detail Event & Tiket</h6>
            <div class="d-flex flex-column flex-md-row gap-4 align-items-md-start mb-4">
                <div class="bg-light rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 70px; height: 70px;">
                    <i class="bi bi-ticket-perforated text-muted fs-3"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="fw-bold mb-2 text-dark">{{ $ticket->event->title }}</h6>
                    <p class="text-muted small mb-1">{{ \Carbon\Carbon::parse($ticket->event->date)->translatedFormat('l, d F Y') }} • {{ \Carbon\Carbon::parse($ticket->event->time)->format('H:i') }} WIB</p>
                    <p class="text-muted small mb-3">{{ $ticket->event->venue }}, {{ $ticket->event->city ?? 'Indonesia' }}</p>

                    <div class="d-flex gap-2">
                        <span class="badge bg-dark text-white rounded-pill px-3 py-2 border border-secondary">{{ $ticket->ticket_category->name ?? '-' }}</span>
                        <span class="badge bg-dark text-white rounded-pill px-3 py-2 border border-secondary">{{ $ticket->quantity }} Tiket</span>
                    </div>
                </div>
                <div class="text-md-end mt-3 mt-md-0">
                    <p class="text-muted small mb-1">Harga</p>
                    <h5 class="fw-bold text-dark mb-0">Rp {{ number_format($ticket->total_price, 0, ',', '.') }}</h5>
                </div>
            </div>

            <hr class="text-muted opacity-25">

            <div class="mt-4">
                <p class="text-muted small mb-2 fw-semibold">Kode Tiket / QR</p>
                <div class="d-flex gap-2 align-items-center">
                    <div class="border rounded-3 px-3 py-2 bg-light d-inline-block fw-bold text-dark font-monospace">
                        {{ $ticket->booking_code }}
                    </div>
                    <button type="button" class="btn btn-outline-secondary rounded-3 btn-sm d-flex align-items-center btn-copy-code"
                        data-copy-text="{{ $ticket->booking_code }}">
                        <i class="bi bi-files me-1 copy-icon"></i> <span class="copy-label">Salin</span>
                    </button>
                </div>
            </div>
            @if($ticket->tickets && $ticket->tickets->count() > 0)
                <div class="mt-3">
                    <p class="text-muted small mb-2 fw-semibold">Kode Tiket Individual</p>
                    <div class="d-flex flex-column gap-2">
                        @foreach($ticket->tickets as $t)
                        <div class="d-flex gap-2 align-items-center">
                            <div class="border rounded-3 px-3 py-2 bg-light d-inline-block fw-bold text-dark font-monospace">
                                {{ $t->ticket_code }}
                            </div>
                            <span class="text-muted small">{{ $t->seat_number ?? '' }}</span>
                            <button type="button" class="btn btn-outline-secondary rounded-3 btn-sm d-flex align-items-center btn-copy-code"
                                data-copy-text="{{ $t->ticket_code }}">
                                <i class="bi bi-files me-1 copy-icon"></i> <span class="copy-label">Salin</span>
                            </button>
                            @if($t->status === 'scanned')
                                <span class="badge bg-success-subtle text-success rounded-pill px-2">
                                    <i class="bi bi-check-circle-fill me-1"></i>Sudah Di-scan
                                </span>
                            @else
                                <span class="badge bg-warning-subtle text-warning rounded-pill px-2">
                                    <i class="bi bi-clock me-1"></i>Belum Di-scan
                                </span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Merchandise -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <h6 class="fw-bold mb-4 text-dark"><i class="bi bi-bag me-2 text-primary"></i>Merchandise</h6>

            @if($ticket->merchandises && $ticket->merchandises->count() > 0)
                <div class="d-flex flex-column gap-3">
                    @foreach($ticket->merchandises as $merch)
                        <div class="d-flex align-items-center justify-content-between border rounded-3 p-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-light rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 50px; height: 50px;">
                                    @if($merch->image)
                                        <img src="{{ asset('storage/' . $merch->image) }}" alt="{{ $merch->name }}" class="rounded-3" style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <i class="bi bi-bag text-muted fs-5"></i>
                                    @endif
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1 text-dark">{{ $merch->name }}</h6>
                                    <p class="text-muted small mb-0">
                                        {{ $merch->pivot->quantity }}x
                                        @if(!empty($merch->pivot->size))
                                            • Ukuran {{ $merch->pivot->size }}
                                        @endif
                                        @if(!empty($merch->pivot->version))
                                            • {{ $merch->pivot->version }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <h6 class="fw-bold text-dark mb-0">Rp {{ number_format($merch->pivot->price * $merch->pivot->quantity, 0, ',', '.') }}</h6>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="d-flex align-items-center gap-2 text-muted py-2">
                    <i class="bi bi-bag-x fs-5"></i>
                    <span class="small">Pembeli tidak membeli merchandise pada transaksi ini.</span>
                </div>
            @endif
        </div>
    </div>

    <!-- Rincian Pembayaran -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <h6 class="fw-bold mb-4 text-dark"><i class="bi bi-receipt me-2 text-primary"></i>Rincian Pembayaran</h6>

            @php
                // Menghitung kembali harga dasar dari total_price (karena total_price sudah ditambah pajak+layanan 13%)
                $basePrice = $ticket->total_price / 1.13;
                $pajak = $basePrice * 0.10;
                $layanan = $basePrice * 0.03;
            @endphp

            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Harga tiket ({{ $ticket->quantity }}x {{ $ticket->ticket_category->name ?? '-' }})</span>
                <span class="fw-medium text-dark">Rp {{ number_format($basePrice, 0, ',', '.') }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Pajak (10%)</span>
                <span class="fw-medium text-dark">Rp {{ number_format($pajak, 0, ',', '.') }}</span>
            </div>
            <div class="d-flex justify-content-between mb-3">
                <span class="text-muted">Biaya layanan (3%)</span>
                <span class="fw-medium text-dark">Rp {{ number_format($layanan, 0, ',', '.') }}</span>
            </div>

            <hr class="text-muted opacity-25">

            <div class="d-flex justify-content-between mt-3">
                <span class="fw-bold text-dark fs-6">Total Pembayaran</span>
                <span class="fw-bold text-dark fs-5">Rp {{ number_format($ticket->total_price, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Riwayat Status -->
    <div class="card border-0 shadow-sm rounded-4 mb-5">
        <div class="card-body p-4">
            <h6 class="fw-bold mb-4 text-dark"><i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Status</h6>

            <div class="timeline position-relative ms-2">
                @if($ticket->payment_status === 'paid' && $ticket->booking_status === 'confirmed')
                <!-- Step 3: Dikonfirmasi -->
                <div class="timeline-item position-relative pb-4 ps-4">
                    <div class="timeline-point position-absolute bg-success rounded-circle border border-white border-2" style="width: 14px; height: 14px; left: -7px; top: 5px;"></div>
                    <div class="timeline-line position-absolute bg-success opacity-25" style="width: 2px; height: 100%; left: -1px; top: 15px;"></div>
                    <h6 class="fw-bold text-dark mb-1">Pembayaran Dikonfirmasi</h6>
                    <p class="text-muted small mb-0">{{ $ticket->updated_at->format('d M Y, H:i') }} WIB</p>
                </div>
                <!-- Step 2: Diterima -->
                <div class="timeline-item position-relative pb-4 ps-4">
                    <div class="timeline-point position-absolute bg-success rounded-circle border border-white border-2" style="width: 14px; height: 14px; left: -7px; top: 5px;"></div>
                    <div class="timeline-line position-absolute bg-success opacity-25" style="width: 2px; height: 100%; left: -1px; top: 15px;"></div>
                    <h6 class="fw-bold text-dark mb-1">Pembayaran Diterima</h6>
                    <p class="text-muted small mb-0">{{ $ticket->updated_at->subMinutes(2)->format('d M Y, H:i') }} WIB</p>
                </div>
                @elseif($ticket->payment_status === 'pending')
                <!-- Step 2: Menunggu -->
                <div class="timeline-item position-relative pb-4 ps-4">
                    <div class="timeline-point position-absolute bg-warning rounded-circle border border-white border-2" style="width: 14px; height: 14px; left: -7px; top: 5px;"></div>
                    <div class="timeline-line position-absolute bg-secondary opacity-25" style="width: 2px; height: 100%; left: -1px; top: 15px;"></div>
                    <h6 class="fw-bold text-dark mb-1">Menunggu Pembayaran</h6>
                    <p class="text-muted small mb-0">{{ $ticket->created_at->format('d M Y, H:i') }} WIB</p>
                </div>
                @endif

                <!-- Step 1: Dibuat -->
                <div class="timeline-item position-relative ps-4">
                    <div class="timeline-point position-absolute bg-secondary rounded-circle border border-white border-2" style="width: 14px; height: 14px; left: -7px; top: 5px;"></div>
                    <h6 class="fw-bold text-dark mb-1">Pesanan Dibuat</h6>
                    <p class="text-muted small mb-0">{{ $ticket->created_at->format('d M Y, H:i') }} WIB</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-primary:hover {
        color: #7c3aed !important;
        background-color: #f8f9fa !important;
        border-color: #7c3aed !important;
    }
    .text-primary { color: #7c3aed !important; }
    .bg-primary-subtle { background-color: #f3f0ff !important; color: #7c3aed !important; }
    .bg-success-subtle { background-color: #ecfdf5 !important; color: #059669 !important; }
    .bg-warning-subtle { background-color: #fffbeb !important; color: #d97706 !important; }
    .bg-danger-subtle { background-color: #fef2f2 !important; color: #dc2626 !important; }
    .btn-copy-code.copied {
        color: #059669 !important;
        border-color: #059669 !important;
        background-color: #ecfdf5 !important;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-copy-code').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const text = btn.getAttribute('data-copy-text');
            const icon = btn.querySelector('.copy-icon');
            const label = btn.querySelector('.copy-label');

            navigator.clipboard.writeText(text).then(function () {
                // simpan state asli biar bisa balik lagi
                const originalIconClass = icon.className;
                const originalLabel = label.textContent;

                icon.className = 'bi bi-check-lg me-1 copy-icon';
                label.textContent = 'Tersalin';
                btn.classList.add('copied');

                setTimeout(function () {
                    icon.className = originalIconClass;
                    label.textContent = originalLabel;
                    btn.classList.remove('copied');
                }, 1500);
            }).catch(function () {
                alert('Gagal menyalin kode.');
            });
        });
    });
});
</script>
@endsection