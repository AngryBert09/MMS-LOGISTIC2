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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Notifications\VendorRegistered;
use App\Notifications\WelcomeVendorNotification;
use App\Models\VerifiedVendor;
use App\Events\VendorStatusUpdated;
use Illuminate\Support\Facades\Cache;



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
            'email' => 'required|email|unique:vendors,email,' . ($decodedEmail ?? $request->email),
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
        $vendor->email = $decodedEmail ?? $request->email; // Use decoded email if available
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

        // Set cache key for the vendor based on email
        $email = $validated['email'];
        $cacheKey = "vendor_auth_{$email}";

        // Attempt to retrieve vendor from cache, excluding the status field
        Log::info('Attempting to retrieve vendor from cache', ['email' => $email]);

        $vendor = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($email) {
            Log::info('Cache miss: Retrieving vendor from database', ['email' => $email]);
            // Fetch vendor data excluding the 'status' field
            return \App\Models\Vendor::where('email', $email)->first(['id', 'email']);
        });

        if ($vendor) {
            Log::info('Cache hit: Vendor data retrieved from cache', ['email' => $email]);
        } else {
            Log::info('Cache miss: Vendor not found in cache', ['email' => $email]);
        }

        // If no vendor found, return error
        if (!$vendor) {
            return redirect()->route('login')->withErrors(['email' => 'No vendor found with this email.']);
        }

        // Fetch the full vendor record from the database to get the status
        $vendor = \App\Models\Vendor::find($vendor->id);

        // Check vendor status
        if ($vendor->status !== 'Approved') {
            $statusMessage = match ($vendor->status) {
                'Pending' => 'Your application is still pending. Please wait for approval.',
                'Rejected' => 'Your application has been rejected. Please contact support.',
                default => 'Unexpected vendor status. Please contact support.',
            };
            return redirect()->back()->withErrors(['email' => $statusMessage]);
        }

        // Attempt to log in
        $remember = request()->has('remember');
        if (!auth()->guard('vendor')->attempt(['email' => $vendor->email, 'password' => $validated['password']], $remember)) {
            Log::warning('Login failed due to incorrect password.', ['email' => $vendor->email]);
            return redirect()->back()->withErrors(['password' => 'Invalid password.']);
        }

        // Regenerate session after successful login
        request()->session()->regenerate();
        Log::info('Vendor login successful', ['vendor_id' => $vendor->id]);

        // Update online status
        $vendor->update(['is_online' => true]);
        broadcast(new VendorStatusUpdated($vendor->id, $vendor->is_online));

        // Check if 2FA was already authenticated within the last 24 hours
        if ($vendor->last_2fa_at && $vendor->last_2fa_at->gt(now()->subDay())) {
            Log::info('Skipping 2FA, already authenticated today.', [
                'vendor_id' => $vendor->id,
                'last_2fa_at' => $vendor->last_2fa_at,
            ]);

            return redirect()->route('dashboard');
        }

        // Generate and store 2FA code
        $twoFactorCode = random_int(100000, 999999);
        $twoFactorExpiresAt = now()->addMinutes(10);

        DB::table('vendor_2fa_codes')->updateOrInsert(
            ['vendor_id' => $vendor->id],
            ['code' => $twoFactorCode, 'expires_at' => $twoFactorExpiresAt, 'created_at' => now()]
        );

        // Send 2FA code via email
        Mail::to($vendor->email)->send(new \App\Mail\TwoFactorCodeMail($twoFactorCode));

        Log::info('2FA code sent', ['vendor_id' => $vendor->id]);

        // Redirect to 2FA verification page
        $url = route('2fa.verify');
        Log::info('Redirecting to 2FA verify page', ['url' => $url]);

        return redirect($url)->with('success', 'A 2FA code has been sent to your email.');
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

        // Clear all cached data (optional)
        Cache::flush();

        // Redirect to the login page with a success message
        return redirect()->route('login')->with('success', 'Logged out successfully');
    }
}
