<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;
use Illuminate\Support\Facades\Log;
use App\Models\VerifiedVendor;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\EmailVerificationMail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;


class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // This can be used for listing profiles if needed in the future
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Not required for editing profiles
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Not required for editing profiles
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Retrieve vendor from the database, will throw 404 if not found
        $vendor = Vendor::findOrFail($id);

        // Check if the vendor is verified by querying the verified_vendor table.
        // Adjust the table and column names as necessary.
        $isVerified = DB::table('verified_vendors')
            ->where('vendor_id', $vendor->id)
            ->value('is_verified');

        // If the vendor is verified, use caching; otherwise, simply return the view.
        if ($isVerified) {
            // Define a cache key based on the vendor ID (or email if you prefer)
            $cacheKey = "vendor_profile_{$vendor->id}";

            // Retrieve vendor profile from cache or store it for 30 minutes if not present
            $vendor = Cache::remember($cacheKey, now()->addMinutes(30), function () use ($vendor) {
                return $vendor;
            });
        }

        return view('profiles.show', compact('vendor'));
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id) {}

    /**
     * Update the specified resource in storage.
     */


    public function update(Request $request, string $id)
    {
        Log::info('Update request received for vendor ID: ' . $id, $request->all());

        // Fetch the vendor
        $vendor = Vendor::findOrFail($id);
        Log::info('Current vendor data before update:', $vendor->toArray());

        // Check if the vendor is verified
        $verifiedVendor = VerifiedVendor::where('vendor_id', $vendor->id)->first();

        if (!$verifiedVendor || $verifiedVendor->verification_token !== 'verified') {
            Log::warning("Vendor ID: $id attempted to update profile but is not verified.");
            return redirect()->route('profiles.show', $vendor->id)->withErrors([
                'error' => 'You must verify your email before updating your profile.',
            ]);
        }

        // Validate request data
        $validatedData = $request->validate([
            'postal_code' => 'nullable|string|max:10',
            'phone_number' => 'nullable|string|max:15',
            'notifications_enabled' => 'nullable|boolean',
            'profile_pic' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'current_password' => 'nullable|required_with:new_password|current_password',
            'new_password' => 'nullable|required_with:current_password|string|min:8|confirmed',
        ]);

        // Update vendor details
        if ($request->filled('postal_code')) {
            $vendor->postal_code = $validatedData['postal_code'];
        }
        if ($request->filled('phone_number')) {
            $vendor->phone_number = $validatedData['phone_number'];
        }
        if ($request->has('notifications_enabled')) {
            $vendor->notifications_enabled = $request->input('notifications_enabled') === '1';
        }

        // Handle profile picture upload
        if ($request->hasFile('profile_pic')) {
            if ($vendor->profile_pic && file_exists(public_path($vendor->profile_pic))) {
                unlink(public_path($vendor->profile_pic));
                Log::info('Deleted old profile picture for vendor ID: ' . $id);
            }

            $profilePic = $request->file('profile_pic');
            $fileName = uniqid() . '_' . time() . '.' . $profilePic->getClientOriginalExtension();
            $profilePic->move(public_path('profile_pics'), $fileName);
            $vendor->profile_pic = 'profile_pics/' . $fileName;
            Log::info('Profile picture updated for vendor ID: ' . $id . ', file path: ' . $vendor->profile_pic);
        }

        // Change password only if vendor is verified
        if ($verifiedVendor->is_verified && $request->filled('new_password')) {
            $vendor->password = Hash::make($validatedData['new_password']);
            Log::info('Password updated for verified vendor ID: ' . $id);
        } elseif ($request->filled('new_password')) {
            Log::warning("Vendor ID: $id attempted to change password but is not verified.");
            return redirect()->route('profiles.show', $vendor->id)->withErrors([
                'error' => 'You must be verified before changing your password.',
            ]);
        }

        try {
            Log::info('Attempting to save updated vendor data:', $vendor->toArray());

            if ($vendor->save()) {
                Log::info('Vendor data after update:', $vendor->toArray());

                // Update VerifiedVendor's is_verified and verified_at timestamp
                if ($verifiedVendor) {
                    $verifiedVendor->is_verified = true;
                    $verifiedVendor->verified_at = now();
                    $verifiedVendor->save();
                    Log::info('VerifiedVendor is_verified set to true and verified_at updated for vendor ID: ' . $vendor->id);
                }

                return redirect()->route('profiles.show', $vendor->id)->with('success', 'Profile updated successfully.');
            } else {
                Log::error('Failed to save vendor data:', $vendor->toArray());
                return redirect()->route('profiles.show', $vendor->id)->withErrors(['error' => 'Failed to update profile.']);
            }
        } catch (\Exception $e) {
            Log::error('Exception occurred while updating vendor:', [
                'error' => $e->getMessage(),
                'vendor_data' => $vendor->toArray(),
            ]);
            return redirect()->route('profiles.show', $vendor->id)->withErrors(['error' => 'Failed to update profile.']);
        }
    }






    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Optional: Delete profile if needed
    }

    public function resendVerificationEmail($id)
    {
        Log::info("Attempting to resend verification email for vendor ID: $id");

        // Find the vendor
        $vendor = Vendor::findOrFail($id);

        // Check if the vendor is already verified
        if ($vendor->verifiedVendor && $vendor->verifiedVendor->is_verified) {
            Log::info("Vendor ID: $id is already verified.");
            return redirect()->route('profiles.show', $vendor->id)->with('message', 'You are already verified.');
        }

        // Get the VerifiedVendor model instance
        $verifiedVendor = $vendor->verifiedVendor;

        // If there is no VerifiedVendor record, create a new one
        if (!$verifiedVendor) {
            $verifiedVendor = new VerifiedVendor();
            $verifiedVendor->vendor_id = $vendor->id;
        }

        // Generate a new verification token
        $token = Str::random(60);

        // Debug the token before saving
        Log::info("Generated verification token for vendor ID: $id", ['token' => $token]);

        // Save the token in the VerifiedVendor model
        $verifiedVendor->verification_token = $token;
        $verifiedVendor->created_at = now();
        $verifiedVendor->save();

        // Send the email with the verification link
        try {
            Log::info("Sending verification email to: " . $vendor->email);
            Mail::to($vendor->email)->send(new EmailVerificationMail($vendor, $token));
            Log::info("Verification email sent successfully to: " . $vendor->email);
        } catch (\Exception $e) {
            Log::error("Failed to send verification email for vendor ID: $id", ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Failed to send verification email.']);
        }

        // Return a success message
        return back()->with('success', 'Verification email sent successfully.');
    }

    public function verifyEmail($vendorId, $token)
    {
        // Find the VerifiedVendor entry for the given vendor
        $verifiedVendor = VerifiedVendor::where('vendor_id', $vendorId)->firstOrFail();

        // Check if the vendor is already verified
        if ($verifiedVendor->verification_token === "verified") {
            return redirect()->route('profiles.show', $vendorId)->withErrors(['error', 'This link has already been used or the email is already verified.']);
        }

        // Check if the token has expired (assuming created_at stores the timestamp of the token creation)
        $tokenExpirationTime = $verifiedVendor->created_at->addMinutes(30);
        if (now()->greaterThan($tokenExpirationTime)) {
            // Set the verification_token to null before redirecting
            $verifiedVendor->verification_token = null;
            $verifiedVendor->save(); // Save the changes to the database

            return redirect()->route('profiles.show', $vendorId)->withErrors(['error', 'This link has expired. Please request a new verification email.']);
        }

        // Check if the token matches
        if ($verifiedVendor->verification_token === $token) {
            // Mark the vendor as verified
            $verifiedVendor->verification_token = "verified";  // Clear the token after successful verification
            $verifiedVendor->save();

            return redirect()->route('profiles.show', $vendorId)->with('success', 'Email successfully verified!');
        }

        return redirect()->route('profiles.show', $vendorId)->with('error', 'Invalid verification token.');
    }
}
