<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use ReCaptcha\ReCaptcha; // Removed unused import

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login-admin');
    }

    public function login(Request $request)
    {
        // Debugging line
        Log::debug('Login request received', ['request' => $request->all()]);

        // Validate the request
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Attempt to log the user in
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            // Check if the user has admin role
            if ($user->role === 'admin') {
                Log::info('Admin logged in: ' . $user->email);
                return response()->json(['message' => 'Admin logged in successfully', 'redirect' => route('admin.dashboard')], 200);
            } else {
                Auth::logout();
                return response()->json(['error' => 'Unauthorized'], 403);
            }
        } else {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }
    }


    public function logout(Request $request)
    {
        // Revoke any kinds of authentication
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('message', 'Admin logged out successfully');
    }
}
