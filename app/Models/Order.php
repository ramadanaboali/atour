<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory,SoftDeletes;

    const STATUS_PENDING=0;
    const STATUS_COMPLEALED=4;
    const STATUS_CANCELED=5;

    public function trip(){
        return $this->belongsTo(Trip::class,'trip_id');
    }
}
