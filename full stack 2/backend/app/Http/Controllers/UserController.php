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

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Optioneel: voorkom dat admin zichzelf of andere admins verwijdert
        if ($user->role === 'admin') {
            return response()->json(['error' => 'Admins kunnen niet worden verwijderd'], 403);
        }

        $user->delete();

        return response()->json(['message' => 'Gebruiker succesvol verwijderd']);
    }
}
