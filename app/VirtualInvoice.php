<?php

namespace App;

use App\Invoice;

class VirtualInvoice
{
    private $invoices;

    public function __construct($encryptedIds)
    {
        $this->invoices = $this->decryptInvoices($encryptedIds);
    }

    /**
     * Get account of invoice
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->invoices[0]->account();
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