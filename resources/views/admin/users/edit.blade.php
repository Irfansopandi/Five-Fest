@extends('admin.layouts.app')

@section('title', 'Ubah Pengguna')

@section('content')
<div class="container-fluid py-5">
    <div style="max-width: 800px; margin: 0 auto;">
        {{-- Back Button --}}
        <a href="{{ route('admin.users.index') }}" class="btn btn-light shadow-sm px-4 py-2 rounded-4 fw-700 mb-4">
            <i class="bi bi-arrow-left me-2"></i> Kembali
        </a>

        {{-- Header --}}
        <div class="mb-5">
            <h2 class="fw-800 text-dark mb-1">Ubah Pengguna</h2>
            <p class="text-secondary mb-0">Perbarui informasi akun <strong>{{ $user->name }}</strong>.</p>
        </div>

        {{-- Form Card --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-5">
                @if($errors->any())
                <div class="alert alert-danger border-0 rounded-4 p-3 mb-4">
                    <ul class="mb-0 small fw-600">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Section: Account Info --}}
                    <div class="mb-5">
                        <h6 class="fw-800 text-primary text-uppercase small letter-spacing-1 mb-4">Informasi Akun</h6>
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-700 small text-secondary">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control border-0 bg-light py-3 rounded-3 @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-700 small text-secondary">Email</label>
                                <input type="email" name="email" class="form-control border-0 bg-light py-3 rounded-3 @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-700 small text-secondary">No. Telepon</label>
                                <input type="text" name="phone" class="form-control border-0 bg-light py-3 rounded-3" value="{{ old('phone', $user->phone) }}" placeholder="+62 8xx xxxx xxxx">
                            </div>
                        </div>
                    </div>

                    {{-- Section: Roles & Status --}}
                    <div class="mb-5">
                        <h6 class="fw-800 text-primary text-uppercase small letter-spacing-1 mb-4">Pengaturan</h6>
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-700 small text-secondary">Peran / Role</label>
                                <select name="role" class="form-select border-0 bg-light py-3 rounded-3" required>
                                    <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>Pengguna (User)</option>
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrator</option>
                                    <option value="vendor" {{ old('role', $user->role) == 'vendor' ? 'selected' : '' }}>Vendor</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-700 small text-secondary">Status Akun</label>
                                <select name="status" class="form-select border-0 bg-light py-3 rounded-3" required>
                                    <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                                    <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Section: Password (Optional) --}}
                    <div class="mb-5">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h6 class="fw-800 text-primary text-uppercase small letter-spacing-1 mb-0">Ubah Kata Sandi</h6>
                            <span class="badge bg-light text-secondary fw-600 rounded-pill">Opsional</span>
                        </div>
                        <p class="text-secondary small mb-4">Kosongkan kolom di bawah jika tidak ingin mengubah kata sandi.</p>
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-700 small text-secondary">Kata Sandi Baru</label>
                                <input type="password" name="password" class="form-control border-0 bg-light py-3 rounded-3 @error('password') is-invalid @enderror">
                                <small class="text-secondary mt-1 d-block">Minimal 8 karakter</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-700 small text-secondary">Konfirmasi Kata Sandi Baru</label>
                                <input type="password" name="password_confirmation" class="form-control border-0 bg-light py-3 rounded-3">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-5 pt-4 border-top">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-light px-5 py-3 rounded-4 fw-800">Batal</a>
                        <button type="submit" class="btn btn-primary px-5 py-3 rounded-4 fw-800 shadow-sm">
                            <i class="bi bi-check-circle me-2"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .fw-800 { font-weight: 800; }
    .fw-700 { font-weight: 700; }
    .fw-600 { font-weight: 600; }
    .letter-spacing-1 { letter-spacing: 1px; }
    .form-control:focus, .form-select:focus { background-color: #f1f5f9 !important; box-shadow: none; border: 1.5px solid var(--primary-color) !important; }
</style>
@endsection