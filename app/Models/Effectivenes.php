<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

class Effectivenes extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'title_en',
        'title_ar',
        'description_ar',
        'description_en',
        'price',
        'from_date',
        'from_time',
        'to_date',
        'to_time',
        'location',
        'lat',
        'long',
        'people',
        'free_cancelation',
        'pay_later',
        'active',
        'cover',
        'city_id',
        'vendor_id',
        'rate',
        'created_by',
        'updated_by',
    ];
    protected $appends = ['title','photo','description'];


    public function getPhotoAttribute()
    {
        return array_key_exists('cover', $this->attributes) ? ($this->attributes['cover'] != null ? asset('storage/' . $this->attributes['cover']) : null) : null;
    }

    public function getTitleAttribute()
    {
        if (!array_key_exists('title_en', $this->attributes) || !array_key_exists('title_ar', $this->attributes)) {
            return "";
        }
        if (App::isLocale('en')) {
            return $this->attributes['title_en'] ?? $this->attributes['title_ar'];
        } else {
            return $this->attributes['title_ar'] ?? $this->attributes['title_en'];
        }
    }
    public function getDescriptionAttribute()
    {

        if (!array_key_exists('description_en', $this->attributes) || !array_key_exists('description_ar', $this->attributes)) {
            return "";
        }

        if (App::isLocale('en')) {
            return $this->attributes['description_en'] ?? $this->attributes['description_ar'];
        } else {
            return $this->attributes['description_ar'] ?? $this->attributes['description_en'];
        }
    }

    public function rates(): ?HasMany
    {
        return $this->hasMany(Rate::class, 'effectivenes_id');
    }
    public function city(): ?BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }
    public function vendor(): ?BelongsTo
    {
        return $this->belongsTo(User::class, 'vendor_id');
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
        return $this->hasMany(Attachment::class, 'model_id')->where('model_type', 'effectivenes');
    }

}
