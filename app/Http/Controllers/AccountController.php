<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Account;
use App\UserConfig;
use App\User;
use App\PeriodAccount;
use App\Http\Requests\AccountSaveRequest;
use App\Http\Requests\AccountDestroyRequest;

class AccountController extends ApplicationController
{
    protected $root = '/accounts';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        return view('accounts.index', [
            'accounts' => Auth::user()->formattedAccounts($request->year),
            'values' => Auth::user()->resumeAccounts($request->year),
            'avg' => Auth::user()->avgTransactions()
        ]);
    }

    private function form($account){
        return view('accounts.form', [
            'account' => $account,
            'select' => Auth::user()->listed('debitAccounts')
        ]);
    }

    public function confirm(Request $request, $id)
    {
        return view('accounts.confirm', [
            'account' => Auth::user()->accounts()->findOrFail($id)
        ]);
    }

    public function create()
    {
        return $this->form(new Account());
    }

    public function edit(Request $request, $id)
    {
        return $this->form(Auth::user()->accounts()->findOrFail($id));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AccountSaveRequest $request
     * @return Response
     */
    public function store(AccountSaveRequest $request)
    {
        $account = new Account();
        $account->user()->associate(Auth::user());
        $account->description = $request->description;
        $account->is_credit_card = $request->isCreditCard();
        if ($account->is_credit_card && isset($request->prefer_debit_account)) {
            $account->preferDebitAccount()->associate($request->prefer_debit_account);
        }
        $account->save();
        return $this->rootRedirect();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AccountSaveRequest $request
     * @param int $id
     * @return Response
     */
    public function update(AccountSaveRequest $request, $id)
    {
        $account = Auth::user()->accounts()->findOrFail($id);
        $account->description = $request->description;
        if ($account->is_credit_card && isset($request->prefer_debit_account)) {
            $account->preferDebitAccount()->associate($request->prefer_debit_account);
        }
        $account->save();
        return $this->rootRedirect();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param AccountDestroyRequest $request
     * @param int $id
     * @return Response
     */
    public function destroy(AccountDestroyRequest $request, $id)
    {
        foreach (Auth::user()->accounts as $account) {
            if ($account->prefer_debit_account_id != $id) continue;
            $account->prefer_debit_account_id = null;
            $account->save();
        }
        Auth::user()->accounts()->findOrFail($id)->delete();
        return $this->rootRedirect();
    }
}
