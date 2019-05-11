<?php

namespace App;

use App\Helpers\Account\Formatter;

class Account extends ApplicationModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description', 'is_credit_card', 'prefer_debit_account_id', 'user_id'
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
     * Method to return total of credit card of account.
     * @param DateTime $date
     * @return double
     */
    public function totalCredit($date){
        if (!$this->is_credit_card) throw new Exception('This is not a credit card.');
        $invoice = $this->invoices()->where('debit_date','<=', $date)->orderBy('debit_date', 'desc')->first();
        if (!isset($invoice)) return 0;
        return $invoice->total();
    }

    /**
     * Method to return total of transfers of account.
     * @param DateTime $date
     * @param bool $paid
     * @return double
     */
    private function totalTransfer($date, $paid = false)
    {
        return $this->transactionsTransfer()->paid($paid)->dateMinus($date)->sum('value');
    }

    /**
     * Method to return total of account.
     * @param DateTime $date
     * @param bool $paid
     * @return double
     */
    public function totalDebit($date, $paid = false)
    {
        return $this->transactions()->paid($paid)->dateMinus($date)->sum('value') +
                -1 * $this->totalTransfer($date, $paid);
    }

    public function format($year = null){
        return (new Formatter($this))->format($year ?: date('Y'));
    }
}