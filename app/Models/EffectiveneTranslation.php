<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EffectiveneTranslation extends Model
{
    use HasFactory;
    protected $fillable = [
        'effectivenes_id',
        'locale',
        'title',
        'description',
    ];
}
