<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TwoFactorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $vendor = Auth::guard('vendor')->user();

        // If no vendor is authenticated, proceed to the next middleware
        if (!$vendor) {
            return $next($request);
        }

        // Determine if vendor is 2FA authenticated (within the last 24 hours)
        $is2faAuthenticated = $vendor->last_2fa_at
            && Carbon::parse($vendor->last_2fa_at)->gt(now()->subDay());

        $currentRouteName = $request->route()->getName();

        // If vendor is already 2FA authenticated and trying to access the 2fa.verify route, redirect them away.
        if ($currentRouteName === '2fa.verify' && $is2faAuthenticated) {
            Log::info('Vendor already 2FA authenticated, redirecting away from 2fa.verify', [
                'vendor_id'   => $vendor->id,
                'last_2fa_at' => $vendor->last_2fa_at,
            ]);
            return redirect()->route('dashboard'); // Redirect to dashboard
        }

        // If vendor is not 2FA authenticated and trying to access any route other than 2fa.verify, force them to 2fa.verify.
        if (!$is2faAuthenticated && $currentRouteName !== '2fa.verify') {
            Log::info('Vendor not 2FA authenticated, redirecting to 2fa.verify', [
                'vendor_id' => $vendor->id,
            ]);
            return redirect()->route('2fa.verify');
        }

        return $next($request);
    }
}
