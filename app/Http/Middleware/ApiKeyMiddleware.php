<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-KEY');

        // 🔐 Vérification clé API
        if (!$apiKey || $apiKey !== env('IOT_API_KEY')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized - Invalid API Key'
            ], 401);
        }

        return $next($request);
    }
}