@extends('v_vendor.v_layouts.app')

@section('title', 'Informasi Legal - Vendor Dashboard')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8" data-aos="fade-up">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-0">Informasi Legal</h5>
                        <p class="text-muted small mb-0">Kelola dokumen identitas dan perpajakan</p>
                    </div>
                    @php
                        $statusColor = [
                            'unverified' => 'secondary',
                            'pending' => 'warning',
                            'verified' => 'success',
                            'rejected' => 'danger'
                        ][auth()->user()->verification_status ?? 'unverified'];
                    @endphp
                    <span class="badge bg-{{ $statusColor }} px-3 py-2 rounded-pill">
                        Status: {{ strtoupper(auth()->user()->verification_status ?? 'unverified') }}
                    </span>
                </div>
                <div class="card-body p-4">
                    @if(auth()->user()->verification_status === 'rejected')
                    <div class="alert alert-danger rounded-4 mb-4">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong>Penolakan:</strong> {{ auth()->user()->rejection_reason }}
                    </div>
                    @endif

                    <form action="#" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">Tipe Dokumen</label>
                                <select class="form-select rounded-3 bg-light" disabled>
                                    <option value="individu" {{ auth()->user()->document_type === 'individu' ? 'selected' : '' }}>Individu</option>
                                    <option value="badan_hukum" {{ auth()->user()->document_type === 'badan_hukum' ? 'selected' : '' }}>Badan Hukum</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">Nomor NPWP</label>
                                <input type="text" class="form-control rounded-3" value="{{ auth()->user()->npwp_number }}" readonly>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold small text-muted">Nama NPWP</label>
                                <input type="text" class="form-control rounded-3" value="{{ auth()->user()->npwp_name }}" readonly>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold small text-muted">Alamat NPWP</label>
                                <textarea class="form-control rounded-3" rows="2" readonly>{{ auth()->user()->npwp_address }}</textarea>
                            </div>

                            {{-- Untuk badan_hukum: NIB full width, lalu NPWP & Anggaran Dasar sejajar --}}
                            @if(auth()->user()->document_type === 'badan_hukum')
                                <div class="col-12">
                                    <label class="form-label fw-bold small text-muted">Nomor NIB</label>
                                    <input type="text" class="form-control rounded-3" value="{{ auth()->user()->nib_number }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted">Dokumen NPWP Terlampir</label>
                                    <div class="mt-2">
                                        @if(auth()->user()->npwp_file)
                                            <a href="{{ asset('storage/' . auth()->user()->npwp_file) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill">
                                                <i class="bi bi-file-earmark-pdf me-1"></i> Lihat Dokumen
                                            </a>
                                        @else
                                            <span class="text-muted small">Belum diunggah</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted">Dokumen Anggaran Dasar</label>
                                    <div class="mt-2">
                                        @if(auth()->user()->anggaran_dasar_file)
                                            <a href="{{ asset('storage/' . auth()->user()->anggaran_dasar_file) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill">
                                                <i class="bi bi-file-earmark-pdf me-1"></i> Lihat Dokumen
                                            </a>
                                        @else
                                            <span class="text-muted small">Belum diunggah</span>
                                        @endif
                                    </div>
                                </div>
                            @else
                                {{-- Untuk individu: NPWP saja --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted">Dokumen NPWP Terlampir</label>
                                    <div class="mt-2">
                                        @if(auth()->user()->npwp_file)
                                            <a href="{{ asset('storage/' . auth()->user()->npwp_file) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill">
                                                <i class="bi bi-file-earmark-pdf me-1"></i> Lihat Dokumen
                                            </a>
                                        @else
                                            <span class="text-muted small">Belum diunggah</span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="mt-5 border-top pt-4">
                            <div class="p-3 rounded-4 bg-light text-center small text-muted">
                                <i class="bi bi-lock-fill me-2"></i> Data legal yang sudah diajukan tidak dapat diubah secara mandiri untuk alasan keamanan. Hubungi Admin jika ada kesalahan data.
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection