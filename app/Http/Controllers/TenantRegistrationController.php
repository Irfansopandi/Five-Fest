<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TenantProfile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TenantRegistrationController extends Controller
{
    public function showForm()
    {
        return view('tenant.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8',
            'business_name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => 'tenant',
                'verification_status' => 'verified',
                'status' => 'active',
            ]);

            TenantProfile::create([
                'user_id' => $user->id,
                'business_name' => $request->business_name,
                'category' => $request->category,
            ]);

            DB::commit();

            auth()->login($user);

            return redirect()->route('home')->with('success', 'Pendaftaran tenant berhasil!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Gagal mendaftar: ' . $e->getMessage()]);
        }
    }

    public function joinEventForm($id)
    {
        $event = \App\Models\Event::findOrFail($id);
        $tenantProfile = auth()->user()->tenantProfile;
        
        $approvedCount = $event->tenants()->where('status', 'approved')->count();
        $isQuotaFull = $event->tenant_quota && $approvedCount >= $event->tenant_quota;

        return view('tenant.join-event', compact('event', 'tenantProfile', 'isQuotaFull', 'approvedCount'));
    }

    public function joinEventMultiStepForm($id)
    {
        $event = \App\Models\Event::findOrFail($id);
        $tenantProfile = auth()->user()->tenantProfile;
        
        // Check if already applied
        $existingJoin = \App\Models\EventTenant::where('event_id', $event->id)
                        ->where('tenant_id', auth()->id())
                        ->first();
                        
        if ($existingJoin && $existingJoin->status != 'rejected') {
            return redirect()->route('tenant.event.join', $event->id)->with('error', 'Anda sudah mengajukan pendaftaran untuk event ini.');
        }
        
        // Check quota
        $approvedCount = $event->tenants()->where('status', 'approved')->count();
        if ($event->tenant_quota && $approvedCount >= $event->tenant_quota) {
            return redirect()->route('tenant.event.join', $event->id)->with('error', 'Kuota tenant untuk event ini sudah penuh.');
        }

        return view('tenant.join-event-form', compact('event', 'tenantProfile'));
    }

    public function joinEventStore(Request $request, $id)
    {
        $event = \App\Models\Event::findOrFail($id);
        $approvedCount = $event->tenants()->where('status', 'approved')->count();
        
        if ($event->tenant_quota && $approvedCount >= $event->tenant_quota) {
            return back()->with('error', 'Mohon maaf, kuota tenant untuk event ini sudah penuh.');
        }

        $request->validate([
            'category' => 'required|string|max:255',
            'booth_photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $tenantProfile = auth()->user()->tenantProfile;
        
        if ($tenantProfile) {
            $tenantProfile->update(['category' => $request->category]);
        }

        $path = $request->file('booth_photo')->store('event_tenants', 'public');

        \App\Models\EventTenant::updateOrCreate(
            [
                'event_id' => $id,
                'tenant_id' => auth()->id(),
            ],
            [
                'business_name' => $tenantProfile ? $tenantProfile->business_name : auth()->user()->name,
                'booth_photo' => $path,
                'status' => 'pending',
            ]
        );

        return redirect()->route('event.detail', $id)->with('success', 'Berhasil mendaftar stand di event ini. Menunggu persetujuan vendor.');
    }
}
