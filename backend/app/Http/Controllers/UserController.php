<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // This function shows all users, but only for admins
    public function index(Request $request)
    {
        // This if statement checks if the logged-in user is not an admin
        if (!$request->user()->isAdmin()) {
            // This returns a eroor message if the user is not an admin
            return response()->json(['message' => 'Niet toegestaan'], 403);
        }

        // This returns the users if the user is an admin
        return User::select('id', 'name')->get(); // only showing id and name
    }

    // This function destroys a user based on the ID except for admins
    public function destroy($id)
    {
        // This checks if it is a user with the ID that is given
        $user = User::findOrFail($id);

        // This prevents that the admin deletes themselves
        if ($user->role === 'admin') {
            return response()->json(['error' => 'Admins kunnen niet worden verwijderd'], 403);
        }

        // This deletes the user from the database
        $user->delete();

        // This returns a JSON response that the user is successfully deleted
        return response()->json(['message' => 'Gebruiker succesvol verwijderd']);
    }
}
