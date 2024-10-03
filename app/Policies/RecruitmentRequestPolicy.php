<?php

namespace App\Policies;

use App\Models\RecruitmentRequest;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RecruitmentRequestPolicy
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
    public function view(User $user, RecruitmentRequest $recruitmentRequest): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, RecruitmentRequest $recruitmentRequest): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, RecruitmentRequest $recruitmentRequest): bool
    {
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, RecruitmentRequest $recruitmentRequest): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, RecruitmentRequest $recruitmentRequest): bool
    {
        //
    }

    public function review(User $user, RecruitmentRequest $recruitmentRequest): bool
    {
        return ('Mở' == $recruitmentRequest->status
                || 'Đã kiểm tra' == $recruitmentRequest->status)
                && 'Nhân sự' == $user->role->name;
    }

    public function approve(User $user, RecruitmentRequest $recruitmentRequest): bool
    {
        return ('Đã kiểm tra' == $recruitmentRequest->status
                || 'Đã duyệt' == $recruitmentRequest->status)
                && 'Ban lãnh đạo' == $user()->role->name;
    }
}
