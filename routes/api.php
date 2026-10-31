<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SchoolApiController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Crypt;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/school/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/school', [SchoolApiController::class, 'show']);
});

use App\Http\Middleware\EncryptApiResponse;

Route::middleware([
    'auth:sanctum',
    EncryptApiResponse::class,
])->group(function () {
    Route::get('/school', [SchoolApiController::class, 'show']);
});


Route::post('/decrypt-test', function (Request $request) {
    $request->validate([
        'data' => 'required|string',
    ]);

    try {
        $decryptedData = Crypt::decryptString($request->data);

        return response()->json([
            'success' => true,
            'decrypted_data' => json_decode($decryptedData, true),
        ]);
    } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid encrypted data',
        ], 422);
    }
});
