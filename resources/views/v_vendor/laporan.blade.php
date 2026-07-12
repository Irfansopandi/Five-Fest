@extends('v_vendor.v_layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="container-fluid px-4 py-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: #1e1b4b;">Laporan Penjualan</h4>
            <p class="text-muted small mb-0">Statistik penjualan tiket per event Anda</p>
        </div>
        <a href="{{ route('vendor.laporan.export') }}" class="btn btn-outline-danger rounded-pill px-4 fw-bold shadow-sm">
            <i class="bi bi-file-earmark-pdf-fill me-2"></i>Cetak PDF
        </a>
    </div>
    
    <!-- Stats Cards -->
    <div class="row g-3 mb-4" data-aos="fade-up">
        <div class="col-xl-4 col-md-6">
            <div class="stat-card stat-card--purple h-100 mb-0">
                <div class="stat-card__icon">
                    <i class="bi bi-calendar-event"></i>
                </div>
                <div class="stat-card__body">
                    <div class="stat-card__label">TOTAL EVENT</div>
                    <div class="stat-card__value">{{ $totalEventAktif }} Event</div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="stat-card stat-card--blue h-100 mb-0">
                <div class="stat-card__icon">
                    <i class="bi bi-ticket-perforated"></i>
                </div>
                <div class="stat-card__body">
                    <div class="stat-card__label">TIKET TERJUAL</div>
                    <div class="stat-card__value">{{ number_format($totalTiketTerjual) }} Tiket</div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-12">
            <div class="stat-card stat-card--green h-100 mb-0">
                <div class="stat-card__icon">
                    <i class="bi bi-wallet2"></i>
                </div>
                <div class="stat-card__body">
                    <div class="stat-card__label">PENDAPATAN BERSIH</div>
                    <div class="stat-card__value">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden" data-aos="fade-up" data-aos-delay="100">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-muted small fw-bold text-uppercase ls-1">Event</th>
                            <th class="py-3 text-muted small fw-bold text-uppercase ls-1">Tiket Terjual</th>
                            <th class="py-3 text-muted small fw-bold text-uppercase ls-1">Total Pendapatan</th>
                            <th class="pe-4 py-3 text-muted small fw-bold text-uppercase ls-1 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($eventStats as $stat)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark small">{{ $stat->title }}</div>
                                <div class="text-muted small" style="font-size: 0.7rem;">{{ $stat->date->format('d M Y') }}</div>
                            </td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 fw-medium" style="font-size: 0.7rem;">
                                    <i class="bi bi-ticket-perforated-fill me-1"></i>{{ $stat->paid_bookings_count }} Tiket
                                </span>
                            </td>
                            <td class="fw-bold text-success small">
                                Rp {{ number_format($stat->total_revenue ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="pe-4 text-end">
                                <a href="{{ route('vendor.laporan.detail', $stat->id) }}" class="btn btn-sm btn-light rounded-pill px-3 border shadow-sm small fw-medium">
                                    Detail Event <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <div class="bg-light rounded-circle d-inline-flex p-4 mb-3">
                                    <i class="bi bi-graph-down fs-1"></i>
                                </div>
                                <p class="mb-0">Belum ada data penjualan.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .ls-1 { letter-spacing: 0.5px; }
    .table > :not(caption) > * > * { padding: 18px 12px; }
    .bg-primary-subtle { background-color: #e0e7ff !important; color: #4338ca !important; }
    
    /* Hover Row Effect */
    .table tbody tr { transition: 0.2s; }
    .table tbody tr:hover { background-color: rgba(124, 58, 237, 0.02); }

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
@endsection
