<?php

namespace App\Notifications;

use App\Models\Recruitment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RecruitmentToApprover extends Notification implements ShouldQueue
{
    use Queueable;

    protected $recruitment_id;

    /**
     * Create a new notification instance.
     */
    public function __construct($recruitment_id)
    {
        $this->recruitment_id = $recruitment_id;
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
        $recruitment = Recruitment::findOrFail($this->recruitment_id);
        $url = '/recruitments/' . $recruitment->id;
        if ($recruitment->position->division_id) {
            return (new MailMessage)
            ->subject('Đề nghị duyệt đề xuất tuyển dụng ' . $recruitment->position->name)
            ->line('Xin mời duyệt đề xuất tuyển dụng vị trí: ' . $recruitment->position->name . '.')
            ->line('Bộ phận: ' . $recruitment->position->division->name . '.')
            ->line('Phòng ban: ' . $recruitment->position->department->name . '.')
            ->action('Duyệt', url($url))
            ->line('Xin cảm ơn!');
        } else {
            return (new MailMessage)
            ->subject('Đề nghị duyệt đề xuất tuyển dụng ' . $recruitment->position->name)
            ->line('Xin mời duyệt đề xuất tuyển dụng vị trí: ' . $recruitment->position->name . '.')
            ->line('Phòng ban: ' . $recruitment->position->department->name . '.')
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
