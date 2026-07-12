<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Ticket;
use App\Models\Event;
use App\Models\VendorWithdrawal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function index(Request $request)
    {

        // SET TIMEZONE EXPLICITLY
        Carbon::setLocale('id');

        // Scope untuk vendor yang sudah diverifikasi
        $verifiedScope = function ($query) {
            $query->whereHas('event.vendor', function ($q) {
                $q->where('verification_status', 'verified');
            });
        };

        // Auto-cancel bookings that have expired (older than 5 minutes and still pending)
        $expiryThreshold = Carbon::now('Asia/Jakarta')->subMinutes(5);
        $expiredBookings = Booking::where('payment_status', 'pending')
            ->where('booking_status', 'pending')
            ->where('created_at', '<=', $expiryThreshold)
            ->get();

        if ($expiredBookings->count() > 0) {
            DB::beginTransaction();
            try {
                foreach ($expiredBookings as $booking) {
                    $booking->update([
                        'booking_status' => 'cancelled',
                        'payment_status' => 'cancelled'
                    ]);

                    // Kembalikan kuota tiket
                    $ticketCategory = \App\Models\TicketCategory::find($booking->ticket_category_id);
                    if ($ticketCategory) {
                        $ticketCategory->increment('quota', $booking->quantity);
                    }
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                \Illuminate\Support\Facades\Log::error('Auto-cancel in Sales index failed: ' . $e->getMessage());
            }
        }

        // Ambil per_page dari request, default 5 agar pagination terlihat
        $perPage = $request->get('per_page', 5);

        // Total Penjualan (hanya yang sudah paid)
        $totalSales = Booking::where('payment_status', 'paid')
            ->where($verifiedScope)
            ->sum('total_price');

        // Total Tiket Terjual
        $totalTickets = Booking::where('payment_status', 'paid')
            ->where($verifiedScope)
            ->sum('quantity');

        // Total Expired Bookings
        $totalExpired = Booking::where('booking_status', 'cancelled')
            ->where($verifiedScope)
            ->count();

        // Sales by Event (Top Selling)
        $searchEvent    = $request->get('search_event');
        $searchVendor   = $request->get('search_vendor');
        $searchCategory = $request->get('search_category');

       $salesByEvent = Booking::select('event_id', DB::raw('SUM(total_price) as total'))
            ->where('payment_status', 'paid')
            ->where($verifiedScope)
            ->with(['event', 'event.vendor'])
            ->when($searchEvent, function ($q) use ($searchEvent) {
                $q->whereHas('event', fn($q2) => $q2->where('title', 'like', "%{$searchEvent}%"));
            })
            ->when($searchVendor, function ($q) use ($searchVendor) {
                $q->whereHas('event.vendor', fn($q2) => $q2->where('name', 'like', "%{$searchVendor}%"));
            })
            ->when($searchCategory, function ($q) use ($searchCategory) {
                $q->whereHas('event', fn($q2) => $q2->where('category', $searchCategory));
            })
            ->groupBy('event_id')
            ->orderBy('total', 'desc')
            ->paginate($perPage, ['*'], 'event_page')
            ->appends($request->only(['search_event', 'search_vendor', 'search_category', 'per_page']));

        // Daftar kategori unik untuk dropdown filter
        $categories = Event::whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        // All Paid Bookings
        $bookings = Booking::with(['user', 'event','merchandises'])
            ->where('payment_status', 'paid')
            ->where($verifiedScope)
            ->latest()
            ->paginate($perPage, ['*'], 'paid_page');

        // Unpaid Bookings dengan informasi waktu kedaluwarsa
        $unpaidBookings = Booking::with(['user', 'event'])
            ->where('payment_status', '!=', 'paid')
            ->where('booking_status', '!=', 'cancelled')
            ->where($verifiedScope)
            ->latest()
            ->paginate($perPage, ['*'], 'unpaid_page');

        // Expired Bookings List
        $expiredBookingsList = Booking::with(['user', 'event','merchandises'])
            ->where('booking_status', 'cancelled')
            ->where($verifiedScope)
            ->latest()
            ->paginate($perPage, ['*'], 'expired_page');

        $now = Carbon::now('Asia/Jakarta');

        // Tambahkan informasi expiry time untuk setiap booking
        foreach ($unpaidBookings as $booking) {
            // set waktu kedaluawarsa: 5 menit setelah booking di buat
            $expiryTime = $booking->created_at->copy()
                ->timezone('Asia/Jakarta')
                ->addMinutes(5);
        

            $booking->expiry_time = $expiryTime;
            $booking->time_remaining = $expiryTime->gt($now)
                ? $now->diffInMinutes($expiryTime)
                : 0;
            $booking->is_expired = $expiryTime->lte($now);

        };

        // Hitung bookings yang akan expired dalam 1 jam
        $urgentBookings = Booking::where('payment_status', '!=', 'paid')
            ->where('booking_status', '!=', 'cancelled')
            ->where($verifiedScope)
            ->whereBetween('created_at' , [
                Carbon::now('Asia/Jakarta')->subMinutes(5),
                Carbon::now('Asia/Jakarta')->subMinutes(4)
            ])
            ->count();

        return view('admin.sales.index', compact(
            'totalSales',
            'totalTickets',
            'totalExpired',
            'salesByEvent',
            'bookings',
            'unpaidBookings',
            'expiredBookingsList',
            'urgentBookings',
            'categories',
            'searchEvent',
            'searchVendor',
            'searchCategory'
        ));

    }
    // View Daftar Tiket
    public function tickets(Request $request)
    {
        $query = Ticket::with(['booking.user', 'booking.event', 'booking.ticket_category', 'scannedBy']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ticket_code', 'like', '%' . $search . '%')
                ->orWhereHas('booking.user', function ($q2) use ($search) {
                    $q2->where('name', 'like', '%' . $search . '%');
                });
            });
        }

        if ($request->filled('event_id')) {
            $query->whereHas('booking', function ($q) use ($request) {
                $q->where('event_id', $request->event_id);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $perPage = $request->get('per_page', 15);
        $tickets          = $query->latest()->paginate($perPage)->appends($request->all());
        $events           = Event::orderBy('title')->get();
        $totalTickets     = Ticket::count();
        $unusedTickets    = Ticket::where('status', 'active')->count();
        $usedTickets      = Ticket::whereIn('status', ['used', 'scanned'])->count();
        $cancelledTickets = Ticket::where('status', 'cancelled')->count();

        return view('admin.tickets.index', compact(
            'tickets', 'events', 'totalTickets', 'unusedTickets', 'usedTickets', 'cancelledTickets'
        ));
    }

    // Method untuk auto-cancel expired bookings (bisa dijadwalkan dengan Laravel Scheduler)
    public function cancelExpiredBookings()
    {
        $expiryThreshold = Carbon::now('Asia/Jakarta')->subMinutes(5);

        $expiredBookings = Booking::where('payment_status', '!=', 'paid')
            ->where('booking_status', '!=', 'cancelled')
            ->where('created_at', '<=', $expiryThreshold)
            ->get();

        foreach ($expiredBookings as $booking) {
            $booking->update([
                'booking_status' => 'cancelled',
                'cancel_reason' => 'Pembayaran tidak dilakukan dalam waktu 5 menit'
            ]);
            
            // Kembalikan stok tiket
            $event = $booking->event;
            $event->increment('available_seats', $booking->quantity);
        }

        return response()->json([
            'message' => "Berhasil membatalkan {$expiredBookings->count()} booking yang kedaluwarsa"
        ]);
    }

    public function income(Request $request)
    {
        $perPage = $request->get('per_page', 5);
        $activeTab = $request->get('tab', 'pendapatan');
        
        $query = \App\Models\User::where('role', 'vendor')
            ->where('verification_status', 'verified');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $vendors = $query->paginate($perPage)->appends($request->all());
        
        $vendorIncomes = [];
        $overallGross = 0;
        $overallTax = 0;
        $overallService = 0;
        $overallNetVendor = 0;

        foreach ($vendors as $vendor) {
            $bookings = Booking::with(['ticket_category', 'merchandises'])
                ->whereHas('event', function ($query) use ($vendor) {
                    $query->where('user_id', $vendor->id);
                })
                ->where('payment_status', 'paid')
                ->get();

            $vendorGross = 0;
            $vendorTax = 0;
            $vendorService = 0;
            $vendorNet = 0;
            $ticketsSold = 0;
            $boothsSold = 0;

            foreach ($bookings as $booking) {
                $ticketTotal = $booking->quantity * ($booking->ticket_category->price ?? 0);
                
                $merchTotal = 0;
                foreach ($booking->merchandises as $merch) {
                    $merchTotal += ($merch->pivot->price * $merch->pivot->quantity);
                }

                $basePrice = $ticketTotal + $merchTotal;
                $tax = (int) round($basePrice * 0.10);
                $serviceFee = (int) round($basePrice * 0.03);
                
                $vendorGross += $booking->total_price; // Total uang yang masuk
                $vendorTax += $tax;
                $vendorService += $serviceFee;
                $vendorNet += $basePrice; // Vendor net is base price since tax & service are added on top
                $ticketsSold += $booking->quantity;
            }

            $booths = \App\Models\EventTenant::with('event')
                ->whereHas('event', function ($query) use ($vendor) {
                    $query->where('user_id', $vendor->id);
                })
                ->where('payment_status', 'paid')
                ->get();

            foreach ($booths as $booth) {
                $boothPrice = $booth->event->tenant_booth_price ?? 0;
                $tenantServiceFee = (int) round($boothPrice * 0.03);
                
                // Tenant pays boothPrice + 3% service fee
                $vendorGross += ($boothPrice + $tenantServiceFee); 
                $vendorTax += 0;
                $vendorService += $tenantServiceFee; // Tenant service fee goes to platform
                $vendorNet += $boothPrice; // Vendor net is 100% of boothPrice
                
                $boothsSold += 1;
            }

            // Get pending withdrawals for this vendor
            $vendorPendingWithdrawals = VendorWithdrawal::where('vendor_id', $vendor->id)
                ->where('status', 'pending')
                ->latest()
                ->get();

            // Calculate withdrawal totals for this vendor
            $allVendorWithdrawals = VendorWithdrawal::where('vendor_id', $vendor->id)->get();
            $vendorTotalWithdrawn = $allVendorWithdrawals->where('status', 'approved')->sum('amount');
            $vendorTotalPendingWd = $allVendorWithdrawals->where('status', 'pending')->sum('amount');
            $vendorRemainingBalance = $vendorNet - $vendorTotalWithdrawn - $vendorTotalPendingWd;
            if ($vendorRemainingBalance < 0) $vendorRemainingBalance = 0;

            $vendorIncomes[] = [
                'vendor' => $vendor,
                'tickets_sold' => $ticketsSold,
                'booths_sold' => $boothsSold,
                'gross_income' => $vendorGross,
                'tax' => $vendorTax,
                'service_fee' => $vendorService,
                'net_income' => $vendorNet,
                'total_withdrawn' => $vendorTotalWithdrawn,
                'total_pending_wd' => $vendorTotalPendingWd,
                'remaining_balance' => $vendorRemainingBalance,
                'pending_withdrawals' => $vendorPendingWithdrawals,
                'all_withdrawals' => $allVendorWithdrawals->sortByDesc('created_at'),
            ];

            $overallGross += $vendorGross;
            $overallTax += $vendorTax;
            $overallService += $vendorService;
            $overallNetVendor += $vendorNet;
        }

        // To get accurate overall stats across ALL vendors, not just paginated ones
        // We recalculate overall without pagination if there are many pages.
        // For simplicity and since we want total platform stats, let's calculate global stats:
        $allBookings = Booking::with(['ticket_category', 'merchandises'])
            ->whereHas('event.vendor', function($q) {
                $q->where('role', 'vendor')
                  ->where('verification_status', 'verified');
            })->where('payment_status', 'paid')->get();
            
        $globalGross = 0;
        $globalTax = 0;
        $globalService = 0;
        $globalNet = 0;

        foreach ($allBookings as $booking) {
            $ticketTotal = $booking->quantity * ($booking->ticket_category->price ?? 0);
            $merchTotal = 0;
            foreach ($booking->merchandises as $merch) {
                $merchTotal += ($merch->pivot->price * $merch->pivot->quantity);
            }
            $basePrice = $ticketTotal + $merchTotal;
            $tax = (int) round($basePrice * 0.10);
            $serviceFee = (int) round($basePrice * 0.03);
            
            $globalGross += $booking->total_price;
            $globalTax += $tax;
            $globalService += $serviceFee;
            $globalNet += $basePrice;
        }

        $allBooths = \App\Models\EventTenant::with('event')
            ->whereHas('event.vendor', function($q) {
                $q->where('role', 'vendor')
                  ->where('verification_status', 'verified');
            })->where('payment_status', 'paid')->get();
            
        foreach ($allBooths as $booth) {
            $boothPrice = $booth->event->tenant_booth_price ?? 0;
            $tenantServiceFee = (int) round($boothPrice * 0.03);
            
            $globalGross += ($boothPrice + $tenantServiceFee);
            $globalTax += 0;
            $globalService += $tenantServiceFee;
            $globalNet += $boothPrice;
        }

        // Count total pending withdrawals across all vendors
        $totalPendingWithdrawals = VendorWithdrawal::where('status', 'pending')->count();

        // All withdrawals for history tab
        $allWithdrawals = VendorWithdrawal::with('vendor')
            ->latest()
            ->get();

        return view('admin.sales.income', compact(
            'vendors', 'vendorIncomes', 
            'globalGross', 'globalTax', 'globalService', 'globalNet',
            'totalPendingWithdrawals', 'activeTab', 'allWithdrawals'
        ));
    }

    /**
     * Approve a vendor withdrawal request
     */
    public function approveWithdrawal($id)
    {
        $withdrawal = VendorWithdrawal::findOrFail($id);

        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Penarikan ini sudah diproses sebelumnya.');
        }

        $withdrawal->update([
            'status' => 'approved',
            'notes' => 'Disetujui oleh admin pada ' . now()->format('d M Y H:i'),
        ]);

        return back()->with('success', 'Penarikan sebesar Rp ' . number_format($withdrawal->amount, 0, ',', '.') . ' untuk ' . ($withdrawal->vendor->name ?? 'Vendor') . ' berhasil disetujui.');
    }

    /**
     * Reject a vendor withdrawal request
     */
    public function rejectWithdrawal(Request $request, $id)
    {
        $withdrawal = VendorWithdrawal::findOrFail($id);

        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Penarikan ini sudah diproses sebelumnya.');
        }

        $request->validate([
            'reject_reason' => 'required|string|max:500',
        ], [
            'reject_reason.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $withdrawal->update([
            'status' => 'rejected',
            'notes' => $request->reject_reason,
        ]);

        return back()->with('success', 'Penarikan untuk ' . ($withdrawal->vendor->name ?? 'Vendor') . ' berhasil ditolak.');
    }
}