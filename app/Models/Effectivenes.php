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
        'description',
        'price',
        'date',
        'time',
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
    protected $appends = ['photo'];

    public function getPhotoAttribute()
    {
        return array_key_exists('cover', $this->attributes) ? ($this->attributes['cover'] != null ? asset('storage/' . $this->attributes['cover']) : null) : null;
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
