<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ticket;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\facade\Pdf;

class StaffController extends Controller
{
    // Tampilkan daftar staf milik vendor yang login
    public function index()
    {
        $staffList = User::where('parent_vendor_id', Auth::id())
            ->where('role', 'vendor_staff')
            ->latest()
            ->get();

        return view('v_vendor.staff.index', compact('staffList'));
    }

    // Tambah staff baru
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.unique'        => 'Email ini sudah digunakan.',
            'password.confirmed'  => 'Konfirmasi password tidak cocok.',
            'password.min'        => 'Password minimal 8 karakter.',
        ]);

        User::create([
            'name'             => $request->name,
            'email'            => $request->email,
            'password'         => Hash::make($request->password),
            'role'             => 'vendor_staff',
            'parent_vendor_id' => Auth::id(),
            'status'           => 'active',
        ]);

        return back()->with('success', 'Staf berhasil ditambahkan.');
    }

    // Hapus staf (hanya jika milik vendor yang login)
    public function destroy($id)
    {
        $staff = User::where('id', $id)
            ->where('parent_vendor_id', Auth::id())
            ->where('role', 'vendor_staff')
            ->firstOrFail();

        $staff->delete();

        return back()->with('success', 'Staf berhasil dihapus.');
    }

    // Halaman scanner merchandise untuk staf
    public function scanner(Request $request)
    {
        $user     = Auth::user();
        $vendorId = $user->role === 'vendor_staff' ? $user->parent_vendor_id : $user->id;

        $events      = \App\Models\Event::where('user_id', $vendorId)->get();
        $perPage     = $request->get('per_page', 5);
        $scanHistory = $this->getScanHistory($vendorId, $perPage);

        return view('v_vendor.staff.scanner', compact('events', 'vendorId', 'scanHistory', 'perPage'));
    }

    // Proses scan QR merchandise
    public function scan(Request $request)
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

        // Cek apakah semua merchandise sudah diambil
        $allCollected = $booking->merchandises->every(function ($m) {
            return $m->pivot->is_collected;
        });

        if ($allCollected) {
            return response()->json([
                'status'  => 'already',
                'booking' => $this->formatBooking($booking),
            ]);
        }

        // Tandai semua sebagai sudah diambil
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
            'booking' => $this->formatBooking($booking),
        ]);
    }

    public function scanHistory()
    {
        $user     = Auth::user();
        $vendorId = $user->role === 'vendor_staff' ? $user->parent_vendor_id : $user->id;

        $scanHistory = $this->getScanHistory($vendorId);

        $result = collect($scanHistory->items())->map(function ($b) {
            $firstMerch = $b->merchandises->first();
            $collectedAt = '-';

            if ($firstMerch && $firstMerch->pivot && $firstMerch->pivot->collected_at) {
                $collectedAt = $firstMerch->pivot->collected_at->format('H:i:s');
            }

            $merchandises = $b->merchandises->map(function ($m) {
                return $m->name . ' x' . $m->pivot->quantity;
            })->join(', ');

            return [
                'id'           => $b->id,
                'booking_code' => $b->booking_code,
                'buyer_name'   => $b->user->name,
                'event_name'   => $b->event->name ?? '-',
                'collected_at' => $collectedAt,
                'merchandises' => $merchandises,
            ];
        });

        return response()->json($result);
    }

    private function getScanHistory($vendorId, $perPage = 5)
    {
        return Booking::with(['user', 'event', 'merchandises'])
            ->whereHas('merchandises', function ($q) {
                $q->where('booking_merchandise.is_collected', true)
                    ->whereDate('booking_merchandise.collected_at', today());
            })
            ->whereHas('event', function ($q) use ($vendorId) {
                $q->where('user_id', $vendorId);
            })
            ->latest()
            ->paginate($perPage)
            ->appends(request()->query());
    }


    private function formatBooking(Booking $booking)
    {
        return [
            'buyer_name'   => $booking->user->name,
            'booking_code' => $booking->booking_code,
            'event_name'   => $booking->event->name ?? '-',
            'merchandises' => $booking->merchandises->map(function ($m) {
                return [
                    'name'     => $m->name,
                    'quantity' => $m->pivot->quantity,
                    'price'    => $m->pivot->price,
                ];
            })->values(),
        ];
    }
}