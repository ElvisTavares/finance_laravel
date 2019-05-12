<?php

/**
 * @param double $value
 * @return string
 */
function _e_money($value)
{
    $value = round($value, 2);
    return number_format($value, 2, __('config.decimal-point'), __('config.thousand-point'));
}

/**
 * @param $date
 * @return false|string
 */
function _e_date($date)
{
    return date(__('config.date-format'), strtotime($date));
}

/**
 * @param $string
 * @param $searched
 * @return false|string
 */
function contains($string, $searched)
{
    return strpos($string, $searched) !== FALSE;
}

function short_encode($data) {
    return strtr(base64_encode($data), '+/=', '-_,');
}

function short_decode($data) {
    return base64_decode(strtr($data, '-_,', '+/='));
}

function add_month_to_date($months, $date){
    return date("Y-m-d\TH:i:s", strtotime("+" . $months . " month", strtotime($date)));
}