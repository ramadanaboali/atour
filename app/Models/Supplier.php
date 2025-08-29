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
        'other_files',
    ];

    public function user(): ?BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
  
    public function subCategory(): ?HasMany
    {
        return $this->hasMany(SubCategory::class, 'supplier_id');
    }

    
}
