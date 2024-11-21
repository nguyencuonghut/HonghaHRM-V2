<?php

namespace App\Notifications;

use App\Models\FirstInterviewInvitation;
use App\Models\Recruitment;
use App\Models\RecruitmentCandidate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RemindFirstInterviewInviation extends Notification implements ShouldQueue
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
        $first_interview_invitation = FirstInterviewInvitation::where('recruitment_candidate_id', $recruitment_candidate->id)->first();

        return (new MailMessage)
                ->subject('Mời phỏng vấn lần 1 các ứng viên cho vị trí ' . $recruitment->position->name)
                ->line('Xin mời bạn tham gia phỏng vấn lần 1 các ứng viên cho vị trí: ' . $recruitment->position->name . '.')
                ->line('Bộ phận: ' . $recruitment->position->division->name . '.')
                ->line('Phòng ban: ' . $recruitment->position->department->name . '.')
                ->line('Thời gian: ' . date('d/m/Y', strtotime($first_interview_invitation->interview_time)) . '.')
                ->line('Xin cảm ơn!');
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
