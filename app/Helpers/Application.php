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

function contains($string, $searched)
{
    return strpos($string, $searched) !== false;
}

define("CRYPT_KEY", env('APP_KEY', 'FR4jhZkoxZ'));
define("CRYPT_METHOD", "AES-128-ECB");

/**
 * Returns an encrypted & utf8-encoded
 */
function sslEncrypt($pure_string)
{
    return urlencode(openssl_encrypt($pure_string, CRYPT_METHOD, CRYPT_KEY));
}

/**
 * Returns decrypted original string
 */
function sslDecrypt($encrypted_string)
{
    return openssl_decrypt(urldecode($encrypted_string), CRYPT_METHOD, CRYPT_KEY);
}