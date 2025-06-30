<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Alleen admin mag deze zien
        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => 'Niet toegestaan'], 403);
        }

        return User::select('id', 'name')->get(); // alleen ID en naam tonen
    }
}
