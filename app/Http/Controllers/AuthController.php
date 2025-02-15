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
use App\Models\VerifiedVendor;
use App\Events\VendorStatusUpdated;



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
            'address' => 'nullable|string|max:255',
            'business_registration' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'mayor_permit' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'tin' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'proof_of_identity' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Create a new vendor record and set properties
        $vendor = new Vendor();
        $vendor->company_name = $request->companyName;
        $vendor->email = $request->email;
        $vendor->password = Hash::make($request->password);
        $vendor->full_name = $request->fullName;
        $vendor->gender = $request->gender;
        $vendor->address = $request->address;

        // Handle file uploads with storage path management
        $errorMessages = [];
        try {
            // Check and upload business registration
            if ($request->hasFile('business_registration')) {
                if ($request->file('business_registration')->getSize() > 2048000) {
                    $errorMessages[] = 'Business registration file is too large. Max size allowed is 2MB.';
                } else {
                    $vendor->business_registration = $request->file('business_registration')->store('documents');
                }
            }

            // Check and upload mayor permit
            if ($request->hasFile('mayor_permit')) {
                if ($request->file('mayor_permit')->getSize() > 2048000) {
                    $errorMessages[] = 'Mayor permit file is too large. Max size allowed is 2MB.';
                } else {
                    $vendor->mayor_permit = $request->file('mayor_permit')->store('documents');
                }
            }

            // Check and upload tin
            if ($request->hasFile('tin')) {
                if ($request->file('tin')->getSize() > 2048000) {
                    $errorMessages[] = 'TIN file is too large. Max size allowed is 2MB.';
                } else {
                    $vendor->tin = $request->file('tin')->store('documents');
                }
            }

            // Check and upload proof of identity
            if ($request->hasFile('proof_of_identity')) {
                if ($request->file('proof_of_identity')->getSize() > 2048000) {
                    $errorMessages[] = 'Proof of identity file is too large. Max size allowed is 2MB.';
                } else {
                    $vendor->proof_of_identity = $request->file('proof_of_identity')->store('documents');
                }
            }
        } catch (\Exception $e) {
            // If an unexpected exception occurs, return an error
            $errorMessages[] = 'An unexpected error occurred during file upload.';
        }

        // If any file was too large, return the user back with the error messages
        if (!empty($errorMessages)) {
            return back()->withErrors(['error' => $errorMessages])->withInput();
        }

        // Save vendor data
        $vendor->save();

        // Create VerifiedVendor record
        VerifiedVendor::create([
            'vendor_id' => $vendor->id, // Use the newly created vendor's ID
            'is_verified' => false, // Set to false as per requirement
            'verified_at' => null, // Initially null, as the vendor is not verified yet
        ]);

        // Send notification to the vendor
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
        $validated = request()->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        // Check if the vendor exists by email
        $vendor = \App\Models\Vendor::where('email', $validated['email'])->first();

        // If vendor doesn't exist, return email error
        if (!$vendor) {
            return redirect()->route('login')->withErrors(['email' => 'No vendor found with this email.'])
                ->withInput(request()->only('email'));
        }

        // Check if the vendor's status is 'Approved'
        if ($vendor->status !== 'Approved') {
            $statusMessage = match ($vendor->status) {
                'Pending' => 'Your application is still pending. Please wait for approval.',
                'Rejected' => 'Your application has been rejected. Please contact support.',
                default => 'Unexpected vendor status. Please contact support.',
            };

            return redirect()->route('login')->withErrors(['email' => $statusMessage])
                ->withInput(request()->only('email'));
        }

        // Attempt to login using the provided email and password
        $remember = request()->has('remember');

        if (auth()->guard('vendor')->attempt(['email' => $vendor->email, 'password' => $validated['password']], $remember)) {
            request()->session()->regenerate();

            Log::info('Vendor login attempt successful', ['vendor_id' => $vendor->id, 'remember_me' => $remember]);

            // Update the vendor's online status
            $vendor->update(['is_online' => true]);
            broadcast(new VendorStatusUpdated($vendor->id, $vendor->is_online));

            // Check if the vendor is verified
            $verifiedVendor = \App\Models\VerifiedVendor::where('vendor_id', $vendor->id)->first();

            if ($verifiedVendor && $verifiedVendor->is_verified) {
                return redirect()->route('dashboard')->with('success', 'Logged in successfully!');
            } else {
                return redirect()->route('profiles.show', $vendor->id)
                    ->with('message', 'Please complete your profile information below to proceed.');
            }
        }

        // Check if the password is incorrect (fails after email is verified)
        if (\App\Models\Vendor::where('email', $validated['email'])->exists()) {
            Log::warning('Vendor login attempt failed due to wrong password', ['email' => $vendor->email]);
            return redirect()->back()->withErrors(['password' => 'Invalid password.'])
                ->withInput(request()->only('email'));
        }

        // If no vendor found, return email error
        Log::warning('Vendor login attempt failed due to invalid email', ['email' => $validated['email']]);
        return redirect()->back()->withErrors(['email' => 'No vendor found with this email.'])
            ->withInput(request()->only('email'));
    }


    public function logout()
    {
        // Get the authenticated vendor
        $vendor = auth()->user();

        if ($vendor) {
            // Update the vendor's 'is_online' status to false
            $vendor->is_online = false;
            $vendor->save();

            // Broadcast the vendor's offline status
            broadcast(new VendorStatusUpdated($vendor->id, $vendor->is_online));
        }

        // Perform the logout
        auth()->logout();

        // Invalidate the session
        request()->session()->invalidate();

        // Regenerate CSRF token for security
        request()->session()->regenerateToken();

        // Redirect to the login page with a success message
        return redirect()->route('login')->with('success', 'Logged out successfully');
    }
}
