@extends('v_vendor.v_layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Kategori Tiket: {{ $event->title }}</h4>
            <p class="text-muted small">Atur jenis tiket seperti VIP, Festival, atau Early Bird.</p>
        </div>
        <a href="{{ route('vendor.ticket_categories.create', ['event_id' => $event->id]) }}" class="btn btn-primary rounded-pill px-4">
            <i class="bi bi-plus-lg me-2"></i>Tambah Kategori
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Nama Tiket</th>
                        <th>Harga</th>
                        <th>Kuota</th>
                        <th>Benefit</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $cat)
                    <tr>
                        <td class="ps-4 fw-bold">{{ $cat->name }}</td>
                        <td>Rp {{ number_format($cat->price, 0, ',', '.') }}</td>
                        <td>{{ $cat->quota }} tiket</td>
                        <td class="small text-muted">{{ $cat->benefits ?? '-' }}</td>
                        <td class="text-end pe-4">
                            <form action="{{ route('vendor.ticket_categories.destroy', $cat->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-light btn-sm text-danger" onclick="return confirm('Hapus kategori ini?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">Belum ada kategori tiket.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection