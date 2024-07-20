<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferTrip extends Model
{
   use HasFactory;

    protected $fillable = ['offer_id', 'trip_id'];

    public function trip()
    {
        return $this->belongsTo(Trip::class, 'trip_id');
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class, 'offer_id');
    }
}
