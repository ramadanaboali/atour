<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderFee extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function vendor(){
        return $this->belongsTo(User::class,'vendor_id');
    }

    public function effectiveness(){
        return $this->belongsTo(Effectivenes::class,'effectiveness_id');
    }

    public function trip(){
        return $this->belongsTo(Trip::class,'trip_id');
    }

    public function gift(){
        return $this->belongsTo(Gift::class,'gift_id');
    }
}
