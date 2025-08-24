<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequirementTranslation extends Model
{
    use HasFactory;

    protected $fillable = ['requirement_id', 'locale', 'title', 'description'];
}
