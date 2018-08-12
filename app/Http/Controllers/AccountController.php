<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Account;
use App\UserConfig;
use App\Transaction;

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
     * @param integer $actualYear
     * @return array
     */
    private function yearsList($actualYear)
    {
        $years = [];
        $systemYear = date('Y');
        $yearDiff = ($systemYear - $actualYear);
        $minYear = 10 - $yearDiff;
        if ($minYear <= 0) {
            $minYear = 1;
        }
        for ($year = $actualYear - $minYear; $year <= $actualYear; $year++) {
            $years[] = $year;
        }
        if ($actualYear < $systemYear) {
            for ($year = $actualYear + 1; $year <= $systemYear; $year++) {
                $years[] = $year;
            }
        }
        return $years;
    }

    /**
     * @param integer $actualYear
     * @return array
     */
    private function months($actualYear)
    {
        $months = [];
        for ($i = 0; $i < 12; $i++) {
            $months[$i] = new \stdClass;
            $months[$i]->init = date($actualYear . '-' . ($i + 1) . '-1');
            $months[$i]->end = date('Y-m-t', strtotime($months[$i]->init));
        }
        return $months;
    }

    /**
     * @param integer $year
     * @param string $viewMode
     * @return \stdClass
     */
    private function period($year, $viewMode)
    {
        $result = new \stdClass;
        $result->actual = new \stdClass;
        $result->actual->month = date('n') - 1;
        if ($viewMode == 'table') {
            $result->actual->year = isset($year) ? $year : date('Y');
            $years = $this->yearsList($result->actual->year);
        } else { // On card view just show actual year
            $result->actual->year = date('Y');
            $years = [$result->actual->year];
        }
        $result->years = $years;
        $result->months = $this->months($result->actual->year);
        return $result;
    }

    /**
     * @param Collection $accounts
     * @param \stdClass $period
     * @return \stdClass
     */
    private function values($accounts, $period)
    {
        $values = new \stdClass;
        $values->paid = [];
        $values->nonPaid = [];
        foreach ($accounts as $account) {
            $values->paid[$account->id] = [];
            $values->nonPaid[$account->id] = [];
            for ($month = 0; $month < 12; $month++) {
                $dateEnd = $period->months[$month]->end;
                $values->nonPaid[$account->id][$month] = $account->total($dateEnd);
                $values->paid[$account->id][$month] = $account->total($dateEnd, true);
            }
            foreach ($account->creditCards() as $creditCard) {
                $values->paid[$creditCard->id] = [];
                $values->nonPaid[$creditCard->id] = [];
                $creditCardFormatted = $creditCard->format($period);
                for ($i = 0; $i < 12; $i++) {
                    $values->paid[$creditCard->id][$i] = 0;
                    $values->nonPaid[$creditCard->id][$i] = 0;
                    $invoice = $creditCardFormatted->invoices[$i];
                    if (isset($invoice)) {
                        $value = $invoice->total();
                        $values->paid[$creditCard->id][$i] = $value < 0 ? $value : 0;
                        $values->nonPaid[$creditCard->id][$i] = $value > 0 ? $value : 0;
                    }
                }
            }
        }
        return $values;
    }

    /**
     * @param Collection $accounts
     * @param \stdClass $period
     * @return array
     */
    private function accounts($accounts, $period)
    {
        $result = [];
        foreach ($accounts as $account) {
            $result[] = $account->format($period);
        }
        return $result;
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
     * @return \stdClass
     */
    private function avg()
    {
        $result = new \stdClass;
        $result->max = Transaction::ofUser(Auth::user())->positive();
        $result->maxDivision = count($result->max->get());
        if ($result->maxDivision == 0) {
            $result->maxDivision = 1;
        }
        $result->max = $result->max->sum('value') / $result->maxDivision;
        $result->min = Transaction::ofUser(Auth::user())->negative();
        $result->minDivision = count($result->min->get());
        if ($result->minDivision == 0) {
            $result->minDivision = 1;
        }
        $result->min = $result->min->sum('value') / $result->minDivision;
        $result->avg = $result->max + $result->min;
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
        $period = $this->period($request->year, $viewMode);
        $accounts = Auth::user()->accounts()->get();
        $values = $this->values($accounts, $period);
        $totals = $this->totals($accounts, $values);
        return view('accounts.index', [
            'viewMode' => $viewMode,
            'accounts' => $this->accounts($accounts, $period),
            'period' => $period,
            'values' => $values,
            'totals' => $totals,
            'avg' => $this->avg()
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
            'selectAccounts' => Auth::user()->listNonCreditCard(),
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
            'selectAccounts' => Auth::user()->listNonCreditCard(),
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
