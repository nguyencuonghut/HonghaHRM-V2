<?php

namespace App\Notifications;

use App\Models\Recruitment;
use App\Models\RecruitmentCandidate;
use App\Models\SecondInterviewInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SecondInterviewInvitationCreated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $recruitment_candidate_id;

    /**
     * Create a new notification instance.
     */
    public function __construct($recruitment_candidate_id)
    {
        $this->recruitment_candidate_id = $recruitment_candidate_id;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $recruitment_candidate = RecruitmentCandidate::findOrFail($this->recruitment_candidate_id);
        $recruitment = Recruitment::findOrFail($recruitment_candidate->recruitment_id);
        $second_interview_invitation = SecondInterviewInvitation::where('recruitment_candidate_id', $recruitment_candidate->id)->first();

        if ($recruitment->position->division_id) {
            return (new MailMessage)
                    ->subject('Mời phỏng vấn lần 2 cho vị trí ' . $recruitment->position->name)
                    ->line('Xin mời bạn tham gia phỏng vấn lần 2 cho vị trí: ' . $recruitment->position->name . '.')
                    ->line('Bộ phận: ' . $recruitment->position->division->name . '.')
                    ->line('Phòng ban: ' . $recruitment->position->department->name . '.')
                    ->line('Thời gian: ' . date('d/m/Y', strtotime($second_interview_invitation->interview_time)) . '.')
                    ->line('Địa điểm: ' . $second_interview_invitation->interview_location . '.')
                    ->line('Người liên hệ: ' . $second_interview_invitation->contact . '.')
                    ->line('Xin cảm ơn!');
        } else {
            return (new MailMessage)
                    ->subject('Mời phỏng vấn lần 2 cho vị trí ' . $recruitment->position->name)
                    ->line('Xin mời bạn tham gia phỏng vấn lần 2 cho vị trí: ' . $recruitment->position->name . '.')
                    ->line('Bộ phận: ' . $recruitment->position->division->name . '.')
                    ->line('Phòng ban: ' . $recruitment->position->department->name . '.')
                    ->line('Thời gian: ' . date('d/m/Y', strtotime($second_interview_invitation->interview_time)) . '.')
                    ->line('Địa điểm: ' . $second_interview_invitation->interview_location . '.')
                    ->line('Người liên hệ: ' . $second_interview_invitation->contact . '.')
                    ->line('Xin cảm ơn!');
        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
