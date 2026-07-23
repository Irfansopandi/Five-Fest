<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingConfirmationMail;
use Illuminate\Support\Facades\Log;  

class BookingController extends Controller
{
    // show booking form
    public function create($eventId)
    {
        $event = Event::with(['ticket_categories', 'merchandises'])->findOrFail($eventId);
        if ($event->open_sale_at && \Carbon\Carbon::now()->lt(\Carbon\Carbon::parse($event->open_sale_at))) {
            return redirect()->route('event.detail', $eventId)->with('error', 'Penjualan tiket belum dibuka.');
        }
        return view('booking.create', compact('event'));
    }

    // proses booking
    public function store(Request $request, $eventId)
    {
        $request->validate([
            'ticket_category_id' => 'required|exists:ticket_categories,id',
            'identity_number' => 'required|string',
            'birth_date' => 'required|date',
            'gender' => 'required|in:L,P',
            'phone' => 'required|string',
            'quantity' => 'required|integer|min:1|max:4',
        ]);

        $event = Event::findOrFail($eventId);
        if ($event->open_sale_at && \Carbon\Carbon::now()->lt(\Carbon\Carbon::parse($event->open_sale_at))) {
            return redirect()->route('event.detail', $eventId)->with('error', 'Penjualan tiket belum dibuka.');
        }
        $ticketCategory = \App\Models\TicketCategory::findOrFail($request->ticket_category_id);
        
        $quantity = (int) $request->quantity;
        
        // Cek Kuota
        if ($ticketCategory->quota < $quantity) {
            return back()->with('error', 'Maaf, kuota tiket tidak mencukupi. Sisa tiket: ' . $ticketCategory->quota);
        }
        
            // calculate subtotal
            $subtotal = $ticketCategory->price * $quantity;
            
            // Merch Logic
            $merchTotal = 0;
            $merchItems = [];
            $selectedMerch = []; // For pivot save later
            if ($request->has('merch_ids')) {
                $merchandises = \App\Models\Merchandise::whereIn('id', $request->merch_ids)->get();
                foreach ($merchandises as $m) {
                    $mQty = (int) ($request->merch_quantities[$m->id] ?? 0);
                    $mSize = $request->merch_sizes[$m->id] ?? null;
                    if ($mQty > 0) {
                        if ($m->stock < $mQty) {
                            return back()->with('error', 'Maaf, stok merchandise ' . $m->name . ' tidak mencukupi. Sisa stok: ' . $m->stock);
                        }
                        $merchTotal += $m->price * $mQty;
                        $displayName = $m->name . ($mSize ? ' [' . $mSize . ']' : '');
                        $merchItems[] = [
                            'id' => 'MERCH-' . $m->id,
                            'price' => (int)$m->price,
                            'quantity' => $mQty,
                            'name' => $displayName,
                        ];
                        $selectedMerch[] = [
                            'id' => $m->id, 
                            'price' => $m->price, 
                            'quantity' => $mQty,
                            'size' => $mSize
                        ];
                    }
                }
            }

            // calculate total (Price + Merch + 10% tax + 3% service fee)
            $basePrice = $subtotal + $merchTotal;
            $tax = (int) round($basePrice * 0.1);
            $serviceFee = (int) round($basePrice * 0.03);
            $totalPrice = $basePrice + $tax + $serviceFee;

            $bookingCode = Booking::generateBookingCode();
            DB::beginTransaction();
            try {
                // Logika Penomoran Kursi
                // Lock category for update to prevent seat duplication
                $ticketCategory = \App\Models\TicketCategory::where('id', $request->ticket_category_id)->lockForUpdate()->first();
                
                if ($ticketCategory->quota < $request->quantity) {
                    throw new \Exception('Maaf, kuota tiket baru saja habis.');
                }

                $startSeat = $ticketCategory->last_seat_number + 1;
                $endSeat = $ticketCategory->last_seat_number + $request->quantity;
                
                // Format seat number: e.g., "VIP 001" or "VIP 001 - 003"
                if ($ticketCategory->type == 'standing') {
                    $seatNumber = 'FREE STANDING';
                } else {
                    $seatNumber = $request->quantity > 1 
                        ? $ticketCategory->name . ' ' . sprintf('%03d', $startSeat) . ' - ' . sprintf('%03d', $endSeat)
                        : $ticketCategory->name . ' ' . sprintf('%03d', $startSeat);
                }

                // Create Booking
                $booking = Booking::create([
                    'user_id' => Auth::id(),
                    'event_id' => $event->id,
                    'ticket_category_id' => $request->ticket_category_id,
                    'booking_code' => $bookingCode,
                    'seat_number' => $seatNumber,
                    'start_seat_number' => ($ticketCategory->type == 'standing') ? null : $startSeat,
                    'identity_number' => $request->identity_number,
                    'birth_date' => $request->birth_date,
                    'gender' => $request->gender,
                    'phone' => $request->phone,
                    'quantity' => $request->quantity,
                    'total_price' => $totalPrice,
                    'payment_status' => 'pending',
                    'booking_status' => 'pending'
                ]);

                // Update Category Quota and Last Seat Number
                $ticketCategory->decrement('quota', $request->quantity);
                $ticketCategory->increment('last_seat_number', $request->quantity);

                // Save pivot & Decrement merchandise stock
                foreach ($selectedMerch as $sm) {
                    $booking->merchandises()->attach($sm['id'], [
                        'price' => $sm['price'], 
                        'quantity' => $sm['quantity'],
                        'size' => $sm['size'] ?? null
                    ]);
                    \App\Models\Merchandise::where('id', $sm['id'])->decrement('stock', $sm['quantity']);
                }

                // Midtrans setup
                \Midtrans\Config::$serverKey = config('midtrans.server_key');
                \Midtrans\Config::$isProduction = config('midtrans.is_production');
                \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
                \Midtrans\Config::$is3ds = config('midtrans.is_3ds');

                $midtransItems = [
                    [
                        'id' => $ticketCategory->id,
                        'price' => (int)$ticketCategory->price,
                        'quantity' => $quantity,
                        'name' => 'Tiket ' . $ticketCategory->name,
                    ],
                    [
                        'id' => 'TAX',
                        'price' => (int)($tax),
                        'quantity' => 1,
                        'name' => 'Pajak (10%)',
                    ],
                    [
                        'id' => 'SERVICE',
                        'price' => (int)($serviceFee),
                        'quantity' => 1,
                        'name' => 'Biaya Layanan (3%)',
                    ]
                ];

                // Add merch to midtrans
                $midtransItems = array_merge($midtransItems, $merchItems);

                $params = [
                    'transaction_details' => [
                        'order_id' => $booking->booking_code,
                        'gross_amount' => (int)$totalPrice,
                    ],
                    'customer_details' => [
                        'first_name' => Auth::user()->name,
                        'email' => Auth::user()->email,
                        'phone' => $request->phone,
                    ],
                    'item_details' => $midtransItems,
                    'callbacks' => [
                        'finish' => route('booking.success', $booking->id)
                    ]
                ];

                $snapToken = \Midtrans\Snap::getSnapToken($params);
                $booking->update(['snap_token' => $snapToken]);

                DB::commit();

                return redirect()->route('booking.payment', $booking->id)
                    ->with('success', 'Booking created successfully!');
      
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = $e->getMessage();
            Log::error('Midtrans Error: ' . $errorMessage);
            
            // Provide a friendlier message for common errors
            if (strpos($errorMessage, 'unauthorized') !== false) {
                $errorMessage = 'Konfigurasi pembayaran (Server Key) tidak valid. Silakan hubungi admin.';
            } elseif (strpos($errorMessage, 'gross_amount') !== false) {
                $errorMessage = 'Kesalahan perhitungan total pembayaran. Silakan coba lagi.';
            }

            return back()->with('error', 'Gagal memproses pembayaran: ' . $errorMessage);
        }
    }

    // Midtrans Callback (Webhook)
    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
        
        if($hashed == $request->signature_key) {
            
            // Handle Booth Payment
            if (strpos($request->order_id, 'BOOTH-') === 0) {
                // Format: BOOTH-{eventTenant_id}-{timestamp}
                $parts = explode('-', $request->order_id);
                $eventTenantId = $parts[1] ?? null;
                
                if ($eventTenantId) {
                    $tenant = \App\Models\EventTenant::find($eventTenantId);
                    if ($tenant) {
                        if($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                            $tenant->update(['payment_status' => 'paid']);
                        } elseif($request->transaction_status == 'pending') {
                            $tenant->update(['payment_status' => 'pending']);
                        } else {
                            $tenant->update(['payment_status' => 'failed']);
                        }
                    }
                }
                return;
            }

            // Handle Regular Ticket Booking
            $booking = Booking::where('booking_code', $request->order_id)->first();
            if($booking) {
                if($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                    if ($booking->payment_status !== 'paid') {
                        // Extract specific payment method (e.g. BCA, Mandiri)
                        $pm = $request->payment_type;
                        if ($pm === 'bank_transfer' && !empty($request->va_numbers[0]['bank'])) {
                            $pm = 'bank_transfer_' . $request->va_numbers[0]['bank'];
                        } elseif ($pm === 'echannel') {
                            $pm = 'bank_transfer_mandiri';
                        } elseif (!empty($request->permata_va_number)) {
                            $pm = 'bank_transfer_permata';
                        }

                        $booking->update([
                            'payment_status' => 'paid',
                            'booking_status' => 'confirmed',
                            'payment_method' => $pm
                        ]);
                        
                        // Generate individual tickets
                        $this->generateTickets($booking);
                        
                        // Reload booking dengan relasi tickets yang baru dibuat
                        $booking->load(['tickets', 'event', 'user', 'ticket_category']);
                        
                        // Send Email
                        try {
                            Mail::to($booking->user->email)->send(new BookingConfirmationMail($booking));
                        } catch (\Exception $e) {
                            Log::error('Failed to send booking confirmation email: ' . $e->getMessage());
                        }
                    }
                } elseif($request->transaction_status == 'pending') {
                    $booking->update(['payment_status' => 'pending']);
                } else {
                    $booking->update(['payment_status' => 'failed', 'booking_status' => 'cancelled']);
                    
                    // Kembalikan kuota tiket
                    $ticketCategory = \App\Models\TicketCategory::find($booking->ticket_category_id);
                    if ($ticketCategory) {
                        $ticketCategory->increment('quota', $booking->quantity);
                    }
                }
            }
        }
    }

    // show payment page
    public function payment($bookingId)
    {
        $booking = Booking::with('event')->findOrFail($bookingId);

        if($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        if($booking->payment_status === 'paid') {
            return redirect()->route('booking.success', $booking->id);
        }

        if ($booking->booking_status === 'cancelled') {
            return redirect()->route('home')->with('error', 'Sesi pembayaran Anda telah berakhir.');
        }

        // Cek jika sudah lebih dari 5 menit sejak dibuat
        $expiryTime = $booking->created_at->addMinutes(5);
        if (\Carbon\Carbon::now()->gt($expiryTime)) {
            if ($booking->payment_status === 'pending') {
                DB::beginTransaction();
                try {
                    $booking->update([
                        'booking_status' => 'cancelled',
                        'payment_status' => 'cancelled'
                    ]);

                    // Kembalikan kuota tiket
                    $ticketCategory = \App\Models\TicketCategory::find($booking->ticket_category_id);
                    if ($ticketCategory) {
                        $ticketCategory->increment('quota', $booking->quantity);
                    }
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('Auto-expire failed: ' . $e->getMessage());
                }
            }
            return redirect()->route('home')->with('error', 'Sesi pembayaran Anda telah berakhir.');
        }

        $secondsRemaining = \Carbon\Carbon::now()->diffInSeconds($expiryTime, false);
        if ($secondsRemaining < 0) {
            $secondsRemaining = 0;
        }

        return view('booking.payment', compact('booking', 'secondsRemaining'));
    }

    // expire booking via AJAX/fetch
    public function expire($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);

        if ($booking->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($booking->payment_status === 'pending') {
            DB::beginTransaction();
            try {
                $booking->update([
                    'booking_status' => 'cancelled',
                    'payment_status' => 'cancelled'
                ]);

                // Kembalikan kuota tiket
                $ticketCategory = \App\Models\TicketCategory::find($booking->ticket_category_id);
                if ($ticketCategory) {
                    $ticketCategory->increment('quota', $booking->quantity);
                }

                DB::commit();
                return response()->json(['message' => 'Booking expired successfully']);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['message' => 'Failed to expire booking: ' . $e->getMessage()], 500);
            }
        }

        return response()->json(['message' => 'Booking already processed or not pending']);
    }

    // show success page
    public function success($bookingId)
    {
        $booking = Booking::with(['event', 'user'])->findOrFail($bookingId);

        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        // Sync status if still pending (fallback for callback issues)
        if ($booking->payment_status === 'pending') {
            $this->syncPaymentStatus($booking);
        }

        return view('booking.success', compact('booking'));
    }

    // Show user's scanned booking history
    public function myBookings()
    {
        $bookings = Booking::with(['event', 'user'])
            ->where('user_id', Auth::id())
            ->where('payment_status', 'paid')
            ->where('booking_status', 'confirmed')
            ->whereHas('tickets', function($q) {
                $q->where('status', 'scanned');
            })
            ->whereDoesntHave('tickets', function($q) {
                $q->where('status', '!=', 'scanned');
            })
            ->latest()
            ->paginate(10);

        return view('user.order-history', compact('bookings'));
    }

    // cancel booking
    public function cancel($bookingId)
    {
        $booking = Booking::with('event')->findOrFail($bookingId);

        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        if ($booking->payment_status === 'paid') {
            return back()->with('error', 'Cannot cancel paid booking.');
        }

        $booking->update([
            'booking_status' => 'cancelled',
            'payment_status' => 'failed'
        ]);

        // Kembalikan kuota tiket
        $ticketCategory = \App\Models\TicketCategory::find($booking->ticket_category_id);
        if ($ticketCategory) {
            $ticketCategory->increment('quota', $booking->quantity);
        }

        return back()->with('success', 'Booking cancelled successfully.');
    }

    private function syncPaymentStatus($booking)
    {
        try {
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production');
            
            $rawStatus = \Midtrans\Transaction::status($booking->booking_code);
            $status = json_decode(json_encode($rawStatus), true);
            
            if ($status['transaction_status'] == 'capture' || $status['transaction_status'] == 'settlement') {
                if ($booking->payment_status !== 'paid') {
                    $pm = $status['payment_type'] ?? null;
                    if ($pm === 'bank_transfer' && !empty($status['va_numbers'][0]['bank'])) {
                        $pm = 'bank_transfer_' . $status['va_numbers'][0]['bank'];
                    } elseif ($pm === 'echannel') {
                        $pm = 'bank_transfer_mandiri';
                    } elseif (!empty($status['permata_va_number'])) {
                        $pm = 'bank_transfer_permata';
                    }

                    $booking->update([
                        'payment_status' => 'paid',
                        'booking_status' => 'confirmed',
                        'payment_method' => $pm
                    ]);
                    
                    // Generate individual tickets
                    $this->generateTickets($booking);
                    
                    // Reload booking dengan relasi tickets yang baru dibuat
                    $booking->load(['tickets', 'event', 'user', 'ticket_category']);
                    
                    // Send Email
                    try {
                        Mail::to($booking->user->email)->send(new BookingConfirmationMail($booking));
                    } catch (\Exception $e) {
                        Log::error('Failed to send fallback email: ' . $e->getMessage());
                    }
                }
            } elseif (in_array($status['transaction_status'], ['deny', 'expire', 'cancel'])) {
                $booking->update([
                    'payment_status' => 'failed',
                    'booking_status' => 'cancelled'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Status sync failed for ' . $booking->booking_code . ': ' . $e->getMessage());
        }
    }

    private function generateTickets($booking)
    {
        if ($booking->tickets()->exists()) {
            return;
        }

        for ($i = 0; $i < $booking->quantity; $i++) {
            $seat = null;
            if ($booking->start_seat_number) {
                $seat = $booking->ticket_category->name . ' ' . sprintf('%03d', $booking->start_seat_number + $i);
            } else {
                $seat = 'FREE STANDING';
            }

            \App\Models\Ticket::create([
                'booking_id' => $booking->id,
                'ticket_code' => \App\Models\Ticket::generateTicketCode(),
                'seat_number' => $seat,
                'status' => 'active',
            ]);
        }
    }
}
