<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftSubCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'gift_id',
        'sub_category_id'
    ];
}

