@extends('v_layouts.app')

@section('title', 'Cari Event - FiveFest')

@section('content')

<style>
    .search-header-section {
        background: radial-gradient(circle at top right, #f5f3ff 0%, #ffffff 100%);
        padding-top: 100px;
        padding-bottom: 60px;
    }

    .breadcrumb-custom .breadcrumb-item + .breadcrumb-item::before {
        content: "•";
        color: #cbd5e1;
    }

    .text-indigo-600 { color: #4c1d95; }
    .bg-indigo-soft { background: #f5f3ff; color: #4c1d95; }
    
    .search-title {
        font-weight: 900;
        letter-spacing: -2px;
        color: #1e1b4b;
    }

    .search-highlight {
        background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .result-count-pill {
        background: rgba(139, 92, 246, 0.1);
        color: #7c3aed;
        padding: 5px 15px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.85rem;
    }

    /* Override card buttons for consistent indigo look */
    .btn-detail-premium {
        background: linear-gradient(135deg, #a855f7 0%, #6366f1 100%);
        color: white;
        border: none;
        padding: 10px 25px;
        border-radius: 50px;
        font-weight: 700;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .btn-detail-premium:hover {
        background: linear-gradient(135deg, #9333ea 0%, #4f46e5 100%);
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(109, 40, 217, 0.35);
        color: white;
    }

    .price-tag {
        color: #4c1d95;
        font-weight: 800;
        font-size: 1.25rem;
    }

    .empty-state-icon {
        width: 120px;
        height: 120px;
        background: #f5f3ff;
        border-radius: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 30px;
        color: #8b5cf6;
        font-size: 3rem;
    }

    @media (max-width: 768px) {
    /* Header */
    .search-header-section {
        padding-top: 40px !important;
        padding-bottom: 30px !important;
        padding-left: 16px !important;
        padding-right: 16px !important;
    }

    .search-title {
        font-size: 1.8rem !important;
        letter-spacing: -1px !important;
    }

    .search-header-section .fs-5 {
        font-size: 0.85rem !important;
    }

    /* Scroll row */
    .mobile-scroll-row {
        display: flex !important;
        flex-wrap: nowrap !important;
        overflow-x: auto !important;
        -webkit-overflow-scrolling: touch !important;
        gap: 14px !important;
        padding: 6px 16px 16px !important;
        margin-bottom: 16px !important;
        scrollbar-width: none !important;
    }

    .mobile-scroll-row::-webkit-scrollbar { display: none !important; }

    .mobile-scroll-row .col-lg-4,
    .mobile-scroll-row > * {
        flex: 0 0 240px !important;
        max-width: 240px !important;
        min-width: 240px !important;
        padding: 0 !important;
    }

    .event-card {
        border-radius: 20px !important;
        width: 100% !important;
        height: 100% !important;
        display: flex !important;
        flex-direction: column !important;
    }

    .event-img-wrapper {
        height: 160px !important;
        min-height: 160px !important;
        border-radius: 20px 20px 0 0 !important;
        flex-shrink: 0 !important;
    }

    .event-img-wrapper img {
        height: 160px !important;
        width: 100% !important;
        object-fit: cover !important;
    }

    .category-badge {
        font-size: 0.6rem !important;
        padding: 4px 10px !important;
    }

    .event-card .card-body {
        padding: 12px !important;
        display: flex !important;
        flex-direction: column !important;
        flex: 1 !important;
    }

    .event-card .card-body > .mt-4 {
        margin-top: 8px !important;
        display: flex !important;
        flex-direction: column !important;
        flex: 1 !important;
    }

    .event-card .card-body h5 {
        font-size: 0.82rem !important;
        min-height: 2.4rem !important;
        margin-bottom: 6px !important;
        line-height: 1.3 !important;
    }

    .event-card .card-body .small { font-size: 0.68rem !important; }

    .event-card .d-flex.align-items-center.justify-content-between.pt-3.border-top {
        margin-top: auto !important;
        padding-top: 8px !important;
        flex-wrap: nowrap !important;
        gap: 8px !important;
        align-items: center !important;
    }

    .price-tag { font-size: 0.82rem !important; flex-shrink: 0 !important; }
    .price-tag small { font-size: 0.6rem !important; }

    .btn-detail-premium {
        padding: 7px 12px !important;
        font-size: 0.68rem !important;
        border-radius: 50px !important;
        white-space: nowrap !important;
        flex-shrink: 0 !important;
    }
}
</style>

<div class="search-header-section">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-4" data-aos="fade-up">
            <ol class="breadcrumb breadcrumb-custom mb-0">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-muted fw-500">Home</a></li>
                <li class="breadcrumb-item active fw-bold text-indigo-600" aria-current="page">Pencarian</li>
            </ol>
        </nav>

        <div class="row align-items-end" data-aos="fade-up">
            <div class="col-lg-8">
                <h1 class="search-title display-4 mb-3">
                    @if(request('category'))
                        Kategori <span class="search-highlight">{{ request('category') }}</span>
                    @else
                        Hasil <span class="search-highlight">Pencarian</span>
                    @endif
                </h1>
                
                @if($query || request('category'))
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <p class="text-muted fs-5 mb-0">
                            Menampilkan hasil untuk: <span class="fw-bold text-dark">"{{ $query ?? request('category') }}"</span>
                        </p>
                        @if($results->total() > 0)
                            <span class="result-count-pill">{{ $results->total() }} Event Ditemukan</span>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<section class="pb-5 mb-5 min-vh-100">
    <div class="container">
        @if($results->count() > 0)
            <div class="row g-4 mt-2">
                @foreach($results as $index => $event)
                    <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $index * 50 }}">
                        <div class="event-card">
                            <div class="event-img-wrapper">
                                <img src="{{ asset('storage/' . $event->image) }}" class="event-img" alt="{{ $event->title }}">
                                <span class="category-badge" style="background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);">
                                    {{ $event->category ?? 'Concert' }}
                                </span>
                            </div>
                            <div class="card-body p-4 pt-0">
                                <div class="mt-4">
                                    <h5 class="fw-bold text-dark mb-3" style="font-size: 1.25rem;">{{ $event->title }}</h5>
                                    
                                    <div class="d-flex flex-column gap-2 mb-4">
                                        <div class="d-flex align-items-center text-muted small">
                                            <i class="bi bi-mic me-2 text-indigo-600"></i> {{ $event->artist ?? 'Various Artists' }}
                                        </div>
                                        <div class="d-flex align-items-center text-muted small">
                                            <i class="bi bi-calendar-week me-2 text-indigo-600"></i> {{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}
                                        </div>
                                        <div class="d-flex align-items-center text-muted small">
                                            <i class="bi bi-geo-alt me-2 text-indigo-600"></i> {{ $event->venue }}
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                                        <div class="price-tag">
                                            <small class="d-block text-muted fw-normal" style="font-size: 0.7rem; letter-spacing: 1px; text-transform: uppercase;">Mulai Dari</small>
                                            Rp{{ number_format($event->ticket_categories->min('price') ?? 0, 0, ',', '.') }}
                                        </div>
                                        @if(auth()->check() && auth()->user()->role === 'tenant')
                                            @if(!$event->is_tenant_open)
                                                <button class="btn px-3 py-2 rounded-pill fw-bold text-white shadow-sm" style="background: #94a3b8; border: none; cursor: not-allowed; font-size: 0.85rem;" disabled>
                                                    TIDAK BUKA
                                                </button>
                                            @else
                                                <div class="text-end">
                                                    @php
                                                        $hasJoined = auth()->check() && \App\Models\EventTenant::where('event_id', $event->id)->where('tenant_id', auth()->id())->exists();
                                                        $tenantRoute = $hasJoined ? route('event.detail', $event->id) : route('tenant.event.join', $event->id);
                                                    @endphp
                                                    <a href="{{ $tenantRoute }}" class="btn-detail-premium">
                                                        OPEN TENANT
                                                    </a>
                                                    @if($event->tenant_quota)
                                                        @php
                                                            $approvedCount = $event->tenants()->where('status', 'approved')->count();
                                                            $remaining = max(0, $event->tenant_quota - $approvedCount);
                                                        @endphp
                                                        <div class="small fw-600 mt-2" style="color: #6366f1; font-size: 0.75rem;">
                                                            Sisa: {{ $remaining }} / {{ $event->tenant_quota }} Slot
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        @else
                                            <a href="{{ url('/events/' . $event->id) }}" class="btn-detail-premium">
                                                Lihat Detail
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-5 d-flex justify-content-center" data-aos="fade-up">
                {{ $results->appends(['query' => $query, 'category' => request('category')])->links() }}
            </div>

        @else
            <div class="text-center py-5" data-aos="zoom-in">
                <div class="empty-state-icon">
                    <i class="bi bi-search-heart"></i>
                </div>
                <h2 class="search-title mb-3">Wah, Hasil Tidak Ditemukan</h2>
                <p class="text-muted mb-5 fs-5 mx-auto" style="max-width: 500px;">
                    Kami tidak dapat menemukan acara yang cocok dengan <span class="fw-bold">"{{ $query }}"</span>. 
                    Coba cari dengan kata kunci lain atau telusuri kategori yang berbeda.
                </p>
                
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="{{ url('/') }}" class="btn-detail-premium px-5">
                        <i class="bi bi-house me-2"></i>Kembali ke Beranda
                    </a>
                    <button onclick="toggleSearch()" class="btn btn-outline-dark rounded-pill px-5 fw-bold">
                        <i class="bi bi-search me-2"></i>Cari Lagi
                    </button>
                </div>
            </div>
        @endif
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Hanya jalankan di mobile
    if (window.innerWidth > 768) return;

    const row = document.querySelector('.pb-5.mb-5.min-vh-100 .row.g-4');
    if (!row) return;

    const cards = Array.from(row.children);
    if (cards.length === 0) return;

    // Kosongkan row asli
    row.innerHTML = '';
    row.style.display = 'block';
    row.style.padding = '0';

    // Pecah per 6 card
    const chunkSize = 4;
    for (let i = 0; i < cards.length; i += chunkSize) {
        const chunk = cards.slice(i, i + chunkSize);

        const scrollRow = document.createElement('div');
        scrollRow.className = 'mobile-scroll-row';

        chunk.forEach(card => {
            card.style.flex = '0 0 200px';
            card.style.maxWidth = '200px';
            card.style.minWidth = '200px';
            card.style.padding = '0';
            scrollRow.appendChild(card);
        });

        row.appendChild(scrollRow);
    }
});
</script>
@endpush

@endsection