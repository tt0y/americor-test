<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * Report or log an exception.
     *
     * @throws Throwable
     */
    public function report(Throwable $e): void
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  Request  $request
     */
    public function render($request, Throwable $e): JsonResponse
    {
        // Logging all errors
        \Log::error($e->getMessage(), ['exception' => $e]);

        // Handling validation errors
        if ($e instanceof ValidationException) {
            return response()->json([
                'error' => 'Validation Error',
                'message' => $e->errors(),
            ], 422);
        }

        // Handling authentication errors
        if ($e instanceof \Illuminate\Auth\AuthenticationException) {
            return response()->json([
                'error' => 'Authentication Error',
                'message' => 'Unauthenticated',
            ], 401);
        }

        // Handling any other errors
        return response()->json([
            'error' => 'Internal Server Error',
            'message' => $e->getMessage(),
        ], 500);
    }
}
