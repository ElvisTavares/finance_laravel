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

class AccountController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('account', ['except' => ['index', 'create', 'store']]);
    }

    /**
     * Get mode view of accounts
     * @param string $viewMode [card, table]
     * @return string
     */
    private function modeView($viewMode)
    {
        $viewModeConfig = UserConfig::accountsModeView(Auth::user()->id);
        if (isset($viewMode)) {
            $viewModeConfig->value = $viewMode;
            $viewModeConfig->save();
        }
        return $viewModeConfig->value ?: 'table';
    }

    /**
     * @param Collection $accounts
     * @param PeriodAccount $periodAccount
     * @return \stdClass
     */
    private function values($accounts, PeriodAccount $periodAccount)
    {
        $values = new \stdClass;
        $values->paid = [];
        $values->nonPaid = [];
        foreach ($accounts as $account) {
            $values->paid[$account->id] = [];
            $values->nonPaid[$account->id] = [];
            for ($month = 0; $month < 12; $month++) {
                $dateEnd = $periodAccount->months[$month]->end;
                $values->nonPaid[$account->id][$month] = $account->total($dateEnd);
                $values->paid[$account->id][$month] = $account->total($dateEnd, true);
            }
        }
        return $values;
    }

    /**
     * @param Collection $accounts
     * @param \stdClass $values
     * @return \stdClass
     */
    private function totals($accounts, $values)
    {
        $result = new \stdClass;
        $result->paid = [];
        $result->nonPaid = [];
        for ($month = 0; $month < 12; $month++) {
            $result->paid[$month] = 0;
            $result->nonPaid[$month] = 0;
            foreach ($accounts as $account) {
                $result->paid[$month] += $values->paid[$account->id][$month];
                $result->nonPaid[$month] += $values->nonPaid[$account->id][$month];
            }
        }
        return $result;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $viewMode = $this->modeView($request->view_mode);
        $periodAccount = new PeriodAccount($request->year, $viewMode);
        $accounts = Auth::user()->accounts()->get();
        $values = $this->values($accounts, $periodAccount);
        $totals = $this->totals($accounts, $values);
        $formatedAccounts = [];
        foreach ($accounts as $account) {
            $formatedAccounts[] = $account->format($periodAccount);
        }
        return view('accounts.index', [
            'viewMode' => $viewMode,
            'accounts' => $formatedAccounts,
            'period' => $periodAccount,
            'values' => $values,
            'totals' => $totals,
            'avg' => Auth::user()->avgTransactions()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('accounts.form', [
            'selectAccounts' => Auth::user()->listed('debitAccounts'),
            'action' => __('common.add')
        ]);
    }

    /**
     * @param $request
     * @return mixed
     */
    private function valid($request)
    {
        return Validator::make($request->all(), [
            'description' => 'required|min:5|max:50'
        ], [
            'description.required' => __('common.description-required'),
            'description.min' => __('common.description-min-5'),
            'description.max' => __('common.description-max-50')
        ])->after(function ($validator) use ($request) {
            if ($request->is_credit_card && $request->prefer_debit_account_id != null) {
                $request->prefer_debit_account = Auth::user()->accounts->find($request->prefer_debit_account_id);
                if (!$request->prefer_debit_account) {
                    $validator->errors()->add('id', __('accounts.not-your-account'));
                }
            }
        })->validate();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->valid($request);
        $account = new Account;
        $account->user()->associate(Auth::user());
        $account->is_credit_card = $request->is_credit_card == null ? false : $request->is_credit_card;
        $account->updateByRequest($request);
        return redirect('/accounts');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function edit(Request $request, $id)
    {
        return view('accounts.form', [
            'account' => $request->account,
            'selectAccounts' => Auth::user()->listed('debitAccounts'),
            'action' => __('common.edit')
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $this->valid($request);
        $account = $request->account;
        $account->updateByRequest($request);
        return redirect('/accounts');
    }

    /**
     * Confirmation do remove the specified resource from storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function confirm(Request $request, $id)
    {
        return view('accounts.confirm', [
            'account' => $request->account
        ]);
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
        foreach (Auth::user()->accounts as $account) {
            if ($account->prefer_debit_account_id == $id) {
                $account->prefer_debit_account_id = null;
                $account->save();
            }
        }
        $request->account->delete();
        return redirect('/accounts');
    }
}
