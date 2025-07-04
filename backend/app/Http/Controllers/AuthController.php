<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    // This function kakes it possible to login with a emailadress and password
public function login(Request $request)
{
    // Retrieves the email and password from the request
    $credentials = $request->only('email', 'password');

    // The user logs in with the credentials
    if (Auth::attempt($credentials)) {
        // If it didn't go as expected the session will be renewed 
        $request->session()->regenerate();

        // This sends a JSON-response with a positive message and the user data
        return response()->json([
            'message' => 'Ingelogd!',
            'user' => Auth::user()
        ]);
    }

    // This sends a error message and a 401 error if 
    return response()->json([
        'message' => 'Login mislukt!'
    ], 401);
}


    // This function makes it possible the the user logs out
    public function logout(Request $request)
    {
        // This logs the user out with the web guard
        Auth::guard('web')->logout();
        // This makes the session invalid an generates new token for security
        $request->session()->invalidate();
        $request->session()->regenerateToken();


        // This sends a message that user is logged out
        return response()->json(['message' => 'Uitgelogd']);
    }

    // This function returns the user data of the logged in user
    public function user(Request $request)
    {
        // This return the user data of the user that is logged in
        return $request->user();
    }
}
