<?php

namespace App;

class PeriodAccount {

    public $actual;
    public $years;
    public $month;

    /**
     * @param integer $year
     * @param string $viewMode
     * @return \stdClass
     */
    public function __construct($year, $viewMode)
    {
        $this->actual = new \stdClass;
        $this->actual->month = date('n') - 1;
        if ($viewMode == 'table') {
            $this->actual->year = isset($year) ? $year : date('Y');
            $years = $this->yearsList($this->actual->year);
        } else { // On card view just show actual year
            $this->actual->year = date('Y');
            $years = [$this->actual->year];
        }
        $this->years = $years;
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
            $months[$i]->init = date($actualYear . '-' . ($i + 1) . '-1');
            $months[$i]->end = date('Y-m-t', strtotime($months[$i]->init));
        }
        return $months;
    }
}