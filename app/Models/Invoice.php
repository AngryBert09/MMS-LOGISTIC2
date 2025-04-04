<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $primaryKey = 'invoice_id'; // Replace with your actual primary key column name



    protected $fillable = [
        'invoice_number',     // Invoice number (the unique generated invoice number)
        'po_id',              // Foreign key for purchase order
        'vendor_id',          // Foreign key for vendor (supplier)
        'invoice_date',       // Date the invoice was issued
        'due_date',
        'paypal_order_id',       // Due date for payment
        'currency_code',      // Currency used for the invoice
        'subtotal',           // Subtotal before tax and discount
        'tax_rate',           // Tax rate applied
        'tax_amount',         // Total tax amount
        'discount_amount',    // Any discount applied
        'total_amount',       // Total amount after tax and discounts
        'status',             // Status of the invoice (unpaid, paid, etc.)
    ];


    // Add any relationships, accessors, or methods below as needed

    // Example of a method to calculate total after applying tax or discounts
    public function calculateTotal($taxRate = 0, $discount = 0)
    {
        $amount = $this->total_amount;
        if ($taxRate) {
            $amount += $amount * ($taxRate / 100);
        }
        if ($discount) {
            $amount -= $discount;
        }
        return $amount;
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'po_id', 'po_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'invoice_id'); // Specify 'invoice_id' if it's the correct foreign key
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function receipt()
    {
        return $this->hasOne(PurchaseReceipt::class, 'invoice_id', 'invoice_id');
    }
}
