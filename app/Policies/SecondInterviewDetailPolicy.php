<?php

namespace App\Policies;

use App\Models\SecondInterviewDetail;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SecondInterviewDetailPolicy
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
    public function view(User $user, SecondInterviewDetail $secondInterviewDetail): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return 'Admin' == $user->role->name || 'Ban lãnh đạo' == $user->role->name;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SecondInterviewDetail $secondInterviewDetail): bool
    {
        return 'Admin' == $user->role->name || 'Ban lãnh đạo' == $user->role->name;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SecondInterviewDetail $secondInterviewDetail): bool
    {
        return 'Admin' == $user->role->name || 'Ban lãnh đạo' == $user->role->name;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SecondInterviewDetail $secondInterviewDetail): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SecondInterviewDetail $secondInterviewDetail): bool
    {
        //
    }
}
