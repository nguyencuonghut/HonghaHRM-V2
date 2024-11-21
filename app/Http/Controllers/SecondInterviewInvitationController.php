<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSecondInterviewInvitationRequest;
use App\Http\Requests\UpdateSecondInterviewInvitationRequest;
use App\Models\Candidate;
use App\Models\RecruitmentCandidate;
use App\Models\SecondInterviewInvitation;
use App\Models\User;
use App\Notifications\RemindSecondInterviewInviation;
use App\Notifications\SecondInterviewInvitationCreated;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use RealRashid\SweetAlert\Facades\Alert;

class SecondInterviewInvitationController extends Controller
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
    public function store(StoreSecondInterviewInvitationRequest $request)
    {
        if (Auth::user()->cannot('create', SecondInterviewInvitation::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        // Delete all previous invitation
        $old_invitations = SecondInterviewInvitation::where('recruitment_candidate_id', $request->recruitment_candidate_id)->get();
        foreach ($old_invitations as $old_invitation) {
            $old_invitation->destroy($old_invitation->id);
        }

        // Create new invitation
        $second_interview_invitation = new SecondInterviewInvitation();
        $second_interview_invitation->recruitment_candidate_id = $request->recruitment_candidate_id;
        $second_interview_invitation->interview_time = Carbon::createFromFormat('d/m/Y H:i', $request->interview_time);
        $second_interview_invitation->interview_location = $request->interview_location;
        $second_interview_invitation->contact = $request->contact;
        $second_interview_invitation->status = 'Đã gửi';
        $second_interview_invitation->save();

        // Send email notification to Candidate
        $recruitment_candidate = RecruitmentCandidate::findOrFail($request->recruitment_candidate_id);
        $candidate = Candidate::findOrFail($recruitment_candidate->candidate_id);
        if ($candidate->email) {
            Notification::route('mail' , $candidate->email)->notify(new SecondInterviewInvitationCreated($recruitment_candidate->id));
        }

        Alert::toast('Gửi lời mời thành công!', 'success', 'top-right');
        return redirect()->route('recruitments.show', $recruitment_candidate->recruitment_id);
    }

    /**
     * Display the specified resource.
     */
    public function show(SecondInterviewInvitation $secondInterviewInvitation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SecondInterviewInvitation $secondInterviewInvitation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSecondInterviewInvitationRequest $request, SecondInterviewInvitation $secondInterviewInvitation)
    {
        if (Auth::user()->cannot('update', $secondInterviewInvitation)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        $secondInterviewInvitation->feedback = $request->feedback;
        if ($request->note) {
            $secondInterviewInvitation->note = $request->note;
        } else {
            $secondInterviewInvitation->note = null;
        }
        $secondInterviewInvitation->save();

        $recruitment_candidate = RecruitmentCandidate::findOrFail($request->recruitment_candidate_id);
        // Send email reminder to Trưởng Đơn Vị
        if ('Đồng ý' == $secondInterviewInvitation->feedback) {
            $interview_time = Carbon::parse($secondInterviewInvitation->interview_time);
            $delay = $interview_time->addMinutes(-60);

            $receivers = User::whereIn('role_id', [2, 4])->get();//2: Ban lãnh đạo, 4: Nhân sự
            foreach ($receivers as $receiver) {
                Notification::route('mail' , $receiver->email)->notify((new RemindSecondInterviewInviation($recruitment_candidate->id))->delay($delay));
            }
        }
        Alert::toast('Cập nhật phản hồi thành công!', 'success', 'top-right');
        return redirect()->route('recruitments.show', $recruitment_candidate->recruitment_id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SecondInterviewInvitation $secondInterviewInvitation)
    {
        //
    }

    public function add($recruitment_candidate_id)
    {
        $recruitment_candidate = RecruitmentCandidate::findOrFail($recruitment_candidate_id);
        return view('interview.second_interview_invitation',
                    [
                        'recruitment_candidate' => $recruitment_candidate
                    ]);
    }

    public function feedback($recruitment_candidate_id)
    {
        $second_interview_invitation = SecondInterviewInvitation::where('recruitment_candidate_id', $recruitment_candidate_id)->first();
        return view('interview.second_interview_feedback',
                    [
                        'second_interview_invitation' => $second_interview_invitation
                    ]);
    }
}
