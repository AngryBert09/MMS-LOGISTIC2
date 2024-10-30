<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;
use Illuminate\Support\Facades\Log;
use App\Models\VerifiedVendor;

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
        $vendor = Vendor::findOrFail($id);
        return view('profiles.show', compact('vendor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $vendor = Vendor::findOrFail($id); // Retrieve the vendor by ID
        // return view('profiles.edit', compact('vendor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        Log::info('Update request received for vendor ID: ' . $id, $request->all());

        $validatedData = $request->validate([
            'postal_code' => 'nullable|string|max:10',
            'phone_number' => 'nullable|string|max:15',
            'notifications_enabled' => 'nullable|boolean',
            'profile_pic' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate profile picture
        ]);

        $vendor = Vendor::findOrFail($id);
        Log::info('Current vendor data before update:', $vendor->toArray());

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
            // Delete the old profile picture if it exists
            if ($vendor->profile_pic && file_exists(public_path($vendor->profile_pic))) {
                unlink(public_path($vendor->profile_pic));
                Log::info('Deleted old profile picture for vendor ID: ' . $id);
            }

            // Generate a unique file name for the new profile picture
            $profilePic = $request->file('profile_pic');
            $fileName = uniqid() . '_' . time() . '.' . $profilePic->getClientOriginalExtension();
            $profilePic->move(public_path('profile_pics'), $fileName);

            // Store the file path in the database
            $vendor->profile_pic = 'profile_pics/' . $fileName;
            Log::info('Profile picture updated for vendor ID: ' . $id . ', file path: ' . $vendor->profile_pic);
        }

        try {
            Log::info('Attempting to save updated vendor data:', $vendor->toArray());

            if ($vendor->save()) {
                Log::info('Vendor data after update:', $vendor->toArray());

                // Update is_verified to true in VerifiedVendor table if needed
                $verifiedVendor = VerifiedVendor::where('vendor_id', $vendor->id)->first();
                if ($verifiedVendor) {
                    $verifiedVendor->is_verified = true;
                    $verifiedVendor->save();
                    Log::info('VerifiedVendor is_verified set to true for vendor ID: ' . $vendor->id);

                    session()->flash('verified_message', 'You are now VERIFIED! You can now access the additional tabs.');
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
}
