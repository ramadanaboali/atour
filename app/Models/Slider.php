<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Slider extends Model
{
    use HasFactory;
    protected $table = 'sliders';
    protected $fillable = ['image','url','title_en','title_ar','description_en','description_ar'];
    protected $appends = ['photo','title','description'];
    public function getPhotoAttribute()
    {
        return array_key_exists('image', $this->attributes) ? ($this->attributes['image'] != null ? asset('storage/sliders/' . $this->attributes['image']) : null) : null;

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
