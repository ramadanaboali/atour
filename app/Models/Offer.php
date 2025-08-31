<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

class Offer extends Model
{
    use HasFactory;
    protected $fillable = [
        'image',
        'active',
        'type',
        'url',
        'active',
    ];
    protected $table = 'offers';
    protected $appends = ['title','photo','description'];

        public function translations()
    {
        return $this->hasMany(OfferTranslation::class);
    }

    public function translate($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->translations->where('locale', $locale)->first();
    }


     public function getTitleAttribute()
    {
        return $this->translate()->title ?? null;
    }


    public function getDescriptionAttribute()
    {
        return $this->translate()->description ?? null;
    }
    public function getPhotoAttribute()
    {
        return array_key_exists('image', $this->attributes) ? ($this->attributes['image'] != null ? asset('storage/offers/' . $this->attributes['image']) : null) : null;
    }
   

}
