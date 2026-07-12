@extends('v_vendor.v_layouts.app')
@section('title', 'Manajemen Staf')

@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0" style="color:#667eea;">Manajemen Staf</h4>
            <p class="text-muted small mb-0">Tambah dan kelola akun staf scanner merchandise kamu.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">

        {{-- Form Tambah Staf --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header border-0 rounded-top-4 py-3"
                     style="background: linear-gradient(135deg, #667eea, #764ba2);">
                    <h6 class="text-white fw-semibold mb-0">
                        <i class="bi bi-person-plus me-2"></i>Tambah Staf Baru
                    </h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('vendor.staff.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Nama Lengkap</label>
                            <input type="text" name="name"
                                   class="form-control rounded-3 @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" placeholder="Nama staf" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Email</label>
                            <input type="email" name="email"
                                   class="form-control rounded-3 @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}" placeholder="email@contoh.com" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Password</label>
                            <div style="position:relative;">
                                <input type="password" name="password" id="passwordInput"
                                    class="form-control rounded-3 @error('password') is-invalid @enderror"
                                    style="padding-right:2.5rem;"
                                    placeholder="Min. 8 karakter" required>
                                <span onclick="togglePassword('passwordInput', 'eyeIcon1')"
                                    style="position:absolute; right:10px; top:50%; transform:translateY(-50%);
                                            cursor:pointer; color:#aaa; line-height:1;">
                                    <i class="bi bi-eye" id="eyeIcon1"></i>
                                </span>
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold small">Konfirmasi Password</label>
                            <div style="position:relative;">
                                <input type="password" name="password_confirmation" id="passwordConfirmInput"
                                    class="form-control rounded-3"
                                    style="padding-right:2.5rem;"
                                    placeholder="Ulangi password" required>
                                <span onclick="togglePassword('passwordConfirmInput', 'eyeIcon2')"
                                    style="position:absolute; right:10px; top:50%; transform:translateY(-50%);
                                            cursor:pointer; color:#aaa; line-height:1;">
                                    <i class="bi bi-eye" id="eyeIcon2"></i>
                                </span>
                            </div>
                        </div>

                        <button type="submit" class="btn w-100 text-white fw-semibold rounded-3"
                                style="background: linear-gradient(135deg, #667eea, #764ba2);">
                            <i class="bi bi-plus-circle me-2"></i>Tambah Staf
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Daftar Staf --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header border-0 rounded-top-4 py-3"
                     style="background: linear-gradient(135deg, #667eea, #764ba2);">
                    <h6 class="text-white fw-semibold mb-0">
                        <i class="bi bi-people me-2"></i>Daftar Staf ({{ $staffList->count() }})
                    </h6>
                </div>
                <div class="card-body p-0">
                    @if($staffList->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-people" style="font-size:2.5rem;opacity:.3;"></i>
                            <p class="mt-2 small">Belum ada staf. Tambahkan staf pertama kamu.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4 small fw-semibold text-muted">#</th>
                                        <th class="small fw-semibold text-muted">Nama</th>
                                        <th class="small fw-semibold text-muted">Email</th>
                                        <th class="small fw-semibold text-muted">Dibuat</th>
                                        <th class="small fw-semibold text-muted">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($staffList as $i => $staff)
                                    <tr>
                                        <td class="ps-4 text-muted small">{{ $i + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                                                     style="width:36px;height:36px;font-size:.8rem;
                                                            background:linear-gradient(135deg,#667eea,#764ba2);">
                                                    {{ strtoupper(substr($staff->name, 0, 1)) }}
                                                </div>
                                                <span class="fw-semibold small">{{ $staff->name }}</span>
                                            </div>
                                        </td>
                                        <td class="text-muted small">{{ $staff->email }}</td>
                                        <td class="text-muted small">{{ $staff->created_at->format('d M Y') }}</td>
                                        <td>
                                            <form action="{{ route('vendor.staff.destroy', $staff->id) }}"
                                                method="POST"
                                                id="deleteForm{{ $staff->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                        onclick="confirmDelete({{ $staff->id }}, '{{ $staff->name }}')"
                                                        class="btn btn-sm btn-outline-danger rounded-3">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Info akses staf --}}
            <div class="alert border-0 rounded-4 mt-3"
                 style="background:#f0edff;color:#5a4fcf;">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Info:</strong> Staf hanya bisa mengakses halaman <strong>Scanner Merchandise</strong> setelah login.
                Mereka tidak bisa melihat laporan, event, atau keuangan.
            </div>
        </div>

    </div>
</div>
@push('scripts')
<script>
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon  = document.getElementById(iconId);

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    }

    function confirmDelete(id, name) {
        Swal.fire({
            title: 'Hapus Staff?',
            text: `Akun staf "${name}" akan dihapus permanen.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="bi bi-trash me-1"></i>Ya, Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true,
        }).then(result => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm' + id).submit();
            }
        });
    }

    
</script>
@endpush
@endsection