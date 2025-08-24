<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhyBookingTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'why_booking_id',
        'locale',
        'title',
        'description'
    ];
}
