<?php

namespace App\Notifications;

use App\Models\Plan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PlanCreated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $plan_id;

    /**
     * Create a new notification instance.
     */
    public function __construct($plan_id)
    {
        $this->plan_id = $plan_id;
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
        $plan = Plan::findOrFail($this->plan_id);
        $url = '/recruitments/' . $plan->recruitment_id;
        if ($plan->recruitment->position->division_id) {
            return (new MailMessage)
            ->subject('Đề nghị duyệt kế hoạch tuyển dụng ' . $plan->recruitment->position->name)
            ->line('Xin mời duyệt kế hoạch tuyển dụng vị trí: ' . $plan->recruitment->position->name . '.')
            ->line('Bộ phận: ' . $plan->recruitment->position->division->name . '.')
            ->line('Phòng ban: ' . $plan->recruitment->position->department->name . '.')
            ->action('Duyệt', url($url))
            ->line('Xin cảm ơn!');
        } else {
            return (new MailMessage)
            ->subject('Đề nghị duyệt kế hoạch tuyển dụng ' . $plan->recruitment->position->name)
            ->line('Xin mời duyệt kế hoạch tuyển dụng vị trí: ' . $plan->recruitment->position->name . '.')
            ->line('Phòng ban: ' . $plan->recruitment->position->department->name . '.')
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
