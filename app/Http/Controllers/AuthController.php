<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Mail\WelcomeEmail;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VerifiedVendor;
use App\Notifications\WelcomeVendorNotification;
use App\Events\VendorStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Crypt;
use App\Mail\TwoFactorCodeMail;

class AuthController extends Controller
{
    public function register()
    {
        return view('auth.register');
    }


    public function store(Request $request)
    {
        Log::info('Store method called', ['request' => $request->all()]);

        // Validate the request
        $validator = Validator::make($request->all(), [
            'companyName' => 'required|string|max:255', // Make companyName optional
            'email' => 'required|email|unique:vendors,email', // Make email optional
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
            'fullName' => 'required|string|max:255',
            'gender' => 'required|string',
            'address' => 'nullable|string|max:255',
            'business_registration' => 'required|file|mimes:pdf,jpg,jpeg,png|mimetypes:application/pdf,image/jpeg,image/png|max:2048',
            'mayor_permit' => 'required|file|mimes:pdf,jpg,jpeg,png|mimetypes:application/pdf,image/jpeg,image/png|max:2048',
            'tin' => 'required|file|mimes:pdf,jpg,jpeg,png|mimetypes:application/pdf,image/jpeg,image/png|max:2048',
            'proof_of_identity' => 'required|file|mimes:pdf,jpg,jpeg,png|mimetypes:application/pdf,image/jpeg,image/png|max:2048',
        ]);

        // If validation fails, return with errors
        if ($validator->fails()) {
            Log::error('Validation failed', ['errors' => $validator->errors()]);
            return back()->withErrors($validator)->withInput();
        }

        // Begin database transaction
        DB::beginTransaction();

        try {
            // Default status
            $status = 'Pending';
            $companyName = $request->companyName; // Default company name
            $email = $request->email; // Default email

            // Check if encrypted data exists in the request
            if ($request->has('data')) {
                try {
                    Log::info('Encrypted data found in request', ['data' => $request->data]);

                    // Decrypt data
                    $decryptedData = Crypt::decryptString($request->data);
                    [$invitedEmail, $invitedName] = explode('|', $decryptedData);

                    Log::info('Decrypted data', ['invitedEmail' => $invitedEmail, 'invitedName' => $invitedName]);

                    // Use the decrypted email and name
                    $email = $invitedEmail; // Override email with decrypted email
                    $companyName = $invitedName; // Use decrypted name as company name

                    // Trim and convert both values to lowercase for accurate comparison
                    $trimmedRequestEmail = strtolower(trim($request->email));
                    $trimmedInvitedEmail = strtolower(trim($invitedEmail));

                    Log::info('Comparing emails:', [
                        'trimmedRequestEmail' => $trimmedRequestEmail,
                        'trimmedInvitedEmail' => $trimmedInvitedEmail,
                    ]);

                    // Check if email matches
                    if ($trimmedRequestEmail === $trimmedInvitedEmail) {
                        Log::info('Email matched! Approving vendor.');
                        $status = 'Approved';
                    } else {
                        Log::warning('Email mismatch. Keeping status as Pending.', [
                            'requestEmail' => $request->email,
                            'decryptedEmail' => $invitedEmail,
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error('Decryption failed', ['error' => $e->getMessage()]);
                }
            }

            // Create vendor
            $vendor = new Vendor([
                'company_name' => $companyName, // Use decrypted or request company name
                'email' => $email, // Use decrypted or request email
                'password' => Hash::make($request->password),
                'full_name' => $request->fullName,
                'gender' => $request->gender,
                'address' => $request->address,
                'status' => $status, // Set status dynamically
            ]);

            Log::info('Vendor instance created', ['vendor' => $vendor]);

            // Handle file uploads
            $errorMessages = $this->handleFileUploads($request, $vendor);
            if (!empty($errorMessages)) {
                Log::error('File upload errors', ['errors' => $errorMessages]);
                return back()->withErrors(['error' => $errorMessages])->withInput();
            }

            // Save vendor
            $vendor->save();
            Log::info('Vendor saved', ['vendor_id' => $vendor->id]);

            // Create verified vendor record
            VerifiedVendor::create([
                'vendor_id' => $vendor->id,
                'is_verified' => ($status === 'Approved'),
                'verified_at' => ($status === 'Approved') ? now() : null,
            ]);

            Log::info('VerifiedVendor record created', ['vendor_id' => $vendor->id]);

            // Store vendor_id in supplier performance table
            DB::table('supplier_performance')->insert([
                'vendor_id' => $vendor->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Log::info('Supplier performance record created', ['vendor_id' => $vendor->id]);

            // Notify vendor
            $vendor->notify(new WelcomeVendorNotification($vendor->full_name));
            Log::info('Welcome notification sent', ['vendor_id' => $vendor->id]);

            // Commit the transaction
            DB::commit();

            // Redirect to login with success message
            return redirect()->route('login')->with('confirmation_message', 'You have registered successfully! Please wait for confirmation from the admin in your email.');
        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollBack();
            Log::error('Transaction failed', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'An error occurred while processing your request. Please try again.'])->withInput();
        }
    }



    private function handleFileUploads(Request $request, Vendor $vendor): array
    {
        $errorMessages = [];
        $files = [
            'business_registration',
            'mayor_permit',
            'tin',
            'proof_of_identity',
        ];

        foreach ($files as $file) {
            if ($request->hasFile($file)) {
                if ($request->file($file)->getSize() > 2048000) {
                    $errorMessages[] = ucfirst(str_replace('_', ' ', $file)) . ' file is too large. Max size allowed is 2MB.';
                } else {
                    $vendor->$file = $request->file($file)->store('documents');
                }
            }
        }

        return $errorMessages;
    }

    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
            'role' => 'required|in:vendor,employee', // Ensure the role is either vendor or employee
        ]);

        $email = $validated['email'];
        $role = $validated['role'];

        Log::info('Attempting to authenticate', ['email' => $email, 'role' => $role]);

        // Determine the guard and model based on the selected role
        $guard = $role === 'vendor' ? 'vendor' : 'web';
        $model = $role === 'vendor' ? Vendor::class : User::class;

        // Retrieve the user/vendor from the database
        $user = $model::where('email', $email)->first(['id', 'email', 'status']);

        if (!$user) {
            Log::error('No user found with this email:', ['email' => $email]);
            return response()->json(['errors' => ['email' => 'No user found with this email.']], 422);
        }

        // Check status for vendors
        if ($role === 'vendor' && $user->status !== 'Approved') {
            $statusMessage = match ($user->status) {
                'Pending'  => 'Your application is still pending. Please wait for approval.',
                'Rejected' => 'Your application has been rejected. Please contact support.',
                default    => 'Unexpected status. Please contact support.',
            };

            Log::error('Vendor status not approved:', ['status' => $user->status]);
            return response()->json(['errors' => ['email' => $statusMessage]], 422);
        }

        // Check status for employees
        if ($role === 'employee' && $user->status !== 'active') {
            $statusMessage = match ($user->status) {
                'inactive' => 'Your account is inactive. Please contact support.',
                default    => 'Unexpected status. Please contact support.',
            };

            Log::error('Employee status not active:', ['status' => $user->status]);
            return response()->json(['errors' => ['email' => $statusMessage]], 422);
        }

        // Attempt authentication
        $remember = $request->has('remember');
        if (!Auth::guard($guard)->attempt(['email' => $user->email, 'password' => $validated['password']], $remember)) {
            Log::error('Invalid password for user:', ['email' => $user->email]);
            return response()->json(['errors' => ['password' => 'Invalid password.']], 422);
        }

        $request->session()->regenerate();
        Log::info('Login successful', ['user_id' => $user->id, 'role' => $role]);

        // Mark user as online (only for vendors)
        if ($role === 'vendor') {
            $user->update(['is_online' => true]);
            broadcast(new VendorStatusUpdated($user->id, $user->is_online));
        }

        // Handle 2FA authentication (only for vendors)
        if ($role === 'vendor') {
            if ($user->last_2fa_at && $user->last_2fa_at->gt(now()->subDay())) {
                Log::info('2FA already verified within the last day:', ['user_id' => $user->id]);
                return response()->json(['redirect' => route('dashboard')], 200);
            }

            $twoFactorCode = random_int(100000, 999999);
            $twoFactorExpiresAt = now()->addMinutes(10);

            DB::table('vendor_2fa_codes')->updateOrInsert(
                ['vendor_id' => $user->id],
                ['code' => $twoFactorCode, 'expires_at' => $twoFactorExpiresAt, 'created_at' => now()]
            );

            Log::info('2FA code generated and saved:', ['user_id' => $user->id, 'code' => $twoFactorCode]);

            Mail::to($user->email)->send(new TwoFactorCodeMail($twoFactorCode));

            Log::info('2FA code sent to user email:', ['email' => $user->email]);

            return response()->json(['redirect' => route('2fa.verify')], 200);
        }

        // Redirect employees to the dashboard
        return response()->json(['redirect' => route('employee.dashboard')], 200);
    }



    public function logout()
    {
        $vendor = auth('vendor')->user();

        if ($vendor) {
            // Update vendor status to offline
            $vendor->update(['is_online' => false]);
            broadcast(new VendorStatusUpdated($vendor->id, $vendor->is_online));

            // Define cache keys for analyze and getMyPerformance
            Cache::flush();
        }

        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logged out successfully');
    }
}
