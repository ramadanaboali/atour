<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

class Feature extends Model
{
     use HasFactory,SoftDeletes;
    protected $fillable = ["title_en", "title_ar", "description_en", "description_ar", "image"];

     protected $appends = ['photo','title','description','text'];
    public function getPhotoAttribute()
    {
         if (!array_key_exists('image', $this->attributes)) {
            return "";
        }
        return array_key_exists('image', $this->attributes) ? ($this->attributes['image'] != null ? asset('storage/' . $this->attributes['image']) : null) : null;

    }

    public function getTitleAttribute()
    {

        if (!array_key_exists('title_en', $this->attributes) || !array_key_exists('title_ar', $this->attributes)) {
            return "";
        }

        if (App::isLocale('en')) {
            return $this->attributes['title_en'] ?? $this->attributes['title_ar'];
        } else {
            return $this->attributes['title_ar'] ?? $this->attributes['title_en'];
        }
    }
    public function getTextAttribute()
    {

        if (!array_key_exists('title_en', $this->attributes) || !array_key_exists('title_ar', $this->attributes)) {
            return "";
        }

        if (App::isLocale('en')) {
            return $this->attributes['title_en'] ?? $this->attributes['title_ar'];
        } else {
            return $this->attributes['title_ar'] ?? $this->attributes['title_en'];
        }
    }
    public function getDescriptionAttribute()
    {

        if (!array_key_exists('description_en', $this->attributes) || !array_key_exists('description_ar', $this->attributes)) {
            return "";
        }

        if (App::isLocale('en')) {
            return $this->attributes['description_en'] ?? $this->attributes['description_ar'];
        } else {
            return $this->attributes['description_ar'] ?? $this->attributes['description_en'];
        }
    }
     public function trips():?BelongsToMany
    {
        return $this->belongsToMany(Trip::class, 'trip_features');

    }
}
