<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class TripFeature extends Model
{
    use HasFactory;
    protected $fillable = [
    "feature_id",
    "trip_id",
    ];


    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function feature()
    {
        return $this->belongsTo(Feature::class);
    }

}
