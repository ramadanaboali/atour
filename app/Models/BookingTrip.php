<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookingTrip extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'booking_date',
        'booking_day',
        'booking_time',
        'people_number',
        'children_number',
        'status',
        'payment_way',
        'payment_id',
        'payment_status',
        'total',
        'customer_total',

        'trip_id',
        'vendor_id',
        'user_id',
        'admin_value',
        'admin_value_status'
    ];

    public function user() :?BelongsTo
    {
    return $this->belongsTo(User::class);
    }
    public function trip() :?BelongsTo
    {
    return $this->belongsTo(Trip::class);
    }
    public function vendor() :?BelongsTo
    {
    return $this->belongsTo(User::class,'vendor_id');
    }
}
