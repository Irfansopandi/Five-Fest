<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VendorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $role =Auth::user()->role;

        // vendor staff yang nyasar ke sini akan ke redirect ke scanner
        if ($role === 'vendor_staff'){
            return redirect()->route('vendor.staff.scanner');
        }

        // Vendor dan Admin boleh masuk
        if (Auth::user()->role === 'vendor' || Auth::user()->role === 'admin') {
            return $next($request);
        }

        // Kalau role lain (misal user biasa), baru abort 403
        abort(403, 'Akses ini hanya untuk Vendor.'); 
    }
}