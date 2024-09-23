<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Mail\WelcomeEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function register()
    {
        return view('auth.register');
    }

    public function store(RegisterUserRequest $request)
    {
        // No need to call $request->validate() manually
        $validated = $request->validated(); // Or directly use $request->only(['name', 'email', 'password'])

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'image' => 'profile/no_profilepic.png'
        ]);

        // Mail::to($user->email)->send(new WelcomeEmail($user));

        return redirect()->route('login')->with('success', 'Account created Successfully!');
    }


    public function login()
    {
        return view('auth.login');
    }

    public function authenticate()
    {
        $validated = request()->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        // Check if the user exists
        $user = \App\Models\User::where('email', $validated['email'])->first();

        if (!$user) {
            // Email not found, display error for both email and password
            return redirect()->route('login')->withErrors([
                'email' => 'No user found with this email.',
                'password' => 'The provided credentials do not match our records.'
            ])->withInput(request()->only('email'));
        }

        // Attempt to log in
        if (auth()->attempt($validated)) {
            request()->session()->regenerate();
            return redirect()->route('dashboard')->with('success', 'Logged in successfully!');
        } else {
            // Password incorrect, display error for both email and password
            return redirect()->route('login')->withErrors([
                'email' => 'The provided credentials do not match our records.',
                'password' => 'The provided credentials do not match our records.'
            ])->withInput(request()->only('email'));
        }
    }



    public function logout()
    {
        auth()->logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'logged out successfully');
    }
}
