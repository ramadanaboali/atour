<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookingGift extends Model
{
    use HasFactory,SoftDeletes;
     protected $fillable = [
        'status',
        'payment_way',
        'payment_id',
        'payment_status',
        'total',
        'customer_total',
        'gift_id',
        'lat',
        'long',
        'delivery_way',
        'delivery_address',
        'delivery_number',
        'location',
        'quntity',
        'user_id',
        'vendor_id',
        'admin_value',
        'quantity',
        'confirm_code',
        'cancel_date',
        'admin_value_status'
    ];

     public function user() :?BelongsTo
     {
        return $this->belongsTo(User::class);
     }
     public function gift() :?BelongsTo
     {
        return $this->belongsTo(Gift::class);
     }
     public function vendor() :?BelongsTo
     {
        return $this->belongsTo(User::class,'vendor_id');
     }

}
