<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusUpdatedNotification extends Notification
{
    use Queueable;

    public Order $order;
    public string $status;

    public function __construct(Order $order, string $status)
    {
        $this->order = $order;
        $this->status = $status;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $statusLabel = ucfirst($this->status);

        return (new MailMessage)
            ->subject('Order status updated')
            ->greeting('Hello ' . ($notifiable->name ?? 'Customer') . '!')
            ->line('Your order #' . str_pad($this->order->id, 4, '0', STR_PAD_LEFT) . ' has been updated to ' . $statusLabel . '.')
            ->action('View order', url('/customer/orders'))
            ->line('Thank you for shopping with us.');
    }
}
