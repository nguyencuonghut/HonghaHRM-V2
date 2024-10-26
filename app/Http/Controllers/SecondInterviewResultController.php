<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSecondInterviewResultRequest;
use App\Http\Requests\UpdateSecondInterviewResultRequest;
use App\Models\SecondInterviewResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class SecondInterviewResultController extends Controller
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
    public function store(StoreSecondInterviewResultRequest $request)
    {
        if (Auth::user()->cannot('create', SecondInterviewResult::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        $second_interview_result = new SecondInterviewResult();
        $second_interview_result->recruitment_candidate_id = $request->recruitment_candidate_id;
        $second_interview_result->interviewer_id = Auth::user()->id;
        $second_interview_result->result = $request->result;
        $second_interview_result->save();

        Alert::toast('Nhập kết quả thành công!', 'success', 'top-right');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(SecondInterviewResult $secondInterviewResult)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SecondInterviewResult $secondInterviewResult)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSecondInterviewResultRequest $request, SecondInterviewResult $secondInterviewResult)
    {
        if (Auth::user()->cannot('update', $secondInterviewResult)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        // TODO: Do not allow to update when Offer committed.
        // $offer = Offer::where('recruitment_candidate_id', $secondInterviewResult->recruitment_candidate_id)->first();
        // if ($offer) {
        //     Alert::toast('Đã có kết quả offer. Không thể sửa!', 'error', 'top-right');
        //     return redirect()->back();
        // }

        $secondInterviewResult->recruitment_candidate_id = $request->recruitment_candidate_id;
        $secondInterviewResult->interviewer_id = Auth::user()->id;
        $secondInterviewResult->result = $request->result;
        $secondInterviewResult->save();

        Alert::toast('Sửa kết quả thành công!', 'success', 'top-right');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SecondInterviewResult $secondInterviewResult)
    {
        if (Auth::user()->cannot('delete', $secondInterviewResult)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        //TODO: Do not allow to delete when Offer committed.
        // $offer = Offer::where('recruitment_candidate_id', $recruitment_candidate_id)->first();
        // if ($offer) {
        //     Alert::toast('Đã có kết quả offer. Không thể xóa!', 'error', 'top-right');
        //     return redirect()->back();
        // }

        $secondInterviewResult->delete();
        Alert::toast('Xóa kết quả thành công!', 'success', 'top-right');
        return redirect()->back();
    }
}
