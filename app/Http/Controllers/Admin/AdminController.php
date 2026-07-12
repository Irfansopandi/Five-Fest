<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    // Dashboard
    public function dashboard()
    {
        $totalEvents = Event::count();
        $totalUsers = User::where('role', 'user')->count();
        $totalBookings = Booking::where('payment_status', 'paid')->sum('quantity');
        $totalRevenue = Booking::where('payment_status', 'paid')->sum('total_price');
        $pendingVendors = User::where('role', 'vendor')->where('verification_status', 'pending')->count();

        $recentBookings = Booking::with(['user', 'event'])
            ->where('payment_status', 'paid')
            ->where('booking_status', 'confirmed')
            ->latest()
            ->take(5)
            ->get();

        $topEvents = Event::withSum(['bookings as tickets_sold' => function($q) {
            $q->where('payment_status', 'paid');
        }], 'quantity')
        ->orderBy('tickets_sold', 'desc')
        ->limit(5)
        ->get();

        // Chart Data: Top 10 Vendor by Revenue
        $vendorSalesData = User::where('role', 'vendor')
            ->join('events', 'users.id', '=', 'events.user_id')
            ->join('bookings', 'events.id', '=', 'bookings.event_id')
            ->where('bookings.payment_status', 'paid')
            ->selectRaw('users.name as vendor_name, SUM(bookings.total_price) as revenue, SUM(bookings.quantity) as tickets')
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('revenue')
            ->limit(10)
            ->get();

        $chartData = [
            'labels' => $vendorSalesData->pluck('vendor_name'),
            'revenue' => $vendorSalesData->pluck('revenue'),
            'tickets' => $vendorSalesData->pluck('tickets'),
        ];

        return view('admin.dashboard', compact(
            'totalEvents',
            'totalUsers',
            'totalBookings',
            'totalRevenue',
            'pendingVendors',
            'recentBookings',
            'topEvents',
            'chartData'
        ));
    }

    // Events Management
    public function events(Request $request)
    {
        $query = Event::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                ->orWhere('artist', 'LIKE', "%{$search}%")
                ->orWhere('venue', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $sortBy = $request->get('sort_by', 'latest');
        switch ($sortBy) {
            case 'oldest':
                $query->oldest();
                break;
            case 'title_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'title_desc':
                $query->orderBy('title', 'desc');
                break;
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            default:
                $query->latest();
        }

        $events = $query->paginate(10)->appends(request()->query());
        $totalEvents = Event::count();
        $activeEvents = Event::where('status', 'active')->count();
        $categories = Event::select('category')->distinct()->pluck('category');

        return view('admin.events.index', compact('events', 'totalEvents', 'activeEvents', 'categories'));
    }

    public function editEvent($id)
    {
        $event = Event::findOrFail($id);
        $categories = Event::select('category')->distinct()->pluck('category');
        return view('admin.events.edit', compact('event', 'categories'));
    }

    public function updateEvent(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'artist' => 'nullable|string|max:255',
            'description' => 'required',
            'venue' => 'required|string|max:255',
            'date' => 'required|date',
            'time' => 'nullable',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string',
            'available_tickets' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($event->image && Storage::disk('public')->exists($event->image)) {
                Storage::disk('public')->delete($event->image);
            }
            
            $imagePath = $request->file('image')->store('events', 'public');
            $validated['image'] = $imagePath;
        }

        $event->update($validated);

        return redirect()->route('admin.events.index')->with('sukses', 'Event berhasil diperbarui!');
    }

    public function deleteEvent($id)
    {
        $event = Event::findOrFail($id);
        
        if ($event->image && Storage::disk('public')->exists($event->image)) {
            Storage::disk('public')->delete($event->image);
        }
        
        $event->delete();

        return redirect()->route('admin.events.index')->with('sukses', 'Event berhasil dihapus!');
    }
}