@extends('v_vendor.v_layouts.app')

@section('title', 'Dashboard Vendor')

@section('content')
<div class="container-fluid px-4 py-3">

    {{-- HEADER --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: #2d2d2d;">
                👋 Selamat datang, {{ Auth()->user()->name }}!
            </h4>
            <p class="text-muted mb-0 small">
                <i class="bi bi-calendar3 me-1"></i>{{ now()->translatedFormat('l, d F Y') }}
            </p>
        </div>
        {{-- <a href="{{ route('vendor.events.create') }}" class="btn btn-vendor">
            <i class="bi bi-plus-circle me-2"></i>Buat Event
        </a> --}}
    </div>

    {{-- STAT CARDS --}}
    <div class="row g-4 mb-5">

        {{-- Total Event --}}
        <div class="col-12 col-sm-6 col-xl-3" data-aos="fade-up" data-aos-delay="100">
            <div class="stat-card stat-card--purple">
                <div class="stat-card__icon">
                    <i class="bi bi-calendar-event-fill"></i>
                </div>
                <div class="stat-card__body">
                    <div class="stat-card__label">Total Event</div>
                    <div class="stat-card__value">{{ $totalEvents }}</div>
                    <a href="{{ route('vendor.events.index') }}" class="stat-card__link">
                        Lihat semua <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Total Booking --}}
        <div class="col-12 col-sm-6 col-xl-3" data-aos="fade-up" data-aos-delay="200">
            <div class="stat-card stat-card--blue">
                <div class="stat-card__icon">
                    <i class="bi bi-ticket-perforated-fill"></i>
                </div>
                <div class="stat-card__body">
                    <div class="stat-card__label">Total Booking</div>
                    <div class="stat-card__value">{{ $totalBookings }}</div>
                    <a href="{{ route('vendor.bookings.index') }}" class="stat-card__link">
                        Lihat semua <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Total Revenue --}}
        <div class="col-12 col-sm-6 col-xl-3" data-aos="fade-up" data-aos-delay="300">
            <div class="stat-card stat-card--green">
                <div class="stat-card__icon">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <div class="stat-card__body">
                    <div class="stat-card__label">Total Pendapatan</div>
                    <div class="stat-card__value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                    <a href="{{ route('vendor.laporan') }}" class="stat-card__link">
                        Lihat laporan <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Pengguna Tiket --}}
        <div class="col-12 col-sm-6 col-xl-3" data-aos="fade-up" data-aos-delay="400">
            <div class="stat-card stat-card--orange">
                <div class="stat-card__icon">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="stat-card__body">
                    <div class="stat-card__label">Pengguna Tiket</div>
                    <div class="stat-card__value">{{ $totalBookings }}</div>
                    <a href="{{ route('vendor.pengguna-tiket') }}" class="stat-card__link">
                        Lihat semua <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

    </div>

    {{-- chart --}}
    <div class="row g-4 mb-4">
        {{-- Bar Chart : tiket terjual per event --}}
        <div class="col-12 col-lg-7" data-aos="fade-up" data-aos-delay="450">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-4 pb-2 px-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="fw-bold mb-0" style="color: #1e1b4b;">
                             <i class="bi bi-ticket-perforated-fill me-2" style="color:#7c3aed;"></i>Tiket Terjual per Event
                        </h6>
                        <p class="text-muted small mb-0">Berdasarkan booking yang sudah dibayar</p>
                    </div>
                </div>
                <div class="card-body px-4 pb-4">
                    @if($chartData['labels']->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-bar-chart fs-5 opacity-25"></i>
                            <p class="mb-0 mt-2">Belum ada data untuk ditampilkan.</p>
                        </div>
                    @else
                        <canvas id="ticketChart" height="250"></canvas>
                    @endif
                </div>
            </div>
        </div>

        {{-- Doughnut Chart: Proporsi Pendapatan --}}
        <div class="col-12 col-lg-5" data-aos="fade-up" data-aos-delay="500">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-4 pb-2 px-4">
                    <h6 class="fw-bold mb-0" style="color:#1e1b4b;">
                        <i class="bi bi-pie-chart-fill me-2" style="color:#7c3aed"></i>Proporsi Pendapatan
                    </h6>
                    <p class="text-muted small mb-0">Distribusi revenue per event</p>
                </div>
                <div class="card-body px-4 pb-4 d-flex align-items-center justify-content-center">
                    @if($chartData['labels']->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-pie-chart fs-1 opacity-25"></i>
                            <p class="mb-0 mt-2">Belum ada data untuk ditampilkan.</p>
                        </div>
                    @else
                        <canvas id="revenueChart" height="250"></canvas>
                    @endif
                </div>
            </div>
        </div>
    </div>



    {{-- RECENT BOOKINGS TABLE --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden" data-aos="fade-up" data-aos-delay="500">
        <div class="card-header bg-white border-0 pt-4 pb-2 px-4 d-flex align-items-center justify-content-between">
            <div>
                <h6 class="fw-bold mb-0" style="color: #1e1b4b;">
                    <i class="bi bi-clock-history me-2"style="color: #7c3aed"></i>Booking Terbaru
                </h6>
                <p class="text-muted small mb-0">5 booking terakhir dari event Anda</p>
            </div>
            <a href="{{ route('vendor.bookings.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                Lihat Semua
            </a>
        </div>
        <div class="card-body px-0 pb-0">
            @if($recentBookings->isEmpty())
                <div class="text-center py-5">
                    <div class="bg-light rounded-circle d-inline-flex p-4 mb-3">
                        <i class="bi bi-inbox fs-1 text-muted"></i>
                    </div>
                    <p class="text-muted mb-0">Belum ada booking masuk.</p>
                    <a href="{{ route('vendor.events.create') }}" class="btn btn-vendor mt-3">
                        <i class="bi bi-plus-circle me-1"></i> Buat Event Pertama
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 text-muted small fw-bold text-uppercase ls-1 py-3">Pemesan</th>
                                <th class="text-muted small fw-bold text-uppercase ls-1 py-3">Event</th>
                                <th class="text-muted small fw-bold text-uppercase ls-1 py-3">Tanggal</th>
                                <th class="text-muted small fw-bold text-uppercase ls-1 py-3">Total</th>
                                <th class="text-muted small fw-bold text-uppercase ls-1 py-3 pe-4 text-end">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentBookings as $booking)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar-sm bg-purple-deep text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 38px; height: 38px;">
                                            {{ strtoupper(substr($booking->user->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark small">{{ $booking->user->name ?? '-' }}</div>
                                            <div class="text-muted" style="font-size: 0.75rem;">{{ $booking->user->email ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-semibold text-dark small">{{ $booking->event->title ?? '-' }}</span>
                                </td>
                                <td class="text-muted small">
                                    {{ $booking->created_at->format('d M Y') }}
                                </td>
                                <td class="fw-bold text-dark small">
                                    Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                </td>
                                <td class="pe-4 text-end">
                                    @php
                                        $status = $booking->booking_status ?? $booking->status ?? 'pending';
                                        $badge = match($status) {
                                            'confirmed' => ['bg' => 'success', 'label' => 'PAID'],
                                            'cancelled' => ['bg' => 'danger', 'label' => 'Cancelled'],
                                            default     => ['bg' => 'warning', 'label' => 'Pending'],
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $badge['bg'] }}-subtle text-{{ $badge['bg'] }} rounded-pill px-3 py-2 fw-medium" style="font-size: 0.7rem;">
                                        {{ $badge['label'] }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
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
        transition: color 0.2s;
    }

    .stat-card__link:hover {
        color: white;
    }

    /* ===== BUTTONS ===== */
    .btn-vendor {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 8px 18px;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s;
    }

    .btn-vendor:hover {
        color: white;
        opacity: 0.9;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .btn-outline-vendor {
        border: 1.5px solid #667eea;
        color: #667eea;
        border-radius: 8px;
        padding: 6px 14px;
        font-size: 0.8rem;
        font-weight: 500;
        background: transparent;
        transition: all 0.2s;
    }

    .btn-outline-vendor:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-color: transparent;
    }

    /* ===== AVATAR ===== */
    .avatar-circle {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-size: 0.8rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    /* ===== TABLE ===== */
    .table > :not(caption) > * > * {
        padding: 12px 10px;
    }

    /* ===== BADGE ===== */
    .bg-success-subtle { background-color: #d1fae5 !important; }
    .bg-danger-subtle  { background-color: #fee2e2 !important; }
    .bg-warning-subtle { background-color: #fef3c7 !important; }

</style>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const chartLabels = @json($chartData['labels']);
    const chartTickets = @json($chartData['tickets']);
    const chartRevenue = @json($chartData['revenue']);

    const colors = [
        'rgba(102,126,234,0.85)', 'rgba(118,75,162,0.85)',
        'rgba(79,172,254,0.85)',  'rgba(67,233,123,0.85)',
        'rgba(250,124,88,0.85)',  'rgba(251,157,62,0.85)',
        'rgba(244,63,94,0.85)',   'rgba(16,185,129,0.85)',
    ];

    document.addEventListener('DOMContentLoaded', function() {

        // Bar Chart: Tiket Terjual 
        const ticketCtx = document.getElementById('ticketChart');
        if (ticketCtx) {
            const gradient = ticketCtx.getContext('2d').createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(102,126,234,0.9)');
            gradient.addColorStop(1, 'rgba(118,75,162,0.7)');

            new Chart(ticketCtx, {
                type: 'bar',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'Tiket Terjual',
                        data: chartTickets,
                        backgroundColor: gradient,
                        borderRadius: 8,
                        borderSkipped: false,
                        barPercentage: 0.6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'white',
                            titleColor: '#1e1b4b',
                            bodyColor: '#667eea',
                            borderColor: '#e2e8f0',
                            borderWidth: 1,
                            padding: 12,
                            callbacks: {
                                label: ctx => ` ${ctx.raw} tiket terjual`
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: {
                                maxRotation: 30,
                                font: { size: 11 },
                                callback: function(val) {
                                    const label = this.getLabelForValue(val);
                                    return label.length > 15 ? label.substring(0, 15) + '...' : label;
                                }
                             }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f1f5f9' },
                            ticks: { 
                                font: { size: 11 },
                                stepSize: 1,
                            }
                        }
                    }
                }
            });
        }

        // Doughnut Chart: revenue 
        const revenueCtx = document.getElementById('revenueChart');
        if (revenueCtx) {
            new Chart(revenueCtx, {
                type: 'doughnut',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        data: chartRevenue,
                        backgroundColor: colors.slice(0, chartRevenue.length),
                        borderWidth: 3,
                        borderColor: '#ffffff',
                        hoverOffset: 8,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 16,
                                font: { size: 11 },
                                usePointStyle: true,
                                boxlWidth: 8,
                                // Truncate label panjang
                                generateLabels: function(chart) {
                                    const data = chart.data;
                                    return data.labels.map((label, i) => ({
                                        text: label.length > 20 ? label.substring(0, 20) + '...' : label,
                                        fillStyle: data.datasets[0].backgroundColor[i],
                                        strokeStyle: '#fff',
                                        lineWidth: 2,
                                        hidden: false,
                                        index: i
                                    }));
                                }

                            }
                        },
                        tooltip: {
                            backgroundColor: 'white',
                            titleColor: '#1e1b4b',
                            bodyColor: '#667eea',
                            borderColor: '#e2e8f0',
                            borderWidth: 1,
                            padding: 12,
                            callbacks: {
                                label: ctx => ` Rp ${new Intl.NumberFormat('id-ID').format(ctx.raw)}`
                            }
                        }
                    }
                }
            });
        }
    });

</script>
@endpush
@endsection