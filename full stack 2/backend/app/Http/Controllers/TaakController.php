<?php

namespace App\Http\Controllers;

use App\Models\Taak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class TaakController extends Controller
{

    use AuthorizesRequests;
public function index(Request $request)
{
    $user = $request->user();

    if ($user->isAdmin()) {
        return Taak::with(['status', 'gebruiker'])->get();
    }

    return $user->taken()->with('status')->get();
}


public function store(Request $request)
{
    $validated = $request->validate([
        'titel' => 'required|string|max:255',
        'beschrijving' => 'required|string',
        'status_id' => 'required|integer',
        'deadline' => 'required|date',
        'gebruiker_id' => 'nullable|exists:users,id',
    ]);

    // Als admin een taak aanmaakt voor een andere gebruiker
    if ($request->user()->isAdmin() && isset($validated['gebruiker_id'])) {
        $taak = Taak::create($validated);
    } else {
        // Gewone gebruiker maakt taak voor zichzelf aan
        $taak = $request->user()->taken()->create($validated);
    }

    return response()->json($taak, 201);
}


public function destroy(Taak $taak)
{
    $this->authorize('delete', $taak);
    $taak->delete();
    return response()->json(['message' => 'Taak verwijderd']);
}

public function show(Taak $taak)
{
    $this->authorize('view', $taak);
    return $taak;
}



public function update(Request $request, Taak $taak)
{
    $this->authorize('update', $taak);

    if ($request->user()->isAdmin()) {
        // ✅ Admin mag alles updaten
        $validated = $request->validate([
            'titel' => 'required|string|max:255',
            'beschrijving' => 'nullable|string',
            'status_id' => 'required|exists:statussen,id',
            'deadline' => 'required|date',
            'gebruiker_id' => 'required|exists:users,id',
        ]);
    } else {
        // ✅ Gewone gebruiker mag alleen status aanpassen
        $validated = $request->validate([
            'status_id' => 'required|exists:statussen,id',
        ]);
    }

    $taak->update($validated);
    return response()->json($taak);
}




}

