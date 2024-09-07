<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripSubCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'trip_id',
        'sub_category_id'
    ];
}
