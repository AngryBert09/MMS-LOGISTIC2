<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Ensure this is the correct import
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract; // Interface import
use Illuminate\Notifications\Notifiable;

class Vendor extends Authenticatable implements AuthenticatableContract
{
    use HasFactory, Notifiable; // Ensure Notifiable is used

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
}
