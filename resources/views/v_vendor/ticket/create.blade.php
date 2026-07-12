@extends('v_vendor.v_layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold">Tambah Kategori Tiket: {{ $event->title }}</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('vendor.ticket_categories.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="event_id" value="{{ $event->id }}">

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Nama Kategori</label>
                            <input type="text" name="name" class="form-control rounded-3" placeholder="Contoh: VIP, Festival, Early Bird" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold">Harga (Rp)</label>
                                <input type="number" name="price" class="form-control rounded-3" placeholder="0" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold">Kuota Tiket</label>
                                <input type="number" name="quota" class="form-control rounded-3" placeholder="Contoh: 100" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold">Benefit / Fasilitas</label>
                            <textarea name="benefits" class="form-control rounded-3" rows="3" placeholder="Contoh: Free Drink, Front Row Seat, Merchandise"></textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Kategori</button>
                            <a href="{{ route('vendor.ticket_categories.index', ['event_id' => $event->id]) }}" class="btn btn-light rounded-pill px-4">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection