@extends('v_vendor.v_layouts.app')

@section('title', 'Edit Event - ' . $event->title)

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');

    :root {
        --primary-gradient: linear-gradient(135deg, #7c3aed 0%, #db2777 100%);
        --card-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: #f8fafc;
    }

    .form-section-card {
        border: none;
        border-radius: 24px;
        box-shadow: var(--card-shadow);
        margin-bottom: 30px;
        overflow: hidden;
    }

    .form-section-header {
        padding: 25px 30px;
        background: white;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .form-section-header i {
        width: 45px;
        height: 45px;
        background: #f5f3ff;
        color: #7c3aed;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .form-label-custom {
        font-weight: 700;
        font-size: 0.85rem;
        color: #475569;
        margin-bottom: 12px;
        display: block;
        min-height: 20px;
        white-space: nowrap;
    }

    .form-control-custom {
        border-radius: 14px;
        border: 2px solid #f1f5f9;
        padding: 12px 14px; /* Reduced horizontal padding */
        font-size: 0.95rem;
        font-weight: 600;
        transition: all 0.3s;
    }

    .form-control-custom:focus {
        border-color: #7c3aed;
        box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.1);
        outline: none;
    }

    .ticket-row, .merch-row {
        background: white;
        border: 2px solid #f1f5f9;
        border-radius: 24px;
        padding: 30px;
        position: relative;
        margin-bottom: 25px;
        transition: all 0.3s;
        box-shadow: 0 4px 12px rgba(0,0,0,0.02);
    }

    .ticket-row:hover, .merch-row:hover {
        border-color: #7c3aed;
        box-shadow: 0 10px 25px rgba(124, 58, 237, 0.08);
    }

    .remove-btn {
        position: absolute;
        top: 15px;
        right: 15px;
        width: 32px;
        height: 32px;
        background: #fee2e2;
        color: #ef4444;
        border: none;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: 0.3s;
        z-index: 10;
        font-size: 0.9rem;
    }

    .remove-btn:hover {
        background: #ef4444;
        color: white;
        transform: rotate(90deg);
    }

    .btn-add-item {
        background: #f5f3ff;
        color: #7c3aed;
        border: 2px dashed #ddd6fe;
        border-radius: 16px;
        padding: 12px 24px;
        font-weight: 700;
        transition: 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-add-item:hover {
        background: #7c3aed;
        color: white;
        border-color: #7c3aed;
    }

    .preview-box {
        border-radius: 16px;
        overflow: hidden;
        border: 2px solid #f1f5f9;
        background: #f8fafc;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f1f5f9; /* Neutral background for contain */
    }

    .preview-box img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain; /* Show full image */
    }

    .sticky-action-bar {
        position: sticky;
        bottom: 20px;
        z-index: 100;
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(15px);
        padding: 20px;
        border-radius: 24px;
        border: 1px solid rgba(255,255,255,0.3);
        box-shadow: 0 20px 50px rgba(0,0,0,0.1);
        margin-top: 40px;
    }

    .btn-save {
        background: var(--primary-gradient);
        color: white;
        border: none;
        padding: 15px 40px;
        border-radius: 16px;
        font-weight: 800;
        letter-spacing: 0.5px;
        box-shadow: 0 10px 25px rgba(124, 58, 237, 0.3);
        transition: 0.3s;
    }

    .btn-save:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(124, 58, 237, 0.4);
        color: white;
    }

    .sort-handle {
        position: absolute;
        top: 15px;
        right: 55px;
        width: 32px;
        height: 32px;
        background: #f1f5f9;
        color: #64748b;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: grab;
        z-index: 10;
        transition: 0.3s;
    }

    .sort-handle:active {
        cursor: grabbing;
        background: #7c3aed;
        color: white;
    }

    .sortable-ghost {
        opacity: 0.4;
        border: 2px dashed #7c3aed !important;
        background: #f5f3ff !important;
    }

    .sortable-drag {
        background: white !important;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
    }
    .custom-switch-purple:checked {
        background-image: linear-gradient(135deg, #7c3aed 0%, #db2777 100%) !important;
        border-color: #7c3aed !important;
    }
    .custom-switch-purple:focus {
        box-shadow: 0 0 0 0.25rem rgba(124, 58, 237, 0.25) !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%237c3aed'/%3e%3c/svg%3e") !important;
    }
</style>

<div class="container-fluid px-4 py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div class="d-flex align-items-center gap-3 w-100" style="min-width: 0;">
            <a href="{{ route('vendor.events.index') }}" class="btn btn-white shadow-sm border-0 rounded-circle p-3 flex-shrink-0" style="background: white;">
                <i class="bi bi-arrow-left fs-5"></i>
            </a>
            <div style="min-width: 0; overflow: hidden;">
                <h3 class="fw-bold mb-0 text-truncate" style="letter-spacing: -0.5px;">Edit Your Masterpiece</h3>
                <p class="text-muted mb-0 text-truncate">Lengkapi detail untuk event <strong>{{ $event->title }}</strong></p>
            </div>
        </div>
    </div>

    @if($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal!',
                html: '<ul class="text-start small text-danger">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                confirmButtonColor: '#7c3aed',
            });
        });
    </script>
    @endif

    <form action="{{ route('vendor.events.update', $event->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-4">
            {{-- KOLOM KIRI: CORE DATA --}}
            <div class="col-lg-8">
                
                {{-- BASIC INFO --}}
                <div class="card form-section-card">
                    <div class="form-section-header">
                        <i class="bi bi-info-circle"></i>
                        <h5 class="fw-bold mb-0">Informasi Dasar</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3 mb-4">
                            <div class="col-md-8">
                                <label class="form-label-custom">Judul Event</label>
                                <input type="text" name="title" value="{{ old('title', $event->title) }}" class="form-control form-control-custom @error('title') is-invalid @enderror" placeholder="Nama konser atau event Anda" required>
                                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-custom">Kategori Event</label>
                                <select name="category" class="form-select form-control-custom @error('category') is-invalid @enderror" required>
                                    <option value="" disabled>Pilih Kategori</option>
                                    <option value="Music" {{ old('category', $event->category) == 'Music' ? 'selected' : '' }}>Music</option>
                                    <option value="Exhibition" {{ old('category', $event->category) == 'Exhibition' ? 'selected' : '' }}>Exhibition</option>
                                    <option value="Sports" {{ old('category', $event->category) == 'Sports' ? 'selected' : '' }}>Sports</option>
                                    <option value="Festival" {{ old('category', $event->category) == 'Festival' ? 'selected' : '' }}>Festival</option>
                                    <option value="Lainnya" {{ old('category', $event->category) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label-custom">Tanggal</label>
                                <input type="date" name="date" value="{{ old('date', \Carbon\Carbon::parse($event->date)->format('Y-m-d')) }}" class="form-control form-control-custom @error('date') is-invalid @enderror" required>
                                @error('date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-custom">Waktu Mulai</label>
                                <input type="time" name="time" value="{{ old('time', \Carbon\Carbon::parse($event->time)->format('H:i')) }}" class="form-control form-control-custom @error('time') is-invalid @enderror" required>
                                @error('time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-custom">Waktu Open Gate</label>
                                <input type="time" name="gate_open_time" value="{{ old('gate_open_time', $event->gate_open_time ? \Carbon\Carbon::parse($event->gate_open_time)->format('H:i') : '') }}" class="form-control form-control-custom @error('gate_open_time') is-invalid @enderror">
                                @error('gate_open_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-8">
                                <label class="form-label-custom">Venue / Lokasi</label>
                                <input type="text" name="venue" value="{{ old('venue', $event->venue) }}" class="form-control form-control-custom @error('venue') is-invalid @enderror" placeholder="Nama gedung atau tempat" required>
                                @error('venue') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-custom">Max Tiket / User</label>
                                <input type="number" name="max_tickets_per_user" value="{{ old('max_tickets_per_user', $event->max_tickets_per_user) }}" class="form-control form-control-custom @error('max_tickets_per_user') is-invalid @enderror" min="1" required>
                                @error('max_tickets_per_user') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label-custom">Deskripsi Event</label>
                            <textarea name="description" rows="6" class="form-control form-control-custom @error('description') is-invalid @enderror" placeholder="Jelaskan detail event Anda agar pembeli tertarik..." required>{{ old('description', $event->description) }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                {{-- TICKET CATEGORIES --}}
                <div class="card form-section-card">
                    <div class="form-section-header justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-ticket-perforated"></i>
                            <h5 class="fw-bold mb-0">Kategori Tiket <small class="fw-normal text-muted ms-2 fs-6">(Drag untuk atur urutan)</small></h5>
                        </div>
                        <button type="button" class="btn btn-add-item" onclick="addTicketRow()">
                            <i class="bi bi-plus-lg"></i> Tambah Kategori
                        </button>
                    </div>
                    <div class="card-body p-4">
                        <div id="ticket-container">
                            @php
                                $oldTickets = old('ticket_names');
                                $displayTickets = $oldTickets 
                                    ? collect($oldTickets)->map(fn($name, $i) => (object)[
                                        'id' => old('ticket_ids')[$i] ?? null,
                                        'name' => $name,
                                        'type' => old('ticket_types')[$i] ?? 'seating',
                                        'price' => old('ticket_prices')[$i] ?? 0,
                                        'quota' => old('ticket_quotas')[$i] ?? 0,
                                        'benefits' => old('ticket_benefits')[$i] ?? null,
                                    ])
                                    : $event->ticket_categories;
                            @endphp

                            @foreach($displayTickets as $ticket)
                                <div class="ticket-row draggable-item">
                                    <button type="button" class="remove-btn" onclick="this.parentElement.remove()"><i class="bi bi-trash3-fill"></i></button>
                                    <div class="sort-handle" title="Drag untuk atur urutan"><i class="bi bi-grip-vertical"></i></div>
                                    <input type="hidden" name="ticket_ids[]" value="{{ $ticket->id }}">
                                    <div class="row g-3 align-items-start">
                                        <div class="col-md-3">
                                            <label class="form-label-custom">Nama Kategori</label>
                                            <input type="text" name="ticket_names[]" class="form-control form-control-custom" value="{{ $ticket->name }}" placeholder="VIP / Festival" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label-custom">Tipe Penataan</label>
                                            <select name="ticket_types[]" class="form-select form-control-custom">
                                                <option value="seating" {{ $ticket->type == 'seating' ? 'selected' : '' }}>Seating (Punya Kursi)</option>
                                                <option value="standing" {{ $ticket->type == 'standing' ? 'selected' : '' }}>Standing (Berdiri)</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label-custom">Harga (Rp)</label>
                                            <input type="number" name="ticket_prices[]" class="form-control form-control-custom" value="{{ $ticket->price }}" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label-custom">Kuota</label>
                                            <input type="number" name="ticket_quotas[]" class="form-control form-control-custom" value="{{ $ticket->quota }}" required>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label-custom">Benefit (Gunakan koma sebagai pemisah)</label>
                                            <input type="text" name="ticket_benefits[]" class="form-control form-control-custom" value="{{ $ticket->benefits }}" placeholder="Snack Box, Front Row, Hi-Touch">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- MERCHANDISE --}}
                <div class="card form-section-card">
                    <div class="form-section-header justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-bag-heart"></i>
                            <h5 class="fw-bold mb-0">Merchandise Event <small class="fw-normal text-muted ms-2 fs-6">(Drag untuk atur urutan)</small></h5>
                        </div>
                        <button type="button" class="btn btn-add-item" onclick="addMerchRow()">
                            <i class="bi bi-plus-lg"></i> Tambah Merchandise
                        </button>
                    </div>
                    <div class="card-body p-4">
                        <div id="merch-container">
                            @php
                                $oldMerch = old('merch_names');
                                $displayMerch = $oldMerch 
                                    ? collect($oldMerch)->map(fn($name, $i) => (object)[
                                        'id' => old('merch_ids')[$i] ?? null,
                                        'name' => $name,
                                        'price' => old('merch_prices')[$i] ?? 0,
                                        'stock' => old('merch_stocks')[$i] ?? 0,
                                        'sizes' => old('merch_sizes')[$i] ?? null,
                                        'versions' => old('merch_versions')[$i] ?? null,
                                        'image' => old("merch_images.$i") ? null : (\App\Models\Merchandise::find(old('merch_ids')[$i])->image ?? null),
                                    ])
                                    : $event->merchandises;
                            @endphp

                            @foreach($displayMerch as $index => $merch)
                                <div class="merch-row draggable-item">
                                    <button type="button" class="remove-btn" onclick="this.parentElement.remove()"><i class="bi bi-trash3-fill"></i></button>
                                    <div class="sort-handle" title="Drag untuk atur urutan"><i class="bi bi-grip-vertical"></i></div>
                                    <input type="hidden" name="merch_ids[]" value="{{ $merch->id }}">
                                    <div class="row g-4">
                                        <div class="col-md-3">
                                            <label class="form-label-custom">Foto Produk</label>
                                            <div id="merchPreview_{{ $index }}" class="preview-box" style="height: 160px;">
                                                <img id="merchImg_{{ $index }}" src="{{ $merch->image ? asset('storage/' . $merch->image) : 'https://placehold.co/160x160?text=No+Image' }}" class="w-100 h-100 object-fit-contain">
                                            </div>
                                            <input type="file" name="merch_images[{{ $index }}]" class="form-control form-control-sm mt-2" accept="image/*" onchange="previewImage(this, 'merchPreview_{{ $index }}', 'merchImg_{{ $index }}')">
                                            <small class="text-muted d-block mt-2">Maks 10MB. Kosongkan jika tidak diganti.</small>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="mb-4">
                                                <label class="form-label-custom">Nama Merchandise</label>
                                                <input type="text" name="merch_names[]" class="form-control form-control-custom" value="{{ $merch->name }}" placeholder="Contoh: Lightstick, T-Shirt XL, dll" required>
                                            </div>
                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <label class="form-label-custom">Harga (Rp)</label>
                                                    <input type="number" name="merch_prices[]" class="form-control form-control-custom" value="{{ $merch->price }}" placeholder="0" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label-custom">Stok</label>
                                                    <input type="number" name="merch_stocks[]" class="form-control form-control-custom" value="{{ $merch->stock ?? 0 }}" placeholder="0" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label-custom">Ukuran (S,M,L...)</label>
                                                    <input type="text" name="merch_sizes[]" class="form-control form-control-custom" value="{{ $merch->sizes }}" placeholder="Kosongkan jika tidak ada">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label-custom">Versi/Varian</label>
                                                    <input type="text" name="merch_versions[]" class="form-control form-control-custom" value="{{ $merch->versions }}" placeholder="Member Name / Ver">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>

            {{-- KOLOM KANAN: MEDIA & PUBLISH --}}
            <div class="col-lg-4">
                
                {{-- VISUALS --}}
                <div class="card form-section-card">
                    <div class="form-section-header">
                        <i class="bi bi-images"></i>
                        <h5 class="fw-bold mb-0">Visual & Media</h5>
                    </div>
                    <div class="card-body p-4">
                        {{-- Poster --}}
                        <div class="mb-4">
                            <label class="form-label-custom">Poster Utama Event</label>
                            <div class="preview-box mb-3">
                                <img id="previewImg" src="{{ asset('storage/' . $event->image) }}" class="w-100" style="max-height: 400px; object-fit: cover;">
                            </div>
                            <input type="file" name="image" class="form-control form-control-custom @error('image') is-invalid @enderror" accept="image/*" onchange="previewImage(this, 'previewImg', 'previewImg')">
                            <small class="text-muted d-block mt-2">Format JPG/PNG, maksimal 10MB. Kosongkan jika tidak ingin mengubah poster.</small>
                            @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Seatmap --}}
                        <div class="mb-4">
                            <label class="form-label-custom">Peta Kursi / Seatplan</label>
                            <div class="preview-box mb-3">
                                <img id="previewSeatImg" src="{{ $event->seat_plan ? asset('storage/' . $event->seat_plan) : 'https://placehold.co/400x250?text=Seatplan' }}" class="w-100" style="max-height: 200px; object-fit: cover;">
                            </div>
                            <input type="file" name="seat_plan" class="form-control form-control-custom @error('seat_plan') is-invalid @enderror" accept="image/*" onchange="previewImage(this, 'previewSeatImg', 'previewSeatImg')">
                            <small class="text-muted d-block mt-2">Format JPG/PNG, maksimal 10MB. Kosongkan jika tidak ingin mengubah.</small>
                            @error('seat_plan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Venue Map --}}
                        <div class="mb-4">
                            <label class="form-label-custom">Denah Venue (Gate/Toilet/dll)</label>
                            <div class="preview-box mb-3">
                                <img id="previewVenueMapImg" src="{{ $event->venue_map ? asset('storage/' . $event->venue_map) : 'https://placehold.co/400x250?text=Venue+Map' }}" class="w-100" style="max-height: 200px; object-fit: cover;">
                            </div>
                            <input type="file" name="venue_map" class="form-control form-control-custom @error('venue_map') is-invalid @enderror" accept="image/*" onchange="previewImage(this, 'previewVenueMapImg', 'previewVenueMapImg')">
                            <small class="text-muted d-block mt-2">Format JPG/PNG, maksimal 10MB. Kosongkan jika tidak ingin mengubah.</small>
                            @error('venue_map') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Maps Link --}}
                        <div class="mb-0">
                            <label class="form-label-custom">Link Google Maps</label>
                            <input type="url" name="venue_location_url" value="{{ old('venue_location_url', $event->venue_location_url) }}" class="form-control form-control-custom @error('venue_location_url') is-invalid @enderror" placeholder="https://goo.gl/maps/...">
                            @error('venue_location_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                         <br>
                        {{-- Spotify --}}
                        <div class="mb-0">
                            <label class="form-label-custom">
                                <i class="bi bi-spotify me-1 text-success"></i> Spotify Playlist ID
                                <span class="fw-normal">(opsional)</span>
                            </label>
                            <input type="text" name="spotify_playlist_id" class="form-control form-control-custom"
                                placeholder="Contoh: 37i9dQZF1DX9tPFwDMOaN1"
                                value="{{ old('spotify_playlist_id', $event->spotify_playlist_id ?? '') }}">
                            <div class="form-text">
                                Buka Spotify → cari playlist → klik titik tiga → Share → Copy Link<br>
                                Contoh: <code>https://open.spotify.com/playlist/<strong>37i9dQZF1DX9tPFwDMOaN1</strong></code>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- INFORMASI PENTING --}}
                <div class="card form-section-card">
                    <div class="form-section-header">
                        <i class="bi bi-shield-lock"></i>
                        <h5 class="fw-bold mb-0">Informasi Penting</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <label class="form-label-custom">Waktu Open Sale (War Ticket)</label>
                            <input type="datetime-local" name="open_sale_at" value="{{ old('open_sale_at', $event->open_sale_at ? \Carbon\Carbon::parse($event->open_sale_at)->format('Y-m-d\TH:i') : '') }}" class="form-control form-control-custom @error('open_sale_at') is-invalid @enderror">
                            @error('open_sale_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label-custom">Syarat & Ketentuan (Teks)</label>
                            <textarea name="terms" rows="5" class="form-control form-control-custom @error('terms') is-invalid @enderror" placeholder="Ketik S&K di sini...">{{ old('terms', $event->terms) }}</textarea>
                            @error('terms') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label-custom">Poster S&K (Opsional)</label>
                            <input type="file" name="terms_image" class="form-control form-control-custom @error('terms_image') is-invalid @enderror" accept="image/*">
                            <small class="text-muted d-block mt-2">Format JPG/PNG, maksimal 10MB.</small>
                            @error('terms_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="mb-0 border-top pt-4 mt-4">
                            <div class="form-check form-switch d-flex align-items-center gap-3">
                                <input class="form-check-input custom-switch-purple" type="checkbox" role="switch" id="is_tenant_open" name="is_tenant_open" value="1" style="transform: scale(1.8); margin-top: 0; cursor: pointer;" onchange="toggleTenantSettings()" {{ old('is_tenant_open', $event->is_tenant_open) ? 'checked' : '' }}>
                                <div>
                                    <label class="form-check-label fw-bold fs-5 mb-0" for="is_tenant_open" style="cursor: pointer; color: #1e1b4b;">Buka Pendaftaran Tenant</label>
                                    <small class="text-muted d-block mt-1">Centang jika Anda mengizinkan pelaku usaha mendaftar booth di event ini.</small>
                                </div>
                            </div>

                            {{-- Tenant Settings --}}
                            <div id="tenant_settings_container" class="mt-4 p-4 bg-light rounded-4 border {{ old('is_tenant_open', $event->is_tenant_open) ? '' : 'd-none' }}">
                                <div class="mb-3">
                                    <label class="form-label-custom text-dark"><i class="bi bi-tag-fill me-1" style="color: #8b5cf6;"></i>Harga Sewa Booth (Rp)</label>
                                    <input type="number" name="tenant_booth_price" class="form-control form-control-custom" placeholder="Contoh: 500000" min="0" value="{{ old('tenant_booth_price', $event->tenant_booth_price ?? 0) }}">
                                    <small class="text-muted mt-1 d-block">Biaya yang harus dibayar tenant jika pengajuan disetujui. Isi 0 jika gratis.</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label-custom text-dark"><i class="bi bi-people-fill me-1" style="color: #8b5cf6;"></i>Kuota Tenant (Opsional)</label>
                                    <input type="number" name="tenant_quota" value="{{ old('tenant_quota', $event->tenant_quota) }}" class="form-control form-control-custom" placeholder="Contoh: 20" min="1">
                                    <small class="text-muted mt-1 d-block">Batas maksimal tenant yang akan diterima. Kosongkan jika tanpa batas.</small>
                                </div>
                                <div class="mb-0">
                                    <label class="form-label-custom text-dark"><i class="bi bi-map-fill me-1" style="color: #8b5cf6;"></i>Denah / Maps Booth (Opsional)</label>
                                    @if($event->booth_map)
                                        <div class="mb-2">
                                            <img src="{{ asset('storage/' . $event->booth_map) }}" alt="Booth Map" style="max-height: 100px; border-radius: 8px;">
                                        </div>
                                    @endif
                                    <input type="file" name="booth_map" class="form-control form-control-custom" accept="image/*">
                                    <small class="text-muted mt-1 d-block">Format JPG/PNG, maksimal 10MB. Hanya akan ditampilkan ke tenant setelah pembayaran lunas.</small>
                                    
                                    <div class="mt-3 bg-white p-3 rounded-3 border border-purple">
                                        <label class="form-label-custom text-dark small mb-1">Pemberitahuan Denah Tenant (Opsional)</label>
                                        <input type="text" name="map_notice" value="{{ old('map_notice', $event->map_notice) }}" class="form-control form-control-sm form-control-custom" placeholder="Contoh: Denah booth akan diinformasikan H-3">
                                        <small class="text-muted d-block mt-1">Hanya akan ditampilkan kepada tenant jika gambar denah booth belum diunggah.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- STICKY ACTION BAR --}}
        <div class="sticky-action-bar d-flex justify-content-between align-items-center">
            <div class="d-none d-md-block">
                <p class="mb-0 fw-bold text-dark">Lakukan pengecekan ulang sebelum menyimpan.</p>
                <p class="mb-0 small text-muted">Perubahan akan langsung terlihat oleh publik.</p>
            </div>
            <div class="d-flex gap-3">
                <a href="{{ route('vendor.events.index') }}" class="btn btn-light rounded-pill px-4 fw-bold py-3">Batalkan</a>
                <button type="submit" class="btn btn-save">
                    <i class="bi bi-cloud-check-fill me-2"></i> Update Event Sekarang
                </button>
            </div>
        </div>

    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Sorting for Tickets
    new Sortable(document.getElementById('ticket-container'), {
        animation: 150,
        handle: '.sort-handle',
        ghostClass: 'sortable-ghost',
        dragClass: 'sortable-drag'
    });

    // Initialize Sorting for Merch
    new Sortable(document.getElementById('merch-container'), {
        animation: 150,
        handle: '.sort-handle',
        ghostClass: 'sortable-ghost',
        dragClass: 'sortable-drag'
    });
});

function addTicketRow() {
    const container = document.getElementById('ticket-container');
    const div = document.createElement('div');
    div.className = 'ticket-row draggable-item';
    div.innerHTML = `
        <button type="button" class="remove-btn" onclick="this.parentElement.remove(); reindexTickets();"><i class="bi bi-trash3-fill"></i></button>
        <div class="sort-handle" title="Drag untuk atur urutan"><i class="bi bi-grip-vertical"></i></div>
        <input type="hidden" name="ticket_ids[]" value="">
        <div class="row g-3 align-items-start">
            <div class="col-md-3">
                <label class="form-label-custom">Nama Kategori</label>
                <input type="text" name="ticket_names[]" class="form-control form-control-custom" placeholder="VIP / Festival" required>
            </div>
            <div class="col-md-4">
                <label class="form-label-custom">Tipe Penataan</label>
                <select name="ticket_types[]" class="form-select form-control-custom">
                    <option value="seating">Seating (Punya Kursi)</option>
                    <option value="standing">Standing (Berdiri)</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label-custom">Harga (Rp)</label>
                <input type="number" name="ticket_prices[]" class="form-control form-control-custom" required>
            </div>
            <div class="col-md-2">
                <label class="form-label-custom">Kuota</label>
                <input type="number" name="ticket_quotas[]" class="form-control form-control-custom" required>
            </div>
            <div class="col-12">
                <label class="form-label-custom">Benefit</label>
                <input type="text" name="ticket_benefits[]" class="form-control form-control-custom" placeholder="Snack Box, Front Row, dll">
            </div>
        </div>
    `;
    container.appendChild(div);
}

function reindexMerch() {
    const container = document.getElementById('merch-container');
    const rows = container.querySelectorAll('.merch-row');
    rows.forEach((row, index) => {
        // Update IDs and attributes for preview
        const previewBox = row.querySelector('.preview-box');
        const img = row.querySelector('img');
        const fileInput = row.querySelector('input[type="file"]');
        
        previewBox.id = `merchPreview_${index}`;
        img.id = `merchImg_${index}`;
        fileInput.name = `merch_images[${index}]`;
        fileInput.setAttribute('onchange', `previewImage(this, 'merchPreview_${index}', 'merchImg_${index}')`);
    });
}

function addMerchRow() {
    const container = document.getElementById('merch-container');
    const index = container.children.length;
    const div = document.createElement('div');
    div.className = 'merch-row draggable-item';
    div.innerHTML = `
        <button type="button" class="remove-btn" onclick="this.parentElement.remove(); reindexMerch();"><i class="bi bi-trash3-fill"></i></button>
        <div class="sort-handle" title="Drag untuk atur urutan"><i class="bi bi-grip-vertical"></i></div>
        <input type="hidden" name="merch_ids[]" value="">
        <div class="row g-4">
            <div class="col-md-3">
                <label class="form-label-custom">Foto Produk</label>
                <div id="merchPreview_${index}" class="preview-box d-none" style="height: 160px;">
                    <img id="merchImg_${index}" src="" class="w-100 h-100 object-fit-contain">
                </div>
                <input type="file" name="merch_images[${index}]" class="form-control form-control-sm mt-2" accept="image/*" onchange="previewImage(this, 'merchPreview_${index}', 'merchImg_${index}')">
                <small class="text-muted d-block mt-2">Maks 10MB.</small>
            </div>
            <div class="col-md-9">
                <div class="mb-4">
                    <label class="form-label-custom">Nama Merchandise</label>
                    <input type="text" name="merch_names[]" class="form-control form-control-custom" placeholder="Contoh: Lightstick, T-Shirt XL, dll" required>
                </div>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label-custom">Harga (Rp)</label>
                        <input type="number" name="merch_prices[]" class="form-control form-control-custom" placeholder="0" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label-custom">Stok</label>
                        <input type="number" name="merch_stocks[]" class="form-control form-control-custom" placeholder="0" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label-custom">Ukuran (S,M,L...)</label>
                        <input type="text" name="merch_sizes[]" class="form-control form-control-custom" placeholder="S, M, L, XL">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label-custom">Versi/Varian</label>
                        <input type="text" name="merch_versions[]" class="form-control form-control-custom" placeholder="Member / Ver">
                    </div>
                </div>
            </div>
        </div>
    `;
    container.appendChild(div);
    reindexMerch(); // Ensure correct index
}

function previewImage(input, previewId, imgId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = (e) => {
            const img = document.getElementById(imgId);
            if (img) img.src = e.target.result;
            const preview = document.getElementById(previewId);
            if (preview) preview.classList.remove('d-none');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function toggleTenantSettings() {
    const isChecked = document.getElementById('is_tenant_open').checked;
    const container = document.getElementById('tenant_settings_container');
    if (isChecked) {
        container.classList.remove('d-none');
    } else {
        container.classList.add('d-none');
    }
}
</script>
@endsection