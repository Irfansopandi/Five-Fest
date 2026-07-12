@extends('admin.layouts.app')

@section('title', 'Pesan Kontak')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="h3 fw-bold mb-0">
                    <span class="p-2 rounded-3 me-2">
                        <i class="bi bi-envelope-exclamation-fill"></i>
                    </span> Pesan Kontak
                </h1>
                <small class="text-muted">Kelola pesan dari pelanggan dan pengunjung</small>
            </div>
            <div class="gap-2 d-flex">
                <a href="{{ route('admin.contact.export') }}"
                    class="btn"
                    style="border: 1px solid #7c3aed; color: #7c3aed;"
                    onmouseover="this.style.backgroundColor='#198754'; this.style.border='1px solid #198754'; this.style.color='white';"
                    onmouseout="this.style.backgroundColor='transparent'; this.style.border='1px solid #7c3aed'; this.style.color='#7c3aed';">
                    <i class="bi bi-download me-2"></i>Export CSV
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4 g-3">
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
            <div class="stat-card stat-card--blue">
                <div class="stat-card__icon"><i class="bi bi-envelope"></i></div>
                <div class="stat-card__body">
                    <div class="stat-card__label">Total Pesan</div>
                    <div class="stat-card__value">{{ $stats['total'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="200">
            <div class="stat-card stat-card--orange">
                <div class="stat-card__icon"><i class="bi bi-exclamation-circle"></i></div>
                <div class="stat-card__body">
                    <div class="stat-card__label">Belum Dibaca</div>
                    <div class="stat-card__value">{{ $stats['unread'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="300">
            <div class="stat-card stat-card--purple">
                <div class="stat-card__icon"><i class="bi bi-check-circle"></i></div>
                <div class="stat-card__body">
                    <div class="stat-card__label">Sudah Dibaca</div>
                    <div class="stat-card__value">{{ $stats['read'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="400">
            <div class="stat-card stat-card--green">
                <div class="stat-card__icon"><i class="bi bi-reply-fill"></i></div>
                <div class="stat-card__body">
                    <div class="stat-card__label">Sudah Dibalas</div>
                    <div class="stat-card__value">{{ $stats['replied'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="card border-0 shadow-sm mb-4" data-aos="fade-up" data-aos-delay="500">
        <div class="card-body">
            <form action="{{ route('admin.contact.index') }}" method="GET" class="row g-3">
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control"
                        placeholder="Cari nama atau email..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="unread"  {{ request('status') == 'unread'  ? 'selected' : '' }}>Ada Belum Dibaca</option>
                        <option value="read"    {{ request('status') == 'read'    ? 'selected' : '' }}>Ada Sudah Dibaca</option>
                        <option value="replied" {{ request('status') == 'replied' ? 'selected' : '' }}>Ada Sudah Dibalas</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100" style="background-color: #7c3aed;">
                        <i class="bi bi-search me-2"></i>Cari
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Conversations List -->
    <div class="card border-0 shadow-sm" data-aos="fade-up" data-aos-delay="600">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="fw-bold">Pengguna</th>
                        <th class="fw-bold text-center">Total Pesan</th>
                        <th class="fw-bold text-center">Belum Dibaca</th>
                        <th class="fw-bold text-center">Status</th>
                        <th class="fw-bold">Pesan Terakhir</th>
                        <th class="fw-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($conversations as $conv)
                        <tr class="{{ $conv->unread_count > 0 ? 'table-warning' : '' }}">

                            {{-- Avatar + Nama + Email --}}
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="position-relative flex-shrink-0">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white"
                                            style="width:38px;height:38px;background:linear-gradient(135deg,#7c3aed,#a855f7);font-size:0.9rem;">
                                            {{ strtoupper(substr($conv->name, 0, 1)) }}
                                        </div>
                                        {{-- Badge merah unread seperti WA --}}
                                        @if($conv->unread_count > 0)
                                            <span class="position-absolute d-flex align-items-center justify-content-center text-white fw-bold"
                                                style="top:-4px;right:-4px;min-width:18px;height:18px;background:#ef4444;border-radius:50%;font-size:9px;border:2px solid white;padding:0 3px;">
                                                {{ $conv->unread_count > 99 ? '99+' : $conv->unread_count }}
                                            </span>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-semibold {{ $conv->unread_count > 0 ? 'fw-bold' : '' }}">
                                            {{ $conv->name }}
                                        </div>
                                        <small class="text-muted">{{ $conv->email }}</small>
                                    </div>
                                </div>
                            </td>


                            {{-- Total Pesan --}}
                            <td class="text-center">
                                <span class="badge rounded-pill bg-secondary">
                                    {{ $conv->total_messages }}
                                </span>
                            </td>

                            {{-- Unread Count --}}
                            <td class="text-center">
                                @if($conv->unread_count > 0)
                                    <span class="badge rounded-pill bg-warning text-dark">
                                        {{ $conv->unread_count }} baru
                                    </span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>

                            {{-- Status overall --}}
                            <td class="text-center">
                                @if($conv->unread_count > 0)
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-exclamation-circle me-1"></i>Belum Dibaca
                                    </span>
                                @elseif($conv->replied_count > 0)
                                    <span class="badge bg-success">
                                        <i class="bi bi-reply-fill me-1"></i>Dibalas
                                    </span>
                                @else
                                    <span class="badge bg-info">
                                        <i class="bi bi-check-circle me-1"></i>Dibaca
                                    </span>
                                @endif
                            </td>

                            {{-- Waktu pesan terakhir --}}
                            <td class="text-muted small">
                                {{ \Carbon\Carbon::parse($conv->last_message_at)->format('d M Y H:i') }}
                            </td>

                            {{-- Aksi --}}
                            <td class="text-center">
                                <a href="{{ route('admin.contact.show', $conv->last_id) }}"
                                   class="btn btn-sm btn-primary"
                                   style="background-color: #7c3aed;">
                                    <i class="bi bi-chat-text me-1"></i>Buka Chat
                                </a>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox" style="font-size: 2rem;"></i><br>
                                <small>Tidak ada pesan</small>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white">
            {{ $conversations->links() }}
        </div>
    </div>
</div>
@endsection