<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class WelcomeVendorNotification extends Notification implements ShouldQueue
{
    use Queueable;

    // Add any properties if needed
    protected $vendorName;

    // Constructor to pass vendor data if needed
    public function __construct($vendorName)
    {
        $this->vendorName = $vendorName;
    }

    // Define the notification delivery channels
    public function via($notifiable)
    {
        // Specify that the notification should be stored in the database
        return ['database'];
    }

    // Store the notification in the database
    public function toArray($notifiable)
    {
        return [
            'message' => 'Welcome ' . $this->vendorName . '! Your account is now active.',
        ];
    }
}
