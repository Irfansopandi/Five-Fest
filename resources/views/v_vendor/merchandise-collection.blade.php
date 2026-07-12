@extends('v_vendor.v_layouts.app')

@section('title', 'Rekap Merchandise')

@section('content')
<div class="container-fluid px-4 py-3">

    <div class="mb-4">
        <h4 class="fw-bold mb-1">Rekap Merchandise</h4>
        <p class="text-muted mb-0 small">Status pengambilan merchandise oleh pembeli</p>
    </div>

    @php
        $statsTotal     = $allBookings->count();
        $statsCollected = $allBookings->filter(fn($b) => $b->merchandises->every(fn($m) => $m->pivot->is_collected))->count();
        $statsPending   = $allBookings->filter(fn($b) => $b->merchandises->some(fn($m) => !$m->pivot->is_collected))->count();
        $statsPct       = $statsTotal > 0 ? round(($statsCollected / $statsTotal) * 100) : 0;
    @endphp

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 text-white rounded-4 h-100"
                style="background:linear-gradient(135deg,#667eea,#764ba2);">
                <div class="card-body p-4 d-flex align-items-center gap-3 position-relative overflow-hidden">
                    <div class="position-absolute opacity-25 rounded-circle"
                        style="width:130px;height:130px;background:rgba(255,255,255,.15);right:-25px;top:-25px;pointer-events:none;"></div>
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                        style="width:54px;height:54px;background:rgba(255,255,255,.2);">
                        <i class="bi bi-box-seam fs-4"></i>
                    </div>
                    <div>
                        <div class="text-uppercase fw-bold mb-1 opacity-85" style="font-size:.62rem;letter-spacing:1.5px;">Total Booking</div>
                        <div class="fw-bold lh-1 mb-1" style="font-size:2rem;">{{ $statsTotal }}</div>
                        <a href="{{ route('vendor.merchandise.collection') }}" class="text-white opacity-75" style="font-size:.75rem;">Lihat semua →</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border-0 text-white rounded-4 h-100"
                style="background:linear-gradient(135deg,#06b6d4,#0ea5e9);">
                <div class="card-body p-4 d-flex align-items-center gap-3 position-relative overflow-hidden">
                    <div class="position-absolute opacity-25 rounded-circle"
                        style="width:130px;height:130px;background:rgba(255,255,255,.15);right:-25px;top:-25px;pointer-events:none;"></div>
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                        style="width:54px;height:54px;background:rgba(255,255,255,.2);">
                        <i class="bi bi-clock-history fs-4"></i>
                    </div>
                    <div>
                        <div class="text-uppercase fw-bold mb-1 opacity-85" style="font-size:.62rem;letter-spacing:1.5px;">Belum Diambil</div>
                        <div class="fw-bold lh-1 mb-1" style="font-size:2rem;">{{ $statsPending }}</div>
                        <a href="{{ route('vendor.merchandise.collection', ['status' => 'pending']) }}" class="text-white opacity-75" style="font-size:.75rem;">Lihat semua →</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border-0 text-white rounded-4 h-100"
                style="background:linear-gradient(135deg,#22c55e,#16a34a);">
                <div class="card-body p-4 d-flex align-items-center gap-3 position-relative overflow-hidden">
                    <div class="position-absolute opacity-25 rounded-circle"
                        style="width:130px;height:130px;background:rgba(255,255,255,.15);right:-25px;top:-25px;pointer-events:none;"></div>
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                        style="width:54px;height:54px;background:rgba(255,255,255,.2);">
                        <i class="bi bi-check-circle fs-4"></i>
                    </div>
                    <div>
                        <div class="text-uppercase fw-bold mb-1 opacity-85" style="font-size:.62rem;letter-spacing:1.5px;">Sudah Diambil</div>
                        <div class="fw-bold lh-1 mb-1" style="font-size:2rem;">{{ $statsCollected }}</div>
                        <a href="{{ route('vendor.merchandise.collection', ['status' => 'collected']) }}" class="text-white opacity-75" style="font-size:.75rem;">Lihat semua →</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border-0 text-white rounded-4 h-100"
                style="background:linear-gradient(135deg,#f97316,#fb923c);">
                <div class="card-body p-4 d-flex align-items-center gap-3 position-relative overflow-hidden">
                    <div class="position-absolute opacity-25 rounded-circle"
                        style="width:130px;height:130px;background:rgba(255,255,255,.15);right:-25px;top:-25px;pointer-events:none;"></div>
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                        style="width:54px;height:54px;background:rgba(255,255,255,.2);">
                        <i class="bi bi-percent fs-4"></i>
                    </div>
                    <div>
                        <div class="text-uppercase fw-bold mb-1 opacity-85" style="font-size:.62rem;letter-spacing:1.5px;">% Diambil</div>
                        <div class="fw-bold lh-1 mb-1" style="font-size:2rem;">{{ $statsPct }}%</div>
                        <span class="text-white opacity-75" style="font-size:.75rem;">Tingkat pengambilan</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FILTER --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted text-uppercase">Pilih Event</label>
                        <select name="event_id" class="form-select rounded-3">
                            <option value="">Semua Event</option>
                            @foreach($events as $event)
                            <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                {{ $event->title }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">Status</label>
                        <select name="status" class="form-select rounded-3">
                            <option value="">Semua Status</option>
                            <option value="collected" {{ request('status') === 'collected' ? 'selected' : '' }}>Sudah Diambil</option>
                            <option value="pending"   {{ request('status') === 'pending'   ? 'selected' : '' }}>Belum Diambil</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold text-muted text-uppercase">Tampilkan</label>
                        <select name="per_page" class="form-select rounded-3">
                            @foreach([5, 10, 25, 50] as $n)
                            <option value="{{ $n }}" {{ request('per_page', 10) == $n ? 'selected' : '' }}>{{ $n }} baris</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn w-100 text-white fw-bold rounded-3"
                                style="background:linear-gradient(135deg,#667eea,#764ba2);">
                            <i class="bi bi-funnel me-1"></i>Terapkan
                        </button>
                    </div>
                    <div class="col-md-1">
                        <a href="{{ route('vendor.merchandise.collection') }}"
                           class="btn w-100 fw-bold rounded-3 btn-outline-secondary">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- TABEL --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3 text-muted small text-uppercase">Pembeli</th>
                            <th class="py-3 text-muted small text-uppercase">Kode Booking</th>
                            <th class="py-3 text-muted small text-uppercase">Event</th>
                            <th class="py-3 text-muted small text-uppercase">Merchandise</th>
                            <th class="py-3 text-muted small text-uppercase">Status</th>
                            <th class="py-3 text-muted small text-uppercase">Waktu Ambil</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $b)
                        @php
                            $allCollected = $b->merchandises->every(fn($m) => $m->pivot->is_collected);
                            $collectedAt  = $b->merchandises->first()?->pivot->collected_at;
                        @endphp
                        <tr>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0"
                                         style="width:38px;height:38px;font-size:0.9rem;background:linear-gradient(135deg,#667eea,#764ba2);">
                                        {{ strtoupper(substr($b->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold small">{{ $b->user->name }}</div>
                                        <div class="text-muted" style="font-size:0.72rem;">{{ $b->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 font-monospace fw-bold small" style="color:#667eea;">
                                {{ $b->booking_code }}
                            </td>
                            <td class="py-3 small fw-semibold">
                                {{ $b->event->title ?? '-' }}
                                @if($b->event)
                                <div class="text-muted" style="font-size:0.72rem;">
                                    {{ \Carbon\Carbon::parse($b->event->date)->format('d M Y') }}
                                </div>
                                @endif
                            </td>
                            <td class="py-3 small">
                                @foreach($b->merchandises as $m)
                                    <span class="badge bg-light text-dark border me-1 mb-1">
                                        {{ $m->name }} x{{ $m->pivot->quantity }}
                                    </span>
                                @endforeach
                            </td>
                            <td class="py-3">
                                @if($allCollected)
                                    <span class="badge rounded-pill px-3 py-2" style="background:#dcfce7;color:#16a34a;">
                                        <i class="bi bi-check-circle-fill me-1"></i>Sudah Diambil
                                    </span>
                                @else
                                    <span class="badge rounded-pill px-3 py-2" style="background:#fff7ed;color:#f97316;">
                                        <i class="bi bi-clock me-1"></i>Belum Diambil
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 small text-muted">
                                @if($allCollected && $collectedAt)
                                    {{ \Carbon\Carbon::parse($collectedAt)->format('d M Y H:i') }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-2 mb-3 d-block opacity-50"></i>
                                Belum ada data merchandise
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($bookings->hasPages())
        <div class="card-footer bg-white border-0 px-4 py-3 d-flex justify-content-between align-items-center">
            <small class="text-muted">
                Menampilkan {{ $bookings->firstItem() }}–{{ $bookings->lastItem() }} dari {{ $bookings->total() }} data
            </small>
            {{ $bookings->withQueryString()->links() }}
        </div>
        @endif
    </div>

</div>
@endsection