<?php

namespace App\Policies;

use App\Models\Commune;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class CommunePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return 'Admin' == Auth::user()->role->name || 'Nhân sự' == Auth::user()->role->name;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Commune $commune): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return 'Admin' == Auth::user()->role->name || 'Nhân sự' == Auth::user()->role->name;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Commune $commune): bool
    {
        return 'Admin' == Auth::user()->role->name || 'Nhân sự' == Auth::user()->role->name;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Commune $commune): bool
    {
        return 'Admin' == Auth::user()->role->name || 'Nhân sự' == Auth::user()->role->name;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Commune $commune): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Commune $commune): bool
    {
        //
    }

    public function import(User $user): bool
    {
        return 'Admin' == $user->role->name || 'Nhân sự' == $user->role->name;
    }
}
