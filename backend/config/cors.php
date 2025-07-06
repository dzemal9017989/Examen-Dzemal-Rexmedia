<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    // Paths that should be accessible via CORS
    'paths' => ['api/*', 'login', 'logout', 'user', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    // Only requests that derives from this localhost will be called
    'allowed_origins' => ['http://localhost:5173'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    // This allows cookies, sessions and tokens to be sent with the request
    'supports_credentials' => true,

];
