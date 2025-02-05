<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Returns extends Model
{
    use HasFactory;

    protected $primaryKey = 'return_id';


    protected $fillable = [
        'quantity_return',
        'return_reason',
        'return_status',
        'return_date',
    ];
}
