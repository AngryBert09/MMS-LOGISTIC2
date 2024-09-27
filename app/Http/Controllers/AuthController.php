<?php

namespace App\Http\Controllers;


use App\Http\Requests\RegisterUserRequest;
use App\Mail\WelcomeEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Models\Vendor;
use Illuminate\Support\Facades\Log;



class AuthController extends Controller
{
    public function register()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'companyName' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:vendors,email',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'max:255',
                'regex:/[a-z]/', // Must contain at least one lowercase letter
                'regex:/[A-Z]/', // Must contain at least one uppercase letter
                'regex:/[0-9]/', // Must contain at least one digit
                'regex:/[@$!%*?&#]/', // Must contain a special character
            ],
            'fullName' => 'required|string|max:255',
            'gender' => 'required|string|in:male,female,other',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'business_registration' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'mayor_permit' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'tin' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'proof_of_identity' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ], [
            'companyName.required' => 'Please enter your company name.',
            'email.required' => 'An email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email is already associated with another vendor.',
            'email.regex' => 'Please provide a valid email format (e.g., example@domain.com).',
            'password.required' => 'A password is required.',
            'password.min' => 'Your password must be at least 8 characters long.',
            'password.confirmed' => 'The password confirmation does not match.',
            'password.regex' => 'Your password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
            'fullName.required' => 'Please enter your full name.',
            'gender.required' => 'Please select your gender.',
            'gender.in' => 'Gender must be one of the following: male, female, or other.',
            'business_registration.required' => 'A business registration document is required.',
            'mayor_permit.required' => 'A mayor’s permit document is required.',
            'tin.required' => 'A TIN document is required.',
            'proof_of_identity.required' => 'A proof of identity document is required.',
            'file.mimes' => 'The document must be a file of type: pdf, jpg, jpeg, png.',
            'file.max' => 'The document may not be greater than 2048 kilobytes.',
        ]);

        if ($validator->fails()) {
            Log::info($validator->errors()); // Log validation errors
            return back()->withErrors($validator)->withInput();
        }

        // Store files and create a new vendor record
        $vendor = new Vendor(); // Ensure you have a Vendor model
        $vendor->company_name = $request->companyName;
        $vendor->email = $request->email;
        $vendor->password = Hash::make($request->password);
        $vendor->full_name = $request->fullName;
        $vendor->gender = $request->gender;
        $vendor->city = $request->city;
        $vendor->state = $request->state;

        // Handle file uploads
        if ($request->hasFile('business_registration')) {
            $vendor->business_registration = $request->file('business_registration')->store('documents');
        }
        if ($request->hasFile('mayor_permit')) {
            $vendor->mayor_permit = $request->file('mayor_permit')->store('documents');
        }
        if ($request->hasFile('tin')) {
            $vendor->tin = $request->file('tin')->store('documents');
        }
        if ($request->hasFile('proof_of_identity')) {
            $vendor->proof_of_identity = $request->file('proof_of_identity')->store('documents');
        }

        // Log before saving
        Log::info('Attempting to save vendor: ', $vendor->toArray());
        $vendor->save();
        Log::info('Vendor saved successfully: ', $vendor->toArray());

        return redirect()->route('login')->with('confirmation_message', 'You have registered successfully! Please wait for confirmation from the admin in your email.');
    }


    public function login()
    {
        return view('auth.login');
    }

    public function authenticate()
    {
        // Validate the incoming request data
        $validated = request()->validate([
            'email' => [
                'required',
                'email',
                'max:255',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', // Additional regex validation for email format
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:255',
                'regex:/[a-z]/', // must contain at least one lowercase letter
                'regex:/[A-Z]/', // must contain at least one uppercase letter
                'regex:/[0-9]/', // must contain at least one digit
                'regex:/[@$!%*?&#]/', // must contain a special character
            ],
        ]);

        // Check if the vendor exists
        $vendor = \App\Models\Vendor::where('email', $validated['email'])->first();

        // If the vendor does not exist, return an error
        if (!$vendor) {
            return redirect()->route('login')->withErrors([
                'email' => 'No vendor found with this email.',
            ])->withInput(request()->only('email'));
        }

        // Check if the vendor's application is pending
        if ($vendor->status === 'Pending') {
            return redirect()->route('login')->withErrors([
                'email' => 'Your application is still pending. Please wait for approval.',
            ])->withInput(request()->only('email'));
        }

        // Check if the vendor's application is rejected
        if ($vendor->status !== 'Approved') {
            return redirect()->route('login')->withErrors([
                'email' => 'Your application has been rejected. Please contact support.',
            ])->withInput(request()->only('email'));
        }

        // Attempt to log in with "remember me" functionality
        $remember = request()->has('remember'); // Check if the "remember me" checkbox is checked

        // Attempt to authenticate the vendor
        if (auth()->attempt(['email' => $vendor->email, 'password' => $validated['password']], $remember)) {
            // Regenerate session to prevent session fixation attacks
            request()->session()->regenerate();

            // Redirect to the vendor dashboard with success message
            return redirect()->route('dashboard')->with('success', 'Logged in successfully!');
        }

        // If the email is valid but the password is incorrect, return a password error
        return redirect()->route('login')->withErrors([
            'password' => 'The provided password is incorrect.',
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
