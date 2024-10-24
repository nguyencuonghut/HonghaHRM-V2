<?php

namespace App\Notifications;

use App\Models\Recruitment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CvReceived extends Notification implements ShouldQueue
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
        return (new MailMessage)
            ->subject('[Honghafeed] Thông báo đã nhận hồ sơ ứng tuyển cho vị trí ' . $recruitment->position->name)
            ->line('Chúng tôi đã nhận được hồ sơ ứng tuyển của bạn cho vị trí: ' . $recruitment->position->name . '.')
            ->line('Cảm ơn bạn đã gửi hồ sơ!')
            ->line('Chúng tôi sẽ liên hệ lại với bạn ngay khi có lịch phỏng vấn.')
            ->line('Đây là mail tự động, bạn vui lòng không phản hồi lại email này.')
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
