<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthenticatedAsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
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
