<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    // Define the table name if it differs from the default (plural form)
    protected $table = 'messages';
    protected $primaryKey = 'message_id';

    // Define which attributes are mass assignable
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'is_read',
    ];


    public function vendor()
    {
        return $this->belongsTo(Vendor::class); // Assuming you have a Vendor model
    }

    // Optionally, if you're using timestamps in the table
    public $timestamps = true;
}
