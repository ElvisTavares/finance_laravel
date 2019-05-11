<?php

namespace App\Http\Controllers;

use App\Transaction;
use App\Invoice;
use App\Helpers\Invoice\Invoice as VirtualInvoice;
use App\UserConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class TransactionController extends ApplicationController
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

    private function getInvoiceByRequest(Request $request, $invoiceId = null){
        $invoiceId = isset($invoiceId) ? $invoiceId : $request->invoice_id;
        return isset($invoiceId) ? VirtualInvoice::get($invoiceId) : null;
    }

    private function getYearByRequest(Request $request, $invoice = null){
        if ($invoice) return $invoice->getYear();
        if ($request->date_init && $request->date_end)
            return strtok($request->date_init , '-');
        return date('Y');
    }

    private function getTransactionsByRequest(Request $request, $account, $invoice = null){
        $transactions = ($invoice) ? $invoice->transactions() : $account->transactions();
        if ($request->description)
            $transactions = $transactions->description($request->description);
        if ($request->date_init && $request->date_end)
            $transactions = $transactions->betweenDates($request->date_init, $request->date_end);
        return $transactions;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $accountId, $invoiceId = null)
    {
        $account = Auth::user()->accounts()->findOrFail($accountId);
        $invoice = $this->getInvoiceByRequest($request, $invoiceId);
        $transactions = $this->getTransactionsByRequest($request, $account, $invoice);
        $year = $this->getYearByRequest($request, $invoice);
        return view('transactions.index', [
            'account' => $account->format($year),
            'invoice' => $invoice,
            'transactions' => $transactions->paginate(30)->appends($request->all())
        ]);
    }

    /**
     * Add categories to listed resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function category(Request $request, $accountId, $invoiceId = null)
    {
        $account = Auth::user()->accounts()->findOrFail($accountId);
        $invoice = $this->getInvoiceByRequest($request, $invoiceId);
        $transactions = $this->getTransactionsByRequest($request, $account, $invoice);
        $categories = explode(',', $request->categories);
        foreach ($transactions->get() as $transaction)
            $transaction->updateCategories($categories);
        $route = route('accounts.transactions', [
            'account' => $account,
            'invoice_id' => $invoice ? $invoice->getId() : null,
            http_build_query($request->except('invoice_id')
        )]);
        return redirect($route);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function charts(Request $request)
    {
        $transactions = $this->getEloquentTransactions($request)->get();
        $categories = Auth::user()->categories;
        $categoryTransactions = [];
        $transactions->each(function ($transaction) use (&$categoryTransactions) {
            $transaction->categories->each(function ($category) use (&$categoryTransactions) {
                $categoryTransactions[] = $category;
            });
        });
        return view('transactions.charts', [
            'account' => $request->account,
            'transactions' => $transactions,
            'categories' => $categories,
            'category_transactions' => $categoryTransactions
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $parameters = [
            'action' => __('common.add'),
            'query' => $this->query(),
            'account' => null,
            'accounts' => null
        ];
        if (isset($request->account) && $request->account != '-1') {
            $parameters['account'] = Auth::user()->accounts->find($request->account);
            $parameters['accounts'] = Auth::user()->accounts->where('id', '<>', $request->account);
        }
        return view('transactions.form', $parameters);
    }


    private function valid($request)
    {
        return Validator::make($request->all(), [
            'description' => 'required|min:5|max:100',
            'date' => 'required',
            'value' => 'required'
        ], [
            'description.required' => __('common.description-required'),
            'description.min' => __('common.description-min-5'),
            'description.max' => __('common.description-max-100'),
            'date.required' => __('common.date-required'),
            'value.required' => __('common.value-required')
        ])->after(function ($validator) use ($request) {
            if ($request->account->is_credit_card) {
                if ($request->invoice_id == null) {
                    $validator->errors()->add('invoice_id', __('transactions.need-set-invoice'));
                } elseif ($request->invoice_id == -1) {
                    if ($request->invoice_description == null || strlen($request->invoice_description) < 5) {
                        $validator->errors()->add('invoice_id', __('transactions.invoice-description-min-5'));
                    } elseif ($request->invoice_date_init == null) {
                        $validator->errors()->add('invoice_id', __('transactions.invoice-date-init-required'));
                    }elseif ($request->invoice_date_end == null) {
                        $validator->errors()->add('invoice_id', __('transactions.invoice-date-end-required'));
                    } elseif ($request->invoice_debit_date == null) {
                        $validator->errors()->add('invoice_id', __('transactions.invoice-debit-date-required'));
                    } else {
                        $invoice = new Invoice;
                        $invoice->account()->associate($request->account);
                        $invoice->description = $request->invoice_description;
                        $invoice->date_init = $request->invoice_date_init;
                        $invoice->date_end = $request->invoice_date_end;
                        $invoice->debit_date = $request->invoice_debit_date;
                        $invoice->save();
                        $request->invoice = $invoice;
                    }
                } else {
                    $request->invoice = Auth::user()->accounts->find($request->invoice);
                }
            }
        })->validate();
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
        $transaction = new Transaction;
        $transaction->account()->associate($request->account);
        $transaction->updateByRequest($request);
        return redirect('/account/' . $request->account->id . '/transactions' . $this->query());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        return view('transactions.form', [
            'action' => __('common.edit'),
            'account' => $request->account,
            'transaction' => $request->transaction,
            'query' => $this->query(),
            'accounts' => Auth::user()->accounts
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->valid($request);
        $transaction = $request->transaction;
        $transaction->updateByRequest($request);
        return redirect('/account/' . $request->account->id . '/transactions' . $this->query());
    }

    /**
     * Confirmation do remove the specified resource from storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function confirm(Request $request)
    {
        return view('transactions.confirm', [
            'account' => $request->account,
            'transaction' => $request->transaction
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
        $request->transaction->delete();
        return redirect('/account/' . $request->account->id . '/transactions' . $this->query());
    }

    /**
     * Show view to repeat the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function repeat(Request $request)
    {
        return view('transactions.repeat', [
            'account' => $request->account,
            'transaction' => $request->transaction
        ]);
    }

    /**
     * Repeat the specified resource from storage.
     *
     * @param Request $request
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function confirmRepeat(Request $request, $id)
    {
        for ($i = 0; $i < $request->times; $i++) {
            $transaction = new Transaction;
            $requestTransaction = $request->transaction;
            $newDate = strtotime("+" . ($i + 1) . " month", strtotime($requestTransaction->date));
            $transaction->account()->associate($requestTransaction->account);
            $transaction->date = date("Y-m-d\TH:i:s", $newDate);
            $transaction->description = $requestTransaction->description;
            $transaction->value = $requestTransaction->value;
            $transaction->paid = false;
            if ($request->account->is_credit_card && isset($requestTransaction->invoice_id)) {
                $transaction->invoice->associate($requestTransaction->invoice);
            }
            $transaction->save();
        }
        return redirect('/account/' . $request->account->id . '/transactions' . $this->query());
    }
}
