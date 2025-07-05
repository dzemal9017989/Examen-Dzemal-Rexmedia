<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    // This function allows a user to log in using an email address and password.
    public function login(Request $request)
{
    // Retrieves the email and password from the request
    $credentials = $request->only('email', 'password');

    // The user attempts to log in with the credentials
    if (Auth::attempt($credentials)) {
            // If authentication is successful, the session will be regenerated for security
            $request->session()->regenerate();

        // This returns a JSON-response with a success message and the authenticated user
        return response()->json([
            'message' => 'Ingelogd!',
            'user' => Auth::user()
        ]);
    }

    // This returns a error message and a 401 error if login fails
    return response()->json([
        'message' => 'Login mislukt!'
    ], 401);
}


    // This function handles user logout
    public function logout(Request $request)
    {
        // This logs the user out with the web guard
        Auth::guard('web')->logout();
        // This makes the session invalid and generates a new token for security reasons
        $request->session()->invalidate();
        // A new CSRF token will be generated for security reasons
        $request->session()->regenerateToken();


        // This returns a message that says that the user is logged out
        return response()->json(['message' => 'Uitgelogd']);
    }

    // This function returns the user data of the logged in user
    public function user(Request $request)
    {
        // This returns the authenticated user
        return $request->user();
    }
}
