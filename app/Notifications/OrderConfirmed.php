<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderConfirmed extends Notification
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
        return (new MailMessage)
            ->subject("Order {$this->order->order_number} confirmed - Luma Lens")
            ->greeting("Thanks, {$this->order->customer_name}!")
            ->line("Your order {$this->order->order_number} has been confirmed and paid via Khalti.")
            ->line('Total: Rs. '.number_format((float) $this->order->total))
            ->action('Track your order', url('/track'))
            ->line('You can check your order status anytime using your order number and email.');
    }
}
