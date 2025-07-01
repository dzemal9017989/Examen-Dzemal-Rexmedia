<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');
Route::get('/user', [AuthController::class, 'user'])->middleware('auth');

Route::get('/login', function (Request $request) {
    return response()->json(['message' => 'Niet ingelogd'], 401);
})->name('login');


Route::get(
    '/accept-invitation/{token}',
    function ($token) {
        return view('app');
        // of redirect naar je Vue route
    }
)->name('invitation.accept');




Route::get('/test-email', function () {
    try {
        Mail::raw('Dit is een test email van Suriname app!', function ($message) {
            $message->to('dzemal.nikocevic@gmail.com') // ← Vervang met jouw email
                   ->subject('Test Email');
        });
        
        return 'Email verstuurd! Check je inbox (en spam folder).';
    } catch (\Exception $e) {
        return 'Fout: ' . $e->getMessage();
    }
});

 