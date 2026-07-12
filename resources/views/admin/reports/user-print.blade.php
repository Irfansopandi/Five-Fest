<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Pengguna</title>
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
        }
        
        .text-center {
            text-align: center;
        }
        
        .footer {
            margin-top: 30px;
            text-align: right;
        }
        
        .footer-info {
            display: inline-block;
            text-align: center;
            margin-top: 60px;
        }
        
        .total-row {
            font-weight: bold;
            background-color: #f9f9f9;
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
        <h1>LAPORAN DATA PENGGUNA</h1>
        <h2>Sistem Pemesanan Tiket Event</h2>
    </div>
    
    <div class="periode">
        <strong>Periode:</strong> {{ date('d/m/Y', strtotime($tanggalAwal)) }} - {{ date('d/m/Y', strtotime($tanggalAkhir)) }}
    </div>
    
    <table>
        <thead>
            <tr>
                <th class="text-center" width="5%">No</th>
                <th width="25%">Nama Pengguna</th>
                <th width="25%">Email</th>
                <th width="20%">Tanggal Daftar</th>
                <th class="text-center" width="15%">Role</th>
                <th class="text-center" width="25%">Status Akun</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $index => $user)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ date('d F Y, H:i', strtotime($user->created_at)) }}</td>
                <td class="text-center">
                    @if(strtolower($user->role) === 'admin')
                        <span style="color: #dc2626; font-weight:bold;">Admin</span>
                    @elseif (strtolower($user->role) === 'vendor')
                        <span style="color: #7c3aed; font-weight:bold;">Vendor</span>
                    @elseif (strtolower($user->role) === 'tenant')
                        <span style="color: #d97706; font-weight:bold;">Tenant</span>
                    @else 
                        <span style="color: #2563eb; font-weight: bold;">User</span>
                    @endif   
                </td>
                <td class="text-center">
                    @if($user->status === 'active')
                        <span style="color: green; font-weight: bold;">Aktif</span>
                    @else
                        <span style="color: red; font-weight: bold;">Non-Aktif</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada data pengguna pada periode ini</td>
            </tr>
            @endforelse
            
            @php
                $totalUser = $users->filter(function($u){
                    return strtolower($u->role) === 'user';
                })->count();

                $totalAdmin = $users->filter(function($u){
                    return strtolower($u->role) === 'admin';
                })->count();

                $totalVendor = $users->filter(function($u){
                    return strtolower($u->role) === 'vendor';
                })->count();

                $totalTenant = $users->filter(function($u){
                    return strtolower($u->role) === 'tenant';
                })->count();
            @endphp

            <tr class="total-row">
                <td colspan="6"><strong>Total Pengguna Terdaftar: {{ $users->count() }} orang</strong></td>
            </tr>
            <tr class="total-row">
                <td colspan="6" style="font-weight: normal;">
                    <em>Rincian: {{ $totalUser }} User, {{ $totalAdmin }} Admin, {{ $totalVendor }} Vendor, {{ $totalTenant }} Tenant</em>
                </td>
            </tr>

        </tbody>
    </table>
    
    <div class="footer">
        <div class="footer-info">
            <p>{{ date('d F Y') }}</p>
            <p>Administrator</p>
            <br><br><br>
            <p>(_____________________)</p>
        </div>
    </div>
    
    <div class="no-print" style="text-align: center; margin-top: 30px;">
        <button onclick="window.print()" style="padding: 10px 30px; font-size: 14px; cursor: pointer; background-color: #007bff; color: white; border: none; border-radius: 5px;">
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