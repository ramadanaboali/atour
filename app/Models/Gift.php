<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

class Gift extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'title_ar',
        'title_en',
        'description_en',
        'description_ar',
        'price',
        'free_cancelation',
        'pay_later',
        'active',
        'long',
        'lat',
        'location_ar',
        'location_en',
        'cover',
        'city_id',
        'vendor_id',
        'rate',
        'created_by',
        'updated_by',
    ];
    protected $appends = ['title','photo','description','location','customer_price'];

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
    public function getLocationAttribute()
    {
        if (App::isLocale('en')) {
            return $this->attributes['location_en'] ?? $this->attributes['location_ar'];
        } else {
            return $this->attributes['location_ar'] ?? $this->attributes['location_en'];
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

    public function rates(): ?HasMany
    {
        return $this->hasMany(Rate::class, 'gift_id');
    }

    public function subcategory(): ?BelongsToMany
    {
        return $this->belongsToMany(SubCategory::class, GiftSubCategory::class, 'gift_id', 'sub_category_id');
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
        return $this->hasMany(Attachment::class, 'model_id')->where('model_type', 'gift');
    }
       public function getCustomerPriceAttribute()
    {
        if (!array_key_exists('price', $this->attributes) ) {
            return 0;
        }

return $this->attributes['price'] + $this->calculateAdminFees();
        
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

    return round($price,2);
}



}
