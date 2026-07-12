<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Report;
use App\Models\User;
use App\Models\Booking;

class ReportController extends Controller
{
    // Menampilkan form laporan data user
    public function userReportForm()
    {
        return view('admin.reports.user-form');
    }

    // Menampilkan form laporan sales (untuk print) -
    public function salesReportForm()
    {
        return view('admin.reports.sales-form'); 
    }

    // Print laporan data user
    public function printUserReport(Request $request)
    {
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ]);

        $users = User::whereBetween('created_at', [
            $request->tanggal_awal . ' 00:00:00',
            $request->tanggal_akhir . ' 23:59:59'
        ])->get();

        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;

        return view('admin.reports.user-print', compact('users', 'tanggalAwal', 'tanggalAkhir'));
    }

    // Print laporan sales
    public function printSalesReport(Request $request)
    {
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ]);

        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;

        // data booking tiket
        $bookings = Booking::with(['event.vendor', 'ticket_category', 'merchandises'])
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [
                $tanggalAwal . ' 00:00:00',
                $tanggalAkhir . ' 23:59:59'
            ])
            ->get();

        // Group by Event
        $groupedEvents = [];
        $totalOverallTransaksi = 0;
        $totalOverallTiket = 0;
        $totalOverallKotor = 0;
        $totalOverallMerch = 0; 
        $totalOverallPajak = 0;
        $totalOverallJasa = 0;
        $totalOverallBersih = 0;

        foreach ($bookings as $booking) {
            $eventId = $booking->event_id;
            
            if (!isset($groupedEvents[$eventId])) {
                $groupedEvents[$eventId] = [
                    'event' => $booking->event,
                    'vendor' => $booking->event->vendor,
                    'transaksi' => 0,
                    'tiket_terjual' => 0,
                    'merch_terjual' => 0,
                    'kotor' => 0,
                    'pajak' => 0,
                    'jasa' => 0,
                    'bersih' => 0,
                ];
            }

            $ticketTotal = $booking->quantity * ($booking->ticket_category->price ?? 0);
            $merchTotal = 0;
            $merchQty = 0;
            foreach ($booking->merchandises as $merch) {
                $merchTotal += ($merch->pivot->price * $merch->pivot->quantity);
                $merchQty += $merch->pivot->quantity;
            }   

            $basePrice = $ticketTotal + $merchTotal;
            $tax = (int) round($basePrice * 0.10);
            $serviceFee = (int) round($basePrice * 0.03);

            $groupedEvents[$eventId]['transaksi'] += 1;
            $groupedEvents[$eventId]['tiket_terjual'] += $booking->quantity;
            $groupedEvents[$eventId]['merch_terjual'] += $merchQty;
            $groupedEvents[$eventId]['kotor'] += ($basePrice + $tax + $serviceFee); // Gross Transaction Value
            $groupedEvents[$eventId]['pajak'] += $tax;
            $groupedEvents[$eventId]['jasa'] += $serviceFee;
            $groupedEvents[$eventId]['bersih'] += $basePrice;

            $totalOverallTransaksi += 1;
            $totalOverallTiket += $booking->quantity;
            $totalOverallMerch += $merchQty;
            $totalOverallKotor += ($basePrice + $tax + $serviceFee);
            $totalOverallPajak += $tax;
            $totalOverallJasa += $serviceFee;
            $totalOverallBersih += $basePrice;
        }
        
        return view('admin.reports.sales-print', compact(
            'groupedEvents', 
            'totalOverallTransaksi', 
            'totalOverallTiket',
            'totalOverallMerch', 
            'totalOverallKotor', 
            'totalOverallPajak', 
            'totalOverallJasa', 
            'totalOverallBersih',
            'tanggalAwal', 
            'tanggalAkhir'
        ));
    }


    // Form laporan tenant
    public function tenantReportForm()
    {
        return view('admin.reports.tenant-form');
    }

    // Print laporan tenant
    public function printTenantReport(Request $request)
    {
        $request->validate([
            'tanggal_awal'  => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ]);

        $tanggalAwal  = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;

        $eventTenants = \App\Models\EventTenant::with(['event', 'tenant'])
            ->where('payment_status', 'paid')
            ->whereBetween('updated_at', [
                $tanggalAwal  . ' 00:00:00',
                $tanggalAkhir . ' 23:59:59'
            ])
            ->get();

        $groupedTenants       = [];
        $totalTenantTransaksi = 0;
        $totalTenantKotor     = 0;
        $totalTenantJasa      = 0;
        $totalTenantBersih    = 0;

        foreach ($eventTenants as $et) {
            $eventId = $et->event_id;

            if (!isset($groupedTenants[$eventId])) {
                $groupedTenants[$eventId] = [
                    'event'     => $et->event,
                    'tenants'   => [],
                    'transaksi' => 0,
                    'kotor'     => 0,
                    'jasa'      => 0,
                    'bersih'    => 0,
                ];
            }

            $boothPrice = $et->event->tenant_booth_price ?? 0;
            $jasa       = (int) round($boothPrice * 0.03);
            $bersih     = $boothPrice - $jasa;

            $groupedTenants[$eventId]['tenants'][]  = $et;
            $groupedTenants[$eventId]['transaksi']  += 1;
            $groupedTenants[$eventId]['kotor']      += $boothPrice;
            $groupedTenants[$eventId]['jasa']       += $jasa;
            $groupedTenants[$eventId]['bersih']     += $bersih;

            $totalTenantTransaksi += 1;
            $totalTenantKotor     += $boothPrice;
            $totalTenantJasa      += $jasa;
            $totalTenantBersih    += $bersih;
        }

        return view('admin.reports.tenant-print', compact(
                'groupedTenants',
                'totalTenantTransaksi', 'totalTenantKotor',
                'totalTenantJasa', 'totalTenantBersih',
                'tanggalAwal', 'tanggalAkhir'
        ));
    }


    public function ownerReportForm(Request $request)
    {
        $perPage = in_array($request->per_page, [5, 10, 25, 50]) ? $request->per_page : 10;

        $reports = Report::with('admin')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return view('admin.reports.owner', compact('reports'));
    }

    public function sendOwnerReport(Request $request)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'file'         => 'required|file|mimes:pdf,doc,docx,xlsx|max:10240',
            'period_start' => 'required|date',
            'period_end'   => 'required|date|after_or_equal:period_start',
        ]);


        $file     = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $filePath = $file->store('reports', 'public');

        Report::create([
            'admin_id'     => auth()->id(),
            'title'        => $request->title,
            'file_path'    => $filePath,
            'file_name'    => $fileName,
            'period_start' => $request->period_start,
            'period_end'   => $request->period_end,
            'status'       => 'unread',
        ]);

        return redirect()->route('admin.reports.owner.form')
            ->with('success', 'Laporan berhasil dikirim ke Owner.');
    }

}