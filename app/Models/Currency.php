<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

class Currency extends Model
{
    use HasFactory;
       protected $fillable = [
        'code',
        'symbol',
        'rate',
    ];
}
