{{-- Pastikan ini pakai layout vendor yang ada sidebarnya --}}
@extends('v_vendor.v_layouts.app')

@section('title', 'Daftar Pesanan Masuk')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Pesanan Masuk 🛒</h2>
            <p class="text-muted">Pantau siapa saja yang sudah memesan tiket event kamu.</p>
        </div>
        <div class="badge bg-primary px-4 py-2 rounded-pill fs-6">
            Total: {{ $bookings->total() }} Pesanan
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary">
                    <tr>
                        <th class="ps-4 py-3">Kode Booking</th>
                        <th class="py-3">Pembeli</th>
                        <th class="py-3">Event / Kategori</th>
                        <th class="py-3 text-center">Status</th>
                        <th class="py-3 text-end pe-4">Total Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $book)
                    <tr>
                        <td class="ps-4">
                            <span class="fw-bold text-dark">#{{ $book->booking_code }}</span>
                            <div class="small text-muted">{{ $book->created_at->format('d M Y, H:i') }}</div>
                        </td>
                        <td>
                            <div class="fw-bold">{{ $book->user->name }}</div>
                            <div class="small text-muted">{{ $book->user->email }}</div>
                        </td>
                        <td>
                            <div class="fw-bold text-primary">{{ $book->event->title }}</div>
                            <span class="badge bg-secondary-subtle text-secondary small">
                                {{ $book->ticket_category ?? 'Umum' }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if($book->booking_status == 'confirmed')
                                <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">Selesai</span>
                            @elseif($book->booking_status == 'pending')
                                <span class="badge bg-warning-subtle text-warning px-3 py-2 rounded-pill">Menunggu</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill">Batal</span>
                            @endif
                        </td>
                        <td class="text-end pe-4 fw-bold">
                            Rp{{ number_format($book->total_price, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="py-4">
                                <i class="bi bi-cart-x display-1 text-muted opacity-25"></i>
                                <p class="text-muted mt-3 fs-5">Belum ada pesanan masuk nih</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($bookings->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $bookings->links() }}
        </div>
        @endif
    </div>
</div>
@endsection