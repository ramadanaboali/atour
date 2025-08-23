<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

class Country extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'countries';
    protected $fillable = ['active'];

    protected $appends = ['title'];

    public function getTitleAttribute()
    {
        return $this->translations->first()->title ?? '';
    }
    public function translations()
    {
        return $this->hasMany(CountryTranslation::class);
    }

    public function translate($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->translations->where('locale', $locale)->first();
    }


}
