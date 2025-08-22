<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripTranslation extends Model
{
    use HasFactory;
    protected $fillable = ['trip_id', 'locale', 'title', 'start_point', 'end_point', 'program_time', 'description','steps_list'];
    protected $casts = [
        'steps_list' => 'array',
    ];
}