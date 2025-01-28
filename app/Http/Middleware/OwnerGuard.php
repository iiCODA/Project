<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class OwnerGuard
{
    public function handle($request, Closure $next)
    {
        if (!Auth::guard('owner')->check()) {
            // Redirect unauthenticated users of the owner guard to the owner login page
            return redirect()->route('owner.login');
        }

        return $next($request);
    }
}
