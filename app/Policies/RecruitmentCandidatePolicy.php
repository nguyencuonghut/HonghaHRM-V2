<?php

namespace App\Policies;

use App\Models\RecruitmentCandidate;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RecruitmentCandidatePolicy
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
    public function view(User $user, RecruitmentCandidate $recruitmentCandidate): bool
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
    public function update(User $user, RecruitmentCandidate $recruitmentCandidate): bool
    {
        return 'Admin' == $user->role->name || 'Nhân sự' == $user->role->name;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, RecruitmentCandidate $recruitmentCandidate): bool
    {
        return 'Admin' == $user->role->name || 'Nhân sự' == $user->role->name;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, RecruitmentCandidate $recruitmentCandidate): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, RecruitmentCandidate $recruitmentCandidate): bool
    {
        //
    }
}
