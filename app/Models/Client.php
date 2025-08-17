<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'users';
    protected $fillable = ['name', 'phone', 'email', 'image', 'type', 'active','address','reset_code','password','fcm_token','deleted_at','code','birthdate','joining_date_from','joining_date_to','city_id','created_by','updated_by','last_login','can_pay_later','can_cancel','nationality','ban_vendor','pay_on_deliver','status','temperory_email','bank_account','bank_name','bank_iban','tax_number','temperory_phone'];

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
        return array_key_exists('image', $this->attributes) ? ($this->attributes['image'] != null ? asset('storage/users/' . $this->attributes['image']) : asset('atour.jpg')) : asset('atour.jpg');
    }

}
