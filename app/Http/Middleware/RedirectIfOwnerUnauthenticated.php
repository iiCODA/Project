<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfOwnerUnauthenticated
{
    public function handle($request, Closure $next)
    {
        // Check if the user is not authenticated with the owner guard
        if (!Auth::guard('owner')->check()) {
            // Redirect to the owner login page
            return redirect()->route('owner.login');
        }

        return $next($request);
    }
}
