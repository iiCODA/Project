<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class OrderStatusChanged extends Notification
{
    use Queueable;

    private $orderDetails;

    public function __construct($orderDetails)
    {
        $this->orderDetails = $orderDetails;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'order_id' => $this->orderDetails['id'],
            'status' => $this->orderDetails['status'],
            'message' => "Your order status has been updated to: {$this->orderDetails['status']}",
        ];
    }
}
