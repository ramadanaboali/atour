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
        'start_point_ar',
        'start_point_en',
        'end_point_ar',
        'end_point_en',
        'program_time_en',
        'program_time_ar',
        'people',
        'free_cancelation',
        'available_days',
        'pay_later',
        'start_long',
        'end_lat',
        'start_long',
        'start_lat',
        'end_long',
        'end_lat',
        'active',
        'cover',
        'available_times',
        'city_id',
        'vendor_id',
        'rate',
        'steps_list',
        'created_by',
        'updated_by',
    ];
    protected $casts = [
        'available_times' => 'array',
        'available_days' => 'array',
        'steps_list' => 'array',
    ];
    protected $appends = ['title', 'photo', 'description', 'start_point', 'end_point', 'text', 'program_time','customer_price'];

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
    public function getProgramTimeAttribute()
    {
        if (!array_key_exists('program_time_en', $this->attributes) || !array_key_exists('program_time_ar', $this->attributes)) {
            return "";
        }
        if (App::isLocale('en')) {
            return $this->attributes['program_time_en'] ?? $this->attributes['program_time_ar'];
        } else {
            return $this->attributes['program_time_ar'] ?? $this->attributes['program_time_en'];
        }
    }
    public function getCustomerPriceAttribute()
    {
        if (!array_key_exists('price', $this->attributes) ) {
            return 0;
        }
        return $this->attributes['price']+$this->calculateAdminFees();
        
    }
    public function getTextAttribute()
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
    public function getStartPointAttribute()
    {
        if (!array_key_exists('start_point_en', $this->attributes) || !array_key_exists('start_point_ar', $this->attributes)) {
            return "";
        }
        if (App::isLocale('en')) {
            return $this->attributes['start_point_en'] ?? $this->attributes['start_point_ar'];
        } else {
            return $this->attributes['start_point_ar'] ?? $this->attributes['start_point_en'];
        }
    }
    public function getEndPointAttribute()
    {
        if (!array_key_exists('end_point_en', $this->attributes) || !array_key_exists('end_point_ar', $this->attributes)) {
            return "";
        }
        if (App::isLocale('en')) {
            return $this->attributes['end_point_en'] ?? $this->attributes['end_point_ar'];
        } else {
            return $this->attributes['end_point_ar'] ?? $this->attributes['end_point_en'];
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

    public function features(): ?BelongsToMany
    {
        return $this->belongsToMany(Feature::class, 'trip_features');

    }
    public function requirements(): ?BelongsToMany
    {
        return $this->belongsToMany(Requirement::class, 'trip_requirements');

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
    public function bookings()
    {
        return $this->hasMany(BookingTrip::class, 'trip_id');
    }

public function calculateAdminFees()
{
    $price = 0;
    $vendor = $this->vendor; // No need for first() since it's already loaded

    if ($vendor && $vendor->feeSetting) {
        $feeSetting = $vendor->feeSetting;

        $price += $feeSetting->tax_type === 'const'
            ? $feeSetting->tax_value
            : ($feeSetting->tax_value * $this->price) / 100;

        $price += $feeSetting->payment_way_type === 'const'
            ? $feeSetting->payment_way_value
            : ($feeSetting->payment_way_value * $this->price) / 100;

        $price += $feeSetting->admin_type === 'const'
            ? $feeSetting->admin_value
            : ($feeSetting->admin_value * $this->price) / 100;

        $price += $feeSetting->admin_fee_type === 'const'
            ? $feeSetting->admin_fee_value
            : ($feeSetting->admin_fee_value * $this->price) / 100;
    }

    return $price;
}

}