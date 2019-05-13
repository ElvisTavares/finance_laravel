<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class RootUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::user()->isAdmin())
            return redirect('/home');
        return $next($request);
    }
}
