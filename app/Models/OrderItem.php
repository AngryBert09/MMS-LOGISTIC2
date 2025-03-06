<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    // Specify the table if it's not the plural of the model name
    protected $table = 'order_items';

    // Define the fillable attributes
    protected $fillable = [
        'po_id', // Foreign key reference to PurchaseOrder
        'item_description',
        'invoice_id',
        'receipt_id',
        'total_price',
        'quantity',
        'unit_price',
    ];

    // Define the relationship with PurchaseOrder
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'po_id', 'po_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
}
