<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

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
