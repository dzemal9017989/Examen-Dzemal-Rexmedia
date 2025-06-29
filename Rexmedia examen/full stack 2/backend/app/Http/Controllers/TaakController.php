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
    return $request->user()->taken()->with('status')->get();
}

public function store(Request $request)
{
    $validated = $request->validate([
        'titel' => 'required|string|max:255',
        'beschrijving' => 'nullable|string',
        'status_id' => 'required|exists:statussen,id',
        'deadline' => 'required|date',
    ]);

    $taak = $request->user()->taken()->create($validated);

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
    $validated = $request->validate([
        'titel' => 'required|string|max:255',
        'beschrijving' => 'nullable|string',
        'status_id' => 'required|exists:statussen,id',
        'deadline' => 'required|date',
    ]);
    $taak->update($validated);
    return response()->json($taak);
}


}

