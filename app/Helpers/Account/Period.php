<?php

namespace App\Helpers\Account;

class Period {

    public $actual;
    public $years;
    public $month;

    /**
     * @param integer $year
     * @return \stdClass
     */
    public function __construct($year)
    {
        $this->actual = new \stdClass;
        $this->actual->month = date('n') - 1;
        $this->actual->year = isset($year) ? $year : date('Y');
        $this->years = $this->yearsList($this->actual->year);
        $this->months = $this->months($this->actual->year);
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
            $months[$i]->init = date($actualYear . '-' . str_pad(($i + 1),2, "0", STR_PAD_LEFT) . '-01');
            $months[$i]->end = date('Y-m-t', strtotime($months[$i]->init));
        }
        return $months;
    }
}