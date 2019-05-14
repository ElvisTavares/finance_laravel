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
use App\Http\Requests\TransactionSaveRequest;
//use App\Http\Requests\AccountDestroyRequest;

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
        return $transactions->orderBy('date', 'DESC');
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
     * Repeat the specified resource from storage.
     *
     * @param Request $request
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function repeat(Request $request, $accountId, $transactionId)
    {
        $account = Auth::user()->accounts()->findOrFail($accountId);
        $transaction = Auth::user()->transactions($accountId)->findOrFail($transactionId);
        for ($months = 0; $months < $request->times; $months++) {
            $new = $transaction->clone();
            $new->paid = false;
            $new->date = add_month_to_date($months + 1, $transaction->date);
            $new->save();
        }
        $route = route('accounts.transactions', [
            'account' => $account,
            'invoice_id' => $transaction->invoice ? $transaction->invoice->getId() : null,
            http_build_query($request->except('invoice_id')
        )]);
        return redirect($route);
    }

    public function repeatView(Request $request, $accountId, $transactionId){
        return view('transactions.repeat', [
            'account' => Auth::user()->accounts()->findOrFail($accountId),
            'transaction' => Auth::user()->transactions($accountId)->findOrFail($transactionId)
        ]);
    }

    private function selectInvoices($account){
        $invoices = [];
        foreach (Auth::user()->invoices($account->id)->get() as $invoice){
            $invoices[$invoice->getId()] = $invoice->id . " / " . $invoice->description;
        }
        return $invoices;
    }

    private function selectAccountsTransfer($accountId){
        $otherAccounts = Auth::user()->accounts()->where('id', '<>', $accountId)->get();
        $accounts = [
            -1 => __('common.select')
        ];
        foreach ($otherAccounts as $account)
            $accounts[$account->id] = $account->id . " / " . $account->description;
        return $accounts;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $accountId, $virtualInvoiceId = null)
    {
        $invoiceId = $virtualInvoiceId ? VirtualInvoice::get($virtualInvoiceId)->getFirstId() : null;
        $account = Auth::user()->accounts()->findOrFail($accountId);
        return view('transactions.form', [
            'account' => $account,
            'transaction' => new Transaction([
                'invoice_id' => $invoiceId
            ]),
            'invoices' => $this->selectInvoices($account),
            'accounts' => $this->selectAccountsTransfer($accountId)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(TransactionSaveRequest $request, $accountId)
    {
        $account = Auth::user()->accounts()->findOrFail($accountId);
        $transaction = new Transaction;
        $transaction->account()->associate($account);
        if ($account->is_credit_card){
            $virtualInvoice = VirtualInvoice::get($request->invoice_id);
            $invoice = Auth::user()->invoices($accountId)->findOrFail($virtualInvoice->getFirstId());
            $transaction->invoice()->associate($invoice);
        }
        if ($request->isTransfer()){
            $accountTransfer = Auth::user()->accounts()->findOrFail($request->account_id_transfer);
            $transaction->accountTransfer()->associate($accountTransfer);
        }
        $transaction->date = $request->date;
        $transaction->paid = $request->isPaid($account);
        $transaction->description = $request->description;
        $transaction->value = $request->value * ($request->isCredit() ? -1 : 1);
        $transaction->save();
        $route = route('accounts.transactions', [
            'account' => $account,
            'invoice_id' => $transaction->invoice ? $transaction->invoice->getId() : null,
            http_build_query($request->except('invoice_id')
        )]);
        return redirect($route);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $accountId, $transactionId)
    {
        $account = Auth::user()->accounts()->findOrFail($accountId);
        return view('transactions.form', [
            'account' => $account,
            'transaction' => Auth::user()->transactions($accountId)->findOrFail($transactionId),
            'invoices' => $this->selectInvoices($account),
            'accounts' => $this->selectAccountsTransfer($accountId)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function update(TransactionSaveRequest $request, $accountId, $transactionId)
    {
        $account = Auth::user()->accounts()->findOrFail($accountId);
        $transaction = Auth::user()->transactions($accountId)->findOrFail($transactionId);
        if ($account->is_credit_card){
            $virtualInvoice = VirtualInvoice::get($request->invoice_id);
            $invoice = Auth::user()->invoices($accountId)->findOrFail($virtualInvoice->getFirstId());
            $transaction->invoice()->associate($invoice);
        }
        if ($request->isTransfer()){
            $accountTransfer = Auth::user()->accounts()->findOrFail($request->account_id_transfer);
            $transaction->accountTransfer()->associate($accountTransfer);
        }
        $transaction->date = $request->date;
        $transaction->paid = $request->isPaid($account);
        $transaction->description = $request->description;
        $transaction->value = $request->value * ($request->isCredit() ? -1 : 1);
        $transaction->save();
        $route = route('accounts.transactions', [
            'account' => $account,
            'invoice_id' => $transaction->invoice ? $transaction->invoice->getId() : null,
            http_build_query($request->except('invoice_id')
        )]);
        return redirect($route);
    }

    /**
     * Confirmation do remove the specified resource from storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function confirm(Request $request, $accountId, $transactionId)
    {
        return view('transactions.confirm', [
            'account' => Auth::user()->accounts()->findOrFail($accountId),
            'transaction' => Auth::user()->transactions($accountId)->findOrFail($transactionId)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $accountId, $transactionId)
    {
        $account = Auth::user()->accounts()->findOrFail($accountId);
        $transaction = Auth::user()->transactions($accountId)->findOrFail($transactionId);
        $invoice = $transaction->invoice;
        $transaction->delete();
        $route = route('accounts.transactions', [
            'account' => $account,
            'invoice_id' => $invoice ? $invoice->getId() : null,
            http_build_query($request->except('invoice_id')
        )]);
        return redirect($route);
    }
}
