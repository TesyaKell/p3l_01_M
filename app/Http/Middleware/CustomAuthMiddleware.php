<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Helper\Helper;

class CustomAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string|null ...$guards
     * @return mixed
     */
    public function handle($request, Closure $next, ...$guards)
    {
        // If no guards are provided, use the default guards from the auth config
        if (empty($guards)) {
            $guards = array_keys(config('auth.guards'));
        }

        // Check if the user is logged in for any of the specified guards
        foreach ($guards as $guard) {
            if (Helper::isLoggedIn($guard)) {
                // Proceed with the request if logged in for any guard
                return $next($request);
            }
        }

        // Redirect to login page or return an unauthorized response
        return redirect()->route('jabatan')->with('error', 'You do not have access to this page.');
    }
}
