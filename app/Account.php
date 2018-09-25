<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description', 'is_credit_card', 'prefer_debit_account_id'
    ];

    /**
     * Method to get user of account
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Method to get prefer debit account of account
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function preferDebitAccount()
    {
        return $this->belongsTo('App\Account', 'prefer_debit_account_id');
    }

    /**
     * Method to get transactions of account
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany('App\Transaction');
    }

    /**
     * Method to get transactions of account which are trenfers
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactionsTransfer()
    {
        return $this->hasMany('App\Transaction', 'account_id_transfer');
    }

    /**
     * Method to get invoices of account
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invoices()
    {
        return $this->hasMany('App\Invoice');
    }

    /**
     * Method to return total of account.
     * @param DateTime $maxDate
     * @param bool $paid
     * @return double
     */
    public function total($maxDate, $paid = false)
    {
        if ($this->is_credit_card){
            $invoice = $this->invoices()->where('debit_date','<=', $maxDate)->orderBy('debit_date', 'desc')->first();
            if (!isset($invoice)) {
                return 0;
            }
            if ($paid && $invoice->total() > 0){
                return $invoice->total();
            } elseif (!$paid && $invoice->total() < 0){
                return $invoice->total();
            } else {
                return 0;
            }
        } else {
            return $this->transactions()->where('paid', $paid)->where('date', '<=', $maxDate)->sum('value') +
                -1 * $this->totalTransfer($maxDate, $paid);
        }
    }

    /**
     * @param \stdClass $period
     * @return \stdClass
     */
    public function format($period = null)
    {
        $formattedAccount = new \stdClass;
        $formattedAccount->id = $this->id;
        $formattedAccount->description = $this->description;
        $formattedAccount->is_credit_card = $this->is_credit_card;
        $formattedAccount->invoices = [];
        if ($this->is_credit_card) {
            for ($month = 0; $month < 12; $month++) {
                $invoice = null;
                if (isset($period)) {
                    $invoices = $this->invoices()->whereBetween('debit_date', [$period->months[$month]->init, $period->months[$month]->end])->get();
                    $invoice = $this->virtualInvoice($invoices);
                }
                $formattedAccount->invoices[] = $invoice;
            }
        }
        return $formattedAccount;
    }

    private function virtualInvoice($invoices){
        $virtualInvoice = [];
        foreach($invoices as $invoice){
            $virtualInvoice['id'] = isset($virtualInvoice['id']) ? $virtualInvoice['id'].';' . $invoice->id : $invoice->id;
            $virtualInvoice['debit_date'] = $invoice->debit_date;
            $virtualInvoice['date_init'] = !isset($virtualInvoice['date_init']) ? $invoice->date_init : $virtualInvoice['date_init'];
            if ($virtualInvoice['date_init'] > $invoice->date_init){
                $virtualInvoice['date_init'] = $invoice->date_init;
            }
            $virtualInvoice['date_end'] = !isset($virtualInvoice['date_end']) ? $invoice->date_end : $virtualInvoice['date_end'];
            if ($virtualInvoice['date_end'] > $invoice->date_end){
                $virtualInvoice['date_end'] = $invoice->date_end;
            }
        }
        if (isset($virtualInvoice['id'])) {
            $virtualInvoice['id'] = myEncrypt($virtualInvoice['id']);
        }
        return $virtualInvoice == [] ? null : (object) $virtualInvoice;
    }

    /**
     * Method to update model by request
     * @param Request $request
     * @return void
     */
    public function updateByRequest($request){
        $this->description = $request->description;
        if ($this->is_credit_card && isset($request->prefer_debit_account)) {
            $this->preferDebitAccount()->associate($request->prefer_debit_account);
        }
        $this->save();
    }

    /**
     * Method to return total of transfers of account.
     * @param DateTime $maxDate
     * @param bool $paid
     * @return double
     */
    private function totalTransfer($maxDate, $paid = false)
    {
        return $this->transactionsTransfer()->where('paid', $paid)->where('date', '<=', $maxDate)->sum('value');
    }


    /**
     * Function to get list of invoice's accounts
     *
     * @return array
     */
    public function listInvoices($create = true)
    {
        $selectInovice = [];
        if ($create) {
            $selectInovice[-1] = __('common.select');
        }
        foreach ($this->invoices()->get() as $account) {
            $selectInovice[myEncrypt($account->id)] = $account->id . "/" . $account->description;
        }
        return $selectInovice;
    }

    /**
     * Scope a query to only non credit card.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNonCreditCard($query)
    {
        return $query->where('is_credit_card', false);
    }

    /**
     * Scope a query to only credit card.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCreditCards($query)
    {
        return $query->where('is_credit_card', true);
    }
}