<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

class Requirement extends Model
{
  use HasFactory,SoftDeletes;
    protected $fillable = ["title_en", "title_ar", "description_en", "description_ar"];

     protected $appends = ['title','description'];

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
     public function trips():?BelongsToMany
    {
        return $this->belongsToMany(Trip::class, 'trip_requirements');

    }
}
