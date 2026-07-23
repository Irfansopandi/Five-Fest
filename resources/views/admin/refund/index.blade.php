@extends('admin.layouts.app')

@section('title', 'Manajemen Refund Tenant')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Manajemen Refund Tenant</h4>
            <p class="text-muted small mb-0">Kelola refund tenant yang diajukan & riwayat refund yang sudah diproses.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3">
            <i class="bi bi-x-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Tabs Container --}}
    <div class="d-inline-flex p-1 bg-light border rounded-pill mb-4" style="background: #f8f9fa !important;">
        <ul class="nav nav-pills border-0" id="refundTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-600 px-4 py-2 rounded-pill" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab">
                    <i class="bi bi-hourglass-split me-2"></i>Menunggu Proses
                    @if($refunds->total() > 0)
                        <span class="badge bg-danger ms-1 text-white">{{ $refunds->total() }}</span>
                    @endif
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-600 px-4 py-2 rounded-pill" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab">
                    <i class="bi bi-clock-history me-2"></i>Riwayat Refund
                    @if($refundHistory->total() > 0)
                        <span class="badge bg-secondary ms-1" style="font-size: 11px;">{{ $refundHistory->total() }}</span>
                    @endif
                </button>
            </li>
        </ul>
    </div>

    <div class="tab-content">

        {{-- TAB 1: Menunggu Proses --}}
        <div class="tab-pane fade show active" id="pending">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-0">
                    
                    {{-- SELEKTOR PAGINATE TAB 1 --}}
                    <div class="px-4 py-3 border-bottom bg-light bg-opacity-50 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                        <div class="d-flex align-items-center gap-2 text-muted small">
                            <span>Tampilkan:</span>
                            <select class="form-select form-select-sm rounded-3 select-per-page" style="width: 75px;" data-tab="approved">
                                <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            </select>
                            <span>data per halaman</span>
                        </div>
                        
                        <div class="text-muted small">
                            @if($refunds->total() > 0)
                                Menampilkan {{ $refunds->firstItem() }}-{{ $refunds->lastItem() }} dari {{ $refunds->total() }} data
                            @else
                                Menampilkan 0-0 dari 0 data
                            @endif
                        </div>
                    </div>

                    @if($refunds->isEmpty())
                        <div class="text-center py-5">
                            <i class="bi bi-check-circle display-4 text-success mb-3 d-block"></i>
                            <h6 class="fw-bold text-dark">Tidak ada pengajuan refund</h6>
                            <p class="text-muted small">Semua refund sudah diproses atau belum ada pengajuan baru.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="px-4 py-3">Tenant</th>
                                        <th class="py-3">Event</th>
                                        <th class="py-3">Vendor</th>
                                        <th class="py-3">Nominal</th>
                                        <th class="py-3">Detail Refund & Rekening</th>
                                        <th class="py-3">Disetujui Vendor</th>
                                        <th class="py-3">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($refunds as $refund)
                                    <tr>
                                        <td class="px-4">
                                            <div class="fw-bold small">{{ $refund->tenant->name ?? '-' }}</div>
                                            <div class="text-muted" style="font-size:11px;">{{ $refund->tenant->email ?? '-' }}</div>
                                            <div class="text-muted" style="font-size:11px;">{{ $refund->business_name }}</div>
                                        </td>
                                        <td>
                                            <div class="fw-bold small">{{ $refund->event->title ?? '-' }}</div>
                                            <div class="text-muted" style="font-size:11px;">
                                                {{ $refund->event->date ? \Carbon\Carbon::parse($refund->event->date)->format('d M Y') : '-' }}
                                            </div>
                                        </td>
                                        <td><span class="small">{{ $refund->event->vendor->name ?? '-' }}</span></td>
                                        <td>
                                            <span class="fw-bold text-danger">
                                                Rp{{ number_format($refund->event->tenant_booth_price ?? 0, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="small">
                                                <strong>Bank:</strong> {{ $refund->refund_bank_name ?? '-' }}<br>
                                                <strong>No. Rek:</strong> {{ $refund->refund_account_number ?? '-' }}<br>
                                                <strong>A/N:</strong> {{ $refund->refund_account_name ?? '-' }}
                                            </div>
                                            <div class="small text-muted mt-1 border-top pt-1 text-wrap" style="max-width:220px;">
                                                <strong>Alasan:</strong> {{ $refund->refund_reason ?? '-' }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="small text-muted">
                                                {{ $refund->refund_approved_at ? \Carbon\Carbon::parse($refund->refund_approved_at)->diffForHumans() : '-' }}
                                            </span>
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.refund.process', $refund->id) }}" method="POST" class="refund-form">
                                                @csrf
                                                <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 btn-proses-refund"
                                                    style="background: linear-gradient(135deg, #a855f7, #6366f1); border:none;">
                                                    <i class="bi bi-arrow-return-left me-1"></i> Proses Refund
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        {{-- TOMBOL NAVIGASI HALAMAN (ANGKA) DI BAWAH JIKA DATA BANYAK --}}
                        @if($refunds->hasPages())
                            <div class="px-4 py-3 border-top d-flex justify-content-end">
                                {{ $refunds->links('pagination::bootstrap-4') }} 
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        {{-- TAB 2: Riwayat Refund --}}
        <div class="tab-pane fade" id="history">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-0">
                    
                    {{-- SELEKTOR PAGINATE TAB 2 --}}
                    <div class="px-4 py-3 border-bottom bg-light bg-opacity-50 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                        <div class="d-flex align-items-center gap-2 text-muted small">
                            <span>Tampilkan:</span>
                            <select class="form-select form-select-sm rounded-3 select-per-page" style="width: 75px;" data-tab="history">
                                <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            </select>
                            <span>data per halaman</span>
                        </div>
                        
                        <div class="text-muted small">
                            @if($refundHistory->total() > 0)
                                Menampilkan {{ $refundHistory->firstItem() }}-{{ $refundHistory->lastItem() }} dari {{ $refundHistory->total() }} data
                            @else
                                Menampilkan 0-0 dari 0 data
                            @endif
                        </div>
                    </div>

                    @if($refundHistory->isEmpty())
                        <div class="text-center py-5">
                            <i class="bi bi-clock-history display-4 text-secondary mb-3 d-block opacity-50"></i>
                            <h6 class="fw-bold text-dark">Belum ada riwayat refund</h6>
                            <p class="text-muted small">Refund yang sudah diproses akan muncul di sini.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="px-4 py-3">Tenant</th>
                                        <th class="py-3">Event</th>
                                        <th class="py-3">Vendor</th>
                                        <th class="py-3">Nominal</th>
                                        <th class="py-3">Detail Refund & Rekening</th>
                                        <th class="py-3">Status</th>
                                        <th class="py-3">Selesai Diproses</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($refundHistory as $refund)
                                    <tr>
                                        <td class="px-4">
                                            <div class="fw-bold small">{{ $refund->tenant->name ?? '-' }}</div>
                                            <div class="text-muted" style="font-size:11px;">{{ $refund->tenant->email ?? '-' }}</div>
                                            <div class="text-muted" style="font-size:11px;">{{ $refund->business_name }}</div>
                                        </td>
                                        <td>
                                            <div class="fw-bold small">{{ $refund->event->title ?? '-' }}</div>
                                            <div class="text-muted" style="font-size:11px;">
                                                {{ $refund->event->date ? \Carbon\Carbon::parse($refund->event->date)->format('d M Y') : '-' }}
                                            </div>
                                        </td>
                                        <td><span class="small">{{ $refund->event->vendor->name ?? '-' }}</span></td>
                                        <td>
                                            <span class="fw-bold text-success">
                                                Rp{{ number_format($refund->event->tenant_booth_price ?? 0, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="small">
                                                <strong>Bank:</strong> {{ $refund->refund_bank_name ?? '-' }}<br>
                                                <strong>No. Rek:</strong> {{ $refund->refund_account_number ?? '-' }}<br>
                                                <strong>A/N:</strong> {{ $refund->refund_account_name ?? '-' }}
                                            </div>
                                            <div class="small text-muted mt-1 border-top pt-1 text-wrap" style="max-width:220px;">
                                                <strong>Alasan:</strong> {{ $refund->refund_reason ?? '-' }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 small fw-600">
                                                <i class="bi bi-check-circle me-1"></i>Selesai
                                            </span>
                                        </td>
                                        <td>
                                            <span class="small text-muted">
                                                {{ $refund->refund_completed_at ? \Carbon\Carbon::parse($refund->refund_completed_at)->format('d M Y, H:i') : '-' }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        {{-- TOMBOL NAVIGASI HALAMAN (ANGKA) DI BAWAH JIKA DATA BANYAK --}}
                        @if($refundHistory->hasPages())
                            <div class="px-4 py-3 border-top d-flex justify-content-end">
                                {{ $refundHistory->links('pagination::bootstrap-4') }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    .fw-600 { 
        font-weight: 600; 
    }
    
    #refundTab .nav-link { 
        border: 1px solid transparent !important;
        color: #475569;
        background: transparent;
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out;
        font-size: 14px; 
    }

    #refundTab .nav-link.active {
        color: #7c3aed !important;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08) !important;
        background-color: #ffffff !important;
        font-weight: 700;
    }

    #refundTab .nav-link:hover:not(.active) {
        color: #7c3aed;
    }

    .tab-content > .tab-pane {
        display: none;
    }
    .tab-content > .active {
        display: block;
    }

    /* KUSTOMISASI TOMBOL ANGKA NAVIGASI HALAMAN */
    .pagination {
        margin: 0;
        font-size: 13px;
        gap: 4px;
    }
    .page-item .page-link {
        padding: 6px 12px;
        border-radius: 6px !important;
        color: #475569;
        border: 1px solid #e2e8f0;
        background-color: #ffffff;
    }
    .page-item.active .page-link {
        background-color: #7c3aed !important;
        border-color: #7c3aed !important;
        color: #ffffff !important;
    }
    .page-item.disabled .page-link {
        color: #94a3b8;
        background-color: #f8fafc;
    }
</style>

@push('scripts')
<script>
    document.querySelectorAll('.btn-proses-refund').forEach(button => {
        button.addEventListener('click', function () {
            const form = this.closest('.refund-form');
            Swal.fire({
                title: 'Proses Refund?',
                text: 'Refund ini akan diproses. Lanjutkan?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Proses!',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#a855f7',
                cancelButtonColor: '#6c757d',
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });
    });

    // HANDLER DROPDOWN SELECT PER PAGE DATATABLE
    document.querySelectorAll('.select-per-page').forEach(select => {
        select.addEventListener('change', function() {
            const perPageValue = this.value;
            const activeTabName = this.getAttribute('data-tab');
            
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('per_page', perPageValue);
            currentUrl.searchParams.set('tab', activeTabName);
            currentUrl.searchParams.set('page', '1'); 
            
            localStorage.setItem('refundActiveTab', activeTabName === 'history' ? '#history' : '#pending');
            window.location.href = currentUrl.toString();
        });
    });

    // RETENTION TAB ACTIVE VIA URL DAN LOCALSTORAGE
    const urlParams = new URLSearchParams(window.location.search);
    const tabParam = urlParams.get('tab');
    if (tabParam) {
        localStorage.setItem('refundActiveTab', tabParam === 'history' ? '#history' : '#pending');
    }

    const activeTabTarget = localStorage.getItem('refundActiveTab');
    if (activeTabTarget) {
        const tabTriggerEl = document.querySelector(`[data-bs-target="${activeTabTarget}"]`);
        if (tabTriggerEl) {
            document.querySelectorAll('#refundTab .nav-link').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-content .tab-pane').forEach(p => p.classList.remove('show', 'active'));
            
            tabTriggerEl.classList.add('active');
            const targetPane = document.querySelector(activeTabTarget);
            if (targetPane) {
                targetPane.classList.add('show', 'active');
            }
        }
    }

    document.querySelectorAll('#refundTab .nav-link').forEach(tab => {
        tab.addEventListener('shown.bs.tab', function (e) {
            localStorage.setItem('refundActiveTab', e.target.getAttribute('data-bs-target'));
        });
    });
</script>
@endpush
@endsection