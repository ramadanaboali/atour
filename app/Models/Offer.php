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
        // 'vendor_id',
        // 'trip_id',
        // 'effectivenes_id',
        // 'gift_id',
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
    // public function vendor(){
    //     return $this->belongsTo(User::class,'vendor_id');
    // }
    // public function trip(){
    //     return $this->belongsTo(Trip::class,'trip_id');
    // }
    // public function gift(){
    //     return $this->belongsTo(Gift::class,'gift_id');
    // }
    // public function effectivenes(){
    //     return $this->belongsTo(Effectivenes::class,'effectivenes_id');
    // }

}
