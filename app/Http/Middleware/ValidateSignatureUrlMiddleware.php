<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Spatie\UrlSigner\Laravel\Facades\UrlSigner;

class ValidateSignatureUrlMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $valid = UrlSigner::validate("http://localhost:3000/registration/{$request->verification}/upload?expires={$request->expires}&signature={$request->signature}");

        if (!$valid) {
            return response()->json([
                "message" => "Link tidak valid"
            ], 401);
        }

        return $next($request);
    }
}
