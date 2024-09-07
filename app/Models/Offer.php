<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

class Offer extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'description_en',
        'description_ar',
        'discount',
        'start_date',
        'end_date',
        'title_en',
        'title_ar',
        'active',
        'user_id',
        'created_by',
        'updated_by'
    ];
    protected $table = 'offers';
    protected $appends = ['title','text','description'];

    public function supplier(){
        return $this->belongsTo(User::class,'user_id');
    }
  
    public function benfits_numbers()
    {
        return 0;
    }

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

}
