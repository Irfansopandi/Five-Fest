@extends('admin.layouts.app')
@section('title', 'Verifikasi Vendor')
@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-800 text-dark mb-1">Verifikasi Vendor</h2>
            <p class="text-secondary mb-0">Tinjau kelengkapan informasi dan legal vendor yang mendaftar.</p>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="row g-4 mb-5">
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
            <div class="stat-card stat-card--blue">
                <div class="stat-card__icon">
                    <i class="bi bi-shop"></i>
                </div>
                <div class="stat-card__body">
                    <div class="stat-card__label">Total Vendor</div>
                    <div class="stat-card__value">{{ $totalVendors }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="200">
            <div class="stat-card stat-card--orange">
                <div class="stat-card__icon">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div class="stat-card__body">
                    <div class="stat-card__label">Menunggu Verifikasi</div>
                    <div class="stat-card__value">{{ $pendingVendors }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="300">
            <div class="stat-card stat-card--green">
                <div class="stat-card__icon">
                    <i class="bi bi-patch-check"></i>
                </div>
                <div class="stat-card__body">
                    <div class="stat-card__label">Terverifikasi</div>
                    <div class="stat-card__value">{{ $verifiedVendors }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="400">
            <div class="stat-card stat-card--red">
                <div class="stat-card__icon">
                    <i class="bi bi-x-octagon"></i>
                </div>
                <div class="stat-card__body">
                    <div class="stat-card__label">Ditolak</div>
                    <div class="stat-card__value">{{ $rejectedVendors }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="card border-0 shadow-sm mb-4" data-aos="fade-up" data-aos-delay="500">
        <div class="card-body p-4">
            <form action="{{ route('admin.vendor.verification') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-700 small text-secondary text-uppercase">Cari Vendor</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control border-0 bg-light" placeholder="Nama, Email, Telepon..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-700 small text-secondary text-uppercase">Status Verifikasi</label>
                        <select name="verification_status" class="form-select border-0 bg-light" onchange="this.form.submit()">
                            <option value="">Semua</option>
                            <option value="pending" {{ request('verification_status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                            <option value="verified" {{ request('verification_status') == 'verified' ? 'selected' : '' }}>Verified</option>
                            <option value="rejected" {{ request('verification_status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-700 small text-secondary text-uppercase">Tampil</label>
                        <select name="per_page" class="form-select border-0 bg-light" onchange="this.form.submit()">
                            <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10 baris</option>
                            <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25 baris</option>
                            <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50 baris</option>
                            <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100 baris</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4 rounded-3 flex-grow-1 fw-700" style="background-color: #7c3aed;">Terapkan</button>
                        <a href="{{ route('admin.vendor.verification') }}" class="btn btn-light px-3 rounded-3 fw-700">Reset</a>
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
                        <th class="border-0 ps-4 py-3 text-secondary small fw-700 text-uppercase">Vendor</th>
                        <th class="border-0 py-3 text-secondary small fw-700 text-uppercase">Kontak</th>
                        <th class="border-0 py-3 text-secondary small fw-700 text-uppercase">Dokumen</th>
                        <th class="border-0 py-3 text-secondary small fw-700 text-uppercase">Verifikasi</th>
                        <th class="border-0 py-3 text-secondary small fw-700 text-uppercase">Bergabung</th>
                        <th class="border-0 py-3 text-secondary small fw-700 text-uppercase text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vendors as $vendor)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="text-white rounded-4 d-flex align-items-center justify-content-center fw-800" style="width: 44px; height: 44px; font-size: 0.9rem; background: linear-gradient(135deg, #f59e0b, #d97706);">
                                    {{ strtoupper(substr($vendor->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-700 text-dark">{{ $vendor->name }}</h6>
                                    <small class="text-secondary">{{ $vendor->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="small fw-600 mb-1 text-dark">{{ $vendor->phone ?? '-' }}</div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border rounded-pill px-3 py-2 small fw-600">
                                {{ $vendor->document_type === 'badan_hukum' ? 'Badan Hukum' : 'Individu' }}
                            </span>
                        </td>
                        <td>
                            @php
                                $vStatus = $vendor->verification_status ?? 'pending';
                                $vClass  = match($vStatus) {
                                    'verified' => 'success',
                                    'rejected' => 'danger',
                                    default    => 'warning',
                                };
                                $vLabel  = match($vStatus) {
                                    'verified' => 'Verified',
                                    'rejected' => 'Ditolak',
                                    default    => 'Pending',
                                };
                            @endphp
                            <span class="badge bg-{{ $vClass }} bg-opacity-10 text-{{ $vClass }} rounded-pill px-3 py-2 small fw-700">
                                {{ $vLabel }}
                            </span>
                        </td>
                        <td class="small text-secondary">
                            {{ $vendor->created_at->format('d M Y') }}
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex align-items-center justify-content-end gap-1">
                                {{-- Lihat Detail --}}
                                <a href="{{ route('admin.users.show', $vendor->id) }}" class="action-btn" title="Lihat Detail">
                                    <i class="bi bi-eye"></i>
                                </a>

                                {{-- Verifikasi (hanya pending) --}}
                                @if($vendor->verification_status === 'pending')
                                <form action="{{ route('admin.users.verify', $vendor->id) }}" method="POST" class="d-inline verify-form" id="verifyForm{{ $vendor->id }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="action" value="approve">
                                    <button type="button" class="action-btn action-btn--success verify-btn"
                                            data-user-id="{{ $vendor->id }}" data-user-name="{{ $vendor->name }}" title="Verifikasi">
                                        <i class="bi bi-patch-check"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.users.verify', $vendor->id) }}" method="POST" class="d-inline" id="rejectForm{{ $vendor->id }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="action" value="reject">
                                    <input type="hidden" name="rejection_reason" id="rejectReason{{ $vendor->id }}" value="">
                                    <button type="button" class="action-btn action-btn--danger reject-btn"
                                            data-user-id="{{ $vendor->id }}" data-user-name="{{ $vendor->name }}" title="Tolak">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </form>
                                @endif

                                {{-- Hapus --}}
                                <button type="button" class="action-btn action-btn--danger delete-btn"
                                        data-user-id="{{ $vendor->id }}" data-user-name="{{ $vendor->name }}" title="Hapus Akun">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <form id="deleteForm{{ $vendor->id }}" action="{{ route('admin.users.destroy', $vendor->id) }}" method="POST" class="d-none">
                                    @csrf @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="text-secondary opacity-50 mb-3"><i class="bi bi-shop fs-1"></i></div>
                            <p class="text-secondary mb-0">Tidak ada vendor ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($vendors->hasPages())
        <div class="card-footer bg-white border-0 py-4 px-4">
            {{ $vendors->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>

<style>
    .fw-800 { font-weight: 800; }
    .fw-700 { font-weight: 700; }
    .fw-600 { font-weight: 600; }
    .action-btn {
        width: 32px; height: 32px; border-radius: 8px;
        display: inline-flex; align-items: center; justify-content: center;
        background: #f1f5f9; color: #64748b; border: none;
        cursor: pointer; transition: all 0.2s ease;
        font-size: 0.85rem; text-decoration: none; padding: 0; flex-shrink: 0;
    }
    .action-btn:hover { background: #667eea; color: white; transform: translateY(-1px); box-shadow: 0 4px 10px rgba(102,126,234,0.3); }
    .action-btn--success:hover { background: #10b981 !important; color: white !important; box-shadow: 0 4px 10px rgba(16,185,129,0.3); }
    .action-btn--danger:hover { background: #ef4444 !important; color: white !important; box-shadow: 0 4px 10px rgba(239,68,68,0.3); }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Hapus
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function () {
            const userId = this.getAttribute('data-user-id');
            const userName = this.getAttribute('data-user-name');
            Swal.fire({
                title: 'Hapus Vendor?',
                text: `Apakah Anda yakin ingin menghapus "${userName}"? Aksi ini permanen.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true,
            }).then(result => {
                if (result.isConfirmed) document.getElementById('deleteForm' + userId).submit();
            });
        });
    });

    // Verifikasi
    document.querySelectorAll('.verify-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const userId = this.getAttribute('data-user-id');
            const userName = this.getAttribute('data-user-name');
            Swal.fire({
                title: 'Verifikasi Vendor?',
                text: `Setujui verifikasi akun "${userName}"?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Verifikasi',
                cancelButtonText: 'Batal',
                reverseButtons: true,
            }).then(result => {
                if (result.isConfirmed) document.getElementById('verifyForm' + userId).submit();
            });
        });
    });

    // Tolak
    document.querySelectorAll('.reject-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const userId = this.getAttribute('data-user-id');
            const userName = this.getAttribute('data-user-name');
            Swal.fire({
                title: 'Tolak Vendor?',
                html: `Masukkan alasan penolakan untuk <strong>${userName}</strong>`,
                input: 'textarea',
                inputPlaceholder: 'Tulis alasan penolakan secara detail...',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Kirim Penolakan',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                inputValidator: value => {
                    if (!value || !value.trim()) return 'Alasan penolakan wajib diisi!';
                }
            }).then(result => {
                if (result.isConfirmed) {
                    document.getElementById('rejectReason' + userId).value = result.value;
                    document.getElementById('rejectForm' + userId).submit();
                }
            });
        });
    });

});
</script>
@endpush
@endsection