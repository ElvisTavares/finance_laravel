<?php

namespace App\Helpers\Account;

use App\Account;
use App\Helpers\Invoice\Invoice;

class Formatter {
    public $id;
    public $description;
    public $is_credit_card;
    public $invoices = [];

    private $account;

    public function __construct(Account $account){
        $this->account = $account;
        $this->id = $account->id;
        $this->description = $account->description;
        $this->is_credit_card = $account->is_credit_card;
    }

    public function format($year, $mode = 'table'){
        if (!$this->account->is_credit_card) return $this;
        $period = new Period($year, $mode);
        for ($monthIndex = 0; $monthIndex < 12; $monthIndex++) {
            $month = $period->months[$monthIndex];
            $invoices = $this->account->invoices()->betweenDates($month->init, $month->end)->get()->toArray();
            $this->invoices[] = empty($invoices) ? null : new Invoice($this->account, $invoices);
        }
        return $this;
    }
}