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
        'gift_id',
        'delivery_way',
        'delivery_address',
        'quntity',
        'user_id',
        'vendor_id'
    ];
        const STATUS_PENDING = 0;
        const STATUS_ACCEPTED = 1;
        const STATUS_COMPLEATED = 2;
        const STATUS_REJECTED = 3;
        const STATUS_CANCELED = 4;
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
