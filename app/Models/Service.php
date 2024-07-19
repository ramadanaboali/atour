<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'title_en',
        'title_ar',
        'city_id',
        'description_en',
        'description_ar',
        'price',
        'start_date',
        'end_date',
        'cover',
        'active',
        'vendor_id',
        'created_by',
        'updated_by'
    ];

    public function city(): ?BelongsTo
    {
        return $this->belongsTo(City::class,'city_id');
    }
    public function offers(): ?HasManyThrough
    {
        return $this->hasManyThrough(Offer::class, OfferService::class,'service_id','id');
    }
}
