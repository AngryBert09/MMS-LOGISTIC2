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
            'email' => 'required|email|unique:vendors,email',
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

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction(); // Start a database transaction

        try {
            $vendor = new Vendor([
                'company_name' => $request->companyName,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'full_name' => $request->fullName,
                'gender' => $request->gender,
                'address' => $request->address,
            ]);

            $errorMessages = $this->handleFileUploads($request, $vendor);

            if (!empty($errorMessages)) {
                return back()->withErrors(['error' => $errorMessages])->withInput();
            }

            $vendor->save();

            VerifiedVendor::create([
                'vendor_id' => $vendor->id,
                'is_verified' => false,
                'verified_at' => null,
            ]);

            // Store vendor_id in the supplier_performance table
            DB::table('supplier_performance')->insert([
                'vendor_id' => $vendor->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Notify the vendor
            $vendor->notify(new WelcomeVendorNotification($vendor->full_name));

            DB::commit(); // Commit transaction if everything is successful

            return redirect()->route('login')->with('confirmation_message', 'You have registered successfully! Please wait for confirmation from the admin in your email.');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction in case of an error
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
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Cache;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Mail;
    use GuzzleHttp\Client;

    public function authenticate(Request $request)
    {
        // Validate the request including reCAPTCHA
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
            'g-recaptcha-response' => 'required', // Add reCAPTCHA validation
        ]);

        // Verify reCAPTCHA
        $client = new Client();
        $response = $client->post('https://www.google.com/recaptcha/api/siteverify', [
            'form_params' => [
                'secret' => env('RECAPTCHA_SECRET_KEY'), // Ensure this is in your .env
                'response' => $validated['g-recaptcha-response'],
                'remoteip' => $request->ip(),
            ],
        ]);

        $body = json_decode((string)$response->getBody());
        if (!$body->success) {
            return response()->json(['errors' => ['captcha' => 'Invalid reCAPTCHA. Please try again.']], 422);
        }

        $email = $validated['email'];
        $cacheKey = "vendor_auth_{$email}";

        Log::info('Attempting to retrieve vendor from cache', ['email' => $email]);

        // Retrieve vendor from cache or database
        $vendor = Cache::remember($cacheKey, now()->addMinutes(2), function () use ($email) {
            Log::info('Cache miss: Retrieving vendor from database', ['email' => $email]);
            return Vendor::where('email', $email)->first(['id', 'email']);
        });

        if (!$vendor) {
            Cache::forget($cacheKey); // Ensure outdated cache is cleared
            return response()->json(['errors' => ['email' => 'No vendor found with this email.']], 422);
        }

        $vendor = Vendor::find($vendor->id);
        if (!$vendor) {
            Cache::forget($cacheKey);
            return response()->json(['errors' => ['email' => 'No vendor found with this email.']], 422);
        }

        // Check vendor status
        if ($vendor->status !== 'Approved') {
            $statusMessage = match ($vendor->status) {
                'Pending'  => 'Your application is still pending. Please wait for approval.',
                'Rejected' => 'Your application has been rejected. Please contact support.',
                default    => 'Unexpected vendor status. Please contact support.',
            };

            Cache::forget($cacheKey);
            return response()->json(['errors' => ['email' => $statusMessage]], 422);
        }

        // Attempt authentication
        $remember = $request->has('remember');
        if (!auth()->guard('vendor')->attempt(['email' => $vendor->email, 'password' => $validated['password']], $remember)) {
            Cache::forget($cacheKey);
            return response()->json(['errors' => ['password' => 'Invalid password.']], 422);
        }

        $request->session()->regenerate();
        Log::info('Vendor login successful', ['vendor_id' => $vendor->id]);

        // Mark vendor as online and update cache
        $vendor->update(['is_online' => true]);
        broadcast(new VendorStatusUpdated($vendor->id, $vendor->is_online));

        Cache::put($cacheKey, $vendor, now()->addMinutes(2)); // Refresh cache after successful login

        // Handle 2FA authentication
        if ($vendor->last_2fa_at && $vendor->last_2fa_at->gt(now()->subDay())) {
            return response()->json(['redirect' => route('dashboard')], 200);
        }

        $twoFactorCode = random_int(100000, 999999);
        $twoFactorExpiresAt = now()->addMinutes(10);

        DB::table('vendor_2fa_codes')->updateOrInsert(
            ['vendor_id' => $vendor->id],
            ['code' => $twoFactorCode, 'expires_at' => $twoFactorExpiresAt, 'created_at' => now()]
        );

        Mail::to($vendor->email)->send(new \App\Mail\TwoFactorCodeMail($twoFactorCode));

        return response()->json(['redirect' => route('2fa.verify')], 200);
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
