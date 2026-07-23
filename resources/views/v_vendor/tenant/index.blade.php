@extends('v_vendor.v_layouts.app')
@section('title', 'Pengajuan Tenant')
@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-800 text-dark mb-1">Pengajuan Tenant</h2>
            <p class="text-secondary mb-0">Kelola tenant yang mendaftar ke event Anda.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Stats Row --}}
    <div class="row g-4 mb-5">
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
            <div class="stat-card stat-card--blue">
                <div class="stat-card__icon">
                    <i class="bi bi-shop-window"></i>
                </div>
                <div class="stat-card__body">
                    <div class="stat-card__label">Total Pengajuan</div>
                    <div class="stat-card__value">{{ $totalTenants }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="200">
            <div class="stat-card stat-card--orange">
                <div class="stat-card__icon">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div class="stat-card__body">
                    <div class="stat-card__label">Menunggu Persetujuan</div>
                    <div class="stat-card__value">{{ $pendingTenants }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="300">
            <div class="stat-card stat-card--green">
                <div class="stat-card__icon">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="stat-card__body">
                    <div class="stat-card__label">Diterima</div>
                    <div class="stat-card__value">{{ $verifiedTenants }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="400">
            <div class="stat-card stat-card--red">
                <div class="stat-card__icon">
                    <i class="bi bi-x-circle"></i>
                </div>
                <div class="stat-card__body">
                    <div class="stat-card__label">Ditolak</div>
                    <div class="stat-card__value">{{ $rejectedTenants }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="card border-0 shadow-sm mb-4" data-aos="fade-up" data-aos-delay="500">
        <div class="card-body p-4">
            <form action="{{ route('vendor.tenants.index') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label fw-700 small text-secondary text-uppercase">Pilih Event</label>
                        <select name="event_id" class="form-select border-0 bg-light" onchange="this.form.submit()">
                            <option value="">Semua Event</option>
                            @foreach($vendorEvents as $event)
                                <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                    {{ $event->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-700 small text-secondary text-uppercase">Status</label>
                        <select name="status" class="form-select border-0 bg-light" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Diterima</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4 rounded-3 flex-grow-1 fw-700">Terapkan</button>
                        <a href="{{ route('vendor.tenants.index') }}" class="btn btn-light px-3 rounded-3 fw-700">Reset</a>
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
                        <th class="border-0 ps-4 py-3 text-secondary small fw-700 text-uppercase">Event Tujuan</th>
                        <th class="border-0 py-3 text-secondary small fw-700 text-uppercase">Info Usaha</th>
                        <th class="border-0 py-3 text-secondary small fw-700 text-uppercase">Pemilik & Kontak</th>
                        <th class="border-0 py-3 text-secondary small fw-700 text-uppercase">Status</th>
                        <th class="border-0 py-3 text-secondary small fw-700 text-uppercase text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tenants as $t)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-700 text-primary">{{ $t->event->title ?? '-' }}</div>
                            <small class="text-secondary">{{ $t->created_at->format('d M Y') }}</small>
                        </td>
                        <td>
                            <div class="fw-700 text-dark mb-1">{{ $t->tenant->tenantProfile->business_name ?? '-' }}</div>
                            <span class="badge bg-light text-dark border rounded-pill px-2 py-1 small fw-600">
                                {{ $t->tenant->tenantProfile->category ?? 'Lainnya' }}
                            </span>
                        </td>
                        <td>
                            <div class="fw-600 text-dark">{{ $t->tenant->name }}</div>
                            <small class="text-secondary">{{ $t->tenant->phone ?? $t->tenant->email }}</small>
                        </td>
                        <td>
                            @php
                                $statusClass = match($t->status) {
                                    'approved' => 'success',
                                    'rejected' => 'danger',
                                    default => 'warning'
                                };
                                $statusLabel = match($t->status) {
                                    'approved' => 'Diterima',
                                    'rejected' => 'Ditolak',
                                    default => 'Pending'
                                };

                                $payClass = match($t->payment_status) {
                                    'paid' => 'success',
                                    'failed' => 'danger',
                                    'refund_requested' => 'warning',
                                    'refunded' => 'secondary',
                                    default => 'secondary'
                                };
                               $payLabel = match(true) {
                                    $t->payment_status === 'paid' && $t->refund_status === 'requested' => 'Minta Refund',
                                    $t->payment_status === 'paid' && $t->refund_status === 'approved'  => 'Refund Disetujui',
                                    $t->payment_status === 'paid' && $t->refund_status === 'rejected'  => 'Refund Ditolak',
                                    $t->payment_status === 'paid'      => 'Lunas',
                                    $t->payment_status === 'refunded'  => 'Telah Refund',
                                    $t->payment_status === 'failed'    => 'Gagal',
                                    default => 'Belum Bayar'
                                };

                                $payClass = match(true) {
                                    $t->payment_status === 'paid' && $t->refund_status === 'requested' => 'warning',
                                    $t->payment_status === 'paid' && $t->refund_status === 'approved'  => 'info',
                                    $t->payment_status === 'paid' && $t->refund_status === 'rejected'  => 'danger',
                                    $t->payment_status === 'paid'      => 'success',
                                    $t->payment_status === 'refunded'  => 'secondary',
                                    $t->payment_status === 'failed'    => 'danger',
                                    default => 'secondary'
                                };
                            @endphp
                            <div class="d-flex flex-column gap-1 align-items-start">
                                <span class="badge bg-{{ $statusClass }} bg-opacity-10 text-{{ $statusClass }} rounded-pill px-3 py-1 small fw-700">
                                    {{ $statusLabel }}
                                </span>
                                @if($t->status === 'approved' && $t->event->tenant_booth_price > 0)
                                    <span class="badge bg-{{ $payClass }} bg-opacity-10 text-{{ $payClass }} rounded-pill px-3 py-1 small fw-700">
                                        <i class="bi bi-wallet2 me-1"></i> {{ $payLabel }}
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex align-items-center justify-content-end gap-2">
                                <button type="button" class="btn btn-sm btn-outline-info fw-600" data-bs-toggle="modal" data-bs-target="#detailModal{{ $t->id }}">
                                    <i class="bi bi-person-lines-fill"></i> Detail
                                </button>
                                
                                @if($t->status === 'pending')
                                    <form action="{{ route('vendor.tenants.verify', $t->id) }}" method="POST" class="d-inline verify-form">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="action" value="approve">
                                        <button type="button" class="btn btn-sm btn-success fw-600 verify-btn" data-name="{{ $t->tenant->tenantProfile->business_name ?? $t->tenant->name }}">
                                            <i class="bi bi-check-lg"></i> Terima
                                        </button>
                                    </form>
                                    <form action="{{ route('vendor.tenants.verify', $t->id) }}" method="POST" class="d-inline reject-form">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="action" value="reject">
                                        <button type="button" class="btn btn-sm btn-danger fw-600 reject-btn" data-name="{{ $t->tenant->tenantProfile->business_name ?? $t->tenant->name }}">
                                            <i class="bi bi-x-lg"></i> Tolak
                                        </button>
                                    </form>
                                @endif

                               @if($t->refund_status === 'requested')
                                    {{-- Approve Refund --}}
                                    <form action="{{ route('vendor.tenants.refund', $t->id) }}" method="POST" class="d-inline refund-approve-form">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="action" value="approve">
                                        <button type="button" class="btn btn-sm btn-success fw-600 refund-approve-btn"
                                            data-name="{{ $t->tenant->tenantProfile->business_name ?? $t->tenant->name }}">
                                            <i class="bi bi-check-lg"></i> Approve Refund
                                        </button>
                                    </form>
                                    {{-- Reject Refund --}}
                                    <button type="button" class="btn btn-sm btn-danger fw-600 refund-reject-btn"
                                        data-name="{{ $t->tenant->tenantProfile->business_name ?? $t->tenant->name }}"
                                        data-action="{{ route('vendor.tenants.refund', $t->id) }}">
                                        <i class="bi bi-x-lg"></i> Tolak Refund
                                    </button>
                                @elseif($t->refund_status === 'approved')
                                    <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3 py-1 small fw-700">
                                        <i class="bi bi-hourglass-split me-1"></i> Menunggu Admin
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>


                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="text-secondary opacity-50 mb-3"><i class="bi bi-shop-window fs-1"></i></div>
                            <p class="text-secondary mb-0">Belum ada tenant yang mengajukan booth.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($tenants->hasPages())
        <div class="card-footer bg-white border-0 py-4 px-4">
            {{ $tenants->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>

{{-- Modals --}}
@foreach($tenants as $t)
<div class="modal fade" id="detailModal{{ $t->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold">Detail Profil: {{ $t->tenant->tenantProfile->business_name ?? $t->tenant->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                {{-- Info Pemilik --}}
                <div class="bg-light p-3 rounded-3 mb-4 border">
                    <h6 class="fw-bold mb-3 border-bottom pb-2">Informasi Pemilik & Kontak</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <small class="text-secondary d-block">Nama Lengkap</small>
                            <span class="fw-600 text-dark">{{ $t->tenant->name }}</span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-secondary d-block">Email</small>
                            <span class="fw-600 text-dark">{{ $t->tenant->email }}</span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-secondary d-block">No. Telepon / WhatsApp</small>
                            <span class="fw-600 text-dark">{{ $t->tenant->phone ?? '-' }}</span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-secondary d-block">Kategori Usaha</small>
                            <span class="fw-600 text-dark">{{ $t->tenant->tenantProfile->category ?? 'Lainnya' }}</span>
                        </div>
                </div>

                {{-- Data Refund (Jika Ada) --}}
                @if($t->refund_status && $t->refund_status !== 'none')
                <div class="bg-warning bg-opacity-10 p-3 rounded-3 mb-4 border border-warning">
                    <h6 class="fw-bold mb-3 border-bottom border-warning-subtle pb-2 text-warning-emphasis"><i class="bi bi-arrow-left-right me-2"></i>Informasi Pengajuan Refund</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <small class="text-secondary d-block">Status Refund</small>
                            <span class="badge bg-warning text-dark fw-bold">{{ strtoupper($t->refund_status) }}</span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-secondary d-block">Tanggal Pengajuan</small>
                            <span class="fw-600 text-dark">{{ $t->refund_requested_at ? $t->refund_requested_at->format('d M Y H:i') : '-' }}</span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-secondary d-block">Nama Bank</small>
                            <span class="fw-600 text-dark">{{ $t->refund_bank_name ?? '-' }}</span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-secondary d-block">Nomor Rekening</small>
                            <span class="fw-600 text-dark">{{ $t->refund_account_number ?? '-' }}</span>
                        </div>
                        <div class="col-md-12">
                            <small class="text-secondary d-block">Atas Nama Rekening</small>
                            <span class="fw-600 text-dark">{{ $t->refund_account_name ?? '-' }}</span>
                        </div>
                        <div class="col-12">
                            <small class="text-secondary d-block">Alasan Pembatalan / Refund</small>
                            <span class="fw-600 text-dark d-block bg-white p-2 rounded border mt-1">{{ $t->refund_reason ?? '-' }}</span>
                        </div>
                        @if($t->refund_status === 'rejected' && $t->refund_reject_reason)
                        <div class="col-12">
                            <small class="text-danger d-block">Alasan Penolakan Vendor</small>
                            <span class="fw-600 text-danger d-block bg-white p-2 rounded border mt-1">{{ $t->refund_reject_reason }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Deskripsi --}}
                @if($t->tenant->tenantProfile && $t->tenant->tenantProfile->description)
                <div class="mb-4">
                    <h6 class="fw-bold mb-2">Deskripsi Usaha</h6>
                    <p class="mb-0 text-secondary bg-light p-3 rounded-3 border">{{ $t->tenant->tenantProfile->description }}</p>
                </div>
                @endif

                {{-- Portofolio --}}
                <h6 class="fw-bold mb-2">Foto / Portofolio Booth</h6>
                @if($t->booth_photo)
                <div class="w-100 rounded-3 overflow-hidden shadow-sm border" style="height: 300px;">
                    <img src="{{ asset('storage/' . $t->booth_photo) }}" class="w-100 h-100" style="object-fit: cover; object-position: center;" alt="Foto Booth">
                </div>
                @else
                <div class="alert alert-secondary small text-center mb-0 border">
                    Tidak ada foto booth yang diunggah.
                </div>
                @endif
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light border rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach


<style>
    .fw-800 { font-weight: 800; }
    .fw-700 { font-weight: 700; }
    .fw-600 { font-weight: 600; }
    
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
    .stat-card--red {
        background: linear-gradient(135deg, #f87171 0%, #dc2626 100%);
        box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
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
        margin: 2px 0 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.verify-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const name = this.getAttribute('data-name');
            const form = this.closest('form');
            Swal.fire({
                title: 'Terima Pengajuan?',
                text: `Setujui pengajuan booth dari "${name}"?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Terima',
                cancelButtonText: 'Batal',
                reverseButtons: true,
            }).then(result => {
                if (result.isConfirmed) form.submit();
            });
        });
    });

    document.querySelectorAll('.reject-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const name = this.getAttribute('data-name');
            const form = this.closest('form');
            Swal.fire({
                title: 'Tolak Pengajuan?',
                text: `Tolak pengajuan booth dari "${name}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Tolak',
                cancelButtonText: 'Batal',
                reverseButtons: true,
            }).then(result => {
                if (result.isConfirmed) form.submit();
            });
        });
    });
   document.querySelectorAll('.refund-approve-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const name = this.getAttribute('data-name');
            const form = this.closest('form');
            Swal.fire({
                title: 'Approve Refund?',
                text: `Setujui permintaan refund dari "${name}"? Dana akan diproses oleh Admin FiveFest.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Approve!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
            }).then(result => {
                if (result.isConfirmed) form.submit();
            });
        });
    });

    document.querySelectorAll('.refund-reject-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const name = this.getAttribute('data-name');
            const action = this.getAttribute('data-action');
            Swal.fire({
                title: 'Tolak Refund?',
                html: `
                    <p class="text-muted mb-3">Masukkan alasan penolakan refund dari "<strong>${name}</strong>".</p>
                    <textarea id="reject-reason" class="form-control" rows="3" 
                        placeholder="Contoh: Refund tidak dapat diproses karena..."></textarea>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Tolak!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                preConfirm: () => {
                    const reason = document.getElementById('reject-reason').value;
                    if (!reason) {
                        Swal.showValidationMessage('Alasan penolakan wajib diisi!');
                        return false;
                    }
                    return reason;
                }
            }).then(result => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = action;
                    form.innerHTML = `
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="PATCH">
                        <input type="hidden" name="action" value="reject">
                        <input type="hidden" name="refund_reject_reason" value="${result.value}">
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });
});
</script>
@endpush
@endsection
