@extends('admin.layouts.app')

@section('title', 'Kirim Laporan ke Owner')

@section('content')
<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex align-items-center gap-3 mb-4">
        <div style="width:48px;height:48px;border-radius:14px;background:linear-gradient(135deg,#667eea,#764ba2);display:flex;align-items:center;justify-content:center;">
            <i class="bi bi-send text-white fs-5"></i>
        </div>
        <div>
            <h4 class="fw-700 mb-0">Kirim Laporan ke Owner</h4>
            <p class="text-muted mb-0 small">Upload dan kirim laporan bulanan kepada Owner</p>
        </div>
    </div>

    <div class="row g-4">

        {{-- Form Kirim --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header border-0 rounded-top-4 py-3 px-4"
                     style="background:linear-gradient(135deg,#667eea,#764ba2);">
                    <h6 class="mb-0 text-white fw-600">
                        <i class="bi bi-file-earmark-arrow-up me-2"></i>Form Laporan Baru
                    </h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.reports.owner.send') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-600 small">Judul Laporan</label>
                            <input type="text" name="title" value="{{ old('title') }}"
                                   class="form-control @error('title') is-invalid @enderror"
                                   placeholder="cth. Laporan Penjualan Mei 2025">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label fw-600 small">Periode Awal</label>
                                <input type="date" name="period_start" value="{{ old('period_start') }}"
                                    class="form-control @error('period_start') is-invalid @enderror">
                                @error('period_start')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-600 small">Periode Akhir</label>
                                <input type="date" name="period_end" value="{{ old('period_end') }}"
                                    class="form-control @error('period_end') is-invalid @enderror">
                                @error('period_end')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-600 small">File Laporan</label>
                            <div class="border-2 border-dashed rounded-3 p-4 text-center"
                                id="uploadArea"
                                style="border: 2px dashed #c4b5fd; background:#faf5ff; cursor:pointer;"
                                onclick="document.getElementById('fileInput').click()">
                                <i class="bi bi-cloud-arrow-up fs-2" id="uploadIcon" style="color:#7c3aed;"></i>
                                <p class="mb-1 fw-600 small mt-2" id="uploadText" style="color:#7c3aed;">Klik untuk upload file</p>
                                <p class="text-muted mb-0" id="uploadHint" style="font-size:0.75rem;">PDF, DOC, DOCX, XLSX — Maks. 10MB</p>
                                <p class="mt-2 mb-0 small fw-semibold" id="fileName" style="color:#4b5563;"></p>
                            </div>
                            <input type="file" id="fileInput" name="file" class="d-none @error('file') is-invalid @enderror"
                                accept=".pdf,.doc,.docx,.xlsx">
                            @error('file')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn w-100 text-white fw-600"
                                style="background:linear-gradient(135deg,#667eea,#764ba2); border:none; padding:10px; border-radius:10px;">
                            <i class="bi bi-send me-2"></i>Kirim ke Owner
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Riwayat --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header border-0 rounded-top-4 py-3 px-4 bg-white border-bottom d-flex align-items-center justify-content-between">
                    <h6 class="mb-0 fw-600">
                        <i class="bi bi-clock-history me-2" style="color:#7c3aed;"></i>Riwayat Laporan Terkirim
                    </h6>
                    {{-- Per page selector --}}
                    <form method="GET" action="{{ request()->url() }}" class="d-flex align-items-center gap-2">
                        <label class="small text-muted mb-0" style="white-space:nowrap;">Tampilkan</label>
                        <select name="per_page" class="form-select form-select-sm"
                                style="width:70px; border-radius:8px; border-color:#e9d5ff; font-size:0.8rem;"
                                onchange="this.form.submit()">
                            @foreach([5, 10, 25, 50] as $size)
                                <option value="{{ $size }}" {{ request('per_page', 10) == $size ? 'selected' : '' }}>
                                    {{ $size }}
                                </option>
                            @endforeach
                        </select>
                        <span class="small text-muted mb-0">data</span>
                    </form>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead style="background:#f5f3ff;">
                                <tr>
                                    <th class="ps-4 py-3 small text-muted fw-600">#</th>
                                    <th class="py-3 small text-muted fw-600">Judul</th>
                                    <th class="py-3 small text-muted fw-600">Periode</th>
                                    <th class="py-3 small text-muted fw-600">Status</th>
                                    <th class="py-3 small text-muted fw-600">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reports as $report)
                                <tr>
                                    <td class="ps-4 small text-muted">{{ $loop->iteration + ($reports->currentPage() - 1) * $reports->perPage() }}</td>
                                    <td><span class="fw-600 small">{{ $report->title }}</span></td>
                                    <td class="small text-muted">{{ $report->periode }}</td>
                                    <td>
                                        @if($report->status === 'unread')
                                            <span class="badge rounded-pill" style="background:#f3e8ff;color:#7c3aed;font-size:0.7rem;padding:4px 10px;">
                                                <i class="bi bi-circle-fill me-1" style="font-size:0.4rem;"></i>Belum Dibaca
                                            </span>
                                        @else
                                            <span class="badge rounded-pill" style="background:#dcfce7;color:#16a34a;font-size:0.7rem;padding:4px 10px;">
                                                <i class="bi bi-check-circle me-1"></i>Dibaca
                                            </span>
                                        @endif
                                    </td>
                                    <td class="small text-muted">{{ $report->created_at->format('d M Y') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-2 d-block mb-2 opacity-50"></i>
                                        <span class="small">Belum ada laporan terkirim.</span>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Footer: info + pagination --}}
                @if($reports->hasPages() || $reports->total() > 0)
                <div class="card-footer bg-white border-top d-flex align-items-center justify-content-between py-3 px-4 flex-wrap gap-2">
                    <p class="mb-0 small text-muted">
                        Menampilkan
                        <span class="fw-600 text-dark">{{ $reports->firstItem() ?? 0 }}</span>–<span class="fw-600 text-dark">{{ $reports->lastItem() ?? 0 }}</span>
                        dari <span class="fw-600 text-dark">{{ $reports->total() }}</span> laporan
                    </p>
                    <div class="pagination-purple">
                        {{ $reports->appends(['per_page' => request('per_page', 10)])->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>

    </div>
</div>

<style>
.pagination-purple .pagination {
    margin: 0;
    gap: 4px;
}
.pagination-purple .page-link {
    border-radius: 8px !important;
    border: 1px solid #e9d5ff;
    color: #7c3aed;
    font-size: 0.8rem;
    padding: 5px 11px;
    line-height: 1.4;
}
.pagination-purple .page-link:hover {
    background: #f3e8ff;
    border-color: #c4b5fd;
    color: #6d28d9;
}
.pagination-purple .page-item.active .page-link {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-color: transparent;
    color: #fff;
}
.pagination-purple .page-item.disabled .page-link {
    background: #faf5ff;
    border-color: #ede9fe;
    color: #c4b5fd;
}
</style>

<script>
document.getElementById('fileInput').addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;

    const ext = file.name.split('.').pop().toLowerCase();
    const icons = { pdf: 'bi-file-earmark-pdf', doc: 'bi-file-earmark-word', docx: 'bi-file-earmark-word', xlsx: 'bi-file-earmark-excel' };
    const colors = { pdf: '#dc2626', doc: '#2563eb', docx: '#2563eb', xlsx: '#16a34a' };

    const area = document.getElementById('uploadArea');
    area.style.background = '#f0fdf4';
    area.style.borderColor = '#86efac';

    const icon = document.getElementById('uploadIcon');
    icon.className = `bi ${icons[ext] || 'bi-file-earmark'} fs-2`;
    icon.style.color = colors[ext] || '#4b5563';

    document.getElementById('uploadText').textContent = 'File terpilih:';
    document.getElementById('uploadHint').textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
    document.getElementById('fileName').textContent = file.name;
});
</script>
@endsection