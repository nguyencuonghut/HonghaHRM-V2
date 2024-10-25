<?php

namespace App\Policies;

use App\Models\InitialInterview;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class InitialInterviewPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, InitialInterview $initialInterview): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return 'Admin' == $user->role->name || 'Nhân sự' == $user->role->name;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, InitialInterview $initialInterview): bool
    {
        return 'Admin' == $user->role->name || 'Nhân sự' == $user->role->name;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, InitialInterview $initialInterview): bool
    {
        return 'Admin' == $user->role->name || 'Nhân sự' == $user->role->name;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, InitialInterview $initialInterview): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, InitialInterview $initialInterview): bool
    {
        //
    }
}
