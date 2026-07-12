@extends('admin.layouts.app')

@section('title', 'Detail User - ' . $user->name)

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-800 mb-1">Detail Profil</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-primary fw-600">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}" class="text-decoration-none text-primary fw-600">Pengguna</a></li>
                    <li class="breadcrumb-item active" aria-current="page text-muted fw-500">{{ $user->name }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ session('users.back_url', route('admin.users.index')) }}" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm">
            <i class="bi bi-arrow-left me-2"></i> Kembali
        </a>
    </div>

    <div class="row g-4">
        {{-- Profile Sidebar --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-0">
                    <div class="p-4 text-center bg-light border-bottom">
                        <div class="position-relative d-inline-block mb-3">
                            <div class="bg-indigo text-white rounded-circle d-flex align-items-center justify-content-center fw-800 mx-auto shadow" style="width: 100px; height: 100px; font-size: 2.5rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <span class="position-absolute bottom-0 end-0 p-2 bg-success border border-white border-2 rounded-circle" title="Aktif"></span>
                        </div>
                        <h5 class="fw-800 mb-1">{{ $user->name }}</h5>
                        <p class="text-muted small mb-3">{{ $user->email }}</p>
                        <div class="d-flex justify-content-center gap-2">
                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill small fw-700">
                                <i class="bi bi-shield-lock me-1"></i> {{ ucfirst($user->role) }}
                            </span>
                            <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'danger' }} bg-opacity-10 text-{{ $user->status === 'active' ? 'success' : 'danger' }} px-3 py-2 rounded-pill small fw-700">
                                <i class="bi bi-{{ $user->status === 'active' ? 'check-circle' : 'x-circle' }} me-1"></i> {{ ucfirst($user->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="mb-4">
                            <label class="text-muted small text-uppercase fw-700 mb-3 d-block">Kontak & Info</label>
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-3"><i class="bi bi-phone text-primary"></i></div>
                                <div>
                                    <p class="mb-0 small text-muted">No. Telepon</p>
                                    <p class="mb-0 fw-600">{{ $user->phone ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-3"><i class="bi bi-calendar3 text-primary"></i></div>
                                <div>
                                    <p class="mb-0 small text-muted">Tanggal Bergabung</p>
                                    <p class="mb-0 fw-600">{{ $user->created_at->format('d M Y') }}</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-3"><i class="bi bi-clock-history text-primary"></i></div>
                                <div>
                                    <p class="mb-0 small text-muted">Login Terakhir</p>
                                    <p class="mb-0 fw-600">
                                        {{ $user->last_login ? \Carbon\Carbon::parse($user->last_login)->diffForHumans() : 'Belum pernah' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        @if($user->role === 'vendor')
                        <div class="mb-4">
                            <label class="text-muted small text-uppercase fw-700 mb-3 d-block">Status Verifikasi</label>
                            @php
                                $statusMap = [
                                    'unverified' => ['color' => 'secondary', 'icon' => 'dash-circle'],
                                    'pending' => ['color' => 'warning', 'icon' => 'hourglass-split'],
                                    'verified' => ['color' => 'success', 'icon' => 'patch-check-fill'],
                                    'rejected' => ['color' => 'danger', 'icon' => 'x-octagon-fill'],
                                ];
                                $vStatus = $user->verification_status ?? 'unverified';
                                $vInfo = $statusMap[$vStatus] ?? $statusMap['unverified'];
                            @endphp
                            <div class="alert alert-{{ $vInfo['color'] }} border-0 rounded-4 d-flex align-items-center mb-0">
                                <i class="bi bi-{{ $vInfo['icon'] }} fs-4 me-3"></i>
                                <div>
                                    <h6 class="mb-0 fw-800">{{ strtoupper($vStatus) }}</h6>
                                    @if($user->verified_at)
                                        <small class="text-xs">Verified: {{\Carbon\Carbon::parse( $user->verified_at)->format('d/m/Y') }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary rounded-pill fw-700 shadow-sm py-2">
                                <i class="bi bi-pencil-square me-2"></i> Edit Profil
                            </a>
                            @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-outline-{{ $user->status === 'active' ? 'danger' : 'success' }} rounded-pill w-100 fw-700 py-2">
                                    <i class="bi bi-power me-2"></i> {{ $user->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabs Section --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 p-4 pb-0">
                    <ul class="nav nav-tabs border-0 bg-light p-1 rounded-pill mb-0" id="profileTabs" role="tablist">
                        @if($user->role !== 'vendor')
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active rounded-pill fw-700 border-0 px-4 py-2" id="transactions-tab" data-bs-toggle="tab" data-bs-target="#transactions" type="button" role="tab">
                                <i class="bi bi-receipt me-2"></i> Transaksi
                            </button>
                        </li>
                        @endif
                        @if($user->role === 'vendor')
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active rounded-pill fw-700 border-0 px-4 py-2" id="vendor-tab" data-bs-toggle="tab" data-bs-target="#vendor" type="button" role="tab">
                                <i class="bi bi-building me-2"></i> Legal & Profil
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill fw-700 border-0 px-4 py-2" id="events-tab" data-bs-toggle="tab" data-bs-target="#events" type="button" role="tab">
                                <i class="bi bi-calendar-event me-2"></i> Event ({{ $vendorEvents->count() }})
                            </button>
                        </li>
                        @endif
                    </ul>
                </div>
                <div class="card-body p-4 mt-2">
                    <div class="tab-content" id="profileTabsContent">
                        
                        @if($user->role !== 'vendor')
                        {{-- Transactions Tab --}}
                        <div class="tab-pane fade show active" id="transactions" role="tabpanel">
                            <div class="d-flex align-items-center justify-content-between mb-4 mt-2">
                                <h6 class="fw-800 mb-0">Histori Pemesanan Tiket</h6>
                                <span class="badge bg-light text-dark rounded-pill px-3 py-2 fw-700 border">{{ $bookings->total() }} Transaksi</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr class="text-secondary small fw-700 text-uppercase bg-light">
                                            <th class="border-0 rounded-start-3 ps-3">Kode</th>
                                            <th class="border-0">Event</th>
                                            <th class="border-0">Tanggal</th>
                                            <th class="border-0 text-center">Qty</th>
                                            <th class="border-0 pe-3 text-end rounded-end-3">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($bookings as $booking)
                                        <tr>
                                            <td class="ps-3"><span class="badge bg-light text-dark fw-700 border">{{ $booking->booking_code }}</span></td>
                                            <td>
                                                <div class="fw-600">{{ $booking->event->title }}</div>
                                                <small class="text-muted">{{ $booking->event->category }}</small>
                                            </td>
                                            <td class="small text-muted">{{ $booking->created_at->format('d M Y, H:i') }}</td>
                                            <td class="text-center fw-700 text-dark">{{ $booking->quantity }}</td>
                                            <td class="text-end pe-3 fw-800 text-primary">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5">
                                                <div class="py-4">
                                                    <i class="bi bi-inbox fs-1 text-muted opacity-25 d-block mb-3"></i>
                                                    <p class="text-muted fw-600">Belum ada riwayat transaksi</p>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4">
                                {{ $bookings->links() }}
                            </div>
                        </div>
                        @endif

                        @if($user->role === 'vendor')
                        {{-- Vendor Profile Tab --}}
                        <div class="tab-pane fade show active" id="vendor" role="tabpanel">
                            <div class="d-flex align-items-center justify-content-between mb-4 mt-2">
                                <h6 class="fw-800 mb-0">Informasi Legal Vendor</h6>
                                @if($user->verification_status === 'pending')
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-success btn-sm rounded-pill px-3 fw-700 shadow-sm" data-bs-toggle="modal" data-bs-target="#verifyModal">
                                        <i class="bi bi-check-circle-fill me-1"></i> Verifikasi Akun
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm rounded-pill px-3 fw-700 shadow-sm" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                        <i class="bi bi-x-circle-fill me-1"></i> Tolak
                                    </button>
                                </div>
                                @endif
                            </div>

                            <div class="row g-4 mb-5">
                                <div class="col-md-6">
                                    <div class="p-3 rounded-4 bg-light border h-100">
                                        <label class="text-muted small fw-700 text-uppercase d-block mb-2">Tipe Dokumen</label>
                                        <p class="mb-0 fw-700 text-indigo fs-5 text-capitalize">{{ str_replace('_', ' ', $user->document_type ?? 'Belum Diatur') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 rounded-4 bg-light border h-100">
                                        <label class="text-muted small fw-700 text-uppercase d-block mb-2">Nomor NPWP</label>
                                        <p class="mb-0 fw-700 text-dark fs-5">{{ $user->npwp_number ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="p-3 rounded-4 bg-light border">
                                        <label class="text-muted small fw-700 text-uppercase d-block mb-2">Nama Terdaftar di NPWP</label>
                                        <p class="mb-0 fw-700 text-dark fs-5">{{ $user->npwp_name ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="p-3 rounded-4 bg-light border">
                                        <label class="text-muted small fw-700 text-uppercase d-block mb-2">Alamat NPWP</label>
                                        <p class="mb-0 fw-600 text-dark">{{ $user->npwp_address ?? '-' }}</p>
                                    </div>
                                </div>
                                @if($user->document_type === 'badan_hukum')
                                <div class="col-md-6">
                                    <div class="p-3 rounded-4 bg-light border h-100">
                                        <label class="text-muted small fw-700 text-uppercase d-block mb-2">Nomor NIB</label>
                                        <p class="mb-0 fw-700 text-dark fs-5">{{ $user->nib_number ?? '-' }}</p>
                                    </div>
                                </div>
                                @endif
                                <div class="col-md-6">
                                    <div class="p-3 rounded-4 bg-light border h-100">
                                        <label class="text-muted small fw-700 text-uppercase d-block mb-2">Dokumen NPWP Terlampir</label>
                                        @if($user->npwp_file)
                                            <a href="{{ asset('storage/' . $user->npwp_file) }}" target="_blank" class="btn btn-primary rounded-pill mt-2 px-4 shadow-sm fw-700">
                                                <i class="bi bi-file-earmark-pdf-fill me-2"></i> Lihat Dokumen Legal
                                            </a>
                                        @else
                                            <div class="p-2 text-muted fw-600 italic mt-1">Belum diunggah</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if($user->verification_status === 'rejected')
                            <div class="p-4 rounded-4 bg-danger bg-opacity-10 border border-danger border-opacity-25">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-exclamation-triangle-fill text-danger fs-4 me-3"></i>
                                    <h6 class="fw-800 text-danger mb-0">Alasan Penolakan Verifikasi</h6>
                                </div>
                                <p class="mb-0 text-danger fw-500 ps-5">{{ $user->rejection_reason }}</p>
                            </div>
                            @endif
                        </div>

                        {{-- Vendor Events Tab --}}
                        <div class="tab-pane fade" id="events" role="tabpanel">
                            <div class="d-flex align-items-center justify-content-between mb-4 mt-2">
                                <h6 class="fw-800 mb-0">Daftar Event dari {{ $user->name }}</h6>
                                <span class="badge bg-gradient-purple text-white rounded-pill px-3 py-2 fw-700 border-0">{{ $vendorEvents->count() }} Event</span>
                            </div>
                            <div class="row g-3">
                                @forelse($vendorEvents as $event)
                                <div class="col-12">
                                    <div class="d-flex align-items-center p-3 rounded-4 bg-white border hover-shadow transition-all">
                                        <img src="{{ asset('storage/' . $event->image) }}" class="rounded-3 shadow-sm me-4" style="width: 100px; height: 100px; object-fit: cover;">
                                        <div class="flex-grow-1">
                                            @if($event->category && trim($event->category) !== '')
                                                <div class="badge bg-gradient-purple text-white rounded-pill small fw-700 px-3 mb-2 border-0">{{ $event->category }}</div>
                                            @endif
                                            <h6 class="mb-1 fw-800">{{ $event->title }}</h6>
                                            <div class="d-flex gap-4 small text-muted">
                                                <span><i class="bi bi-calendar-event me-2"></i> {{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}</span>
                                                <span><i class="bi bi-ticket-perforated me-2 text-primary"></i> <strong class="text-dark">{{ $event->bookings_count }}</strong> Tiket Terjual</span>
                                            </div>
                                        </div>
                                        <div class="text-end ps-3">
                                            <span class="badge bg-{{ $event->status === 'active' ? 'success' : ($event->status === 'cancelled' ? 'danger' : 'secondary') }} rounded-pill mb-3 px-3 py-2 d-inline-block fw-700">
                                                {{ strtoupper($event->status) }}
                                            </span>
                                            <a href="{{ route('event.detail', $event->id) }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill px-4 d-block fw-700">Detail</a>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="col-12 text-center py-5">
                                    <div class="py-4">
                                        <i class="bi bi-calendar-x fs-1 text-muted opacity-25 d-block mb-3"></i>
                                        <p class="text-muted fw-600">Vendor ini belum memiliki event terdaftar</p>
                                    </div>
                                </div>
                                @endforelse
                            </div>
                        </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODALS --}}
@if($user->role === 'vendor' && $user->verification_status === 'pending')
<!-- Verify Modal -->
<div class="modal fade" id="verifyModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 100px; height: 100px;">
                    <i class="bi bi-patch-check-fill fs-1"></i>
                </div>
                <h4 class="fw-800 mb-2">Verifikasi Akun?</h4>
                <p class="text-muted">Dengan menyetujui, <strong>{{ $user->name }}</strong> akan mendapatkan status <span class="text-success fw-700">Verified</span> dan diizinkan membuat event di platform.</p>
                
                <form action="{{ route('admin.users.verify', $user->id) }}" method="POST" class="mt-4 pt-2">
                    @csrf @method('PATCH')
                    <input type="hidden" name="action" value="approve">
                    <div class="row g-2">
                        <div class="col-6">
                            <button type="button" class="btn btn-light rounded-pill w-100 fw-700 py-3" data-bs-dismiss="modal">Batal</button>
                        </div>
                        <div class="col-6">
                            <button type="submit" class="btn btn-success rounded-pill w-100 fw-700 py-3 shadow-sm">Ya, Verifikasi</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <h5 class="modal-title fw-800">Tolak Verifikasi Vendor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('admin.users.verify', $user->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <input type="hidden" name="action" value="reject">
                    <div class="mb-4">
                        <label class="form-label fw-800 text-muted small text-uppercase mb-2">Alasan Penolakan</label>
                        <textarea name="rejection_reason" class="form-control rounded-4 bg-light border-0 p-3 fw-500" rows="5" placeholder="Tulis alasan penolakan secara mendetail agar vendor dapat memahami kekurangannya..." required></textarea>
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <button type="button" class="btn btn-light rounded-pill w-100 fw-700 py-3" data-bs-dismiss="modal">Batal</button>
                        </div>
                        <div class="col-6">
                            <button type="submit" class="btn btn-danger rounded-pill w-100 fw-700 py-3 shadow-sm">Kirim Penolakan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<style>
    .bg-indigo { background-color: #667eea; }
    .text-indigo { color: #667eea; }
    .border-indigo { border-color: #667eea; }
    .bg-gradient-purple { background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); }
    .hover-shadow:hover { box-shadow: 0 10px 20px rgba(0,0,0,0.05); transform: translateY(-2px); }
    .transition-all { transition: all 0.3s ease; }
    .nav-tabs .nav-link { color: #64748b; }
    .nav-tabs .nav-link.active { background-color: #ffffff !important; color: #667eea !important; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
    .breadcrumb-item + .breadcrumb-item::before { content: "›"; font-size: 1.2rem; line-height: 1; vertical-align: middle; color: #cbd5e1; }
    .fw-500 { font-weight: 500; }
    .fw-600 { font-weight: 600; }
</style>
@endpush