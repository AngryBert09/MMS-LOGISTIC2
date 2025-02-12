<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerifiedVendor extends Model
{
    use HasFactory;
    protected $table = 'verified_vendors';
    // Disable timestamps
    public $timestamps = false;

    protected $casts = [
        'created_at' => 'datetime',
    ];


    protected $fillable = [
        'vendor_id',
        'is_verified',
        'verified_at',
    ];
}
