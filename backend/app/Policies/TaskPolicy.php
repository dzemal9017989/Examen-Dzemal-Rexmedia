<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    /**
     * Determine whether the user can view any models.
     */

     // The methods decides if a user can view all tasks
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */

     // The method decides if a user can view a specific task if he or she is an admin or the owner of the task
    public function view(User $user, Task $task): bool
{
    return $user->isAdmin() || $task->user_id === $user->id;
}

    /**
     * Determine whether the user can create models.
     */

    // This function does not allow any users to create a task
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */

     // This function decides if a user can update a task
public function update(User $user, Task $task)
{
    if ($user->isAdmin()) return true;

    // A normal user can only update their own task status
    return $user->id === $task->user_id;
}


    /**
     * Determine whether the user can delete the model.
     */

     // This function aloows an admin to delete any task
public function delete(User $user, Task $task): bool
{
    return $user->isAdmin() || $task->user_id === $user->id;
}

    /**
     * Determine whether the user can restore the model.
     */

    // This function does not allow anyone to delete a task
    public function restore(User $user, Task $task): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */

     // This function allows only admins to permanently delete a task
    public function forceDelete(User $user, Task $task): bool
    {
        return $user->isAdmin();
    }
}
