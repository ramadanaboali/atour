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
        'licence_image',
        'profile',
        'type',
        'streat',
        'postal_code',
        'national_id',
        'user_id',
        'description',
        'short_description',
        'url',
        'profission_guide',
        'job',
        'experience_info',
        'languages',
        'bank_name',
        'bank_number',
        'tax_number',
        'place_summary',
        'place_content',
        'expectations',
        'general_name',
        'nationality',
        'created_by',
        'updated_by'
    ];

    public function user(): ?BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
  
  
    public function createdBy(): ?BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function updatedBy(): ?BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function subCategory(): ?HasMany
    {
        return $this->hasMany(SubCategory::class, 'supplier_id');
    }

    protected $appends = ['photo','licence_file'];
      public function getPhotoAttribute()
    {
        return array_key_exists('image', $this->attributes) ? ($this->attributes['image'] != null ? asset('storage/suppliers/' . $this->attributes['image']) : asset('atour.jpg')) : asset('atour.jpg');
    }
    public function getLicenceFileAttribute()
    {
        return array_key_exists('licence_file', $this->attributes) ? ($this->attributes['licence_file'] != null ? asset('storage/suppliers/' . $this->attributes['licence_file']) : asset('atour.jpg')) : asset('atour.jpg');
    }
}
