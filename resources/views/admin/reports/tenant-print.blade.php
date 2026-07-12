<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Sewa Booth Tenant</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; padding: 20px; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #333; padding-bottom: 15px; }
        .header h1 { font-size: 24px; margin-bottom: 5px; }
        .header h2 { font-size: 18px; font-weight: normal; margin-bottom: 10px; }
        .periode { text-align: center; margin-bottom: 20px; font-size: 13px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table, th, td { border: 1px solid #333; }
        th { background-color: #f0f0f0; padding: 10px; text-align: left; font-weight: bold; }
        td { padding: 8px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .total-row { font-weight: bold; background-color: #f9f9f9; }
        .summary { margin-top: 30px; float: right; width: 320px; }
        .summary table { width: 100%; }
        .summary .total-row { background-color: #f0f0f0; font-size: 14px; }
        .footer { clear: both; margin-top: 100px; text-align: right; }
        .footer-info { display: inline-block; text-align: center; margin-top: 60px; }
        .event-group { margin-bottom: 30px; }
        .event-title { background: #f0f0f0; padding: 8px 10px; font-weight: bold; font-size: 13px; border: 1px solid #333; border-bottom: none; }
        @media print { body { padding: 0; } .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN SEWA BOOTH TENANT</h1>
        <h2>Sistem Pemesanan Tiket Event — Five Fest</h2>
    </div>

    <div class="periode">
        <strong>Periode:</strong> {{ date('d/m/Y', strtotime($tanggalAwal)) }} - {{ date('d/m/Y', strtotime($tanggalAkhir)) }}
    </div>

    @forelse($groupedTenants as $eventId => $data)
    <div class="event-group">
        <div class="event-title">
            Event: {{ $data['event']->title ?? 'Event Dihapus' }}
            &nbsp;|&nbsp; Total Tenant: {{ $data['transaksi'] }}
            &nbsp;|&nbsp; Harga/Booth: Rp {{ number_format($data['event']->tenant_booth_price ?? 0, 0, ',', '.') }}
        </div>
        <table>
            <thead>
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th width="30%">Nama Tenant</th>
                    <th width="25%">Email</th>
                    <th width="15%">Tgl Bayar</th>
                    <th class="text-right" width="12%">Harga Booth</th>
                    <th class="text-right" width="13%">Platform Fee (3%)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['tenants'] as $idx => $et)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td>{{ $et->tenant->name ?? '-' }}</td>
                    <td>{{ $et->tenant->email ?? '-' }}</td>
                    <td>{{ date('d/m/Y', strtotime($et->updated_at)) }}</td>
                    <td class="text-right">Rp {{ number_format($et->event->tenant_booth_price ?? 0, 0, ',', '.') }}</td>
                    <td class="text-right" style="color:#d97706;">
                        Rp {{ number_format(round(($et->event->tenant_booth_price ?? 0) * 0.03), 0, ',', '.') }}
                    </td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="4">Subtotal Event Ini</td>
                    <td class="text-right">Rp {{ number_format($data['kotor'], 0, ',', '.') }}</td>
                    <td class="text-right" style="color:#d97706;">Rp {{ number_format($data['jasa'], 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    @empty
    <div style="text-align:center; padding:40px; border:1px solid #333;">
        Tidak ada data pembayaran booth tenant pada periode ini.
    </div>
    @endforelse

    @if(count($groupedTenants) > 0)
    <div class="summary">
        <table>
            <tr><td colspan="2" style="background:#7c3aed; color:white; font-weight:bold; padding:10px;">RINGKASAN KESELURUHAN</td></tr>
            <tr>
                <td><strong>Total Tenant Membayar:</strong></td>
                <td class="text-right">{{ number_format($totalTenantTransaksi, 0, ',', '.') }} tenant</td>
            </tr>
            <tr>
                <td><strong>Total Platform Fee (3%):</strong></td>
                <td class="text-right" style="color:#d97706;">Rp {{ number_format($totalTenantJasa, 0, ',', '.') }}</td>
            </tr>
            <tr class="total-row">
                <td><strong>TOTAL PENDAPATAN KOTOR:</strong></td>
                <td class="text-right">Rp {{ number_format($totalTenantKotor, 0, ',', '.') }}</td>
            </tr>
            <tr style="background:#dff0d8;">
                <td><strong>BERSIH SETELAH FEE:</strong></td>
                <td class="text-right" style="color:#3c763d; font-weight:bold;">Rp {{ number_format($totalTenantBersih, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>
    @endif

    <div class="footer">
        <div class="footer-info">
            <p>{{ date('d F Y') }}</p>
            <p>Administrator</p>
            <br><br><br>
            <p>(_____________________)</p>
        </div>
    </div>

    <div class="no-print" style="text-align:center; margin-top:30px; clear:both;">
        <button onclick="window.print()" style="padding:10px 30px; font-size:14px; cursor:pointer; background:#7c3aed; color:white; border:none; border-radius:5px;">
            Cetak Laporan
        </button>
        <button onclick="window.close()" style="padding:10px 30px; font-size:14px; cursor:pointer; background:#6c757d; color:white; border:none; border-radius:5px; margin-left:10px;">
            Tutup
        </button>
    </div>

    <script>
        window.onload = function() { window.print(); }
    </script>
</body>
</html>