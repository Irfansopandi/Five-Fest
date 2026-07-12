@extends('v_vendor.v_layouts.app')

@section('title', 'Detail Laporan Penjualan')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-down">
        <div>
            <a href="{{ route('vendor.laporan') }}" class="btn btn-sm btn-light border shadow-sm rounded-pill px-3 mb-2 fw-medium text-muted hover-primary">
                <i class="bi bi-arrow-left me-1"></i> Laporan penjualan
            </a>
            <p class="text-muted small mb-1">Detail event</p>
            <h3 class="fw-bold mb-1" style="color: #1e1b4b;">{{ $event->title }}</h3>
            <div class="d-flex align-items-center gap-3 text-muted small mt-2">
                <span><i class="bi bi-calendar-event me-1"></i> {{ $event->date->format('d M Y') }}</span>
                <span><i class="bi bi-geo-alt me-1"></i> {{ $event->venue }}</span>
                <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1">Selesai</span>
            </div>
        </div>
        <a href="{{ route('vendor.laporan.detail.export', $event->id) }}" class="btn btn-danger rounded-pill px-4 py-2 shadow-sm fw-bold">
            <i class="bi bi-file-earmark-pdf-fill me-2"></i>Cetak PDF
        </a>
    </div>

    <!-- 4 Stat Cards -->
    <div class="row g-4 mb-4">
        <!-- Tiket Terjual -->
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="50">
            <div class="stat-card stat-card--blue">
                <div class="stat-card__icon">
                    <i class="bi bi-ticket-perforated"></i>
                </div>
                <div class="stat-card__body">
                    <div class="stat-card__label">Tiket Terjual</div>
                    <div class="stat-card__value">{{ number_format($ticketsSold, 0, ',', '.') }}</div>
                    <span class="stat-card__link">dari {{ number_format($totalCapacity, 0, ',', '.') }} kapasitas</span>
                </div>
            </div>
        </div>

        <!-- Total Pendapatan -->
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
            <div class="stat-card stat-card--green">
                <div class="stat-card__icon">
                    <i class="bi bi-wallet2"></i>
                </div>
                <div class="stat-card__body">
                    <div class="stat-card__label">Total Pendapatan</div>
                    <div class="stat-card__value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                    <span class="stat-card__link">setelah potongan 13%</span>
                </div>
            </div>
        </div>

        <!-- Potongan Platform -->
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="150">
            <div class="stat-card stat-card--orange">
                <div class="stat-card__icon">
                    <i class="bi bi-graph-down-arrow"></i>
                </div>
                <div class="stat-card__body">
                    <div class="stat-card__label">Potongan Platform</div>
                    <div class="stat-card__value">Rp {{ number_format($platformFee, 0, ',', '.') }}</div>
                    <span class="stat-card__link">13% dari transaksi</span>
                </div>
            </div>
        </div>

        <!-- Tingkat Pengisian -->
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="200">
            <div class="stat-card stat-card--purple">
                <div class="stat-card__icon">
                    <i class="bi bi-people"></i>
                </div>
                <div class="stat-card__body">
                    <div class="stat-card__label">Tingkat Pengisian</div>
                    <div class="stat-card__value">{{ $fillRate }}%</div>
                    <span class="stat-card__link">{{ number_format($ticketsSold, 0, ',', '.') }} dari {{ number_format($totalCapacity, 0, ',', '.') }} kursi</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Breakdown per jenis tiket -->
        <div class="col-lg-6" data-aos="fade-up" data-aos-delay="250">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 p-4 pb-0">
                    <h5 class="fw-bold mb-0"><i class="bi bi-tags text-primary me-2"></i>Breakdown per jenis tiket</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive mt-3">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted small">
                                <tr>
                                    <th class="ps-4 py-3 fw-bold">Jenis tiket</th>
                                    <th class="py-3 fw-bold">Harga</th>
                                    <th class="py-3 fw-bold text-center">Terjual</th>
                                    <th class="pe-4 py-3 fw-bold text-end">Pendapatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ticketBreakdown as $cat)
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="fw-bold text-dark">{{ $cat['name'] }}</div>
                                        <div class="text-muted" style="font-size: 0.75rem;">Slot: {{ $cat['slot'] }}</div>
                                    </td>
                                    <td class="py-3 fw-bold text-dark small">Rp {{ number_format($cat['price'], 0, ',', '.') }}</td>
                                    <td class="py-3 text-center fw-bold {{ $cat['sold'] > 0 ? 'text-success' : 'text-muted' }}">{{ number_format($cat['sold'], 0, ',', '.') }}</td>
                                    <td class="pe-4 py-3 text-end fw-bold {{ $cat['revenue'] > 0 ? 'text-success' : 'text-muted' }} small">Rp {{ number_format($cat['revenue'], 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Breakdown Penjualan Merchandise -->
        <div class="col-lg-6" data-aos="fade-up" data-aos-delay="300">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 p-4 pb-0">
                    <h5 class="fw-bold mb-0"><i class="bi bi-bag text-primary me-2"></i>Breakdown penjualan merchandise</h5>
                </div>
                <div class="card-body p-0">
                    @if(count($merchandiseBreakdown) > 0)
                    <div class="table-responsive mt-3">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted small">
                                <tr>
                                    <th class="ps-4 py-3 fw-bold">Nama merchandise</th>
                                    <th class="py-3 fw-bold text-center">Qty terjual</th>
                                    <th class="pe-4 py-3 fw-bold text-end">Pendapatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($merchandiseBreakdown as $merch)
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="fw-bold text-dark">{{ $merch['name'] }}</div>
                                    </td>
                                    <td class="py-3 text-center fw-bold {{ $merch['qty'] > 0 ? 'text-success' : 'text-muted' }}">
                                        {{ number_format($merch['qty'], 0, ',', '.') }}
                                    </td>
                                    <td class="pe-4 py-3 text-end fw-bold {{ $merch['revenue'] > 0 ? 'text-success' : 'text-muted' }} small">
                                        Rp {{ number_format($merch['revenue'], 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td class="ps-4 py-3 fw-bold text-dark small">Total</td>
                                    <td class="py-3 text-center fw-bold text-dark">
                                        {{ number_format(array_sum(array_column($merchandiseBreakdown, 'qty')), 0, ',', '.') }}
                                    </td>
                                    <td class="pe-4 py-3 text-end fw-bold text-dark small">
                                        Rp {{ number_format(array_sum(array_column($merchandiseBreakdown, 'revenue')), 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    @else
                    <div class="d-flex flex-column align-items-center justify-content-center h-100 py-5 text-muted">
                        <div class="bg-light rounded-circle d-inline-flex p-4 mb-3">
                            <i class="bi bi-bag-x fs-2"></i>
                        </div>
                        <p class="mb-0 small">Belum ada penjualan merchandise untuk event ini.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Pembeli -->
    <div class="card border-0 shadow-sm rounded-4 mb-4" data-aos="fade-up" data-aos-delay="350">
        <div class="card-header bg-white border-0 p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h5 class="fw-bold mb-0"><i class="bi bi-people text-primary me-2"></i>Daftar pembeli</h5>
            <div class="d-flex gap-2">
                <form action="{{ url()->current() }}" method="GET" class="d-flex gap-2">
                    <select name="per_page" class="form-select form-select-sm rounded-pill border shadow-sm" style="width: auto;" onchange="this.form.submit()">
                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 data</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 data</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 data</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 data</option>
                    </select>
                    <div class="input-group input-group-sm rounded-pill overflow-hidden border shadow-sm" style="width: 250px;">
                        <span class="input-group-text bg-white border-0 pe-1"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-0 shadow-none" value="{{ request('search') }}" placeholder="Cari nama / email / kode...">
                        @if(request('search'))
                            <a href="{{ route('vendor.laporan.detail', $event->id) }}" class="input-group-text bg-white border-0 text-decoration-none"><i class="bi bi-x-circle-fill text-muted"></i></a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="buyerTable">
                    <thead class="bg-light text-muted small">
                        <tr>
                            <th class="ps-4 py-3 fw-bold">Nama pembeli</th>
                            <th class="py-3 fw-bold">Jenis tiket</th>
                            <th class="py-3 fw-bold">Merchandise</th>
                            <th class="py-3 fw-bold">Tgl beli</th>
                            <th class="py-3 fw-bold text-center">Status</th>
                            <th class="pe-4 py-3 fw-bold text-end">Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($buyers as $buyer)
                        <tr class="buyer-row">
                            <td class="ps-4 py-3">
                                <div class="fw-bold text-dark buyer-name">{{ $buyer->user->name ?? $buyer->guest_name }}</div>
                                <div class="text-muted small buyer-email" style="font-size: 0.75rem;">{{ $buyer->user->email ?? $buyer->guest_email }}</div>
                            </td>
                            <td class="py-3">
                                <span class="badge bg-secondary rounded-pill px-3">{{ $buyer->ticket_category->name ?? '-' }}</span>
                            </td>
                            <td class="py-3">
                                @if($buyer->merchandises && $buyer->merchandises->count() > 0)
                                    @foreach($buyer->merchandises as $merch)
                                        <div class="small text-dark fw-medium">
                                            {{ $merch->name }}
                                            <span class="text-muted">×{{ $merch->pivot->quantity }}</span>
                                        </div>
                                    @endforeach
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td class="py-3 text-muted small fw-medium">
                                {{ $buyer->created_at->format('d M Y') }}
                            </td>
                            <td class="py-3 text-center">
                                @if($buyer->payment_status === 'paid')
                                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1 fw-bold">Lunas</span>
                                @elseif($buyer->payment_status === 'pending')
                                    <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-1 fw-bold">Pending</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-1 fw-bold">{{ ucfirst($buyer->payment_status) }}</span>
                                @endif
                            </td>
                            <td class="pe-4 py-3 text-end fw-bold text-success small">
                                Rp {{ number_format($buyer->total_price / 1.13, 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <div class="bg-light rounded-circle d-inline-flex p-4 mb-3">
                                    <i class="bi bi-inbox fs-1"></i>
                                </div>
                                <p class="mb-0">Belum ada pembeli tiket untuk event ini.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($buyers->hasPages())
            <div class="d-flex justify-content-center p-4 border-top">
                {{ $buyers->links('pagination::bootstrap-5') }}
            </div>
            @endif
        </div>
    </div>
</div>

<style>
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
        height: 100%;
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

    .stat-card__link {
        color: rgba(255,255,255,0.8);
        font-size: 0.78rem;
        text-decoration: none;
    }

    .bg-primary-subtle { background-color: #f3f0ff !important; color: #7c3aed !important; }
    .bg-success-subtle { background-color: #ecfdf5 !important; color: #059669 !important; }
    .bg-warning-subtle { background-color: #fffbeb !important; color: #d97706 !important; }
    .bg-danger-subtle { background-color: #fef2f2 !important; color: #dc2626 !important; }
    .bg-info-subtle { background-color: #eff6ff !important; color: #2563eb !important; }
    
    .text-primary { color: #7c3aed !important; }
    
    .hover-primary:hover {
        color: #7c3aed !important;
        background-color: #f8f9fa !important;
        border-color: #7c3aed !important;
    }
</style>
@endsection