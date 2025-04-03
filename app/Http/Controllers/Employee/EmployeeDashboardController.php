<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeDashboardController extends Controller
{
    public function index()
    {
        return view('employee.dashboard');
    }

    public function getEmployeeNotifications()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access'], 401);
        }

        $notifications = $user->unreadNotifications->map(function ($notification) {
            return [
                'id' => $notification->id,
                'title' => $notification->data['title'] ?? 'New Notification',
                'message' => $notification->data['message'] ?? 'You have a new update.',
                'type' => $notification->data['type'] ?? 'general', // Default to 'general' if not specified
                'related_id' => $notification->data['related_id'] ?? null, // Generic ID for any related model
                'url' => $notification->data['url'] ?? null,
                'created_at' => $notification->created_at->diffForHumans(),
            ];
        });

        return response()->json([
            'success' => true,
            'notifications' => $notifications,
        ]);
    }
}
