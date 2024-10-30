<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeEmail;
use App\Models\Comment;
use App\Models\Idea;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class DashboardController extends Controller
{

    public function index()
    {
        // Retrieve the authenticated vendor
        $vendor = auth()->user(); // Get the authenticated user (vendor)

        // Fetch notifications associated with the authenticated vendor
        $notifications = $vendor->notifications; // Adjust this line if necessary

        // Pass both the notifications and the vendor's profile picture to the view
        return view('dashboard', [
            'notifications' => $notifications,
            'profile_pic' => $vendor->profile_pic // Include the profile picture
        ]);
    }
}
