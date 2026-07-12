<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Transaksi Tiket</title>
    <style>
        /* ── PENGATURAN HALAMAN A4 LANDSCAPE DOMPDF AMAN ── */
        @page {
            size: a4 landscape;
            margin: 60px 70px 100px 70px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            color: #2d3748;
            background: #ffffff;
            line-height: 1.45;
        }

        /* Pembungkus tambahan: jaga jarak aman dari tepi kertas,
           sebagai cadangan jika margin @page tidak terbaca penuh
           oleh renderer (DomPDF/browser preview). */
        .page-wrapper {
            padding: 10px 12px 0 12px;
        }

        /* ── HEADER UTAMA: LOGO/JUDUL KIRI, META KANAN DALAM SATU BARIS ── */
        .report-header {
            width: 100%;
            border-bottom: 2px solid #1a202c;
            padding-bottom: 14px;
            margin-bottom: 22px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-table td {
            border: none;
            padding: 0;
            vertical-align: bottom;
        }
        .brand-tag {
            display: inline-block;
            background-color: #2b6cb0;
            color: #ffffff;
            font-size: 9px;
            font-weight: bold;
            letter-spacing: 1px;
            padding: 3px 8px;
            margin-bottom: 8px;
        }
        .title-main {
            font-size: 19px;
            font-weight: bold;
            color: #1a202c;
            letter-spacing: 0.2px;
            margin-bottom: 3px;
        }
        .title-sub {
            font-size: 10px;
            color: #64748b;
        }
        .status-badge {
            display: inline-block;
            background-color: #ecfdf5;
            color: #15803d;
            border: 1px solid #86efac;
            font-size: 8px;
            font-weight: bold;
            letter-spacing: 0.4px;
            padding: 2px 7px;
            margin-left: 6px;
        }

        .meta-right-box {
            text-align: right;
            font-size: 9px;
            color: #4a5568;
            line-height: 1.7;
        }
        .meta-right-box .meta-label {
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            font-size: 8px;
        }
        .meta-right-box .meta-value {
            color: #1a202c;
            font-weight: bold;
            font-size: 10px;
        }

        /* ── LAYOUT TABEL TRANSAKSI UTAMA ── */
        .table-section {
            width: 100%;
            margin-bottom: 60px;
        }
        .section-title-row {
            width: 100%;
            margin-bottom: 10px;
        }
        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: #1a202c;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            border-left: 3px solid #1a202c;
            padding-left: 8px;
        }

        table.main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
        table.main-table th {
            background-color: #2b6cb0;
            color: #ffffff;
            padding: 9px 8px;
            font-size: 8.5px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            text-align: left;
        }
        table.main-table th:first-child { border-top-left-radius: 2px; }
        table.main-table td {
            padding: 9px 8px;
            border-bottom: 1px solid #e2e8f0;
            color: #334155;
            vertical-align: top;
            font-size: 9.5px;
        }
        table.main-table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }
        table.main-table tbody tr:last-child td {
            border-bottom: 2px solid #2b6cb0;
        }

        /* Helpers alignment & utilities */
        .text-center { text-align: center !important; }
        .text-right { text-align: right !important; }
        .fw-bold { font-weight: bold; }
        .text-muted { color: #94a3b8; font-size: 8.5px; }
        .text-primary { color: #2b6cb0; }
        .code-pill {
            display: inline-block;
            background-color: #eff6ff;
            color: #2b6cb0;
            border: 1px solid #bfdbfe;
            font-weight: bold;
            font-size: 9px;
            padding: 2px 6px;
        }
        .merch-tag {
            display: block;
            font-size: 9px;
            color: #334155;
            margin-bottom: 2px;
        }
        .merch-empty {
            color: #cbd5e1;
            font-size: 9px;
        }

        /* ── AREA REKAP RINGKASAN BAWAH ── */
        .rekap-container-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }
        .rekap-container-table td {
            border: none;
            padding: 0;
            vertical-align: top;
        }
        .rekap-note {
            font-size: 8.5px;
            color: #94a3b8;
            line-height: 1.6;
            padding-right: 30px;
        }

        table.rekap-table {
            width: 100%;
            border-collapse: collapse;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
        }
        table.rekap-table td {
            padding: 7px 14px;
            font-size: 9.5px;
            color: #4a5568;
            border: none;
        }
        table.rekap-table tr.border-top-line td {
            border-top: 1px dashed #cbd5e1;
            padding-top: 7px;
        }
        table.rekap-table tr.grand-total td {
            background-color: #2b6cb0;
            color: #ffffff;
            font-size: 12px;
            font-weight: bold;
            padding-top: 10px;
            padding-bottom: 10px;
        }
        table.rekap-table tr.grand-total td.text-right {
            color: #ffffff;
        }

        /* ── FOOTER HALAMAN (Terkunci di Ujung Bawah Kertas) ── */
        .report-footer {
            position: fixed;
            bottom: 0px;
            left: 20px;
            right: 20px;
            height: 35px;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
        }
        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }
        .footer-table td {
            border: none;
            font-size: 8px;
            color: #a0aec0;
            vertical-align: middle;
        }
    </style>
</head>
<body>

<div class="page-wrapper">

{{-- HEADER: JUDUL KIRI + META KANAN DALAM SATU BARIS SEJAJAR --}}
<div class="report-header">
    <table class="header-table">
        <tr>
            <td style="width: 55%;">
                <div class="brand-tag">FIVEFEST PLATFORM</div>
                <div class="title-main">
                    Daftar Transaksi Tiket &amp; Merchandise
                    <span class="status-badge">PAID</span>
                </div>
                <div class="title-sub">Laporan riwayat transaksi penjualan vendor</div>
            </td>
            <td style="width: 45%;">
                <div class="meta-right-box">
                    <div class="meta-label">Vendor</div>
                    <div class="meta-value">{{ auth()->user()->name }}</div>
                    <div class="meta-label" style="margin-top: 4px;">Tipe Dokumen</div>
                    <div>Laporan Riwayat Transaksi Tabel</div>
                    <div class="meta-label" style="margin-top: 4px;">Waktu Cetak</div>
                    <div>{{ now()->format('d M Y, H:i') }} WIB</div>
                </div>
            </td>
        </tr>
    </table>
</div>

{{-- KONTEN TABEL DATA TRANSAKSI MASUK --}}
<div class="table-section">
    <div class="section-title-row">
        <div class="section-title">Data Log Transaksi Masuk</div>
    </div>
    <table class="main-table">
        <thead>
            <tr>
                <th style="width: 4%;" class="text-center">No</th>
                <th style="width: 10%;">Kode Booking</th>
                <th style="width: 24%;">Nama Event</th>
                <th style="width: 19%;">Nama Pembeli</th>
                <th style="width: 7%;" class="text-center">Jumlah</th>
                <th style="width: 15%;">Merchandise</th>
                <th style="width: 11%;" class="text-right">Total Bayar</th>
                <th style="width: 10%;" class="text-center">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($bookings) && $bookings->count() > 0)
                @foreach($bookings as $index => $booking)
                <tr>
                    <td class="text-center text-muted fw-bold">{{ $index + 1 }}</td>
                    <td><span class="code-pill">#{{ $booking->code ?? $booking->id }}</span></td>

                    {{-- Proteksi Deteksi Kolom Nama Event --}}
                    <td class="fw-bold">
                        {{ $booking->event->name ?? ($booking->event->title ?? ($booking->event->nama_event ?? 'Nama Event Tidak Tersedia')) }}
                    </td>

                    <td>
                        <div class="fw-bold">{{ $booking->user->name ?? 'Pembeli Umum' }}</div>
                        <div class="text-muted">{{ $booking->user->email ?? '-' }}</div>
                    </td>
                    <td class="text-center fw-bold">{{ $booking->quantity ?? 0 }} Tiket</td>
                    <td>
                        @if($booking->merchandises && $booking->merchandises->count() > 0)
                            @foreach($booking->merchandises as $merch)
                                <span class="merch-tag">{{ $merch->name }} <span class="text-muted">×{{ $merch->pivot->quantity ?? 1 }}</span></span>
                            @endforeach
                        @else
                            <span class="merch-empty">—</span>
                        @endif
                    </td>
                    <td class="text-right fw-bold">Rp {{ number_format($booking->total_price ?? 0, 0, ',', '.') }}</td>
                    <td class="text-center">
                        <div>{{ $booking->created_at ? $booking->created_at->format('d M Y') : '-' }}</div>
                        <div class="text-muted">{{ $booking->created_at ? $booking->created_at->format('H:i') : '-' }} WIB</div>
                    </td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="8" class="text-center text-muted" style="padding: 40px; font-size: 11px;">
                        Tidak ada catatan log transaksi tiket untuk periode ini.
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    {{-- REKAP TOTAL DI BAWAH HALAMAN --}}
    @php
        $pajak = $totalPendapatan * 0.10;
        $jasa = $totalPendapatan * 0.03;
        $bersih = $totalPendapatan - $pajak - $jasa;
    @endphp
    <table class="rekap-container-table">
        <tr>
            <td style="width: 50%;">
                <div class="rekap-note">
                    Rekap dihitung otomatis dari seluruh transaksi berstatus <strong>Paid</strong> pada periode laporan ini.
                    Potongan pajak dan jasa layanan platform telah disertakan dalam perhitungan total bersih.
                </div>
            </td>
            <td style="width: 50%;">
                <table class="rekap-table">
                    <tr>
                        <td>Total Kuantitas Tiket Terjual</td>
                        <td class="text-right fw-bold" style="font-size: 11px;">{{ $totalTiket }} Unit Tiket</td>
                    </tr>
                    <tr>
                        <td>Total Pendapatan Kotor (Bruto)</td>
                        <td class="text-right fw-bold">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Total Potongan Pajak (10%)</td>
                        <td class="text-right text-muted">Rp {{ number_format($pajak, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="border-top-line">
                        <td class="text-muted">Total Jasa Layanan (3%)</td>
                        <td class="text-right text-muted">Rp {{ number_format($jasa, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="grand-total">
                        <td>TOTAL BERSIH PENDAPATAN VENDOR</td>
                        <td class="text-right">Rp {{ number_format($bersih, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>

</div>

{{-- FOOTER DI UJUNG PALING BAWAH KERTAS --}}
<div class="report-footer">
    <table class="footer-table">
        <tr>
            <td style="width: 70%;">
                &copy; {{ date('Y') }} <span class="fw-bold">FiveFest Platform</span> — Laporan dicetak otomatis oleh sistem ekspor data vendor terverifikasi.
            </td>
            <td style="width: 30%;" class="text-right">
                Internal Financial Report &nbsp;|&nbsp; Validated
            </td>
        </tr>
    </table>
</div>

</body>
</html>