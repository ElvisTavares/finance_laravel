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
            if ($account->is_credit_card){
                $this->paid[$account->id][$month] = $account->totalCredit($dateEnd);
                $this->nonPaid[$account->id][$month] = 0;
            } else {
                $this->paid[$account->id][$month] = $account->totalDebit($dateEnd, true);
                $this->nonPaid[$account->id][$month] = $account->totalDebit($dateEnd);
            }
        }
    }

    public function getInit($month){
        return $this->period->months[$month]->init;
    }

    public function getEnd($month){
        return $this->period->months[$month]->end;
    }

    public function getTotal($account, $month){
        return $this->paid[$account->id][$month] + $this->nonPaid[$account->id][$month];
    }

    public function getPaid($account, $month){
        return $this->paid[$account->id][$month];
    }

    public function getNonPaid($account, $month){
        return $this->nonPaid[$account->id][$month];
    }

    public function isThisMonth($month){
        return $this->isFromThisYear() && $this->period->actual->month == $month;
    }

    public function isThisYear($year){
        return $this->period->actual->year == $year;
    }

    public function isFromThisYear(){
        return $this->period->actual->year == date('Y');
    }

    public function avaliableYears(){
        return $this->period->years;
    }

    public function totalActualMonth(){
        return $this->totalPaidActualMonth() + $this->totalNonPaidActualMonth();
    }

    public function totalPaidActualMonth(){
        return $this->totalPaid($this->period->actual->month);
    }

    public function totalNonPaidActualMonth(){
        return $this->totalNonPaid($this->period->actual->month);
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

    public function total($month){
        return $this->totalPaid($month) + $this->totalNonPaid($month);
    }
}