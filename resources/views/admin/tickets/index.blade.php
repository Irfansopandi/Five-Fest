@extends('admin.layouts.app')
@section('title', 'Daftar Tiket')
@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-800 text-dark mb-1">Daftar Tiket</h2>
            <p class="text-secondary mb-0">Pantau status tiket yang telah diterbitkan untuk seluruh event.</p>
        </div>
    </div>

    {{-- Stats --}}
    <div class="row g-4 mb-5">
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
            <div class="stat-card stat-card--blue">
                <div class="stat-card__icon">
                    <i class="bi bi-ticket-perforated"></i>
                </div>
                <div class="stat-card__body">
                    <div class="stat-card__label">Total Tiket</div>
                    <div class="stat-card__value">{{ $totalTickets }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="200">
            <div class="stat-card stat-card--orange">
                <div class="stat-card__icon">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div class="stat-card__body">
                    <div class="stat-card__label">Belum Digunakan</div>
                    <div class="stat-card__value">{{ $unusedTickets }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="300">
            <div class="stat-card stat-card--green">
                <div class="stat-card__icon">
                    <i class="bi bi-check2-circle"></i>
                </div>
                <div class="stat-card__body">
                    <div class="stat-card__label">Sudah Digunakan</div>
                    <div class="stat-card__value">{{ $usedTickets }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="400">
            <div class="stat-card stat-card--red">
                <div class="stat-card__icon">
                    <i class="bi bi-x-circle"></i>
                </div>
                <div class="stat-card__body">
                    <div class="stat-card__label">Dibatalkan</div>
                    <div class="stat-card__value">{{ $cancelledTickets }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="card border-0 shadow-sm mb-4" data-aos="fade-up" data-aos-delay="500">
        <div class="card-body p-4">
            <form action="{{ route('admin.tickets.index') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-700 small text-secondary text-uppercase">Cari Tiket</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control border-0 bg-light"
                                   placeholder="Kode tiket, nama pembeli..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-700 small text-secondary text-uppercase">Event</label>
                        <select name="event_id" class="form-select border-0 bg-light" onchange="this.form.submit()">
                            <option value="">Semua Event</option>
                            @foreach($events as $event)
                                <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                    {{ $event->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-700 small text-secondary text-uppercase">Status</label>
                        <select name="status" class="form-select border-0 bg-light" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Belum Digunakan</option>
                            <option value="scanned" {{ request('status') == 'scanned' ? 'selected' : '' }}>Sudah Digunakan</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-700 small text-secondary text-uppercase">Tampilkan</label>
                        <select name="per_page" class="form-select border-0 bg-light" onchange="this.form.submit()">
                            <option value="15" {{ request('per_page') == 15 ? 'selected' : '' }}>15 Data</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 Data</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 Data</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 Data</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn px-3 rounded-3 flex-grow-1 text-white fw-700" style="background-color: #7c3aed;">Terapkan</button>
                        <a href="{{ route('admin.tickets.index') }}" class="btn btn-light px-3 rounded-3" title="Reset Filter">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card border-0 shadow-sm overflow-hidden" data-aos="fade-up" data-aos-delay="600">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr class="bg-light">
                        <th class="border-0 ps-4 py-3 text-secondary small fw-700 text-uppercase">Kode Tiket</th>
                        <th class="border-0 py-3 text-secondary small fw-700 text-uppercase">Pembeli</th>
                        <th class="border-0 py-3 text-secondary small fw-700 text-uppercase">Event</th>
                        <th class="border-0 py-3 text-secondary small fw-700 text-uppercase">Kategori</th>
                        <th class="border-0 py-3 text-secondary small fw-700 text-uppercase">No. Tiket</th>
                        <th class="border-0 py-3 text-secondary small fw-700 text-uppercase">Status</th>
                        <th class="border-0 py-3 text-secondary small fw-700 text-uppercase">Scan Terakhir</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                    <tr>
                        <td class="ps-4">
                            <span class="badge bg-light text-dark border fw-700 px-3 py-2">
                                {{ $ticket->ticket_code }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="bg-primary text-white rounded-3 d-flex align-items-center justify-content-center fw-700"
                                     style="width: 36px; height: 36px; font-size: 0.8rem; flex-shrink: 0;">
                                    {{ strtoupper(substr($ticket->booking->user->name ?? 'U', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-600 small text-dark">{{ $ticket->booking->user->name ?? '-' }}</div>
                                    <div class="text-secondary" style="font-size: 0.75rem;">{{ $ticket->booking->user->email ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="fw-600 small text-dark">{{ $ticket->booking->event->title ?? '-' }}</div>
                            <div class="text-secondary" style="font-size: 0.75rem;">
                                <i class="bi bi-calendar3 me-1"></i>
                                {{ $ticket->booking->event ? \Carbon\Carbon::parse($ticket->booking->event->date)->format('d M Y') : '-' }}
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 small fw-600">
                                {{ $ticket->booking->ticket_category->name ?? '-' }}
                            </span>
                        </td>
                        <td class="small fw-600 text-dark">
                            {{ $ticket->seat_number ?? '-' }}
                        </td>
                        <td>
                            @php
                                $statusClass = match($ticket->status) {
                                    'used', 'scanned' => 'success',
                                    'cancelled' => 'danger',
                                    default     => 'warning',
                                };
                                $statusLabel = match($ticket->status) {
                                    'used', 'scanned' => 'Sudah Digunakan',
                                    'cancelled' => 'Dibatalkan',
                                    default     => 'Belum Digunakan',
                                };
                            @endphp
                            <span class="badge bg-{{ $statusClass }} bg-opacity-10 text-{{ $statusClass }} rounded-pill px-3 py-2 small fw-700">
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td class="small text-secondary">
                            @if($ticket->scanned_at)
                                <div>{{ $ticket->scanned_at->format('d M Y, H:i') }}</div>
                                @if($ticket->scannedBy)
                                    <div class="text-xs text-muted">oleh {{ $ticket->scannedBy->name }}</div>
                                @endif
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="text-secondary opacity-50 mb-3"><i class="bi bi-ticket-perforated fs-1"></i></div>
                            <p class="text-secondary mb-0">Tidak ada tiket ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($tickets->hasPages())
        <div class="card-footer bg-white border-0 py-4 px-4">
            {{ $tickets->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>

<style>
    .fw-800 { font-weight: 800; }
    .fw-700 { font-weight: 700; }
    .fw-600 { font-weight: 600; }
    .text-xs { font-size: 0.75rem; }
</style>
@endsection