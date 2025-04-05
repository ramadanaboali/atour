<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookingEffectivene extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'status',
        'payment_way',
        'payment_id',
        'payment_status',
        'total',
         'customer_total',
        'effectivene_id',
        'user_id',
        'vendor_id',
        'admin_value',
                'confirm_code',
        'admin_value_status'
    ];


     public function user() :?BelongsTo
     {
        return $this->belongsTo(User::class);
     }
     public function effectivene() :?BelongsTo
     {
        return $this->belongsTo(Effectivenes::class,'effectivene_id');
     }
     public function vendor() :?BelongsTo
     {
        return $this->belongsTo(User::class,'vendor_id');
     }
}
