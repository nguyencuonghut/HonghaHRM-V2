<?php

namespace App\Notifications;

use App\Models\RecruitmentRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RecruitmentRequestToApprover extends Notification implements ShouldQueue
{
    use Queueable;

    protected $recruitment_request_id;

    /**
     * Create a new notification instance.
     */
    public function __construct($recruitment_request_id)
    {
        $this->recruitment_request_id = $recruitment_request_id;
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
        $recruitment_request = RecruitmentRequest::findOrFail($this->recruitment_request_id);
        $url = '/recruitment_requests/' . $recruitment_request->id;
        if ($recruitment_request->position->division_id) {
            return (new MailMessage)
            ->subject('Đề nghị duyệt đề xuất tuyển dụng ' . $recruitment_request->position->name)
            ->line('Xin mời duyệt đề xuất tuyển dụng vị trí: ' . $recruitment_request->position->name . '.')
            ->line('Bộ phận: ' . $recruitment_request->position->division->name . '.')
            ->line('Phòng ban: ' . $recruitment_request->position->department->name . '.')
            ->action('Duyệt', url($url))
            ->line('Xin cảm ơn!');
        } else {
            return (new MailMessage)
            ->subject('Đề nghị duyệt đề xuất tuyển dụng ' . $recruitment_request->position->name)
            ->line('Xin mời duyệt đề xuất tuyển dụng vị trí: ' . $recruitment_request->position->name . '.')
            ->line('Phòng ban: ' . $recruitment_request->position->department->name . '.')
            ->action('Duyệt', url($url))
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
