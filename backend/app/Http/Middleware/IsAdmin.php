<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    // This method or function is used to check if the user is an admin
    public function handle(Request $request, Closure $next): Response
    {
        // This if statement checks if the user is not logged in or if the user is not an admin
        if (!$request->user() || !$request->user()->isAdmin()) {
            // this returns a JSON response with a message that access is denied
            return response()->json(['message' => 'Toegang geweigerd (alleen admin)'], 403);
        }


        // If the user is an admin, the request will be passed to the next middleware or controller
        return $next($request);
    }
}

