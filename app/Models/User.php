<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasRoles;
    use SoftDeletes;

    protected $table = 'users';
    protected $fillable = ['name', 'phone', 'email', 'image', 'type', 'active','address','reset_code','password','fcm_token','deleted_at','code','birthdate','joining_date_from','joining_date_to','city_id','created_by','updated_by','last_login','can_pay_later','can_cancel','nationality','ban_vendor','pay_on_deliver','status','temperory_email','bank_account','bank_name','bank_iban','tax_number','temperory_phone'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public const TYPE_ADMIN = 1;
    public const TYPE_CLIENT = 2;
    public const TYPE_SUPPLIER = 3;


    protected $appends = ['photo'];
    public function getPhotoAttribute()
    {
        return array_key_exists('image', $this->attributes) ? ($this->attributes['image'] != null ? asset('storage/users/' . $this->attributes['image']) : null) : null;
    }

    public function supplier(){
        return $this->hasOne(Supplier::class,'user_id');
    }
    public function city(){
        return $this->hasOne(City::class,'city_id');
    }
    public function country(){
        return $this->hasOne(Country::class,'country_id');
    }
    public function feeSetting(){
        return $this->hasOne(UserFee::class,'user_id');
    }
    public function attachments(){
        return $this->hasMany(Attachment::class,'model_id')->where('model_type','user');
    }
    public function photos(){
        return $this->hasMany(Attachment::class,'model_id')->where('model_type','user')->where('title','images');
    }

      public function trips(): ?HasMany
    {
        return $this->hasMany(Trip::class, 'vendor_id');
    }
    
}
