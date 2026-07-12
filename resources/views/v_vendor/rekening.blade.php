@extends('v_vendor.v_layouts.app')

@section('title', 'Rekening & Penarikan Dana')

@section('content')
<div class="container-fluid px-4 py-4">

    <!-- Header -->
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: #1e1b4b;">Rekening & Penarikan Dana</h4>
            <p class="text-muted small mb-0">Kelola pendapatan bersih per event dan lakukan penarikan dana langsung dari masing-masing event</p>
        </div>
        <div class="d-flex align-items-center gap-2 mt-2 p-3 rounded-4 border bg-warning-subtle" style="max-width: 600px;">
            <i class="bi bi-info-circle-fill text-warning fs-5 flex-shrink-0"></i>
            <span class="small text-warning-emphasis fw-medium">
                Minimal penarikan dana adalah <strong>Rp 100.000</strong> per pengajuan. <br>
                Penarikan hanya bisa dilakukan pada <br><strong>Tahap 1</strong> (H-14 s/d H-9, maks. <strong>70%.</strong>) <br> <strong>Tahap 2</strong> (H+1 setelah acara, maks. <strong>sisa 100%.</strong>).
            </span>
        </div>
    </div>

    <!-- Alert Success / Error -->
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 alert-dismissible fade show p-3 mb-4" role="alert">
            <div class="d-flex align-items-center gap-2 text-success">
                <i class="bi bi-check-circle-fill fs-5"></i>
                <div class="fw-medium small">{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm rounded-4 alert-dismissible fade show p-3 mb-4" role="alert">
            <div class="d-flex align-items-center gap-2 text-danger">
                <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                <div class="fw-medium small">{{ session('error') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm rounded-4 alert-dismissible fade show p-3 mb-4" role="alert">
            <div class="d-flex flex-column gap-1 text-danger small">
                @foreach ($errors->all() as $error)
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-x-circle-fill"></i>
                        <span>{{ $error }}</span>
                    </div>
                @endforeach
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="row g-3 mb-4" data-aos="fade-up">
        <div class="col-lg-4 col-md-6">
            <div class="stat-card stat-card--balance h-100 mb-0">
                <div class="stat-card__icon">
                    <i class="bi bi-wallet2"></i>
                </div>
                <div class="stat-card__body">
                    <div class="stat-card__label">SALDO TERSEDIA</div>
                    <div class="stat-card__value">Rp {{ number_format($availableBalance, 0, ',', '.') }}</div>
                    <div class="stat-card__subtext"><i class="bi bi-info-circle me-1"></i>Siap dicairkan ke rekening Anda</div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="stat-card stat-card--revenue h-100 mb-0">
                <div class="stat-card__icon">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
                <div class="stat-card__body">
                    <div class="stat-card__label">TOTAL PENDAPATAN BERSH</div>
                    <div class="stat-card__value">Rp {{ number_format($totalNetRevenue, 0, ',', '.') }}</div>
                    <div class="stat-card__subtext"><i class="bi bi-check2-all me-1"></i>Akumulasi bersih semua event</div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-12">
            <div class="stat-card stat-card--withdrawn h-100 mb-0">
                <div class="stat-card__icon">
                    <i class="bi bi-cash-coin"></i>
                </div>
                <div class="stat-card__body">
                    <div class="stat-card__label">TOTAL TELAH DITARIK</div>
                    <div class="stat-card__value">Rp {{ number_format($totalWithdrawn, 0, ',', '.') }}</div>
                    @if($totalPending > 0)
                        <div class="stat-card__subtext text-warning-sub"><i class="bi bi-hourglass-split me-1"></i>Rp {{ number_format($totalPending, 0, ',', '.') }} sedang diproses</div>
                    @else
                        <div class="stat-card__subtext"><i class="bi bi-arrow-up-right-circle me-1"></i>Pencairan dana sukses</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="d-flex justify-content-start mb-4" data-aos="fade-up" data-aos-delay="100">
        <ul class="nav nav-pills p-1.5 bg-light rounded-pill gap-1 mb-0 border" style="padding: 6px;" id="rekeningTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link rounded-pill px-4 py-2 fw-bold active" id="financial-tab" data-bs-toggle="pill" data-bs-target="#financial-pane" type="button" role="tab" aria-controls="financial-pane" aria-selected="true">
                    <i class="bi bi-graph-up-arrow me-2"></i>Rincian Finansial per Event
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link rounded-pill px-4 py-2 fw-bold" id="withdrawal-tab" data-bs-toggle="pill" data-bs-target="#withdrawal-pane" type="button" role="tab" aria-controls="withdrawal-pane" aria-selected="false">
                    <i class="bi bi-cash-coin me-2"></i>Riwayat Penarikan Dana
                </button>
            </li>
        </ul>
    </div>

    <div class="tab-content" id="rekeningTabContent" data-aos="fade-up" data-aos-delay="200">
        <!-- Tab 1: Rincian Finansial per Event -->
        <div class="tab-pane fade show active" id="financial-pane" role="tabpanel" aria-labelledby="financial-tab">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h5 class="fw-bold mb-1" style="color: #1e1b4b;">Rincian Finansial per Event</h5>
                        <p class="text-muted small mb-0">Rincian pendapatan bersih dan penjualan tiket/booth per event. Tarik dana langsung dari tombol "Tarik" di tiap baris.</p>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Pagination Config / Page Selector -->
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3 bg-light p-3 rounded-4 border">
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted small text-nowrap fw-semibold">Tampilkan:</span>
                            <select class="form-select form-select-sm rounded-3 border-secondary-subtle" style="width: auto; cursor: pointer; min-width: 80px;" id="perPageEvent">
                                <option value="5" selected>5</option>
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="all">Semua</option>
                            </select>
                            <span class="text-muted small">data per halaman</span>
                        </div>
                        <div class="small text-secondary fw-semibold" id="paginationInfoEvent">
                            Menampilkan 0-0 dari 0 data
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="tableEvent">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-3 py-3 text-muted small fw-bold text-uppercase ls-1">Event</th>
                                    <th class="py-3 text-muted small fw-bold text-uppercase ls-1 text-center">Tiket Terjual</th>
                                    <th class="py-3 text-muted small fw-bold text-uppercase ls-1 text-end">Bersih Tiket</th>
                                    <th class="py-3 text-muted small fw-bold text-uppercase ls-1 text-center">Tenant Booth</th>
                                    <th class="py-3 text-muted small fw-bold text-uppercase ls-1 text-end">Bersih Booth</th>
                                    <th class="pe-3 py-3 text-muted small fw-bold text-uppercase ls-1 text-end">Total Bersih</th>
                                    <th class="pe-3 py-3 text-muted small fw-bold text-uppercase ls-1 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($eventBreakdowns as $breakdown)
                                <tr class="event-row">
                                    <td class="ps-3">
                                        <div class="fw-bold text-dark small mb-0.5">{{ $breakdown['event']->title }}</div>
                                        <div class="text-muted small d-flex align-items-center gap-1" style="font-size: 0.7rem;">
                                            <i class="bi bi-calendar3"></i>
                                            {{ $breakdown['event']->date->format('d M Y') }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-indigo-subtle text-indigo rounded-pill px-3 py-1.5 fw-bold" style="font-size: 0.75rem;">
                                            {{ number_format($breakdown['tickets_sold'], 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="text-end fw-semibold text-dark small">
                                        Rp {{ number_format($breakdown['ticket_net'], 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        @if($breakdown['event']->is_tenant_open)
                                            <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1.5 fw-bold" style="font-size: 0.75rem;">
                                                {{ number_format($breakdown['booths_sold'], 0, ',', '.') }}
                                            </span>
                                        @else
                                            <span class="text-muted small" style="font-size: 0.75rem; font-style: italic;">No Tenant</span>
                                        @endif
                                    </td>
                                    <td class="text-end fw-semibold text-dark small">
                                        @if($breakdown['event']->is_tenant_open)
                                            Rp {{ number_format($breakdown['booth_net'], 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="pe-3 text-end fw-bold text-indigo small">
                                        Rp {{ number_format($breakdown['total_net'], 0, ',', '.') }}
                                    </td>
                                    <td class="text-center pe-3">
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('vendor.rekening.exportEvent', $breakdown['event']->id) }}"
                                            class="btn btn-sm btn-outline-indigo rounded-pill px-3 py-1 d-inline-flex align-items-center gap-1"
                                            style="font-size: 0.72rem; font-weight: 600; color: #4338ca; border-color: #4338ca;"
                                            title="Cetak PDF laporan event ini"
                                            target="_blank">
                                                <i class="bi bi-file-earmark-pdf-fill"></i> PDF
                                            </a>

                                            @if($breakdown['wd_can_withdraw'])
                                                <button type="button"
                                                    class="btn btn-sm btn-primary rounded-pill px-3 py-1 d-inline-flex align-items-center gap-1"
                                                    style="font-size: 0.72rem; font-weight: 600;"
                                                    title="Tarik dana event ini"
                                                    onclick="openWithdrawModal(
                                                        {{ $breakdown['event']->id }},
                                                        '{{ addslashes($breakdown['event']->title) }}',
                                                        {{ $breakdown['wd_available'] }},
                                                        '{{ addslashes($breakdown['wd_phase']['message']) }}'
                                                    )">
                                                    <i class="bi bi-cash-stack"></i> Tarik
                                                </button>
                                            @else
                                                <button type="button"
                                                    class="btn btn-sm btn-secondary rounded-pill px-3 py-1 d-inline-flex align-items-center gap-1"
                                                    style="font-size: 0.72rem; font-weight: 600; opacity: 0.65;"
                                                    disabled
                                                    title="{{ $breakdown['wd_phase']['message'] }}">
                                                    <i class="bi bi-lock-fill"></i> Tarik
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <div class="bg-light rounded-circle d-inline-flex p-3 mb-2">
                                            <i class="bi bi-calendar-x fs-2"></i>
                                        </div>
                                        <p class="mb-0 fw-medium small">Belum ada event terdaftar.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Controls -->
                    <div class="d-flex justify-content-end mt-4" id="paginationControlsEvent">
                        <!-- Rendered by JS -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab 2: Riwayat Penarikan Dana -->
        <div class="tab-pane fade" id="withdrawal-pane" role="tabpanel" aria-labelledby="withdrawal-tab">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 py-4 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h5 class="fw-bold mb-1 text-dark">Riwayat Penarikan Dana</h5>
                        <p class="text-muted small mb-0">Catatan pengajuan pencairan dana Anda ke rekening bank terdaftar</p>
                    </div>
                    <span class="badge bg-light text-dark rounded-pill border px-3 py-2 small fw-medium">
                        Total: {{ $withdrawals->count() }} Transaksi
                    </span>
                </div>
                <div class="card-body p-4 pt-0">
                    <!-- Pagination Config / Page Selector -->
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3 bg-light p-3 rounded-4 border">
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted small text-nowrap fw-semibold">Tampilkan:</span>
                            <select class="form-select form-select-sm rounded-3 border-secondary-subtle" style="width: auto; cursor: pointer; min-width: 80px;" id="perPageWd">
                                <option value="5" selected>5</option>
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="all">Semua</option>
                            </select>
                            <span class="text-muted small">data per halaman</span>
                        </div>
                        <div class="small text-secondary fw-semibold" id="paginationInfoWd">
                            Menampilkan 0-0 dari 0 data
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="tableWd">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3 text-muted small fw-bold text-uppercase ls-1">Tanggal</th>
                                    <th class="py-3 text-muted small fw-bold text-uppercase ls-1">Event</th>
                                    <th class="py-3 text-muted small fw-bold text-uppercase ls-1">Informasi Rekening Tujuan</th>
                                    <th class="py-3 text-muted small fw-bold text-uppercase ls-1">Jumlah Tarik</th>
                                    <th class="py-3 text-muted small fw-bold text-uppercase ls-1">Status</th>
                                    <th class="pe-4 py-3 text-muted small fw-bold text-uppercase ls-1 text-end">Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($withdrawals as $wd)
                                <tr class="wd-row">
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark small">{{ $wd->created_at->format('d M Y') }}</div>
                                        <div class="text-muted small" style="font-size: 0.7rem;">Pukul {{ $wd->created_at->format('H:i') }} WIB</div>
                                    </td>
                                    <td>
                                        <span class="small fw-medium text-dark">{{ $wd->event->title ?? '-' }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-secondary-subtle text-dark rounded-pill px-2 py-1 small fw-bold">
                                                {{ strtoupper($wd->bank_name) }}
                                            </span>
                                            <span class="fw-medium text-dark small">{{ $wd->account_number }}</span>
                                        </div>
                                        <div class="text-muted small" style="font-size: 0.75rem;">a.n. {{ $wd->account_holder }}</div>
                                    </td>
                                    <td class="fw-bold text-indigo small">
                                        Rp {{ number_format($wd->amount, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        @if($wd->status === 'pending')
                                            <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-3 py-2 fw-medium d-inline-flex align-items-center gap-1.5" style="font-size: 0.7rem;">
                                                <span class="spinner-grow spinner-grow-sm text-warning" style="width: 8px; height: 8px; animation-duration: 1.5s;" role="status"></span>
                                                Menunggu Persetujuan
                                            </span>
                                        @elseif($wd->status === 'approved')
                                            <span class="badge bg-success-subtle text-success-emphasis rounded-pill px-3 py-2 fw-medium d-inline-flex align-items-center gap-1.5" style="font-size: 0.7rem;">
                                                <i class="bi bi-check-circle-fill"></i>
                                                Pencairan Berhasil
                                            </span>
                                        @elseif($wd->status === 'rejected')
                                            <span class="badge bg-danger-subtle text-danger-emphasis rounded-pill px-3 py-2 fw-medium d-inline-flex align-items-center gap-1.5" style="font-size: 0.7rem;">
                                                <i class="bi bi-x-circle-fill"></i>
                                                Ditolak Admin
                                            </span>
                                        @endif
                                    </td>
                                    <td class="pe-4 text-end text-muted small" style="font-size: 0.75rem; max-width: 250px;">
                                        {{ $wd->notes ?: '-' }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <div class="bg-light rounded-circle d-inline-flex p-4 mb-3">
                                            <i class="bi bi-cash fs-1"></i>
                                        </div>
                                        <p class="mb-0 fw-medium">Belum ada riwayat penarikan dana.</p>
                                        <p class="small text-muted">Lakukan penarikan pertama Anda dari tombol "Tarik" di tab Rincian Finansial.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Controls -->
                    <div class="d-flex justify-content-end mt-4" id="paginationControlsWd">
                        <!-- Rendered by JS -->
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Modal Withdraw / Tarik Dana (per event, diisi dinamis via JS) -->
<div class="modal fade" id="withdrawModal" tabindex="-1" aria-labelledby="withdrawModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
            <div class="modal-header border-0 text-white p-4" style="background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);">
                <div>
                    <h5 class="modal-title fw-bold" id="withdrawModalLabel"><i class="bi bi-cash-stack me-2"></i>Form Penarikan Dana</h5>
                    <p class="small mb-0 text-white-50 mt-1">Event: <span id="wd_event_title" class="fw-semibold"></span></p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('vendor.rekening.withdraw') }}" method="POST" id="withdrawForm">
                @csrf
                <input type="hidden" name="event_id" id="wd_event_id">

                <div class="modal-body p-4 bg-light">
                    <!-- Informational Box -->
                    <div class="p-3 bg-white rounded-4 border mb-3 d-flex align-items-center justify-content-between shadow-sm">
                        <div>
                            <div class="small text-muted fw-semibold">Maksimal Bisa Ditarik (Event Ini)</div>
                            <div class="h4 fw-bold mb-0 text-indigo" id="wd_available_display" style="letter-spacing: -0.5px;">
                                Rp 0
                            </div>
                        </div>
                        <span class="badge bg-indigo-subtle text-indigo p-2 rounded-3"><i class="bi bi-wallet2 fs-4"></i></span>
                    </div>

                    <!-- Note tahap penarikan -->
                    <div class="alert alert-warning border-0 rounded-4 small mb-4 d-flex gap-2 align-items-start" style="background-color:#fffbeb;">
                        <i class="bi bi-info-circle-fill mt-1"></i>
                        <span id="wd_phase_message"></span>
                    </div>

                    <!-- Input Fields -->
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Nama Bank Tujuan</label>
                        <select name="bank_name" class="form-select rounded-3 shadow-sm py-2.5 small fw-semibold" required>
                            <option value="" disabled selected>-- Pilih Bank --</option>
                            <option value="bca">Bank Central Asia (BCA)</option>
                            <option value="mandiri">Bank Mandiri</option>
                            <option value="bni">Bank Negara Indonesia (BNI)</option>
                            <option value="bri">Bank Rakyat Indonesia (BRI)</option>
                            <option value="cimb">CIMB Niaga</option>
                            <option value="permata">Permata Bank</option>
                            <option value="gopay">GoPay (Digital Wallet)</option>
                            <option value="ovo">OVO (Digital Wallet)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Nomor Rekening / No. HP Wallet</label>
                        <input type="text" name="account_number" class="form-control rounded-3 shadow-sm py-2.5 small" 
                               placeholder="Contoh: 1234567890 atau 0812xxxx" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Nama Pemilik Rekening (Sesuai Buku Tabungan)</label>
                        <input type="text" name="account_holder" class="form-control rounded-3 shadow-sm py-2.5 small" 
                               placeholder="Nama lengkap pemegang rekening" required>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-bold small text-muted">Jumlah Penarikan (IDR)</label>
                        <div class="input-group rounded-3 shadow-sm overflow-hidden">
                            <span class="input-group-text bg-indigo text-white border-0 fw-bold">Rp</span>
                            <input type="number" name="amount" id="wd_amount_input" class="form-control py-2.5 small fw-bold" 
                                   min="10000" placeholder="Minimal Rp 100.000" required>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2 px-1">
                            <span class="text-muted small" style="font-size: 0.75rem;"><i class="bi bi-info-circle me-1"></i>Min. penarikan Rp 100.000</span>
                            <button type="button" id="wd_tarik_semua_btn" class="btn btn-link p-0 text-indigo fw-bold small text-decoration-none" style="font-size: 0.75rem;">
                                Tarik Maksimal
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 bg-white d-flex gap-2">
                    <button type="button" class="btn btn-light rounded-pill px-4 py-2 fw-semibold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fw-bold flex-grow-1" id="submitBtn">
                        Konfirmasi Penarikan <i class="bi bi-arrow-right-short ms-1"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .ls-1 { letter-spacing: 0.5px; }
    .table > :not(caption) > * > * { padding: 18px 12px; }
    .bg-indigo-subtle { background-color: #e0e7ff !important; color: #4338ca !important; }
    .text-indigo { color: #4338ca !important; }
    .bg-indigo { background-color: #4338ca !important; }
    .text-warning-sub { color: #f59e0b !important; }
    .border-start-md { border-left: 1px solid #e5e7eb; }
    @media (max-width: 767.98px) {
        .border-start-md { border-left: none; border-top: 1px solid #e5e7eb; padding-top: 15px; }
    }

    .table tbody tr { transition: 0.2s; }
    .table tbody tr:hover { background-color: rgba(67, 56, 202, 0.02); }

    .stat-card {
        border-radius: 16px;
        padding: 24px;
        display: flex;
        align-items: center;
        gap: 20px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        cursor: default;
        overflow: hidden;
        position: relative;
    }

    .stat-card::after {
        content: '';
        position: absolute;
        top: -20px;
        right: -20px;
        width: 85px;
        height: 85px;
        border-radius: 50%;
        background: rgba(255,255,255,0.12);
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    .stat-card--balance {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.25);
    }
    .stat-card--revenue {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.25);
    }
    .stat-card--withdrawn {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.25);
    }

    .stat-card__icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        background: rgba(255,255,255,0.22);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        color: white;
        flex-shrink: 0;
    }

    .stat-card__body {
        flex-grow: 1;
        min-width: 0;
    }

    .stat-card__label {
        color: rgba(255,255,255,0.85);
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.75px;
    }

    .stat-card__value {
        color: white;
        font-size: 1.6rem;
        font-weight: 800;
        line-height: 1.2;
        margin: 4px 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .stat-card__subtext {
        color: rgba(255,255,255,0.8);
        font-size: 0.75rem;
        font-weight: 500;
    }

    .bg-indigo-dark {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
    }

    .glass-bg-blur {
        position: absolute;
        top: 0; right: 0; bottom: 0; left: 0;
        background: radial-gradient(circle at 100% 0%, rgba(99, 102, 241, 0.15) 0%, transparent 60%);
        pointer-events: none;
    }

    .bg-white-10 {
        background-color: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .edu-icon {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .nav-pills {
        background-color: #f8fafc !important;
        border: 1px solid #e2e8f0 !important;
    }
    .nav-pills .nav-link {
        color: #64748b !important;
        font-size: 0.9rem;
        transition: all 0.25s ease;
        background: transparent !important;
        border: none !important;
    }
    .nav-pills .nav-link.active {
        background-color: #fff !important;
        color: #4338ca !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06) !important;
    }
    .nav-pills .nav-link:hover:not(.active) {
        color: #1e293b !important;
    }

    .pagination .page-link {
        border: 1px solid #e2e8f0;
        color: #64748b;
        background-color: #fff;
        transition: all 0.2s ease;
        padding: 0;
    }
    .pagination .page-item.active .page-link {
        background-color: #4338ca !important;
        border-color: #4338ca !important;
        color: #fff !important;
    }
    .pagination .page-link:hover:not(.active) {
        background-color: #f1f5f9;
        color: #1e293b;
        border-color: #cbd5e1;
    }
    .pagination .page-item.disabled .page-link {
        color: #cbd5e1;
        background-color: #f8fafc;
        border-color: #e2e8f0;
    }

    .btn-outline-indigo:hover {
        background-color: #4338ca !important;
        color: white !important;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    function setupTablePagination(tableId, rowClass, selectId, infoId, controlsId) {
        const table = document.getElementById(tableId);
        if (!table) return;

        const select = document.getElementById(selectId);
        const info = document.getElementById(infoId);
        const controls = document.getElementById(controlsId);
        const allRows = table.getElementsByClassName(rowClass);
        const totalRows = allRows.length;

        if (totalRows === 0) {
            if (info) info.textContent = "Menampilkan 0-0 dari 0 data";
            return;
        }

        let currentPage = 1;
        let itemsPerPage = select.value === 'all' ? totalRows : parseInt(select.value);

        function renderPage() {
            itemsPerPage = select.value === 'all' ? totalRows : parseInt(select.value);
            const totalPages = Math.ceil(totalRows / itemsPerPage);

            if (currentPage > totalPages) {
                currentPage = totalPages || 1;
            }

            const startIdx = (currentPage - 1) * itemsPerPage;
            const endIdx = Math.min(startIdx + itemsPerPage, totalRows);

            for (let i = 0; i < totalRows; i++) {
                if (i >= startIdx && i < endIdx) {
                    allRows[i].style.display = '';
                } else {
                    allRows[i].style.display = 'none';
                }
            }

            if (info) {
                if (totalRows === 0) {
                    info.textContent = "Menampilkan 0-0 dari 0 data";
                } else {
                    info.textContent = `Menampilkan ${startIdx + 1}-${endIdx} dari ${totalRows} data`;
                }
            }

            if (controls) {
                controls.innerHTML = '';
                if (totalPages <= 1) return;

                const ul = document.createElement('ul');
                ul.className = 'pagination pagination-sm rounded-pill mb-0 gap-1';

                const prevLi = document.createElement('li');
                prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
                const prevBtn = document.createElement('button');
                prevBtn.className = 'page-link rounded-circle d-flex align-items-center justify-content-center';
                prevBtn.style.width = '32px';
                prevBtn.style.height = '32px';
                prevBtn.innerHTML = '<i class="bi bi-chevron-left"></i>';
                prevBtn.type = 'button';
                prevBtn.addEventListener('click', () => {
                    if (currentPage > 1) {
                        currentPage--;
                        renderPage();
                    }
                });
                prevLi.appendChild(prevBtn);
                ul.appendChild(prevLi);

                for (let p = 1; p <= totalPages; p++) {
                    const li = document.createElement('li');
                    li.className = `page-item ${currentPage === p ? 'active' : ''}`;
                    const btn = document.createElement('button');
                    btn.className = 'page-link rounded-circle d-flex align-items-center justify-content-center fw-bold';
                    btn.style.width = '32px';
                    btn.style.height = '32px';
                    btn.textContent = p;
                    btn.type = 'button';
                    btn.addEventListener('click', () => {
                        currentPage = p;
                        renderPage();
                    });
                    li.appendChild(btn);
                    ul.appendChild(li);
                }

                const nextLi = document.createElement('li');
                nextLi.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
                const nextBtn = document.createElement('button');
                nextBtn.className = 'page-link rounded-circle d-flex align-items-center justify-content-center';
                nextBtn.style.width = '32px';
                nextBtn.style.height = '32px';
                nextBtn.innerHTML = '<i class="bi bi-chevron-right"></i>';
                nextBtn.type = 'button';
                nextBtn.addEventListener('click', () => {
                    if (currentPage < totalPages) {
                        currentPage++;
                        renderPage();
                    }
                });
                nextLi.appendChild(nextBtn);
                ul.appendChild(nextLi);

                controls.appendChild(ul);
            }
        }

        select.addEventListener('change', () => {
            currentPage = 1;
            renderPage();
        });

        renderPage();
    }

    setupTablePagination('tableEvent', 'event-row', 'perPageEvent', 'paginationInfoEvent', 'paginationControlsEvent');
    setupTablePagination('tableWd', 'wd-row', 'perPageWd', 'paginationInfoWd', 'paginationControlsWd');

    // === BARU: handle tombol "Tarik Maksimal" di modal ===
    const wdAmountInput = document.getElementById('wd_amount_input');
    const wdMaxBtn       = document.getElementById('wd_tarik_semua_btn');

    if (wdMaxBtn) {
        wdMaxBtn.addEventListener('click', () => {
            wdAmountInput.value = wdMaxBtn.dataset.available || 0;
        });
    }
});

// === BARU: buka modal tarik dana untuk event tertentu, isi semua field-nya ===
function openWithdrawModal(eventId, eventTitle, available, message) {
    document.getElementById('wd_event_id').value = eventId;
    document.getElementById('wd_event_title').textContent = eventTitle;
    document.getElementById('wd_available_display').textContent =
        'Rp ' + Number(available).toLocaleString('id-ID');
    document.getElementById('wd_phase_message').textContent = message;

    const amountInput = document.getElementById('wd_amount_input');
    amountInput.max = available;
    amountInput.value = '';

    document.getElementById('wd_tarik_semua_btn').dataset.available = available;

    const modal = new bootstrap.Modal(document.getElementById('withdrawModal'));
    modal.show();
}
</script>
@endsection