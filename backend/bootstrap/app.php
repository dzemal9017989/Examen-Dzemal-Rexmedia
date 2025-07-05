<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// This specifies the base location of your Laravel application
return Application::configure(basePath: dirname(__DIR__))
// This sets the environment to production
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )

    // You install the middleware here
->withMiddleware(function (Middleware $middleware) {
    $middleware->statefulApi();

    //  Here you register your alias
    $middleware->alias([
        'is_admin' => \App\Http\Middleware\IsAdmin::class,
    ]);
})

    // This is for custom error handling
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
