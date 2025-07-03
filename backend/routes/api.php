<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaakController;
use App\Http\Controllers\Admin\UserBeheerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GebruikerController;
use App\Http\Controllers\InvitationController;

// ✅ Login route (no auth needed)
Route::post('/login', [AuthController::class, 'login']);
Route::get('/invitations/{token}', [InvitationController::class, 'show']);
Route::post('/invitations/{token}/accept', [InvitationController::class, 'accept']);

// ✅ Routes that need authentication (using 'auth' not 'auth:sanctum')
Route::middleware('auth')->group(function () {
  // User routes
  Route::get('/user', [AuthController::class, 'user']);
  Route::post('/logout', [AuthController::class, 'logout']);

  // Taken routes
  Route::get('/taken', [TaakController::class, 'index']);
  Route::post('/taken', [TaakController::class, 'store']);
  Route::get('taken/{taak}', [TaakController::class, 'show']);
  Route::put('/taken/{taak}', [TaakController::class, 'update']);
  Route::delete('/taken/{taak}', [TaakController::class, 'destroy']);

  // Users routes
  Route::get('/api/users', [UserController::class, 'index']);
  Route::delete('/admin/users/{id}', [UserController::class, 'destroy']);
});

// Gewijzigd:
Route::middleware(['auth', 'is_admin'])->group(function () {
  Route::get('/admin/users', [UserBeheerController::class, 'index']);
  Route::post('/admin/users', [UserBeheerController::class, 'store']);
  Route::get('/admin/invitations', [InvitationController::class, 'index']);
  Route::post('/admin/invitations', [InvitationController::class, 'store']);
  Route::delete('/admin/invitations/{id}', [InvitationController::class, 'destroy']);
});


Route::middleware('auth')->get('/users', [GebruikerController::class, 'index']);
