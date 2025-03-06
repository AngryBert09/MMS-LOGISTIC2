<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DeliveryOnly
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is logged in and has the "delivery" role
        $user = Session::get('user');

        if (!$user || $user['role'] !== 'delivery') {
            return redirect()->route('deliveries.show')->with('error', 'Access denied. Only delivery personnel can access this page.');
        }

        return $next($request);
    }
}
