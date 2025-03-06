<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\BiddingDetail;

class VendorChosenNotification extends Notification
{
    use Queueable;

    protected $bidding;

    /**
     * Create a new notification instance.
     */
    public function __construct(BiddingDetail $bidding)
    {
        $this->bidding = $bidding;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail', 'database']; // Sends both email & database notification
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Congratulations! You Have Been Chosen for a Bid')
            ->greeting('Hello, ' . $notifiable->name)
            ->line('You have been selected as the vendor for the bidding item: ' . $this->bidding->item_name)
            ->action('View Bid Details', url('/biddings/' . $this->bidding->id))
            ->line('Thank you for your participation!');
    }

    /**
     * Store the notification in the database.
     */
    public function toDatabase($notifiable)
    {
        return new DatabaseMessage([
            'biddingId' => $this->bidding->id,
            'message' => 'You have been selected as the vendor for the bidding item: ' . $this->bidding->item_name,
            'url' => url('/biddings/' . $this->bidding->id),
        ]);
    }
}
