<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripOffer extends Model
{
    use HasFactory;
    protected $table = 'trip_offers';
    protected $fillable = ['trip_id', 'title','description','image'];
    protected $appends = ['photo'];

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function getPhotoAttribute()
    {
        return asset('storage/' . $this->image);
    }
}
