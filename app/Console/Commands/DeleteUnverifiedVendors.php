<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DeleteUnverifiedVendors extends Command
{
    protected $signature = 'vendors:delete-unverified';
    protected $description = 'Deletes unverified vendor accounts older than 7 days';


    public function handle()
    {
        // Get vendors that are unverified and meet the time condition (1 minute ago)
        $vendors = Vendor::whereHas('verifiedVendor', function ($query) {
            $query->where('is_verified', false)
                ->where('created_at', '<=', Carbon::now()->subDays(2));  // Delete the data in 2days
        })->get();

        // Loop through each vendor and delete
        foreach ($vendors as $vendor) {
            $vendor->delete();
            Log::info("Deleted unverified vendor ID: {$vendor->id} at " . Carbon::now());
        }

        // Log the total number of deleted vendors
        Log::info("Total deleted unverified vendor accounts: " . $vendors->count());
    }
}
