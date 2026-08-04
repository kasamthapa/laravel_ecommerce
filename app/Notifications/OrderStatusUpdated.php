<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusUpdated extends Notification
{
    use Queueable;

    public function __construct(private readonly Order $order) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $status = ucfirst(str_replace('_', ' ', $this->order->status));

        return (new MailMessage)
            ->subject("Order {$this->order->order_number} is now {$status} - Luma Lens")
            ->greeting("Hi {$this->order->customer_name},")
            ->line("Your order {$this->order->order_number} has been updated to: {$status}.")
            ->action('Track your order', url('/track'))
            ->line('Thanks for shopping with Luma Lens.');
    }
}
