<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Ensure this is the correct import
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract; // Interface import
use Illuminate\Notifications\Notifiable;
use App\Notifications\CustomResetPassword;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Mail;

class Vendor extends Authenticatable implements AuthenticatableContract
{
    use HasFactory, Notifiable; // Ensure Notifiable is used

    protected $guarded = ['id']; // This will prevent the 'id' field from being mass-assigned


    protected $fillable = [
        'company_name',
        'email',
        'password',
        'full_name',
        'gender',
        'status',
        'business_registration',
        'mayor_permit',
        'tax_identification_number',
        'proof_of_identity',
        'postal_code',
        'profile_pic',
        'phone_number',
        'address',
        'notifications_enabled',
        'is_online',
        'last_2fa_at'
    ];

    // Optionally, you can hide sensitive attributes
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_2fa_at' => 'datetime',

        // Add any other casts if needed
    ];

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function verifiedVendor()
    {
        return $this->hasOne(VerifiedVendor::class, 'vendor_id', 'id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function isActive()
    {
        // Example: Check if the last login time is within the last 5 minutes
        // You can adjust the condition based on your needs (e.g., online status or last activity)

        $lastActive = $this->last_activity_at; // Assuming you have a 'last_activity_at' field
        $timeout = now()->subMinutes(5); // Consider active if last activity is within 5 minutes

        return $lastActive && $lastActive >= $timeout;
    }

    public function unreadMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id')
            ->where('is_read', false);  // Only count unread messages
    }

    public function sendPasswordResetNotification($token)
    {
        $resetUrl = url(route('password.reset', ['token' => $token, 'email' => $this->email], false));
        Mail::to($this->email)->send(new ResetPasswordMail($resetUrl));
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function vendorBids()
    {
        return $this->hasMany(VendorBid::class);
    }
}
