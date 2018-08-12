<?php

namespace App\Http\Controllers;

use App\Transaction;
use App\Invoice;
use App\UserConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
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
     * Create a query string to use in url
     *
     * @return string
     */
    private function query()
    {
        $dateInit = Input::get('date_init');
        $dateEnd = Input::get('date_end');
        $description = Input::get('description');
        $accountId = Input::get('account_id');
        return "?" . implode([(isset($accountId) ? 'account_id=' . $accountId : '') . (isset($description) ? 'description=' . $description : ''), (isset($dateInit) && isset($dateEnd) ? 'date_init=' . $dateInit . '&date_end=' . $dateEnd : '')], '&');
    }

    private function getEloquentTransactions($request)
    {
        $date_init = $request->input('date_init');
        $date_end = $request->input('date_end');
        $filter_date = true;
        if (isset($request->invoice_id)) {
            $invoice = $request->account->invoices()->where('id', $request->invoice_id)->first();
            if (isset($invoice)) {
                $filter_date = false;
                $transactions = $invoice->transactions();
            } else {
                $transactions = $request->account->transactions();
            }
        } else {
            if (isset($request->account)) {
                $transactions = $request->account->transactions();
            } else {
                $transactions = Transaction::whereIn('account_id', Auth::user()->accounts->map(function ($account) {
                    return $account->id;
                }));
            }
        }
        if ($filter_date) {
            if ($date_init !== null && $date_end !== null) {
                $transactions->whereBetween('date', [$date_init, $date_end]);
            }
        }

        $request->description = iconv('UTF-8', 'ASCII//TRANSLIT', strtolower($request->description));
        $transactions = $transactions->whereRaw("replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace( lower(description), 'á','a'), 'ã','a'), 'â','a'), 'é','e'), 'ê','e'), 'í','i'),'ó','o') ,'õ','o') ,'ô','o'),'ú','u'), 'ç','c') LIKE '%{$request->description}%'")->orderBy('date')->orderBy('description');
        return $transactions;
    }

    /**
     * Get mode view of accounts
     * @param string $viewMode [card, table]
     * @return string
     */
    private function modeView($viewMode)
    {
        $viewModeConfig = UserConfig::transactionsModeView(Auth::user()->id);
        if (isset($viewMode)) {
            $viewModeConfig->value = $viewMode;
            $viewModeConfig->save();
        }
        return $viewModeConfig->value ?: 'table';
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $transactions = $this->getEloquentTransactions($request)->paginate(30)->appends(request()->input());
        return view('transactions.index', [
            'account' => $request->account,
            'transactions' => $transactions,
            'query' => $this->query(),
            'viewMode' => $this->modeView($request->view_mode)
        ]);
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
        $category_transactions = [];
        $transactions->each(function ($transaction) use (&$category_transactions) {
            $transaction->categories->each(function ($category) use (&$category_transactions) {
                $category_transactions[] = $category;
            });
        });
        return view('transactions.charts', [
            'account' => $request->account,
            'transactions' => $transactions,
            'categories' => $categories,
            'category_transactions' => $category_transactions
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

    /**
     * Add categories to listed resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function addCategories(Request $request)
    {
        $categories = explode(',', $request->categories);
        $transactions = $this->getEloquentTransactions($request)->get();
        foreach ($transactions as $transaction) {
            $transaction->updateCategories($categories);
        }
        $baseUrl = isset($request->account) ? '/account/' . $request->account->id : '';
        return redirect( $baseUrl . '/transactions' . $this->query());
    }
}
