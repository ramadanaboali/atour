<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'supplier_id',
        'transaction_id',
        'service_type',
        'service_id',
        'rating',
        'comment',
        'customer_name',
        'customer_email',
        'ip_address',
        'user_agent',
        'is_verified',
        'rated_at',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_verified' => 'boolean',
        'rated_at' => 'datetime',
    ];

    protected $dates = [
        'rated_at',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the customer who made the rating
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Get the supplier being rated
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }

    /**
     * Get the service being rated (polymorphic relationship)
     */
    public function service()
    {
        switch ($this->service_type) {
            case 'tour':
                return $this->belongsTo(Trip::class, 'service_id');
            case 'event':
                return $this->belongsTo(Event::class, 'service_id');
            case 'gift':
                return $this->belongsTo(Gift::class, 'service_id');
            default:
                return null;
        }
    }

    /**
     * Scope to get verified ratings only
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Scope to get ratings by service type
     */
    public function scopeByServiceType($query, $serviceType)
    {
        return $query->where('service_type', $serviceType);
    }

    /**
     * Scope to get ratings by supplier
     */
    public function scopeBySupplier($query, $supplierId)
    {
        return $query->where('supplier_id', $supplierId);
    }

    /**
     * Scope to get ratings by customer
     */
    public function scopeByCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    /**
     * Scope to get ratings within date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('rated_at', [$startDate, $endDate]);
    }

    /**
     * Get average rating for a supplier
     */
    public static function getSupplierAverageRating($supplierId, $serviceType = null)
    {
        $query = static::verified()->where('supplier_id', $supplierId);
        
        if ($serviceType) {
            $query->where('service_type', $serviceType);
        }
        
        return $query->avg('rating') ?: 0;
    }

    /**
     * Get rating distribution for a supplier
     */
    public static function getSupplierRatingDistribution($supplierId, $serviceType = null)
    {
        $query = static::verified()->where('supplier_id', $supplierId);
        
        if ($serviceType) {
            $query->where('service_type', $serviceType);
        }
        
        $distribution = [];
        for ($i = 1; $i <= 5; $i++) {
            $distribution[$i] = $query->where('rating', $i)->count();
        }
        
        return $distribution;
    }

    /**
     * Get total ratings count for a supplier
     */
    public static function getSupplierRatingsCount($supplierId, $serviceType = null)
    {
        $query = static::verified()->where('supplier_id', $supplierId);
        
        if ($serviceType) {
            $query->where('service_type', $serviceType);
        }
        
        return $query->count();
    }

    /**
     * Check if customer can rate this transaction
     */
    public static function canCustomerRate($customerId, $transactionId)
    {
        return !static::where('customer_id', $customerId)
            ->where('transaction_id', $transactionId)
            ->exists();
    }

    /**
     * Get rating display text
     */
    public function getRatingTextAttribute()
    {
        $ratings = [
            1 => __('ratings.very_poor'),
            2 => __('ratings.poor'),
            3 => __('ratings.average'),
            4 => __('ratings.good'),
            5 => __('ratings.excellent'),
        ];

        return $ratings[$this->rating] ?? __('ratings.unknown');
    }

    /**
     * Get star display for rating
     */
    public function getStarsAttribute()
    {
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            $stars .= $i <= $this->rating ? '★' : '☆';
        }
        return $stars;
    }

    /**
     * Get service name
     */
    public function getServiceNameAttribute()
    {
        $service = $this->service();
        if ($service) {
            return $service->name ?? $service->title ?? 'N/A';
        }
        return 'N/A';
    }
}
