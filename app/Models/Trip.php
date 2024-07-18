<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

class Trip extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
    'title_en',
    'title_ar',
    'description_en',
    'description_ar',
    'price',
    'phone',
    'start_point',
    'end_point',
    'cover',
    'free_cancelation',
    'cancelation_policy',
    'start_point_descriprion_en',
    'end_point_descriprion_en',
    'start_point_descriprion_ar',
    'end_point_descriprion_ar',
    'custom_fields',
    'active',
    'pay_later',
    'vendor_id',
    'created_by',
    'updated_by',
    ];
   protected $appends = ['title','photo','description','start_point_descriprion','end_point_descriprion'];

    public function getPhotoAttribute()
    {
        return array_key_exists('cover', $this->attributes) ? ($this->attributes['cover'] != null ? asset('storage/' . $this->attributes['cover']) : null) : null;
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
    public function getStartPointDescriprionAttribute()
    {
        if(App::isLocale('en')) {
            return $this->attributes['start_point_descriprion_en'] ?? $this->attributes['start_point_descriprion_ar'];
        } else {
            return $this->attributes['start_point_descriprion_ar'] ?? $this->attributes['start_point_descriprion_en'];
        }
    }
    public function getEndPointDescriprionAttribute()
    {
        if(App::isLocale('en')) {
            return $this->attributes['end_point_descriprion_en'] ?? $this->attributes['end_point_descriprion_ar'];
        } else {
            return $this->attributes['end_point_descriprion_ar'] ?? $this->attributes['end_point_descriprion_en'];
        }
    }



    public function vendor(): ?BelongsTo
    {
        return $this->belongsTo(User::class,'vendor_id');
    }

    public function programs(): ?HasMany
    {
        return $this->hasMany(TripProgram::class, 'trip_id');

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
