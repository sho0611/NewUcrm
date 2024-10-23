<?php

namespace App\Notifications; 

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AppointmentCreated extends Notification
{
    use Queueable;

    protected $appointment;
    protected $itemNames;
    /**
     * Create a new notification instance.
     *
     * @param mixed $appointment
     * @param string $itemNames
     * @return void
     */
    public function __construct($appointment, $itemNames)
    {
        $this->appointment = $appointment;
        $this->itemNames = $itemNames;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('予約管理')
            ->line('あなたの予約が完了しました。')
            ->line('予約日: ' . $this->appointment->appointment_date)
            ->line('予約時間: ' . $this->appointment->appointment_time)
            ->line('予約アイテム: ' . $this->itemNames)
            ->line('ありがとうございます！');
    }
}
