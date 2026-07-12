@extends('v_vendor.v_layouts.app')

@section('title', 'Scanner Tiket')

@section('content')
<div class="container-fluid px-4 py-3">
    {{-- HEADER --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: #2d2d2d;">
                <i class="bi bi-qr-code-scan me-2 text-primary"></i>Scanner Tiket
            </h4>
            <p class="text-muted mb-0 small">Lakukan check-in tiket penonton secara real-time</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7" data-aos="fade-right" data-aos-delay="100">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h6 class="fw-bold mb-0">Area Scanner</h6>
                </div>
                <div class="card-body p-4">
                    <div class="scanner-wrapper rounded-4 overflow-hidden bg-dark position-relative" style="min-height: 400px; border: 1px solid rgba(0,0,0,0.1);">
                        <div id="reader"></div>
                    </div>
                    
                    <div id="scanner-message" class="alert alert-info mt-4 border-0 rounded-3 small">
                        <i class="bi bi-info-circle me-2"></i>Menunggu inisialisasi kamera...
                    </div>

                    <div class="mt-4 pt-3 border-top">
                        <label class="form-label small fw-bold text-muted text-uppercase">Atau Input Manual</label>
                        <div class="input-group">
                            <input type="text" id="manual-code" class="form-control form-control-lg border-0 bg-light rounded-start-3" placeholder="Contoh: TC-XXXXXXXXXX" style="font-size: 0.9rem;">
                            <button onclick="processManual()" class="btn btn-primary px-4 rounded-end-3">
                                <i class="bi bi-send-fill"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5" data-aos="fade-left" data-aos-delay="200">
            <div id="result-container">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 h-100">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h6 class="fw-bold mb-0">Hasil Scan</h6>
                    </div>
                    <div class="card-body p-4 text-center">
                        <!-- Placeholder State -->
                        <div id="result-placeholder" class="py-5">
                            <div class="bg-light rounded-circle d-inline-flex p-4 mb-3">
                                <i class="bi bi-qr-code fs-1 text-muted"></i>
                            </div>
                            <p class="text-muted">Arahkan kamera ke QR Code tiket untuk melihat detailnya di sini.</p>
                        </div>

                        <!-- Result Card (Initially Hidden) -->
                        <div id="result-card" class="d-none">
                            <div id="result-header" class="alert mb-4 border-0 rounded-4 py-3 fw-bold">
                                <!-- Status will be here -->
                            </div>
                            
                            <div class="text-center mb-4">
                                <div class="avatar-lg bg-purple-deep text-white rounded-circle d-inline-flex align-items-center justify-content-center fw-bold mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                                    <span id="res-initial">U</span>
                                </div>
                                <h4 id="res-user-name" class="fw-bold mb-1">Nama User</h4>
                                <span id="res-ticket-code" class="badge bg-light text-dark border px-3 py-2">TC-XXXXX</span>
                            </div>
                            
                            <div class="row g-3 text-start bg-light p-3 rounded-4">
                                <div class="col-6">
                                    <small class="text-muted d-block text-uppercase ls-1" style="font-size: 0.65rem;">Kategori</small>
                                    <strong id="res-category" class="small text-dark">-</strong>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block text-uppercase ls-1" style="font-size: 0.65rem;">No. Tiket</small>
                                    <strong id="res-seat" class="small text-dark">-</strong>
                                </div>
                                <div class="col-12 border-top pt-2">
                                    <small class="text-muted d-block text-uppercase ls-1" style="font-size: 0.65rem;">Nama Event</small>
                                    <strong id="res-event" class="small text-dark">-</strong>
                                </div>
                            </div>

                            <button onclick="resetScanner()" class="btn btn-primary w-100 mt-4 rounded-pill py-3 fw-bold shadow-sm">
                                <i class="bi bi-arrow-repeat me-2"></i>SCAN BERIKUTNYA
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4" data-aos="fade-up" data-aos-delay="300">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h6 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Scan Terbaru</h6>
                    <div class="d-flex align-items-center gap-3">
                        <form method="GET" action="{{ route('vendor.scanner') }}" class="d-flex align-items-center gap-2 m-0">
                            <label for="per_page" class="small text-muted mb-0 text-nowrap">Tampilkan:</label>
                            <select name="per_page" id="per_page" class="form-select form-select-sm rounded-pill" onchange="this.form.submit()" style="width: auto;">
                                <option value="5" {{ request('per_page', 5) == 5 ? 'selected' : '' }}>5</option>
                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            </select>
                        </form>
                        <span id="total-scans-badge" class="badge bg-purple-deep border-0 text-white rounded-pill px-3 py-2 shadow-sm" data-total="{{ $recentScans->total() }}">{{ $recentScans->total() }} Tiket</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3 text-muted small text-uppercase">Waktu Scan</th>
                                    <th class="py-3 text-muted small text-uppercase">Kode Tiket</th>
                                    <th class="py-3 text-muted small text-uppercase">Penonton</th>
                                    <th class="py-3 text-muted small text-uppercase">Event</th>
                                    <th class="py-3 text-muted small text-uppercase">Kategori</th>
                                    <th class="py-3 text-muted small text-uppercase">No. Tiket</th>
                                </tr>
                            </thead>
                            <tbody id="scan-history-body">
                                @forelse($recentScans as $scan)
                                    <tr>
                                        <td class="px-4 py-3">
                                            <div class="fw-bold text-dark">{{ $scan->scanned_at ? $scan->scanned_at->format('d M Y') : '-' }}</div>
                                            <div class="small text-muted">{{ $scan->scanned_at ? $scan->scanned_at->format('H:i:s') : '-' }}</div>
                                        </td>
                                        <td class="py-3 font-monospace fw-bold text-primary">{{ $scan->ticket_code }}</td>
                                        <td class="py-3">
                                            <div class="fw-bold">{{ $scan->booking->user->name ?? '-' }}</div>
                                            <div class="small text-muted">{{ $scan->booking->user->email ?? '-' }}</div>
                                        </td>
                                        <td class="py-3">
                                            <div class="text-truncate" style="max-width: 200px;">{{ $scan->booking->event->title ?? '-' }}</div>
                                        </td>
                                        <td class="py-3">
                                            <span class="badge bg-light text-dark border">{{ $scan->booking->ticket_category->name ?? '-' }}</span>
                                        </td>
                                        <td class="py-3 small text-muted fw-semibold">
                                            {{ $scan->seat_number ?? 'Free Seating' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr id="empty-row">
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            <i class="bi bi-inbox fs-2 mb-3 d-block opacity-50"></i>
                                            Belum ada data riwayat scan
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($recentScans->hasPages())
                <div class="card-footer bg-white border-0 py-3 px-4 d-flex justify-content-end">
                    {{ $recentScans->links('pagination::bootstrap-5') }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom style for html5-qrcode UI in admin context */
    #reader { border: none !important; width: 100% !important; background: transparent !important; }
    #reader video { border-radius: 12px; }
    #reader__dashboard_section_csr button, 
    #reader__dashboard_section_fsr button {
        background: #7c3aed !important;
        border: none !important;
        padding: 10px 20px !important;
        border-radius: 10px !important;
        color: white !important;
        font-weight: 600 !important;
        margin: 5px !important;
        transition: 0.2s;
    }
    #reader__dashboard_section_csr button:hover { opacity: 0.9; transform: translateY(-1px); }
    #reader__camera_selection {
        background: #f8fafc !important;
        color: #1e293b !important;
        border: 1px solid #e2e8f0 !important;
        padding: 8px !important;
        border-radius: 10px !important;
        width: 100%;
        margin-bottom: 10px;
    }
    .bg-purple-deep {
        background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
    }
    .ls-1 { letter-spacing: 0.5px; }
</style>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    let html5QrcodeScanner = new Html5QrcodeScanner(
        "reader", 
        { 
            fps: 20, // Higher FPS for smoother scanning
            qrbox: function(viewfinderWidth, viewfinderHeight) {
                let minEdgePercentage = 0.7; // 70% of the smallest edge
                let minEdgeSize = Math.min(viewfinderWidth, viewfinderHeight);
                let qrboxSize = Math.floor(minEdgeSize * minEdgePercentage);
                return {
                    width: qrboxSize,
                    height: qrboxSize
                };
            },
            rememberLastUsedCamera: true,
            aspectRatio: 1.0,
            supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA]
        }
    );

    function onScanSuccess(decodedText, decodedResult) {
        html5QrcodeScanner.clear();
        document.getElementById('scanner-message').className = 'alert alert-warning mt-4 border-0 rounded-3 small';
        document.getElementById('scanner-message').innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Sedang memproses tiket: <strong>' + decodedText + '</strong>';
        processTicket(decodedText);
    }

    function onScanFailure(error) { }

    html5QrcodeScanner.render(onScanSuccess, onScanFailure);

    function processManual() {
        const codeInput = document.getElementById('manual-code');
        const code = codeInput.value.trim();
        
        if (!code) {
            alert('Silakan masukkan kode tiket.');
            return;
        }

        html5QrcodeScanner.clear(); // Stop camera if running
        document.getElementById('scanner-message').className = 'alert alert-warning mt-4 border-0 rounded-3 small';
        document.getElementById('scanner-message').innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Sedang memproses kode manual: <strong>' + code + '</strong>';
        processTicket(code);
        codeInput.value = ''; // Clear input
    }

    async function processTicket(code) {
        try {
            const response = await fetch("{{ route('vendor.scan') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ ticket_code: code })
            });

            const data = await response.json();
            showResult(data, code);
        } catch (error) {
            console.error('Error:', error);
            showResult({ success: false, message: 'Gagal menghubungi server.' }, code);
        }
    }

    function showResult(data, code) {
        const placeholder = document.getElementById('result-placeholder');
        const resultCard = document.getElementById('result-card');
        const header = document.getElementById('result-header');
        const message = document.getElementById('scanner-message');

        placeholder.classList.add('d-none');
        resultCard.classList.remove('d-none');
        
        if (data.success) {
            header.className = 'alert alert-success mb-4 border-0 rounded-4 py-3 fw-bold';
            header.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i>VALID: CHECK-IN BERHASIL';
            
            document.getElementById('res-user-name').innerText = data.user.name;
            document.getElementById('res-initial').innerText = data.user.name.charAt(0).toUpperCase();
            document.getElementById('res-ticket-code').innerText = data.ticket.ticket_code;
            document.getElementById('res-category').innerText = data.category;
            document.getElementById('res-seat').innerText = data.seat || 'FREE SEATING';
            document.getElementById('res-event').innerText = data.ticket.booking.event.title;
            
            message.className = 'alert alert-success mt-4 border-0 rounded-3 small';
            message.innerHTML = '<i class="bi bi-check-circle me-2"></i>' + data.message;

            // Tambahkan baris baru ke tabel riwayat scan secara dinamis
            const tbody = document.getElementById('scan-history-body');
            const emptyRow = document.getElementById('empty-row');
            if (emptyRow) emptyRow.remove();

            const date = new Date();
            const timeStr = date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            // Format tanggal seperti '17 May 2026'
            const dateStr = date.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
            
            const newRow = document.createElement('tr');
            newRow.classList.add('bg-success', 'bg-opacity-10'); // Highlight sementara
            newRow.innerHTML = `
                <td class="px-4 py-3">
                    <div class="fw-bold text-dark">${dateStr}</div>
                    <div class="small text-muted">${timeStr}</div>
                </td>
                <td class="py-3 font-monospace fw-bold text-primary">${data.ticket.ticket_code}</td>
                <td class="py-3">
                    <div class="fw-bold">${data.user.name}</div>
                    <div class="small text-muted">${data.user.email}</div>
                </td>
                <td class="py-3">
                    <div class="text-truncate" style="max-width: 200px;">${data.ticket.booking.event.title}</div>
                </td>
                <td class="py-3">
                    <span class="badge bg-light text-dark border">${data.category}</span>
                </td>
                <td class="py-3 small text-muted fw-semibold">
                    ${data.seat || 'Free Seating'}
                </td>
            `;
            tbody.insertBefore(newRow, tbody.firstChild);

            // Update badge total
            const badge = document.getElementById('total-scans-badge');
            if (badge) {
                let currentTotal = parseInt(badge.getAttribute('data-total')) || 0;
                currentTotal++;
                badge.setAttribute('data-total', currentTotal);
                badge.innerText = currentTotal + ' Tiket';
            }

            // Hilangkan highlight setelah 3 detik
            setTimeout(() => {
                newRow.classList.remove('bg-success', 'bg-opacity-10');
            }, 3000);

        } else {
            header.className = 'alert alert-danger mb-4 border-0 rounded-4 py-3 fw-bold';
            header.innerHTML = '<i class="bi bi-x-circle-fill me-2"></i>INVALID: GAGAL';
            
            document.getElementById('res-user-name').innerText = data.ticket ? (data.ticket.booking.user.name) : 'Tidak Diketahui';
            document.getElementById('res-initial').innerText = data.ticket ? data.ticket.booking.user.name.charAt(0).toUpperCase() : '?';
            document.getElementById('res-ticket-code').innerText = code || 'Kode Tidak Valid';
            document.getElementById('res-category').innerText = '-';
            document.getElementById('res-seat').innerText = '-';
            document.getElementById('res-event').innerText = '-';
            
            message.className = 'alert alert-danger mt-4 border-0 rounded-3 small';
            message.innerHTML = '<i class="bi bi-exclamation-triangle me-2"></i>' + data.message;
        }
    }

    function resetScanner() {
        document.getElementById('result-placeholder').classList.remove('d-none');
        document.getElementById('result-card').classList.add('d-none');
        document.getElementById('scanner-message').className = 'alert alert-info mt-4 border-0 rounded-3 small';
        document.getElementById('scanner-message').innerHTML = '<i class="bi bi-info-circle me-2"></i>Menunggu inisialisasi kamera...';
        
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    }
</script>
@endpush
@endsection
