<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Vendor;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class ForgotPasswordController extends Controller
{
    // Show Forgot Password Form
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password'); // Ensure you have this view
    }

    // Send Reset Link
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::broker('vendors')->sendResetLink(
            $request->only('email')
        );

        // Redirect to the login page with a success message or error
        return $status === Password::RESET_LINK_SENT
            ? redirect()->route('login')->with('confirmation_message', __($status)) // Redirect to login
            : back()->withErrors(['email' => __($status)]); // Or go back on error
    }


    // Show Reset Password Form
    public function showResetPasswordForm(Request $request, $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }


    // Handle Password Reset
    public function resetPassword(Request $request)
    {
        // Validate input fields
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        Log::debug('Password reset request validated.', ['email' => $request->email]);

        // Retrieve the vendor using the email
        $vendor = Vendor::where('email', $request->email)->first();
        if (!$vendor) {
            Log::warning('No vendor found with the provided email.', ['email' => $request->email]);
            return back()->withErrors(['email' => 'No vendor found with this email address.']);
        }

        Log::debug('Vendor retrieved successfully.', ['vendor_id' => $vendor->id]);

        // Retrieve the reset token record
        $resetRecord = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (!$resetRecord) {
            Log::warning('No password reset request found.', ['email' => $request->email]);
            return redirect()->route('login')->withErrors(['message' => 'This reset link is invalid or has already been used.']);
        }

        // Verify token expiration (default 60 minutes)
        if (Carbon::parse($resetRecord->created_at)->addMinutes(config('auth.passwords.vendors.expire', 60))->isPast()) {
            Log::warning('Password reset token expired.', ['email' => $request->email]);
            return redirect()->route('login')->withErrors(['message' => 'This reset link has expired.']);
        }

        // Compare hashed token
        if (!Hash::check($request->token, $resetRecord->token)) {
            Log::warning('Invalid reset token.', ['email' => $request->email]);
            return redirect()->route('login')->withErrors(['message' => 'Invalid reset token.']);
        }

        Log::debug('Reset token verified successfully.', ['email' => $request->email]);

        // Check if the new password is different from the old one
        if (Hash::check($request->password, $vendor->password)) {
            Log::warning('New password is the same as the old password.', ['vendor_id' => $vendor->id]);
            return back()->withErrors(['password' => 'New password cannot be the same as the old password.']);
        }

        Log::debug('New password is valid and different from the old password.');

        // Perform password reset manually
        $vendor->update(['password' => Hash::make($request->password)]);
        Log::info('Vendor password updated successfully.', ['vendor_id' => $vendor->id]);

        // Remove the reset token from the database
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        Log::info('Password reset token deleted.', ['email' => $request->email]);

        return redirect()->route('login')->with('confirmation_message', 'Your password has been reset successfully. Please log in with your new password.');
    }
}
