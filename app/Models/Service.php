<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

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
    protected $appends = ['photo','title','description'];
    public function getPhotoAttribute()
    {
        return array_key_exists('cover', $this->attributes) ? ($this->attributes['cover'] != null ? asset('storage/services/' . $this->attributes['cover']) : null) : null;

    }

     public function getTitleAttribute()
    {
        if(App::isLocale('en')) {
            return $this->attributes['title_en'] ?? $this->attributes['title_ar'];
        } else {
            return $this->attributes['title_ar'] ?? $this->attributes['title_en'];
        }
    }
    public function getDescriptionAttribute()
    {
        if(App::isLocale('en')) {
            return $this->attributes['description_en'] ?? $this->attributes['description_ar'];
        } else {
            return $this->attributes['description_ar'] ?? $this->attributes['description_en'];
        }
    }

    public function city(): ?BelongsTo
    {
        return $this->belongsTo(City::class,'city_id');
    }
    public function offers(): ?HasManyThrough
    {
        return $this->hasManyThrough(Offer::class, OfferService::class,'service_id','id');
    }
}
