<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcurementRequest extends Model
{
    use HasFactory;

    protected $table = 'procurement_requests'; // Specify table name

    protected $fillable = [
        'item_name',
        'quantity',
        'starting_price',
        'deadline',
        'vendor_id',
        'description',
        'status'
    ];

    protected $casts = [
        'starting_price' => 'decimal:2',
        'deadline' => 'datetime',
    ];

    /**
     * Relationship: Procurement Request belongs to an Employee
     */


    /**
     * Relationship: Procurement Request belongs to a Vendor
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Scope: Fetch only open procurement requests
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'Open');
    }
}
