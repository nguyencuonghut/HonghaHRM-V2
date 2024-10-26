<?php

namespace App\Policies;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class PlanPolicy
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
    public function view(User $user, Plan $plan): bool
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
    public function update(User $user, Plan $plan): bool
    {
        //Sửa khi Plan chưa được duyệt
        //hoặc Plan được duyệt nhưng budget = 0
        return ('Nhân sự' == Auth::user()->role->name
                && 'Chưa duyệt' == $plan->status)
                ||
                ('Nhân sự' == Auth::user()->role->name
                    && 'Đã duyệt' == $plan->status
                    && !$plan->budget)
                ||
                ('Nhân sự' == Auth::user()->role->name
                    && 'Đã duyệt' == $plan->status
                    && 'Từ chối' == $plan->approver_result);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Plan $plan): bool
    {
        //Xóa khi Plan chưa được duyệt
        //hoặc Plan được duyệt nhưng budget = 0
        return ('Nhân sự' == Auth::user()->role->name
                && 'Chưa duyệt' == $plan->status)
                ||
                ('Nhân sự' == Auth::user()->role->name
                && 'Đã duyệt' == $plan->status
                && !$plan->budget);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Plan $plan): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Plan $plan): bool
    {
        //
    }

    public function approve(User $user, Plan $plan): bool
    {
        return 'Admin' == Auth::user()->role->name || 'Ban lãnh đạo' == Auth::user()->role->name;
    }
}
