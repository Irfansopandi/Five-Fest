<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Booking;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    // Show profile page
    public function index()
    {
        return view('user.profile');
    }

    // Update profile
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $rules = [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ];

        if ($user->role !== 'vendor') {
            $rules['email'] = 'required|email|unique:users,email,' . $user->id;
        }

        $request->validate($rules);

        $updateData = [
            'name' => $request->name,
            'phone' => $request->phone,
        ];

        if ($user->role !== 'vendor') {
            $updateData['email'] = $request->email;
        }

        DB::table('users')
            ->where('id', $user->id)
            ->update($updateData);

        return back()->with('success', 'Profile updated successfully!');
    }

    // Update password
    public function updatePassword(Request $request)
    {
        $userId = Auth::id();

        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $currentPassword = DB::table('users')
            ->where('id', $userId)
            ->value('password');

        if (!Hash::check($request->current_password, $currentPassword)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        DB::table('users')
            ->where('id', $userId)
            ->update([
                'password' => Hash::make($request->password)
            ]);

        return back()->with('success', 'Password changed successfully!');
    }

    public function orderHistory(Request $request)
    {
        try {
            $userId = Auth::id();
            
            // Ambil per_page dari request, default 10
            $perPage = $request->input('per_page', 10);
            
            // Query dasar
            $query = Booking::with('event')
                ->where('user_id', $userId);

            // Filter berdasarkan search (booking code atau event title)
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('booking_code', 'LIKE', "%{$search}%")
                    ->orWhereHas('event', function($q) use ($search) {
                        $q->where('title', 'LIKE', "%{$search}%");
                    });
                });
            }
            
            // Filter berdasarkan status
            if ($request->filled('status')) {
                if ($request->status == 'confirmed') {
                    $query->where('payment_status', 'paid')
                        ->where('booking_status', 'confirmed');
                } elseif ($request->status == 'pending') {
                    $query->where('payment_status', 'pending');
                } elseif ($request->status == 'cancelled') {
                    $query->where('booking_status', 'cancelled');
                }
            }
            
            // Sorting
            if ($request->filled('sort')) {
                switch ($request->sort) {
                    case 'newest':
                        $query->orderBy('created_at', 'desc');
                        break;
                    case 'oldest':
                        $query->orderBy('created_at', 'asc');
                        break;
                    case 'highest':
                        $query->orderBy('total_price', 'desc');
                        break;
                    case 'lowest':
                        $query->orderBy('total_price', 'asc');
                        break;
                    default:
                        $query->orderBy('created_at', 'desc');
                }
            } else {
                $query->orderBy('created_at', 'desc');
            }
            
            // Paginate dengan per_page yang dipilih
            $bookings = $query->paginate($perPage);
            $bookings->appends($request->all());

            return view('user.order-history', compact('bookings'));
            
        } catch (\Exception $e) {
            Log::error('Order History Error: ' . $e->getMessage());
            
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function myMessages()
    {
        // Mark all replied messages as read by user
        \App\Models\ContactMessage::where('email', Auth::user()->email)
            ->where('status', 'replied')
            ->where('is_read_by_user', false)
            ->update(['is_read_by_user' => true]);

        $messages = \App\Models\ContactMessage::where('email', Auth::user()->email)
            ->oldest()
            ->get();
        return view('user.messages', compact('messages'));
    }
}