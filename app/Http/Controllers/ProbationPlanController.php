<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProbationPlanRequest;
use App\Http\Requests\UpdateProbationPlanRequest;
use App\Models\ProbationPlan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class ProbationPlanController extends Controller
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
    public function store(StoreProbationPlanRequest $request)
    {
        if (Auth::user()->cannot('create', ProbationPlan::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        $probation_plan = new ProbationPlan();
        $probation_plan->probation_id = $request->probation_id;
        $probation_plan->work_title = $request->work_title;
        $probation_plan->work_requirement = $request->work_requirement;
        $probation_plan->work_deadline = Carbon::createFromFormat('d/m/Y', $request->work_deadline);
        if ($request->instructor) {
            $probation_plan->instructor = $request->instructor;
        }
        $probation_plan->save();

        Alert::toast('Thêm chi tiết thử việc thành công!', 'success', 'top-right');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(ProbationPlan $probationPlan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProbationPlan $probationPlan)
    {
        if (Auth::user()->cannot('update', $probationPlan)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        // Check condition before edit
        if ($probationPlan->probation->result_manager_status) {
            Alert::toast('Thử việc đã được QL đánh giá. Bạn không thể sửa!', 'error', 'top-right');
            return redirect()->back();
        }
        return view('probation_plan.edit', ['probation_plan' => $probationPlan]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProbationPlanRequest $request, ProbationPlan $probationPlan)
    {
        // Check condition before edit
        if ($probationPlan->probation->result_manager_status) {
            Alert::toast('Thử việc đã được QL đánh giá. Bạn không thể sửa!', 'error', 'top-right');
            return redirect()->back();
        }

        $probationPlan->work_title = $request->work_title;
        $probationPlan->work_requirement = $request->work_requirement;
        $probationPlan->work_deadline = Carbon::createFromFormat('d/m/Y', $request->work_deadline);
        if ($request->instructor) {
            $probationPlan->instructor = $request->instructor;
        }
        if ($request->work_result) {
            $probationPlan->work_result = $request->work_result;
        }
        $probationPlan->save();

        Alert::toast('Sửa chi tiết thử việc thành công!', 'success', 'top-right');
        return redirect()->route('probations.show', $probationPlan->probation->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProbationPlan $probationPlan)
    {
        if (Auth::user()->cannot('delete', $probationPlan)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        // Check condition before delete
        if ($probationPlan->probation->result_manager_status) {
            Alert::toast('Thử việc đã được QL đánh giá. Bạn không thể sửa!', 'error', 'top-right');
            return redirect()->back();
        }
        $probationPlan->delete();

        Alert::toast('Xóa chi tiết thử việc thành công!', 'success', 'top-right');
        return redirect()->back();
    }
}
