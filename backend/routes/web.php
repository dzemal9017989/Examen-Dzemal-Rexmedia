<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

// This make a GET route to test email sending functionality
Route::get('/test-email', function () {
    // This try catch block attempts to send an email
    try {
        Mail::raw('Dit is een test email van Suriname app!', function ($message) {
            $message->to('dzemal.nikocevic@gmail.com') // ← Vervang met jouw email
                   ->subject('Test Email');
        });
        
        // If the email is sent successfully, it returns a success message
        return 'Email verstuurd! Check je inbox (en spam folder).';
        // If it does not work it catches the exception and returns an error message
    } catch (\Exception $e) {
        return 'Fout: ' . $e->getMessage();
    }
});

 