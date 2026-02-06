<?php

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Suppress PHP 8.3 Deprecation Warnings
|--------------------------------------------------------------------------
|
| For PHP 8.3 compatibility with older Laravel versions, suppress
| deprecation warnings before Laravel loads. These don't affect functionality.
|
*/

if (PHP_VERSION_ID >= 80300) {
    // Suppress deprecation and strict warnings
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
    
    // Set custom error handler to suppress deprecation warnings
    set_error_handler(function($errno, $errstr, $errfile, $errline) {
        // Suppress deprecation and strict warnings
        if ($errno === E_DEPRECATED || $errno === E_STRICT) {
            return true; // Suppress the error
        }
        // Let other errors be handled normally
        return false;
    }, E_ALL | E_STRICT);
}

/*
|--------------------------------------------------------------------------
| Register The Composer Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader
| for our application. We just need to utilize it! We'll require it
| into the script here so that we do not have to worry about the
| loading of any our classes "manually". Feels great to relax.
|
*/

require __DIR__.'/../vendor/autoload.php';
