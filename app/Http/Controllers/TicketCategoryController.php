<?php

namespace App\Http\Controllers;

use App\Models\TicketCategory;
use App\Models\Event;
use Illuminate\Http\Request;

class TicketCategoryController extends Controller
{
    // Tambahkan fungsi index ini yang hilang:
    public function index(Request $request)
    {
        $eventId = $request->query('event_id');
        
        // Ambil data event dan pastikan milik user yang login
        $event = Event::where('user_id', auth()->id())->findOrFail($eventId);

        // Ambil semua kategori tiket berdasarkan event_id
        $categories = TicketCategory::where('event_id', $eventId)->get();

        return view('v_vendor.ticket.index', compact('categories', 'event'));
    }

    public function create(Request $request)
    {
        $eventId = $request->query('event_id');
        $event = Event::where('user_id', auth()->id())->findOrFail($eventId);
        return view('v_vendor.ticket.create', compact('event'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'name'     => 'required|string|max:255',
            'price'    => 'required|numeric|min:0',
            'quota'    => 'required|integer|min:1',
            'benefits' => 'nullable|string',
        ]);

        TicketCategory::create($request->all());

        return redirect()->route('vendor.ticket_categories.index', ['event_id' => $request->event_id])
                         ->with('success', 'Kategori tiket berhasil dibuat!');
    }
}