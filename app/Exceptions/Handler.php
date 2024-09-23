<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        // Add exceptions that you don't want to report, such as some 404s, etc.
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
        // Log specific exception types in more detail
        if ($exception instanceof QueryException) {
            Log::error('Database Query Exception', [
                'message' => $exception->getMessage(),
                'sql' => $exception->getSql(),
                'bindings' => $exception->getBindings(),
            ]);
        }

        // Let Laravel handle reporting for other exception types
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        // Handle validation errors
        if ($exception instanceof ValidationException) {
            return response()->json([
                'error' => 'Validation Error',
                'messages' => $exception->errors(),
            ], 422);
        }

        // Handle authentication errors
        if ($exception instanceof AuthenticationException) {
            return response()->json([
                'error' => 'Unauthenticated',
            ], 401);
        }

        // Handle access denied/authorization errors
        if ($exception instanceof AccessDeniedHttpException) {
            return response()->json([
                'error' => 'Forbidden',
            ], 403);
        }

        // Handle database query exceptions
        if ($exception instanceof QueryException) {
            return response()->json([
                'error' => 'Database Error',
                'message' => $exception->getMessage(),
            ], 500);
        }

        // For any other exceptions, log and return a generic error message
        if ($request->expectsJson()) {
            return response()->json([
                'error' => 'Server Error',
                'message' => $exception->getMessage(),
            ], 500);
        }

        // Let Laravel handle non-API exceptions for web requests
        return parent::render($request, $exception);
    }
}
