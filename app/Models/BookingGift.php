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
        'lat',
        'long',
        'delivery_way',
        'delivery_address',
        'delivery_number',
        'location',
        'quntity',
        'user_id',
        'vendor_id'
    ];
         public const STATUS_PENDING = 0;
    public const STATUS_ACCEPTED = 1;
    public const STATUS_REJECTED = 2;
    public const STATUS_ONPROGRESS = 3;
    public const STATUS_COMPLEALED = 4;
    public const STATUS_CANCELED = 5;
    public const STATUS_WITHDRWAL = 6;

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
