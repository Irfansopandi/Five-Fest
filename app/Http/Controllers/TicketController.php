<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class TicketController extends Controller
{
    // Tampilkan halaman My Tickets
    public function myTickets()
    {
        $upcomingBookings = Booking::with('event')
            ->where('user_id', Auth::id())
            ->where('payment_status', 'paid')
            ->where('booking_status', 'confirmed')
            ->whereHas('tickets', function($query) {
                $query->where('status', '!=', 'scanned');
            })
            ->whereHas('event', function($query) {
                $query->where('date', '>=', now()->toDateString());
            })
            ->latest()
            ->paginate(5, ['*'], 'upcoming_page');

        $pastBookings = Booking::with('event')
            ->where('user_id', Auth::id())
            ->where('payment_status', 'paid')
            ->where(function($query) {
                $query->whereDoesntHave('tickets', function($q) {
                    $q->where('status', '!=', 'scanned');
                })
                ->orWhereHas('event', function($q) {
                    $q->where('date', '<', now()->toDateString());
                });
            })
            ->whereHas('tickets')
            ->latest()
            ->paginate(5, ['*'], 'past_page');

        return view('user.tickets', compact('upcomingBookings', 'pastBookings'));
    }

    // Tampilkan detail ticket dengan QR code
    public function showTicket($id)
    {
        $booking = Booking::with(['event', 'tickets'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('user.ticket-show', compact('booking'));
    }

    // Download E-Ticket PDF
    public function downloadTicket($id)
    {
        $booking = Booking::with(['event', 'user', 'tickets'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('user.ticket-print', compact('booking'));
    }

    // Generate QR Code image
    public function generateQrCode($ticketCode)
    {
        $ticket = Ticket::where('ticket_code', $ticketCode)->first();

        if (!$ticket) {
            abort(404);
        }

        $qrCode = QrCode::size(300)
            ->margin(1)
            ->errorCorrection('H')
            ->generate($ticketCode);

        return response($qrCode)
            ->header('Content-Type', 'image/svg+xml');
    }

    // --- VENDOR SCANNER METHODS ---

    // Tampilkan Scanner untuk Vendor
    public function scanner(Request $request)
    {
        $perPage = $request->input('per_page', 5);
        $recentScans = Ticket::with(['booking.user', 'booking.event', 'booking.ticket_category'])
            ->where('status', 'scanned')
            ->whereHas('booking.event', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->latest('scanned_at')
            ->paginate($perPage);

        /** @var \Illuminate\Pagination\LengthAwarePaginator $recentScans */
        $recentScans->withQueryString();

        return view('vendor.scanner', compact('recentScans'));
    }

    // Proses Scan QR - check in tiket masuk
    public function scan(Request $request)
    {
        $ticketCode = $request->ticket_code;

        $ticket = Ticket::with(['booking.user', 'booking.event', 'booking.ticket_category'])
            ->where('ticket_code', $ticketCode)
            ->first();

        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Tiket tidak valid atau tidak ditemukan.'
            ], 404);
        }

        if ($ticket->booking->event->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk scan tiket event ini.'
            ], 403);
        }

        if ($ticket->status === 'scanned') {
            return response()->json([
                'success' => false,
                'message' => 'Tiket sudah pernah digunakan pada ' . $ticket->scanned_at->format('d M Y H:i'),
                'ticket'  => $ticket
            ]);
        }

        if ($ticket->status === 'cancelled') {
            return response()->json([
                'success' => false,
                'message' => 'Tiket telah dibatalkan.'
            ], 400);
        }

        $ticket->update([
            'status'     => 'scanned',
            'scanned_at' => now(),
            'scanned_by' => Auth::id()
        ]);

        return response()->json([
            'success'  => true,
            'message'  => 'Check-in berhasil! Selamat menonton.',
            'ticket'   => $ticket,
            'user'     => $ticket->booking->user,
            'category' => $ticket->booking->ticket_category->name,
            'seat'     => $ticket->seat_number
        ]);
    }

    // Proses Scan QR - khusus merchandise (dipanggil dari StaffController)
    public function scanMerchandise(Request $request)
    {
        $request->validate(['booking_code' => 'required|string']);

        $code = $request->booking_code;

        // Cari via ticket_code dulu, fallback ke booking_code
        $ticket = Ticket::with(['booking.user', 'booking.event', 'booking.merchandises'])
            ->where('ticket_code', $code)
            ->first();

        $booking = $ticket
            ? $ticket->booking
            : Booking::with(['user', 'event', 'merchandises'])
                ->where('booking_code', $code)
                ->where('payment_status', 'paid')
                ->first();

        if (!$booking) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Tiket tidak ditemukan.'
            ]);
        }

        if ($booking->merchandises->isEmpty()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Booking ini tidak memiliki merchandise.'
            ]);
        }

        $allCollected = $booking->merchandises->every(fn($m) => $m->pivot->is_collected);

        if ($allCollected) {
            return response()->json([
                'status'  => 'already',
                'booking' => $this->formatMerchandiseBooking($booking),
            ]);
        }

        $booking->merchandises->each(function ($m) use ($booking) {
            $booking->merchandises()->updateExistingPivot($m->id, [
                'is_collected' => true,
                'collected_at' => now(),
            ]);
        });

        $booking->load('merchandises');

        return response()->json([
            'status'  => 'success',
            'message' => 'Merchandise berhasil diverifikasi!',
            'booking' => $this->formatMerchandiseBooking($booking),
        ]);
    }

    private function formatMerchandiseBooking(Booking $booking)
    {
        return [
            'buyer_name'   => $booking->user->name,
            'booking_code' => $booking->booking_code,
            'event_name'   => $booking->event->name ?? '-',
            'merchandises' => $booking->merchandises->map(fn($m) => [
                'name'     => $m->name,
                'quantity' => $m->pivot->quantity,
                'price'    => $m->pivot->price,
            ])->values(),
        ];
    }
}