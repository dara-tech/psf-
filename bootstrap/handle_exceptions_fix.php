<?php

/**
 * Custom HandleExceptions Bootstrap Fix for PHP 8.3
 * 
 * This file patches Laravel's HandleExceptions to suppress deprecation warnings
 * for PHP 8.3 compatibility with Laravel 5.x
 */

if (PHP_VERSION_ID >= 80300) {
    // Patch HandleExceptions before it's loaded
    // We need to intercept the error handler that converts warnings to exceptions
    
    // Store original error handler
    $originalErrorHandler = null;
    
    // Set a custom error handler that runs before Laravel's
    set_error_handler(function($errno, $errstr, $errfile, $errline) use (&$originalErrorHandler) {
        // Suppress deprecation and strict warnings
        if ($errno === E_DEPRECATED || $errno === E_STRICT) {
            // Check if it's a deprecation warning we want to suppress
            if (strpos($errstr, 'Deprecated') !== false || 
                strpos($errstr, 'Return type') !== false ||
                strpos($errstr, 'Method ReflectionParameter::getClass()') !== false ||
                strpos($errstr, 'should either be compatible') !== false) {
                return true; // Suppress the error
            }
        }
        
        // For other errors, call original handler if it exists
        if ($originalErrorHandler && is_callable($originalErrorHandler)) {
            return call_user_func($originalErrorHandler, $errno, $errstr, $errfile, $errline);
        }
        
        return false; // Let PHP handle it
    }, E_ALL | E_STRICT);
}


