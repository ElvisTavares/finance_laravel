<?php

namespace App\Http\Middleware;

use Closure;

class CheckTransaction
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
        $transaction = $request->account->transactions->where('id', $request->transaction)->first();
        if (!$transaction) {
            return redirect('/account/' . $request->account->id . '/transactions')->withErrors([__('transactions.not-your-transaction')]);
        }
        $request->transaction = $transaction;
        return $next($request);
    }
}
