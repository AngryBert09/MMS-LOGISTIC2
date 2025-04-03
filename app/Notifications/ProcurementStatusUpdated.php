<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class ProcurementStatusUpdated extends Notification
{
    use Queueable;

    protected $procurement;
    protected $newStatus;

    public function __construct($procurement, $newStatus)
    {
        $this->procurement = $procurement;
        $this->newStatus = $newStatus;
    }

    public function via($notifiable)
    {
        return ['database']; // Store in database
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => "Your procurement request for {$this->procurement->item_name} has been updated to '{$this->newStatus}'.",
            'procurement_id' => $this->procurement->id,
            'status' => $this->newStatus,
        ];
    }
}
