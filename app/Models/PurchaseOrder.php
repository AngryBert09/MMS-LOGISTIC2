<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $table = 'purchase_orders'; // replace with your actual table name
    protected $primaryKey = 'po_id'; // specify the primary key
    public $incrementing = false; // set to false if po_id is not an auto-incrementing field
    protected $keyType = 'string'; // adjust this based on the data type of po_id

    protected $fillable = [
        'order_date',
        'delivery_date',
        'order_status',
        'total_amount',
        'payment_terms',
        'delivery_location',
        'notes_instructions',
    ];


    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'po_id', 'po_id');
    }

    public function timelineEvents()
    {
        return $this->hasMany(TimelineEvent::class, 'purchase_order_id', 'po_id'); // Adjust 'po_id' if needed
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'po_id', 'po_id');
    }

    public function receipts()
    {
        return $this->hasMany(PurchaseReceipt::class, 'po_id');
    }
}
