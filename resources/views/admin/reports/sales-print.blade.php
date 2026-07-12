<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Penjualan Tiket & Merchandise Vendor</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            font-size: 12px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #333;
            padding-bottom: 15px;
        }
        
        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        
        .header h2 {
            font-size: 18px;
            font-weight: normal;
            margin-bottom: 10px;
        }
        
        .periode {
            text-align: center;
            margin-bottom: 20px;
            font-size: 13px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            table-layout: fixed;
        }
        
        table, th, td {
            border: 1px solid #333;
        }
        
        th {
            background-color: #f0f0f0;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }
        
        td {
            padding: 8px;
            word-wrap: break-word;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        td.text-right,
        td.text-center {
            white-space: nowrap; 
        }
        
        .summary {
            margin-top: 30px;
            float: right;
            width: 300px;
        }
        
        .summary table {
            width: 100%;
        }
        
        .summary td {
            padding: 8px;
        }
        
        .summary .total-row {
            background-color: #f0f0f0;
            font-weight: bold;
            font-size: 14px;
        }
        
        .footer {
            clear: both;
            margin-top: 100px;
            text-align: right;
        }
        
        .footer-info {
            display: inline-block;
            text-align: center;
            margin-top: 60px;
        }
        
        @media print {
            body {
                padding: 0;
            }
            
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN DATA PENJUALAN TIKET VENDOR & MERCHANDISE</h1>
        <h2>Sistem Pemesanan Tiket Event</h2>
    </div>
    
    <div class="periode">
        <strong>Periode:</strong> {{ date('d/m/Y', strtotime($tanggalAwal)) }} - {{ date('d/m/Y', strtotime($tanggalAkhir)) }}
    </div>
    
    <table>
        <thead>
            <tr>
                <th class="text-center" width="5%">No</th>
                <th width="20%">Nama Event</th>
                <th width="15%">Vendor</th>
                <th class="text-center" width="10%">Total Transaksi</th>
                <th class="text-center" width="10%">Tiket Terjual</th>
                <th class="text-center" width="10%">Merchandise Terjual</th>
                <th class="text-right" width="12%">Pendapatan Kotor</th>
                <th class="text-right" width="10%">Pajak (10%)</th>
                <th class="text-right" width="10%">Jasa (3%)</th>
                <th class="text-right" width="12%">Bersih Vendor</th>
            </tr>
        </thead>
        <tbody>
            @php $i = 1; @endphp
            @forelse($groupedEvents as $eventId => $data)
            <tr>
                <td class="text-center">{{ $i++ }}</td>
                <td>{{ $data['event']->title ?? 'Event Dihapus' }}</td>
                <td>{{ $data['vendor']->name ?? '-' }}</td>
                <td class="text-center">{{ number_format($data['transaksi'], 0, ',', '.') }}</td>
                <td class="text-center">{{ number_format($data['tiket_terjual'], 0, ',', '.') }}</td>
                <td class="text-center">{{ number_format($data['merch_terjual'], 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($data['kotor'], 0, ',', '.') }}</td>
                <td class="text-right" style="color: #d9534f;">Rp {{ number_format($data['pajak'], 0, ',', '.') }}</td>
                <td class="text-right" style="color: #f0ad4e;">Rp {{ number_format($data['jasa'], 0, ',', '.') }}</td>
                <td class="text-right" style="font-weight: bold; color: #5cb85c;">Rp {{ number_format($data['bersih'], 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center">Tidak ada data penjualan pada periode ini</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    @if(count($groupedEvents) > 0)
    <div class="summary">
        <table>
            <tr>
                <td><strong>Total Keseluruhan Transaksi:</strong></td>
                <td class="text-right">{{ number_format($totalOverallTransaksi, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>Total Keseluruhan Tiket Terjual:</strong></td>
                <td class="text-right">{{ number_format($totalOverallTiket, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>Total Keseluruhan Merchandise Terjual:</strong></td>
                <td class="text-right">{{ number_format($totalOverallMerch, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>Total Keseluruhan Pajak (10%):</strong></td>
                <td class="text-right" style="color: #d9534f;">Rp {{ number_format($totalOverallPajak, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>Total Keseluruhan Jasa Layanan (3%):</strong></td>
                <td class="text-right" style="color: #f0ad4e;">Rp {{ number_format($totalOverallJasa, 0, ',', '.') }}</td>
            </tr>
            <tr class="total-row">
                <td><strong>TOTAL PENDAPATAN KOTOR:</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($totalOverallKotor, 0, ',', '.') }}</strong></td>
            </tr>
            <tr class="total-row" style="background-color: #dff0d8;">
                <td><strong>TOTAL BERSIH VENDOR:</strong></td>
                <td class="text-right" style="color: #3c763d;"><strong>Rp {{ number_format($totalOverallBersih, 0, ',', '.') }}</strong></td>
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
    
    <div class="no-print" style="text-align: center; margin-top: 30px; clear: both;">
        <button onclick="window.print()" style="padding: 10px 30px; font-size: 14px; cursor: pointer; background-color: #28a745; color: white; border: none; border-radius: 5px;">
            Cetak Laporan
        </button>
        <button onclick="window.close()" style="padding: 10px 30px; font-size: 14px; cursor: pointer; background-color: #6c757d; color: white; border: none; border-radius: 5px; margin-left: 10px;">
            Tutup
        </button>
    </div>
    
    <script>
        // Auto print saat halaman dibuka (opsional)
        window.onload = function() { window.print(); }
    </script>
</body>
</html>