<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Restrict2faVerifyAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Get the authenticated vendor
        $vendor = Auth::guard('vendor')->user();

        // If no vendor is authenticated, allow access (this middleware is only for authenticated users)
        if (!$vendor) {
            return $next($request);
        }

        // Check if the vendor has completed 2FA within the last 24 hours
        $is2faAuthenticated = $vendor->last_2fa_at
            && Carbon::parse($vendor->last_2fa_at)->gt(now()->subDay());

        // If the vendor has already completed 2FA, deny access to the 2fa/verify route
        if ($is2faAuthenticated) {
            Log::info('Vendor already 2FA authenticated, denying access to 2fa/verify', [
                'vendor_id'   => $vendor->id,
                'last_2fa_at' => $vendor->last_2fa_at,
            ]);
            return redirect()->route('dashboard'); // Redirect to dashboard or another appropriate route
        }

        // If the vendor has not completed 2FA, allow access to the 2fa/verify route
        return $next($request);
    }
}
