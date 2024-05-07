<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

class Add extends Model
{
   use HasFactory,SoftDeletes;
    protected $fillable = [
        'description_en',
        'description_ar',
        'location',
        'start_date',
        'end_date',
        'title_en',
        'title_ar',
        'active',
        'image',
        'views',
        'created_by',
        'updated_by'
    ];
     protected $table = 'adds';
    protected $appends = ['title','text','description','photo'];

    public function getTextAttribute()
    {
        if(App::isLocale('en')) {
            return $this->attributes['title_en'] ?? $this->attributes['title_ar'];
        } else {
            return $this->attributes['title_ar'] ?? $this->attributes['title_en'];
        }
    }

    public function getPhotoAttribute()
    {
        return array_key_exists('image', $this->attributes) ? ($this->attributes['image'] != null ? asset('storage/adds/' . $this->attributes['image']) : null) : null;

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

}
