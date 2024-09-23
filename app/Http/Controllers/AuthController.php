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

        // If the user does not exist, display an email error
        if (!$user) {
            return redirect()->route('login')->withErrors([
                'email' => 'No user found with this email.'
            ])->withInput(request()->only('email'));
        }

        // Attempt to log in
        if (auth()->attempt($validated)) {
            request()->session()->regenerate();
            return redirect()->route('dashboard')->with('success', 'Logged in successfully!');
        }

        // If the email is valid but the password is incorrect, display only the password error
        return redirect()->route('login')->withErrors([
            'password' => 'The provided password is incorrect.'
        ])->withInput(request()->only('email'));
    }


    public function logout()
    {
        auth()->logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logged out successfully');
    }


    public function forgotpassword()
    {
        return view('auth.forgot-password');
    }
}
