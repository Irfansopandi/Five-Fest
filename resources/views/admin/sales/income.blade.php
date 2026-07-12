@extends('admin.layouts.app')

@section('title', 'Penghasilan Vendor')

@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-800 text-dark mb-1">Pendapatan Platform</h2>
            <p class="text-secondary mb-0">Rincian pendapatan tiket dan merchandise beserta perhitungan pajak dan layanan per vendor.</p>
        </div>
        @if($totalPendingWithdrawals > 0)
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-warning text-dark rounded-pill px-3 py-2 fw-700 d-flex align-items-center gap-2" style="font-size: 0.85rem;">
                <i class="bi bi-exclamation-triangle-fill"></i>
                {{ $totalPendingWithdrawals }} Penarikan Menunggu Konfirmasi
            </span>
        </div>
        @endif
    </div>

    {{-- Stats Row (Platform Wide) --}}
    <div class="row g-4 mb-5">
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
            <div class="stat-card stat-card--blue">
                <div class="stat-card__icon">
                    <i class="bi bi-wallet2"></i>
                </div>
                <div class="stat-card__body">
                    <div class="stat-card__label">Total Transaksi</div>
                    <div class="stat-card__value" style="font-size: 1.25rem;">Rp {{ number_format($globalGross, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="200">
            <div class="stat-card stat-card--red">
                <div class="stat-card__icon">
                    <i class="bi bi-bank"></i>
                </div>
                <div class="stat-card__body">
                    <div class="stat-card__label">Total Pajak (10%)</div>
                    <div class="stat-card__value" style="font-size: 1.25rem;">Rp {{ number_format($globalTax, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="300">
            <div class="stat-card stat-card--orange">
                <div class="stat-card__icon">
                    <i class="bi bi-briefcase"></i>
                </div>
                <div class="stat-card__body">
                    <div class="stat-card__label">Jasa Layanan (3%)</div>
                    <div class="stat-card__value" style="font-size: 1.25rem;">Rp {{ number_format($globalService, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="400">
            <div class="stat-card stat-card--green">
                <div class="stat-card__icon">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <div class="stat-card__body">
                    <div class="stat-card__label">Total Bersih Vendor</div>
                    <div class="stat-card__value" style="font-size: 1.25rem;">Rp {{ number_format($globalNet, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tab Navigation --}}
    <div class="d-flex justify-content-start mb-4" data-aos="fade-up" data-aos-delay="100">
        <ul class="nav nav-pills p-1.5 bg-light rounded-pill gap-1 mb-0 border" style="padding: 6px;" id="incomeTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link rounded-pill px-4 py-2 fw-bold @if($activeTab === 'pendapatan') active @endif" id="pendapatan-tab" data-bs-toggle="pill" data-bs-target="#pendapatan-content" type="button" role="tab">
                    <i class="bi bi-shop me-2"></i>Pendapatan Vendor
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link rounded-pill px-4 py-2 fw-bold @if($activeTab === 'riwayat') active @endif" id="riwayat-tab" data-bs-toggle="pill" data-bs-target="#riwayat-content" type="button" role="tab">
                    <i class="bi bi-clock-history me-2"></i>Riwayat Penarikan
                    @if($totalPendingWithdrawals > 0)
                    <span class="badge bg-danger rounded-pill ms-1" style="font-size: 0.65rem;">{{ $totalPendingWithdrawals }}</span>
                    @endif
                </button>
            </li>
        </ul>
    </div>

    {{-- Tab Contents --}}
    <div class="tab-content" id="incomeTabsContent">

        {{-- ================= TAB 1: PENDAPATAN VENDOR ================= --}}
        <div class="tab-pane fade @if($activeTab === 'pendapatan') show active @endif" id="pendapatan-content" role="tabpanel">
            {{-- Filter --}}
            <div class="card border-0 shadow-sm mb-4" data-aos="fade-up" data-aos-delay="500">
                <div class="card-body p-4">
                    <form action="{{ route('admin.income') }}" method="GET">
                        <input type="hidden" name="tab" value="pendapatan">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-5">
                                <label class="form-label fw-700 small text-secondary text-uppercase">Cari Vendor</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
                                    <input type="text" name="search" class="form-control border-0 bg-light" placeholder="Ketik nama atau email vendor..." value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-700 small text-secondary text-uppercase">Tampilkan</label>
                                <select name="per_page" class="form-select border-0 bg-light" onchange="this.form.submit()">
                                    <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5 Baris</option>
                                    <option value="10" {{ request('per_page', 5) == 10 ? 'selected' : '' }}>10 Baris</option>
                                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 Baris</option>
                                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 Baris</option>
                                </select>
                            </div>
                            <div class="col-md-4 d-flex gap-2">
                                <button type="submit" class="btn btn-primary px-4 rounded-3 flex-grow-1 fw-700" style="background-color: #7c3aed;">Terapkan</button>
                                <a href="{{ route('admin.income', ['tab' => 'pendapatan']) }}" class="btn btn-light px-3 rounded-3 fw-700">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Table --}}
            <div class="card border-0 shadow-sm overflow-hidden" data-aos="fade-up" data-aos-delay="600">
                <div class="card-header bg-white border-0 py-4 ps-4">
                    <h5 class="fw-800 mb-0 text-dark"><i class="bi bi-shop me-2 "style="color: #7c3aed;"></i>Rincian Pendapatan per Vendor</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr class="bg-light">
                                <th class="border-0 ps-4 py-3 text-secondary small fw-700 text-uppercase">Vendor</th>
                                <th class="border-0 py-3 text-secondary small fw-700 text-uppercase text-center">Penjualan</th>
                                <th class="border-0 py-3 text-secondary small fw-700 text-uppercase text-end">Total Transaksi</th>
                                <th class="border-0 py-3 text-secondary small fw-700 text-uppercase text-end">Pajak (10%)</th>
                                <th class="border-0 py-3 text-secondary small fw-700 text-uppercase text-end">Jasa Layanan (3%)</th>
                                <th class="border-0 py-3 text-secondary small fw-700 text-uppercase text-end">Saldo Vendor</th>
                                <th class="border-0 py-3 text-secondary small fw-700 text-uppercase text-center pe-4">Penarikan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vendorIncomes as $index => $v)
                            <tr>
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-primary text-white rounded-4 d-flex align-items-center justify-content-center fw-800" style="width: 44px; height: 44px; font-size: 0.9rem;">
                                            {{ strtoupper(substr($v['vendor']->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-700 text-dark">{{ $v['vendor']->name }}</h6>
                                            <small class="text-secondary">{{ $v['vendor']->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center py-3">
                                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 fw-700 mb-1">
                                        {{ number_format($v['tickets_sold']) }} Tiket
                                    </span>
                                    @if($v['booths_sold'] > 0)
                                    <br>
                                    <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3 py-2 fw-700 mt-1">
                                        {{ number_format($v['booths_sold']) }} Booth
                                    </span>
                                    @endif
                                </td>
                                <td class="text-end fw-600 text-dark py-3">
                                    Rp {{ number_format($v['gross_income'], 0, ',', '.') }}
                                </td>
                                <td class="text-end fw-600 text-danger py-3">
                                    Rp {{ number_format($v['tax'], 0, ',', '.') }}
                                </td>
                                <td class="text-end fw-600 text-warning py-3">
                                    Rp {{ number_format($v['service_fee'], 0, ',', '.') }}
                                </td>
                                <td class="text-end py-3">
                                    <span class="fw-800 text-success">Rp {{ number_format($v['remaining_balance'], 0, ',', '.') }}</span>
                                    @if($v['total_withdrawn'] > 0)
                                    <div class="mt-1">
                                        <small class="text-secondary" style="font-size: 0.7rem;">
                                            <i class="bi bi-arrow-up-right text-danger me-1"></i>Telah ditarik: Rp {{ number_format($v['total_withdrawn'], 0, ',', '.') }}
                                        </small>
                                    </div>
                                    @endif
                                </td>
                                <td class="text-center pe-4 py-3">
                                    @if($v['pending_withdrawals']->count() > 0)
                                        <button type="button" 
                                            class="btn btn-sm withdrawal-confirm-btn"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#withdrawalModal{{ $index }}">
                                            <span class="withdrawal-confirm-btn__count">{{ $v['pending_withdrawals']->count() }}</span>
                                            <span>Konfirmasi</span>
                                        </button>
                                    @else
                                        <span class="text-secondary small fw-600 d-inline-flex align-items-center gap-1">
                                            <i class="bi bi-check-circle text-success"></i> Tidak ada
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-secondary opacity-50 mb-3"><i class="bi bi-wallet2 fs-1"></i></div>
                                    <p class="text-secondary mb-0">Belum ada data penghasilan vendor.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($vendors->hasPages())
                <div class="card-footer bg-white border-0 py-4 px-4">
                    {{ $vendors->links('pagination::bootstrap-5') }}
                </div>
                @endif
            </div>
        </div>

        {{-- ================= TAB 2: RIWAYAT PENARIKAN ================= --}}
        <div class="tab-pane fade @if($activeTab === 'riwayat') show active @endif" id="riwayat-content" role="tabpanel">
            <div class="card border-0 shadow-sm overflow-hidden" data-aos="fade-up" data-aos-delay="300">
                <div class="card-header bg-white border-0 py-4 ps-4">
                    <h5 class="fw-800 mb-0 text-dark"><i class="bi bi-clock-history me-2" style="color: #7c3aed"></i>Riwayat Penarikan Dana per Vendor</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr class="bg-light">
                                <th class="border-0 ps-4 py-3 text-secondary small fw-700 text-uppercase">Vendor</th>
                                <th class="border-0 py-3 text-secondary small fw-700 text-uppercase text-end">Total Saldo</th>
                                <th class="border-0 py-3 text-secondary small fw-700 text-uppercase text-end">Total Penarikan</th>
                                <th class="border-0 py-3 text-secondary small fw-700 text-uppercase text-end">Sisa Saldo</th>
                                <th class="border-0 py-3 text-secondary small fw-700 text-uppercase text-center pe-4">Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $hasAnyWithdrawal = false; @endphp
                            @foreach($vendorIncomes as $index => $v)
                                @if($v['all_withdrawals']->count() > 0)
                                @php $hasAnyWithdrawal = true; @endphp
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="bg-primary text-white rounded-4 d-flex align-items-center justify-content-center fw-800" style="width: 44px; height: 44px; font-size: 0.9rem;">
                                                {{ strtoupper(substr($v['vendor']->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-700 text-dark">{{ $v['vendor']->name }}</h6>
                                                <small class="text-secondary">{{ $v['vendor']->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end fw-600 text-dark py-3">
                                        Rp {{ number_format($v['net_income'], 0, ',', '.') }}
                                    </td>
                                    <td class="text-end py-3">
                                        <span class="fw-700 text-danger">Rp {{ number_format($v['total_withdrawn'], 0, ',', '.') }}</span>
                                        @if($v['total_pending_wd'] > 0)
                                        <br>
                                        <small style="font-size: 0.72rem; color: #f59e0b;">
                                            <i class="bi bi-clock-fill me-1"></i>Pending: Rp {{ number_format($v['total_pending_wd'], 0, ',', '.') }}
                                        </small>
                                        @endif
                                    </td>
                                    <td class="text-end py-3">
                                        <span class="fw-800 text-success">Rp {{ number_format($v['remaining_balance'], 0, ',', '.') }}</span>
                                    </td>
                                    <td class="text-center pe-4 py-3">
                                        <button class="btn btn-sm btn-light rounded-3 fw-600 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#historyCollapse{{ $index }}">
                                            <i class="bi bi-chevron-down me-1"></i>{{ $v['all_withdrawals']->count() }} Transaksi
                                        </button>
                                    </td>
                                </tr>
                                {{-- Collapsible detail rows --}}
                                <tr class="collapse" id="historyCollapse{{ $index }}">
                                    <td colspan="5" class="p-0 border-0">
                                        <div class="px-4 py-3" style="background: #f8fafc;">
                                            <table class="table table-sm align-middle mb-0" style="background: white; border-radius: 10px; overflow: hidden;">
                                                <thead>
                                                    <tr>
                                                        <th class="border-0 ps-3 py-2 text-secondary small fw-700 text-uppercase" style="font-size: 0.7rem;">Tanggal</th>
                                                        <th class="border-0 py-2 text-secondary small fw-700 text-uppercase text-end" style="font-size: 0.7rem;">Jumlah</th>
                                                        <th class="border-0 py-2 text-secondary small fw-700 text-uppercase" style="font-size: 0.7rem;">Bank</th>
                                                        <th class="border-0 py-2 text-secondary small fw-700 text-uppercase" style="font-size: 0.7rem;">No. Rekening</th>
                                                        <th class="border-0 py-2 text-secondary small fw-700 text-uppercase" style="font-size: 0.7rem;">Atas Nama</th>
                                                        <th class="border-0 py-2 text-secondary small fw-700 text-uppercase text-center" style="font-size: 0.7rem;">Status</th>
                                                        <th class="border-0 pe-3 py-2 text-secondary small fw-700 text-uppercase" style="font-size: 0.7rem;">Catatan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($v['all_withdrawals'] as $wd)
                                                    <tr>
                                                        <td class="ps-3 py-2">
                                                            <div class="fw-600 small">{{ $wd->created_at->format('d M Y') }}</div>
                                                            <small class="text-secondary" style="font-size: 0.7rem;">{{ $wd->created_at->format('H:i') }} WIB</small>
                                                        </td>
                                                        <td class="text-end py-2">
                                                            <span class="fw-700 small">Rp {{ number_format($wd->amount, 0, ',', '.') }}</span>
                                                        </td>
                                                        <td class="py-2"><span class="small fw-600">{{ $wd->bank_name }}</span></td>
                                                        <td class="py-2"><code class="small">{{ $wd->account_number }}</code></td>
                                                        <td class="py-2"><span class="small">{{ $wd->account_holder }}</span></td>
                                                        <td class="text-center py-2">
                                                            @if($wd->status === 'approved')
                                                                <span class="badge rounded-pill px-2 py-1 fw-700" style="background: rgba(16,185,129,0.12); color: #059669; font-size: 0.7rem;">
                                                                    <i class="bi bi-check-circle-fill me-1"></i>Disetujui
                                                                </span>
                                                            @elseif($wd->status === 'pending')
                                                                <span class="badge rounded-pill px-2 py-1 fw-700" style="background: rgba(245,158,11,0.12); color: #b45309; font-size: 0.7rem;">
                                                                    <i class="bi bi-clock-fill me-1"></i>Pending
                                                                </span>
                                                            @else
                                                                <span class="badge rounded-pill px-2 py-1 fw-700" style="background: rgba(239,68,68,0.12); color: #dc2626; font-size: 0.7rem;">
                                                                    <i class="bi bi-x-circle-fill me-1"></i>Ditolak
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td class="pe-3 py-2">
                                                            <small class="text-secondary" style="font-size: 0.72rem;">{{ $wd->notes ?? '-' }}</small>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                            @if(!$hasAnyWithdrawal)
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="text-secondary opacity-50 mb-3"><i class="bi bi-inbox fs-1"></i></div>
                                    <p class="text-secondary mb-0 fw-600">Belum ada riwayat penarikan dana.</p>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Modals for each vendor with pending withdrawals --}}
@foreach($vendorIncomes as $index => $v)
    @if($v['pending_withdrawals']->count() > 0)
    <div class="modal fade" id="withdrawalModal{{ $index }}" tabindex="-1" aria-labelledby="withdrawalModalLabel{{ $index }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 rounded-4 overflow-hidden">
                {{-- Header --}}
                <div class="modal-header border-0 px-4 pt-4 pb-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div>
                        <h5 class="modal-title fw-800 text-white mb-1" id="withdrawalModalLabel{{ $index }}">
                            <i class="bi bi-cash-coin me-2"></i>Konfirmasi Penarikan Dana
                        </h5>
                        <p class="text-white mb-0 small" style="opacity: 0.85;">
                            {{ $v['vendor']->name }} — {{ $v['pending_withdrawals']->count() }} pengajuan menunggu
                        </p>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                {{-- Body --}}
                <div class="modal-body px-4 py-4">
                    @foreach($v['pending_withdrawals'] as $wIndex => $withdrawal)
                    <div class="withdrawal-card mb-3 {{ !$loop->last ? '' : '' }}">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <span class="badge rounded-pill px-3 py-2 fw-700" style="background: rgba(245, 158, 11, 0.15); color: #b45309;">
                                    <i class="bi bi-clock me-1"></i>Menunggu Konfirmasi
                                </span>
                            </div>
                            <div class="text-end">
                                <div class="fw-800 text-dark" style="font-size: 1.25rem;">
                                    Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}
                                </div>
                                <small class="text-secondary">
                                    <i class="bi bi-calendar3 me-1"></i>{{ $withdrawal->created_at->format('d M Y, H:i') }} WIB
                                </small>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-sm-4">
                                <div class="info-block">
                                    <div class="info-block__label">
                                        <i class="bi bi-bank me-1"></i>Nama Bank
                                    </div>
                                    <div class="info-block__value">{{ $withdrawal->bank_name }}</div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="info-block">
                                    <div class="info-block__label">
                                        <i class="bi bi-credit-card me-1"></i>No. Rekening
                                    </div>
                                    <div class="info-block__value">{{ $withdrawal->account_number }}</div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="info-block">
                                    <div class="info-block__label">
                                        <i class="bi bi-person me-1"></i>Atas Nama
                                    </div>
                                    <div class="info-block__value">{{ $withdrawal->account_holder }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 justify-content-end">
                            <button type="button" 
                                class="btn btn-outline-danger btn-sm px-3 py-2 rounded-3 fw-700 d-inline-flex align-items-center gap-1 btn-reject"
                                data-id="{{ $withdrawal->id }}"
                                data-vendor="{{ $v['vendor']->name }}"
                                data-amount="{{ $withdrawal->amount }}">
                                <i class="bi bi-x-circle"></i> Tolak
                            </button>
                            <button type="button" 
                                class="btn btn-success btn-sm px-3 py-2 rounded-3 fw-700 d-inline-flex align-items-center gap-1 btn-approve"
                                data-id="{{ $withdrawal->id }}"
                                data-vendor="{{ $v['vendor']->name }}"
                                data-amount="{{ $withdrawal->amount }}">
                                <i class="bi bi-check-circle"></i> Setujui
                            </button>
                        </div>

                        @if(!$loop->last)
                        <hr class="my-3" style="border-color: #e2e8f0;">
                        @endif
                    </div>
                    @endforeach
                </div>

                {{-- Footer --}}
                <div class="modal-footer border-0 bg-light px-4 py-3">
                    <small class="text-secondary me-auto">
                        <i class="bi bi-info-circle me-1"></i>Pastikan dana sudah ditransfer sebelum menyetujui penarikan.
                    </small>
                    <button type="button" class="btn btn-light rounded-3 fw-700 px-4" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endif
@endforeach

{{-- Hidden forms for approve/reject --}}
@foreach($vendorIncomes as $v)
    @foreach($v['pending_withdrawals'] as $withdrawal)
        <form id="approveForm{{ $withdrawal->id }}" action="{{ route('admin.withdrawal.approve', $withdrawal->id) }}" method="POST" class="d-none">
            @csrf
            @method('PATCH')
        </form>
        <form id="rejectForm{{ $withdrawal->id }}" action="{{ route('admin.withdrawal.reject', $withdrawal->id) }}" method="POST" class="d-none">
            @csrf
            @method('PATCH')
            <input type="hidden" name="reject_reason" id="rejectReason{{ $withdrawal->id }}">
        </form>
    @endforeach
@endforeach

<style>
    .fw-800 { font-weight: 800; }
    .fw-700 { font-weight: 700; }
    .fw-600 { font-weight: 600; }
    
    .withdrawal-confirm-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        font-size: 0.78rem;
        font-weight: 600;
        padding: 6px 14px 6px 8px;
        border-radius: 20px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }
    .withdrawal-confirm-btn:hover {
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(102, 126, 234, 0.45);
    }
    .withdrawal-confirm-btn__count {
        background: rgba(255,255,255,0.25);
        width: 22px;
        height: 22px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.72rem;
        font-weight: 800;
        flex-shrink: 0;
    }

    .withdrawal-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 20px;
        transition: all 0.2s ease;
    }
    .withdrawal-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }

    .info-block {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 12px;
    }
    .info-block__label {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #94a3b8;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }
    .info-block__value {
        font-size: 0.9rem;
        font-weight: 700;
        color: #1e293b;
    }

    .modal-content {
        box-shadow: 0 25px 60px rgba(0,0,0,0.15);
    }

    /* ===== TAB STYLING ===== */
    #incomeTabs {
        background-color: #f8fafc !important;
        border: 1px solid #e2e8f0 !important;
    }
    #incomeTabs .nav-link {
        color: #64748b !important;
        font-size: 0.9rem;
        transition: all 0.25s ease;
        background: transparent !important;
        border: none !important;
    }
    #incomeTabs .nav-link.active {
        background-color: #fff !important;
        color: #7c3aed !important; /* Admin purple brand color */
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06) !important;
    }
    #incomeTabs .nav-link:hover:not(.active) {
        color: #1e293b !important;
    }
</style>

@push('scripts')
<script>
    // Handle reject button click
    document.querySelectorAll('.btn-reject').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const vendor = this.dataset.vendor;
            const amount = parseInt(this.dataset.amount);
            rejectWithdrawal(id, vendor, amount);
        });
    });

    // Handle approve button click
    document.querySelectorAll('.btn-approve').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const vendor = this.dataset.vendor;
            const amount = parseInt(this.dataset.amount);
            approveWithdrawal(id, vendor, amount);
        });
    });

    function approveWithdrawal(id, vendorName, amount) {
        const formattedAmount = new Intl.NumberFormat('id-ID').format(amount);
        
        // Close all Bootstrap modals first to prevent focus trap conflict
        document.querySelectorAll('.modal.show').forEach(function(modal) {
            var bsModal = bootstrap.Modal.getInstance(modal);
            if (bsModal) bsModal.hide();
        });

        setTimeout(function() {
            Swal.fire({
                title: 'Setujui Penarikan?',
                html: `
                    <div class="text-start">
                        <p class="mb-2">Anda akan menyetujui penarikan dana:</p>
                        <div style="background: #f0fdf4; border: 1px solid #86efac; border-radius: 10px; padding: 12px; margin: 8px 0;">
                            <div style="font-weight: 700; color: #166534; font-size: 1.1rem;">Rp ${formattedAmount}</div>
                            <div style="color: #15803d; font-size: 0.85rem;">untuk ${vendorName}</div>
                        </div>
                        <p class="mb-0 text-secondary small mt-2"><i class="bi bi-info-circle me-1"></i>Pastikan dana sudah ditransfer ke rekening vendor sebelum menyetujui.</p>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#64748b',
                confirmButtonText: '<i class="bi bi-check-circle me-1"></i> Ya, Setujui',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('approveForm' + id).submit();
                }
            });
        }, 300);
    }

    function rejectWithdrawal(id, vendorName, amount) {
        const formattedAmount = new Intl.NumberFormat('id-ID').format(amount);
        
        // Close all Bootstrap modals first to prevent focus trap conflict
        document.querySelectorAll('.modal.show').forEach(function(modal) {
            var bsModal = bootstrap.Modal.getInstance(modal);
            if (bsModal) bsModal.hide();
        });

        setTimeout(function() {
            Swal.fire({
                title: 'Tolak Penarikan?',
                html: `
                    <div class="text-start">
                        <p class="mb-2">Anda akan menolak penarikan dana:</p>
                        <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 10px; padding: 12px; margin: 8px 0;">
                            <div style="font-weight: 700; color: #991b1b; font-size: 1.1rem;">Rp ${formattedAmount}</div>
                            <div style="color: #dc2626; font-size: 0.85rem;">dari ${vendorName}</div>
                        </div>
                        <label for="swal-reject-reason" class="form-label fw-bold small text-secondary mt-2">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea id="swal-reject-reason" class="form-control" rows="3" placeholder="Contoh: Data rekening tidak valid, saldo belum cukup, dll."></textarea>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: '<i class="bi bi-x-circle me-1"></i> Ya, Tolak',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                didOpen: () => {
                    // Ensure textarea is focusable after modal is fully gone
                    const textarea = document.getElementById('swal-reject-reason');
                    if (textarea) textarea.focus();
                },
                preConfirm: () => {
                    const reason = document.getElementById('swal-reject-reason').value.trim();
                    if (!reason) {
                        Swal.showValidationMessage('Alasan penolakan wajib diisi!');
                        return false;
                    }
                    return reason;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('rejectReason' + id).value = result.value;
                    document.getElementById('rejectForm' + id).submit();
                }
            });
        }, 300);
    }
</script>
@endpush
@endsection
