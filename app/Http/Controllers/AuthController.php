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
use App\Notifications\VendorRegistered;
use App\Notifications\WelcomeVendorNotification;



class AuthController extends Controller
{
    public function register()
    {
        return view('auth.register');
    }


    public function store(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'companyName' => 'required|string|max:255',
            'email' => 'required|email|unique:vendors,email',
            'password' => 'required|string|min:8|confirmed',
            'fullName' => 'required|string|max:255',
            'gender' => 'required|string',
            'address' => 'nullable|string|max:255', // Consolidated address field
            'business_registration' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'mayor_permit' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'tin' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'proof_of_identity' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        if ($validator->fails()) {
            Log::info($validator->errors()); // Log validation errors
            return back()->withErrors($validator)->withInput();
        }

        // Create a new vendor record and set properties
        $vendor = new Vendor();
        $vendor->company_name = $request->companyName;
        $vendor->email = $request->email;
        $vendor->password = Hash::make($request->password);
        $vendor->full_name = $request->fullName;
        $vendor->gender = $request->gender;
        $vendor->address = $request->address; // Use new consolidated address field

        // Handle file uploads with storage path management
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

        // Log vendor data before saving
        Log::info('Attempting to save vendor: ', $vendor->toArray());
        $vendor->save();
        Log::info('Vendor saved successfully: ', $vendor->toArray());

        // Send the VendorRegistered notification
        $vendor->notify(new WelcomeVendorNotification($vendor->full_name));

        // Redirect to login with a confirmation message
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
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        // Check if the vendor exists
        $vendor = \App\Models\Vendor::where('email', $validated['email'])->first();

        // If the vendor does not exist, return an error
        if (!$vendor) {
            return redirect()->route('login')->withErrors([
                'email' => 'No vendor found with this email.',
            ])->withInput(request()->only('email'));
        }

        // Check vendor status and handle accordingly
        switch ($vendor->status) {
            case 'Pending':
                return redirect()->route('login')->withErrors([
                    'email' => 'Your application is still pending. Please wait for approval.',
                ])->withInput(request()->only('email'));

            case 'Rejected':
                return redirect()->route('login')->withErrors([
                    'email' => 'Your application has been rejected. Please contact support.',
                ])->withInput(request()->only('email'));

            case 'Approved':
                // Continue to authenticate if the vendor is approved
                break;

            default:
                return redirect()->route('login')->withErrors([
                    'email' => 'Unexpected vendor status. Please contact support.',
                ])->withInput(request()->only('email'));
        }

        // Attempt to log in with "remember me" functionality
        $remember = request()->has('remember'); // Check if the "remember me" checkbox is checked

        // Attempt to authenticate the vendor
        if (auth()->guard('vendor')->attempt(['email' => $vendor->email, 'password' => $validated['password']], $remember)) {
            // Regenerate session to prevent session fixation attacks
            request()->session()->regenerate();

            // Redirect to the vendor dashboard with a success message
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
