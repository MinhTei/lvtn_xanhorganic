<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order,
        public string $oldStatus,
        public string $newStatus,
    ) {
        $this->order->loadMissing(['user']);
    }

    public function envelope(): Envelope
    {
        $label = Order::STATUS_LABELS[$this->newStatus] ?? $this->newStatus;

        return new Envelope(
            subject: 'Cập nhật đơn #' . $this->order->order_code . ' — ' . $label,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'clients.email.order_status_updated',
            with: [
                'order' => $this->order,
                'oldStatus' => $this->oldStatus,
                'newStatus' => $this->newStatus,
                'statusLabels' => Order::STATUS_LABELS,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
