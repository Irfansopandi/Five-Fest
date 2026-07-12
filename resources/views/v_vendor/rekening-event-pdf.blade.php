<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Keuangan - {{ $event->title }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10.5px;
            color: #1e293b;
            background: #fff;
            line-height: 1.4;
        }

        /* ===== HEADER ===== */
        .header {
            background: #1e1b4b;
            color: white;
            padding: 20px 28px 18px;
        }
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 14px;
        }
        .brand {
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }
        .brand span { color: #a5b4fc; }
        .doc-title {
            font-size: 11px;
            color: rgba(255,255,255,0.65);
            margin-top: 2px;
        }
        .print-date {
            text-align: right;
            font-size: 9px;
            color: rgba(255,255,255,0.6);
            line-height: 1.6;
        }
        .event-banner {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 6px;
            padding: 10px 14px;
        }
        .event-title {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 4px;
            letter-spacing: 0.2px;
        }
        .event-meta {
            font-size: 9.5px;
            color: rgba(255,255,255,0.7);
            display: flex;
            gap: 18px;
        }

        /* ===== SUMMARY CARDS ===== */
        .summary-section {
            padding: 14px 28px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }
        .summary-grid {
            display: table;
            border-spacing: 10px 0;
            table-layout: fixed;
            width: 100%;

        }
        .summary-card {
            display: table-cell;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 14px 16px;
            border-top: 3px solid #4338ca;
            vertical-align: top;
        }
        .summary-card.green  { border-top-color: #10b981; }
        .summary-card.orange { border-top-color: #f59e0b; }
        .summary-card.blue   { border-top-color: #3b82f6; }
        .summary-card.purple { border-top-color: #7c3aed; }
        .summary-label {
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            margin-bottom: 5px;
        }
        .summary-value {
            font-size: 13px;
            font-weight: 800;
            color: #1e293b;
            line-height: 1.1;
        }
        .summary-sub {
            font-size: 8.5px;
            color: #94a3b8;
            margin-top: 3px;
        }

        /* ===== SECTION ===== */
        .section {
            padding: 16px 28px;
        }
        .section + .section {
            padding-top: 4px;
        }
        .section-title {
            font-size: 11px;
            font-weight: 700;
            color: #1e1b4b;
            margin-bottom: 8px;
            padding-bottom: 5px;
            border-bottom: 2px solid #e0e7ff;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .section-title::before {
            content: '';
            display: inline-block;
            width: 3px;
            height: 12px;
            background: #4338ca;
            border-radius: 2px;
            flex-shrink: 0;
        }

        /* ===== TABLES ===== */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
        }

        thead {
            display: table-header-group;
        }
        thead tr {
            background: #1e1b4b;
        }
        thead th {
            color: white;
            font-size: 8.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            padding: 8px 9px;
            text-align: left;
        }
        thead th.text-right  { text-align: right; }
        thead th.text-center { text-align: center; }

        tbody tr {
            page-break-inside: avoid;
        }
        tbody tr:nth-child(even) { background: #f8fafc; }
        tbody tr:nth-child(odd)  { background: #ffffff; }
        tbody td {
            padding: 7px 9px;
            font-size: 9.5px;
            color: #334155;
            border-bottom: 1px solid #f1f5f9;
        }
        tbody td.text-right  { text-align: right; }
        tbody td.text-center { text-align: center; }
        tbody td.fw-bold     { font-weight: 700; color: #1e293b; }
        tbody td.indigo      { color: #4338ca; font-weight: 700; }

        tfoot {
            display: table-footer-group;
        }
        tfoot tr { background: #e0e7ff; }
        tfoot td {
            padding: 8px 9px;
            font-size: 9.5px;
            font-weight: 700;
            color: #1e1b4b;
            border-top: 2px solid #4338ca;
        }
        tfoot td.text-right  { text-align: right; }
        tfoot td.text-center { text-align: center; }

        /* Badge */
        .badge {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 20px;
            font-size: 8.5px;
            font-weight: 700;
        }
        .badge-blue   { background: #e0e7ff; color: #4338ca; }
        .badge-green  { background: #d1fae5; color: #059669; }
        .badge-yellow { background: #fef9c3; color: #b45309; }
        .badge-red    { background: #fee2e2; color: #dc2626; }
        .badge-gray   { background: #f1f5f9; color: #64748b; }

        /* ===== FEE BREAKDOWN BOX ===== */
        .fee-box {
            background: #fafafa;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 12px;
        }
        .fee-row {
            display: flex;
            justify-content: space-between;
            padding: 3px 0;
            font-size: 9.5px;
            color: #475569;
        }
        .fee-row.total {
            border-top: 1.5px solid #c7d2fe;
            margin-top: 8px;
            padding-top: 8px;
            font-weight: 700;
            font-size: 11px;
            color: #1e1b4b;
        }
        .fee-row.indent {
            padding-left: 16px;
            color: #64748b;
            font-size: 9px;
        }
        .fee-row.sub-total {
            padding-top: 4px;
        }
        .fee-divider {
            border: none;
            border-top: 1px dashed #e2e8f0;
            margin: 6px 0;
        }

        /* Buyer table header slightly different shade */
        .buyer-table thead tr { background: #312e81; }

        /* ===== FOOTER ===== */
        .footer {
            margin: 16px 28px 0;
            padding: 10px 0 0;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .footer-brand {
            font-size: 10px;
            font-weight: 700;
            color: #4338ca;
        }
        .footer-note {
            font-size: 8.5px;
            color: #94a3b8;
            margin-top: 2px;
        }
        .watermark-note {
            font-size: 8.5px;
            color: #cbd5e1;
            text-align: center;
            margin-top: 4px;
            padding-bottom: 10px;
        }

        /* ===== PRINT STYLES ===== */
        @media print {
            @page {
                size: A4;
                margin: 0;
            }

            html, body {
                width: 210mm;
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }

            /* Avoid page breaks inside rows/cards */
            tr                 { page-break-inside: avoid; }
            .summary-card      { page-break-inside: avoid; }
            .fee-box           { page-break-inside: avoid; }
            .section-title     { page-break-after: avoid; }

            /* Keep header on every page top — just first occurrence */
            .header            { page-break-after: avoid; }

            /* No shadows in print */
            * { box-shadow: none !important; }
        }
    </style>
</head>
<body>

<!-- ===== HEADER ===== -->
<div class="header">
    <div class="header-top">
        <div>
            <div class="brand">Five<span>Fest</span></div>
            <div class="doc-title">Laporan Keuangan Event</div>
        </div>
        <div class="print-date">
            Dicetak: {{ now()->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }} WIB<br>
            Vendor: {{ auth()->user()->name }}
        </div>
    </div>
    <div class="event-banner">
        <div class="event-title">{{ $event->title }}</div>
        <div class="event-meta">
            <span>&#128197; {{ $event->date->isoFormat('D MMMM YYYY') }}</span>
            <span>&#128205; {{ $event->venue }}</span>
            @if($event->category)<span>&#127913; {{ $event->category }}</span>@endif
        </div>
    </div>
</div>

<!-- ===== RINGKASAN FINANSIAL ===== -->
<div class="summary-section">
    <div class="summary-grid" cellspacing="8" cellpadding="0">
        <div class="summary-card green">
            <div class="summary-label">Tiket Terjual</div>
            <div class="summary-value">{{ number_format($ticketsSold, 0, ',', '.') }}</div>
            <div class="summary-sub">dari {{ number_format($totalCapacity, 0, ',', '.') }} slot</div>
        </div>
        <div class="summary-card orange">
            <div class="summary-label">Fill Rate</div>
            <div class="summary-value">{{ $fillRate }}%</div>
            <div class="summary-sub">kapasitas terisi</div>
        </div>
        <div class="summary-card blue">
            <div class="summary-label">Bersih Tiket</div>
            <div class="summary-value">Rp {{ number_format($netTicketRevenue, 0, ',', '.') }}</div>
            <div class="summary-sub">setelah biaya platform</div>
        </div>
        @if($event->is_tenant_open)
        <div class="summary-card">
            <div class="summary-label">Bersih Booth</div>
            <div class="summary-value">Rp {{ number_format($netBoothRevenue, 0, ',', '.') }}</div>
            <div class="summary-sub">{{ $event->tenants->count() }} booth terbayar</div>
        </div>
        @endif
        <div class="summary-card purple">
            <div class="summary-label">Total Bersih</div>
            <div class="summary-value" style="color:#4338ca;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            <div class="summary-sub">keseluruhan pendapatan</div>
        </div>
    </div>
</div>

<!-- ===== RINCIAN PER KATEGORI TIKET ===== -->
<div class="section">
    <div class="section-title">Rincian Penjualan per Kategori Tiket</div>
    <table>
        <thead>
            <tr>
                <th style="width:30%">Kategori Tiket</th>
                <th class="text-right">Harga Satuan</th>
                <th class="text-center">Terjual</th>
                <th class="text-center">Slot Total</th>
                <th class="text-right">% Terisi</th>
                <th class="text-right">Pendapatan Kotor</th>
                <th class="text-right">Pendapatan Bersih</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTicketNet = 0; $grandTicketGross = 0; @endphp
            @forelse($ticketBreakdown as $row)
            @php
                $fillPct = $row['slot'] > 0 ? round(($row['sold'] / $row['slot']) * 100, 1) : 0;
                $gross = $row['sold'] * $row['price'];
                $grandTicketNet += $row['revenue'];
                $grandTicketGross += $gross;
            @endphp
            <tr>
                <td class="fw-bold">{{ $row['name'] }}</td>
                <td class="text-right">Rp {{ number_format($row['price'], 0, ',', '.') }}</td>
                <td class="text-center">
                    <span class="badge badge-blue">{{ number_format($row['sold'], 0, ',', '.') }}</span>
                </td>
                <td class="text-center">{{ number_format($row['slot'], 0, ',', '.') }}</td>
                <td class="text-right">
                    <span class="badge {{ $fillPct >= 80 ? 'badge-green' : ($fillPct >= 40 ? 'badge-yellow' : 'badge-gray') }}">
                        {{ $fillPct }}%
                    </span>
                </td>
                <td class="text-right">Rp {{ number_format($gross, 0, ',', '.') }}</td>
                <td class="text-right indigo">Rp {{ number_format($row['revenue'], 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center" style="color:#94a3b8; padding:16px;">
                    Tidak ada data kategori tiket.
                </td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2">TOTAL</td>
                <td class="text-center">{{ number_format($ticketsSold, 0, ',', '.') }}</td>
                <td class="text-center">{{ number_format($totalCapacity, 0, ',', '.') }}</td>
                <td class="text-right">{{ $fillRate }}%</td>
                <td class="text-right">Rp {{ number_format($grandTicketGross, 0, ',', '.') }}</td>
                <td class="text-right" style="color:#4338ca;">Rp {{ number_format($grandTicketNet, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</div>

<!-- ===== RINCIAN BIAYA PLATFORM ===== -->
<div class="section">
    <div class="section-title">Rincian Biaya Platform &amp; Kalkulasi Bersih</div>
    <div class="fee-box">
        <div class="fee-row">
            <span>Pendapatan Kotor Tiket</span>
            <span>Rp {{ number_format($grandTicketGross, 0, ',', '.') }}</span>
        </div>
        <div class="fee-row indent">
            <span>— Biaya Platform (13% dari kotor)</span>
            <span style="color:#ef4444;">- Rp {{ number_format($grandTicketGross - $netTicketRevenue, 0, ',', '.') }}</span>
        </div>
        <div class="fee-row sub-total">
            <span style="font-weight:600; color:#059669;">Bersih dari Tiket</span>
            <span style="font-weight:700; color:#059669;">Rp {{ number_format($netTicketRevenue, 0, ',', '.') }}</span>
        </div>

        @if($event->is_tenant_open && $netBoothRevenue > 0)
        <hr class="fee-divider">
        <div class="fee-row">
            <span>Pendapatan Booth Tenant ({{ $event->tenants->count() }} booth)</span>
            <span>Rp {{ number_format($netBoothRevenue + round($netBoothRevenue * 0.03), 0, ',', '.') }}</span>
        </div>
        <div class="fee-row indent">
            <span>— Biaya Layanan Tenant (3% dari booth)</span>
            <span style="color:#ef4444;">- Rp {{ number_format(round($netBoothRevenue * 0.03), 0, ',', '.') }}</span>
        </div>
        <div class="fee-row sub-total">
            <span style="font-weight:600; color:#059669;">Bersih dari Booth</span>
            <span style="font-weight:700; color:#059669;">Rp {{ number_format($netBoothRevenue, 0, ',', '.') }}</span>
        </div>
        @endif

        <div class="fee-row total">
            <span>TOTAL PENDAPATAN BERSIH EVENT</span>
            <span style="color:#4338ca;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
        </div>
    </div>
</div>

<!-- ===== DAFTAR PEMBELI ===== -->
<div class="section">
    <div class="section-title">Daftar Pembelian Tiket (Semua Status)</div>
    <table class="buyer-table">
        <thead>
            <tr>
                <th style="width:4%">#</th>
                <th style="width:26%">Pembeli</th>
                <th style="width:17%">Kode Booking</th>
                <th style="width:19%">Kategori Tiket</th>
                <th class="text-center" style="width:7%">Qty</th>
                <th class="text-right" style="width:14%">Total Bayar</th>
                <th class="text-right" style="width:13%">Status</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse($allBuyers as $booking)
            <tr>
                <td class="text-center" style="color:#94a3b8;">{{ $no++ }}</td>
                <td>
                    <div style="font-weight:600; font-size:9.5px; color:#1e293b;">
                        {{ $booking->user ? $booking->user->name : ($booking->guest_name ?? '-') }}
                    </div>
                    <div style="font-size:8.5px; color:#94a3b8;">
                        {{ $booking->user ? $booking->user->email : ($booking->guest_email ?? '') }}
                    </div>
                </td>
                <td style="font-family:monospace; font-size:8.5px; color:#475569; letter-spacing:0.3px;">
                    {{ $booking->booking_code ?? '-' }}
                </td>
                <td style="font-size:9px;">
                    {{ $booking->ticket_category ? $booking->ticket_category->name : '-' }}
                </td>
                <td class="text-center">
                    <span class="badge badge-blue">{{ $booking->quantity }}</span>
                </td>
                <td class="text-right fw-bold">
                    Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                </td>
                <td class="text-right">
                    @if($booking->payment_status === 'paid')
                        <span class="badge badge-green">Lunas</span>
                    @elseif($booking->payment_status === 'pending')
                        <span class="badge badge-yellow">Pending</span>
                    @elseif($booking->payment_status === 'cancelled' || $booking->payment_status === 'canceled')
                        <span class="badge badge-red">Cancelled</span>
                    @else
                        <span class="badge badge-gray">{{ ucfirst($booking->payment_status) }}</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center" style="color:#94a3b8; padding:18px;">
                    Belum ada data pembelian.
                </td>
            </tr>
            @endforelse
        </tbody>
        @if($allBuyers->count() > 0)
        <tfoot>
            <tr>
                <td colspan="4">TOTAL TRANSAKSI</td>
                <td class="text-center">{{ number_format($allBuyers->sum('quantity'), 0, ',', '.') }}</td>
                <td class="text-right" style="color:#4338ca;">Rp {{ number_format($allBuyers->sum('total_price'), 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tfoot>
        @endif
    </table>
    <div style="font-size:8.5px; color:#94a3b8; margin-top:5px;">
        * Menampilkan semua transaksi termasuk yang pending, gagal, atau dibatalkan.
    </div>
</div>

<!-- ===== FOOTER ===== -->
<div class="footer">
    <div>
        <div class="footer-brand">FiveFest Platform</div>
        <div class="footer-note">Laporan ini dibuat otomatis oleh sistem FiveFest</div>
    </div>
    <div style="text-align:right;">
        <div class="footer-note">ID Event: #{{ $event->id }}</div>
        <div class="footer-note">{{ now()->format('d/m/Y H:i') }} WIB</div>
    </div>
</div>
<div class="watermark-note">
    Dokumen ini bersifat rahasia dan hanya untuk keperluan internal vendor.
</div>

</body>
</html>