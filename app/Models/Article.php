<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

class Article extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'type',
        'description_en',
        'description_ar',
        'tags',
        'start_date',
        'end_date',
        'title_en',
        'title_ar',
        'active',
        'created_by',
        'updated_by'
    ];
     protected $table = 'articles';
    protected $appends = ['title','text','description'];

    public function getTextAttribute()
    {
        if(App::isLocale('en')) {
            return $this->attributes['title_en'] ?? $this->attributes['title_ar'];
        } else {
            return $this->attributes['title_ar'] ?? $this->attributes['title_en'];
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
    public function getDescriptionAttribute()
    {
        if(App::isLocale('en')) {
            return $this->attributes['description_en'] ?? $this->attributes['description_ar'];
        } else {
            return $this->attributes['description_ar'] ?? $this->attributes['description_en'];
        }
    }

    public function attachments(){
        return $this->hasMany(Attachment::class,'model_id')->where('model_type','article');
    }
}
