<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\HttpFoundation\Response;

class EncryptApiResponse
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response->isSuccessful()) {
            $originalData = $response->getContent();

            $encryptedData = Crypt::encryptString($originalData);

            return response()->json([
                'encrypted' => true,
                'data' => $encryptedData,
            ], $response->getStatusCode());
        }

        return $response;
    }
}
