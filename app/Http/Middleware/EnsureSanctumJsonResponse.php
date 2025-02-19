<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class EnsureSanctumJsonResponse
{
    public function handle(Request $request, Closure $next)
    {
        // Force Accept: application/json for all requests
        if (!$request->expectsJson()) {
            $request->headers->set('Accept', 'application/json');
        }

        try {
            return $next($request);
        } catch (AuthenticationException $exception) {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'Invalid API token or authentication required.'
            ], 401);
        }
    }
}
