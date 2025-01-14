<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class VendorStatusUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $vendor_id;
    public $is_online;

    public function __construct($vendorId, $isOnline)
    {
        $this->vendor_id = $vendorId;
        $this->is_online = $isOnline;
    }

    public function broadcastOn()
    {
        return new Channel('vendor-status');
    }

    public function broadcastAs()
    {
        return 'status-updated';
    }
}
