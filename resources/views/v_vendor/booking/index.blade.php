@extends('v_vendor.v_layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Daftar Transaksi Tiket</h4>
            <p class="text-muted small">Pantau status transaksi, pendapatan, dan kelola penjualan tiket Anda.</p>
        </div>
    </div>

    {{-- STATS CARDS --}}
    <div class="row g-4 mb-5">
        <div class="col-12 col-md-3" {!! $tab === 'penjualan' ? 'data-aos="fade-up" data-aos-delay="100"' : '' !!}>
            <div class="stat-card stat-card--green h-100">
                <div class="stat-card__icon">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <div class="stat-card__body">
                    <div class="stat-card__label">Pendapatan</div>
                    <div class="stat-card__value">Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-3" {!! $tab === 'penjualan' ? 'data-aos="fade-up" data-aos-delay="200"' : '' !!}>
            <div class="stat-card stat-card--blue h-100">
                <div class="stat-card__icon">
                    <i class="bi bi-ticket-perforated-fill"></i>
                </div>
                <div class="stat-card__body">
                    <div class="stat-card__label">Tiket Terjual</div>
                    <div class="stat-card__value">{{ $tiketTerjual ?? 0 }} <span class="fs-6 fw-normal" style="color: rgba(255,255,255,0.8);">Tiket</span></div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-3" {!! $tab === 'penjualan' ? 'data-aos="fade-up" data-aos-delay="250"' : '' !!}>
            <div class="stat-card stat-card--purple h-100">
                <div class="stat-card__icon">
                    <i class="bi bi-bag-check-fill"></i>
                </div>
                <div class="stat-card__body">
                    <div class="stat-card__label">Merchandise Terjual</div>
                    <div class="stat-card__value">{{ $merchandiseTerjual ?? 0 }} <span class="fs-6 fw-normal" style="color: rgba(255,255,255,0.8);">Item</span></div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-3" {!! $tab === 'penjualan' ? 'data-aos="fade-up" data-aos-delay="300"' : '' !!}>
            <div class="stat-card stat-card--red h-100">
                <div class="stat-card__icon">
                    <i class="bi bi-x-circle"></i>
                </div>
                <div class="stat-card__body">
                    <div class="stat-card__label">Batal / Expired</div>
                    <div class="stat-card__value">{{ $batal ?? 0 }} <span class="fs-6 fw-normal" style="color: rgba(255,255,255,0.8);">Trx</span></div>
                </div>
            </div>
        </div>
    </div>

    {{-- TABS & FILTERS --}}
    @php
        $tab = $tab ?? 'penjualan';
    @endphp
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <ul class="nav nav-pills p-1.5 bg-light rounded-pill gap-1 mb-0 border" style="padding: 6px;">
            <li class="nav-item">
                <a class="nav-link rounded-pill px-4 py-2 fw-bold {{ $tab === 'penjualan' ? 'active' : '' }}" 
                   href="{{ route('vendor.bookings.index', ['tab' => 'penjualan', 'per_page' => $perPage, 'event_id' => $eventId ?? '']) }}">
                    <i class="bi bi-check-circle me-2"></i>Penjualan Tiket
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link rounded-pill px-4 py-2 fw-bold {{ $tab === 'riwayat' ? 'active' : '' }}" 
                   href="{{ route('vendor.bookings.index', ['tab' => 'riwayat', 'per_page' => $perPage, 'event_id' => $eventId ?? '']) }}">
                    <i class="bi bi-clock-history me-2"></i>Riwayat Transaksi
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link rounded-pill px-4 py-2 fw-bold {{ $tab === 'batal' ? 'active' : '' }}" 
                   href="{{ route('vendor.bookings.index', ['tab' => 'batal', 'per_page' => $perPage, 'event_id' => $eventId ?? '']) }}">
                    <i class="bi bi-calendar-x me-2"></i>Pesanan Expired
                </a>
            </li>
        </ul>

        <div class="d-flex gap-3 align-items-center flex-wrap">
             {{-- Tombol Export PDF --}}
            <a href="{{ route('vendor.bookings.export', ['tab' => $tab, 'event_id' => $eventId ?? '']) }}"
            class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-bold shadow-sm">
                <i class="bi bi-file-earmark-pdf-fill me-1"></i> Export PDF
            </a>
            <form action="{{ route('vendor.bookings.index') }}" method="GET" class="d-flex align-items-center gap-2 bg-white px-3 py-2 rounded-pill shadow-sm border mb-0" id="filterForm">
                @if(isset($tab))
                    <input type="hidden" name="tab" value="{{ $tab }}">
                @endif
                
                <i class="bi bi-calendar-event ms-1" style="color: #7c3aed;"></i>
                <select name="event_id" class="form-select form-select-sm border-0 bg-transparent fw-bold text-dark pe-4 py-0 border-end border-2" onchange="document.getElementById('filterForm').submit()" style="cursor: pointer; box-shadow: none; max-width: 200px;">
                    <option value="">Semua Event</option>
                    @foreach($vendorEvents as $event)
                        <option value="{{ $event->id }}" {{ (isset($eventId) && $eventId == $event->id) ? 'selected' : '' }}>
                            {{ Str::limit($event->title, 25) }}
                        </option>
                    @endforeach
                </select>

                <i class="bi bi-funnel ms-2" style="color: #7c3aed;"></i>
                <select name="per_page" id="per_page" class="form-select form-select-sm border-0 bg-transparent fw-bold text-dark pe-4 py-0" onchange="document.getElementById('filterForm').submit()" style="cursor: pointer; box-shadow: none;">
                    <option value="10" {{ (isset($perPage) && $perPage == 10) ? 'selected' : '' }}>10 Data</option>
                    <option value="25" {{ (isset($perPage) && $perPage == 25) ? 'selected' : '' }}>25 Data</option>
                    <option value="50" {{ (isset($perPage) && $perPage == 50) ? 'selected' : '' }}>50 Data</option>
                    <option value="100" {{ (isset($perPage) && $perPage == 100) ? 'selected' : '' }}>100 Data</option>
                </select>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden" {!! $tab === 'penjualan' ? 'data-aos="fade-up" data-aos-delay="500"' : '' !!}>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-muted small fw-bold text-uppercase ls-1">Kode</th>
                        <th class="py-3 text-muted small fw-bold text-uppercase ls-1">Event</th>
                        <th class="py-3 text-muted small fw-bold text-uppercase ls-1">Pembeli</th>
                        <th class="py-3 text-muted small fw-bold text-uppercase ls-1 text-center">Jumlah</th>
                        <th class="py-3 text-muted small fw-bold text-uppercase ls-1">Merchandise</th>
                        <th class="py-3 text-muted small fw-bold text-uppercase ls-1">Total</th>
                        <th class="py-3 text-muted small fw-bold text-uppercase ls-1 text-center">Status</th>
                        <th class="pe-4 py-3 text-muted small fw-bold text-uppercase ls-1 text-end">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                    <tr>
                        <td class="ps-4">
                            <span class="fw-bold text-primary small">{{ $booking->booking_code }}</span>
                        </td>
                        <td>
                            <div class="fw-bold text-dark small">{{ $booking->event->title ?? '-' }}</div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-xs bg-light rounded-circle d-flex align-items-center justify-content-center me-2 text-primary fw-bold" style="width: 30px; height: 30px; font-size: 0.75rem;">
                                    {{ strtoupper(substr($booking->user->name ?? 'G', 0, 1)) }}
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-dark small">{{ $booking->user->name ?? 'Guest' }}</span>
                                    <small class="text-muted" style="font-size: 0.65rem;">{{ $booking->user->email ?? '-' }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="text-center small">{{ $booking->quantity }} <span class="text-muted">Tiket</span></td>
                        <td>
                            @if($booking->merchandises && $booking->merchandises->count())
                                @foreach($booking->merchandises as $merch)
                                <div class="small">
                                    <span class="fw-semibold">{{ $merch->name }}</span>
                                    <span class="text-muted">×{{ $merch->pivot->quantity }}</span>
                                </div>
                                @endforeach
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td class="fw-bold text-dark small">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                        <td class="text-center">
                            @php
                                $statusClass = match($booking->payment_status) {
                                    'paid' => 'success',
                                    'pending' => 'warning',
                                    'cancelled' => 'danger',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $statusClass }}-subtle text-{{ $statusClass }} px-3 py-2 rounded-pill fw-medium" style="font-size: 0.7rem;">
                                {{ strtoupper($booking->payment_status) }}
                            </span>
                        </td>
                        <td class="text-end pe-4 small text-muted">
                            <div class="fw-medium">{{ $booking->created_at->format('d M Y') }}</div>
                            <div style="font-size: 0.7rem;">{{ $booking->created_at->format('H:i') }} WIB</div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <div class="bg-light rounded-circle d-inline-flex p-4 mb-3">
                                <i class="bi bi-inbox fs-1"></i>
                            </div>
                            <p class="mb-0">Belum ada data booking.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bookings->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $bookings->links() }}
        </div>
        @endif
    </div>
</div>

<style>
    /* ===== TAB STYLING (SIMILAR TO ADMIN) ===== */
    .nav-pills {
        background-color: #f8fafc !important;
        border: 1px solid #e2e8f0 !important;
    }
    .nav-pills .nav-link {
        color: #64748b !important;
        font-size: 0.9rem;
        transition: all 0.25s ease;
        background: transparent !important;
        border: none !important;
    }
    .nav-pills .nav-link.active {
        background-color: #fff !important;
        color: #7c3aed !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06) !important;
    }
    .nav-pills .nav-link:hover:not(.active) {
        color: #1e293b !important;
    }

    .ls-1 { letter-spacing: 0.5px; }
    .table > :not(caption) > * > * { padding: 16px 12px; }
    .bg-success-subtle { background-color: #dcfce7 !important; color: #15803d !important; }
    .bg-warning-subtle { background-color: #fef3c7 !important; color: #92400e !important; }
    .bg-danger-subtle  { background-color: #fee2e2 !important; color: #b91c1c !important; }
    .bg-primary-subtle { background-color: #dbeafe !important; color: #1e40af !important; }
    
    .table tbody tr { transition: 0.2s; }
    .table tbody tr:hover { background-color: rgba(124, 58, 237, 0.02); }
    
    .bg-vendor {
        background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%) !important;
        border: none !important;
    }

    /* ===== STAT CARDS ===== */
    .stat-card {
        border-radius: 14px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        cursor: default;
        overflow: hidden;
        position: relative;
    }
    .stat-card::after {
        content: '';
        position: absolute;
        top: -20px;
        right: -20px;
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: rgba(255,255,255,0.12);
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
    }
    .stat-card--purple {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }
    .stat-card--blue {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        box-shadow: 0 4px 15px rgba(79, 172, 254, 0.3);
    }
    .stat-card--green {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        box-shadow: 0 4px 15px rgba(67, 233, 123, 0.3);
    }
    .stat-card--orange {
        background: linear-gradient(135deg, #fa7c58 0%, #fb9d3e 100%);
        box-shadow: 0 4px 15px rgba(250, 124, 88, 0.3);
    }
    .stat-card--red {
        background: linear-gradient(135deg, #ff0844 0%, #ffb199 100%);
        box-shadow: 0 4px 15px rgba(255, 8, 68, 0.3);
    }
    .stat-card__icon {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        background: rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        flex-shrink: 0;
    }
    .stat-card__body {
        flex-grow: 1;
        min-width: 0;
    }
    .stat-card__label {
        color: rgba(255,255,255,0.85);
        font-size: 0.8rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .stat-card__value {
        color: white;
        font-size: 1.5rem;
        font-weight: 700;
        line-height: 1.2;
        margin: 2px 0 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>

<script>
    (function() {
        if (document.referrer && document.referrer.indexOf('/vendor/bookings') !== -1) {
            var elements = document.querySelectorAll('[data-aos]');
            for (var i = 0; i < elements.length; i++) {
                elements[i].removeAttribute('data-aos');
                elements[i].removeAttribute('data-aos-delay');
            }
        }
    })();
</script>
@endsection