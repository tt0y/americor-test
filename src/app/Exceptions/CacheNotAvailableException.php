<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CacheNotAvailableException extends Exception
{
    /**
     * Render the exception into an HTTP response.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function render($request)
    {
        return response()->json([
            'error' => 'Cache Not Available',
            'message' => 'Unable to store or retrieve data from the cache.',
        ], 500);
    }
}
