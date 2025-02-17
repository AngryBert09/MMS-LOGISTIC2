<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class TwoFactorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $vendor = Auth::guard('vendor')->user(); // Get the authenticated vendor

        if (!$vendor) {
            Log::warning('No authenticated vendor found');
            return redirect()->route('login');
        }

        // ✅ Check if 2FA has already been authenticated within the last 24 hours
        if ($vendor->last_2fa_at && Carbon::parse($vendor->last_2fa_at)->gt(now()->subDay())) {
            Log::info('2FA already authenticated today', ['vendor_id' => $vendor->id, 'last_2fa_at' => $vendor->last_2fa_at]);
            return $next($request); // Allow access if 2FA is still valid
        }

        // Check if a valid 2FA code exists in the database
        $twoFactorRecord = DB::table('vendor_2fa_codes')
            ->where('vendor_id', $vendor->id)
            ->where('expires_at', '>', now())
            ->first();

        if (!$twoFactorRecord) {
            Log::info('No valid 2FA code found for vendor', ['vendor_id' => $vendor->id]);
            return redirect()->route('2fa.verify');
        }

        Log::info('2FA code exists, proceeding with verification', ['vendor_id' => $vendor->id]);

        return $next($request);
    }
}
