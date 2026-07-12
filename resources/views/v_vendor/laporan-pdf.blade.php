<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan Vendor - FiveFest</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #7c3aed; padding-bottom: 10px; }
        .header h2 { color: #7c3aed; margin-bottom: 5px; }
        .header p { margin: 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background-color: #f3f4f6; color: #4b5563; text-transform: uppercase; font-size: 10px; letter-spacing: 0.5px; text-align: left; padding: 12px; border-bottom: 1px solid #e5e7eb; }
        td { padding: 12px; border-bottom: 1px solid #f3f4f6; vertical-align: top; }
        .footer { text-align: right; margin-top: 50px; font-size: 10px; color: #999; }
        .text-success { color: #15803d; font-weight: bold; }
        .total-box { background-color: #7c3aed; color: white; padding: 15px; border-radius: 8px; margin-top: 20px; }
        .total-box h4 { margin: 0; font-size: 14px; }
        .total-box .price { font-size: 20px; font-weight: bold; margin-top: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN PENJUALAN VENDOR</h2>
        <p>FiveFest Platform</p>
        <p style="font-size: 10px;">Dicetak pada: {{ now()->format('d M Y, H:i') }} WIB</p>
    </div>

    <p><strong>Nama Vendor:</strong> {{ auth()->user()->name }}</p>

    <table>
        <thead>
            <tr>
                <th>Event</th>
                <th>Tanggal Event</th>
                <th>Tiket Terjual</th>
                <th>Total Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @foreach($eventStats as $stat)
            <tr>
                <td><strong>{{ $stat->title }}</strong></td>
                <td>{{ $stat->date->format('d M Y') }}</td>
                <td>{{ $stat->paid_bookings_count }} Tiket</td>
                <td class="text-success">Rp {{ number_format($stat->total_revenue ?? 0, 0, ',', '.') }}</td>
            </tr>
            @php $grandTotal += $stat->total_revenue ?? 0; @endphp
            @endforeach
        </tbody>
    </table>

    <div class="total-box">
        <h4>TOTAL SELURUH PENDAPATAN</h4>
        <div class="price">Rp {{ number_format($grandTotal, 0, ',', '.') }}</div>
    </div>

    <div class="footer">
        <p>Laporan ini dihasilkan secara otomatis oleh sistem FiveFest.</p>
        <p>&copy; {{ date('Y') }} FiveFest Team.</p>
    </div>
</body>
</html>
