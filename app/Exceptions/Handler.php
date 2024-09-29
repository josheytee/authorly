<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * Report or log an exception.
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        // Ensure the request expects a JSON response
        if ($request->expectsJson()) {

            // Handle validation errors
            if ($exception instanceof ValidationException) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation Error',
                    'errors' => $exception->errors(),
                ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            }

            // Handle 404 errors
            if ($exception instanceof NotFoundHttpException) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Resource not found',
                ], JsonResponse::HTTP_NOT_FOUND);
            }

            // Handle general HTTP exceptions (e.g., 401, 403, etc.)
            if ($exception instanceof HttpException) {
                return response()->json([
                    'status' => 'error',
                    'message' => $exception->getMessage(),
                ], $exception->getStatusCode());
            }

            // Handle any other exceptions (Internal Server Error)
            return response()->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred',
                'error' => $exception->getMessage(),
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        // Fallback to parent handler for non-JSON requests
        return parent::render($request, $exception);
    }
}
