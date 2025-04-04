<?php

// app/Notifications/BidStatusUpdated.php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\BiddingDetail;

class BidStatusUpdated extends Notification
{
    protected $bidding;
    protected $status;

    public function __construct(BiddingDetail $bidding, $status)
    {
        $this->bidding = $bidding;
        $this->status = $status;
    }

    public function via($notifiable)
    {
        // Define which channels to send the notification through, for example: database, mail, etc.
        return ['database'];  // You can add more channels if needed, like ['mail', 'database']
    }

    public function toDatabase($notifiable)
    {
        return [
            'bidding_id' => $this->bidding->id,
            'status' => $this->status,
            'message' => 'Your bid has been ' . $this->status,
        ];
    }

    // If you want to send email or other types, define other methods like `toMail()`
}
// app/Notifications/BidStatusUpdated.php
