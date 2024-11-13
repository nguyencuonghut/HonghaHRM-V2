<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFirstInterviewResultRequest;
use App\Http\Requests\UpdateFirstInterviewResultRequest;
use App\Models\FirstInterviewResult;
use App\Models\SecondInterviewResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class FirstInterviewResultController extends Controller
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
    public function store(StoreFirstInterviewResultRequest $request)
    {
        if (Auth::user()->cannot('create', FirstInterviewResult::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        $first_interview_result = new FirstInterviewResult();
        $first_interview_result->recruitment_candidate_id = $request->recruitment_candidate_id;
        $first_interview_result->interviewer_id = Auth::user()->id;
        $first_interview_result->result = $request->result;
        $first_interview_result->save();

        Alert::toast('Nhập kết quả thành công!', 'success', 'top-right');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(FirstInterviewResult $firstInterviewResult)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FirstInterviewResult $firstInterviewResult)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFirstInterviewResultRequest $request, FirstInterviewResult $firstInterviewResult)
    {
        if (Auth::user()->cannot('update', $firstInterviewResult)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }
        //Do not allow to update when SecondInterviewResult committed.
        $second_interview_result = SecondInterviewResult::where('recruitment_candidate_id', $firstInterviewResult->recruitment_candidate_id)->first();
        if ($second_interview_result) {
            Alert::toast('Đã có kết quả PV lần 2. Không thể sửa!', 'error', 'top-right');
            return redirect()->back();
        }

        $firstInterviewResult->recruitment_candidate_id = $request->recruitment_candidate_id;
        $firstInterviewResult->interviewer_id = Auth::user()->id;
        $firstInterviewResult->result = $request->result;
        $firstInterviewResult->save();

        Alert::toast('Sửa kết quả thành công!', 'success', 'top-right');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FirstInterviewResult $firstInterviewResult)
    {
        if (Auth::user()->cannot('delete', $firstInterviewResult)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }
        // TODO: Do not allow to delete when SecondInterviewResult committed.
        // $second_interview_result = SecondInterviewResult::where('proposal_candidate_id', $proposal_candidate_id)->first();
        // if ($second_interview_result) {
        //     Alert::toast('Đã có kết quả PV lần 2. Không thể xóa!', 'error', 'top-right');
        //     return redirect()->back();
        // }

        $firstInterviewResult->delete();
        Alert::toast('Xóa kết quả thành công!', 'success', 'top-right');
        return redirect()->back();
    }
}
