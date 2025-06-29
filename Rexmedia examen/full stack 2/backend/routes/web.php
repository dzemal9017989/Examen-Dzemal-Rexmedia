<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum');

// Voeg fallback toe voor middleware die zoekt naar een 'login' route
Route::get('/login', function () {
    return response()->json(['message' => 'Niet ingelogd'], 401);
})->name('login');



