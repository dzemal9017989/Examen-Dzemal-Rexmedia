<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class GebruikerController extends Controller
{
    // Thois function retrieves users from the database
    public function index()
    {
        // This returns a list from all the users
        return User::select('id', 'name')->get(); // It shows only the id and the name of the user
    }
}
