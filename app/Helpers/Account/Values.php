<?php

namespace App\Helpers\Account;

class Values {
    private $paid = [];
    private $nonPaid = [];
    private $period;

    public function __construct($year){
        $this->period = new Period($year);
    }

    public function fillAccount($account){
        $this->paid[$account->id] = [];
        $this->nonPaid[$account->id] = [];
        for ($month = 0; $month < 12; $month++) {
            $dateEnd = $this->period->months[$month]->end;
            $this->paid[$account->id][$month] = $account->total($dateEnd, true);
            $this->nonPaid[$account->id][$month] = $account->total($dateEnd);
        }
    }

    public function totalPaid($month){
        $sum = 0;
        foreach ($this->paid as $account => $months)
            $sum += $months[$month];
        return $sum;
    }

    public function totalNonPaid($month){
        $sum = 0;
        foreach ($this->nonPaid as $account => $months)
            $sum += $months[$month];
        return $sum;
    }
}