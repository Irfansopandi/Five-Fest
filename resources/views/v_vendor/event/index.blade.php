@extends('v_vendor.v_layouts.app')

@section('title', 'Event Saya')

@section('content')
<div class="container-fluid px-4 py-3">

    {{-- HEADER --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <h4 class="fw-bold mb-1" style="color: #2d2d2d;">
                <i class="bi bi-calendar-event me-2" style="color: #667eea;"></i>Event Saya
            </h4>
            <p class="text-muted mb-0 small">Kelola semua event yang Anda buat</p>
        </div>
        <div class="d-flex gap-3 align-items-center flex-wrap">
            <form action="{{ route('vendor.events.index') }}" method="GET" class="d-flex align-items-center gap-2 bg-white px-3 py-2 rounded-pill shadow-sm border">
                @if(request('tab'))
                    <input type="hidden" name="tab" value="{{ request('tab') }}">
                @endif
                <label for="per_page" class="small fw-bold text-muted mb-0 text-nowrap"><i class="bi bi-funnel me-1"></i>Tampilkan:</label>
                <select name="per_page" id="per_page" class="form-select form-select-sm border-0 bg-transparent fw-bold text-dark pe-4 py-0" onchange="this.form.submit()" style="cursor: pointer; box-shadow: none;">
                    <option value="6" {{ (isset($perPage) && $perPage == 6) ? 'selected' : '' }}>6 Event</option>
                    <option value="9" {{ (isset($perPage) && $perPage == 9) ? 'selected' : '' }}>9 Event</option>
                    <option value="12" {{ (isset($perPage) && $perPage == 12) ? 'selected' : '' }}>12 Event</option>
                    <option value="24" {{ (isset($perPage) && $perPage == 24) ? 'selected' : '' }}>24 Event</option>
                </select>
            </form>
            <a href="{{ route('vendor.events.create') }}" class="btn btn-vendor rounded-pill px-4">
                <i class="bi bi-plus-circle me-2"></i>Buat Event
            </a>
        </div>
    </div>

    {{-- TABS --}}
    <ul class="nav nav-pills mb-4 gap-2">
        <li class="nav-item">
            <a class="nav-link rounded-pill px-4 fw-bold {{ $tab === 'upcoming' ? 'active bg-vendor text-white shadow-sm' : 'bg-white text-muted border' }}" 
               href="{{ route('vendor.events.index', ['tab' => 'upcoming', 'per_page' => $perPage]) }}">
                <i class="bi bi-calendar-check me-2"></i>Event Mendatang
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link rounded-pill px-4 fw-bold {{ $tab === 'past' ? 'active bg-vendor text-white shadow-sm' : 'bg-white text-muted border' }}" 
               href="{{ route('vendor.events.index', ['tab' => 'past', 'per_page' => $perPage]) }}">
                <i class="bi bi-calendar-x me-2"></i>Event Selesai
            </a>
        </li>
    </ul>

    @if($events->isEmpty())
        <div class="card border-0 shadow-sm rounded-4 text-center py-5">
            <div class="card-body">
                <div class="bg-light rounded-circle d-inline-flex p-4 mb-3">
                    <i class="bi bi-calendar-x fs-1 text-muted"></i>
                </div>
                <p class="text-muted mb-1 fw-bold">Belum ada event.</p>
                <p class="text-muted small mb-3">Mulai buat event pertama Anda sekarang!</p>
                <a href="{{ route('vendor.events.create') }}" class="btn btn-vendor rounded-pill px-4">
                    <i class="bi bi-plus-circle me-1"></i> Buat Event
                </a>
            </div>
        </div>
    @else
        <div class="row g-4">
            @foreach($events as $index => $event)
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ 100 * (($index % 3) + 1) }}">
                <div class="card event-card h-100 border-0 shadow-sm rounded-4 position-relative overflow-hidden transition-all">
                    <!-- Status Badge -->
                    @php $eventSudahSelesai = now()->gt($event->date); @endphp
                    <div class="position-absolute top-0 end-0 m-3 z-1">
                        @php
                            if ($eventSudahSelesai) {
                                $badge = ['bg' => 'secondary', 'label' => 'Selesai'];
                            } else {
                                $status = $event->status ?? 'active';
                                $badge = match($status) {
                                    'active'   => ['bg' => 'success', 'label' => 'Aktif'],
                                    'inactive' => ['bg' => 'secondary', 'label' => 'Nonaktif'],
                                    default    => ['bg' => 'warning', 'label' => ucfirst($status)],
                                };
                            }
                        @endphp
                        <span class="badge bg-{{ $badge['bg'] }} rounded-pill px-3 py-2 fw-bold shadow-sm" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                            <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i>{{ $badge['label'] }}
                        </span>
                    </div>
                    
                    <!-- Image -->
                    <div class="event-img-wrapper position-relative" style="height: 180px; overflow: hidden; background: #f8fafc;">
                        <img src="{{ asset('storage/' . $event->image) }}" class="w-100 h-100 object-fit-cover transition-transform duration-300" onerror="this.src='https://placehold.co/400x250?text=Event'">
                        <div class="position-absolute bottom-0 start-0 w-100 p-3 bg-gradient-dark">
                            <span class="badge bg-white text-dark rounded-pill shadow-sm fw-bold">
                                <i class="bi bi-tag-fill me-1" style="color: #7c3aed;"></i>{{ $event->category ?? 'General' }}
                            </span>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body p-4 d-flex flex-column">
                        <h5 class="fw-bold text-dark mb-3" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.4; min-height: 2.8rem;">
                            {{ $event->title }}
                        </h5>

                        <div class="d-flex align-items-center text-muted small mb-2">
                            <div class="bg-light rounded p-1 me-2"><i class="bi bi-calendar-event text-primary w-20px text-center"></i></div>
                            <span class="fw-medium">{{ \Carbon\Carbon::parse($event->date)->format('d M Y') }} • {{ \Carbon\Carbon::parse($event->time)->format('H:i') }} WIB</span>
                        </div>
                        <div class="d-flex align-items-center text-muted small mb-4 text-truncate">
                            <div class="bg-light rounded p-1 me-2"><i class="bi bi-geo-alt text-danger w-20px text-center"></i></div>
                            <span class="fw-medium text-truncate">{{ $event->venue }}</span>
                        </div>
                        
                        <div class="row g-2 mb-4 bg-light rounded-4 p-3 mx-0 mt-auto border">
                            <div class="col-6 border-end border-2 border-white">
                                <div class="text-muted mb-1 fw-bold" style="font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.5px;">Mulai Dari</div>
                                <div class="fw-bold text-dark" style="font-size: 0.95rem;">Rp {{ number_format($event->price, 0, ',', '.') }}</div>
                            </div>
                            <div class="col-6 ps-3">
                                <div class="text-muted mb-1 fw-bold" style="font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.5px;">Sisa Kuota</div>
                                <div class="fw-bold text-dark" style="font-size: 0.95rem;">{{ $event->available_tickets }} <span class="fw-normal text-muted" style="font-size: 0.75rem;">Tiket</span></div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="d-flex gap-2 mt-auto">
                            <a href="{{ route('vendor.events.edit', $event->id) }}" class="btn btn-light flex-grow-1 rounded-3 text-primary fw-bold shadow-sm action-btn">
                                <i class="bi bi-pencil-square me-1"></i> Edit
                            </a>
                            
                            @unless($eventSudahSelesai)
                            <form action="{{ route('vendor.events.toggle-status', $event->id) }}" method="POST" class="flex-grow-1 m-0">
                                @csrf @method('PATCH')
                                @if($event->status === 'active')
                                    <button type="submit" class="btn btn-outline-secondary w-100 rounded-3 fw-bold shadow-sm action-btn" title="Nonaktifkan Event">
                                        <i class="bi bi-pause-circle me-1"></i> Nonaktif
                                    </button>
                                @else
                                    <button type="submit" class="btn btn-success w-100 rounded-3 fw-bold shadow-sm action-btn" title="Aktifkan Event" style="background-color: #10b981; border-color: #10b981;">
                                        <i class="bi bi-play-circle me-1"></i> Aktifkan
                                    </button>
                                @endif
                            </form>
                            @endunless

                            <form action="{{ route('vendor.events.destroy', $event->id) }}" method="POST" class="delete-form m-0">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-outline-danger rounded-3 btn-delete px-3 shadow-sm action-btn" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- PAGINATION --}}
        @if($events->hasPages())
        <div class="mt-5 d-flex justify-content-center">
            {{ $events->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
        @endif
    @endif

</div>

<style>
    .event-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    .event-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.08) !important; }
    .event-card:hover .event-img-wrapper img { transform: scale(1.05); }
    .transition-transform { transition: transform 0.5s ease; }
    .bg-gradient-dark { background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, transparent 100%); }
    .action-btn { transition: 0.2s; }
    .action-btn:active { transform: scale(0.95); }
    
    .btn-vendor {
        background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
        color: white; border: none;
        box-shadow: 0 4px 15px rgba(124, 58, 237, 0.25);
        transition: 0.3s;
    }
    .btn-vendor:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(124, 58, 237, 0.35);
        color: white;
    }
    
    .bg-vendor {
        background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%) !important;
        border: none !important;
    }
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function () {
            const form = this.closest('.delete-form');
            Swal.fire({
                title: 'Hapus Event?',
                text: 'Event yang dihapus tidak bisa dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then(result => {
                if (result.isConfirmed) form.submit();
            });
        });
    });
</script>
@endpush
@endsection