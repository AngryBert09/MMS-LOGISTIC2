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
        'city',
        'state',
        'status',
        'business_registration',
        'mayor_permit',
        'tax_identification_number',
        'proof_of_identity'
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
}
