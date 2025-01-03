<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

class Trip extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'title_ar',
        'title_en',
        'description_en',
        'description_ar',
        'price',
        'start_point',
        'program_time',
        'people',
        'trip_requirements',
        'free_cancelation',
        'available_days',
        'pay_later',
        'active',
        'cover',
        'available_times',
        'city_id',
        'vendor_id',
        'rate',
        'created_by',
        'updated_by',
    ];
    protected $casts = [
        'available_times' => 'array',
        'available_days' => 'array',
    ];
    protected $appends = ['title','photo','description'];

    public function getPhotoAttribute()
    {
        return array_key_exists('cover', $this->attributes) ? ($this->attributes['cover'] != null ? asset('storage/' . $this->attributes['cover']) : null) : null;
    }

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

    public function offers(): ?HasMany
    {
        return $this->hasMany(Offer::class, 'trip_id');
    }
    public function rates(): ?HasMany
    {
        return $this->hasMany(Rate::class, 'trip_id');
    }

    public function subcategory(): ?BelongsToMany
    {
        return $this->belongsToMany(SubCategory::class, TripSubCategory::class, 'trip_id', 'sub_category_id');
    }
    public function city(): ?BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }
    public function vendor(): ?BelongsTo
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function features(): ?HasMany
    {
        return $this->hasMany(TripFeature::class, 'trip_id');

    }
    public function createdBy(): ?BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function updatedBy(): ?BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'model_id')->where('model_type', 'trip');
    }

}
