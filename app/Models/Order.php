<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'code',
        'tourist_name',
        'tourist_email',
        'tourist_phone',
        'promocode',
        'promocode_value',
        'payment_type',
        'payment_status',
        'order_date',
        'order_time',
        'address',
        'type',
        'details',
        'status',
        'total',
        'members',
        'childrens',
        'adults',
        'program_id',
        'trip_id',
        'user_id',
    ];
    const STATUS_PENDING=0;
    const STATUS_ACCEPTED=1;
    const STATUS_REJECTED=2;
    const STATUS_ONPROGRESS=3;
    const STATUS_COMPLEALED=4;
    const STATUS_CANCELED=5;

    public function trip(){
        return $this->belongsTo(Trip::class,'trip_id');
    }
    public function program(){
        return $this->belongsTo(TripProgram::class,'program_id');
    }
    public function client(){
        return $this->belongsTo(Client::class,'user_id');
    }
}
