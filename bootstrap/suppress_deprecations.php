<?php

/**
 * Suppress PHP 8.3 Deprecation Warnings
 * 
 * This file suppresses deprecation warnings for PHP 8.3 compatibility
 * with older Laravel versions. Include this before autoload.php
 */

if (PHP_VERSION_ID >= 80300) {
    // Suppress deprecation and strict warnings
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
    
    // Custom error handler to suppress deprecation warnings
    set_error_handler(function($errno, $errstr, $errfile, $errline) {
        // Suppress deprecation and strict warnings
        if ($errno === E_DEPRECATED || $errno === E_STRICT) {
            return true; // Suppress the error
        }
        // Let other errors be handled normally
        return false;
    }, E_ALL | E_STRICT);
    
    // Also suppress warnings in exception handler
    set_exception_handler(function($exception) {
        if ($exception instanceof ErrorException) {
            $message = $exception->getMessage();
            if (strpos($message, 'Deprecated') !== false || 
                strpos($message, 'Return type') !== false ||
                strpos($message, 'Method ReflectionParameter::getClass()') !== false) {
                // Suppress deprecation exceptions
                return;
            }
        }
        // Let other exceptions be handled normally
        throw $exception;
    });
}


