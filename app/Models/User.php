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
        'code'=>'string'
    ];

    public const TYPE_ADMIN = 1;
    public const TYPE_CLIENT = 2;
    public const TYPE_SUPPLIER = 3;


    protected $appends = ['photo'];
    public function getPhotoAttribute()
    {
        return array_key_exists('image', $this->attributes) ? ($this->attributes['image'] != null ? asset('storage/users/' . $this->attributes['image']) : asset('atour.jpg')) : asset('atour.jpg');
    }

    public function supplier(){
        return $this->hasOne(Supplier::class,'user_id');
    }
    public function city(){
        return $this->belongsTo(City::class,'city_id');
    }
    public function country(){
        return $this->belongsTo(Country::class,'country_id');
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

    public function loginAttempts(): HasMany
    {
        return $this->hasMany(LoginAttempt::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(UserActivityLog::class);
    }

    public function generateTwoFactorCode(): string
    {
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        $this->update([
            'two_factor_code' => $code,
            'two_factor_expires_at' => now()->addMinutes(10),
            'two_factor_verified_at' => null,
        ]);

        return $code;
    }

    public function verifyTwoFactorCode(string $code): bool
    {
        if ($this->two_factor_code === $code && 
            $this->two_factor_expires_at && 
            $this->two_factor_expires_at->isFuture()) {
            
            $this->update([
                'two_factor_code' => null,
                'two_factor_expires_at' => null,
                'two_factor_verified_at' => now(),
                'failed_login_attempts' => 0,
            ]);
            
            return true;
        }
        
        return false;
    }

    public function isAccountLocked(): bool
    {
        return $this->locked_until && $this->locked_until->isFuture();
    }

    public function incrementFailedAttempts(): void
    {
        $this->increment('failed_login_attempts');
        
        if ($this->failed_login_attempts >= 5) {
            $this->update(['locked_until' => now()->addMinutes(30)]);
        }
    }

    public function resetFailedAttempts(): void
    {
        $this->update([
            'failed_login_attempts' => 0,
            'locked_until' => null,
        ]);
    }
    
}
