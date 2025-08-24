<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleTranslation extends Model
{
    use HasFactory;
    protected $fillable = [
        'locale',
        'title',
        'description',
        'tags',
        'article_id'
    ];

}
