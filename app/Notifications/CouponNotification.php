<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CouponNotification extends Notification
{
    use Queueable;
    protected $coupon;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($coupon)
    {
        $this->coupon = $coupon;
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
        ->subject('特別クーポンのご案内')
        ->line('新規登録ありがとうございます！')
        ->line('こちらのクーポンコードを次回の購入時にご利用ください:')
        ->line('クーポンコード: ' . $this->coupon->code)
        ->line('割引: ' . $this->coupon->discount_value . '%')
        ->line('有効期限: ' . $this->coupon->expiration_date)
        ->line('ありがとうございます！');
    }
    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
