<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FAQTranslation extends Model
{
    use HasFactory;

    protected $fillable = ['faq_id', 'locale', 'question', 'answer'];
}
