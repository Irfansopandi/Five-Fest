@extends('owner.layouts.app')

@section('title', 'Detail Laporan')

@section('content')
<div class="container-fluid py-4 px-4">
    <div class="mb-4">
        <a href="{{ route('owner.reports') }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 14px; overflow: hidden;">

        {{-- Header --}}
        <div class="card-header d-flex justify-content-between align-items-center py-3 px-4 border-0"
            style="background: linear-gradient(135deg, #7c3aed, #6d28d9);">
            <h6 class="mb-0 fw-semibold text-white">{{ $report->title ?? 'Detail Laporan' }}</h6>
            @if($report->status === 'unread')
                <span class="badge rounded-pill" style="font-size: 11px; background: #fbbf24; color: #78350f; padding: 5px 12px;">Belum dibaca</span>
            @else
                <span class="badge rounded-pill" style="font-size: 11px; background: #34d399; color: #064e3b; padding: 5px 12px;">Sudah dibaca</span>
            @endif
        </div>

        {{-- Meta info --}}
        <div class="row g-0" style="border-bottom: 1px solid #f0eaff;">
            <div class="col-md-4 px-4 py-3" style="border-right: 1px solid #f0eaff;">
                <p class="text-uppercase mb-1" style="font-size: 10px; letter-spacing: .06em; color: #9ca3af;">Dikirim oleh</p>
                <p class="mb-0 fw-semibold small d-flex align-items-center gap-2">
                    {{ $report->admin->name ?? '-' }}
                    <span class="badge fw-normal" style="font-size: 10px; background: #ede9fe; color: #7c3aed; border-radius: 6px; padding: 3px 8px;">Admin</span>
                </p>
            </div>
            <div class="col-md-4 px-4 py-3" style="border-right: 1px solid #f0eaff;">
                <p class="text-uppercase mb-1" style="font-size: 10px; letter-spacing: .06em; color: #9ca3af;">Periode</p>
                <p class="mb-0 fw-semibold small">{{ $report->getPeriodeAttribute() ?? '-' }}</p>
            </div>
            <div class="col-md-4 px-4 py-3">
                <p class="text-uppercase mb-1" style="font-size: 10px; letter-spacing: .06em; color: #9ca3af;">Tanggal dikirim</p>
                <p class="mb-0 fw-semibold small">{{ $report->created_at->format('d M Y, H:i') }}</p>
            </div>
        </div>

        {{-- File lampiran --}}
        <div class="p-4">
            <p class="text-uppercase mb-3" style="font-size: 10px; letter-spacing: .06em; color: #9ca3af;">Lampiran</p>

            @if($report->file_path)
            <div class="d-flex align-items-center gap-3 p-3 rounded-3" style="background: #f9f7ff; border: 1px solid #ede9fe;">
                <div class="d-flex align-items-center justify-content-center rounded-2"
                     style="width: 38px; height: 38px; flex-shrink: 0; background: #fee2e2;">
                    <i class="bi bi-file-earmark-pdf text-danger"></i>
                </div>
                <div class="flex-grow-1 overflow-hidden">
                    <p class="mb-0 fw-semibold small text-truncate">{{ $report->file_name ?? basename($report->file_path) }}</p>
                    <p class="mb-0" style="font-size: 11px; color: #9ca3af;">File laporan terlampir</p>
                </div>
                <a href="{{ route('owner.reports.download', $report) }}"
                   class="btn btn-sm d-inline-flex align-items-center gap-1 flex-shrink-0"
                   style="background: #ede9fe; color: #7c3aed; border: none; border-radius: 8px; font-size: 12px; font-weight: 500; padding: 6px 14px;">
                    <i class="bi bi-download"></i> Download
                </a>
            </div>
            @else
            <p class="fst-italic small mb-0" style="color: #9ca3af;">Tidak ada file terlampir.</p>
            @endif
        </div>

    </div>
</div>
@endsection