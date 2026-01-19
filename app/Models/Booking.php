<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class Booking extends Model
{
    protected $table = 'bookings';
    protected $fillable = [
        'id',
        'customer_name',
        'email',
        'status',
        'booking_date'
    ];

}
