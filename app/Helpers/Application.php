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

/**
 * Returns an encrypted & utf8-encoded
 */
function sslEncrypt($pure_string)
{
    return encrypt_decrypt('encrypt', $pure_string);
}

/**
 * Returns decrypted original string
 */
function sslDecrypt($encrypted_string)
{
    return encrypt_decrypt('decrypt', $encrypted_string);
}

function encrypt_decrypt($action, $string) {
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $secret_key = env('APP_KEY', 'FR4jhZkoxZ');
    $secret_iv = env('APP_KEY', 'FR4jhZkoxZ');
    // hash
    $key = hash('sha256', $secret_key);

    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    if ( $action == 'encrypt' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else if( $action == 'decrypt' ) {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
}