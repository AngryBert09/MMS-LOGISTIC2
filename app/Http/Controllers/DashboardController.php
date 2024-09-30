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
        // Assuming you have a relationship defined to fetch notifications
        $notifications = auth()->user()->notifications; // Adjust this line if necessary

        return view('dashboard', compact('notifications'));
    }
}
