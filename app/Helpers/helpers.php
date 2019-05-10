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