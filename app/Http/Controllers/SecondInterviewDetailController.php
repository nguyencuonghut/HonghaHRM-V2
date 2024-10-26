<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSecondInterviewDetailRequest;
use App\Http\Requests\UpdateSecondInterviewDetailRequest;
use App\Models\SecondInterviewDetail;
use App\Models\SecondInterviewResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class SecondInterviewDetailController extends Controller
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
    public function store(StoreSecondInterviewDetailRequest $request)
    {
        if (Auth::user()->cannot('create', SecondInterviewDetail::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        $second_interview_detail = new SecondInterviewDetail();
        $second_interview_detail->recruitment_candidate_id = $request->recruitment_candidate_id;
        $second_interview_detail->content = $request->content;
        $second_interview_detail->comment = $request->comment;
        $second_interview_detail->score = $request->score;
        $second_interview_detail->save();

        Alert::toast('Nhập dữ liệu thành công!', 'success', 'top-right');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(SecondInterviewDetail $secondInterviewDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SecondInterviewDetail $secondInterviewDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSecondInterviewDetailRequest $request, SecondInterviewDetail $secondInterviewDetail)
    {
        if (Auth::user()->cannot('update', $secondInterviewDetail)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        // Do not allow to update if SecondInterview has result
        $second_interview_result = SecondInterviewResult::where('recruitment_candidate_id', $request->recruitment_candidate_id)->first();
        if ($second_interview_result) {
            Alert::toast('Kết quả PV lần 2 đã duyệt. Không xóa được!', 'error', 'top-right');
            return redirect()->back();
        }

        $secondInterviewDetail->recruitment_candidate_id = $request->recruitment_candidate_id;
        $secondInterviewDetail->content = $request->content;
        $secondInterviewDetail->comment = $request->comment;
        $secondInterviewDetail->score = $request->score;
        $secondInterviewDetail->save();

        Alert::toast('Sửa dữ liệu thành công!', 'success', 'top-right');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SecondInterviewDetail $secondInterviewDetail)
    {
        if (Auth::user()->cannot('delete', $secondInterviewDetail)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }
        // Do not allow to delete when SecondInterviewResult committed.
        $second_interview_result = SecondInterviewResult::where('recruitment_candidate_id', $secondInterviewDetail->recruitment_candidate_id)->first();
        if ($second_interview_result) {
            Alert::toast('Đã có kết quả PV lần 2. Không thể xóa!', 'error', 'top-right');
            return redirect()->back();
        }
        $secondInterviewDetail->delete();
        Alert::toast('Xóa kết quả thành công!', 'success', 'top-right');
        return redirect()->back();
    }
}
