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
        'image',     
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
        return $this->hasMany(WhyBookingTranslation::class);
    }

    public function translate($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->translations->where('locale', $locale)->first();
    }
}
