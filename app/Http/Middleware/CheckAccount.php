<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckAccount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $account = Auth::user()->accounts->where('id', $request->account)->first();
        if (!$account) {
            return redirect('/accounts')->withErrors([__('accounts.not-your-account')]);
        }
        $request->account = $account;
        return $next($request);
    }
}
