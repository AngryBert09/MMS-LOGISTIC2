<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiddingDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_name',
        'starting_price',
        'po_id',
        'quantity',
        'deadline',
        'vendor_id',
        'employee_id',
        'description',
        'comments',
        'bid_amount'
    ];


    public function vendorBids()
    {
        return $this->hasMany(VendorBid::class, 'bidding_id');
    }

    // Define the vendor relationship
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    // Bidding Model

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'po_id', 'po_id');
    }
}
