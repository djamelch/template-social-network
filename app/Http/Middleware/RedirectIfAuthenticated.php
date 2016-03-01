<?php
//With this file if the user is authenticated we can choose what to do with them
namespace Social\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
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
        if (Auth::guard($guard)->check()) {
            return redirect()->route('home'); //We want to do this so if the user is alredy loggedin they can't access the signup files.
        }

        return $next($request);
    }
}
