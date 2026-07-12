@extends('owner.layouts.app')

@section('title', 'Laporan dari Admin')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-3">
            <div style="width:48px;height:48px;border-radius:14px;background:linear-gradient(135deg,#667eea,#764ba2);display:flex;align-items:center;justify-content:center;">
                <i class="bi bi-file-earmark-text text-white fs-5"></i>
            </div>
            <div>
                <h5 class="fw-bold mb-0">Laporan dari Admin</h5>
                <p class="text-muted mb-0 small">Daftar laporan yang dikirim oleh Admin</p>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('owner.reports') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Bulan</label>
                    <select name="month" class="form-select form-select-sm">
                        <option value="">-- Semua Bulan --</option>
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Tahun</label>
                    <select name="year" class="form-select form-select-sm">
                        <option value="">-- Semua Tahun --</option>
                        @foreach($years as $y)
                            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-sm w-100 text-white"
                            style="background:linear-gradient(135deg,#667eea,#764ba2); border:none;">
                        <i class="bi bi-funnel me-1"></i>Filter
                    </button>
                </div>
                @if(request()->hasAny(['month', 'year']))
                <div class="col-md-2">
                    <a href="{{ route('owner.reports') }}" class="btn btn-sm btn-outline-secondary w-100">Reset</a>
                </div>
                @endif
            </form>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header border-0 rounded-top-4 py-3 px-4" style="background:#f5f3ff;">
            <h6 class="mb-0 fw-semibold" style="color:#7c3aed;">
                <i class="bi bi-clock-history me-2"></i>Riwayat Laporan
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background:#f5f3ff;">
                        <tr>
                            <th class="ps-4 py-3 small text-muted fw-semibold" style="width:50px;">#</th>
                            <th class="py-3 small text-muted fw-semibold">Judul Laporan</th>
                            <th class="py-3 small text-muted fw-semibold">Dikirim oleh</th>
                            <th class="py-3 small text-muted fw-semibold">Periode</th>
                            <th class="py-3 small text-muted fw-semibold">Tanggal Kirim</th>
                            <th class="py-3 small text-muted fw-semibold">Status</th>
                            <th class="py-3 small text-muted fw-semibold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                        <tr style="{{ $report->status === 'unread' ? 'background-color: #faf5ff;' : '' }}">
                            <td class="ps-4 text-muted small">
                                {{ $loop->iteration + ($reports->currentPage() - 1) * $reports->perPage() }}
                            </td>
                            <td class="fw-semibold small">{{ $report->title ?? 'Laporan #' . $report->id }}</td>
                            <td class="text-muted small">{{ $report->admin->name ?? '-' }}</td>
                            <td class="small">
                                @if($report->period_start && $report->period_end)
                                    {{ $report->period_start->translatedFormat('d M Y') }} –
                                    {{ $report->period_end->translatedFormat('d M Y') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="small text-muted">{{ $report->created_at->format('d M Y, H:i') }}</td>
                            <td>
                                @if($report->status === 'unread')
                                    <span class="badge rounded-pill" style="background:#f3e8ff;color:#7c3aed;font-size:0.7rem;">
                                        <i class="bi bi-circle-fill me-1" style="font-size:0.4rem;"></i>Belum dibaca
                                    </span>
                                @else
                                    <span class="badge rounded-pill" style="background:#dcfce7;color:#16a34a;font-size:0.7rem;">
                                        <i class="bi bi-check-circle me-1"></i>Sudah dibaca
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('owner.reports.show', $report) }}"
                                   class="btn btn-sm text-white me-1"
                                   style="background:linear-gradient(135deg,#667eea,#764ba2); border:none;">
                                    <i class="bi bi-eye"></i> Lihat
                                </a>
                                <a href="{{ route('owner.reports.download', $report) }}"
                                   class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-download"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-2 d-block mb-2 opacity-50"></i>
                                <span class="small">Belum ada laporan yang dikirim.</span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($reports->hasPages())
        <div class="card-footer bg-white border-top d-flex justify-content-end py-2 rounded-bottom-4">
            {{ $reports->withQueryString()->links() }}
        </div>
        @endif
    </div>

</div>
@endsection