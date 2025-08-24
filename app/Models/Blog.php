<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

class Blog extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
    'publisher_name',
    'publisher_image',
    'cover',
    'active',
    'created_by',
    'updated_by',
    ];
     protected $table = 'blogs';
    protected $appends = ['photo','publisherphoto','title','text','description'];

    public function getPhotoAttribute()
    {
        return array_key_exists('cover', $this->attributes) ? ($this->attributes['cover'] != null ? asset('storage/' . $this->attributes['cover']) : null) : null;

    }
    public function getPublisherphotoAttribute()
    {
        return array_key_exists('publisher_image', $this->attributes) ? ($this->attributes['publisher_image'] != null ? asset('storage/' . $this->attributes['publisher_image']) : null) : null;

    }

   public function getTextAttribute()
    {
        return $this->translations->first()->title ?? '';
    }

    public function getTitleAttribute()
    {
        return $this->translations->first()->title ?? '';
    }
    public function getDescriptionAttribute()
    {
        return $this->translations->first()->description ?? '';
    }

    public function translations()
    {
        return $this->hasMany(BlogTranslation::class);
    }

    public function translate($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->translations->where('locale', $locale)->first();
    }


    public function attachments(){
        return $this->hasMany(Attachment::class,'model_id')->where('model_type','blog');
    }
}
