@extends('v_vendor.v_layouts.app')

@section('title', 'Scanner Merchandise')

@section('content')
<div class="container-fluid px-4 py-3">

    {{-- HEADER --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color:#2d2d2d;">
                <i class="bi bi-qr-code-scan me-2" style="color:#667eea;"></i>Scanner Merchandise
            </h4>
            <p class="text-muted mb-0 small">Scan QR Code tiket untuk verifikasi pengambilan merchandise</p>
        </div>
    </div>

    <div class="row">

        {{-- KIRI: Area Scanner --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h6 class="fw-bold mb-0">Area Scanner</h6>
                </div>
                <div class="card-body p-4">
                    <div class="scanner-wrapper rounded-4 overflow-hidden bg-dark position-relative"
                         style="min-height:400px; border:1px solid rgba(0,0,0,0.1);">
                        <div id="reader"></div>
                    </div>

                    <div id="scanner-message" class="alert alert-info mt-4 border-0 rounded-3 small">
                        <i class="bi bi-info-circle me-2"></i>Menunggu inisialisasi kamera...
                    </div>

                    <div class="mt-4 pt-3 border-top">
                        <label class="form-label small fw-bold text-muted text-uppercase">Atau Input Manual</label>
                        <div class="input-group">
                            <input type="text" id="manual-code"
                                   class="form-control form-control-lg border-0 bg-light rounded-start-3"
                                   placeholder="Contoh: FF-XXXXXXXX"
                                   style="font-size:0.9rem;">
                            <button onclick="processManual()"
                                    class="btn px-4 rounded-end-3 text-white fw-semibold"
                                    style="background:linear-gradient(135deg,#667eea,#764ba2);">
                                <i class="bi bi-send-fill"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- KANAN: Hasil Scan --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h6 class="fw-bold mb-0">Hasil Scan</h6>
                </div>
                <div class="card-body p-4 text-center">

                    <div id="result-placeholder" class="py-5">
                        <div class="bg-light rounded-circle d-inline-flex p-4 mb-3">
                            <i class="bi bi-box-seam fs-1 text-muted"></i>
                        </div>
                        <p class="text-muted">Arahkan kamera ke QR Code tiket untuk melihat detail merchandise di sini.</p>
                    </div>

                    <div id="result-card" class="d-none">
                        <div id="result-header" class="alert mb-4 border-0 rounded-4 py-3 fw-bold"></div>

                        <div class="text-center mb-4">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center text-white fw-bold mb-3"
                                 style="width:80px;height:80px;font-size:2rem;background:linear-gradient(135deg,#667eea,#764ba2);">
                                <span id="res-initial">U</span>
                            </div>
                            <h4 id="res-user-name" class="fw-bold mb-1">-</h4>
                            <span id="res-booking-code" class="badge bg-light text-dark border px-3 py-2">-</span>
                        </div>

                        <div class="text-start bg-light p-3 rounded-4 mb-3">
                            <small class="text-muted text-uppercase" style="font-size:.65rem;">Event</small>
                            <div class="fw-bold small" id="res-event">-</div>
                        </div>

                        <div id="res-merch-list" class="text-start mb-3"></div>

                        <button onclick="resetScanner()"
                                class="btn w-100 mt-2 rounded-pill py-3 fw-bold text-white shadow-sm"
                                style="background:linear-gradient(135deg,#667eea,#764ba2);">
                            <i class="bi bi-arrow-repeat me-2"></i>SCAN BERIKUTNYA
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Riwayat Scan Hari Ini --}}
    <div class="row mt-2">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-clock-history me-2" style="color:#667eea;"></i>Riwayat Scan Hari Ini
                    </h6>
                    <div class="d-flex align-items-center gap-2">
                        <span class="small text-muted">Tampilkan:</span>
                        <select class="form-select form-select-sm rounded-pill" style="width:auto;" onchange="changePerPage(this.value)">
                            @foreach([5, 10, 25, 50] as $opt)
                                <option value="{{ $opt }}" {{ $perPage == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                        <span class="badge rounded-pill px-3 py-2 text-white" id="scanCountBadge"
                              style="background:linear-gradient(135deg,#667eea,#764ba2);">
                            {{ $scanHistory->total() }} Scan
                        </span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3 text-muted small text-uppercase">Waktu</th>
                                    <th class="py-3 text-muted small text-uppercase">Kode Booking</th>
                                    <th class="py-3 text-muted small text-uppercase">Pembeli</th>
                                    <th class="py-3 text-muted small text-uppercase">Merchandise</th>
                                    <th class="py-3 text-muted small text-uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody id="scan-history-body">
                                @forelse($scanHistory as $b)
                                <tr id="row-{{ $b->id }}">
                                    <td class="px-4 py-3 small">
                                        {{ $b->merchandises->first()?->pivot->collected_at ? \Carbon\Carbon::parse($b->merchandises->first()->pivot->collected_at)->format('H:i:s') : '-' }}
                                    </td>
                                    <td class="py-3 font-monospace fw-bold small" style="color:#667eea;">
                                        {{ $b->booking_code }}
                                    </td>
                                    <td class="py-3 small fw-bold">{{ $b->user->name }}</td>
                                    <td class="py-3 small text-muted">
                                        {{ $b->merchandises->map(fn($m) => $m->name . ' x' . $m->pivot->quantity)->join(', ') }}
                                    </td>
                                    <td class="py-3">
                                        <span class="badge bg-success rounded-pill">Berhasil</span>
                                    </td>
                                </tr>
                                @empty
                                <tr id="empty-row">
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-2 mb-3 d-block opacity-50"></i>
                                        Belum ada scan hari ini
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($scanHistory->hasPages())
                    <div class="d-flex justify-content-end px-4 py-3 border-top">
                        {{ $scanHistory->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>

<style>
    #reader { border:none !important; width:100% !important; background:transparent !important; }
    #reader video { border-radius:12px; }
    #reader__dashboard_section_csr button,
    #reader__dashboard_section_fsr button {
        background: linear-gradient(135deg,#667eea,#764ba2) !important;
        border: none !important;
        padding: 10px 20px !important;
        border-radius: 10px !important;
        color: white !important;
        font-weight: 600 !important;
        margin: 5px !important;
    }
    #reader__camera_selection {
        background: #f8fafc !important;
        border: 1px solid #e2e8f0 !important;
        padding: 8px !important;
        border-radius: 10px !important;
        width: 100%;
        margin-bottom: 10px;
    }
</style>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    let html5QrcodeScanner = new Html5QrcodeScanner("reader", {
        fps: 20,
        qrbox: function(w, h) {
            let size = Math.floor(Math.min(w, h) * 0.7);
            return { width: size, height: size };
        },
        rememberLastUsedCamera: true,
        aspectRatio: 1.0,
        supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA]
    });

    function onScanSuccess(decodedText) {
        html5QrcodeScanner.clear();
        setMessage('warning', 'Sedang memproses: <strong>' + decodedText + '</strong>');
        processMerch(decodedText);
    }

    function onScanFailure() {}

    html5QrcodeScanner.render(onScanSuccess, onScanFailure);

    function processManual() {
        const input = document.getElementById('manual-code');
        const code  = input.value.trim();
        if (!code) { alert('Masukkan kode booking terlebih dahulu.'); return; }
        html5QrcodeScanner.clear();
        setMessage('warning', 'Sedang memproses: <strong>' + code + '</strong>');
        processMerch(code);
        input.value = '';
    }

    async function processMerch(code) {
        try {
            const res  = await fetch('{{ route("vendor.staff.scanner.scan") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ booking_code: code })
            });
            const data = await res.json();
            showResult(data, code);
        } catch(e) {
            showResult({ status: 'error', message: 'Gagal menghubungi server.' }, code);
        }
    }

    function showResult(data, code) {
        document.getElementById('result-placeholder').classList.add('d-none');
        document.getElementById('result-card').classList.remove('d-none');

        const header = document.getElementById('result-header');

        if (data.status === 'success') {
            header.className = 'alert alert-success mb-4 border-0 rounded-4 py-3 fw-bold';
            header.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i>MERCHANDISE TERVERIFIKASI!';
            setMessage('success', data.message);
            fillResult(data.booking);
            addHistoryRow(data.booking, 'success');
            playBeep('success');

        } else if (data.status === 'already') {
            header.className = 'alert alert-warning mb-4 border-0 rounded-4 py-3 fw-bold';
            header.innerHTML = '<i class="bi bi-exclamation-triangle-fill me-2"></i>SUDAH DIAMBIL SEBELUMNYA';
            setMessage('warning', 'Merchandise untuk booking ini sudah diambil.');
            fillResult(data.booking);
            playBeep('warning');

        } else {
            header.className = 'alert alert-danger mb-4 border-0 rounded-4 py-3 fw-bold';
            header.innerHTML = '<i class="bi bi-x-circle-fill me-2"></i>GAGAL';
            setMessage('danger', data.message);
            document.getElementById('res-user-name').innerText    = '-';
            document.getElementById('res-initial').innerText      = '?';
            document.getElementById('res-booking-code').innerText = code;
            document.getElementById('res-event').innerText        = '-';
            document.getElementById('res-merch-list').innerHTML   = '';
            playBeep('error');
        }
    }

    function fillResult(booking) {
        document.getElementById('res-user-name').innerText    = booking.buyer_name;
        document.getElementById('res-initial').innerText      = booking.buyer_name.charAt(0).toUpperCase();
        document.getElementById('res-booking-code').innerText = booking.booking_code;
        document.getElementById('res-event').innerText        = booking.event_name;

        let merchHTML = '<p class="fw-semibold small text-muted text-uppercase mb-2" style="letter-spacing:1px;">Merchandise</p>';
        booking.merchandises.forEach(m => {
            merchHTML += `
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <div>
                        <span class="fw-semibold small">${m.name}</span>
                        <span class="badge ms-2 text-white" style="background:#667eea;">x${m.quantity}</span>
                    </div>
                    <span class="text-muted small">Rp ${Number(m.price * m.quantity).toLocaleString('id-ID')}</span>
                </div>`;
        });
        document.getElementById('res-merch-list').innerHTML = merchHTML;
    }

    function addHistoryRow(booking, type) {
        const emptyRow = document.getElementById('empty-row');
        if (emptyRow) emptyRow.remove();

        const time  = new Date().toLocaleTimeString('id-ID', { hour:'2-digit', minute:'2-digit', second:'2-digit' });
        const merch = booking.merchandises.map(m => m.name + ' x' + m.quantity).join(', ');

        const row = document.createElement('tr');
        row.className = 'table-success';
        row.innerHTML = `
            <td class="px-4 py-3 small">${time}</td>
            <td class="py-3 font-monospace fw-bold small" style="color:#667eea;">${booking.booking_code}</td>
            <td class="py-3 small fw-bold">${booking.buyer_name}</td>
            <td class="py-3 small text-muted">${merch}</td>
            <td class="py-3"><span class="badge bg-success rounded-pill">Berhasil</span></td>
        `;

        document.getElementById('scan-history-body').insertBefore(row, document.getElementById('scan-history-body').firstChild);
        setTimeout(() => row.classList.remove('table-success'), 3000);
    }

    function changePerPage(value) {
        const url = new URL(window.location.href);
        url.searchParams.set('per_page', value);
        url.searchParams.delete('page');
        window.location.href = url.toString();
    }

    function setMessage(type, msg) {
        const el = document.getElementById('scanner-message');
        el.className = `alert alert-${type} mt-4 border-0 rounded-3 small`;
        el.innerHTML = `<i class="bi bi-info-circle me-2"></i>${msg}`;
    }

    function resetScanner() {
        document.getElementById('result-placeholder').classList.remove('d-none');
        document.getElementById('result-card').classList.add('d-none');
        setMessage('info', 'Menunggu inisialisasi kamera...');
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    }

    function playBeep(type) {
        try {
            const ctx  = new (window.AudioContext || window.webkitAudioContext)();
            const osc  = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.frequency.value = type === 'success' ? 880 : type === 'warning' ? 440 : 220;
            gain.gain.setValueAtTime(0.3, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.3);
            osc.start();
            osc.stop(ctx.currentTime + 0.3);
        } catch(e) {}
    }
</script>
@endpush
@endsection