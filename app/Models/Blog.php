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
    'title_en',
    'title_ar',
    'publisher_name',
    'publisher_image',
    'cover',
    'content_en',
    'content_ar',
    'active',
    'created_by',
    'updated_by',
    ];
     protected $table = 'blogs';
    protected $appends = ['photo','publisherphoto','title','text','content'];

    public function getPhotoAttribute()
    {
        return array_key_exists('cover', $this->attributes) ? ($this->attributes['cover'] != null ? asset('storage/blogs/' . $this->attributes['cover']) : null) : null;

    }
    public function getPublisherphotoAttribute()
    {
        return array_key_exists('publisher_image', $this->attributes) ? ($this->attributes['publisher_image'] != null ? asset('storage/blogs/' . $this->attributes['publisher_image']) : null) : null;

    }

    public function getTextAttribute()
    {
        if(App::isLocale('en')) {
            return $this->attributes['title_en'] ?? $this->attributes['title_ar'];
        } else {
            return $this->attributes['title_ar'] ?? $this->attributes['title_en'];
        }
    }
    public function getContentAttribute()
    {
        if(App::isLocale('en')) {
            return $this->attributes['content_en'] ?? $this->attributes['content_ar'];
        } else {
            return $this->attributes['content_ar'] ?? $this->attributes['content_en'];
        }
    }

    public function getTitleAttribute()
    {
        if(App::isLocale('en')) {
            return $this->attributes['title_en'] ?? $this->attributes['title_ar'];
        } else {
            return $this->attributes['title_ar'] ?? $this->attributes['title_en'];
        }
    }

    public function attachments(){
        return $this->hasMany(Attachment::class,'model_id')->where('model_type','blog');
    }
}
