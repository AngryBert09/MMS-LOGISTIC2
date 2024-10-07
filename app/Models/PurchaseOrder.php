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
        'customer_name',
        'order_date',
        'delivery_date',
        'order_status',
        'total_amount',
        'payment_terms',
        'delivery_location',
        'notes_instructions',
    ];
}
