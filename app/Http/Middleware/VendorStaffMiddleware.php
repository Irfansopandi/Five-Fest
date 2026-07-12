<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VendorStaffMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        
        if(!Auth::check()){
            return redirect()->route('login');
        }

        $role = Auth::user()->role;

        // hanya vendor dan staff vendor yang boleh akses route ini
        if ($role === 'vendor' || $role === 'vendor_staff') {
            return $next($request);
            
        }

        abort(403, 'Akses hanya untuk vendor dan staff vendor. ');
            
    }
}
            