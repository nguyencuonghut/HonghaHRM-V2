<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFirstInterviewInvitationRequest;
use App\Http\Requests\UpdateFirstInterviewInvitationRequest;
use App\Models\Candidate;
use App\Models\Filter;
use App\Models\FirstInterviewInvitation;
use App\Models\RecruitmentCandidate;
use App\Notifications\FirstInterviewInvitationCreated;
use App\Notifications\RemindFirstInterviewInviation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use RealRashid\SweetAlert\Facades\Alert;

class FirstInterviewInvitationController extends Controller
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
    public function store(StoreFirstInterviewInvitationRequest $request)
    {
        if (Auth::user()->cannot('create', FirstInterviewInvitation::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        // Delete all previous invitation
        $old_invitations = FirstInterviewInvitation::where('recruitment_candidate_id', $request->recruitment_candidate_id)->get();
        foreach ($old_invitations as $old_invitation) {
            $old_invitation->destroy($old_invitation->id);
        }

        // Create new invitation
        $first_interview_invitation = new FirstInterviewInvitation();
        $first_interview_invitation->recruitment_candidate_id = $request->recruitment_candidate_id;
        $first_interview_invitation->interview_time = Carbon::createFromFormat('d/m/Y H:i', $request->interview_time);
        $first_interview_invitation->interview_location = $request->interview_location;
        $first_interview_invitation->contact = $request->contact;
        $first_interview_invitation->status = 'Đã gửi';
        $first_interview_invitation->save();

        // Send email notification to Candidate
        $recruitment_candidate = RecruitmentCandidate::findOrFail($request->recruitment_candidate_id);
        $candidate = Candidate::findOrFail($recruitment_candidate->candidate_id);
        if ($candidate->email) {
            Notification::route('mail' , $candidate->email)->notify(new FirstInterviewInvitationCreated($recruitment_candidate->id));
        }

        Alert::toast('Gửi lời mời thành công!', 'success', 'top-right');
        return redirect()->route('recruitments.show', $recruitment_candidate->recruitment_id);
    }

    /**
     * Display the specified resource.
     */
    public function show(FirstInterviewInvitation $firstInterviewInvitation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FirstInterviewInvitation $firstInterviewInvitation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFirstInterviewInvitationRequest $request, FirstInterviewInvitation $firstInterviewInvitation)
    {
        if (Auth::user()->cannot('update', $firstInterviewInvitation)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        $firstInterviewInvitation->feedback = $request->feedback;
        if ($request->note) {
            $firstInterviewInvitation->note = $request->note;
        } else {
            $firstInterviewInvitation->note = null;
        }
        $firstInterviewInvitation->save();

        $recruitment_candidate = RecruitmentCandidate::findOrFail($request->recruitment_candidate_id);
        // Send email reminder to Trưởng Đơn Vị
        if ('Đồng ý' == $firstInterviewInvitation->feedback) {
            $filter = Filter::where('recruitment_candidate_id', $recruitment_candidate->id)->first();
            Notification::route('mail' , $filter->approver->email)->notify(new RemindFirstInterviewInviation($recruitment_candidate->id));
        }
        Alert::toast('Cập nhật phản hồi thành công!', 'success', 'top-right');
        return redirect()->route('recruitments.show', $recruitment_candidate->recruitment_id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FirstInterviewInvitation $firstInterviewInvitation)
    {
        //
    }

    public function add($recruitment_candidate_id)
    {
        if (Auth::user()->cannot('create', FirstInterviewInvitation::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        $recruitment_candidate = RecruitmentCandidate::findOrFail($recruitment_candidate_id);
        return view('interview.first_interview_invitation',
                    ['recruitment_candidate' => $recruitment_candidate]
                    );
    }

    public function feedback($recruitment_candidate_id)
    {
        $first_interview_invitation = FirstInterviewInvitation::where('recruitment_candidate_id', $recruitment_candidate_id)->first();
        return view('.interview.first_interview_feedback',
                    ['first_interview_invitation' => $first_interview_invitation]
                    );
    }
}
