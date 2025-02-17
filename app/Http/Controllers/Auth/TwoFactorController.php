<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Models\Vendor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Mail\TwoFactorCodeMail;
use Illuminate\Support\Facades\Log;


class TwoFactorController extends Controller
{
    public function showVerifyForm()
    {

        return view('auth.two-factor');
    }

    public function verify(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'code' => 'required|digits:6',
        ]);

        // Get the authenticated vendor
        $vendor = Auth::guard('vendor')->user();

        if (!$vendor) {
            // Return user-friendly error message for expired session
            return response()->json(['success' => false, 'message' => 'Your session has expired. Please log in again to continue.']);
        }

        // Retrieve the 2FA record for the vendor
        $twoFactorRecord = DB::table('vendor_2fa_codes')
            ->where('vendor_id', $vendor->id)
            ->where('expires_at', '>', now())
            ->first();

        // Check if the 2FA code is valid
        if (!$twoFactorRecord || $request->code != $twoFactorRecord->code) {
            // Return user-friendly error message for invalid or expired code
            return response()->json(['success' => false, 'message' => 'The code you entered is invalid or has expired. Please try again.']);
        }

        // Update the last 2FA authentication timestamp
        $vendor->update(['last_2fa_at' => now()]);

        // Delete the used 2FA code
        DB::table('vendor_2fa_codes')->where('vendor_id', $vendor->id)->delete();

        // Return success message
        return response()->json(['success' => true, 'message' => 'Your two-factor authentication was successfully verified.']);
    }


    public function resendOtp(Request $request)
    {
        $vendor = Auth::guard('vendor')->user();

        if (!$vendor) {
            return response()->json(['success' => false, 'message' => 'Session expired. Please log in again.']);
        }

        // Generate and store new 2FA code
        $twoFactorCode = random_int(100000, 999999);
        $twoFactorExpiresAt = now()->addMinutes(10);

        DB::table('vendor_2fa_codes')->updateOrInsert(
            ['vendor_id' => $vendor->id],
            ['code' => $twoFactorCode, 'expires_at' => $twoFactorExpiresAt, 'created_at' => now()]
        );

        // Send new 2FA code via email
        Mail::to($vendor->email)->send(new \App\Mail\TwoFactorCodeMail($twoFactorCode));

        return response()->json(['success' => true, 'message' => 'A new OTP has been sent to your email address.']);
    }
}
