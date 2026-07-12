<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Merchandise;
use Illuminate\Http\Request;

class MerchandiseController extends Controller
{
    public function create()
{
    // Ambil event milik vendor yang sedang login saja
    $events = Event::where('user_id', auth()->id())->get();
    return view('v_vendor.merch.create', compact('events'));
}

public function store(Request $request)
{
    $request->validate([
        'event_id' => 'required|exists:events,id',
        'name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'description' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('merchandises', 'public');
    }

    Merchandise::create([
        'event_id' => $request->event_id,
        'name' => $request->name,
        'price' => $request->price,
        'stock' => $request->stock,
        'description' => $request->description,
        'image' => $imagePath,
    ]);

    return redirect()->route('vendor.merchandises.index')->with('success', 'Merchandise berhasil ditambah!');


        $data = $request->all();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('merch', 'public');
        }

        Merchandise::create($data);

        return redirect()->back()->with('success', 'Merch berhasil ditambahkan!');
    }
}