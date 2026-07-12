@extends('admin.layouts.app')

@section('title', 'Pajak Jasa Platform')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-800 text-dark">Pajak Jasa Platform</h4>
            <p class="text-secondary mb-0 small">Pendapatan murni platform Five Fest dari potongan 3% transaksi tiket (Vendor) dan penyewaan booth (Tenant)</p>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="d-flex justify-content-start mb-4">
        <ul class="nav nav-pills p-1.5 bg-light rounded-pill gap-1 mb-0 border" style="padding: 6px;" id="financeTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link rounded-pill px-4 py-2 fw-bold @if($activeTab === 'vendor') active @endif" id="vendor-tab" data-bs-toggle="pill" data-bs-target="#vendor-content" type="button" role="tab" aria-controls="vendor-content" aria-selected="{{ $activeTab === 'vendor' ? 'true' : 'false' }}">
                    <i class="bi bi-briefcase me-2"></i>Jasa Layanan Vendor
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link rounded-pill px-4 py-2 fw-bold @if($activeTab === 'tenant') active @endif" id="tenant-tab" data-bs-toggle="pill" data-bs-target="#tenant-content" type="button" role="tab" aria-controls="tenant-content" aria-selected="{{ $activeTab === 'tenant' ? 'true' : 'false' }}">
                    <i class="bi bi-shop me-2"></i>Jasa Layanan Tenant
                </button>
            </li>
        </ul>
    </div>

    <!-- Tab Contents -->
    <div class="tab-content" id="financeTabsContent">
        
        <!-- ================= TAB 1: VENDOR SERVICE FEE ================= -->
        <div class="tab-pane fade @if($activeTab === 'vendor') show active @endif" id="vendor-content" role="tabpanel" aria-labelledby="vendor-tab">
            <!-- Stats Cards -->
            <div class="row g-3 mb-4">
                <!-- Total Vendor Service Fee -->
                <div class="col-xl-6 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="stat-card stat-card--purple h-100 mb-0">
                        <div class="stat-card__icon">
                            <i class="bi bi-wallet2"></i>
                        </div>
                        <div class="stat-card__body">
                            <div class="stat-card__label">TOTAL SALDO TERKUMPUL (VENDOR)</div>
                            <div class="stat-card__value">Rp {{ number_format($totalServiceFee, 0, ',', '.') }}</div>
                            <span class="stat-card__link" style="opacity: 0.8;">
                                <i class="bi bi-arrow-up-right"></i> Akumulasi dari seluruh tiket lunas
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Platform Bank Info -->
                <div class="col-xl-6 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="stat-card stat-card--blue h-100 mb-0">
                        <div class="stat-card__icon">
                            <i class="bi bi-bank"></i>
                        </div>
                        <div class="stat-card__body">
                            <div class="stat-card__label">DATA REKENING PLATFORM</div>
                            <div class="stat-card__value" style="font-size: 1.5rem; letter-spacing: 2px;">8291 002 991</div>
                            <span class="stat-card__link" style="opacity: 0.8;">
                                <i class="bi bi-building"></i> BCA A.N PT FIVE FEST INDONESIA
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter / Search -->
            <div class="card border-0 shadow-sm mb-4" data-aos="fade-up" data-aos-delay="300">
                <div class="card-body p-4">
                    <form action="{{ route('admin.finance.service-fee') }}" method="GET">
                        <input type="hidden" name="tab" value="vendor">
                        <!-- Keep tenant pagination & search parameters if any -->
                        @if(request('search_tenant')) <input type="hidden" name="search_tenant" value="{{ request('search_tenant') }}"> @endif
                        @if(request('per_page_tenant')) <input type="hidden" name="per_page_tenant" value="{{ request('per_page_tenant') }}"> @endif
                        @if(request('page_tenant')) <input type="hidden" name="page_tenant" value="{{ request('page_tenant') }}"> @endif
                        
                        <div class="row g-3 align-items-end">
                            <div class="col-md-6">
                                <label class="form-label fw-700 small text-secondary text-uppercase">Cari Transaksi Tiket</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
                                    <input type="text" name="search_vendor" class="form-control border-0 bg-light" placeholder="Kode Booking atau Nama Event..." value="{{ request('search_vendor') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-700 small text-secondary text-uppercase">Tampilkan</label>
                                <select name="per_page_vendor" class="form-select border-0 bg-light" onchange="this.form.submit()">
                                    <option value="10" {{ request('per_page_vendor') == 10 ? 'selected' : '' }}>10 Baris</option>
                                    <option value="25" {{ request('per_page_vendor') == 25 ? 'selected' : '' }}>25 Baris</option>
                                    <option value="50" {{ request('per_page_vendor') == 50 ? 'selected' : '' }}>50 Baris</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex gap-2">
                                <button type="submit" class="btn btn-primary px-4 rounded-3 flex-grow-1 fw-700" style="background-color: #7c3aed; border-color: #7c3aed;">Terapkan</button>
                                <a href="{{ route('admin.finance.service-fee', ['tab' => 'vendor']) }}" class="btn btn-light px-3 rounded-3 fw-700">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table -->
            <div class="card border-0 shadow-sm overflow-hidden" data-aos="fade-up" data-aos-delay="400">
                <div class="card-header bg-white border-0 py-4 ps-4">
                    <h6 class="mb-0 fw-800 text-dark"><i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Masuk Jasa Layanan Vendor</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr class="bg-light">
                                <th class="border-0 ps-4 py-3 text-secondary small fw-700 text-uppercase">Booking ID</th>
                                <th class="border-0 py-3 text-secondary small fw-700 text-uppercase">Tanggal</th>
                                <th class="border-0 py-3 text-secondary small fw-700 text-uppercase">Event & Vendor</th>
                                <th class="border-0 py-3 text-secondary small fw-700 text-uppercase text-end">Harga Dasar (Total)</th>
                                <th class="border-0 py-3 text-secondary small fw-700 text-uppercase text-end pe-4">Jasa Layanan (3%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $booking)
                                @php
                                    $ticketTotal = $booking->quantity * ($booking->ticket_category->price ?? 0);
                                    $merchTotal = 0;
                                    foreach ($booking->merchandises as $merch) {
                                        $merchTotal += ($merch->pivot->price * $merch->pivot->quantity);
                                    }
                                    $basePrice = $ticketTotal + $merchTotal;
                                    $serviceFee = (int) round($basePrice * 0.03);
                                @endphp
                                <tr>
                                    <td class="ps-4">
                                        <code class="fw-700 text-primary">{{ $booking->booking_code }}</code>
                                    </td>
                                    <td>
                                        <div class="fw-600">{{ $booking->created_at->format('d M Y') }}</div>
                                        <small class="text-secondary">{{ $booking->created_at->format('H:i') }} WIB</small>
                                    </td>
                                    <td>
                                        <div class="fw-700 text-dark">{{ Str::limit($booking->event->title ?? '-', 35) }}</div>
                                        <small class="text-secondary"><i class="bi bi-person me-1"></i>{{ $booking->event->vendor->name ?? '-' }}</small>
                                    </td>
                                    <td class="text-end fw-600 text-secondary">
                                        Rp {{ number_format($basePrice, 0, ',', '.') }}
                                    </td>
                                    <td class="text-end pe-4">
                                        <span class="fw-800 text-success bg-success bg-opacity-10 px-2 py-1 rounded">
                                            + Rp {{ number_format($serviceFee, 0, ',', '.') }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="text-secondary opacity-50 mb-3"><i class="bi bi-inbox fs-1"></i></div>
                                        <p class="text-secondary mb-0 fw-600">Belum ada riwayat transaksi masuk vendor.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($bookings->hasPages())
                <div class="card-footer bg-white border-0 py-4 px-4">
                    {{ $bookings->links('pagination::bootstrap-5') }}
                </div>
                @endif
            </div>
        </div>

        <!-- ================= TAB 2: TENANT SERVICE FEE ================= -->
        <div class="tab-pane fade @if($activeTab === 'tenant') show active @endif" id="tenant-content" role="tabpanel" aria-labelledby="tenant-tab">
            <!-- Stats Cards -->
            <div class="row g-3 mb-4">
                <!-- Total Tenant Service Fee -->
                <div class="col-xl-6 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="stat-card stat-card--purple h-100 mb-0">
                        <div class="stat-card__icon">
                            <i class="bi bi-wallet2"></i>
                        </div>
                        <div class="stat-card__body">
                            <div class="stat-card__label">TOTAL SALDO TERKUMPUL (TENANT)</div>
                            <div class="stat-card__value">Rp {{ number_format($totalTenantServiceFee, 0, ',', '.') }}</div>
                            <span class="stat-card__link" style="opacity: 0.8;">
                                <i class="bi bi-arrow-up-right"></i> Akumulasi dari seluruh sewa booth lunas
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Platform Bank Info -->
                <div class="col-xl-6 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="stat-card stat-card--blue h-100 mb-0">
                        <div class="stat-card__icon">
                            <i class="bi bi-bank"></i>
                        </div>
                        <div class="stat-card__body">
                            <div class="stat-card__label">DATA REKENING PLATFORM</div>
                            <div class="stat-card__value" style="font-size: 1.5rem; letter-spacing: 2px;">8291 002 991</div>
                            <span class="stat-card__link" style="opacity: 0.8;">
                                <i class="bi bi-building"></i> BCA A.N PT FIVE FEST INDONESIA
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter / Search -->
            <div class="card border-0 shadow-sm mb-4" data-aos="fade-up" data-aos-delay="300">
                <div class="card-body p-4">
                    <form action="{{ route('admin.finance.service-fee') }}" method="GET">
                        <input type="hidden" name="tab" value="tenant">
                        <!-- Keep vendor pagination & search parameters if any -->
                        @if(request('search_vendor')) <input type="hidden" name="search_vendor" value="{{ request('search_vendor') }}"> @endif
                        @if(request('per_page_vendor')) <input type="hidden" name="per_page_vendor" value="{{ request('per_page_vendor') }}"> @endif
                        @if(request('page_vendor')) <input type="hidden" name="page_vendor" value="{{ request('page_vendor') }}"> @endif
                        
                        <div class="row g-3 align-items-end">
                            <div class="col-md-6">
                                <label class="form-label fw-700 small text-secondary text-uppercase">Cari Tenant/Event</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
                                    <input type="text" name="search_tenant" class="form-control border-0 bg-light" placeholder="Nama Tenant atau Nama Event..." value="{{ request('search_tenant') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-700 small text-secondary text-uppercase">Tampilkan</label>
                                <select name="per_page_tenant" class="form-select border-0 bg-light" onchange="this.form.submit()">
                                    <option value="10" {{ request('per_page_tenant') == 10 ? 'selected' : '' }}>10 Baris</option>
                                    <option value="25" {{ request('per_page_tenant') == 25 ? 'selected' : '' }}>25 Baris</option>
                                    <option value="50" {{ request('per_page_tenant') == 50 ? 'selected' : '' }}>50 Baris</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex gap-2">
                                <button type="submit" class="btn btn-primary px-4 rounded-3 flex-grow-1 fw-700" style="background-color: #7c3aed; border-color: #7c3aed;">Terapkan</button>
                                <a href="{{ route('admin.finance.service-fee', ['tab' => 'tenant']) }}" class="btn btn-light px-3 rounded-3 fw-700">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table -->
            <div class="card border-0 shadow-sm overflow-hidden" data-aos="fade-up" data-aos-delay="400">
                <div class="card-header bg-white border-0 py-4 ps-4">
                    <h6 class="mb-0 fw-800 text-dark"><i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Masuk Jasa Layanan Tenant</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr class="bg-light">
                                <th class="border-0 ps-4 py-3 text-secondary small fw-700 text-uppercase">Nama Tenant</th>
                                <th class="border-0 py-3 text-secondary small fw-700 text-uppercase">Tanggal</th>
                                <th class="border-0 py-3 text-secondary small fw-700 text-uppercase">Event & Jenis</th>
                                <th class="border-0 py-3 text-secondary small fw-700 text-uppercase text-end">Harga Sewa Booth</th>
                                <th class="border-0 py-3 text-secondary small fw-700 text-uppercase text-end pe-4">Jasa Layanan (3%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($booths as $booth)
                                @php
                                    $boothPrice = $booth->event->tenant_booth_price ?? 0;
                                    $serviceFee = (int) round($boothPrice * 0.03);
                                @endphp
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-700 text-dark">{{ $booth->tenant->name ?? '-' }}</div>
                                        <small class="text-secondary">{{ $booth->tenant->email ?? '-' }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-600">{{ $booth->created_at->format('d M Y') }}</div>
                                        <small class="text-secondary">{{ $booth->created_at->format('H:i') }} WIB</small>
                                    </td>
                                    <td>
                                        <div class="fw-700 text-dark">{{ Str::limit($booth->event->title ?? '-', 35) }}</div>
                                        <small class="text-secondary"><i class="bi bi-shop me-1"></i>Sewa Booth</small>
                                    </td>
                                    <td class="text-end fw-600 text-secondary">
                                        Rp {{ number_format($boothPrice, 0, ',', '.') }}
                                    </td>
                                    <td class="text-end pe-4">
                                        <span class="fw-800 text-success bg-success bg-opacity-10 px-2 py-1 rounded">
                                            + Rp {{ number_format($serviceFee, 0, ',', '.') }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="text-secondary opacity-50 mb-3"><i class="bi bi-inbox fs-1"></i></div>
                                        <p class="text-secondary mb-0 fw-600">Belum ada riwayat transaksi masuk tenant.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($booths->hasPages())
                <div class="card-footer bg-white border-0 py-4 px-4">
                    {{ $booths->links('pagination::bootstrap-5') }}
                </div>
                @endif
            </div>
        </div>

    </div>
</div>

<style>
    .fw-800 { font-weight: 800; }
    .fw-700 { font-weight: 700; }
    .fw-600 { font-weight: 600; }
    
    /* ===== TAB STYLING ===== */
    #financeTabs {
        background-color: #f8fafc !important;
        border: 1px solid #e2e8f0 !important;
    }
    #financeTabs .nav-link {
        color: #64748b !important;
        font-size: 0.9rem;
        transition: all 0.25s ease;
        background: transparent !important;
        border: none !important;
    }
    #financeTabs .nav-link.active {
        background-color: #fff !important;
        color: #7c3aed !important; /* Admin purple brand color */
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06) !important;
    }
    #financeTabs .nav-link:hover:not(.active) {
        color: #1e293b !important;
        background: transparent !important;
    }
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sync URL query param `tab` when client switches tabs
        var triggerTabList = [].slice.call(document.querySelectorAll('#financeTabs button'))
        triggerTabList.forEach(function (triggerEl) {
            triggerEl.addEventListener('shown.bs.tab', function (event) {
                var tabId = event.target.id;
                var tabName = tabId === 'tenant-tab' ? 'tenant' : 'vendor';
                var url = new URL(window.location);
                url.searchParams.set('tab', tabName);
                window.history.pushState({}, '', url);
            });
        });
    });
</script>
@endpush
@endsection
