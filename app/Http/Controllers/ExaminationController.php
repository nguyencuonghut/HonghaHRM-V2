<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExaminationRequest;
use App\Models\Examination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class ExaminationController extends Controller
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
    public function store(StoreExaminationRequest $request)
    {
        if (Auth::user()->cannot('create', Examination::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        $exam = new Examination();
        $exam->recruitment_candidate_id = $request->recruitment_candidate_id;
        $exam->standard_score = $request->standard_score;
        $exam->candidate_score = $request->candidate_score;
        $exam->result = $request->result;
        $exam->save();

        Alert::toast('Nhập kết quả thi tuyển thành công!', 'success', 'top-right');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Examination $examination)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Examination $examination)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Examination $examination)
    {
        if (Auth::user()->cannot('update', $examination)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        $examination->recruitment_candidate_id = $request->recruitment_candidate_id;
        $examination->standard_score = $request->standard_score;
        $examination->candidate_score = $request->candidate_score;
        $examination->result = $request->result;
        $examination->save();

        Alert::toast('Sửa kết quả thi tuyển thành công!', 'success', 'top-right');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Examination $examination)
    {
        if (Auth::user()->cannot('delete', $examination)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        //TODO: Check if Examination is used or not
        $examination->delete();

        Alert::toast('Xóa kết quả thi tuyển thành công!', 'success', 'top-right');
        return redirect()->back();
    }
}
