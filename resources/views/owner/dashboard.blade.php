@extends('owner.layouts.app')

@section('title', 'Dashboard Owner')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-800 mb-0" style="color:#1e1b4b;">Dashboard Owner</h4>
        <p class="text-muted small mb-0">Selamat datang kembali, {{ auth()->user()->name }} 👋</p>
    </div>
    <div class="text-muted small">
        <i class="bi bi-calendar3 me-1"></i> {{ now()->translatedFormat('l, d F Y') }}
    </div>
</div>

{{-- ===== STAT CARDS ===== --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-4" data-aos="fade-up" data-aos-delay="0">
        <div class="stat-card stat-card--purple">
            <div class="stat-card__icon"><i class="bi bi-shop"></i></div>
            <div class="stat-card__body">
                <div class="stat-card__label">Total Vendor</div>
                <div class="stat-card__value">{{ $totalVendor }}</div>
                <span class="stat-card__link">Vendor terdaftar</span>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-4" data-aos="fade-up" data-aos-delay="50">
        <div class="stat-card stat-card--blue">
            <div class="stat-card__icon"><i class="bi bi-bag-heart"></i></div>
            <div class="stat-card__body">
                <div class="stat-card__label">Total Tenant</div>
                <div class="stat-card__value">{{ $totalTenant }}</div>
                <span class="stat-card__link">Tenant UMKM aktif</span>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-4" data-aos="fade-up" data-aos-delay="100">
        <div class="stat-card stat-card--green">
            <div class="stat-card__icon"><i class="bi bi-people"></i></div>
            <div class="stat-card__body">
                <div class="stat-card__label">Total Pengguna</div>
                <div class="stat-card__value">{{ $totalUser }}</div>
                <span class="stat-card__link">Pengguna terdaftar</span>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-4" data-aos="fade-up" data-aos-delay="150">
        <div class="stat-card stat-card--orange">
            <div class="stat-card__icon"><i class="bi bi-calendar-event"></i></div>
            <div class="stat-card__body">
                <div class="stat-card__label">Total Event</div>
                <div class="stat-card__value">{{ $totalEvent }}</div>
                <span class="stat-card__link">Event tersedia</span>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-4" data-aos="fade-up" data-aos-delay="200">
        <div class="stat-card stat-card--purple">
            <div class="stat-card__icon"><i class="bi bi-ticket-perforated"></i></div>
            <div class="stat-card__body">
                <div class="stat-card__label">Total Booking</div>
                <div class="stat-card__value">{{ $totalBooking }}</div>
                <span class="stat-card__link">Tiket terjual</span>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-4" data-aos="fade-up" data-aos-delay="250">
        <div class="stat-card stat-card--red">
            <div class="stat-card__icon"><i class="bi bi-file-earmark-text"></i></div>
            <div class="stat-card__body">
                <div class="stat-card__label">Laporan Belum Dibaca</div>
                <div class="stat-card__value">{{ $unreadReport }}</div>
                <a href="{{ route('owner.reports') }}" class="stat-card__link">Lihat laporan →</a>
            </div>
        </div>
    </div>
</div>

{{-- ===== LAPORAN TERBARU ===== --}}
<div class="card border-0 shadow-sm rounded-4" data-aos="fade-up" data-aos-delay="300">
    <div class="card-header bg-white border-0 rounded-top-4 d-flex align-items-center justify-content-between py-3 px-4">
        <div>
            <h6 class="fw-700 mb-0" style="color:#1e1b4b;">
                <i class="bi bi-file-earmark-text me-2 text-indigo" style="color:#4f46e5;"></i>
                Laporan Terbaru dari Admin
            </h6>
        </div>
        <a href="{{ route('owner.reports') }}" class="btn btn-sm rounded-3 fw-600" style="background:#4f46e5;color:white;font-size:0.8rem;">
            Lihat Semua
        </a>
    </div>
    <div class="card-body p-0">
        @if($latestReports->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-inbox fs-1 text-muted opacity-50"></i>
                <p class="text-muted mt-2 small">Belum ada laporan masuk</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size:0.875rem;">
                    <thead style="background:#f8f9fa;">
                        <tr>
                            <th class="px-4 py-3 fw-600 text-muted border-0" style="font-size:0.75rem;letter-spacing:0.5px;">JUDUL LAPORAN</th>
                            <th class="py-3 fw-600 text-muted border-0" style="font-size:0.75rem;letter-spacing:0.5px;">TANGGAL</th>
                            <th class="py-3 fw-600 text-muted border-0" style="font-size:0.75rem;letter-spacing:0.5px;">DIKIRIM OLEH</th>
                            <th class="py-3 fw-600 text-muted border-0" style="font-size:0.75rem;letter-spacing:0.5px;">STATUS</th>
                            <th class="py-3 fw-600 text-muted border-0" style="font-size:0.75rem;letter-spacing:0.5px;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($latestReports as $report)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center gap-2">
                                    @if($report->status === 'unread')
                                        <span class="unread-dot"></span>
                                    @endif
                                    <span class="fw-600" style="color:#1e1b4b;">{{ $report->title }}</span>
                                </div>
                            </td>
                            <td class="py-3 text-muted">
                                {{ $report->day }} {{ $report->month_name }} {{ $report->year }}
                            </td>
                            <td class="py-3 text-muted">{{ $report->admin->name ?? '-' }}</td>
                            <td class="py-3">
                                @if($report->status === 'unread')
                                    <span class="badge rounded-pill" style="background:#fef3c7;color:#d97706;font-size:0.72rem;font-weight:600;">
                                        <i class="bi bi-circle-fill me-1" style="font-size:0.4rem;"></i> Belum Dibaca
                                    </span>
                                @else
                                    <span class="badge rounded-pill" style="background:#d1fae5;color:#059669;font-size:0.72rem;font-weight:600;">
                                        <i class="bi bi-check-circle-fill me-1" style="font-size:0.7rem;"></i> Sudah Dibaca
                                    </span>
                                @endif
                            </td>
                            <td class="py-3">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('owner.reports.show', $report) }}"
                                       class="btn btn-sm rounded-3 fw-600"
                                       style="background:#ede9fe;color:#4f46e5;font-size:0.78rem;">
                                        <i class="bi bi-eye me-1"></i> Lihat
                                    </a>
                                    <a href="{{ route('owner.reports.download', $report) }}"
                                       class="btn btn-sm rounded-3 fw-600"
                                       style="background:#d1fae5;color:#059669;font-size:0.78rem;">
                                        <i class="bi bi-download me-1"></i> Unduh
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

@endsection