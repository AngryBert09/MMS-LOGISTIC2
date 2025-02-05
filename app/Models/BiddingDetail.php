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
        'deadline',
        'vendor_id',
        'description',
        'comments',
        'bid_amount'
    ];
}
