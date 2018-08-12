<?php

/**
 * Function to require all php files in path
 *
 * @param string $basePath
 */
function requireAll($basePath)
{
    foreach (scandir($basePath) as $filename) {
        $path = $basePath . '/' . $filename;
        if (is_file($path)) {
            require_once $path;
        }
    }
}

/**
 * @param double $value
 * @return string
 */
function formatMoney($value)
{
    $value = round($value, 2);
    $formattedNumber = number_format($value, 2, __('config.decimal-point'), __('config.thousand-point'));
    $tagTemplate = new TagTemplate('font', ['class' => $value < 0 ? 'negative' : 'positive'], $formattedNumber);
    return $tagTemplate->html();
}

/**
 * @param $date
 * @return false|string
 */
function formatDate($date)
{
    return date(__('config.date-format'), strtotime($date));
}