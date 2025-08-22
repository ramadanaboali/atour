<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftTranslation extends Model
{
    use HasFactory;
    protected $fillable = ['gift_id', 'locale', 'title', 'description', 'location'];
}
