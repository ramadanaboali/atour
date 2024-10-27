<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

class Offer extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'image',
        'active',
        'vendor_id',
        'trip_id',
    ];
    protected $table = 'offers';
    protected $appends = ['photo'];

    public function getPhotoAttribute()
    {
        return array_key_exists('image', $this->attributes) ? ($this->attributes['image'] != null ? asset('storage/' . $this->attributes['image']) : null) : null;
    }
    public function vendor(){
        return $this->belongsTo(User::class,'vendor_id');
    }
    public function trip(){
        return $this->belongsTo(Trip::class,'trip_id');
    }

}
