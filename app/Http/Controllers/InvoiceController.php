<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Invoice;
use App\UserConfig;
use App\Http\Requests\InvoiceDestroyRequest;
use App\Http\Requests\InvoiceSaveRequest;

class InvoiceController extends ApplicationController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $account = Auth::user()->accounts()->findOrFail($request->account);
        return view('invoices.index', [
            'account' => $account,
            'invoices' => $account->invoices()->orderBy('debit_date')->orderBy('date_init')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $accountId)
    {
        return view('invoices.form', [
            'account' => Auth::user()->accounts()->findOrFail($accountId),
            'invoice' => new Invoice()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $accountId, $invoiceId)
    {
        return view('invoices.form', [
            'account' => Auth::user()->accounts()->findOrFail($accountId),
            'invoice' => Auth::user()->invoices($accountId)->findOrFail($invoiceId)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(InvoiceSaveRequest $request, $accountId)
    {
        $account = Auth::user()->accounts()->findOrFail($accountId);
        $invoice = new Invoice;
        $invoice->account()->associate($account);
        $invoice->description = $request->description;
        $invoice->date_init = $request->date_init;
        $invoice->date_end = $request->date_end;
        $invoice->debit_date = $request->debit_date;
        $invoice->save();
        return redirect(route('accounts.invoices', $account->id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function update(InvoiceSaveRequest $request, $accountId, $invoiceId)
    {
        $account = Auth::user()->accounts()->findOrFail($accountId);
        $invoice = Auth::user()->invoices($accountId)->findOrFail($invoiceId);
        $invoice->description = $request->description;
        $invoice->date_init = $request->date_init;
        $invoice->date_end = $request->date_end;
        $invoice->debit_date = $request->debit_date;
        $invoice->save();
        return redirect(route('accounts.invoices', $account->id));
    }

    /**
     * Confirmation to remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function confirm(Request $request, $accountId, $invoiceId)
    {
        return view('invoices.confirm', [
            'account' => Auth::user()->accounts()->findOrFail($accountId),
            'invoice' => Auth::user()->invoices($accountId)->findOrFail($invoiceId)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(InvoiceDestroyRequest $request, $accountId, $invoiceId)
    {
        $account = Auth::user()->accounts()->findOrFail($accountId);
        $invoice = Auth::user()->invoices($accountId)->findOrFail($invoiceId);
        $invoice->transactions()->delete();
        $invoice->delete();
        return redirect(route('accounts.invoices', $account->id));
    }
}
