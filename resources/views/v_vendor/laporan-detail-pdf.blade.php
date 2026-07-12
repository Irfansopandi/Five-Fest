<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Detail - {{ $event->title }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #1e1b4b;
            background: #fff;
            padding: 32px 36px;
        }

        /* ── HEADER ── */
        .header {
            display: table;
            width: 100%;
            margin-bottom: 24px;
            border-bottom: 3px solid #7c3aed;
            padding-bottom: 16px;
        }
        .header-left  { display: table-cell; vertical-align: middle; }
        .header-right { display: table-cell; vertical-align: middle; text-align: right; }

        .brand {
            font-size: 22px;
            font-weight: 700;
            color: #7c3aed;
            letter-spacing: 1px;
        }
        .doc-title {
            font-size: 11px;
            color: #6b7280;
            margin-top: 2px;
        }
        .print-info {
            font-size: 10px;
            color: #6b7280;
            line-height: 1.6;
        }
        .print-info strong { color: #1e1b4b; }

        /* ── EVENT TITLE BLOCK ── */
        .event-block {
            background: #f3f0ff;
            border-left: 5px solid #7c3aed;
            border-radius: 6px;
            padding: 12px 16px;
            margin-bottom: 22px;
        }
        .event-name {
            font-size: 15px;
            font-weight: 700;
            color: #1e1b4b;
            margin-bottom: 5px;
        }
        .event-meta {
            font-size: 10px;
            color: #6b7280;
        }
        .event-meta span { margin-right: 16px; }

        /* ── SECTION TITLE ── */
        .section-title {
            font-size: 12px;
            font-weight: 700;
            color: #1e1b4b;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #ede9fe;
        }
        .section-title .icon { color: #7c3aed; }

        /* ── TABLES ── */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        thead th {
            background: #7c3aed;
            color: #fff;
            font-size: 10px;
            font-weight: 600;
            padding: 8px 10px;
            text-align: left;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }
        thead th.text-right { text-align: right; }
        thead th.text-center { text-align: center; }

        tbody tr:nth-child(even) { background: #faf9ff; }
        tbody tr:nth-child(odd)  { background: #ffffff; }

        tbody td {
            padding: 8px 10px;
            font-size: 10.5px;
            color: #374151;
            border-bottom: 1px solid #f0ebff;
            vertical-align: top;
        }
        tbody td.text-right  { text-align: right; }
        tbody td.text-center { text-align: center; }
        tbody td.text-green  { color: #059669; font-weight: 600; }
        tbody td.text-muted  { color: #9ca3af; }
        tbody td.fw-bold     { font-weight: 700; }

        tfoot td {
            background: #ede9fe;
            padding: 8px 10px;
            font-size: 10.5px;
            font-weight: 700;
            color: #1e1b4b;
            border-top: 2px solid #7c3aed;
        }
        tfoot td.text-right  { text-align: right; }
        tfoot td.text-center { text-align: center; }

        /* ── TWO COLUMNS ── */
        .two-col { display: table; width: 100%; margin-bottom: 24px; }
        .col-left  { display: table-cell; width: 49%; vertical-align: top; padding-right: 10px; }
        .col-right { display: table-cell; width: 49%; vertical-align: top; padding-left: 10px; }

        /* ── EMPTY STATE ── */
        .empty-state {
            text-align: center;
            padding: 20px;
            color: #9ca3af;
            font-size: 10px;
            border: 1px dashed #d1d5db;
            border-radius: 6px;
            margin-bottom: 24px;
        }

        /* ── STATUS BADGE ── */
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 9.5px;
            font-weight: 600;
        }
        .badge-success  { background: #d1fae5; color: #065f46; }
        .badge-warning  { background: #fef3c7; color: #92400e; }
        .badge-danger   { background: #fee2e2; color: #991b1b; }
        .badge-default  { background: #e5e7eb; color: #374151; }

        /* ── FOOTER ── */
        .footer {
            margin-top: 28px;
            padding-top: 10px;
            border-top: 1px solid #ede9fe;
            text-align: center;
            font-size: 9px;
            color: #9ca3af;
        }
        .footer strong { color: #7c3aed; }

        /* ── PAGE BREAK ── */
        .page-break { page-break-before: always; }
    </style>
</head>
<body>

{{-- ── HEADER ── --}}
<div class="header">
    <div class="header-left">
        <div class="brand">FiveFest</div>
        <div class="doc-title">Laporan Detail Penjualan Event</div>
    </div>
    <div class="header-right">
        <div class="print-info">
            Dicetak: {{ now()->locale('id')->translatedFormat('d F Y, H:i') }} WIB<br>
            Vendor: <strong>{{ auth()->user()->name }}</strong>
        </div>
    </div>
</div>

{{-- ── EVENT INFO ── --}}
<div class="event-block">
    <div class="event-name">{{ $event->title }}</div>
    <div class="event-meta">
        <span>&#128197; {{ $event->date->format('d M Y') }}</span>
        <span>&#128205; {{ $event->venue }}</span>
        @if($event->category)
            <span>&#127991; {{ $event->category }}</span>
        @endif
    </div>
</div>

{{-- ── TWO COLUMNS: Tiket + Merchandise ── --}}
<div class="two-col">

    {{-- Breakdown Tiket --}}
    <div class="col-left">
        <div class="section-title"><span class="icon">&#127991;</span> Breakdown per Jenis Tiket</div>
        <table>
            <thead>
                <tr>
                    <th>Jenis Tiket</th>
                    <th class="text-center">Slot</th>
                    <th class="text-center">Terjual</th>
                    <th class="text-right">Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ticketBreakdown as $cat)
                <tr>
                    <td>
                        <strong>{{ $cat['name'] }}</strong><br>
                        <span style="color:#9ca3af;font-size:9.5px;">Rp {{ number_format($cat['price'], 0, ',', '.') }}/tiket</span>
                    </td>
                    <td class="text-center">{{ number_format($cat['slot'], 0, ',', '.') }}</td>
                    <td class="text-center {{ $cat['sold'] > 0 ? 'text-green' : 'text-muted' }}">
                        {{ number_format($cat['sold'], 0, ',', '.') }}
                    </td>
                    <td class="text-right {{ $cat['revenue'] > 0 ? 'text-green' : 'text-muted' }}">
                        Rp {{ number_format($cat['revenue'], 0, ',', '.') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td><strong>Total</strong></td>
                    <td class="text-center">{{ number_format(array_sum(array_column($ticketBreakdown, 'slot')), 0, ',', '.') }}</td>
                    <td class="text-center">{{ number_format(array_sum(array_column($ticketBreakdown, 'sold')), 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format(array_sum(array_column($ticketBreakdown, 'revenue')), 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- Breakdown Merchandise --}}
    <div class="col-right">
        <div class="section-title"><span class="icon">&#128717;</span> Breakdown Penjualan Merchandise</div>
        @if(count($merchandiseBreakdown) > 0)
        <table>
            <thead>
                <tr>
                    <th>Nama Merchandise</th>
                    <th class="text-center">Qty</th>
                    <th class="text-right">Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($merchandiseBreakdown as $merch)
                <tr>
                    <td><strong>{{ $merch['name'] }}</strong></td>
                    <td class="text-center {{ $merch['qty'] > 0 ? 'text-green' : 'text-muted' }}">
                        {{ number_format($merch['qty'], 0, ',', '.') }}
                    </td>
                    <td class="text-right {{ $merch['revenue'] > 0 ? 'text-green' : 'text-muted' }}">
                        Rp {{ number_format($merch['revenue'], 0, ',', '.') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td><strong>Total</strong></td>
                    <td class="text-center">{{ number_format(array_sum(array_column($merchandiseBreakdown, 'qty')), 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format(array_sum(array_column($merchandiseBreakdown, 'revenue')), 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
        @else
        <div class="empty-state">Belum ada penjualan merchandise untuk event ini.</div>
        @endif
    </div>

</div>

{{-- ── DAFTAR PEMBELI ── --}}
<div class="section-title"><span class="icon">&#128101;</span> Daftar Pembeli</div>
<table>
    <thead>
        <tr>
            <th style="width:4%">#</th>
            <th style="width:22%">Nama Pembeli</th>
            <th style="width:18%">Jenis Tiket</th>
            <th style="width:22%">Merchandise</th>
            <th style="width:13%">Tgl Beli</th>
            <th style="width:10%" class="text-center">Status</th>
            <th style="width:11%" class="text-right">Nominal</th>
        </tr>
    </thead>
    <tbody>
        @forelse($allBuyers as $i => $buyer)
        <tr>
            <td class="text-muted">{{ $i + 1 }}</td>
            <td>
                <strong>{{ $buyer->user->name ?? $buyer->guest_name ?? '-' }}</strong><br>
                <span style="color:#9ca3af;font-size:9px;">{{ $buyer->user->email ?? $buyer->guest_email ?? '' }}</span>
            </td>
            <td>{{ $buyer->ticket_category->name ?? '-' }}</td>
            <td>
                @if($buyer->merchandises && $buyer->merchandises->count() > 0)
                    @foreach($buyer->merchandises as $merch)
                        <span>{{ $merch->name }} &times;{{ $merch->pivot->quantity }}</span><br>
                    @endforeach
                @else
                    <span style="color:#9ca3af;">—</span>
                @endif
            </td>
            <td>{{ $buyer->created_at->format('d M Y') }}</td>
            <td class="text-center">
                @if($buyer->payment_status === 'paid')
                    <span class="badge badge-success">Lunas</span>
                @elseif($buyer->payment_status === 'pending')
                    <span class="badge badge-warning">Pending</span>
                @elseif($buyer->payment_status === 'cancelled')
                    <span class="badge badge-danger">Cancelled</span>
                @else
                    <span class="badge badge-default">{{ ucfirst($buyer->payment_status) }}</span>
                @endif
            </td>
            <td class="text-right {{ $buyer->payment_status === 'paid' ? 'text-green' : 'text-muted' }}">
                Rp {{ number_format($buyer->total_price / 1.13, 0, ',', '.') }}
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center text-muted" style="padding:20px;">
                Belum ada data pembeli.
            </td>
        </tr>
        @endforelse
    </tbody>
    @if($allBuyers->count() > 0)
    <tfoot>
        <tr>
            <td colspan="6"><strong>Total Pendapatan Bersih (paid)</strong></td>
            <td class="text-right">
                Rp {{ number_format($allBuyers->where('payment_status','paid')->sum(fn($b) => $b->total_price / 1.13), 0, ',', '.') }}
            </td>
        </tr>
    </tfoot>
    @endif
</table>

{{-- ── FOOTER ── --}}
<div class="footer">
    <strong>FiveFest Platform</strong> &mdash;
    Laporan ini dibuat otomatis oleh sistem FiveFest &bull;
    ID Event: #{{ $event->id }} &bull;
    {{ now()->format('d/m/Y H:i') }} WIB<br>
    Dokumen ini bersifat rahasia dan hanya untuk keperluan internal vendor.
</div>

</body>
</html>