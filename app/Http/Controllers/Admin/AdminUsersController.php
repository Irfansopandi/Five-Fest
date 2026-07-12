<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AdminUsersController extends Controller
{
    /**
     * Display a listing of users (excludes vendors).
     */
    public function index(Request $request)
    {
        // Otomatis nonaktifkan user yang tidak aktif selama 1 tahun
        $oneYearAgo = \Carbon\Carbon::now()->subYear();
        User::whereIn('role', ['user', 'admin'])
            ->where('status', 'active')
            ->where(function($q) use ($oneYearAgo) {
                $q->where('last_login', '<', $oneYearAgo)
                  ->orWhere(function($q2) use ($oneYearAgo) {
                      $q2->whereNull('last_login')->where('created_at', '<', $oneYearAgo);
                  });
            })
            ->update(['status' => 'inactive']);

        $query = User::query()->whereIn('role', ['user', 'admin']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $perPage = $request->input('per_page', 10);
        $users = $query->latest()->paginate($perPage)->appends($request->all());

        return view('admin.users.index', compact('users'));
    }

    /**
     * Display vendor verification page.
     */
    public function vendorVerification(Request $request)
    {
        $query = User::query()->where('role', 'vendor');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('verification_status')) {
            $query->where('verification_status', $request->verification_status);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $perPage = $request->input('per_page', 10);
        $vendors = $query->latest()->paginate($perPage)->appends($request->all());

        $totalVendors     = User::where('role', 'vendor')->count();
        $pendingVendors   = User::where('role', 'vendor')->where('verification_status', 'pending')->count();
        $verifiedVendors  = User::where('role', 'vendor')->where('verification_status', 'verified')->count();
        $rejectedVendors  = User::where('role', 'vendor')->where('verification_status', 'rejected')->count();

        return view('admin.users.vendor-verification', compact(
            'vendors', 'totalVendors', 'pendingVendors', 'verifiedVendors', 'rejectedVendors'
        ));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone'    => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role'     => ['required', 'in:user,admin,vendor'],
            'status'   => ['required', 'in:active,inactive'],
        ]);

        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'phone'    => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
            'status'   => $validated['status'],
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil ditambahkan!');
    }

    /**
     * Display the specified user.
     */
    public function show(Request $request, User $user)
    {
        // simpan url halaman sebelum nya
        session()->put('users.back_url', url()->previous());
        $perPage  = $request->input('per_page', 10);
        $bookings = $user->bookings()->with('event')->latest()->paginate($perPage)->appends($request->all());

        $vendorEvents = collect();
        if ($user->role === 'vendor') {
            $vendorEvents = $user->events()->withCount('bookings')->latest()->get();
        }

        return view('admin.users.show', compact('user', 'bookings', 'vendorEvents'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|unique:users,email,' . $user->id,
            'phone'  => 'nullable|string|max:20',
            'role'   => 'required|in:user,admin,vendor',
            'status' => 'required|in:active,inactive',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:8|confirmed';
            if ($request->password !== $request->password_confirmation) {
                return back()->withErrors(['password' => 'Konfirmasi password tidak cocok.'])->withInput();
            }
        }

        $validated = $request->validate($rules);

        $user->name   = $validated['name'];
        $user->email  = $validated['email'];
        $user->phone  = $validated['phone'] ?? null;
        $user->role   = $validated['role'];
        $user->status = $validated['status'];

        if ($request->filled('password')) {
            $user->password = bcrypt($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'Data pengguna berhasil diperbarui!');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }

        $user->delete();

        return back()->with('success', 'Pengguna berhasil dihapus!');
    }

    /**
     * Toggle user status (active/inactive)
     */
    public function toggleStatus(User $user)
    {
        $user->update([
            'status' => $user->status === 'active' ? 'inactive' : 'active'
        ]);

        return back()->with('success', 'Status pengguna berhasil diubah!');
    }

    /**
     * Verify a vendor account.
     */
    public function verify(Request $request, User $user)
    {
        $request->validate([
            'action'           => 'required|in:approve,reject',
            'rejection_reason' => 'required_if:action,reject|nullable|string|max:500'
        ]);

        if ($request->action === 'approve') {
            $user->update([
                'verification_status' => 'verified',
                'verified_at'         => now(),
                'rejection_reason'    => null,
                'show_verified_popup' => true,
            ]);
            return back()->with('success', 'Akun vendor berhasil diverifikasi!');
        } else {
            $user->update([
                'verification_status' => 'rejected',
                'rejection_reason'    => $request->rejection_reason,
                'verified_at'         => null
            ]);
            return back()->with('success', 'Verifikasi vendor ditolak.');
        }
    }

    /**
     * Display tenant verification page.
     */
    public function tenantVerification(Request $request)
    {
        $query = User::query()->where('role', 'tenant')->with('tenantProfile');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%')
                  ->orWhereHas('tenantProfile', function($q) use ($search) {
                      $q->where('business_name', 'like', '%' . $search . '%')
                        ->orWhere('category', 'like', '%' . $search . '%');
                  });
            });
        }


        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $perPage = $request->input('per_page', 10);
        $tenants = $query->latest()->paginate($perPage)->appends($request->all());

        $totalTenants     = User::where('role', 'tenant')->count();
        $activeTenants    = User::where('role', 'tenant')->where('status', 'active')->count();
        $inactiveTenants  = User::where('role', 'tenant')->where('status', 'inactive')->count();

        return view('admin.users.tenant-verification', compact(
            'tenants', 'totalTenants', 'activeTenants', 'inactiveTenants'
        ));
    }
}