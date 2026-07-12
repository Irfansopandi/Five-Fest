@extends('admin.layouts.app')
@section('title', 'Kelola Pengguna')
@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-800 text-dark mb-1">Manager Pengguna</h2>
            <p class="text-secondary mb-0">Kelola hak akses dan status seluruh pengguna sistem.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary shadow-sm px-4 py-2 rounded-4">
            <i class="bi bi-plus-lg me-2"></i> Tambah Pengguna
        </a>
    </div>

    {{-- Stats Row --}}
    <div class="row g-4 mb-5">
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
            <div class="stat-card stat-card--blue">
                <div class="stat-card__icon">
                    <i class="bi bi-people"></i>
                </div>
                <div class="stat-card__body">
                    <div class="stat-card__label">Total Pelanggan</div>
                    <div class="stat-card__value">{{ \App\Models\User::where('role', 'user')->count() }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
            <div class="stat-card stat-card--red">
                <div class="stat-card__icon">
                    <i class="bi bi-shield-lock"></i>
                </div>
                <div class="stat-card__body">
                    <div class="stat-card__label">Administrator</div>
                    <div class="stat-card__value">{{ \App\Models\User::where('role', 'admin')->count() }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
            <div class="stat-card stat-card--green">
                <div class="stat-card__icon">
                    <i class="bi bi-check2-circle"></i>
                </div>
                <div class="stat-card__body">
                    <div class="stat-card__label">User Aktif</div>
                    <div class="stat-card__value">{{ \App\Models\User::whereIn('role', ['user','admin'])->where('status', 'active')->count() }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="card border-0 shadow-sm mb-4" data-aos="fade-up" data-aos-delay="400">
        <div class="card-body p-4">
            <form action="{{ route('admin.users.index') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-700 small text-secondary text-uppercase">Cari Pengguna</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control border-0 bg-light" placeholder="Nama, Email, Telepon..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-700 small text-secondary text-uppercase">Peran</label>
                        <select name="role" class="form-select border-0 bg-light" onchange="this.form.submit()">
                            <option value="">Semua Peran</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-700 small text-secondary text-uppercase">Status</label>
                        <select name="status" class="form-select border-0 bg-light" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
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
                        <a href="{{ route('admin.users.index') }}" class="btn btn-light px-3 rounded-3 fw-700">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card border-0 shadow-sm overflow-hidden" data-aos="fade-up" data-aos-delay="500">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr class="bg-light">
                        <th class="border-0 ps-4 py-3 text-secondary small fw-700 text-uppercase">Profil</th>
                        <th class="border-0 py-3 text-secondary small fw-700 text-uppercase">Kontak</th>
                        <th class="border-0 py-3 text-secondary small fw-700 text-uppercase">Peran</th>
                        <th class="border-0 py-3 text-secondary small fw-700 text-uppercase">Status</th>
                        <th class="border-0 py-3 text-secondary small fw-700 text-uppercase">Login Terakhir</th>
                        <th class="border-0 py-3 text-secondary small fw-700 text-uppercase text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-primary text-white rounded-4 d-flex align-items-center justify-content-center fw-800" style="width: 44px; height: 44px; font-size: 0.9rem;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-700 text-dark">{{ $user->name }}</h6>
                                    <small class="text-secondary">Sejak {{ $user->created_at->format('d M Y') }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="small fw-600 mb-1 text-dark">{{ $user->email }}</div>
                            <div class="small text-secondary">{{ $user->phone ?? 'Tidak ada telepon' }}</div>
                        </td>
                        <td>
                            @if($user->role === 'admin')
                                <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2 small fw-700">Admin</span>
                            @else
                                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 small fw-700">Customer</span>
                            @endif
                        </td>
                        <td>
                            @if($user->status === 'active')
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 small fw-700">Aktif</span>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-2 small fw-700">Non-Aktif</span>
                            @endif
                        </td>
                        <td class="small text-secondary">
                            {{ $user->last_login ? \Carbon\Carbon::parse($user->last_login)->diffForHumans() : 'Belum pernah' }}
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex align-items-center justify-content-end gap-1">
                                <a href="{{ route('admin.users.show', $user->id) }}" class="action-btn" title="Lihat Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="action-btn" title="Edit Data">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST" class="d-inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="action-btn" title="{{ $user->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}">
                                        <i class="bi bi-power"></i>
                                    </button>
                                </form>
                                @if($user->id !== auth()->id())
                                <button type="button" class="action-btn action-btn--danger delete-btn"
                                        data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}" title="Hapus Akun">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <form id="deleteForm{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-none">
                                    @csrf @method('DELETE')
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="text-secondary opacity-50 mb-3"><i class="bi bi-people fs-1"></i></div>
                            <p class="text-secondary mb-0">Tidak ada pengguna ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
        <div class="card-footer bg-white border-0 py-4 px-4">
            {{ $users->links('pagination::bootstrap-5') }}
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
    .action-btn--danger:hover { background: #ef4444 !important; color: white !important; box-shadow: 0 4px 10px rgba(239,68,68,0.3); }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function () {
            const userId = this.getAttribute('data-user-id');
            const userName = this.getAttribute('data-user-name');
            Swal.fire({
                title: 'Hapus Akun?',
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