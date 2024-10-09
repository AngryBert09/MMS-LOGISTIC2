<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimelineEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_order_id', // Foreign key to relate to PurchaseOrder
        'event_date',
        'event_title',
        'event_details',
    ];

    // Define the relationship back to PurchaseOrder
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }
}
