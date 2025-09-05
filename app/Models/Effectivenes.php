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
  
        'price',
        'from_date',
        'from_time',
        'to_date',
        'to_time',
        'location',
        'type',
        'lat',
        'long',
        'is_group',
        'min_people',
        'max_people',
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
    protected $appends = ['title','photo','description','customer_price'];


    public function getPhotoAttribute()
    {
        return array_key_exists('cover', $this->attributes) ? ($this->attributes['cover'] != null ? asset('storage/' . $this->attributes['cover']) : null) : null;
    }

     public function translations()
    {
        return $this->hasMany(EffectiveneTranslation::class);
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
       public function bookings()
    {
        return $this->hasMany(BookingEffectivene::class, 'effectivene_id');
    }

       public function getCustomerPriceAttribute()
    {
        if (!array_key_exists('price', $this->attributes) ) {
            return 0;
        }

        return number_format($this->attributes['price'] + $this->calculateAdminFees(), 2, '.', '');
               
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
        return $this->hasMany(CustomerRating::class, 'service_id')->where('service_type', 'event');
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
