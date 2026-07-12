@extends('admin.layouts.app')

@use('Illuminate\Support\Str')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-800 text-dark">Rekening Pajak (10%)</h4>
            <p class="text-secondary mb-0 small">Dana pajak yang disiapkan untuk disetorkan ke kas negara</p>
        </div>
    </div>

    @if(session('sukses'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('sukses') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <!-- Total Tax Collected -->
        <div class="col-xl-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="stat-card stat-card--blue h-100 mb-0">
                <div class="stat-card__icon">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <div class="stat-card__body">
                    <div class="stat-card__label">TOTAL PAJAK TERKUMPUL</div>
                    <div class="stat-card__value">Rp {{ number_format($totalTax, 0, ',', '.') }}</div>
                    <span class="stat-card__link" style="opacity: 0.8;">
                        <i class="bi bi-info-circle"></i> Keseluruhan dana pajak 10%
                    </span>
                </div>
            </div>
        </div>

        <!-- Tax Remitted -->
        <div class="col-xl-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="stat-card stat-card--green h-100 mb-0">
                <div class="stat-card__icon">
                    <i class="bi bi-check2-all"></i>
                </div>
                <div class="stat-card__body">
                    <div class="stat-card__label">SUDAH DISETOR</div>
                    <div class="stat-card__value">Rp {{ number_format($totalRemitted, 0, ',', '.') }}</div>
                    <span class="stat-card__link" style="opacity: 0.8;">
                        <i class="bi bi-bank"></i> Dana yang sudah disetorkan ke negara
                    </span>
                </div>
            </div>
        </div>

        <!-- Tax Pending -->
        <div class="col-xl-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="stat-card stat-card--orange h-100 mb-0">
                <div class="stat-card__icon">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div class="stat-card__body">
                    <div class="stat-card__label">BELUM DISETOR</div>
                    <div class="stat-card__value">Rp {{ number_format($totalPending, 0, ',', '.') }}</div>
                    <span class="stat-card__link" style="opacity: 0.8;">
                        <i class="bi bi-exclamation-triangle"></i> Dana tertahan di rekening platform
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="card border-0 shadow-sm mb-4" data-aos="fade-up" data-aos-delay="400">
        <div class="card-body p-4">
            <form action="{{ route('admin.finance.tax') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-700 small text-secondary text-uppercase">Cari Transaksi</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control border-0 bg-light" placeholder="Kode Booking atau Nama Event..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-700 small text-secondary text-uppercase">Status Penyetoran</label>
                        <select name="status" class="form-select border-0 bg-light" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Belum Disetor</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Sudah Disetor</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-700 small text-secondary text-uppercase">Tampilkan</label>
                        <select name="per_page" class="form-select border-0 bg-light" onchange="this.form.submit()">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 Baris</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 Baris</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 Baris</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4 rounded-3 flex-grow-1 fw-700" style="background-color: #7c3aed;">Terapkan</button>
                        <a href="{{ route('admin.finance.tax') }}" class="btn btn-light px-3 rounded-3 fw-700">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card border-0 shadow-sm overflow-hidden" data-aos="fade-up" data-aos-delay="500">
        <div class="card-header bg-white border-0 py-4 ps-4">
            <h6 class="mb-0 fw-800 text-dark"><i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Pajak Masuk</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr class="bg-light">
                        <th class="border-0 ps-4 py-3 text-secondary small fw-700 text-uppercase">Event</th>
                        <th class="border-0 py-3 text-secondary small fw-700 text-uppercase text-center">Jumlah Booking</th>
                        <th class="border-0 py-3 text-secondary small fw-700 text-uppercase text-end">Total Harga Dasar</th>
                        <th class="border-0 py-3 text-secondary small fw-700 text-uppercase text-end">Total Pajak (10%)</th>
                        <th class="border-0 py-3 text-secondary small fw-700 text-uppercase text-center">Status</th>
                        <th class="border-0 py-3 text-secondary small fw-700 text-uppercase text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($eventTaxPaginated as $row)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-700 text-dark">{{ Str::limit($row['event']->title ?? '-', 35) }}</div>
                                <small class="text-secondary"><i class="bi bi-person me-1"></i>{{ $row['vendor']->name ?? '-' }}</small>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark rounded-pill px-3 py-2 fw-700">{{ $row['booking_count'] }} Transaksi</span>
                            </td>
                            <td class="text-end fw-600 text-secondary">
                                Rp {{ number_format($row['total_base'], 0, ',', '.') }}
                            </td>
                            <td class="text-end fw-800 text-dark">
                                Rp {{ number_format($row['total_tax'], 0, ',', '.') }}
                            </td>
                            <td class="text-center">
                                @if($row['status'] === 'paid')
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 fw-700">Sudah Disetor</span>
                                @elseif($row['status'] === 'partial')
                                    <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2 fw-700">{{ $row['remitted_count'] }}/{{ $row['booking_count'] }} Disetor</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2 fw-700">Belum Disetor</span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                @if($row['status'] !== 'paid')
                                    <button type="button" class="btn btn-sm btn-primary fw-700 px-3 rounded-pill"
                                        data-event-id="{{ $row['event_id'] }}"
                                        data-event-title="{{ $row['event']->title ?? '-' }}"
                                        onclick="uploadReceiptEvent(this)">Setor</button>
                                @else
                                    <button type="button" class="btn btn-sm btn-success fw-700 px-3 rounded-pill"
                                        data-event-id="{{ $row['event_id'] }}"
                                        onclick="viewReceiptEvent(this)">
                                        <i class="bi bi-receipt me-1"></i>Lihat Bukti
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-secondary opacity-50 mb-3"><i class="bi bi-inbox fs-1"></i></div>
                                <p class="text-secondary mb-0 fw-600">Belum ada riwayat pajak masuk.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($eventTaxPaginated->hasPages())
        <div class="card-footer bg-white border-0 py-4 px-4">
            {{ $eventTaxPaginated->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>

<style>
    .fw-800 { font-weight: 800; }
    .fw-700 { font-weight: 700; }
    .fw-600 { font-weight: 600; }
</style>

<script>
    function uploadReceiptEvent(button) {
        const eventId = button.dataset.eventId;
        const eventTitle = button.dataset.eventTitle;

        Swal.fire({
            title: 'Upload Bukti Setor',
            text: 'Upload bukti transfer untuk SEMUA transaksi belum disetor pada event: ' + eventTitle,
            input: 'file',
            inputAttributes: {
                'accept': 'image/*,application/pdf',
                'aria-label': 'Upload bukti setor pajak'
            },
            showCancelButton: true,
            confirmButtonText: 'Setor Pajak',
            cancelButtonText: 'Batal',
            showLoaderOnConfirm: true,
            preConfirm: (file) => {
                if (!file) {
                    Swal.showValidationMessage('Anda harus memilih file bukti transfer!');
                    return false;
                }

                let formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('tax_receipt', file);

                return fetch(`{{ url('admin/finance/tax') }}/${eventId}/remit-event`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw new Error(err.message || response.statusText) });
                    }
                    return response.json();
                })
                .catch(error => {
                    Swal.showValidationMessage(`Gagal: ${error}`);
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed && result.value.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: result.value.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.reload();
                });
            }
        });
    }
    function viewReceiptEvent(button) {
    const eventId = button.dataset.eventId;
  
    fetch(`{{ url('admin/finance/tax') }}/${eventId}/receipt`, {
        headers: { 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        if (!data.url) {
            Swal.fire('Bukti Tidak Ditemukan', 'File bukti setor tidak tersedia.', 'warning');
            return;
        }

        const isImage = /\.(jpg|jpeg|png|webp|gif)$/i.test(data.url);
        const isPdf   = /\.pdf$/i.test(data.url);

        Swal.fire({
            title: 'Bukti Setor Pajak',
            html: isImage
                ? `<img src="${data.url}" class="img-fluid rounded" style="max-height:400px;">`
                : `<a href="${data.url}" target="_blank" class="btn btn-primary">
                       <i class="bi bi-file-earmark-pdf me-1"></i>Buka PDF
                   </a>`,
            showConfirmButton: false,
            showCloseButton: true,
            width: isImage ? '600px' : '400px',
        });
    })
    .catch(() => {
        Swal.fire('Error', 'Gagal mengambil data bukti setor.', 'error');
    });
}
</script>
@endsection
