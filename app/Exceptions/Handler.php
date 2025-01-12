<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function render($request, Throwable $exception)
    {
        // Check if the request is from the API route
        if ($request->is('api/*') || $request->expectsJson()) {
            return response()->json([
                'error' => true,
                'message' => $exception->getMessage(),
                'status_code' => $this->getStatusCode($exception),
            ], $this->getStatusCode($exception));
        }

        // Default behavior for web requests
        return parent::render($request, $exception);
    }

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Get the status code for the exception.
     *
     * @param  \Throwable  $exception
     * @return int
     */
    protected function getStatusCode(Throwable $exception)
    {
        if (method_exists($exception, 'getStatusCode')) {
            return $exception->getStatusCode();
        }

        return 500; // Default to 500 if no status code is available
    }
}