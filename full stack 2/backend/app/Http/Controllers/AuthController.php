<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        return response()->json([
            'message' => 'Ingelogd!',
            'user' => Auth::user()
        ]);
    }

    return response()->json([
        'message' => 'Login mislukt!'
    ], 401);
}



    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Uitgelogd']);
    }

    public function user(Request $request)
    {
        return $request->user();
    }
}
