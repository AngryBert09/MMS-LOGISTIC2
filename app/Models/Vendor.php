<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;


    protected $fillable = [
        'company_name',
        'email',
        'password',
        'full_name',
        'gender',
        'city',
        'state',
        'status',
        'business_registration_document',
        'mayor_permit',
        'tax_identification_number',
        'proof_of_identity',
    ];

    // Optionally, you can hide sensitive attributes
    protected $hidden = [
        'password',
    ];
}
