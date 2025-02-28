<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRecruitmentCandidateRequest;
use App\Http\Requests\UpdateRecruitmentCandidateRequest;
use App\Models\Candidate;
use App\Models\RecruitmentCandidate;
use App\Notifications\CvReceived;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use RealRashid\SweetAlert\Facades\Alert;

class RecruitmentCandidateController extends Controller
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
    public function store(StoreRecruitmentCandidateRequest $request)
    {
        if (Auth::user()->cannot('create', RecruitmentCandidate::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        $recruitment_candidate = new RecruitmentCandidate();
        $recruitment_candidate->recruitment_id = $request->recruitment_id;
        $recruitment_candidate->candidate_id = $request->candidate_id;
        if ($request->hasFile('cv_file')) {
            $path = 'dist/cv';

            !file_exists($path) && mkdir($path, 0777, true);

            $file = $request->file('cv_file');
            $name = time() . rand(1,100) . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $file->move($path, $name);

            $recruitment_candidate->cv_file = $path . '/' . $name;
        }
        $recruitment_candidate->batch = $request->batch;
        $recruitment_candidate->channel_id = $request->channel_id;
        $recruitment_candidate->creator_id = Auth::user()->id;
        $recruitment_candidate->save();

        // Send notification to candidate's email
        $candidate = Candidate::findOrFail($request->candidate_id);
        if ($candidate->email) {
            Notification::route('mail' , $candidate->email)->notify(new CvReceived($request->recruitment_id));
        }

        Alert::toast('Thêm ứng viên mới thành công!', 'success', 'top-right');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(RecruitmentCandidate $recruitmentCandidate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RecruitmentCandidate $recruitmentCandidate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRecruitmentCandidateRequest $request, RecruitmentCandidate $recruitmentCandidate)
    {
        if (Auth::user()->cannot('update', $recruitmentCandidate)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        $recruitmentCandidate->recruitment_id = $request->recruitment_id;
        $recruitmentCandidate->candidate_id = $request->candidate_id;
        if ($request->hasFile('cv_file')) {
            //Delete old file
            if (file_exists($recruitmentCandidate->cv_file)) {
                unlink(public_path($recruitmentCandidate->cv_file));
            }

            //Store new file
            $path = 'dist/cv';

            !file_exists($path) && mkdir($path, 0777, true);

            $file = $request->file('cv_file');
            $name = time() . rand(1,100) . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $file->move($path, $name);

            $recruitmentCandidate->cv_file = $path . '/' . $name;
        }
        $recruitmentCandidate->batch = $request->batch;
        $recruitmentCandidate->channel_id = $request->channel_id;
        $recruitmentCandidate->creator_id = Auth::user()->id;
        $recruitmentCandidate->save();

        Alert::toast('Sửa ứng viên mới thành công!', 'success', 'top-right');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RecruitmentCandidate $recruitmentCandidate)
    {
        //Remove uploaded file
        if (file_exists($recruitmentCandidate->cv_file)) {
            unlink(public_path($recruitmentCandidate->cv_file));
        }

        //Remove record
        $recruitmentCandidate->delete();
        Alert::toast('Xóa ứng viên thành công!', 'success', 'top-right');
        return redirect()->back();
    }
}
