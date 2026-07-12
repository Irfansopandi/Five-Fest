@extends('v_vendor.v_layouts.app')

@section('title', 'Kelola Merchandise - FiveFest')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="fw-bold mb-1">Manajemen Merchandise 👕</h2>
            <p class="text-muted">Kelola stok dan harga pernak-pernik event kamu di sini.</p>
        </div>
        <a href="{{ route('vendor.merchandises.create') }}" class="btn btn-primary px-4 py-2 rounded-pill fw-bold shadow-sm">
            <i class="bi bi-plus-lg me-2"></i>Tambah Merch Baru
        </a>
    </div>

    {{-- STATS MERCH --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3">
                <div class="d-flex align-items-center">
                    <div class="p-3 rounded-3 bg-primary bg-opacity-10 text-primary me-3">
                        <i class="bi bi-box-seam fs-4"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">Total Produk</small>
                        <span class="fw-bold fs-5">{{ $merchandises->count() }} Item</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3">
                <div class="d-flex align-items-center">
                    <div class="p-3 rounded-3 bg-warning bg-opacity-10 text-warning me-3">
                        <i class="bi bi-exclamation-triangle fs-4"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">Stok Tipis</small>
                        <span class="fw-bold fs-5">{{ $merchandises->where('stock', '<', 5)->count() }} Item</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TABLE SECTION --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">Produk</th>
                        <th class="py-3">Harga</th>
                        <th class="py-3">Stok</th>
                        <th class="py-3">Event Terkait</th>
                        <th class="py-3 text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($merchandises as $item)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <img src="{{ $item->image ? asset('storage/'.$item->image) : 'https://placehold.co/50' }}" 
                                     class="rounded-3 me-3" width="50" height="50" style="object-fit: cover;">
                                <div>
                                    <h6 class="fw-bold mb-0">{{ $item->name }}</h6>
                                    <small class="text-muted text-truncate d-inline-block" style="max-width: 150px;">{{ $item->description }}</small>
                                </div>
                            </div>
                        </td>
                        <td><span class="fw-bold text-primary">Rp{{ number_format($item->price, 0, ',', '.') }}</span></td>
                        <td>
                            @if($item->stock <= 5)
                                <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill">Sisa {{ $item->stock }}</span>
                            @else
                                <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">{{ $item->stock }} Pcs</span>
                            @endif
                        </td>
                        <td><span class="badge bg-secondary-subtle text-secondary">{{ $item->event->title ?? 'N/A' }}</span></td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('vendor.merchandises.edit', $item->id) }}" class="btn btn-light btn-sm rounded-3">
                                    <i class="bi bi-pencil-square text-primary"></i>
                                </a>
                                <form action="{{ route('vendor.merchandises.destroy', $item->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-light btn-sm rounded-3" onclick="return confirm('Hapus merch ini?')">
                                        <i class="bi bi-trash text-danger"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <img src="https://cdni.iconscout.com/illustration/premium/thumb/empty-cart-4816550-4004141.png" width="150" class="mb-3 opacity-50">
                            <p class="text-muted mb-0">Belum ada merchandise untuk event kamu.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection