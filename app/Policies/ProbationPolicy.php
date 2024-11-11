<?php

namespace App\Policies;

use App\Models\Probation;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProbationPolicy
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
    public function view(User $user, Probation $probation): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role->name == 'Admin' || $user->role->name == 'Trưởng đơn vị';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Probation $probation): bool
    {
        return $user->role->name == 'Admin' || $user->role->name == 'Trưởng đơn vị';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Probation $probation): bool
    {
        return $user->role->name == 'Admin' || $user->role->name == 'Trưởng đơn vị';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Probation $probation): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Probation $probation): bool
    {
        //
    }

    public function evaluate(User $user, Probation $probation): bool
    {
        return $user->role->name == 'Admin' || $user->role->name == 'Trưởng đơn vị';
    }

    public function review(User $user, Probation $probation): bool
    {
        return $user->role->name == 'Admin' || $user->role->name == 'Nhân sự';
    }

    public function approve(User $user, Probation $probation): bool
    {
        return $user->role->name == 'Admin' || $user->role->name == 'Ban lãnh đạo';
    }
}
