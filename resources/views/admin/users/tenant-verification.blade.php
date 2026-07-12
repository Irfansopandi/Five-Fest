@extends('admin.layouts.app')
@section('title', 'Daftar Tenant')
@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-800 text-dark mb-1">Daftar Tenant</h2>
            <p class="text-secondary mb-0">Daftar seluruh profil tenant yang mendaftar di sistem.</p>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="row g-4 mb-5">
        <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="stat-card stat-card--blue">
                <div class="stat-card__icon"><i class="bi bi-shop-window"></i></div>
                <div class="stat-card__body">
                    <div class="stat-card__label">Total Tenant</div>
                    <div class="stat-card__value">{{ $totalTenants }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="stat-card stat-card--green">
                <div class="stat-card__icon"><i class="bi bi-check-circle"></i></div>
                <div class="stat-card__body">
                    <div class="stat-card__label">Akun Aktif</div>
                    <div class="stat-card__value">{{ $activeTenants }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="card border-0 shadow-sm mb-4" data-aos="fade-up" data-aos-delay="300">
        <div class="card-body p-4">
            <form action="{{ route('admin.tenant.verification') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-7">
                        <label class="form-label fw-700 small text-secondary text-uppercase">Cari Tenant</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control border-0 bg-light" placeholder="Nama, Email, Telepon, Usaha..." value="{{ request('search') }}">
                        </div>
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
                        <a href="{{ route('admin.tenant.verification') }}" class="btn btn-light px-3 rounded-3 fw-700">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card border-0 shadow-sm overflow-hidden" data-aos="fade-up" data-aos-delay="400">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr class="bg-light">
                        <th class="border-0 ps-4 py-3 text-secondary small fw-700 text-uppercase">Pemilik</th>
                        <th class="border-0 py-3 text-secondary small fw-700 text-uppercase">Info Usaha</th>
                        <th class="border-0 py-3 text-secondary small fw-700 text-uppercase">Kategori</th>
                        <th class="border-0 py-3 text-secondary small fw-700 text-uppercase text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tenants as $tenant)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="text-white rounded-4 d-flex align-items-center justify-content-center fw-800" style="width: 44px; height: 44px; font-size: 0.9rem; background: linear-gradient(135deg, #8b5cf6, #6366f1);">
                                    {{ strtoupper(substr($tenant->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-700 text-dark">{{ $tenant->name }}</h6>
                                    <small class="text-secondary">{{ $tenant->email }}</small>
                                    <div class="small fw-600 mt-1"><i class="bi bi-telephone-fill text-muted me-1"></i>{{ $tenant->phone ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="fw-700 text-dark mb-1">{{ $tenant->tenantProfile->business_name ?? '-' }}</div>
                            <small class="text-muted d-inline-block text-truncate" style="max-width: 200px;" title="{{ $tenant->tenantProfile->description ?? '' }}">
                                {{ $tenant->tenantProfile->description ?? '-' }}
                            </small>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border rounded-pill px-3 py-2 small fw-600">
                                {{ $tenant->tenantProfile->category ?? 'Lainnya' }}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex align-items-center justify-content-end gap-1">
                                <button type="button" class="action-btn action-btn--success" data-bs-toggle="modal" data-bs-target="#detailModal{{ $tenant->id }}" title="Detail Profil">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button type="button" class="action-btn action-btn--danger delete-btn ms-2"
                                        data-user-id="{{ $tenant->id }}" data-user-name="{{ $tenant->name }}" title="Hapus Akun">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <form id="deleteForm{{ $tenant->id }}" action="{{ route('admin.users.destroy', $tenant->id) }}" method="POST" class="d-none">
                                    @csrf @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5">
                            <div class="text-secondary opacity-50 mb-3"><i class="bi bi-shop-window fs-1"></i></div>
                            <p class="text-secondary mb-0">Tidak ada tenant ditemukan.</p>
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

{{-- Modals (di luar tabel agar tidak corrupt HTML) --}}
@foreach($tenants as $tenant)
<div class="modal fade" id="detailModal{{ $tenant->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold">Detail Profil: {{ $tenant->tenantProfile->business_name ?? $tenant->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">

                {{-- Info Pemilik --}}
                <div class="bg-light p-3 rounded-3 mb-4 border">
                    <h6 class="fw-bold mb-3 border-bottom pb-2">Informasi Pemilik & Kontak</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <small class="text-secondary d-block">Nama Lengkap</small>
                            <span class="fw-600 text-dark">{{ $tenant->name }}</span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-secondary d-block">Email</small>
                            <span class="fw-600 text-dark">{{ $tenant->email }}</span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-secondary d-block">No. Telepon / WhatsApp</small>
                            <span class="fw-600 text-dark">{{ $tenant->phone ?? '-' }}</span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-secondary d-block">Kategori Usaha</small>
                            <span class="fw-600 text-dark">{{ $tenant->tenantProfile->category ?? 'Lainnya' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Deskripsi --}}
                @if($tenant->tenantProfile && $tenant->tenantProfile->description)
                <div class="mb-4">
                    <h6 class="fw-bold mb-2">Deskripsi Usaha</h6>
                    <p class="mb-0 text-secondary bg-light p-3 rounded-3 border">{{ $tenant->tenantProfile->description }}</p>
                </div>
                @endif

                {{-- Foto Portfolio --}}
                @if($tenant->tenantProfile && $tenant->tenantProfile->portfolio_images)
                @php
                    $images = is_array($tenant->tenantProfile->portfolio_images)
                        ? $tenant->tenantProfile->portfolio_images
                        : json_decode($tenant->tenantProfile->portfolio_images, true);
                @endphp
                @if(!empty($images))
                <div class="mb-2">
                    <h6 class="fw-bold mb-3">Foto / Portofolio Booth</h6>
                    <div class="row g-2">
                        @foreach($images as $img)
                        <div class="col-6 col-md-4">
                            <a href="{{ asset('storage/' . $img) }}" target="_blank">
                                <img src="{{ asset('storage/' . $img) }}"
                                     class="img-fluid rounded-3 border w-100"
                                     style="height: 150px; object-fit: cover;"
                                     onerror="this.src='https://placehold.co/300x150?text=No+Image'">
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
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
    .action-btn--warning:hover { background: #f59e0b !important; color: white !important; box-shadow: 0 4px 10px rgba(245,158,11,0.3); }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function () {
            const userId = this.getAttribute('data-user-id');
            const userName = this.getAttribute('data-user-name');
            Swal.fire({
                title: 'Hapus Tenant?',
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
});
</script>
@endpush
@endsection