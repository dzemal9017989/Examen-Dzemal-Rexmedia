<?php

namespace App\Policies;

use App\Models\Taak;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaakPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Taak $taak): bool
{
    return $user->isAdmin() || $taak->gebruiker_id === $user->id;
}

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
public function update(User $user, Taak $taak)
{
    if ($user->isAdmin()) return true;

    // gewone gebruiker mag alleen zijn eigen taakstatus wijzigen
    return $user->id === $taak->gebruiker_id;
}


    /**
     * Determine whether the user can delete the model.
     */
public function delete(User $user, Taak $taak): bool
{
    return $user->isAdmin() || $taak->gebruiker_id === $user->id;
}

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Taak $taak): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Taak $taak): bool
    {
        return false;
    }
}
