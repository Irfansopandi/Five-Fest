@extends('v_vendor.v_layouts.app')

@section('title', 'Informasi Dasar - Vendor Dashboard')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8" data-aos="fade-up">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold mb-0">Informasi Dasar</h5>
                    <p class="text-muted small mb-0">Kelola informasi profil dasar vendor Anda</p>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">Nama Vendor / Perusahaan</label>
                                <input type="text" name="name" class="form-control rounded-3" value="{{ auth()->user()->name }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">No. Telepon</label>
                                <input type="tel" name="phone" class="form-control rounded-3" value="{{ auth()->user()->phone }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold small text-muted">Email Bisnis</label>
                                <input type="email" class="form-control rounded-3 bg-light" value="{{ auth()->user()->email }}" readonly>
                                <small class="text-info mt-1 d-block"><i class="bi bi-shield-lock me-1"></i>Email telah diverifikasi dan dikunci untuk akun Vendor.</small>
                            </div>
                        </div>

                        <div class="mt-5 border-top pt-4 text-end">
                            <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill fw-bold">
                                Simpan Perubahan <i class="bi bi-check2-circle ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
