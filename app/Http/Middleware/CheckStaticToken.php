<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ApiToken;

class CheckStaticToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek dan tambahkan header CORS di sini
        $response = $next($request);

        // Menambahkan header CORS secara manual
        $response->headers->set('Access-Control-Allow-Origin', '*');  // Ganti dengan URL aplikasi React kamu
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization');
        $response->headers->set('Access-Control-Allow-Credentials', 'true'); // Optional, jika perlu kirim credentials

        // Jika permintaan adalah OPTIONS (preflight request), langsung return response tanpa validasi token
        if ($request->getMethod() == "OPTIONS") {
            return $response;
        }

        // Periksa apakah ada Authorization header
        $token = $request->header('Authorization');
        
        // Periksa apakah token ada di database
        $apiToken = ApiToken::where('token', substr($token, 7))->first(); // Menghilangkan "Bearer "

        if (!$apiToken) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $response;
    }
}
