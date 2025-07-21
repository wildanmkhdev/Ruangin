<?php

namespace App\Http\Middleware;

use App\Models\ApiKey;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // var api key menampung sebah request dari header dengan nama key x-api key
        $apikey = $request->header('X-API_KEY');
        // di cek apakah key ini di dalam tabel db api key jika tidak ad akan teunr erorr kode 401
        if (!$apikey || !ApiKey::where('key', $apikey)->exists()) {
            return response()->json(['message' => 'Unauthorized'], 401);
            # code...
        }
        // jika ad boleh pakai
        return $next($request);
    }
}
