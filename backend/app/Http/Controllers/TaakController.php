<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class TaakController extends Controller
{

    use AuthorizesRequests;

    // This function shos a  list with tasks depenning on the user role
    public function index(Request $request)
    {
        // This gets the current logged-in user
        $user = $request->user();

        // 
        if ($user->isAdmin()) {
            // This returns all the tasks including the linked status and user
            return Task::with(['status', 'user'])->get();
        }

        // This returns the tasks of the logged-in user, including the linked status
        return $user->tasks()->with('status')->get();
    }


    // This function adds a new task to the database
    public function store(Request $request)
    {
        // This validates things such as title and description for example
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status_id' => 'required|integer',
            'deadline' => 'required|date',
            'user_id' => 'nullable|exists:users,id',
        ]);

        // If admin makes a task for a new user
        if ($request->user()->isAdmin() && isset($validated['user_id'])) {
            $task = Task::create($validated);
        } else {
            // This saves a task in the database for the user
            $task = $request->user()->tasks()->create($validated);
        }

        // This returns a JSON response that says that a task is created
        return response()->json($task, 201);
    }


    // This function deletes a task from the database permanantly
    public function destroy(int $id)
    {
        //  This checks if the user is allowed to delete the task
        $task = Task::findOrFail($id);
        // This checks who is allowed to delete a task
        $this->authorize('delete', $task);
        // This deletes the task immediately from the database
        $deleted = Task::destroy($id);
        if (!$deleted) {
            // This returns a 404 error if the task is not found
            return response()->json(['message' => 'Taak niet gevonden'], 404);
        }
        // This returns a JSON response that says that the task is deleted
        return response()->json(['message' => 'Taak verwijderd']);
    }

    // This function shows a specific task 
    public function show(int $id)
    {
        // This looks for a task with an ID 
        $task = Task::with(['status', 'user'])->findOrFail($id);
        // This checks if the user is allowed to view the task
        $this->authorize('view', $task);
        // This returns the task with its status and user
        return $task;
    }


    // This function updates a task depending on who is logged in
    public function update(Request $request, int $id)
    {

        // This checks if the user is allowed to update the task
        $task = Task::findOrFail($id);
        // This checks if the user is allowed to update the task
        $this->authorize('update', $task);
        // This if else statement checks if the user is an admin or a regular user
        if ($request->user()->isAdmin()) {
            // This validates the data for an admin user
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'status_id' => 'required|exists:statuses,id',
                'deadline' => 'required|date',
                'user_id' => 'required|exists:users,id',
            ]);
        } else {
            // This allows a regular user to update only the status of their own task
            $validated = $request->validate([
                'status_id' => 'required|exists:statuses,id',
            ]);
        }


        $task->update($validated);

        // This returns the updated task
        return response()->json($task);
    }
}
