<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
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
    protected $fillable = ['name', 'phone', 'email', 'image', 'description', 'type', 'active', 'passport', 'licence','isVerified','address','reset_code','password','fcm_token','deleted_at','rate','phone_code','verifaid','language'];

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


    protected $appends = ['photo'];
    public function getPhotoAttribute()
    {
        return array_key_exists('image', $this->attributes) ? ($this->attributes['image'] != null ? asset('public/storage/users/' . $this->attributes['image']) : null) : null;
    }


}
