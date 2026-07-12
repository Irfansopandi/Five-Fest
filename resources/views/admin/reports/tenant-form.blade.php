@extends('admin.layouts.app')

@section('title', 'Cetak Laporan Sewa Booth Tenant')

@section('content')
<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-5">
                    <div class="text-center mb-5">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-4 mb-4" style="width: 80px; height: 80px; background: #ede9fe; color: #7c3aed;">
                            <i class="bi bi-shop fs-1"></i>
                        </div>
                        <h2 class="fw-800 text-dark mb-2">Laporan Sewa Booth Tenant</h2>
                        <p class="text-secondary">Pilih rentang tanggal untuk mencetak laporan sewa booth tenant</p>
                    </div>

                    <form action="{{ route('admin.reports.tenant.print') }}" method="POST" target="_blank">
                        @csrf

                        <div class="row g-4 mb-5">
                            <div class="col-md-6">
                                <label for="tanggal_awal" class="form-label fw-700 small text-secondary text-uppercase mb-2">Tanggal Awal</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0 px-3"><i class="bi bi-calendar-date"></i></span>
                                    <input type="date"
                                           class="form-control border-0 bg-light py-3 fw-600 @error('tanggal_awal') is-invalid @enderror"
                                           id="tanggal_awal"
                                           name="tanggal_awal"
                                           value="{{ old('tanggal_awal', date('Y-m-d')) }}"
                                           required>
                                </div>
                                @error('tanggal_awal')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="tanggal_akhir" class="form-label fw-700 small text-secondary text-uppercase mb-2">Tanggal Akhir</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0 px-3"><i class="bi bi-calendar-date"></i></span>
                                    <input type="date"
                                           class="form-control border-0 bg-light py-3 fw-600 @error('tanggal_akhir') is-invalid @enderror"
                                           id="tanggal_akhir"
                                           name="tanggal_akhir"
                                           value="{{ old('tanggal_akhir', date('Y-m-d')) }}"
                                           required>
                                </div>
                                @error('tanggal_akhir')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-lg w-100 py-3 shadow-sm rounded-4 fw-800"
                            style="background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%); color: white; border: none;">
                            <i class="bi bi-printer me-2"></i> Cetak Laporan Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .fw-800 { font-weight: 800; }
    .fw-700 { font-weight: 700; }
    .fw-600 { font-weight: 600; }
    .form-control:focus { background-color: #f8fafc !important; }
</style>
@endsection