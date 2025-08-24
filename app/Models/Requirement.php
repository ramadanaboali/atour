<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

class Requirement extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [];

    protected $appends = ['title','description','text'];

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
        return $this->hasMany(RequirementTranslation::class);
    }

    public function translate($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->translations->where('locale', $locale)->first();
    }

    public function trips(): ?BelongsToMany
    {
        return $this->belongsToMany(Trip::class, 'trip_requirements');

    }
}
