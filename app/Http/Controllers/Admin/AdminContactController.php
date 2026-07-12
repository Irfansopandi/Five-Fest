<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminContactController extends Controller
{
    /**
     * Menampilkan daftar pesan kontak
     */
    public function index(Request $request)
    {
        $query = ContactMessage::select('email', 'name')
            ->selectRaw('MAX(id) as last_id')
            ->selectRaw('COUNT(*) as total_messages')
            ->selectRaw('MAX(created_at) as last_message_at')
            ->selectRaw('SUM(CASE WHEN status = "unread" THEN 1 ELSE 0 END) as unread_count')
            ->selectRaw('SUM(CASE WHEN status = "replied" THEN 1 ELSE 0 END) as replied_count')
            ->groupBy('email', 'name');

        // Filter berdasarkan status
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");       
            });
        }

        $conversations = $query->orderByDesc('last_message_at')->paginate(15);

        $stats = [
            'total'   => ContactMessage::count(),
            'unread'  => ContactMessage::where('status', 'unread')->count(),
            'read'    => ContactMessage::where('status', 'read')->count(),
            'replied' => ContactMessage::where('status', 'replied')->count(),
        ];

        return view('admin.contact.index', compact('conversations', 'stats'));
    }

    /**
     * Menampilkan detail pesan kontak
     */
    public function show($id)
    {
        // $id di sini adalah id pesan terakhir, ambil email-nya dulu
        $message = ContactMessage::findOrFail($id);

        // Mark semua pesan dari email ini sebagai read
        ContactMessage::where('email', $message->email)
            ->where('status', 'unread')
            ->update(['status' => 'read']);

        // Ambil SEMUA pesan dari email ini (thread)
        $thread = ContactMessage::where('email', $message->email)
            ->oldest()
            ->get();

        $user = [
            'name'  => $message->name,
            'email' => $message->email,
        ];

        return view('admin.contact.show', compact('thread', 'user', 'message'));
    }

    /**
     * Update status dan admin notes
     */
    public function update(Request $request, $id)
    {
        $message = ContactMessage::findOrFail($id);

        $validated = $request->validate([
            'admin_notes' => 'required|string',
        ]);

        // Selalu set replied saat admin balas
        $message->update([
            'admin_notes'    => $validated['admin_notes'],
            'status'         => 'replied',
            'replied_at'     => now(),
            'is_read_by_user'=> false,
        ]);

        return redirect()->route('admin.contact.show', $id)
            ->with('success', 'Balasan berhasil dikirim!');
    }

    /**
     * Hapus pesan kontak
     */
    public function destroy($id)
    {
        $message = ContactMessage::findOrFail($id);

        // Hapus file foto jika ada
        if ($message->photo_path && Storage::disk('public')->exists($message->photo_path)) {
            Storage::disk('public')->delete($message->photo_path);
        }

        $message->delete();

        return redirect()->route('admin.contact.index')
                        ->with('success', 'Pesan berhasil dihapus!');
    }

    /**
     * Download foto lampiran
     */
    public function downloadPhoto($id)
    {
        $message = ContactMessage::findOrFail($id);

        if (!$message->photo_path || !Storage::disk('public')->exists($message->photo_path)) {
            return redirect()->back()->with('error', 'File tidak ditemukan!');
        }

        return Storage::disk('public')->download($message->photo_path);
    }

    /**
     * Export pesan ke CSV
     */
    public function export(Request $request)
    {
        $query = ContactMessage::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $messages = $query->latest()->get();

        $filename = 'contact_messages_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $handle = fopen('php://memory', 'w');
        fputcsv($handle, ['Nama', 'Email', 'Subjek', 'Pesan', 'Status', 'Tanggal', 'Foto']);

        foreach ($messages as $msg) {
            fputcsv($handle, [
                $msg->name,
                $msg->email,
                $msg->subject,
                $msg->message,
                $msg->status,
                $msg->created_at->format('Y-m-d H:i'),
                $msg->photo_path ? 'Ya' : 'Tidak',
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
