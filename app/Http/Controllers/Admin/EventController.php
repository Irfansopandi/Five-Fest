<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventController extends Controller
{
    /**
     * Display a listing of events
     */
    public function index(Request $request)
    {
        $query = Event::query();
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                ->orWhere('artist', 'like', "%{$search}%")
                ->orWhere('venue', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Filter by category
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }
        
        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }
        
        // Sort
        $sortBy = $request->get('sort_by', 'latest');
        switch ($sortBy) {
            case 'oldest':
                $query->oldest('date');
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
                $query->latest('date');
        }
        
        $events = $query->withCount('bookings')->paginate(10)->appends(request()->query());

        $totalEvents = Event::count();
        $activeEvents = Event::where('status', 'active')->count();
        $categories = Event::select('category')->distinct()->pluck('category');
        
        return view('admin.events.index', compact('events', 'totalEvents', 'activeEvents', 'categories'));
        
    }
    
    /**
     * Show the form for creating a new event
     */
    public function createEvent()
    {
        $categories = Event::select('category')->distinct()->pluck('category');
        return view('admin.events.create', compact('categories'));
    }
    
    /**
     * Store a newly created event
     */
public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'terms' => 'nullable|string',
        'venue' => 'required|string|max:255',
        'date' => 'required|date|after_or_equal:today',
        'time' => 'required',
        'price' => 'nullable|numeric|min:0',
        'available_tickets' => 'nullable|integer|min:0',
        'status' => 'required|in:active,inactive,cancelled',
        'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        'seat_plan' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Tambah validasi
        'category' => 'nullable|string|max:100',
        'artist' => 'nullable|string|max:255',
    ]);

    // Set user_id dari siapa yang login & set capacity sama dengan tiket tersedia awal
    $validated['user_id'] = auth()->id();
    $validated['capacity'] = $request->available_tickets ?? 0;
    $validated['price'] = 0;
    $validated['available_tickets'] = 0;

    // Handle Upload Poster
    if ($request->hasFile('image')) {
        $validated['image'] = $request->file('image')->store('events/posters', 'public');
    }

    // Handle Upload Seat Plan
    if ($request->hasFile('seat_plan')) {
        $validated['seat_plan'] = $request->file('seat_plan')->store('events/seatplans', 'public');
    }

    Event::create($validated);

        
        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event berhasil dibuat!');
    }
    
    /**
     * Display the specified event
     */
    public function show(Event $event)
    {
        $event->load(['bookings.user']);
        
        // Statistics
        $totalBookings = $event->bookings()->count();
        $totalRevenue = $event->bookings()->where('status', 'completed')->sum('total_amount');
        $ticketsSold = $event->bookings()->where('status', 'completed')->sum('quantity');
        
        return view('admin.events.show', compact('event', 'totalBookings', 'totalRevenue', 'ticketsSold'));
    }
    
    /**
     * Show the form for editing the specified event
     */
    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }
    
    /**
     * Update the specified event
     */
public function update(Request $request, Event $event)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'terms' => 'nullable|string',
        'venue' => 'required|string|max:255',
        'date' => 'required|date',
        'time' => 'required',
        'price' => 'nullable|numeric|min:0',
        'capacity' => 'nullable|integer|min:0', // Tambah kapasitas di edit
        'available_tickets' => 'nullable|integer|min:0',
        'status' => 'required|in:active,inactive,cancelled',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        'seat_plan' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        'category' => 'nullable|string|max:100',
        'artist' => 'nullable|string|max:255',
    ]);

    // Handle Update Poster (Hapus yang lama jika ada file baru)
    if ($request->hasFile('image')) {
        if ($event->image) Storage::disk('public')->delete($event->image);
        $validated['image'] = $request->file('image')->store('events/posters', 'public');
    }

    // Handle Update Seat Plan
    if ($request->hasFile('seat_plan')) {
        if ($event->seat_plan) Storage::disk('public')->delete($event->seat_plan);
        $validated['seat_plan'] = $request->file('seat_plan')->store('events/seatplans', 'public');
    }

    $event->update($validated);

    return redirect()->route('admin.events.index')->with('success', 'Event berhasil diperbarui!');

    return redirect()->route('admin.events.index')->with('success', 'Event berhasil diperbarui!');
}
    
    /**
     * Remove the specified event
     */
    public function destroy(Event $event)
    {
        // Check if event has active/completed booking
        $activeBookings = $event->bookings()
            ->whereIn('payment_status', ['pending', 'completed'])
            ->count();


        if($activeBookings> 0) {
            return redirect()
                ->route('admin.events.index') 
                ->with('error', 'Tidak dapat menghapus acara dengan pemesanan yang aktif!');
        }
        
        // Delete image
        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }
        
        $event->delete();
        
        return redirect()
            ->route('admin.events.index') 
            ->with('success', 'Event berhasil dihapus!');
    }
    
    /**
     * Toggle event status
     */
    public function toggleStatus(Event $event)
    {
        $event->status = $event->status === 'active' ? 'inactive' : 'active';
        $event->save();
        
        return response()->json([
            'success' => true,
            'status' => $event->status,
            'message' => 'Ubah status Event Berhasil!'
        ]);
    }
}
