@extends('v_vendor.v_layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Daftar Pengguna Tiket</h2>
            <p class="text-muted small mb-0">Kelola dan pantau semua tiket yang telah terjual.</p>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 text-white rounded-4 h-100"
                 style="background:linear-gradient(135deg,#667eea,#764ba2);">
                <div class="card-body p-4 d-flex align-items-center gap-3 position-relative overflow-hidden">
                    <div class="position-absolute opacity-25 rounded-circle"
                         style="width:130px;height:130px;background:rgba(255,255,255,.15);right:-25px;top:-25px;pointer-events:none;"></div>
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:54px;height:54px;background:rgba(255,255,255,.2);">
                        <i class="bi bi-ticket-detailed-fill fs-4"></i>
                    </div>
                    <div>
                        <div class="text-uppercase fw-bold mb-1" style="font-size:.62rem;letter-spacing:1.5px;opacity:.85;">Total Tiket</div>
                        <div class="fw-bold lh-1 mb-1" style="font-size:2rem;">{{ number_format($totalTickets) }}</div>
                        <span class="opacity-75" style="font-size:.75rem;">keseluruhan event</span>
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
                        <i class="bi bi-qr-code-scan fs-4"></i>
                    </div>
                    <div>
                        <div class="text-uppercase fw-bold mb-1" style="font-size:.62rem;letter-spacing:1.5px;opacity:.85;">Sudah Di-scan</div>
                        <div class="fw-bold lh-1 mb-1" style="font-size:2rem;">{{ number_format($totalScanned) }}</div>
                        <a href="{{ route('vendor.pengguna-tiket', ['status' => 'scanned']) }}"
                           class="text-white opacity-75" style="font-size:.75rem;">Lihat semua →</a>
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
                        <i class="bi bi-clock-history fs-4"></i>
                    </div>
                    <div>
                        <div class="text-uppercase fw-bold mb-1" style="font-size:.62rem;letter-spacing:1.5px;opacity:.85;">Belum Di-scan</div>
                        <div class="fw-bold lh-1 mb-1" style="font-size:2rem;">{{ number_format($totalBelum) }}</div>
                        <a href="{{ route('vendor.pengguna-tiket', ['status' => 'belum']) }}"
                           class="text-white opacity-75" style="font-size:.75rem;">Lihat semua →</a>
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
                        <i class="bi bi-percent fs-4"></i>
                    </div>
                    <div>
                        <div class="text-uppercase fw-bold mb-1" style="font-size:.62rem;letter-spacing:1.5px;opacity:.85;">% Di-scan</div>
                        <div class="fw-bold lh-1 mb-1" style="font-size:2rem;">{{ $pctScanned }}%</div>
                        <span class="opacity-75" style="font-size:.75rem;">tingkat kehadiran</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Per-Event Breakdown Cards (compact 3 kolom) --}}
    @if($eventStats->count() > 0)
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3 px-4">
            <h6 class="fw-bold text-muted text-uppercase mb-3" style="font-size:.72rem;letter-spacing:1px;">
                <i class="bi bi-bar-chart-fill me-1" style="color:#667eea;"></i> Rekap Scan per Event
            </h6>
            <div class="row g-2">
                @foreach($eventStats as $es)
                <div class="col-md-4">
                    <div class="border rounded-3 p-2 px-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <div class="fw-semibold text-dark text-truncate" style="font-size:.78rem;max-width:75%;">{{ $es->title }}</div>
                            <span class="badge rounded-pill px-2" style="background:#ede9fe;color:#7c3aed;font-size:.65rem;">
                                {{ $es->pct }}%
                            </span>
                        </div>
                        <div class="progress mb-1" style="height:4px;border-radius:99px;">
                            <div class="progress-bar" role="progressbar"
                                 style="width:{{ $es->pct }}%;background:linear-gradient(90deg,#667eea,#764ba2);">
                            </div>
                        </div>
                        <div class="d-flex gap-2" style="font-size:.68rem;">
                            <span class="text-success fw-semibold"><i class="bi bi-check-circle-fill me-1"></i>{{ $es->scanned }} scan</span>
                            <span class="text-warning fw-semibold"><i class="bi bi-clock me-1"></i>{{ $es->belum }} belum</span>
                            <span class="text-muted">dari {{ $es->total_qty }} tiket</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- Filter + Tabel --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-0 py-3 px-4">
            <div class="row align-items-center g-2">

                {{-- Judul kiri --}}
                <div class="col-12 col-md">
                    <h5 class="mb-0 fw-bold">Data Transaksi</h5>
                </div>

                {{-- Filter tengah --}}
                <div class="col-12 col-md-auto d-flex justify-content-md-center justify-content-start">
                    <form action="{{ url()->current() }}" method="GET"
                          class="d-flex flex-wrap gap-2 align-items-center">

                        {{-- Pilih Event --}}
                        <select name="event_id" class="form-select form-select-sm rounded-pill border bg-light shadow-sm"
                                style="width:auto;" onchange="this.form.submit()">
                            <option value="">Semua Event</option>
                            @foreach($vendorEvents as $ve)
                            <option value="{{ $ve->id }}" {{ request('event_id') == $ve->id ? 'selected' : '' }}>
                                {{ $ve->title }}
                            </option>
                            @endforeach
                        </select>

                        {{-- Status Scan --}}
                        <select name="status" class="form-select form-select-sm rounded-pill border bg-light shadow-sm"
                                style="width:auto;" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="scanned" {{ request('status') === 'scanned' ? 'selected' : '' }}>Sudah Di-scan</option>
                            <option value="belum"   {{ request('status') === 'belum'   ? 'selected' : '' }}>Belum Di-scan</option>
                        </select>

                        {{-- Per Page --}}
                        <select name="per_page" class="form-select form-select-sm rounded-pill border bg-light shadow-sm"
                                style="width:auto;" onchange="this.form.submit()">
                            @foreach([10, 25, 50, 100] as $n)
                            <option value="{{ $n }}" {{ request('per_page', 10) == $n ? 'selected' : '' }}>{{ $n }} data</option>
                            @endforeach
                        </select>

                        {{-- Search --}}
                        <div class="input-group input-group-sm rounded-pill overflow-hidden border bg-light shadow-sm"
                             style="width:220px;">
                            <span class="input-group-text bg-light border-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" name="search" class="form-control bg-light border-0 shadow-none"
                                   value="{{ request('search') }}" placeholder="Cari nama / kode...">
                            @if(request('search'))
                            <a href="{{ route('vendor.pengguna-tiket') }}"
                               class="input-group-text bg-light border-0 text-decoration-none">
                                <i class="bi bi-x-circle-fill text-muted"></i>
                            </a>
                            @endif
                        </div>

                        {{-- Reset --}}
                        @if(request()->hasAny(['event_id','status','search','per_page']))
                        <a href="{{ route('vendor.pengguna-tiket') }}"
                           class="btn btn-sm btn-outline-secondary rounded-pill px-3">Reset</a>
                        @endif

                    </form>
                </div>

                {{-- Spacer kanan supaya filter tetap di tengah --}}
                <div class="col-md d-none d-md-block"></div>

            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-uppercase small fw-bold text-muted">Nama Pembeli</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted">Event</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted">Kategori</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted">No. Tiket</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted text-center">Status Scan</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                    @php
                        $scannedCount = $ticket->tickets->where('status', 'scanned')->count(); // jumlah tiket dalam booking ini yg sudah di-scan
                        $totalCount   = $ticket->tickets->count();                            // total tiket dalam booking ini
                        $isScanned    = $scannedCount > 0;
                        $scannedAt    = $ticket->tickets->where('status', 'scanned')->first()?->scanned_at;
                    @endphp
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0"
                                     style="width:38px;height:38px;font-size:.9rem;background:linear-gradient(135deg,#667eea,#764ba2);">
                                    {{ strtoupper(substr($ticket->user->name ?? '?', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-semibold small">{{ $ticket->user->name }}</div>
                                    <div class="text-muted" style="font-size:.72rem;">{{ $ticket->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="fw-semibold small">{{ $ticket->event->title }}</div>
                            <div class="text-muted" style="font-size:.72rem;">
                                {{ \Carbon\Carbon::parse($ticket->event->date)->format('d M Y') }}
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border fw-medium">
                                {{ $ticket->ticket_category->name ?? '-' }}
                            </span>
                        </td>
                        <td>
                            @forelse($ticket->tickets as $t)
                                <div class="badge bg-light text-dark border fw-medium mb-1">
                                    {{ $t->seat_number ?? '-' }}
                                </div>
                            @empty
                                <span class="text-muted">-</span>
                            @endforelse
                        </td>
                        <td class="text-center">
                            @if($isScanned)
                                <span class="badge rounded-pill px-3 py-2" style="background:#dcfce7;color:#16a34a;">
                                    <i class="bi bi-qr-code-scan me-1"></i>Sudah Di-scan ({{ $scannedCount }}/{{ $totalCount }})  {{-- tambah info jumlah --}}
                                </span>
                                @if($scannedAt)
                                <div class="text-muted mt-1" style="font-size:.7rem;">
                                    {{ \Carbon\Carbon::parse($scannedAt)->format('d M Y H:i') }}
                                </div>
                                @endif
                            @else
                                <span class="badge rounded-pill px-3 py-2" style="background:#fff7ed;color:#f97316;">
                                    <i class="bi bi-clock me-1"></i>Belum Di-scan ({{ $scannedCount }}/{{ $totalCount }})  {{-- tambah info jumlah, biasanya 0/x --}}
                                </span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('vendor.pengguna-tiket.detail', $ticket->id) }}"
                               class="btn btn-light btn-sm rounded-3 border-0 shadow-sm" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-ticket-perforated d-block mb-2 fs-2 opacity-50"></i>
                            Belum ada data tiket ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tickets->hasPages())
        <div class="card-footer bg-white border-0 px-4 py-3 d-flex justify-content-between align-items-center">
            <small class="text-muted">
                Menampilkan {{ $tickets->firstItem() }}–{{ $tickets->lastItem() }} dari {{ $tickets->total() }} data
            </small>
            {{ $tickets->withQueryString()->links() }}
        </div>
        @endif
    </div>

</div>
@endsection