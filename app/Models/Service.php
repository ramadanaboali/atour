<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'title_en',
        'title_ar',
        'city_id',
        'description_en',
        'description_ar',
        'price',
        'start_date',
        'end_date',
        'cover',
        'active',
        'vendor_id',
        'created_by',
        'updated_by'
    ];
}
