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
        'rerequest_reason',
        'type',
        'streat',
        'postal_code',
        'national_id',
        'user_id',
        'description',
        'short_description',
        'url',
        'job',
        'can_pay_later',
        'can_cancel',
        'pay_on_deliver',
        'bank_account',
        'bank_name',
        'bank_iban',
        'tax_number',
        'licence_file',
        'tax_file',
        'commercial_register',
        'other_files','national_id_file'
    ];

    protected $appends = ['licence_file_url','tax_file_url','commercial_register_url','other_files_url','national_id_file_url'];
   
    public function getLicenceFileUrlAttribute()
    {
        return array_key_exists('licence_file', $this->attributes) ? ($this->attributes['licence_file'] != null ? asset('storage/users/' . $this->attributes['licence_file']) : null) : null;
    }
    public function getTaxFileUrlAttribute()
    {
        return array_key_exists('tax_file', $this->attributes) ? ($this->attributes['tax_file'] != null ? asset('storage/users/' . $this->attributes['tax_file']) : null) : null;
    }
    public function getCommercialRegisterUrlAttribute()
    {
        return array_key_exists('commercial_register', $this->attributes) ? ($this->attributes['commercial_register'] != null ? asset('storage/users/' . $this->attributes['commercial_register']) : null) : null;
    }
    public function getOtherFilesUrlAttribute()
    {
        return array_key_exists('other_files', $this->attributes) ? ($this->attributes['other_files'] != null ? asset('storage/users/' . $this->attributes['other_files']) : null) : null;
    }
    public function getNationalIdFileUrlAttribute()
    {
        return array_key_exists('national_id_file', $this->attributes) ? ($this->attributes['national_id_file'] != null ? asset('storage/users/' . $this->attributes['national_id_file']) : null) : null;
    }
    public function user(): ?BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
  
    public function subCategory(): ?HasMany
    {
        return $this->hasMany(SubCategory::class, 'supplier_id');
    }

    
}
