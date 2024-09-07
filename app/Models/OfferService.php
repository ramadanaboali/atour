<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferService extends Model
{
    use HasFactory;

    protected $fillable = ['offer_id'];


    public function offer()
    {
        return $this->belongsTo(Offer::class,'offer_id');
    }
}
