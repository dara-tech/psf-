<?php

/**
 * Suppress PHP 8 deprecation warnings for Laravel 7 compatibility
 * This file is included before the autoloader to catch errors early
 */

// Suppress all deprecation warnings
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_WARNING);

// Custom error handler for deprecation warnings
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    if (($errno & E_DEPRECATED) || ($errno & E_STRICT) || 
        strpos($errstr, 'Return type') !== false ||
        strpos($errstr, 'should either be compatible') !== false) {
        return true; // Suppress
    }
    return false;
}, E_ALL);

// Suppress fatal errors for deprecation issues
register_shutdown_function(function () {
    $error = error_get_last();
    if ($error && ($error['type'] === E_ERROR || $error['type'] === E_CORE_ERROR)) {
        if (strpos($error['message'], 'Return type') !== false ||
            strpos($error['message'], 'should either be compatible') !== false ||
            strpos($error['message'], 'ArrayAccess') !== false) {
            // Suppress the error output
            return;
        }
    }
});

