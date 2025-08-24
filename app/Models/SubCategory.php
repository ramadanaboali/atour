<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubCategory extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'sub_categories';
    protected $fillable = ['category','created_by','updated_by','active','parent_id'];
    protected $appends = ['title','text'];


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
        return $this->hasMany(SubCategoryTranslation::class);
    }

    public function translate($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->translations->where('locale', $locale)->first();
    }


    public function createdBy(): ?BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function updatedBy(): ?BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

}
