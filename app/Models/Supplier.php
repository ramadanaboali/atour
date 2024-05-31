<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
     use HasFactory;
    use SoftDeletes;
    protected $table = 'suppliers';
    protected $fillable = [
        'tour_guid',
        'rerequest_reason',
        'type',
        'country_id',
        'city_id',
        'streat',
        'postal_code',
        'user_id',
        'description',
        'short_description',
        'url',
        'profission_guide',
        'job',
        'experience_info',
        'languages',
        'banck_name',
        'banck_number',
        'tax_number',
        'place_summary',
        'place_content',
        'expectations',
        'general_name',
        'nationality',
        'created_by',
        'updated_by'];

    public function user(): ?BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function trips(): ?HasMany
    {
        return $this->hasMany(Trip::class, 'vendor_id');
    }
    public function city(): ?BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }
    public function createdBy(): ?BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function updatedBy(): ?BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    protected $appends = ['photo'];
    public function getPhotoAttribute()
    {
        return array_key_exists('image', $this->attributes) ? ($this->attributes['image'] != null ? asset('storage/suppliers/' . $this->attributes['image']) : null) : null;
    }

}
