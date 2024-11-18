<?php

namespace App\Policies;

use App\Models\Recruitment;
use App\Models\User;
use App\Models\UserDepartment;
use Illuminate\Auth\Access\Response;

class RecruitmentPolicy
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
    public function view(User $user, Recruitment $recruitment): bool
    {
        // Check authorization
        $dept_position_id = $recruitment->position->department_id;
        $user_department_ids = [];
        $user_department_ids = UserDepartment::where('user_id', $user->id)->pluck('department_id')->toArray();
        if ('Trưởng đơn vị' == $user->role->name
            && !in_array($dept_position_id, $user_department_ids)) {
            return false;
        } else {
            return true;
        }
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
    public function update(User $user, Recruitment $recruitment): bool
    {
        return $user->id == $recruitment->creator->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Recruitment $recruitment): bool
    {
        return $user->id == $recruitment->creator->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Recruitment $recruitment): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Recruitment $recruitment): bool
    {
        //
    }

    public function review(User $user, Recruitment $recruitment): bool
    {
        return ('Mở' == $recruitment->status
                || 'Đã kiểm tra' == $recruitment->status)
                && 'Nhân sự' == $user->role->name;
    }

    public function approve(User $user, Recruitment $recruitment): bool
    {
        return ('Đã kiểm tra' == $recruitment->status
                || 'Đã duyệt' == $recruitment->status)
                && 'Ban lãnh đạo' == $user->role->name;
    }
}
