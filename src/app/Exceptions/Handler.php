<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    /**
     * Report or log an exception.
     *
     * @param \Throwable $exception
     * @return void
     * @throws Throwable
     */
    public function report(Throwable $exception): void
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable $exception
     * @return JsonResponse
     */
    public function render($request, Throwable $exception): JsonResponse
    {
        // Logging all errors
        \Log::error($exception->getMessage(), ['exception' => $exception]);

        // Handling validation errors
        if ($exception instanceof ValidationException) {
            return response()->json([
                'error' => 'Validation Error',
                'message' => $exception->errors()
            ], 422);
        }

        // Handling authentication errors
        if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
            return response()->json([
                'error' => 'Authentication Error',
                'message' => 'Unauthenticated',
            ], 401);
        }

        // Handling any other errors
        return response()->json([
            'error' => 'Internal Server Error',
            'message' => $exception->getMessage(),
        ], 500);
    }
}
