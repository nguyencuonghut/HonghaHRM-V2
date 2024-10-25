<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFirstInterviewDetailRequest;
use App\Models\FirstInterviewDetail;
use App\Models\FirstInterviewResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class FirstInterviewDetailController extends Controller
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
    public function store(StoreFirstInterviewDetailRequest $request)
    {
        if (Auth::user()->cannot('create', FirstInterviewDetail::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        $first_interview_detail = new FirstInterviewDetail();
        $first_interview_detail->recruitment_candidate_id = $request->recruitment_candidate_id;
        $first_interview_detail->content = $request->content;
        $first_interview_detail->comment = $request->comment;
        $first_interview_detail->score = $request->score;
        $first_interview_detail->save();

        Alert::toast('Nhập dữ liệu thành công!', 'success', 'top-right');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(FirstInterviewDetail $firstInterviewDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FirstInterviewDetail $firstInterviewDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FirstInterviewDetail $firstInterviewDetail)
    {
        if (Auth::user()->cannot('update', $firstInterviewDetail)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        $firstInterviewDetail->recruitment_candidate_id = $request->recruitment_candidate_id;
        $firstInterviewDetail->content = $request->content;
        $firstInterviewDetail->comment = $request->comment;
        $firstInterviewDetail->score = $request->score;
        $firstInterviewDetail->save();

        Alert::toast('Nhập dữ liệu thành công!', 'success', 'top-right');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FirstInterviewDetail $firstInterviewDetail)
    {
        if (Auth::user()->cannot('delete', $firstInterviewDetail)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        // Do not allow to delete if FirstInterview has result
        $first_interview_result = FirstInterviewResult::where('recruitment_candidate_id', $firstInterviewDetail->recruitment_candidate_id)->first();
        if ($first_interview_result) {
            Alert::toast('Kết quả PV lần 1 đã duyệt. Không xóa được!', 'error', 'top-right');
            return redirect()->back();
        }

        $firstInterviewDetail->delete();
        Alert::toast('Xóa kết quả thành công!', 'success', 'top-right');
        return redirect()->back();
    }
}
