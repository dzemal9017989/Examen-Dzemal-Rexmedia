<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;


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

 