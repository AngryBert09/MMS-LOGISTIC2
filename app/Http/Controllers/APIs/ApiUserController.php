<?php

namespace App\Http\Controllers\APIs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ApiUserController extends Controller
{
    public function getApiLogin()
    {
        return view('api.login-api');
    }

    public function getApiRegister()
    {
        return view('api.signup-api');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // ✅ Save login log to database
            Log::info([
                'user_id' => $user->id,
                'email' => $user->email,
                'ip_address' => $request->ip(),
            ]);

            return redirect()->route('api.dashboard');
        }

        // ❌ Save failed attempt log
        Log::info([
            'email' => $request->input('email'),
            'ip_address' => $request->ip(),
        ]);

        return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }

    public function register(Request $request)
    {
        // ✅ Validate input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // ✅ Create user
        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hash password
        ]);

        // ✅ Auto-login after registration (optional)
        auth()->login($user);

        // ✅ Redirect to dashboard with success message
        return redirect()->route('api.dashboard')->with('success', 'Account created successfully.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidate session and regenerate CSRF token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('api.login'); // Redirect to login page
    }


    public function generateApiToken(Request $request)
    {
        $user = Auth::user(); // Get the authenticated user

        // Delete previous tokens (Optional: If you want only one active token per user)
        $user->tokens()->delete();

        // Create a new token
        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json(['token' => $token]);
    }

    public function getApiDashboard()
    {
        return view('api.dashboard-api');
    }
}
