<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorBid extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'bidding_id',
        'bid_amount',
        'comments',
        'shortname',
    ];

    // Relationships
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function bidding()
    {
        return $this->belongsTo(BiddingDetail::class);
    }
}
