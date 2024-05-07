<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

class WhyBooking extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'title_en',
        'title_ar',
        'image',
        'description_en',
        'description_ar',
        'active',
        'created_by',
        'updated_by'
    ];
    protected $table = "why_bookings";

    protected $appends = ['photo','title','description'];
    
    public function getPhotoAttribute()
    {
        return array_key_exists('image', $this->attributes) ? ($this->attributes['image'] != null ? asset('storage/why_bookings/' . $this->attributes['image']) : null) : null;

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
            return $this->attributes['title_en'] ?? $this->attributes['title_ar'];
        } else {
            return $this->attributes['title_ar'] ?? $this->attributes['title_en'];
        }
    }
}
