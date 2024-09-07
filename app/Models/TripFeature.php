<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripFeature extends Model
{
    use HasFactory;
    protected $fillable = [
    "title_ar",
    "title_en",
    "description_en",
    "description_ar",
    "trip_id",
    ];

    public function trip(){
        return $this->belongsTo(Trip::class);
    }
    
}
