<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;

class UserBeheerController extends Controller
{
    // This function retrieves users from the database
    public function index()
    {
        // This returns a list from all the users
        return User::all();
    }

    // The purpose of the function is add a new user to the database.
    public function store(Request $request)
    {
        // This validates data such as name, email, password and role
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:user,admin',
        ]);

        // The password is hashed due to security reasons
        $validated['password'] = Hash::make($validated['password']);

        // This makes a new user in the database with the validated data
        $user = User::create($validated);

        // This returns a JSON response 
        return response()->json($user, 201);
    }
}
