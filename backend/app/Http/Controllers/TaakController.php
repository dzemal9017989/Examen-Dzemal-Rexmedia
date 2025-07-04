<?php

namespace App\Http\Controllers;

use App\Models\Task;
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
        return Task::with(['status', 'user'])->get();
    }

    return $user->tasks()->with('status')->get();
}


public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'status_id' => 'required|integer',
        'deadline' => 'required|date',
        'user_id' => 'nullable|exists:users,id',
    ]);

    // Als admin een taak aanmaakt voor een andere gebruiker
    if ($request->user()->isAdmin() && isset($validated['user_id'])) {
        $task = Task::create($validated);
    } else {
        // Gewone gebruiker maakt taak voor zichzelf aan
        $task = $request->user()->tasks()->create($validated);
    }

    return response()->json($task, 201);
}


public function destroy(Task $task)
{
    $this->authorize('delete', $task);
    $task->forceDelete();
    return response()->json(['message' => 'Taak verwijderd']);
}

public function show(Task $task)
{
    $this->authorize('view', $task);
    return $task;
}



public function update(Request $request, Task $task)
{
    $this->authorize('update', $task);

    if ($request->user()->isAdmin()) {
        // ✅ Admin mag alles updaten
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status_id' => 'required|exists:statuses,id',
            'deadline' => 'required|date',
            'user_id' => 'required|exists:users,id',
        ]);
    } else {
        // ✅ Gewone gebruiker mag alleen status aanpassen
        $validated = $request->validate([
            'status_id' => 'required|exists:statuses,id',
        ]);
    }

    $task->update($validated);
    return response()->json($task);
}




}

