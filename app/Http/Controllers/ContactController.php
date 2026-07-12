<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContactController extends Controller
{
    /**
     * Menampilkan form kontak
     */
    public function showForm()
    {
        $messages = collect();
        if (auth()->check()) {
            $messages = ContactMessage::where('email', auth()->user()->email)->latest()->get();
        }
        return view('contact', compact('messages'));
    }

    /**
     * Menyimpan pesan kontak dengan upload foto
     */
    public function send(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // Max 5MB
        ]);

        // Handle upload file foto
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('contact-messages', 'public');
        }

        // Simpan ke database
        ContactMessage::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'photo_path' => $photoPath,
            'status' => 'unread',
        ]);

        if ($request->input('redirect_to') === 'my-messages') {
            return redirect()->route('my-messages')
                ->with('success', 'Pesan berhasil dikirim! Admin akan segera meresponnya.');
        }

        // Redirect dengan pesan sukses
        return redirect()->back()
             ->with('success', 'Pesan berhasil dikirim! Admin akan segera meresponnya.');
    }
}
