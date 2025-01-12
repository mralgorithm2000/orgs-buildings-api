<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Define the static API key
        $staticApiKey = env('STATIC_API_KEY', 'your_default_api_key_here');

        // Check for the API key in headers
        $apiKey = $request->header('X-API-KEY');

        if (!$apiKey || $apiKey !== $staticApiKey) {
            return response()->json(['message' => 'Unauthorized: Invalid API Key'], 401);
        }

        return $next($request);
    }
}
