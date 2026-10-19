<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateSyncApiKey
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $expectedKey = config('services.sync.api_key', env('SYNC_API_KEY'));

        if (empty($expectedKey)) {
            return response()->json([
                'success' => false,
                'message' => 'SYNC_API_KEY is not configured on the server.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $providedKey = $request->header('X-API-KEY') 
            ?? $request->bearerToken() 
            ?? $request->input('api_key');

        if (!$providedKey || !hash_equals($expectedKey, (string) $providedKey)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Invalid or missing API Key.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
