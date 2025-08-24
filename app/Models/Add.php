<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

class Add extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [

        'location',
        'start_date',
        'end_date',

        'active',
        'image',
        'views',
        'created_by',
        'updated_by'
    ];
    protected $table = 'adds';
    protected $appends = ['title','text','description','photo'];



    public function getPhotoAttribute()
    {
        return array_key_exists('image', $this->attributes) ? ($this->attributes['image'] != null ? asset('storage/adds/' . $this->attributes['image']) : null) : null;

    }


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

    public function translations()
    {
        return $this->hasMany(AddTranslation::class);
    }

    public function translate($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->translations->where('locale', $locale)->first();
    }

}
