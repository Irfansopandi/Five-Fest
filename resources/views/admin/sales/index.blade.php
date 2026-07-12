@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-800 text-dark mb-1">Daftar Transaksi</h2>
            <p class="text-secondary mb-0">Analisis performa penjualan tiket dan statistik pendapatan.</p>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="row g-4 mb-5">
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
            <div class="stat-card stat-card--green">
                <div class="stat-card__icon">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <div class="stat-card__body">
                    <div class="stat-card__label">Total Pendapatan</div>
                    <div class="stat-card__value">Rp {{ number_format($totalSales, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
            <div class="stat-card stat-card--blue">
                <div class="stat-card__icon">
                    <i class="bi bi-ticket-perforated"></i>
                </div> 
                <div class="stat-card__body">
                    <div class="stat-card__label">Tiket Terjual</div>
                    <div class="stat-card__value">{{ number_format($totalTickets) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
            <div class="stat-card stat-card--red">
                <div class="stat-card__icon">
                    <i class="bi bi-calendar-x"></i>
                </div>
                <div class="stat-card__body">
                    <div class="stat-card__label">Pesanan Expired</div>
                    <div class="stat-card__value">{{ number_format($totalExpired ?? 0) }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content Card --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden" data-aos="fade-up" data-aos-delay="400">
        <div class="card-header bg-white border-0 p-0">
            <ul class="nav nav-pills p-1.5 bg-light m-3 rounded-pill gap-1 border" style="padding: 6px;" id="reportTabs" role="tablist">
                <li class="nav-item flex-grow-1" role="presentation">
                    <button class="nav-link active w-100 rounded-pill fw-bold py-2.5" id="sales-tab" data-bs-toggle="tab" data-bs-target="#sales" type="button" role="tab">
                        <i class="bi bi-bar-chart-line me-2"></i> Penjualan Per Event
                    </button>
                </li>
                <li class="nav-item flex-grow-1" role="presentation">
                    <button class="nav-link w-100 rounded-pill fw-bold py-2.5" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab">
                        <i class="bi bi-clock-history me-2"></i> Riwayat Transaksi
                    </button>
                </li>
                <li class="nav-item flex-grow-1" role="presentation">
                    <button class="nav-link w-100 rounded-pill fw-bold py-2.5" id="expired-tab" data-bs-toggle="tab" data-bs-target="#expired" type="button" role="tab">
                        <i class="bi bi-calendar-x me-2"></i> Pesanan Expired
                    </button>
                </li>
            </ul>
        </div>

        <div class="card-body p-0">
            <div class="tab-content" id="reportTabsContent">
                {{-- Sales Per Event --}}
                <div class="tab-pane fade show active p-4" id="sales" role="tabpanel">

                    {{-- Search & Filter --}}
                    <form action="{{ route('admin.sales') }}" method="GET" class="filter-bar mb-4">
                        <input type="hidden" name="per_page" value="{{ $perPage ?? 5 }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="filter-label">Nama Event</label>
                                <div class="search-input-wrap">
                                    <i class="bi bi-search"></i>
                                    <input type="text" name="search_event" class="form-control" placeholder="Cari nama event..." value="{{ $searchEvent ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="filter-label">Vendor</label>
                                <input type="text" name="search_vendor" class="form-control" placeholder="Cari nama vendor..." value="{{ $searchVendor ?? '' }}">
                            </div>
                            <div class="col-md-3">
                                <label class="filter-label">Kategori</label>
                                <select name="search_category" class="form-select">
                                    <option value="">Semua Kategori</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat }}" {{ ($searchCategory ?? '') == $cat ? 'selected' : '' }}>
                                            {{ $cat }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 d-flex gap-2">
                                <button type="submit" class="btn btn-filter-apply w-100">Terapkan</button>
                                <a href="{{ route('admin.sales') }}#sales" class="btn btn-filter-reset">Reset</a>
                            </div>
                        </div>
                    </form>
                   <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr class="bg-light">
                                    <th class="border-0 ps-4 py-3 text-secondary small fw-700 text-uppercase">Nama Event</th>
                                    <th class="border-0 py-3 text-secondary small fw-700 text-uppercase">Vendor</th>
                                    <th class="border-0 py-3 text-secondary small fw-700 text-uppercase">Kategori</th>
                                    <th class="border-0 py-3 text-secondary small fw-700 text-uppercase text-end pe-4">Total Pendapatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($salesByEvent as $sale)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-800 text-dark">{{ $sale->event->title }}</div>
                                    </td>
                                    <td>
                                    <div class="fw-700 text-dark">{{ $sale->event->vendor->name ?? '-' }}</div>
                                        <small class="text-secondary">{{ $sale->event->vendor->email ?? '' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 fw-700">{{ $sale->event->category }}</span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="fw-800 text-success">Rp {{ number_format($sale->total, 0, ',', '.') }}</div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-secondary">Belum ada data penjualan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 px-4 pb-4">
                        {{ $salesByEvent->links() }}
                    </div>
                </div>

                {{-- History Transaksi --}}
                <div class="tab-pane fade p-4" id="history" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr class="bg-light">
                                    <th class="border-0 ps-4 py-3 text-secondary small fw-700 text-uppercase">Booking Code</th>
                                    <th class="border-0 py-3 text-secondary small fw-700 text-uppercase">Customer</th>
                                    <th class="border-0 py-3 text-secondary small fw-700 text-uppercase">Event</th>
                                    <th class="border-0 py-3 text-secondary small fw-700 text-uppercase">Merchandise</th>
                                    <th class="border-0 py-3 text-secondary small fw-700 text-uppercase">Qty</th>
                                    <th class="border-0 py-3 text-secondary small fw-700 text-uppercase text-end pe-4">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bookings as $booking)
                                <tr>
                                    <td class="ps-4">
                                        <code class="fw-700 text-primary">{{ $booking->booking_code }}</code>
                                    </td>
                                    <td>
                                        <div class="fw-700 text-dark">{{ $booking->user->name }}</div>
                                        <small class="text-secondary">{{ $booking->created_at->format('d M Y') }}</small>
                                    </td>
                                    <td><div class="small fw-600">{{ Str::limit($booking->event->title, 30) }}</div></td>
                                    <td>
                                        @forelse($booking->merchandises as $merch)
                                            <div class="small">{{ $merch->name }} <span class="text-secondary">x{{ $merch->pivot->quantity }}</span></div>
                                        @empty
                                            <span class="text-secondary small">-</span>
                                        @endforelse
                                    </td>
                                    <td><span class="badge bg-light text-dark rounded-pill fw-700">{{ $booking->quantity }}</span></td>
                                    <td class="text-end pe-4 fw-800 text-dark">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-secondary">Tidak ada riwayat transaksi.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 px-4 pb-4">
                        {{ $bookings->links() }}
                    </div>
                </div>

                {{-- Expired Payments --}}
                <div class="tab-pane fade p-4" id="expired" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr class="bg-light">
                                    <th class="border-0 ps-4 py-3 text-secondary small fw-700 text-uppercase">Booking</th>
                                    <th class="border-0 py-3 text-secondary small fw-700 text-uppercase">Customer</th>
                                    <th class="border-0 py-3 text-secondary small fw-700 text-uppercase">Event</th>
                                    <th class="border-0 py-3 text-secondary small fw-700 text-uppercase">Merchandise</th>
                                    <th class="border-0 py-3 text-secondary small fw-700 text-uppercase text-end pe-4">Total Tagihan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($expiredBookingsList as $booking)
                                <tr>
                                    <td class="ps-4">
                                        <code class="fw-700 text-danger">{{ $booking->booking_code }}</code>
                                    </td>
                                    <td>
                                        <div class="fw-700 text-dark">{{ $booking->user->name ?? 'Guest' }}</div>
                                        <small class="text-secondary">{{ $booking->user->email ?? '-' }}</small>
                                    </td>
                                    <td>
                                        <div class="small fw-600">{{ Str::limit($booking->event->title ?? '-', 30) }}</div>
                                    </td>
                                    <td>
                                        @forelse($booking->merchandises as $merch)
                                            <div class="small">{{ $merch->name }} <span class="text-secondary">x{{ $merch->pivot->quantity }}</span></div>
                                        @empty
                                            <span class="text-secondary small">-</span>
                                        @endforelse
                                    </td>
                                    <td class="text-end pe-4 fw-800 text-dark">Rp {{ number_format($booking->total_price ?? 0, 0, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-secondary">Tidak ada data pesanan expired.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 px-4 pb-4">
                        {{ $expiredBookingsList->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .fw-800 { font-weight: 800; }
    .fw-700 { font-weight: 700; }
    .fw-600 { font-weight: 600; }
    /* ===== TAB STYLING ===== */
    #reportTabs {
        background-color: #f8fafc !important;
        border: 1px solid #e2e8f0 !important;
    }
    #reportTabs .nav-link {
        color: #64748b !important;
        font-size: 0.9rem;
        transition: all 0.25s ease;
        background: transparent !important;
        border: none !important;
    }
    #reportTabs .nav-link.active {
        background-color: #fff !important;
        color: #7c3aed !important; /* Admin purple brand color */
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06) !important;
    }
    #reportTabs .nav-link:hover:not(.active) {
        color: #1e293b !important;
    }

    /* ===== FILTER BAR ===== */
    .filter-bar {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 1.25rem 1.5rem;
    }
    .filter-label {
        display: block;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #64748b;
        margin-bottom: 0.4rem;
    }
    .search-input-wrap {
        position: relative;
    }
    .search-input-wrap i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 0.9rem;
        pointer-events: none;
        z-index: 2;
    }
    .filter-bar .search-input-wrap input.form-control {
        padding-left: 40px !important;
    }
    .filter-bar .form-control,
    .filter-bar .form-select {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.55rem 0.9rem;
        font-size: 0.9rem;
        background-color: #fff;
    }
    .filter-bar .form-control:focus,
    .filter-bar .form-select:focus {
        border-color: #7c3aed;
        box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
    }
    .btn-filter-apply {
        background-color: #7c3aed;
        color: #fff;
        border: none;
        border-radius: 10px;
        font-weight: 700;
        padding: 0.55rem 1rem;
        font-size: 0.9rem;
    }
    .btn-filter-apply:hover {
        background-color: #6d28d9;
        color: #fff;
    }
    .btn-filter-reset {
        background-color: #fff;
        color: #475569;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        font-weight: 700;
        padding: 0.55rem 1rem;
        font-size: 0.9rem;
        white-space: nowrap;
    }
    .btn-filter-reset:hover {
        background-color: #f1f5f9;
        color: #1e293b;
    }
</style>

@push('scripts')
<script src="{{ asset('js/sales.js')}}"></script>
@endpush
@endsection