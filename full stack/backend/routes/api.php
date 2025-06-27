<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaakController;

// ✅ Login route
Route::post('/login', [AuthController::class, 'login']);

// ✅ Logout route (auth verplicht)
Route::middleware('auth:sanctum')->post('/logout', function (Request $request) {
    $request->user()->currentAccessToken()->delete();
    return response()->json(['message' => 'Uitgelogd']);
});

// ✅ Taken-routes voor ingelogde gebruikers
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/taken', [TaakController::class, 'index']);
    Route::post('/taken', [TaakController::class, 'store']);
    Route::delete('/taken/{taak}', [TaakController::class, 'destroy']);
});
