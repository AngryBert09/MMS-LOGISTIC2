<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class VendorFullPaymentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $invoice;

    public function __construct($invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * Notification channels
     */
    public function via($notifiable)
    {
        return ['mail', 'database']; // ✅ Store in the database too
    }

    /**
     * Email notification
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Full Payment Received for Invoice #' . $this->invoice->invoice_number)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('The invoice #' . $this->invoice->invoice_number . ' has been fully paid.')
            ->line('Total Amount: $' . number_format($this->invoice->orderItems->sum('total_price'), 2))
            ->line('Thank you for using our service!')
            ->action('View Invoice', url('/invoices/' . $this->invoice->id))
            ->line('Best regards, Your Company Name');
    }

    /**
     * Store notification in the database
     */
    public function toArray($notifiable)
    {
        return [
            'invoice_id' => $this->invoice->id,
            'invoice_number' => $this->invoice->invoice_number,
            'message' => 'Full payment received for invoice #' . $this->invoice->invoice_number,
            'amount' => $this->invoice->orderItems->sum('total_price'),
            'date' => now(),
        ];
    }
}
