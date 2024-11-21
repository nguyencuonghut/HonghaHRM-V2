<?php

namespace App\Http\Controllers;

use App\Models\FirstInterviewInvitation;
use App\Models\Recruitment;
use App\Models\RecruitmentCandidate;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        $events = [];

        // First interview invitations
        $first_interview_invitations = FirstInterviewInvitation::where('feedback', 'Đồng ý')->get();
        foreach($first_interview_invitations as $first_interview_invitation){
            $hour = Carbon::parse($first_interview_invitation->interview_time)->hour;
            $minute = Carbon::parse($first_interview_invitation->interview_time)->minute;

            $recruitment_candidate = RecruitmentCandidate::findOrFail($first_interview_invitation->recruitment_candidate_id);
            $recruitment = Recruitment::findOrFail($recruitment_candidate->recruitment_id);

            $background_color = '#00a65a'; //green
            $border_color = '#00a65a'; //green
            $event = [
                "title" => 'PV lần 1 - ' . $recruitment->position->name,
                "start" => $first_interview_invitation->interview_time,
                "allDay" => false,
                "backgroundColor" => $background_color,
                "borderColor" => $border_color,
            ];
            array_push($events, $event);
        }

        // Second interview invitations
        // $second_interview_invitations = SecondInterviewInvitation::all();
        // foreach($second_interview_invitations as $second_interview_invitation){
        //     $hour = Carbon::parse($second_interview_invitation->interview_time)->hour;
        //     $minute = Carbon::parse($second_interview_invitation->interview_time)->minute;

        //     $proposal_candidate = ProposalCandidate::findOrFail($second_interview_invitation->proposal_candidate_id);
        //     $proposal = RecruitmentProposal::findOrFail($proposal_candidate->proposal_id);

        //     $background_color = '#00a65a'; //green
        //     $border_color = '#00a65a'; //green
        //     $event = [
        //         "title" => 'PV lần 2 - ' . $proposal->company_job->name,
        //         "start" => $second_interview_invitation->interview_time,
        //         "allDay" => false,
        //         "backgroundColor" => $background_color,
        //         "borderColor" => $border_color,
        //     ];
        //     array_push($events, $event);
        // }
        return view('calendar.index', ['events' => $events]);
    }
}
