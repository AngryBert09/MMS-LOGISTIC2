<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\VendorStatusUpdated;

class SessionExpiredMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && !$request->session()->has('last_activity')) {
            $vendor = Auth::user();
            $vendor->is_online = false;
            $vendor->save();

            // Broadcast that the vendor is offline
            broadcast(new VendorStatusUpdated($vendor->id, $vendor->is_online));

            // Log out the user
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('warning', 'Session expired. You have been logged out.');
        }

        $request->session()->put('last_activity', now());

        return $next($request);
    }
}
