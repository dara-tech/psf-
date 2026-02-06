<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        // Suppress PHP 8 deprecation-related fatal errors
        if ($exception instanceof \Error && 
            (strpos($exception->getMessage(), 'Return type') !== false ||
             strpos($exception->getMessage(), 'should either be compatible') !== false ||
             strpos($exception->getMessage(), 'ArrayAccess') !== false)) {
            return; // Don't report these errors
        }
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        // Suppress PHP 8 deprecation-related fatal errors and show a generic error
        if ($exception instanceof \Error && 
            (strpos($exception->getMessage(), 'Return type') !== false ||
             strpos($exception->getMessage(), 'should either be compatible') !== false ||
             strpos($exception->getMessage(), 'ArrayAccess') !== false)) {
            // Try to continue despite the error
            try {
                return parent::render($request, $exception);
            } catch (\Throwable $e) {
                // If we can't render, return a simple error page
                return response('Application Error: PHP 8 compatibility issue. Please use PHP 7.4 or upgrade Laravel.', 500);
            }
        }
        return parent::render($request, $exception);
    }
}
