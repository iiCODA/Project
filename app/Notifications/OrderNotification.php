<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderNotification extends Notification
{
    use Queueable;

    protected $action; 
    protected $order;  

    public function __construct($action, $order)
    {
        $this->action = $action;
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['database']; 
    }

    public function toArray($notifiable)
    {
        return [
            'action' => $this->action,
            'order_id' => $this->order->id,
            'message' => $this->generateMessage(),
        ];
    }

    private function generateMessage()
    {
        switch ($this->action) {
            case 'placed':
                return "Your order #{$this->order->id} has been placed successfully.";
            case 'updated':
                return "Your order #{$this->order->id} has been updated.";
            case 'deleted':
                return "Your order #{$this->order->id} has been deleted.";
            default:
                return "An update occurred on your order.";
        }
    }
}
