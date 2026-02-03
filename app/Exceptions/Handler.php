<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

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
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        // Check if the request is for API routes
        if ($request->is('api/*')) {
            // For API requests, return JSON response
            if ($exception instanceof \Illuminate\Validation\ValidationException) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $exception->errors(),
                ], 422);
            }

            if ($exception instanceof HttpException) {
                return response()->json([
                    'message' => $exception->getMessage() ?: 'HTTP Error',
                ], $exception->getStatusCode());
            }

            // For other exceptions, return a generic error
            return response()->json([
                'message' => 'An error occurred',
                'error' => $exception->getMessage(),
            ], 500);
        }

        // For non-API requests, use default rendering
        return parent::render($request, $exception);
    }
}