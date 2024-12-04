<?php

namespace App\Policies;

use App\Models\DocType;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DocTypePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->role->name == 'Admin' || $user->role->name == 'Nhân sự';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DocType $docType): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role->name == 'Admin' || $user->role->name == 'Nhân sự';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DocType $docType): bool
    {
        return $user->role->name == 'Admin' || $user->role->name == 'Nhân sự';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DocType $docType): bool
    {
        return $user->role->name == 'Admin' || $user->role->name == 'Nhân sự';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, DocType $docType): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, DocType $docType): bool
    {
        //
    }
}
