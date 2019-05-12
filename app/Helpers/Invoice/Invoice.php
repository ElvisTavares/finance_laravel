<?php

namespace App\Helpers\Invoice;

use Auth;
use App\Account;
use App\Transaction;
use App\Invoice as MainInvoice;

class Invoice
{
    public $account;
    private $invoices;


    public function __construct($account, $invoices)
    {
        $this->account = $account;
        $this->invoices = $invoices;
    }

    public function getId(){
        return short_encode($this->account->id.";".implode(',', array_map(function($invoice){
            return $invoice['id'];
        }, $this->invoices)));
    }

    public function getFirstId(){
        $filtered = array_filter($this->invoices);
        if (empty($filtered)) return null;
        return $filtered[0]->id;
    }

    public static function get($id){
        $decode = short_decode($id);
        $accountInvoicesIds = explode(";", $decode);
        $account = Auth::user()->accounts()->findOrFail($accountInvoicesIds[0]);
        $invoicesId = explode(",", $accountInvoicesIds[1]);
        $invoices = [];
        foreach($invoicesId as $invoiceId)
            $invoices[] = $account->invoices()->findOrFail($invoiceId);
        return (new self($account, $invoices));
    }

    public function getYear(){
        $filtered = array_filter($this->invoices);
        if (empty($filtered)) return date('Y');
        return strtok($filtered[0]->debit_date, '-');;
    }

    /**
     * Get transactions of invoice
     */
    public function transactions()
    {
        $transactionIds = [];
        foreach ($this->invoices as $invoice)
            foreach ($invoice->transactions as $transaction)
                $transactionIds[] = $transaction->id;
        return Transaction::whereIn('id', $transactionIds);
    }

    /**
     * Get total of invoice
     *
     * @return double
     */
    public function total(){
        $total = 0;
        foreach ($this->invoices as $invoice)
            $total += $invoice->total();
        return $total;
    }

    public function description(){
        $description = '';
        foreach (array_filter($this->invoices) as $invoice) {
            if ($description != '') $description .= "; ";
            $description .= $invoice['id']."/".$invoice['description'];
        }
        return "[". $description. "]";
    }
}