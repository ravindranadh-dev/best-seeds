<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Check if the user is an admin
                if (Auth::user()->role == 1 || Auth::user()->is_admin == 1) {
                    return redirect()->route('admin');
                }
                
                // For non-admin users, redirect to home or another appropriate route
                return redirect('/');
            }
        }

        return $next($request);
    }
}
