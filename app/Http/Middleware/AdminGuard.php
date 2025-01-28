<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminGuard
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('admin')->check() && auth()->user()->user_type === 'admin') {
            return $next($request);
        }

        return redirect('/admin/login')->with('error', 'Access denied! Admins only.');
    }
}
