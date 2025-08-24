<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddTranslation extends Model
{
    use HasFactory;
    protected $fillable = [
        'locale',
        'title',
        'description',
        'add_id'
    ];
}
