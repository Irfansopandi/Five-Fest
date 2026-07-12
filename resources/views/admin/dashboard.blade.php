@extends('admin.layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container-fluid px-4 py-3">

    {{-- HEADER --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: #2d2d2d;">
                <i class="bi bi-house-door-fill me-2 text-dark"></i> Selamat datang, {{ Auth()->user()->name }}!
            </h4>
            <p class="text-muted mb-0 small">
                <i class="bi bi-calendar3 me-1"></i>{{ now()->translatedFormat('l, d F Y') }}
            </p>
        </div>
    </div>

    {{-- STAT CARDS --}}
    <div class="row g-3 mb-5">
        {{-- Total Event --}}
        <div class="col-12 col-sm-6 col-xl-3" data-aos="fade-up" data-aos-delay="100">
            <div class="stat-card stat-card--purple">
                <div class="stat-card__icon">
                    <i class="bi bi-calendar-event-fill"></i>
                </div>
                <div class="stat-card__body">
                    <div class="stat-card__label">Total Event</div>
                    <div class="stat-card__value">{{ $totalEvents }}</div>
                    <span class="stat-card__link" style="opacity: 0.5;">
                        <i class="bi bi-calendar-check"></i> Data Keseluruhan
                    </span>
                </div>
            </div>
        </div>

        {{-- Total Pengguna --}}
        <div class="col-12 col-sm-6 col-xl-3" data-aos="fade-up" data-aos-delay="200">
            <div class="stat-card stat-card--blue">
                <div class="stat-card__icon">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="stat-card__body">
                    <div class="stat-card__label">Total Pengguna</div>
                    <div class="stat-card__value">{{ $totalUsers }}</div>
                    <a href="{{ route('admin.users.index') }}" class="stat-card__link">
                        Lihat semua <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Total Pendapatan --}}
        <div class="col-12 col-sm-6 col-xl-3" data-aos="fade-up" data-aos-delay="300">
            <div class="stat-card stat-card--green">
                <div class="stat-card__icon">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <div class="stat-card__body">
                    <div class="stat-card__label">Total Pendapatan</div>
                    <div class="stat-card__value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                    <a href="{{ route('admin.sales') }}" class="stat-card__link">
                        Lihat laporan <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Vendor Pending --}}
        <div class="col-12 col-sm-6 col-xl-3" data-aos="fade-up" data-aos-delay="400">
            <div class="stat-card stat-card--orange">
                <div class="stat-card__icon">
                    <i class="bi bi-shield-exclamation"></i>
                </div>
                <div class="stat-card__body">
                    <div class="stat-card__label">Vendor Pending</div>
                    <div class="stat-card__value">{{ $pendingVendors }}</div>
                    <a href="{{ route('admin.users.index', ['role' => 'vendor', 'verification_status' => 'pending']) }}" class="stat-card__link">
                        Verifikasi sekarang <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- CHART ROW --}}
    <div class="row mb-5" data-aos="fade-up" data-aos-delay="500">
        {{-- bar chart --}}
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-4 pb-2 px-4">
                    <h6 class="fw-bold mb-0" style="color: #2d2d2d;">
                        <i class="bi bi-bar-chart-fill me-2" style='color:#7c3aed;'></i>Top 10 Vendor dengan Pendapatan Tertinggi
                    </h6>
                </div>
                <div class="card-body px-4 pb-4">
                    <canvas id="salesChart" height="80"></canvas>
                </div>
            </div>
        </div>

        {{-- Donut Chart --}}
        <div class="col-12 col-lg-4 mt-4 mt-lg-0">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-4 pb-2 px-4">
                    <h6 class="fw-bold mb-0" style="color: #2d2d2d;">
                         <i class="bi bi-ticket-perforated-fill me-2" style="color:#7c3aed;"></i>Proporsi Tiket Terjual
                    </h6>
                    <p class="text-muted small mb-0">Per vendor</p>
                </div>
                <div class="card-body px-4 pb-4 d-flex align-items-center justify-content-center">
                <canvas id="ticketDonut" height="280"></canvas>
            </div>
            </div>
        </div>
    </div>

    {{-- RECENT BOOKINGS TABLE --}}
    <div class="card border-0 shadow-sm rounded-4" data-aos="fade-up" data-aos-delay="600">
        <div class="card-header bg-white border-0 pt-4 pb-2 px-4 d-flex align-items-center justify-content-between">
            <div>
                <h6 class="fw-bold mb-0" style="color: #2d2d2d;">
                    <i class="bi bi-clock-history me-2" style="color: #7c3aed;"></i>Booking Terbaru
                </h6>
                <p class="text-muted small mb-0">5 booking terakhir dari seluruh platform</p>
            </div>
            <a href="{{ route('admin.sales') }}" class="btn btn-sm btn-outline-vendor">
                Lihat Semua
            </a>
        </div>
        <div class="card-body px-4 pb-4">
            @if($recentBookings->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted opacity-50"></i>
                    <p class="text-muted mt-3 mb-0">Belum ada booking masuk.</p>

                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr class="table-light">
                                <th class="border-0 rounded-start ps-3 text-muted small fw-semibold py-3">BOOKING CODE</th>
                                <th class="border-0 text-muted small fw-semibold py-3">CUSTOMER</th>
                                <th class="border-0 text-muted small fw-semibold py-3">EVENT NAME</th>
                                <th class="border-0 text-muted small fw-semibold py-3 text-center">QTY</th>
                                <th class="border-0 text-muted small fw-semibold py-3 text-end pe-3">AMOUNT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentBookings as $booking)
                            <tr>
                                <td class="ps-3"><span class="badge bg-light text-indigo border fw-bold px-3 py-2">{{ $booking->booking_code }}</span></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-circle">
                                            {{ strtoupper(substr($booking->user->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div class="fw-bold text-dark small">{{ $booking->user->name ?? '-' }}</div>
                                    </div>
                                </td>
                                <td class="small fw-600 text-indigo">{{ $booking->event->title ?? '-' }}</td>
                                <td class="text-center fw-bold">{{ $booking->quantity }}</td>
                                <td class="text-end pe-3 fw-bold text-indigo">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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

    .text-indigo { color: #667eea !important; }
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Chart Data
    const chartLabels = @json($chartData['labels'] ?? []);
    const chartRevenue = @json($chartData['revenue'] ?? []);
    const chartTickets = @json($chartData['tickets'] ?? []);

document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('salesChart').getContext('2d');
    
    const gradientRevenue = ctx.createLinearGradient(0, 0, 0, 400);
    gradientRevenue.addColorStop(0, 'rgba(102, 126, 234, 0.9)');
    gradientRevenue.addColorStop(1, 'rgba(118, 75, 162, 0.9)');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartLabels,
            datasets: [
                {
                    label: 'Pendapatan (Rp)',
                    data: chartRevenue,
                    backgroundColor: gradientRevenue,
                    borderRadius: 6,
                    borderSkipped: false,
                    barPercentage: 0.6,
                    yAxisID: 'y'
                },
                {
                    label: 'Tiket Terjual',
                    data: chartTickets,
                    backgroundColor: '#fb9d3e',
                    borderRadius: 6,
                    borderSkipped: false,
                    barPercentage: 0.6,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: {
                            family: "'Inter', sans-serif",
                            weight: '500'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(255, 255, 255, 0.95)',
                    titleColor: '#2d2d2d',
                    bodyColor: '#2d2d2d',
                    borderColor: '#e2e8f0',
                    borderWidth: 1,
                    padding: 12,
                    boxPadding: 6,
                    usePointStyle: true,
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.datasetIndex === 0) {
                                label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(context.raw);
                            } else {
                                label += context.raw + ' Tiket';
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: { family: "'Inter', sans-serif" }
                    }
                },
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    grid: {
                        color: '#f1f5f9',
                        drawBorder: false,
                    },
                    border: { dash: [4, 4] },
                    ticks: {
                        font: { family: "'Inter', sans-serif" },
                        callback: function(value) {
                            if (value === 0) return 'Rp 0';
                            return 'Rp ' + new Intl.NumberFormat('id-ID', { notation: "compact", compactDisplay: "short" }).format(value);
                        }
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    grid: { drawOnChartArea: false },
                    ticks: { 
                        stepSize: 1,
                        font: { family: "'Inter', sans-serif" }
                    }
                },
            }
        }
    });

    // Donut Chart: Tiket per Vendor
    const ticketDonutCtx = document.getElementById('ticketDonut');
    if (ticketDonutCtx) {
        const donutColors = [
            '#667eea','#764ba2','#fb9d3e','#43e97b',
            '#4facfe','#fa7c58','#f43f5e','#10b981',
            '#f59e0b','#6366f1'
        ];
        new Chart(ticketDonutCtx, {
            type: 'doughnut',
            data: {
                labels: chartLabels,
                datasets: [{
                    data: chartTickets,
                    backgroundColor: donutColors.slice(0, chartLabels.length),
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
                            padding: 12,
                            font: { size: 11 },
                            usePointStyle: true,
                            generateLabels: function(chart) {
                                return chart.data.labels.map((label, i) => ({
                                    text: label.length > 16 ? label.substring(0, 16) + '…' : label,
                                    fillStyle: donutColors[i],
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
                        titleColor: '#2d2d2d',
                        bodyColor: '#667eea',
                        borderColor: '#e2e8f0',
                        borderWidth: 1,
                        padding: 12,
                        callbacks: {
                            label: ctx => ` ${ctx.raw} tiket`
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