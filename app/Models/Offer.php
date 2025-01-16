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
        'title_en',
        'title_ar',
        'description_en',
        'description_ar',
        'image',
        'active',
        'type',
        'vendor_id',
        'trip_id',
        'effectivenes_id',
        'gift_id',
        'active',
    ];
    protected $table = 'offers';
    protected $appends = ['title','photo','description'];

    public function getTitleAttribute()
    {
        if (App::isLocale('en')) {
            return $this->attributes['title_en'] ?? $this->attributes['title_ar'];
        } else {
            return $this->attributes['title_ar'] ?? $this->attributes['title_en'];
        }
    }
    public function getDescriptionAttribute()
    {
        if (App::isLocale('en')) {
            return $this->attributes['description_en'] ?? $this->attributes['description_ar'];
        } else {
            return $this->attributes['description_ar'] ?? $this->attributes['description_en'];
        }
    }

    public function getPhotoAttribute()
    {
        return array_key_exists('image', $this->attributes) ? ($this->attributes['image'] != null ? asset('storage/offers/' . $this->attributes['image']) : null) : null;
    }
    public function vendor(){
        return $this->belongsTo(User::class,'vendor_id');
    }
    public function trip(){
        return $this->belongsTo(Trip::class,'trip_id');
    }
    public function gift(){
        return $this->belongsTo(Gift::class,'gift_id');
    }
    public function effectivenes(){
        return $this->belongsTo(Effectivenes::class,'effectivenes_id');
    }

}
