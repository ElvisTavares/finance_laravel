<?php

namespace App\Http\Middleware;

use Closure;

class CheckInvoice
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
        $invoice = $request->account->invoices->where('id', $request->invoice)->first();
        if (!$invoice) {
            return redirect('/account/' . $request->account->id . '/invoices')->withErrors([__('invoices.not-your-invoice')]);
        }
        $request->invoice = $invoice;
        return $next($request);
    }
}
