<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

class City extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'cities';
    protected $fillable = [ 'country_id','created_by','updated_by','active','image'];
    protected $appends = ['title','text','photo'];

    public function getPhotoAttribute()
    {
        return array_key_exists('image', $this->attributes) ? ($this->attributes['image'] != null ? asset('storage/cities/' . $this->attributes['image']) : null) : null;

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
        return $this->hasMany(CityTranslation::class);
    }

    public function translate($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->translations->where('locale', $locale)->first();
    }

    public function country(): ?BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function trips(): ?HasMany
    {
        return $this->hasMany(Trip::class, 'city_id');
    }

    public function gifts(): ?HasMany
    {
        return $this->hasMany(Gift::class, 'city_id');
    }
    public function effectivenes(): ?HasMany
    {
        return $this->hasMany(Effectivenes::class, 'city_id');
    }
    public function createdBy(): ?BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function updatedBy(): ?BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

}
