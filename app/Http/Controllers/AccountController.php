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
            'accounts' => $this->user->formattedAccounts($request->year),
            'values' => $this->user->resumeAccounts($request->year),
            'avg' => $this->user->avgTransactions()
        ]);
    }

    private function form($account){
        return view('accounts.form', [
            'account' => $account,
            'action' => $account->id ? __('common.add') : __('common.edit'),
            'select' => $this->user->listed('debitAccounts')
        ]);
    }

    public function confirm(Request $request, $id)
    {
        return view('accounts.confirm', [
            'account' => $request->account
        ]);
    }

    public function create()
    {
        return $this->form(new Account());
    }

    public function edit(Request $request, $id)
    {
        return $this->form($request->account);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(AccountSaveRequest $request)
    {
        $request->user = $this->user;
        $account = new Account();
        $account->user()->associate($this->user);
        $account->is_credit_card = $request->is_credit_card == null ? false : $request->is_credit_card;
        $account->updateByRequest($request);
        return $this->rootRedirect();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(AccountSaveRequest $request, $id)
    {
        $request->user = $this->user;
        $account = $request->account;
        $account->updateByRequest($request);
        return $this->rootRedirect();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        foreach ($this->user->accounts as $account) {
            if ($account->prefer_debit_account_id == $id) {
                $account->prefer_debit_account_id = null;
                $account->save();
            }
        }
        $request->account->delete();
        return $this->rootRedirect();
    }
}
