<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil input q (teks) DAN category (klik box)
        $search = $request->input('query');
        $category = $request->input('category');

        // 2. Query dasar (Cuma event yang aktif dan belum lewat)
        $today = now()->toDateString();
        $query = Event::with('ticket_categories')
            ->where('status', 'active')
            ->whereDate('date', '>=', $today);

        // 3. LOGIK KATEGORI (Ini yang bikin klik box langsung muncul)
        if ($category) {
            $query->where('category', $category);
            // Increment search count for category searches
            Event::where('status', 'active')->where('category', $category)->increment('search_count');
        }

        // 4. LOGIK PENCARIAN TEKS (Jika user ngetik di navbar)
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('artist', 'LIKE', "%{$search}%")
                  ->orWhere('venue', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
            // Increment search count for matching results
            $query->getQuery()->increment('search_count');
        }

        // 5. Eksekusi
        $results = $query->latest()->paginate(12);

        // 6. Lempar ke view 'search'
        return view('search', [
            'results' => $results,
            'query' => $search,
            'category' => $category // Variabel ini penting buat judul di halaman search
        ]);
    }

    // Method lain (create, store, dll) dikosongkan saja jika tidak dipakai
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
