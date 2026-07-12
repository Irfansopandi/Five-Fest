@extends('v_vendor.v_layouts.app')

@section('content')
<div class="container py-4">
    <h3 class="fw-bold mb-4">Tambah Merchandise Baru</h3>
    <form action="{{ route('vendor.merchandises.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card border-0 shadow-sm p-4">
            <div class="mb-3">
                <label class="form-label">Pilih Event</label>
                <select name="event_id" class="form-select" required>
                    @foreach($events as $event)
                        <option value="{{ $event->id }}">{{ $event->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Nama Produk</label>
                <input type="text" name="name" class="form-control" placeholder="Contoh: Kaos Official" required>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Harga (Rp)</label>
                    <input type="number" name="price" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Stok Awal</label>
                    <input type="number" name="stock" class="form-control" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Foto Produk</label>
                <input type="file" name="image" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary px-5">Simpan Merchandise</button>
        </div>
    </form>
</div>
@endsection