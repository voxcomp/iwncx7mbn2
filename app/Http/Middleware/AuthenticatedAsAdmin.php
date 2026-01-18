<?php

namespace App\Http\Middleware;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Facades\Auth;

class AuthenticatedAsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (is_null(Auth::id())) {
            return \Redirect::to('/login');
        }

        if (! Auth::user()->isAdmin()) {
            return \Redirect::to('/unauthorized');
        }

        return $next($request);
    }
}
