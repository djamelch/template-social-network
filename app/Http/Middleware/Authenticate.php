<?php
//This file authenticates the user
namespace Social\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->guest()) { //Checks the user is a guest
            if ($request->ajax() || $request->wantsJson()) { //if theya are it does this ajax request ehich returns a 401 unauthorized response.
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('auth.signin'); //Otherwise it will redirect to guest auth.signin
            }
        }

        return $next($request);
    }
}
