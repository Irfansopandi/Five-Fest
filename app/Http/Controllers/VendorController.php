<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Booking;
use App\Models\TicketCategory;
use App\Models\Merchandise;
use App\Models\EventTenant;
use App\Models\VendorWithdrawal;
// use App\Services\SpotifyService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http; 
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VendorController extends Controller
{
    public function homeVendor()
    {
        return view('v_vendor.home');
    }

    public function landingPage()
    {
        return view('v_vendor.home');
    }

    public function dashboard()
    {
        $vendorId = auth()->id();
        $totalEvents = Event::where('user_id', $vendorId)->count();
        
        $totalBookings = Booking::whereHas('event', fn($q) => $q->where('user_id', $vendorId))
            ->where('payment_status', 'paid')
            ->count();
            
        $ticketRevenue = Booking::whereHas('event', fn($q) => $q->where('user_id', $vendorId))
            ->where('payment_status', 'paid')
            ->sum('total_price');
            
        $boothRevenue = \App\Models\EventTenant::whereHas('event', fn($q) => $q->where('user_id', $vendorId))
            ->where('payment_status', 'paid')
            ->get()
            ->sum(function($eventTenant) {
                return $eventTenant->event->tenant_booth_price ?? 0;
            });
            
        $ticketBaseRevenue = $ticketRevenue / 1.13;
        $totalRevenue = $ticketBaseRevenue + $boothRevenue;
            
        $recentBookings = Booking::whereHas('event', fn($q) => $q->where('user_id', $vendorId))
            ->with(['user', 'event'])
            ->latest()
            ->take(5)
            ->get();

        
        // chat data untuk dashboard vendor
        $eventSalesData = Event::where('user_id', $vendorId)
            ->withSum(['bookings as tickets_sold' => function($q) {
                $q->where('payment_status', 'paid');
            }], 'quantity')
            ->withSum(['bookings as revenue' => function($q) {
                $q->where('payment_status', 'paid');
            }], 'total_price')
            ->orderByDesc('tickets_sold')
            ->get();

        $chartData = [
            'labels' => $eventSalesData->pluck('title'),
            'tickets'=> $eventSalesData->pluck('tickets_sold')->map(fn($v) => (int)($v ?? 0)),
            'revenue' => $eventSalesData->pluck('revenue')->map(fn($v) => (float)(($v ?? 0) )),
        ];

        return view('v_vendor.dashboard', compact(
            'totalEvents', 'totalBookings', 'totalRevenue', 'recentBookings', 'chartData'));
    }

    // Event Management
    public function indexEvent(Request $request)
    {
        $perPage = $request->input('per_page', 9);
        $tab = $request->input('tab', 'upcoming');

        $query = Event::where('user_id', auth()->id())->latest();

        if ($tab === 'past') {
            $query->whereDate('date', '<', now()->toDateString());
        } else {
            $query->whereDate('date', '>=', now()->toDateString());
        }

        $events = $query->paginate($perPage);

        return view('v_vendor.event.index', compact('events', 'perPage', 'tab'));
    }

    public function createEvent()
    {
        $categories = Event::select('category')->distinct()->pluck('category');
        return view('v_vendor.event.create', compact('categories'));
    }

    public function storeEvent(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'venue' => 'required|string|max:255',
            'date' => 'required|date',
            'time' => 'required',
            'max_tickets_per_user' => 'required|integer|min:1',
            'image' => 'required|image|max:10240',
            'category' => 'nullable|string',
            'artist' => 'nullable|string|max:255',
            'seat_plan' => 'nullable|image|max:10240',
            'venue_map' => 'nullable|image|max:10240',
            'terms_image' => 'nullable|image|max:10240',
            'gate_open_time' => 'nullable',
            'venue_location_url' => 'nullable|url',
            'open_sale_at' => 'nullable',
            'terms' => 'nullable|string',
            'tenant_quota' => 'nullable|integer|min:1',
            'map_notice' => 'nullable|string',
            'spotify_playlist_id' => 'nullable|string|max:255',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'inactive';

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('events/posters', 'public');
        }
        if ($request->hasFile('seat_plan')) {
            $validated['seat_plan'] = $request->file('seat_plan')->store('events/seatplans', 'public');
        }
        if ($request->hasFile('venue_map')) {
            $validated['venue_map'] = $request->file('venue_map')->store('events/maps', 'public');
        }
        if ($request->hasFile('terms_image')) {
            $validated['terms_image'] = $request->file('terms_image')->store('events/terms', 'public');
        }

        $validated['is_tenant_open'] = $request->has('is_tenant_open');
        $validated['tenant_booth_price'] = $request->input('tenant_booth_price', 0);
        if ($request->hasFile('booth_map')) {
            $validated['booth_map'] = $request->file('booth_map')->store('events/booth_maps', 'public');
        }
        $validated['spotify_playlist_id'] = $request->input('spotify_playlist_id'); 

        $event = Event::create($validated);

        if ($request->has('ticket_names')) {
            foreach ($request->ticket_names as $i => $name) {
                if (!$name) continue;
                $event->ticket_categories()->create([
                    'name' => $name,
                    'type' => $request->ticket_types[$i] ?? 'seating',
                    'price' => $request->ticket_prices[$i] ?? 0,
                    'quota' => $request->ticket_quotas[$i] ?? 0,
                    'benefits' => $request->ticket_benefits[$i] ?? null,
                    'sort_order' => $i
                ]);
            }
        }

        $event->update([
            'price' => $event->ticket_categories()->min('price') ?? 0,
            'capacity' => $event->ticket_categories()->sum('quota'),
            'available_tickets' => $event->ticket_categories()->sum('quota'),
        ]);

        if ($request->has('merch_names')) {
            foreach ($request->merch_names as $i => $name) {
                if (!$name) continue;
                $merchData = [
                    'name' => $name,
                    'price' => $request->merch_prices[$i] ?? 0,
                    'stock' => $request->merch_stocks[$i] ?? 0,
                    'sizes' => $request->merch_sizes[$i] ?? null,
                    'versions' => $request->merch_versions[$i] ?? null,
                    'sort_order' => $i
                ];
                if ($request->hasFile("merch_images.$i")) {
                    $merchData['image'] = $request->file("merch_images.$i")->store('merchandises', 'public');
                }
                $event->merchandises()->create($merchData);
            }
        }

        return redirect()->route('vendor.events.index')->with('success', 'Event berhasil dibuat!');
    }

    public function editEvent(Event $event)
    {
        if ($event->user_id !== auth()->id()) abort(403);
        return view('v_vendor.event.edit', compact('event'));
    }

    public function updateEvent(Request $request, Event $event)
    {
        if ($event->user_id !== auth()->id()) abort(403);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'venue' => 'required|string|max:255',
            'date' => 'required|date',
            'time' => 'required',
            'max_tickets_per_user' => 'required|integer|min:1',
            'category' => 'nullable|string',
            'artist' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:10240',
            'seat_plan' => 'nullable|image|max:10240',
            'venue_map' => 'nullable|image|max:10240',
            'terms_image' => 'nullable|image|max:10240',
            'gate_open_time' => 'nullable',
            'venue_location_url' => 'nullable|url',
            'open_sale_at' => 'nullable',
            'terms' => 'nullable|string',
            'tenant_quota' => 'nullable|integer|min:1',
            'map_notice' => 'nullable|string',
            'spotify_playlist_id' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('image')) {
            if ($event->image) Storage::disk('public')->delete($event->image);
            $validated['image'] = $request->file('image')->store('events/posters', 'public');
        }
        if ($request->hasFile('seat_plan')) {
            if ($event->seat_plan) Storage::disk('public')->delete($event->seat_plan);
            $validated['seat_plan'] = $request->file('seat_plan')->store('events/seatplans', 'public');
        }
        if ($request->hasFile('venue_map')) {
            if ($event->venue_map) Storage::disk('public')->delete($event->venue_map);
            $validated['venue_map'] = $request->file('venue_map')->store('events/maps', 'public');
        }
        if ($request->hasFile('terms_image')) {
            if ($event->terms_image) Storage::disk('public')->delete($event->terms_image);
            $validated['terms_image'] = $request->file('terms_image')->store('events/terms', 'public');
        }

        $validated['is_tenant_open'] = $request->has('is_tenant_open');
        $validated['tenant_booth_price'] = $request->input('tenant_booth_price', 0);
        if ($request->hasFile('booth_map')) {
            if ($event->booth_map) Storage::disk('public')->delete($event->booth_map);
            $validated['booth_map'] = $request->file('booth_map')->store('events/booth_maps', 'public');
        }

        $validated['spotify_playlist_id'] = $request->input('spotify_playlist_id');

        $event->update($validated);

        $keepTicketIds = [];
        if ($request->has('ticket_names')) {
            foreach ($request->ticket_names as $i => $name) {
                if (!$name) continue;
                $ticketId = $request->ticket_ids[$i] ?? null;
                $data = [
                    'name' => $name,
                    'type' => $request->ticket_types[$i] ?? 'seating',
                    'price' => $request->ticket_prices[$i] ?? 0,
                    'quota' => $request->ticket_quotas[$i] ?? 0,
                    'benefits' => $request->ticket_benefits[$i] ?? null,
                    'sort_order' => $i
                ];

                if ($ticketId) {
                    $tc = TicketCategory::find($ticketId);
                    if ($tc) {
                        $tc->update($data);
                        $keepTicketIds[] = $tc->id;
                    }
                } else {
                    $newTc = $event->ticket_categories()->create($data);
                    $keepTicketIds[] = $newTc->id;
                }
            }
        }
        $event->ticket_categories()->whereNotIn('id', $keepTicketIds)->delete();

        $event->update([
            'price' => $event->ticket_categories()->min('price') ?? 0,
            'capacity' => $event->ticket_categories()->sum('quota'),
            'available_tickets' => $event->ticket_categories()->sum('quota'),
        ]);

        $keepMerchIds = [];
        if ($request->has('merch_names')) {
            foreach ($request->merch_names as $i => $name) {
                if (!$name) continue;
                $merchId = $request->merch_ids[$i] ?? null;
                $data = [
                    'name' => $name,
                    'price' => $request->merch_prices[$i] ?? 0,
                    'stock' => $request->merch_stocks[$i] ?? 0,
                    'sizes' => $request->merch_sizes[$i] ?? null,
                    'versions' => $request->merch_versions[$i] ?? null,
                    'sort_order' => $i
                ];

                if ($request->hasFile("merch_images.$i")) {
                    $data['image'] = $request->file("merch_images.$i")->store('merchandises', 'public');
                }

                if ($merchId) {
                    $m = Merchandise::find($merchId);
                    if ($m) {
                        if (isset($data['image']) && $m->image) Storage::disk('public')->delete($m->image);
                        $m->update($data);
                        $keepMerchIds[] = $m->id;
                    }
                } else {
                    $newM = $event->merchandises()->create($data);
                    $keepMerchIds[] = $newM->id;
                }
            }
        }
        $event->merchandises()->whereNotIn('id', $keepMerchIds)->get()->each(function($m) {
            if ($m->image) Storage::disk('public')->delete($m->image);
            $m->delete();
        });

        return redirect()->route('vendor.events.index')->with('success', 'Event berhasil diperbarui!');
    }

    public function destroyEvent(Event $event)
    {
        if ($event->user_id !== auth()->id()) abort(403);

        $eventSudahSelesai = now()->gt($event->date);

        if (!$eventSudahSelesai && $event->bookings()->exists()) {
            return redirect()->route('vendor.events.index')
                ->with('error', 'Event tidak bisa dihapus karena sudah ada transaksi booking.');
        }

        if ($eventSudahSelesai) {
            $bookingIds = $event->bookings()->pluck('id');
            DB::table('booking_merchandise')->whereIn('booking_id', $bookingIds)->delete();
            DB::table('tickets')->whereIn('booking_id', $bookingIds)->delete();
            $event->bookings()->delete();
            $event->ticket_categories()->delete();
            $event->merchandises()->each(function($m) {
                if ($m->image) Storage::disk('public')->delete($m->image);
                $m->delete();
            });
        }

        if ($event->image) Storage::disk('public')->delete($event->image);
        if ($event->seat_plan) Storage::disk('public')->delete($event->seat_plan);

        $event->delete();

        return redirect()->route('vendor.events.index')
            ->with('success', 'Event berhasil dihapus!');
    }

    public function toggleEventStatus(Event $event)
    {
        if ($event->user_id !== auth()->id()) abort(403);

        $eventSudahSelesai = now()->gt($event->date);

        if ($eventSudahSelesai) {
            return back()->with('error', 'Event yang sudah berakhir tidak bisa diubah statusnya.');
        }

        $event->status = $event->status === 'active' ? 'inactive' : 'active';
        $event->save();

        return back()->with('success', 'Status event berhasil diubah!');
    }

    public function indexBooking(Request $request)
    {
        $vendorId = auth()->id();
        $perPage = $request->input('per_page', 10);
        $tab = $request->input('tab', 'penjualan');
        $eventId = $request->input('event_id');

        $baseQuery = Booking::whereHas('event', function ($q) use ($vendorId) {
            $q->where('user_id', $vendorId);
        });

        if ($eventId) {
            $baseQuery->where('event_id', $eventId);
        }

        $totalPendapatan = (clone $baseQuery)->where('payment_status', 'paid')->sum('total_price');
        $tiketTerjual = (clone $baseQuery)->where('payment_status', 'paid')->sum('quantity');
        
        // Hitung merchandise terjual (Jika ada filter event_id, kita tambahkan juga filternya)
        $merchandiseQuery = \Illuminate\Support\Facades\DB::table('booking_merchandise')
            ->join('bookings', 'booking_merchandise.booking_id', '=', 'bookings.id')
            ->join('events', 'bookings.event_id', '=', 'events.id')
            ->where('events.user_id', $vendorId)
            ->where('bookings.payment_status', 'paid');
            
        if ($eventId) {
            $merchandiseQuery->where('bookings.event_id', $eventId);
        }
        
        $merchandiseTerjual = $merchandiseQuery->sum('booking_merchandise.quantity');

        $menunggu = (clone $baseQuery)->where('payment_status', 'pending')->count();
        $batal = (clone $baseQuery)->whereIn('payment_status', ['cancelled', 'expire', 'failed'])->count();

        $bookingsQuery = (clone $baseQuery)->with(['event', 'user','merchandises'])->latest();
        
        if ($tab === 'penjualan') {
            $bookingsQuery->where('payment_status', 'paid');
        } elseif ($tab === 'menunggu') {
            $bookingsQuery->where('payment_status', 'pending');
        } elseif ($tab === 'batal') {
            $bookingsQuery->whereIn('payment_status', ['cancelled', 'expire', 'failed']);
        }

        $bookings = $bookingsQuery->paginate($perPage)->appends($request->query());
        $vendorEvents = Event::where('user_id', $vendorId)->select('id', 'title')->get();

        // ── PERBAIKAN: Tambahkan 'merchandiseTerjual' di dalam compact() ──
        return view('v_vendor.booking.index', compact(
            'bookings', 
            'perPage', 
            'tab', 
            'eventId', 
            'vendorEvents', 
            'totalPendapatan', 
            'tiketTerjual', 
            'merchandiseTerjual', // <-- disisipkan di sini
            'menunggu', 
            'batal'
        ));
    }

    public function exportBookings(Request $request)
    {
        $vendorId = auth()->id();
        $tab = $request->input('tab', 'penjualan');
        $eventId = $request->input('event_id');

        // Ambil query dasar dengan relasi lengkap
        $query = Booking::whereHas('event', function($q) use ($vendorId) {
            $q->where('user_id', $vendorId);
            })
            ->with(['event', 'user', 'merchandises']);

        // Filter berdasarkan Event jika dipilih
        if ($eventId) {
            $query->where('event_id', $eventId);
        }

        // Filter berdasarkan Tab Status Pembayaran
        if ($tab === 'penjualan') {
            $query->where('payment_status', 'paid');
        } elseif ($tab === 'menunggu') {
            $query->where('payment_status', 'pending');
        } elseif ($tab === 'batal') {
            $query->whereIn('payment_status', ['cancelled', 'expire', 'failed']);
        }

        // Ambil data transaksi urutan terbaru
        $bookings = $query->latest()->get();

        // Hitung ringkasan total pendapatan dari data yang difilter
        $totalPendapatan = $bookings->where('payment_status', 'paid')->sum('total_price');
        $totalTiket = $bookings->where('payment_status', 'paid')->sum('quantity');

        // Kirimkan data ke view
        $pdf = Pdf::loadView('v_vendor.booking.bookings-pdf', compact('bookings', 'tab', 'totalPendapatan', 'totalTiket'))
            ->setPaper('a4', 'landscape');

        $filename = 'Daftar-Transaksi-' . ucfirst($tab) . '-' . now()->format('Ymd') . '.pdf';

        return $pdf->download($filename);
    }

    public function laporan()
    {
        $vendorId = auth()->id();
        $eventStats = Event::where('user_id', $vendorId)
            ->withSum(['bookings as tickets_sold' => function($q) {
                $q->where('payment_status', 'paid');
            }], 'quantity')
            ->withSum(['bookings as ticket_revenue' => function($q) {
                $q->where('payment_status', 'paid');
            }], 'total_price')
            ->with(['tenants' => function($q) {
                $q->where('payment_status', 'paid');
            }])
            ->get()
            ->map(function($event) {
                $netTicketRevenue = ($event->ticket_revenue ?? 0) / 1.13;
                $boothRevenue = $event->tenants->sum(fn($t) => $event->tenant_booth_price ?? 0);
                
                $event->paid_bookings_count = $event->tickets_sold ?? 0;
                $event->total_revenue = $netTicketRevenue + $boothRevenue;
                return $event;
            });

        $totalTiketTerjual = $eventStats->sum('paid_bookings_count');
        $totalPendapatan = $eventStats->sum('total_revenue');
        $totalEventAktif = $eventStats->count();

        return view('v_vendor.laporan', compact('eventStats', 'totalTiketTerjual', 'totalPendapatan', 'totalEventAktif'));
    }

    public function laporanDetail($id)
    {
        $vendorId = auth()->id();
        $event = Event::where('user_id', $vendorId)
            ->with(['ticket_categories', 'tenants' => fn($q) => $q->where('payment_status', 'paid')])
            ->findOrFail($id);

        $paidBookings = Booking::where('event_id', $id)
            ->where('payment_status', 'paid')
            ->with(['user', 'ticket_category','merchandises'])
            ->get();

        $ticketsSold = $paidBookings->sum('quantity');
        $remainingQuota = $event->ticket_categories->sum('quota');
        $totalCapacity = $ticketsSold + $remainingQuota;
        $fillRate = $totalCapacity > 0 ? round(($ticketsSold / $totalCapacity) * 100, 1) : 0;

        $ticketGross = $paidBookings->sum('total_price') / 1.13;
        $boothGross = $event->tenants->sum(fn($t) => $event->tenant_booth_price ?? 0);
        
        $netTicketRevenue = $ticketGross;
        $netBoothRevenue = $boothGross;
        $totalRevenue = $netTicketRevenue + $netBoothRevenue;

        $platformFee = ($ticketGross * 0.13) + ($boothGross * 0.03);

        $ticketBreakdown = [];
        foreach ($event->ticket_categories as $category) {
            $catBookings = $paidBookings->where('ticket_category_id', $category->id);
            $sold = $catBookings->sum('quantity');
            $revenue = ($catBookings->sum('total_price') / 1.13); 
            $ticketBreakdown[] = [
                'name' => $category->name,
                'price' => $category->price,
                'sold' => $sold,
                'revenue' => $revenue,
                'slot' => $sold + $category->quota
            ];
        }

        $dailySales = $paidBookings->groupBy(function($b) {
            return $b->created_at->format('d M');
        })->map->sum('quantity');

        $salesChart = [];
        $startDate = now()->subDays(6);
        for ($i = 0; $i < 7; $i++) {
            $dateKey = $startDate->copy()->addDays($i)->format('d M');
            $salesChart[$dateKey] = $dailySales->get($dateKey, 0);
        }

        $perPage = request('per_page', 10);
        $search = request('search');

        $buyersQuery = Booking::where('event_id', $id)->with(['user', 'ticket_category']);
        
        if ($search) {
            $buyersQuery->where(function($q) use ($search) {
                $q->whereHas('user', function($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhere('guest_name', 'like', "%{$search}%")
                ->orWhere('guest_email', 'like', "%{$search}%")
                ->orWhere('booking_code', 'like', "%{$search}%");
            });
        }

        $buyers = $buyersQuery->latest()->paginate($perPage)->appends(request()->query());


        //  logic $merchandiseBreakdown
        $merchandiseBreakdown = [];
        foreach ($paidBookings as $booking) {
            foreach ($booking->merchandises as $merch) {
                $key = $merch->id;
                if (!isset($merchandiseBreakdown[$key])) {
                    $merchandiseBreakdown[$key] = [
                        'name'    => $merch->name,
                        'qty'     => 0,
                        'revenue' => 0,
                    ];
                }
                $merchandiseBreakdown[$key]['qty']     += $merch->pivot->quantity;
                $merchandiseBreakdown[$key]['revenue'] += $merch->pivot->price * $merch->pivot->quantity;
            }
        }
        $merchandiseBreakdown = array_values($merchandiseBreakdown);

        //  buyers query
        $buyersQuery = Booking::where('event_id', $id)
            ->with(['user', 'ticket_category', 'merchandises']);

        return view('v_vendor.laporan-detail', compact(
            'event', 
            'ticketsSold', 
            'totalCapacity', 
            'fillRate', 
            'totalRevenue', 
            'platformFee', 
            'ticketBreakdown', 
            'salesChart', 
            'buyers',
            'merchandiseBreakdown'

        ));
    }
    public function exportLaporanDetail($id)
{
    $vendorId = auth()->id();
 
    $event = Event::where('user_id', $vendorId)
        ->with(['ticket_categories', 'tenants' => fn($q) => $q->where('payment_status', 'paid')])
        ->findOrFail($id);
 
    $allBuyers = Booking::where('event_id', $id)
        ->with(['user', 'ticket_category', 'merchandises'])
        ->latest()
        ->get();
 
    $paidBookings = $allBuyers->where('payment_status', 'paid');
 
    // Breakdown tiket
    $ticketBreakdown = [];
    foreach ($event->ticket_categories as $category) {
        $catBookings = $paidBookings->where('ticket_category_id', $category->id);
        $sold    = $catBookings->sum('quantity');
        $revenue = $catBookings->sum('total_price') / 1.13;
        $ticketBreakdown[] = [
            'name'    => $category->name,
            'price'   => $category->price,
            'sold'    => $sold,
            'revenue' => $revenue,
            'slot'    => $sold + $category->quota,
        ];
    }
    // Breakdown merchandise
    $merchandiseBreakdown = [];
    foreach ($paidBookings as $booking) {
        foreach ($booking->merchandises as $merch) {
            $key = $merch->id;
            if (!isset($merchandiseBreakdown[$key])) {
                $merchandiseBreakdown[$key] = [
                    'name'    => $merch->name,
                    'qty'     => 0,
                    'revenue' => 0,
                ];
            }
            $merchandiseBreakdown[$key]['qty']     += $merch->pivot->quantity;
            $merchandiseBreakdown[$key]['revenue'] += $merch->pivot->price * $merch->pivot->quantity;
        }
    }
    $merchandiseBreakdown = array_values($merchandiseBreakdown);
 
    $pdf = Pdf::loadView('v_vendor.laporan-detail-pdf', compact(
        'event',
        'allBuyers',
        'ticketBreakdown',
        'merchandiseBreakdown',
    ))->setPaper('a4', 'portrait');
 
    $filename = 'Laporan-Detail-' . \Illuminate\Support\Str::slug($event->title) . '-' . now()->format('Ymd') . '.pdf';
 
    return $pdf->download($filename);
}

    public function exportLaporan()
    {
        $vendorId = auth()->id();
        $eventStats = Event::where('user_id', $vendorId)
            ->withSum(['bookings as tickets_sold' => function($q) {
                $q->where('payment_status', 'paid');
            }], 'quantity')
            ->withSum(['bookings as ticket_revenue' => function($q) {
                $q->where('payment_status', 'paid');
            }], 'total_price')
            ->with(['tenants' => function($q) {
                $q->where('payment_status', 'paid');
            }])
            ->get()
            ->map(function($event) {
                $netTicketRevenue = ($event->ticket_revenue ?? 0) / 1.13;
                $boothRevenue = $event->tenants->sum(fn($t) => $event->tenant_booth_price ?? 0);
                $event->paid_bookings_count = $event->tickets_sold ?? 0;
                $event->total_revenue = $netTicketRevenue + $boothRevenue;
                return $event;
            });

        $pdf = Pdf::loadView('v_vendor.laporan-pdf', compact('eventStats'));
        return $pdf->download('Laporan-Penjualan-FiveFest.pdf');
    }


    public function exportRekeningEvent($id)
    {
        $vendorId = auth()->id();

        // pastikan event milik vendor login
        $event = Event::where('user_id', $vendorId)
            ->with([
                'ticket_categories',
                'tenants' => fn($q) => $q->where('payment_status','paid'),
            ])
            ->findOrFail($id);

            // semua booking (semua status) untuk daftar pembeli lengkap
            $allBuyers = Booking::where('event_id', $id)
                ->with(['user','ticket_category'])
                ->latest()
                ->get();

                // hanya yang paid untuk kalkulasi revenue 
                $paidBookings = $allBuyers->where('payment_status','paid');

                // tiket breakdown per kategri
                $ticketBreakdown = [];
                foreach ( $event->ticket_categories as $category) {
                    $catBookings = $paidBookings->where('ticket_category_id', $category->id);
                    $sold = $catBookings->sum('quantity');
                    $revenue = $catBookings->sum('total_price') / 1.13;

                    $ticketBreakdown[] = [
                        'name' => $category->name,
                        'price' => $category->price,
                        'sold' => $sold,
                        'revenue' => $revenue,
                        'slot' => $sold + $category->quota,
                    ];
                }

                // sumary number

                $ticketsSold = $paidBookings->sum('quantity');
                $remainingQuota = $event->ticket_categories->sum('quota');
                $totalCapacity = $ticketsSold + $remainingQuota;
                $fillRate = $totalCapacity > 0 ? round(($ticketsSold / $totalCapacity) *100,1) : 0;
                $netTicketRevenue = $paidBookings->sum('total_price') / 1.13;
                $netBoothRevenue  = $event->tenants->sum(fn($t) => $event->tenant_booth_price ?? 0);
                $totalRevenue     = $netTicketRevenue + $netBoothRevenue;

                $pdf = Pdf::loadView('v_vendor.rekening-event-pdf', compact(
                'event',
                'allBuyers',
                'ticketBreakdown',
                'ticketsSold',
                'totalCapacity',
                'fillRate',
                'netTicketRevenue',
                'netBoothRevenue',
                'totalRevenue',
            ))->setPaper('a4', 'portrait');
        
            $filename = 'Laporan-Event-' . Str::slug($event->title) . '-' . now()->format('Ymd') . '.pdf';
        
            return $pdf->download($filename);
    }
    // Hitung pendapatan bersih satu event (dipakai bareng oleh rekening() & storeWithdrawal())
    private function calculateEventNetRevenue(Event $event): array
    {
        $paidBookings = Booking::where('event_id', $event->id)
            ->where('payment_status', 'paid')
            ->with(['ticket_category', 'merchandises'])
            ->get();

        $ticketNet = 0;
        $ticketsSold = 0;
        foreach ($paidBookings as $booking) {
            $ticketTotal = $booking->quantity * ($booking->ticket_category->price ?? 0);
            $merchTotal = 0;
            foreach ($booking->merchandises as $merch) {
                $merchTotal += ($merch->pivot->price * $merch->pivot->quantity);
            }
            $ticketNet   += ($ticketTotal + $merchTotal);
            $ticketsSold += $booking->quantity;
        }

        $paidBooths = \App\Models\EventTenant::where('event_id', $event->id)
            ->where('payment_status', 'paid')
            ->get();

        $boothNet   = 0;
        $boothsSold = 0;
        foreach ($paidBooths as $booth) {
            $boothNet += ($event->tenant_booth_price ?? 0);
            $boothsSold++;
        }

        return [
            'tickets_sold' => $ticketsSold,
            'ticket_net'   => $ticketNet,
            'booths_sold'  => $boothsSold,
            'booth_net'    => $boothNet,
            'total_net'    => $ticketNet + $boothNet,
        ];
    }
    /**
     * Cek fase penarikan dana berdasarkan tanggal event:
     * - H-14 s/d H-9  -> tahap 1, maks 70%
     * - H+1 dst tanpa batas akhir -> tahap 2, maks 100% (sengaja ga ditutup biar dana ga nyangkut kalau vendor lupa tarik)
     */
    private function getWithdrawalPhase($eventDate): array
    {
        $today = now()->startOfDay();
        $eventDate = \Carbon\Carbon::parse($eventDate)->startOfDay();

        $window1Open  = $eventDate->copy()->subDays(14); // H-14
        $window1Close = $eventDate->copy()->subDays(9);  // H-9
        $window2Open  = $eventDate->copy()->addDays(1);  // H+1, dan seterusnya

        // Tahap 1
        if ($today->between($window1Open, $window1Close)) {
            return [
                'status'       => 'window1',
                'cap_percent'  => 70,
                'can_withdraw' => true,
                'message'      => 'Tahap 1 aktif: maksimal 70% dari pendapatan bersih event ini. Jendela tutup ' . $window1Close->translatedFormat('d M Y') . '.',
            ];
        }

        // Tahap 2: dibuka sejak H+1, tanpa batas akhir
        if ($today->greaterThanOrEqualTo($window2Open)) {
            return [
                'status'       => 'window2',
                'cap_percent'  => 100,
                'can_withdraw' => true,
                'message'      => 'Tahap 2 aktif: sisa dana (hingga 100%) bisa ditarik kapan saja sejak ' . $window2Open->translatedFormat('d M Y') . ', tidak ada batas akhir.',
            ];
        }

        // Belum masuk H-14
        if ($today->lt($window1Open)) {
            return [
                'status'       => 'too_early',
                'cap_percent'  => 0,
                'can_withdraw' => false,
                'message'      => 'Penarikan dibuka mulai ' . $window1Open->translatedFormat('d M Y') . ' (H-14 sebelum acara).',
            ];
        }

        // Antara H-8 s/d hari H (sebelum H+1)
        return [
            'status'       => 'locked_between',
            'cap_percent'  => 0,
            'can_withdraw' => false,
            'message'      => 'Jendela tahap 1 sudah ditutup. Tahap 2 dibuka ' . $window2Open->translatedFormat('d M Y') . ' (H+1 setelah acara).',
        ];
    }

    public function penggunaTiket()
    {
        $vendorId = auth()->id();
        $perPage  = request('per_page', 10);
        $search   = request('search');
        $eventId  = request('event_id');
        $status   = request('status');

        // Ambil event vendor untuk dropdown
        $vendorEvents = \App\Models\Event::where('user_id', $vendorId)
            ->orderBy('date', 'desc')->get();

        $ticketsQuery = Booking::whereHas('event', fn($q) => $q->where('user_id', $vendorId))
            ->where('booking_status', 'confirmed')
            ->with(['user', 'event', 'ticket_category', 'tickets']);

        if ($eventId) {
            $ticketsQuery->where('event_id', $eventId);
        }

        if ($search) {
            $ticketsQuery->where(function($q) use ($search) {
                $q->whereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%"))
                ->orWhere('guest_name', 'like', "%{$search}%")
                ->orWhere('booking_code', 'like', "%{$search}%");
            });
        }

        if ($status === 'scanned') {
            $ticketsQuery->whereHas('tickets', fn($q) => $q->where('status', 'scanned'));
        } elseif ($status === 'belum') {
            $ticketsQuery->whereHas('tickets', fn($q) => $q->where('status', '!=', 'scanned'));
        }

        $tickets = $ticketsQuery->latest()->paginate($perPage)->appends(request()->query());

        // Stats global
        $totalTickets = Booking::whereHas('event', fn($q) => $q->where('user_id', $vendorId))
            ->where('booking_status', 'confirmed')
            ->sum('quantity');

        $totalScanned = \App\Models\Ticket::whereHas('booking', fn($q) =>
            $q->whereHas('event', fn($q2) => $q2->where('user_id', $vendorId))
            ->where('booking_status', 'confirmed'))
            ->where('status', 'scanned')->count();

        $totalBelum = $totalTickets - $totalScanned;
        $pctScanned = $totalTickets > 0 ? round(($totalScanned / $totalTickets) * 100) : 0;

        // Stats per event (untuk card breakdown)
        $eventStats = \App\Models\Event::where('user_id', $vendorId)
            ->withSum(['bookings as total_qty' => function ($q) {
                $q->where('booking_status', 'confirmed');
            }], 'quantity')
            ->get()
            ->map(function($event) {
                $scanned = \App\Models\Ticket::whereHas('booking', function ($q) use ($event) {
                    $q->where('event_id', $event->id)
                        ->where('booking_status', 'confirmed');
                })
                    ->where('status', 'scanned')->count();
                $total   = (int)($event->total_qty ?? 0);
                $event->scanned   = $scanned;
                $event->belum     = max(0, $total - $scanned);
                $event->total_qty = $total;
                $event->pct       = $total > 0 ? round(($scanned / $total) * 100) : 0;
                return $event;
            })->filter(fn($e) => $e->total_qty > 0);

        return view('v_vendor.pengguna-tiket', compact(
            'tickets', 'totalTickets', 'totalScanned', 'totalBelum', 'pctScanned',
            'vendorEvents', 'eventStats'
        ));
    }

    public function penggunaTiketDetail($id)
    {
        $vendorId = auth()->id();
        $ticket = Booking::whereHas('event', fn($q) => $q->where('user_id', $vendorId))
                    ->with(['user', 'event', 'ticket_category','tickets','merchandises'])
                    ->findOrFail($id);

        return view('v_vendor.pengguna-tiket-detail', compact('ticket'));
    }

    public function tenants(Request $request)
    {
        $vendorId = auth()->id();
        $query = EventTenant::whereHas('event', function($q) use ($vendorId) {
            $q->where('user_id', $vendorId);
        })->with(['tenant.tenantProfile', 'event']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        $tenants = $query->latest()->paginate(10)->appends($request->all());
        $vendorEvents = Event::where('user_id', $vendorId)->get();

        $baseTenantQuery = EventTenant::whereHas('event', function($q) use ($vendorId) {
            $q->where('user_id', $vendorId);
        });
        
        $totalTenants    = (clone $baseTenantQuery)->count();
        $pendingTenants  = (clone $baseTenantQuery)->where('status', 'pending')->count();
        $verifiedTenants = (clone $baseTenantQuery)->where('status', 'approved')->count();
        $rejectedTenants = (clone $baseTenantQuery)->where('status', 'rejected')->count();

        return view('v_vendor.tenant.index', compact(
            'tenants', 'vendorEvents', 
            'totalTenants', 'pendingTenants', 'verifiedTenants', 'rejectedTenants'
        ));
    }

    public function verifyTenant(Request $request, EventTenant $eventTenant)
    {
        if ($eventTenant->event->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
        ]);

        $status = $request->action === 'approve' ? 'approved' : 'rejected';
        
        $eventTenant->update(['status' => $status]);

        return back()->with('success', 'Status pengajuan tenant berhasil diperbarui menjadi: ' . ucfirst($status));
    }

    public function refundTenant(Request $request, EventTenant $eventTenant)
    {
        if ($eventTenant->event->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
            'refund_reject_reason' => 'required_if:action,reject|nullable|string|max:500',
        ], [
            'refund_reject_reason.required_if' => 'Alasan penolakan wajib diisi.',
        ]);

        if ($eventTenant->refund_status !== 'requested') {
            return back()->with('error', 'Status refund tidak valid.');
        }

        if ($request->action === 'approve') {
            $eventTenant->update([
                'refund_status'      => 'approved',
                'refund_approved_at' => now(),
            ]);
            return back()->with('success', 'Refund disetujui! Menunggu proses oleh admin FiveFest.');
        } else {
            $eventTenant->update([
                'refund_status'        => 'rejected',
                'payment_status'       => 'paid',
                'refund_reject_reason' => $request->refund_reject_reason,
            ]);
            return back()->with('success', 'Refund ditolak.');
        }
    }

    public function informasiDasar() { return view('v_vendor.informasi-dasar'); }
    public function informasiLegal() { return view('v_vendor.informasi-legal'); }
    
    public function rekening()
    {
        $vendorId = auth()->id();
        $events = Event::where('user_id', $vendorId)->get();
        $eventIds = $events->pluck('id');
        
        $paidBookings = Booking::whereIn('event_id', $eventIds)
            ->where('payment_status', 'paid')
            ->with(['ticket_category', 'merchandises'])
            ->get();
            
        $ticketGrossPaid  = 0;
        $ticketBase       = 0;
        $ticketTax        = 0;
        $ticketServiceFee = 0;
        $netTicketRevenue = 0;
        
        foreach ($paidBookings as $booking) {
            $ticketTotal = $booking->quantity * ($booking->ticket_category->price ?? 0);
            
            $merchTotal = 0;
            foreach ($booking->merchandises as $merch) {
                $merchTotal += ($merch->pivot->price * $merch->pivot->quantity);
            }

            $basePrice = $ticketTotal + $merchTotal;
            $tax = (int) round($basePrice * 0.10);
            $serviceFee = (int) round($basePrice * 0.03);
            
            $ticketGrossPaid  += $booking->total_price;
            $ticketTax        += $tax;
            $ticketServiceFee += $serviceFee;
            $netTicketRevenue += $basePrice;
        }
        $ticketBase = $netTicketRevenue;
        
        $paidBooths = \App\Models\EventTenant::whereIn('event_id', $eventIds)
            ->where('payment_status', 'paid')
            ->with('event')
            ->get();
            
        $boothGrossPaid        = 0;
        $boothTax              = 0;
        $boothTenantServiceFee = 0;
        $netBoothRevenue       = 0;
        
        foreach ($paidBooths as $booth) {
            $boothPrice       = $booth->event->tenant_booth_price ?? 0;
            $tenantServiceFee = (int) round($boothPrice * 0.03);
            
            $boothGrossPaid        += ($boothPrice + $tenantServiceFee);
            $boothTax              += 0;
            $boothTenantServiceFee += $tenantServiceFee;
            $netBoothRevenue       += $boothPrice;
        }

        // === BARU: ambil semua withdrawal vendor sekaligus, dikelompokkan per event_id ===
        $withdrawalsByEvent = VendorWithdrawal::where('vendor_id', $vendorId)
            ->whereIn('event_id', $eventIds)
            ->get()
            ->groupBy('event_id');
        
        $eventBreakdowns = [];
        foreach ($events as $event) {
            $eventBookings = Booking::where('event_id', $event->id)
                ->where('payment_status', 'paid')
                ->with(['ticket_category', 'merchandises'])
                ->get();
                
            $eventTicketGross  = 0;
            $eventTicketNet    = 0;
            $eventTicketsSold  = 0;
            
            foreach ($eventBookings as $booking) {
                $ticketTotal = $booking->quantity * ($booking->ticket_category->price ?? 0);
                $merchTotal = 0;
                foreach ($booking->merchandises as $merch) {
                    $merchTotal += ($merch->pivot->price * $merch->pivot->quantity);
                }
                $basePrice = $ticketTotal + $merchTotal;
                $eventTicketGross += $booking->total_price;
                $eventTicketNet   += $basePrice;
                $eventTicketsSold += $booking->quantity;
            }
            
            $eventBooths = \App\Models\EventTenant::where('event_id', $event->id)
                ->where('payment_status', 'paid')
                ->get();
                
            $eventBoothGross  = 0;
            $eventBoothNet    = 0;
            $eventBoothsSold  = 0;
            
            foreach ($eventBooths as $booth) {
                $boothPrice       = $event->tenant_booth_price ?? 0;
                $tenantServiceFee = (int) round($boothPrice * 0.03);
                
                $eventBoothGross += ($boothPrice + $tenantServiceFee);
                $eventBoothNet   += $boothPrice;
                $eventBoothsSold += 1;
            }

            $eventTotalNet = $eventTicketNet + $eventBoothNet;

            // === BARU: fase & sisa kuota penarikan dana untuk event ini ===
            $phase        = $this->getWithdrawalPhase($event->date);
            $eventWd      = $withdrawalsByEvent->get($event->id, collect());
            $wdApproved   = $eventWd->where('status', 'approved')->sum('amount');
            $wdPending    = $eventWd->where('status', 'pending')->sum('amount');
            $alreadyReq   = $wdApproved + $wdPending;

            $capAmount   = $phase['cap_percent'] > 0
                ? min(floor($eventTotalNet * $phase['cap_percent'] / 100), $eventTotalNet)
                : 0;
            $availableWd = max(0, $capAmount - $alreadyReq);
            $canWithdraw = $phase['can_withdraw'] && $availableWd >= 10000;
            
            $eventBreakdowns[] = [
                'event'        => $event,
                'tickets_sold' => $eventTicketsSold,
                'ticket_gross' => $eventTicketGross,
                'ticket_net'   => $eventTicketNet,
                'booths_sold'  => $eventBoothsSold,
                'booth_gross'  => $eventBoothGross,
                'booth_net'    => $eventBoothNet,
                'total_net'    => $eventTotalNet,
                'wd_phase'        => $phase,
                'wd_already'      => $alreadyReq,
                'wd_available'    => $availableWd,
                'wd_can_withdraw' => $canWithdraw,
            ];
        }
        
        $hasOpenTenant     = $events->contains('is_tenant_open', 1) || $events->contains('is_tenant_open', true);
        $totalGrossRevenue = $ticketGrossPaid + $boothGrossPaid;
        $totalTax          = $ticketTax + $boothTax;
        $totalServiceFee   = $ticketServiceFee + $boothTenantServiceFee;
        $totalNetRevenue   = $netTicketRevenue + $netBoothRevenue;
        
        $withdrawals    = VendorWithdrawal::where('vendor_id', $vendorId)->with('event')->latest()->get();
        $totalWithdrawn = $withdrawals->where('status', 'approved')->sum('amount');
        $totalPending   = $withdrawals->where('status', 'pending')->sum('amount');
        
        $availableBalance = $totalNetRevenue - $totalWithdrawn - $totalPending;
        if ($availableBalance < 0) {
            $availableBalance = 0;
        }

        return view('v_vendor.rekening', compact(
            'ticketGrossPaid', 
            'ticketBase', 
            'ticketTax', 
            'ticketServiceFee', 
            'netTicketRevenue',
            'boothGrossPaid', 
            'boothTax', 
            'boothTenantServiceFee', 
            'netBoothRevenue', 
            'hasOpenTenant',
            'totalGrossRevenue', 
            'totalTax', 
            'totalServiceFee', 
            'totalNetRevenue',
            'withdrawals', 
            'totalWithdrawn', 
            'totalPending', 
            'availableBalance',
            'eventBreakdowns'
        ));
    }

    public function storeWithdrawal(Request $request)
    {
        $vendorId = auth()->id();

        $request->validate([
            'event_id'       => 'required|exists:events,id',
            'bank_name'      => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'account_holder' => 'required|string|max:150',
            'amount'         => 'required|integer|min:10000',
        ], [
            'event_id.required'       => 'Event tujuan penarikan wajib dipilih.',
            'bank_name.required'      => 'Nama Bank wajib diisi.',
            'account_number.required' => 'Nomor rekening wajib diisi.',
            'account_holder.required' => 'Nama pemilik rekening wajib diisi.',
            'amount.required'         => 'Jumlah penarikan wajib diisi.',
            'amount.integer'          => 'Jumlah penarikan harus berupa angka.',
            'amount.min'              => 'Minimal penarikan adalah Rp 100.000.',
        ]);

        // pastikan event ini benar milik vendor yang login
        $event = Event::where('id', $request->event_id)
            ->where('user_id', $vendorId)
            ->firstOrFail();

        // validasi fase waktu di server (jangan andalkan disabled button di frontend doang)
        $phase = $this->getWithdrawalPhase($event->date);
        if (!$phase['can_withdraw']) {
            return back()->with('error', 'Penarikan untuk event "' . $event->title . '" tidak bisa dilakukan saat ini. ' . $phase['message']);
        }

        $netRevenue = $this->calculateEventNetRevenue($event);

        $alreadyReq = VendorWithdrawal::where('vendor_id', $vendorId)
            ->where('event_id', $event->id)
            ->whereIn('status', ['approved', 'pending'])
            ->sum('amount');

        $capAmount   = min(floor($netRevenue['total_net'] * $phase['cap_percent'] / 100), $netRevenue['total_net']);
        $availableWd = max(0, $capAmount - $alreadyReq);

        if ($request->amount > $availableWd) {
            return back()->with('error', 'Maksimal penarikan untuk event "' . $event->title . '" saat ini adalah Rp '
                . number_format($availableWd, 0, ',', '.') . '. ' . $phase['message']);
        }

        VendorWithdrawal::create([
            'vendor_id'      => $vendorId,
            'event_id'       => $event->id,
            'amount'         => $request->amount,
            'bank_name'      => $request->bank_name,
            'account_number' => $request->account_number,
            'account_holder' => $request->account_holder,
            'status'         => 'pending',
        ]);

        return back()->with('success', 'Pengajuan penarikan dana untuk event "' . $event->title . '" berhasil dikirim! Silakan tunggu konfirmasi admin.');
    }

    public function merchandiseCollection(Request $request)
    {
        $vendorId = Auth::id();

        $perPage = $request->input('per_page', 10);

        $bookings = \App\Models\Booking::with(['user', 'event', 'merchandises'])
            ->whereHas('event', fn($q) => $q->where('user_id', $vendorId))
            ->whereHas('merchandises')
            ->when($request->event_id, fn($q) => $q->where('event_id', $request->event_id))
            ->when($request->status, function($q) use ($request) {
                if ($request->status === 'collected') {
                    $q->whereHas('merchandises', fn($q2) =>
                        $q2->where('booking_merchandise.is_collected', true));
                } elseif ($request->status === 'pending') {
                    $q->whereHas('merchandises', fn($q2) =>
                        $q2->where('booking_merchandise.is_collected', false));
                }
            })
            ->latest()
            ->paginate($perPage);

        $events = \App\Models\Event::where('user_id', $vendorId)->get();
        $allBookings = \App\Models\Booking::with('merchandises')
            ->whereHas('event', fn($q) => $q->where('user_id', $vendorId))
            ->whereHas('merchandises')
            ->get();

        return view('v_vendor.merchandise-collection', compact('bookings', 'events', 'allBookings'));
    }
}