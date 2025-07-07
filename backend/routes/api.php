<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaakController;
use App\Http\Controllers\Admin\UserBeheerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GebruikerController;
use App\Http\Controllers\InvitationController;

// These routes are public and do not require authentication
Route::post('/login', [AuthController::class, 'login']);
Route::get('/invitations/{token}', [InvitationController::class, 'show']);
Route::post('/invitations/{token}/accept', [InvitationController::class, 'accept']);

// ✅ Routes that need authentication (using 'auth' not 'auth:sanctum')
Route::middleware('auth')->group(function () {
  // User routes
  Route::get('/user', [AuthController::class, 'user']);
  Route::post('/logout', [AuthController::class, 'logout']);

  // Tasks routes
  Route::get('/taken', [TaakController::class, 'index']);
  Route::post('/taken', [TaakController::class, 'store']);
  Route::get('taken/{id}', [TaakController::class, 'show']);
  Route::put('/taken/{id}', [TaakController::class, 'update']);
  Route::delete('/taken/{id}', [TaakController::class, 'destroy']);

  // Users routes
  Route::get('/api/users', [UserController::class, 'index']);
  Route::delete('/admin/users/{id}', [UserController::class, 'destroy']);
});

// This middleware checks if the user is authenticated and has the 'admin' role
Route::middleware(['auth', 'is_admin'])->group(function () {
  Route::get('/admin/users', [UserBeheerController::class, 'index']);
  Route::post('/admin/users', [UserBeheerController::class, 'store']);
  Route::get('/admin/invitations', [InvitationController::class, 'index']);
  Route::post('/admin/invitations', [InvitationController::class, 'store']);
  Route::delete('/admin/invitations/{id}', [InvitationController::class, 'destroy']);
});

// This route shows all users, but only for authenticated users
Route::middleware('auth')->get('/users', [GebruikerController::class, 'index']);
