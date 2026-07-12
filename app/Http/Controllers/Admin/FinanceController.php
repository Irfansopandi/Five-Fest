<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Storage;

class FinanceController extends Controller
{
    /**
     * Menampilkan Rekening Jasa Layanan Platform (3%)
     */
    public function serviceFeeAccount(Request $request)
    {
        $perPageVendor = $request->get('per_page_vendor', 10);
        $perPageTenant = $request->get('per_page_tenant', 10);
        
        // --- Vendor Query ---
        $queryVendor = Booking::with(['event', 'user', 'ticket_category', 'merchandises'])
            ->where('payment_status', 'paid');
            
        if ($request->filled('search_vendor')) {
            $searchVendor = $request->search_vendor;
            $queryVendor->where(function($q) use ($searchVendor) {
                $q->where('booking_code', 'like', "%{$searchVendor}%")
                  ->orWhereHas('event', function($sq) use ($searchVendor) {
                      $sq->where('title', 'like', "%{$searchVendor}%");
                  });
            });
        }
        
        $bookings = $queryVendor->latest()->paginate($perPageVendor, ['*'], 'page_vendor')->appends($request->all());
        
        // --- Tenant Query ---
        $queryTenant = \App\Models\EventTenant::with(['event', 'tenant'])
            ->where('payment_status', 'paid');
            
        if ($request->filled('search_tenant')) {
            $searchTenant = $request->search_tenant;
            $queryTenant->where(function($q) use ($searchTenant) {
                $q->whereHas('tenant', function($sq) use ($searchTenant) {
                    $sq->where('name', 'like', "%{$searchTenant}%");
                })->orWhereHas('event', function($sq) use ($searchTenant) {
                    $sq->where('title', 'like', "%{$searchTenant}%");
                });
            });
        }
        
        $booths = $queryTenant->latest()->paginate($perPageTenant, ['*'], 'page_tenant')->appends($request->all());
        
        // --- Stats: Vendor Service Fee ---
        $totalServiceFee = 0;
        $allPaidBookings = Booking::with(['ticket_category', 'merchandises'])
            ->where('payment_status', 'paid')
            ->get();
            
        foreach ($allPaidBookings as $booking) {
            $ticketTotal = $booking->quantity * ($booking->ticket_category->price ?? 0);
            $merchTotal = 0;
            foreach ($booking->merchandises as $merch) {
                $merchTotal += ($merch->pivot->price * $merch->pivot->quantity);
            }
            $basePrice = $ticketTotal + $merchTotal;
            $totalServiceFee += (int) round($basePrice * 0.03);
        }

        // --- Stats: Tenant Service Fee ---
        $totalTenantServiceFee = 0;
        $allPaidBooths = \App\Models\EventTenant::with('event')
            ->where('payment_status', 'paid')
            ->get();
            
        foreach ($allPaidBooths as $booth) {
            $boothPrice = $booth->event->tenant_booth_price ?? 0;
            $totalTenantServiceFee += (int) round($boothPrice * 0.03);
        }
        
        // Active Tab
        $activeTab = $request->get('tab', 'vendor');

        return view('admin.finance.service-fee', compact(
            'bookings', 
            'booths', 
            'totalServiceFee', 
            'totalTenantServiceFee',
            'activeTab'
        ));
    }

    /**
     * Menampilkan Rekening Jasa Layanan Tenant (3%)
     */
    public function tenantServiceFeeAccount(Request $request)
    {
        return redirect()->route('admin.finance.service-fee', ['tab' => 'tenant']);
    }

    /**
     * Menampilkan Rekening Pajak Negara (10%)
     */
    public function taxAccount(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        // Ambil semua booking yang sudah paid, group nanti per event_id di PHP
        $query = Booking::with(['event.vendor', 'ticket_category', 'merchandises'])
            ->where('payment_status', 'paid');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                  ->orWhereHas('event', function ($sq) use ($search) {
                      $sq->where('title', 'like', "%{$search}%");
                  });
            });
        }

        $allBookings = $query->get();

        // Group booking per event_id
        $grouped = $allBookings->groupBy('event_id');

        $eventTaxData = [];

        foreach ($grouped as $eventId => $bookings) {
            $event = $bookings->first()->event;
            if (!$event) continue;

            $totalBase = 0;
            $totalTax = 0;
            $remittedCount = 0;
            $bookingCount = $bookings->count();

            foreach ($bookings as $booking) {
                $ticketTotal = $booking->quantity * ($booking->ticket_category->price ?? 0);
                $merchTotal = 0;
                foreach ($booking->merchandises as $merch) {
                    $merchTotal += ($merch->pivot->price * $merch->pivot->quantity);
                }
                $basePrice = $ticketTotal + $merchTotal;
                $tax = (int) round($basePrice * 0.10);

                $totalBase += $basePrice;
                $totalTax += $tax;

                if ($booking->tax_remitted) {
                    $remittedCount++;
                }
            }

            // Status: 'paid' jika semua booking sudah disetor, 'partial' jika sebagian, 'pending' jika belum sama sekali
            if ($remittedCount === $bookingCount) {
                $status = 'paid';
            } elseif ($remittedCount > 0) {
                $status = 'partial';
            } else {
                $status = 'pending';
            }

            // Filter status dari request (jika ada)
            if ($request->filled('status')) {
                if ($request->status === 'paid' && $status !== 'paid') continue;
                if ($request->status === 'pending' && $status === 'paid') continue;
            }

            $eventTaxData[] = [
                'event_id' => $eventId,
                'event' => $event,
                'vendor' => $event->vendor,
                'booking_count' => $bookingCount,
                'remitted_count' => $remittedCount,
                'total_base' => $totalBase,
                'total_tax' => $totalTax,
                'status' => $status,
            ];
        }

        // Urutkan terbaru dulu berdasarkan event_id (atau bisa pakai created_at terakhir booking)
        $eventTaxData = collect($eventTaxData)->sortByDesc('event_id')->values();

        // Manual pagination karena data sudah berbentuk array hasil agregasi
        $currentPage = $request->get('page', 1);
        $pagedData = $eventTaxData->forPage($currentPage, $perPage)->values();

        $eventTaxPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $pagedData,
            $eventTaxData->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Hitung total pajak global (tidak terpengaruh filter & pagination)
        $totalTax = 0;
        $totalRemitted = 0;
        $totalPending = 0;

        $allPaidBookings = Booking::with(['ticket_category', 'merchandises'])
            ->where('payment_status', 'paid')
            ->get();

        foreach ($allPaidBookings as $booking) {
            $ticketTotal = $booking->quantity * ($booking->ticket_category->price ?? 0);
            $merchTotal = 0;
            foreach ($booking->merchandises as $merch) {
                $merchTotal += ($merch->pivot->price * $merch->pivot->quantity);
            }
            $basePrice = $ticketTotal + $merchTotal;
            $tax = (int) round($basePrice * 0.10);

            $totalTax += $tax;
            if ($booking->tax_remitted) {
                $totalRemitted += $tax;
            } else {
                $totalPending += $tax;
            }
        }

        return view('admin.finance.tax', compact('eventTaxPaginated', 'totalTax', 'totalRemitted', 'totalPending'));
    }

    /**
     * Setor Pajak (Ubah status tax_remitted menjadi true)
     */
    public function remitTax(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        if ($booking->tax_remitted) {
            return response()->json(['success' => false, 'message' => 'Pajak transaksi ini sudah disetorkan.'], 400);
        }
        
        $request->validate([
            'tax_receipt' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);
        
        $path = $request->file('tax_receipt')->store('tax_receipts', 'public');
        
        $booking->tax_remitted = true;
        $booking->tax_receipt = $path;
        $booking->save();
        
        return response()->json(['success' => true, 'message' => 'Pajak transaksi ' . $booking->booking_code . ' berhasil disetor.']);
    }

    /**
     * Setor Pajak untuk SEMUA booking dalam satu event sekaligus
     */
    public function remitTaxByEvent(Request $request, $eventId)
    {
        $request->validate([
            'tax_receipt' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $bookings = Booking::where('event_id', $eventId)
            ->where('payment_status', 'paid')
            ->where('tax_remitted', false)
            ->get();

        if ($bookings->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Tidak ada booking yang belum disetor untuk event ini.'], 400);
        }

        $path = $request->file('tax_receipt')->store('tax_receipts', 'public');

        foreach ($bookings as $booking) {
            $booking->tax_remitted = true;
            $booking->tax_receipt = $path;
            $booking->save();
        }

        return response()->json([
            'success' => true,
            'message' => "Pajak untuk {$bookings->count()} transaksi pada event ini berhasil disetor."
        ]);
    }

    public function getTaxReceipt($eventId)
    {
        $booking = \App\Models\Booking::where('event_id', $eventId)
            ->where('tax_remitted', true)        // ← tambah ini
            ->whereNotNull('tax_receipt')
            ->latest()
            ->first();

        if (!$booking || !$booking->tax_receipt) {
            return response()->json(['url' => null]);
        }

        return response()->json([
            'url' => Storage::url($booking->tax_receipt)
        ]);
    }
}
