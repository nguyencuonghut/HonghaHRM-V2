<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInitialInterviewRequest;
use App\Http\Requests\UpdateInitialInterviewRequest;
use App\Models\FirstInterviewDetail;
use App\Models\InitialInterview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class InitialInterviewController extends Controller
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
    public function store(StoreInitialInterviewRequest $request)
    {
        if (Auth::user()->cannot('create', InitialInterview::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        $initial_interview = new InitialInterview();
        $initial_interview->recruitment_candidate_id = $request->recruitment_candidate_id;
        $initial_interview->health_score = $request->health_score;
        $initial_interview->attitude_score = $request->attitude_score;
        $initial_interview->stability_score = $request->stability_score;
        $initial_interview->interviewer_id = Auth::user()->id;
        $initial_interview->result = $request->result;
        if ($request->health_comment) {
            $initial_interview->health_comment = $request->health_comment;
        }
        if ($request->attitude_comment) {
            $initial_interview->attitude_comment = $request->attitude_comment;
        }
        if ($request->stability_comment) {
            $initial_interview->stability_comment = $request->stability_comment;
        }
        $initial_interview->total_score = $request->health_score + $request->attitude_score + $request->stability_score;
        $initial_interview->save();

        Alert::toast('Nhập kết quả phỏng vấn sơ bộ thành công!', 'success', 'top-right');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(InitialInterview $initialInterview)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InitialInterview $initialInterview)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInitialInterviewRequest $request, InitialInterview $initialInterview)
    {
        if (Auth::user()->cannot('update', $initialInterview)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        $initialInterview->recruitment_candidate_id = $request->recruitment_candidate_id;
        $initialInterview->health_score = $request->health_score;
        $initialInterview->attitude_score = $request->attitude_score;
        $initialInterview->interviewer_id = Auth::user()->id;
        $initialInterview->result = $request->result;
        if ($request->health_comment) {
            $initialInterview->health_comment = $request->health_comment;
        }
        if ($request->attitude_comment) {
            $initialInterview->attitude_comment = $request->attitude_comment;
        }
        if ($request->stability_comment) {
            $initialInterview->stability_comment = $request->stability_comment;
        }
        $initialInterview->total_score = $request->health_score + $request->attitude_score + $request->stability_score;
        $initialInterview->save();

        Alert::toast('Sửa kết quả phỏng vấn sơ bộ thành công!', 'success', 'top-right');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InitialInterview $initialInterview)
    {
        if (Auth::user()->cannot('delete', $initialInterview)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        //Check condition before deleting
        $first_interview_detail = FirstInterviewDetail::where('recruitment_candidate_id', $initialInterview->recruitment_candidate_id)->get();
        if ($first_interview_detail->count()) {
            Alert::toast('Đã có phỏng vấn lần 1. Không thể xóa!', 'error', 'top-right');
            return redirect()->back();
        }
        $initialInterview->delete();

        Alert::toast('Xóa kết quả phỏng vấn sơ bộ thành công!', 'success', 'top-right');
        return redirect()->back();
    }
}
