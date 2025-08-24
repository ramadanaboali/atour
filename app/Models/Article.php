<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

class Article extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'type',
        'start_date',
        'end_date',
        'active',
        'created_by',
        'updated_by'
    ];
     protected $table = 'articles';
    protected $appends = ['title','text','description','tags'];

   public function getTextAttribute()
    {
        return $this->translations->first()->title ?? '';
    }

    public function getTitleAttribute()
    {
        return $this->translations->first()->title ?? '';
    }
    public function getDescriptionAttribute()
    {
        return $this->translations->first()->description ?? '';
    }
    public function getTagsAttribute()
    {
        return $this->translations->first()->tags ?? '';
    }

    public function translations()
    {
        return $this->hasMany(ArticleTranslation::class);
    }

    public function translate($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->translations->where('locale', $locale)->first();
    }


    public function attachments(){
        return $this->hasMany(Attachment::class,'model_id')->where('model_type','article');
    }
}
