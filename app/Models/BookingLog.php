<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingLog extends Model
{
    protected $connection = 'analytics';

    protected $fillable = [
        'booking_id',
        'action',
        'payload'
    ];

    protected $casts = [
        'payload' => 'array'
    ];
}
