<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlanRequest;
use App\Http\Requests\UpdatePlanRequest;
use App\Models\Plan;
use App\Models\Recruitment;
use App\Models\User;
use App\Notifications\PlanRequestApprove;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePlanRequest $request)
    {
        if (Auth::user()->cannot('create', Plan::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        //Create new RecruitmentPlan
        $plan = new Plan();
        $plan->recruitment_id = $request->recruitment_id;
        if ($request->budget) {
            $plan->budget = $request->budget;
            $plan->status = 'Chưa duyệt';
        } else {
            $plan->status = 'Đã duyệt';
        }
        $plan->creator_id = Auth::user()->id;
        $plan->save();

        //Create plan_method pivot item
        $plan->methods()->attach($request->method_id);

        //Send notification to approver
        if ($request->budget) {
            $approvers = User::where('role_id', 2)->get(); //2: Ban lãnh đạo
            foreach ($approvers as $approver) {
                Notification::route('mail' , $approver->email)->notify(new PlanRequestApprove($plan->id));
            }
        }

        Alert::toast('Thêm kế hoạch tuyển dụng mới thành công!', 'success', 'top-right');
        return redirect()->route('recruitments.show', $plan->recruitment_id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Plan $plan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Plan $plan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePlanRequest $request, Plan $plan)
    {
        if (Auth::user()->cannot('update', $plan)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        if ($request->budget) {
            $plan->budget = $request->budget;
            $plan->status = 'Chưa duyệt';
        } else {
            $plan->status = 'Đã duyệt';
        }
        $plan->creator_id = Auth::user()->id;
        $plan->save();

        // Delete all old plan_method pivot items
        $plan->methods()->detach();

        //Create plan_method pivot item
        $plan->methods()->attach($request->method_id);

        //Send notification to approver
        if ($request->budget) {
            $approvers = User::where('role_id', 2)->get(); //2: Ban lãnh đạo
            foreach ($approvers as $approver) {
                Notification::route('mail' , $approver->email)->notify(new PlanRequestApprove($plan->id));
            }
        }

        Alert::toast('Sửa kế hoạch tuyển dụng thành công!', 'success', 'top-right');
        return redirect()->route('recruitments.show', $plan->recruitment_id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plan $plan)
    {
        if (Auth::user()->cannot('delete', $plan)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('recruitments.show', $plan->recruitment_id);
        }

        $plan->delete();
        Alert::toast('Xóa kế hoạch thành công!', 'success', 'top-right');
        return redirect()->route('recruitments.show', $plan->recruitment_id);
    }
}
