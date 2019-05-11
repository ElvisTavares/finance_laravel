<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Invoice;
use App\UserConfig;

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
    public function create(Request $request)
    {
        return view('invoices.form', [
            'action' => __('common.add'),
            'account' => $request->account
        ]);
    }

    /**
     * @param $request
     * @return mixed
     */
    private function valid($request)
    {
        return Validator::make($request->all(), [
            'description' => 'required|min:5|max:100',
            'date_init' => 'required',
            'date_end' => 'required',
            'debit_date' => 'required'
        ], [
            'description.required' => __('common.description-required'),
            'description.min' => __('common.description-min-5'),
            'description.max' => __('common.description-max-100'),
            'date_init.required' => __('common.date-required'),
            'date_end.required' => __('common.date-required'),
            'debit_date.required' => __('common.date-required'),
        ])->validate();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->valid($request);
        $invoice = new Invoice;
        $invoice->account()->associate($request->account);
        $invoice->description = $request->description;
        $invoice->date_init = $request->date_init;
        $invoice->date_end = $request->date_end;
        $invoice->debit_date = $request->debit_date;
        $invoice->save();
        return redirect('/account/' . $request->account->id . '/invoices/');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        return view('invoices.form', [
            'action' => __('common.edit'),
            'account' => $request->account,
            'invoice' => $request->invoice
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->valid($request);
        $request->invoice->description = $request->description;
        $request->invoice->date_init = $request->date_init;
        $request->invoice->date_end = $request->date_end;
        $request->invoice->debit_date = $request->debit_date;
        $request->invoice->save();
        return redirect('/account/' . $request->account->id . '/invoices');
    }

    /**
     * Confirmation to remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function confirm(Request $request)
    {
        return view('invoices.confirm', [
            'account' => $request->account,
            'invoice' => $request->invoice
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $request->invoice->transactions()->delete();
        $request->invoice->delete();
        return redirect('/account/' . $request->account->id . '/invoices');
    }
}
