<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Broadcast::routes();

        require base_path('routes/channels.php');


        Broadcast::channel('chat.{vendorId}', function ($user, $vendorId) {
            return $user->id === (int) $vendorId; // Adjust as needed for access control
        });
    }
}
