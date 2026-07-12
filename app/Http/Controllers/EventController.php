<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Services\SpotifyService;
use Illuminate\Http\Request;

class EventController extends Controller
{
    protected SpotifyService $spotify;

    public function __construct(SpotifyService $spotify)
    {
        $this->spotify = $spotify;
    }

    /**
     * Tampilkan halaman detail event (publik)
     */
    public function show(int $id)
    {
        $event = Event::with('ticket_categories')->findOrFail($id);

        // Increment view count
        $event->increment('view_count');
        // Increment search count jika datang dari halaman search
        if (request()->headers->get('referer') && str_contains(request()->headers->get('referer'), 'search')) {
            $event->increment('search_count');
        }

        // Ambil tracks Spotify sesuai event (artist → kategori → default)
        $spotifyTracks = $this->spotify->getTracksForEvent(
            $event->artist ?? null,
            $event->category ?? null,
            limit: 12,
            playlistId: $event->spotify_playlist_id ?? null,
        );
        

        return view('event-detail', compact('event', 'spotifyTracks'));
    }
}