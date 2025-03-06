<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseReceipt extends Model
{
    use HasFactory;

    // Define the table name if it's not the plural form of the model name
    protected $table = 'purchase_receipts';
    protected $primaryKey = 'receipt_id';

    protected $casts = [
        'receipt_date' => 'date',
    ];

    // Fillable attributes for mass assignment
    protected $fillable = [
        'receipt_id',      // Unique receipt number
        'po_id', // Foreign key to the related purchase order
        'vendor_id',
        'receipt_number',
        'invoice_id',
        'receipt_date',       // Foreign key to the vendor
        'total_amount',
        'tax_amount',
        'payment_method',
        'currency',
        'notes',
        'status',          // Receipt status (e.g., paid, pending, cancelled)
        'created_at',      // Timestamp for creation
        'updated_at',      // Timestamp for updates
    ];


    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'po_id', 'po_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'po_id', 'po_id'); // Ensure correct foreign key
    }


    // Additional methods can be added here
    public static function createReceipt($data)
    {
        return self::create($data);
    }

    public function updateReceipt($data)
    {
        return $this->update($data);
    }

    public function deleteReceipt()
    {
        return $this->delete();
    }

    // Example of a method to get formatted date
    public function getFormattedDateIssuedAttribute()
    {
        return $this->receipt_date->format('d-m-Y');
    }
}
