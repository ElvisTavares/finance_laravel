<?php

namespace App\Helpers\Invoice;

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

    public function id(){
        return encrypt($this->account->id.";".implode(',', array_map(function($invoice){
            return $invoice['id'];
        }, $this->invoices)));
    }

    /**
     * Get transactions of invoice
     */
    public function transactions()
    {
        $transactionIds = [];
        foreach ($this->invoices as $invoice) {
            foreach ($invoice->transactions()->get() as $transaction) {
                $transactionIds[] = $transaction->id;
            }
        }
        return Transaction::whereIn('id', $transactionIds);
    }

    /**
     * Get total of invoice
     *
     * @return double
     */
    public function total(){
        $total = 0;
        foreach ($this->invoices as $invoice) {
            $total += $invoice->total();
        }
        return $total;
    }

    public function encryptedId(){
        $ids = '';
        foreach ($this->invoices as $invoice) {
            if ($ids != ''){
                $ids .= ";";
            }
            $ids .= $invoice->id;
        }
        return sslEncrypt($ids);
    }

    private function decryptInvoices($encryptedIds){
        $ids = array_map('intval', explode(';', sslDecrypt($encryptedIds)));
        return Invoice::whereIn('id', $ids)->orderBy('id', 'asc')->get();
    }

    public function description(){
        $description = '';
        foreach ($this->invoices as $invoice) {
            if ($description != ''){
                $description .= "; ";
            }
            $description .= $invoice->id."/".$invoice->description;
        }
        return $description;
    }
}