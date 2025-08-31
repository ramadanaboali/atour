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
        'price',
        'min_people',
        'max_people',
        'free_cancelation',
        'available_days',
        'pay_later',
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
        'created_by',
        'updated_by',
    ];
    protected $casts = [
        'available_times' => 'array',
        'available_days' => 'array',
    ];
    protected $appends = [ 'photo', 'customer_price','title','description','start_point','end_point','program_time','steps_list'];



    public function getStartPointAttribute()
    {
        return $this->translations->first()->start_point ?? '';
    }
    public function getEndPointAttribute()
    {
        return $this->translations->first()->end_point ?? '';
    }
    public function getTitleAttribute()
    {
        return $this->translations->first()->title ?? '';
    }
    public function getDescriptionAttribute()
    {
        return $this->translations->first()->description ?? '';
    }
    public function getProgramTimeAttribute()
    {
        return $this->translations->first()->program_time ?? '';
    }
    public function getStepsListAttribute()
    {
        return $this->translations->first()->steps_list[request()->header('lang', 'en')] ?? [];
    }
    public function getPhotoAttribute()
    {
        return array_key_exists('cover', $this->attributes) ? ($this->attributes['cover'] != null ? asset('storage/' . $this->attributes['cover']) : null) : null;
    }


    public function getCustomerPriceAttribute()
    {
        if (!array_key_exists('price', $this->attributes)) {
            return 0;
        }

        return number_format($this->attributes['price'] + $this->calculateAdminFees(), 2, '.', '');


    }
    public function translations()
    {
        return $this->hasMany(TripTranslation::class);
    }

    public function translate($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->translations->where('locale', $locale)->first();
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

        return round($price, 2);
    }

    // Rating relationships
    public function ratings(): HasMany
    {
        return $this->hasMany(CustomerRating::class, 'service_id')->where('service_type', 'tour');
    }

    public function getAverageRatingAttribute(): float
    {
        return $this->ratings()->verified()->avg('rating') ?: 0;
    }

    public function getTotalRatingsAttribute(): int
    {
        return $this->ratings()->verified()->count();
    }

}
